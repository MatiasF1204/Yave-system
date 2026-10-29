@extends('layouts.admin')

@section('content')
    <div class="container-fluid user-create-page p-0">
        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger user-create-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>
                    <strong>Revisá los datos ingresados.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <div class="user-create-card">

            <div class="form-card-heading">
                <div class="form-heading-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>
                    <h5>Información de la cuenta</h5>
                    <p>Los campos marcados con <span class="required-mark">*</span> son obligatorios.</p>
                </div>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-body">

                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="name" class="custom-label">
                            Nombre completo
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>

                            <input type="text" id="name" name="name"
                                class="custom-input @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Ingresá el nombre completo" autocomplete="name" maxlength="255" required>
                        </div>

                        @error('name')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="custom-label">
                            Correo electrónico
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-envelope input-icon"></i>

                            <input type="email" id="email" name="email"
                                class="custom-input @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                placeholder="ejemplo@correo.com" autocomplete="email" maxlength="255" required>
                        </div>

                        @error('email')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-divider"></div>

                    {{-- Contraseña --}}
                    <div class="form-group">
                        <label for="password" class="custom-label">
                            Contraseña
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-lock input-icon"></i>

                            <input type="password" id="password" name="password"
                                class="custom-input password-input @error('password') is-invalid @enderror"
                                placeholder="Creá una contraseña" autocomplete="new-password" required>

                            <button type="button" class="toggle-password" data-target="password"
                                aria-label="Mostrar contraseña" aria-pressed="false">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <small class="field-hint">
                            <i class="bi bi-shield-lock"></i>
                            Utilizá una contraseña segura.
                        </small>

                        @error('password')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="form-group">
                        <label for="password_confirmation" class="custom-label">
                            Confirmar contraseña
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-lock-fill input-icon"></i>

                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="custom-input password-input" placeholder="Repetí la contraseña"
                                autocomplete="new-password" required>

                            <button type="button" class="toggle-password" data-target="password_confirmation"
                                aria-label="Mostrar confirmación de contraseña" aria-pressed="false">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-person-check-fill"></i>
                        Registrar usuario
                    </button>
                </div>
            </form>
        </div>

        {{-- Nota informativa --}}
        <div class="user-create-note">
            <i class="bi bi-info-circle-fill"></i>
            <span>Verificá que el correo electrónico sea correcto antes de registrar la cuenta.</span>
        </div>

    </div>

    <style>
        /* =========================================
           ESTILOS EXCLUSIVOS DE REGISTRO DE USUARIOS
           ========================================= */

        .user-create-page {
            --navy: #142b4a;
            --navy-light: #1d416b;
            --blue: #285d91;
            --blue-light: #e5edf7;
            --border: #d4deea;
            --text: #26384d;
            --muted: #718096;

            color: var(--text);
            font-size: 15px;
            padding-bottom: 25px;
        }

        .user-create-page .heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 65px;
            width: 65px;
            height: 65px;
            color: #fff;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 16px;
            font-size: 30px;
        }

        /* Alertas */

        .user-create-page .user-create-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 10px;
            font-size: 14px;
        }

        .user-create-page .user-create-alert>i {
            margin-top: 2px;
            font-size: 20px;
        }

        .user-create-page .user-create-alert ul {
            padding-left: 19px;
        }

        /* Tarjeta principal */

        .user-create-page .user-create-card {
            overflow: hidden;
            max-width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(25, 48, 75, 0.045);
        }

        .user-create-page .form-card-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 23px 28px;
            border-bottom: 1px solid var(--border);
        }

        .user-create-page .form-heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 46px;
            width: 46px;
            height: 46px;
            color: var(--blue);
            background: var(--blue-light);
            border-radius: 11px;
            font-size: 22px;
        }

        .user-create-page .form-card-heading h5 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 17px;
            font-weight: 750;
        }

        .user-create-page .form-card-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .user-create-page .required-mark {
            color: #b42332;
            font-weight: 750;
        }

        /* Campos */

        .user-create-page .form-body {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px 28px;
            padding: 28px;
        }

        .user-create-page .form-group {
            min-width: 0;
        }

        .user-create-page .custom-label {
            display: block;
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .user-create-page .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .user-create-page .input-icon {
            position: absolute;
            left: 15px;
            z-index: 1;
            color: #7c8da2;
            font-size: 17px;
            pointer-events: none;
        }

        .user-create-page .custom-input {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 48px;
            padding: 12px 15px 12px 44px;
            color: var(--text);
            background: #fff;
            border: 1px solid #cbd6e3;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .user-create-page .custom-input::placeholder {
            color: #9aa7b6;
        }

        .user-create-page .custom-input:hover {
            border-color: #aabbd0;
        }

        .user-create-page .custom-input:focus {
            background: #fff;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, 0.12);
        }

        .user-create-page .custom-input.is-invalid {
            border-color: #dc3545;
            padding-right: 42px;
        }

        .user-create-page .custom-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .user-create-page .password-input {
            padding-right: 48px;
        }

        .user-create-page .toggle-password {
            position: absolute;
            right: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            color: #718096;
            background: transparent;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            cursor: pointer;
            transition: color 0.2s, background 0.2s;
        }

        .user-create-page .toggle-password:hover {
            color: var(--navy);
            background: var(--blue-light);
        }

        .user-create-page .toggle-password:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 1px;
        }

        .user-create-page .field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 8px;
            color: #b42332;
            font-size: 13px;
            line-height: 1.5;
        }

        .user-create-page .field-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .user-create-page .field-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 9px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .user-create-page .form-divider {
            grid-column: 1 / -1;
            height: 1px;
            margin: 0;
            background: #e7edf4;
        }

        /* Botones */

        .user-create-page .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 28px;
            background: #f7f9fc;
            border-top: 1px solid var(--border);
        }

        .user-create-page .btn-cancel,
        .user-create-page .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 44px;
            padding: 11px 18px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
        }

        .user-create-page .btn-cancel {
            color: #53657b;
            background: #fff;
            border-color: #cbd6e3;
        }

        .user-create-page .btn-cancel:hover {
            color: var(--navy);
            background: #edf2f8;
            border-color: #b8c7d9;
        }

        .user-create-page .btn-submit {
            color: #fff;
            background: var(--navy);
            border-color: var(--navy);
        }

        .user-create-page .btn-submit:hover {
            color: #fff;
            background: var(--navy-light);
            border-color: var(--navy-light);
            transform: translateY(-1px);
        }

        .user-create-page .btn-cancel i,
        .user-create-page .btn-submit i {
            font-size: 16px;
        }

        /* Nota informativa */

        .user-create-page .user-create-note {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
            padding: 14px 17px;
            color: #536b87;
            background: #edf4fb;
            border: 1px solid #d7e5f3;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.6;
        }

        .user-create-page .user-create-note i {
            margin-top: 2px;
            color: var(--blue);
            font-size: 17px;
        }

        /* Adaptación a pantallas pequeñas */

        @media (max-width: 768px) {
            .user-create-page .user-create-heading {
                padding: 25px;
            }

            .user-create-page .form-body {
                gap: 23px;
                padding: 23px;
            }

            .user-create-page .form-card-heading {
                padding: 20px 23px;
            }

            .user-create-page .form-actions {
                padding: 18px 23px;
            }
        }

        @media (max-width: 576px) {
            .user-create-page .user-create-heading {
                padding: 22px 19px;
                border-radius: 12px;
            }

            .user-create-page .user-create-heading h2 {
                font-size: 24px;
            }

            .user-create-page .user-create-heading p {
                font-size: 14px;
            }

            .user-create-page .heading-icon {
                display: none;
            }

            .user-create-page .form-body {
                grid-template-columns: minmax(0, 1fr);
                gap: 22px;
                padding: 20px;
            }

            .user-create-page .form-divider {
                grid-column: auto;
            }

            .user-create-page .form-card-heading {
                padding: 18px 20px;
            }

            .user-create-page .form-heading-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
                font-size: 19px;
            }

            .user-create-page .form-card-heading h5 {
                font-size: 16px;
            }

            .user-create-page .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
                padding: 18px 20px;
            }

            .user-create-page .btn-cancel,
            .user-create-page .btn-submit {
                width: 100%;
            }

            .user-create-page .user-create-note {
                padding: 12px 14px;
            }
        }
    </style>

    <script>
        document.querySelectorAll('.user-create-page .toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);

                if (!input) {
                    return;
                }

                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(isPassword));
                button.setAttribute(
                    'aria-label',
                    isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
                );

                const icon = button.querySelector('i');

                if (icon) {
                    icon.classList.toggle('bi-eye', !isPassword);
                    icon.classList.toggle('bi-eye-slash', isPassword);
                }
            });
        });
    </script>
@endsection
