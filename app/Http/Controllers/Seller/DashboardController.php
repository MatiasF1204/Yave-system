<?php

namespace App\Http\Controllers\Seller;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // Método index que retorna el dashboard para el rol de vendedor
    public function index()
    {
        return view('seller.dashboard');
    }
}
