@extends('layouts.seller')

@section('content')
<div class="bg-white shadow rounded-lg p-4">
    <h2 class="text-2xl font-semibold mb-3">Bienvenido, {{ Auth::user()->name }} 🧾</h2>
    <p class="text-gray-600">Este es tu panel de ventas.</p>
</div>
@endsection
