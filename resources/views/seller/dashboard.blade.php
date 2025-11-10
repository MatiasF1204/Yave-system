@extends('layouts.seller')

@section('content')
<div class="bg-white shadow rounded-lg p-4">
    <h2 class="text-2xl font-semibold mb-3">Bienvenido, {{ Auth::user()->name }} 🧾</h2>
    <p class="text-gray-600">Este es tu panel de ventas.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <div class="p-4 bg-blue-100 rounded-lg text-center">
            <h3 class="font-bold text-blue-800">📦 Productos</h3>
            <p>45 disponibles</p>
        </div>
        <div class="p-4 bg-green-100 rounded-lg text-center">
            <h3 class="font-bold text-green-800">💰 Ventas</h3>
            <p>+120 este mes</p>
        </div>
        <div class="p-4 bg-yellow-100 rounded-lg text-center">
            <h3 class="font-bold text-yellow-800">👥 Clientes</h3>
            <p>87 registrados</p>
        </div>
    </div>
</div>
@endsection
