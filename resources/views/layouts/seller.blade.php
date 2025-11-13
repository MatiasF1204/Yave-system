<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panel Vendedor - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Ajustes personalizados */
        body {
            background-color: #f3f4f6;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1e293b;
            /* Azul oscuro */
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            color: #fff;
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #334155;
            color: #fff;
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
        }

        .topbar {
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }
    </style>
</head>

<body class="font-sans antialiased">
    {{-- Sidebar --}}
    @include('seller.components.sidebar')

    {{-- Contenedor principal --}}
    <div class="content">
        {{-- Topbar --}}
        <div class="topbar">
            <h1 class="text-xl font-semibold text-gray-700">Panel de Vendedor</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="text-gray-600">👋 {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Salir</button>
                </form>
            </div>
        </div>

        {{-- Contenido dinámico --}}
        <main class="mt-4">
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
