@extends('layouts.admin')

@section('content')
    <div class="container-fluid category-create-page p-0">

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
        <div class="category-card">

            {{-- Cabecera de la tarjeta --}}
            <div class="section-heading">
                <span class="section-icon">
                    <i class="bi bi-tags-fill"></i>
                </span>

                <div>
                    <h5 class="mb-1">Registrar nueva categoría</h5>
                    <small>Completá los datos para incorporar una categoría al sistema.</small>
                </div>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="form-body">

                    {{-- Nombre de la categoría --}}
                    <div class="form-group">
                        <label for="name" class="custom-label">
                            Nombre de la categoría
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-tag input-icon"></i>

                            <input type="text" id="name" name="name"
                                class="custom-input @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Ej.: Productos de limpieza" autocomplete="off" maxlength="255" required
                                autofocus>
                        </div>

                        @error('name')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="field-hint">
                            <i class="bi bi-info-circle"></i>
                            Ingresá un nombre claro que permita identificar la categoría.
                        </small>
                    </div>

                    <div class="form-divider"></div>

                    {{-- Información --}}
                    <div class="form-info">
                        <i class="bi bi-lightbulb-fill"></i>
                        <span>
                            Las categorías permiten organizar los productos y facilitar
                            su búsqueda dentro del sistema.
                        </span>
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('admin.categories.index') }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-save">
                        <i class="bi bi-plus-circle-fill"></i>
                        Registrar categoría
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* =========================================
           ESTILOS EXCLUSIVOS DE CREACIÓN DE CATEGORÍAS
           ========================================= */

        .category-create-page {
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

        .category-create-page .create-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
        }

        .category-create-page .create-alert>i {
            margin-top: 2px;
            font-size: 20px;
        }

        .category-create-page .validation-errors {
            padding-left: 20px;
        }

        .category-create-page .validation-errors li {
            margin-bottom: 4px;
            color: #970c0c !important;
            line-height: 1.5;
        }

        /* Tarjeta */

        .category-create-page .category-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(25, 48, 75, 0.045);
        }

        .category-create-page .section-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 23px 28px;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .category-create-page .section-icon {
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

        .category-create-page .section-heading h5 {
            color: var(--navy);
            font-size: 17px;
            font-weight: 750;
        }

        .category-create-page .section-heading small {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        /* Campos */

        .category-create-page .form-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 25px;
            padding: 28px;
        }

        .category-create-page .form-group {
            min-width: 0;
            max-width: 760px;
        }

        .category-create-page .custom-label {
            display: block;
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .category-create-page .required-mark {
            color: #b42332;
            font-weight: 750;
        }

        .category-create-page .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .category-create-page .input-icon {
            position: absolute;
            left: 15px;
            z-index: 1;
            color: #7c8da2;
            font-size: 17px;
            pointer-events: none;
        }

        .category-create-page .custom-input {
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

        .category-create-page .custom-input::placeholder {
            color: #9aa7b6;
        }

        .category-create-page .custom-input:hover {
            border-color: #aabbd0;
        }

        .category-create-page .custom-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, 0.12);
        }

        .category-create-page .custom-input.is-invalid {
            border-color: #dc3545;
        }

        .category-create-page .custom-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .category-create-page .field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 8px;
            color: #970c0c;
            font-size: 13px;
            line-height: 1.5;
        }

        .category-create-page .field-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .category-create-page .field-hint {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 9px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .category-create-page .field-hint i {
            margin-top: 1px;
        }

        /* Separador e información */

        .category-create-page .form-divider {
            height: 1px;
            background: #e7edf4;
        }

        .category-create-page .form-info {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: -8px;
            color: #536b87;
            font-size: 13px;
            line-height: 1.6;
        }

        .category-create-page .form-info>i {
            margin-top: 2px;
            color: var(--blue);
            font-size: 16px;
        }

        /* Botones */

        .category-create-page .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 28px;
            background: #f7f9fc;
            border-top: 1px solid var(--border);
        }

        .category-create-page .btn-cancel,
        .category-create-page .btn-save {
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

        .category-create-page .btn-cancel {
            color: #53657b;
            background: #fff;
            border-color: #cbd6e3;
        }

        .category-create-page .btn-cancel:hover {
            color: var(--navy);
            background: #edf2f8;
            border-color: #b8c7d9;
        }

        .category-create-page .btn-save {
            color: #fff;
            background: var(--navy);
            border-color: var(--navy);
        }

        .category-create-page .btn-save:hover {
            color: #fff;
            background: var(--navy-light);
            border-color: var(--navy-light);
            transform: translateY(-1px);
        }

        .category-create-page .btn-cancel:focus-visible,
        .category-create-page .btn-save:focus-visible {
            outline: 3px solid rgba(40, 93, 145, 0.3);
            outline-offset: 2px;
        }

        /* Responsive */

        @media (max-width: 768px) {
            .category-create-page .form-body {
                gap: 23px;
                padding: 23px;
            }

            .category-create-page .section-heading {
                padding: 20px 23px;
            }

            .category-create-page .form-actions {
                padding: 18px 23px;
            }
        }

        @media (max-width: 576px) {
            .category-create-page .form-body {
                gap: 22px;
                padding: 20px;
            }

            .category-create-page .section-heading {
                align-items: flex-start;
                padding: 18px 20px;
            }

            .category-create-page .section-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
                font-size: 19px;
            }

            .category-create-page .section-heading h5 {
                font-size: 16px;
            }

            .category-create-page .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
                padding: 18px 20px;
            }

            .category-create-page .btn-cancel,
            .category-create-page .btn-save {
                width: 100%;
            }
        }
    </style>
@endsection
