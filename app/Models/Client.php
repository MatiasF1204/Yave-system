<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $primaryKey = 'client_id';

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

    // Eliminación lógica (sin borrado físico)
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }
}
