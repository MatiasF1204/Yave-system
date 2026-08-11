<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | Login</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <!-- Vite -->
    @vite(['resources/css/welcome.css'])
</head>

<body>

    <div class="container" id="container">

        <!-- LOGIN -->
        <div class="form-container sign-in">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Session status --}}
                @if (session('status'))
                    <div class="alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="brand">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Productos Yavé</span>
                </div>

                <h1>Iniciar sesión</h1>

                {{-- EMAIL --}}
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                        autofocus autocomplete="username">
                </div>


                {{-- PASSWORD --}}
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña" required
                        autocomplete="current-password">
                </div>

                <div style="margin-bottom: 10px; color:red">
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror

                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit">Ingresar</button>
            </form>
        </div>

        <!-- PANEL DERECHO -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <img src="{{ asset('images/logo-blanco.png') }}" alt="Logo Yavé" class="logo">
                    <h1>¡Bienvenido a Productos Yavé!</h1>
                    <p>
                        Sistema web de gestión de ventas, stock y clientes.
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>
