<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'dni',
        'phone',
        'status',
    ];

    // Scope para filtrar activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Ventas realizadas por el cliente
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'client_id', 'id');
    }

    // Eliminación lógica (sin borrado físico)
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }
}
