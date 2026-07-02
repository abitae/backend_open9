<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Services\UniqueCodeService;
use Illuminate\Support\Facades\Http;

class MercadoPagoService
{
    public function __construct(
        private readonly UniqueCodeService $codes,
    ) {}

    /**
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     * @return array{order: Order, init_point: string}
     */
    public function createOrder(array $buyer, array $items): array
    {
        $accessToken = $this->accessToken();

        $orderItems = [];
        $total = 0.0;

        foreach ($items as $item) {
            $product = Product::query()->findOrFail($item['product_id']);
            $qty = max(1, (int) $item['quantity']);
            $subtotal = (float) $product->price * $qty;
            $total += $subtotal;

            $orderItems[] = [
                'product' => $product,
                'quantity' => $qty,
                'unit_price' => (float) $product->price,
                'subtotal' => $subtotal,
            ];
        }

        $order = Order::query()->create([
            'order_code' => $this->codes->make(Order::class, 'order_code', 'ORD'),
            'buyer_name' => $buyer['name'],
            'buyer_email' => $buyer['email'],
            'buyer_phone' => $buyer['phone'] ?? null,
            'total' => $total,
            'currency' => 'USD',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $buyer['notes'] ?? null,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create([
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        $preference = Http::withToken($accessToken)
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => $order->items->map(fn ($item): array => [
                    'title' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                    'currency_id' => 'USD',
                ])->all(),
                'payer' => [
                    'name' => $order->buyer_name,
                    'email' => $order->buyer_email,
                ],
                'external_reference' => $order->order_code,
                'back_urls' => [
                    'success' => url('/tienda?payment=success'),
                    'failure' => url('/tienda?payment=failure'),
                    'pending' => url('/tienda?payment=pending'),
                ],
                'auto_return' => 'approved',
            ]);

        if (! $preference->successful()) {
            throw new \RuntimeException('No se pudo crear la preferencia de Mercado Pago.');
        }

        $order->update([
            'mercadopago_preference_id' => $preference->json('id'),
        ]);

        return [
            'order' => $order->fresh('items'),
            'init_point' => (string) $preference->json('init_point'),
        ];
    }

    public function handleWebhook(array $payload): void
    {
        if (($payload['type'] ?? '') !== 'payment') {
            return;
        }

        $paymentId = data_get($payload, 'data.id');

        if ($paymentId === null) {
            return;
        }

        $accessToken = $this->accessToken();
        $payment = Http::withToken($accessToken)
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if (! $payment->successful()) {
            return;
        }

        $orderCode = $payment->json('external_reference');
        $status = $payment->json('status');

        $order = Order::query()->where('order_code', $orderCode)->first();

        if ($order === null) {
            return;
        }

        $order->update([
            'mercadopago_payment_id' => (string) $paymentId,
            'payment_status' => match ($status) {
                'approved' => 'paid',
                'pending', 'in_process' => 'pending',
                default => 'failed',
            },
            'status' => $status === 'approved' ? 'confirmed' : $order->status,
        ]);
    }

    private function accessToken(): string
    {
        $token = config('services.mercadopago.access_token');

        if (! is_string($token) || $token === '') {
            throw new \RuntimeException('Mercado Pago no está configurado.');
        }

        return $token;
    }
}
