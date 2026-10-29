@extends('layouts.admin')

@section('content')
    <div class="container-fluid user-edit-page p-0">
        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger edit-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>
                    <strong>Revisá los datos ingresados.</strong>

                    <ul class="validation-errors mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <div class="edit-card">

            <div class="section-heading">
                <span class="section-icon">
                    <i class="bi bi-person-vcard"></i>
                </span>

                <div>
                    <h5 class="mb-1">Información de la cuenta</h5>
                    <small>Actualizá la información del usuario seleccionado.</small>
                </div>

                <span class="user-reference">
                    <i class="bi bi-person-badge me-1"></i>
                    ID: {{ $user->id }}
                </span>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

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
                                class="custom-input @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" placeholder="Ingresá el nombre completo"
                                autocomplete="name" maxlength="255" required>
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
                                class="custom-input @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" placeholder="ejemplo@correo.com"
                                autocomplete="email" maxlength="255" required>
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
                    <div class="form-group password-group">
                        <label for="password" class="custom-label">
                            Nueva contraseña
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-lock input-icon"></i>

                            <input type="password" id="password" name="password"
                                class="custom-input password-input @error('password') is-invalid @enderror"
                                placeholder="Dejar en blanco para conservar la actual" autocomplete="new-password">

                            <button type="button" class="toggle-password" aria-label="Mostrar contraseña"
                                aria-pressed="false" onclick="togglePassword()">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <small class="field-hint">
                            <i class="bi bi-info-circle"></i>
                            Solo completá este campo si querés cambiar la contraseña actual.
                        </small>

                        @error('password')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-save">
                        <i class="bi bi-check-circle-fill"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .user-edit-page {
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

        /* Alertas */
        .user-edit-page .edit-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 10px;
            font-size: 14px;
        }

        .user-edit-page .edit-alert>i {
            margin-top: 2px;
            font-size: 20px;
        }

        .user-edit-page .validation-errors {
            padding-left: 20px;
        }

        .user-edit-page .validation-errors li {
            margin-bottom: 4px;
            color: #970c0c !important;
            line-height: 1.5;
        }

        /* Tarjeta */

        .user-edit-page .edit-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(25, 48, 75, 0.045);
        }

        .user-edit-page .section-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px 28px;
            border-bottom: 1px solid var(--border);
        }

        .user-edit-page .section-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 45px;
            width: 45px;
            height: 45px;
            color: var(--blue);
            background: var(--blue-light);
            border-radius: 11px;
            font-size: 21px;
        }

        .user-edit-page .section-heading h5 {
            color: var(--navy);
            font-size: 17px;
            font-weight: 750;
        }

        .user-edit-page .section-heading small {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .user-edit-page .user-reference {
            margin-left: auto;
            padding: 7px 12px;
            color: var(--navy);
            background: var(--blue-light);
            border: 1px solid #d3e0ef;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* Campos */

        .user-edit-page .form-body {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px 28px;
            padding: 28px;
        }

        .user-edit-page .form-group {
            min-width: 0;
        }

        .user-edit-page .custom-label {
            display: block;
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .user-edit-page .required-mark {
            color: #b42332;
            font-weight: 750;
        }

        .user-edit-page .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .user-edit-page .input-icon {
            position: absolute;
            left: 15px;
            z-index: 1;
            color: #7c8da2;
            font-size: 17px;
            pointer-events: none;
        }

        .user-edit-page .custom-input {
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
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .user-edit-page .custom-input::placeholder {
            color: #9aa7b6;
        }

        .user-edit-page .custom-input:hover {
            border-color: #aabbd0;
        }

        .user-edit-page .custom-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, 0.12);
        }

        .user-edit-page .custom-input.is-invalid {
            border-color: #dc3545;
        }

        .user-edit-page .custom-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .user-edit-page .password-input {
            padding-right: 48px;
        }

        .user-edit-page .toggle-password {
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

        .user-edit-page .toggle-password:hover {
            color: var(--navy);
            background: var(--blue-light);
        }

        .user-edit-page .toggle-password:focus-visible {
            outline: 2px solid var(--blue);
            outline-offset: 1px;
        }

        .user-edit-page .field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 8px;
            color: #970c0c;
            font-size: 13px;
            line-height: 1.5;
        }

        .user-edit-page .field-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .user-edit-page .field-hint {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 9px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .user-edit-page .field-hint i {
            margin-top: 1px;
        }

        .user-edit-page .form-divider {
            grid-column: 1 / -1;
            height: 1px;
            background: #e7edf4;
        }

        .user-edit-page .password-group {
            grid-column: 1 / -1;
        }

        /* Botones */

        .user-edit-page .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 28px;
            background: #f7f9fc;
            border-top: 1px solid var(--border);
        }

        .user-edit-page .btn-cancel,
        .user-edit-page .btn-save {
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

        .user-edit-page .btn-cancel {
            color: #53657b;
            background: #fff;
            border-color: #cbd6e3;
        }

        .user-edit-page .btn-cancel:hover {
            color: var(--navy);
            background: #edf2f8;
            border-color: #b8c7d9;
        }

        .user-edit-page .btn-save {
            color: #fff;
            background: var(--navy);
            border-color: var(--navy);
        }

        .user-edit-page .btn-save:hover {
            color: #fff;
            background: var(--navy-light);
            border-color: var(--navy-light);
            transform: translateY(-1px);
        }

        /* Responsive */

        @media (max-width: 768px) {
            .user-edit-page .edit-heading {
                padding: 25px;
            }

            .user-edit-page .form-body {
                gap: 23px;
                padding: 23px;
            }

            .user-edit-page .section-heading {
                padding: 20px 23px;
            }

            .user-edit-page .form-actions {
                padding: 18px 23px;
            }
        }

        @media (max-width: 576px) {
            .user-edit-page .edit-heading {
                padding: 22px 19px;
                border-radius: 12px;
            }

            .user-edit-page .edit-heading h1 {
                font-size: 24px;
            }

            .user-edit-page .edit-heading p {
                font-size: 14px;
            }

            .user-edit-page .heading-icon {
                display: none;
            }

            .user-edit-page .form-body {
                grid-template-columns: minmax(0, 1fr);
                gap: 22px;
                padding: 20px;
            }

            .user-edit-page .form-divider,
            .user-edit-page .password-group {
                grid-column: auto;
            }

            .user-edit-page .section-heading {
                flex-wrap: wrap;
                padding: 18px 20px;
            }

            .user-edit-page .section-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
                font-size: 19px;
            }

            .user-edit-page .section-heading h5 {
                font-size: 16px;
            }

            .user-edit-page .user-reference {
                margin-left: 54px;
            }

            .user-edit-page .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
                padding: 18px 20px;
            }

            .user-edit-page .btn-cancel,
            .user-edit-page .btn-save {
                width: 100%;
            }

            .user-edit-page .edit-note {
                padding: 12px 14px;
            }
        }
    </style>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const button = document.querySelector('.user-edit-page .toggle-password');
            const icon = button ? button.querySelector('i') : null;

            if (!input || !button) {
                return;
            }

            const showingPassword = input.type === 'password';

            input.type = showingPassword ? 'text' : 'password';

            button.setAttribute('aria-pressed', String(showingPassword));
            button.setAttribute(
                'aria-label',
                showingPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
            );

            if (icon) {
                icon.classList.toggle('bi-eye', !showingPassword);
                icon.classList.toggle('bi-eye-slash', showingPassword);
            }
        }
    </script>
@endsection
