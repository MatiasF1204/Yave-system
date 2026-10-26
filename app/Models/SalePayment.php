<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalePayment extends Model
{
    protected $primaryKey = 'sale_payment_id';

    protected $fillable = [
        'sale_id',
        'payment_method_id',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // Venta a la que pertenece el pago
    public function sale(): BelongsTo
    {
        return $this->belongsTo(
            Sale::class,
            'sale_id',
            'sale_id'
        );
    }

    // Método de pago utilizado
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(
            PaymentMethod::class,
            'payment_method_id',
            'id'
        );
    }
}
