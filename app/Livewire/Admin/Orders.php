<?php

namespace App\Livewire\Admin;

use App\Models\Order;

class Orders extends BaseResourceIndex
{
    protected string $modelClass = Order::class;

    protected string $permission = 'orders';

    protected string $title = 'Pedidos';

    protected string $description = 'Órdenes de la tienda.';

    /** @var list<string> */
    protected array $searchable = ['order_code', 'buyer_name', 'buyer_email'];

    /** @var array<string, string> */
    protected array $columns = [
        'id' => 'ID',
        'order_code' => 'Código',
        'buyer_name' => 'Cliente',
        'total' => 'Total',
        'payment_status' => 'Pago',
        'status' => 'Estado',
    ];

    /** @var array<string, array<string, mixed>> */
    protected array $fields = [
        'status' => [
            'label' => 'Estado',
            'type' => 'select',
            'options' => [
                'pending' => 'Pendiente',
                'confirmed' => 'Confirmado',
                'cancelled' => 'Cancelado',
                'completed' => 'Completado',
            ],
            'rules' => ['required', 'string'],
        ],
        'payment_status' => [
            'label' => 'Estado de pago',
            'type' => 'select',
            'options' => [
                'unpaid' => 'Sin pagar',
                'pending' => 'Pendiente',
                'paid' => 'Pagado',
                'failed' => 'Fallido',
            ],
            'rules' => ['required', 'string'],
        ],
    ];

    public function create(): void
    {
        abort(403);
    }
}
