<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly MercadoPagoService $mercadoPago,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'buyer.name' => ['required', 'string', 'max:255'],
            'buyer.email' => ['required', 'email', 'max:255'],
            'buyer.phone' => ['nullable', 'string', 'max:255'],
            'buyer.notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $result = $this->mercadoPago->createOrder($data['buyer'], $data['items']);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json([
            'order_code' => $result['order']->order_code,
            'init_point' => $result['init_point'],
        ]);
    }

    public function webhook(Request $request): JsonResponse
    {
        $this->mercadoPago->handleWebhook($request->all());

        return response()->json(['status' => 'ok']);
    }
}
