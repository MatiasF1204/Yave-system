<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // Método index que retorna el dashboard para el rol de administrador
    public function index()
    {
        return view('admin.dashboard');
    }
}
