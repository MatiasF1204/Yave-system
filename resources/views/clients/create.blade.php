@extends('layouts.admin')

@section('content')
    <div class="container-fluid client-create-page p-0">

        {{-- Errores generales --}}
        @if ($errors->any())
            <div class="alert alert-danger create-alert" role="alert">
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

        {{-- Tarjeta del formulario --}}
        <div class="client-card">

            {{-- Cabecera de la tarjeta --}}
            <div class="section-heading">
                <span class="section-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </span>

                <div>
                    <h5 class="mb-1">Registrar nuevo cliente</h5>
                    <small>Completá los datos para incorporar un cliente al sistema.</small>
                </div>
            </div>

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="form-body">

                    {{-- Nombre completo --}}
                    <div class="form-group full-width">
                        <label for="full_name" class="custom-label">
                            Nombre completo
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>

                            <input type="text" id="full_name" name="full_name"
                                class="custom-input @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}"
                                placeholder="Ingresá el nombre y apellido" autocomplete="name" maxlength="255" required>
                        </div>

                        @error('full_name')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- DNI --}}
                    <div class="form-group">
                        <label for="dni" class="custom-label">
                            DNI
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-card-text input-icon"></i>

                            <input type="text" id="dni" name="dni"
                                class="custom-input @error('dni') is-invalid @enderror" value="{{ old('dni') }}"
                                placeholder="Ingresá el número de DNI" inputmode="numeric" autocomplete="off" required>
                        </div>

                        @error('dni')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="form-group">
                        <label for="phone" class="custom-label">
                            Teléfono
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-telephone input-icon"></i>

                            <input type="tel" id="phone" name="phone"
                                class="custom-input @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                placeholder="Ej.: 2964 123456" autocomplete="tel">
                        </div>

                        @error('phone')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-info">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>
                            Los campos marcados con <strong>*</strong> son obligatorios.
                            Verificá que el DNI sea correcto antes de registrar el cliente.
                        </span>
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('clients.index') }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-save">
                        <i class="bi bi-person-check-fill"></i>
                        Registrar cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* ========================================
           ESTILOS EXCLUSIVOS DE CREACIÓN DE CLIENTES
           ======================================== */

        .client-create-page {
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

        /* Alertas de validación */

        .client-create-page .create-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
        }

        .client-create-page .create-alert>i {
            margin-top: 2px;
            font-size: 20px;
        }

        .client-create-page .validation-errors {
            padding-left: 20px;
        }

        .client-create-page .validation-errors li {
            margin-bottom: 4px;
            color: #970c0c !important;
            line-height: 1.5;
        }

        /* Tarjeta */

        .client-create-page .client-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(25, 48, 75, 0.045);
        }

        .client-create-page .section-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 23px 28px;
            border-bottom: 1px solid var(--border);
            background: #fff;
        }

        .client-create-page .section-icon {
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

        .client-create-page .section-heading h5 {
            color: var(--navy);
            font-size: 17px;
            font-weight: 750;
        }

        .client-create-page .section-heading small {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        /* Campos */

        .client-create-page .form-body {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px 28px;
            padding: 28px;
        }

        .client-create-page .form-group {
            min-width: 0;
        }

        .client-create-page .full-width {
            grid-column: 1 / -1;
        }

        .client-create-page .custom-label {
            display: block;
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .client-create-page .required-mark {
            color: #b42332;
            font-weight: 750;
        }

        .client-create-page .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .client-create-page .input-icon {
            position: absolute;
            left: 15px;
            z-index: 1;
            color: #7c8da2;
            font-size: 17px;
            pointer-events: none;
        }

        .client-create-page .custom-input {
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

        .client-create-page .custom-input::placeholder {
            color: #9aa7b6;
        }

        .client-create-page .custom-input:hover {
            border-color: #aabbd0;
        }

        .client-create-page .custom-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, 0.12);
        }

        .client-create-page .custom-input.is-invalid {
            border-color: #dc3545;
        }

        .client-create-page .custom-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .client-create-page .field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 8px;
            color: #970c0c;
            font-size: 13px;
            line-height: 1.5;
        }

        .client-create-page .field-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Separador e información */

        .client-create-page .form-divider {
            grid-column: 1 / -1;
            height: 1px;
            background: #e7edf4;
        }

        .client-create-page .form-info {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: -8px;
            color: #536b87;
            font-size: 13px;
            line-height: 1.6;
        }

        .client-create-page .form-info>i {
            margin-top: 2px;
            color: var(--blue);
            font-size: 16px;
        }

        .client-create-page .form-info strong {
            color: #b42332;
        }

        /* Botones */

        .client-create-page .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 28px;
            background: #f7f9fc;
            border-top: 1px solid var(--border);
        }

        .client-create-page .btn-cancel,
        .client-create-page .btn-save {
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

        .client-create-page .btn-cancel {
            color: #53657b;
            background: #fff;
            border-color: #cbd6e3;
        }

        .client-create-page .btn-cancel:hover {
            color: var(--navy);
            background: #edf2f8;
            border-color: #b8c7d9;
        }

        .client-create-page .btn-save {
            color: #fff;
            background: var(--navy);
            border-color: var(--navy);
        }

        .client-create-page .btn-save:hover {
            color: #fff;
            background: var(--navy-light);
            border-color: var(--navy-light);
            transform: translateY(-1px);
        }

        .client-create-page .btn-cancel:focus-visible,
        .client-create-page .btn-save:focus-visible {
            outline: 3px solid rgba(40, 93, 145, 0.3);
            outline-offset: 2px;
        }

        /* Responsive */

        @media (max-width: 768px) {
            .client-create-page .form-body {
                gap: 23px;
                padding: 23px;
            }

            .client-create-page .section-heading {
                padding: 20px 23px;
            }

            .client-create-page .form-actions {
                padding: 18px 23px;
            }
        }

        @media (max-width: 576px) {
            .client-create-page .form-body {
                grid-template-columns: minmax(0, 1fr);
                gap: 22px;
                padding: 20px;
            }

            .client-create-page .full-width,
            .client-create-page .form-divider,
            .client-create-page .form-info {
                grid-column: auto;
            }

            .client-create-page .section-heading {
                align-items: flex-start;
                padding: 18px 20px;
            }

            .client-create-page .section-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
                font-size: 19px;
            }

            .client-create-page .section-heading h5 {
                font-size: 16px;
            }

            .client-create-page .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
                padding: 18px 20px;
            }

            .client-create-page .btn-cancel,
            .client-create-page .btn-save {
                width: 100%;
            }
        }
    </style>
@endsection
