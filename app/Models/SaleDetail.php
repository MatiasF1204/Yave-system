<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    protected $primaryKey = 'sale_detail_id';

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_cost',
        'unit_transport_cost',
        'unit_total_cost',
        'unit_price',
        'unit_profit',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'unit_transport_cost' => 'decimal:2',
            'unit_total_cost' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'unit_profit' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // Venta a la que pertenece el detalle
    public function sale(): BelongsTo
    {
        return $this->belongsTo(
            Sale::class,
            'sale_id',
            'sale_id'
        );
    }

    // Producto vendido
    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id',
            'id'
        );
    }
}
