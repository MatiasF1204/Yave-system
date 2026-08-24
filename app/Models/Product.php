<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cost',
        'transport_cost',
        'total_cost',
        'price',
        'profit',
        'stock',
        'image',
        'status',
    ];

    // Relación: un producto puede pertenecer a muchas categorías
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    // Scope para filtrar productos activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Desactivación lógica
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }
}