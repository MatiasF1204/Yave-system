<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    // Relación: una categoría puede tener muchos productos
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }

    // Scope para filtrar activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Eliminación lógica (sin borrado físico)
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }
}