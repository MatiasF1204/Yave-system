
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#68a3b5">

    <title>Productos YAVÉ | Iniciar sesión</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo-color.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @vite(['resources/css/welcome.css'])
</head>

<body>
    <main class="login-page">
        <div class="login-container">

            {{-- Panel de inicio de sesión --}}
            <section class="login-form-section">
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <a href="{{ url('/') }}" class="brand" aria-label="Productos YAVÉ">
                        <span class="brand-icon">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </span>
                        <span class="brand-name">Productos <strong>YAVÉ</strong></span>
                    </a>

                    <div class="form-heading">
                        <span class="eyebrow">BIENVENIDO DE NUEVO</span>
                        <h1>Iniciar sesión</h1>
                        <p>Ingresá tus datos para continuar.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert-success" role="status">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert-error" role="alert">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="input-field">
                        <label for="email">Correo electrónico</label>
                        <div class="input-wrapper">
                            <i class="fa-regular fa-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="nombre@correo.com"
                                required
                                autofocus
                                autocomplete="username">
                        </div>
                    </div>

                    <div class="input-field">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Ingresá tu contraseña"
                                required
                                autocomplete="current-password">
                            <button type="button" class="password-toggle"
                                id="togglePassword" aria-label="Mostrar contraseña"
                                aria-pressed="false">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-button">
                        Ingresar
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <p class="form-footer">
                        <i class="fa-solid fa-shield-halved"></i>
                        Acceso exclusivo para usuarios autorizados
                    </p>
                </form>
            </section>

            {{-- Panel de marca --}}
            <aside class="brand-panel">
                <div class="decor-circle decor-circle-one"></div>
                <div class="decor-circle decor-circle-two"></div>

                <div class="brand-panel-content">
                    <img
                        src="{{ asset('images/logo-blanco.png') }}"
                        alt="Logo de Productos YAVÉ"
                        class="brand-logo">

                    <span class="panel-label">PRODUCTOS YAVÉ</span>
                    <h2>Todo en limpieza.</h2>
                    <p>Ventas y stock, en un solo lugar.</p>

                    <div class="panel-decoration">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>

                <span class="panel-bottom-text">
                    Gestión simple, todos los días.
                </span>
            </aside>

        </div>
    </main>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            this.innerHTML = isPassword
                ? '<i class="fa-regular fa-eye-slash"></i>'
                : '<i class="fa-regular fa-eye"></i>';

            this.setAttribute(
                'aria-label',
                isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
            );

            this.setAttribute('aria-pressed', String(isPassword));
        });
    </script>
</body>

</html>

