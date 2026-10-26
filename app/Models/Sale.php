<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'client_id',
        'user_id',
        'total',
        'observations',
        'sale_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'sale_date' => 'datetime',
        ];
    }

    // Cliente que realizó la compra
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    // Usuario que registró la venta
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Pagos asociados a la venta
    public function payments(): HasMany
    {
        return $this->hasMany(
            SalePayment::class,
            'sale_id',
            'sale_id'
        );
    }

    // Productos incluidos en la venta
    // public function details(): HasMany
    // {
    //     return $this->hasMany(
    //         SaleDetail::class,
    //         'sale_id',
    //         'sale_id'
    //     );
    // }
}
