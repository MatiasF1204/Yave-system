@extends('layouts.admin')

@section('content')
    <div class="container-fluid payment-method-create-page p-0">

        {{-- Mensajes de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger payment-method-alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <div>
                        <strong>No se pudo registrar el método de pago.</strong>
                        <p class="mb-1">Revisá los siguientes errores:</p>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="payment-method-card">

            {{-- Encabezado interno --}}
            <div class="payment-method-card-header">
                <div class="payment-method-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>

                <div>
                    <h2>Información del método de pago</h2>
                    <p>Completá los datos para registrar un nuevo método de pago.</p>
                </div>
            </div>

            <div class="payment-method-card-body">
                <form action="{{ route('admin.payment_methods.store') }}" method="POST" id="paymentMethodForm">
                    @csrf

                    <div class="form-section-title">
                        <i class="bi bi-pencil-square"></i>
                        Datos del método
                    </div>

                    <div class="form-group-custom">
                        <label for="name" class="form-label-custom">
                            Nombre del método
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-icon-wrapper">
                            <i class="bi bi-credit-card input-icon"></i>
                            <input type="text" name="name" id="name"
                                class="form-control-custom @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Ej.: Efectivo, Transferencia, Mercado Pago" minlength="3" maxlength="100"
                                required autofocus>
                        </div>

                        @error('name')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="form-help">
                            <i class="bi bi-info-circle"></i>
                            Ingresá un nombre claro para identificar el medio de pago.
                        </small>
                    </div>

                    <div class="form-note">
                        <i class="bi bi-lightbulb"></i>
                        <div>
                            <strong>Importante</strong>
                            <p>
                                El método registrado podrá utilizarse al cargar los pagos de las ventas,
                                según la configuración del sistema.
                            </p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.payment_methods.index') }}" class="btn-cancel-custom">
                            <i class="bi bi-arrow-left"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="btn-submit-custom" id="submitPaymentMethod">
                            <i class="bi bi-check-circle"></i>
                            Registrar método
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .payment-method-create-page {
            --pm-navy: #142b4a;
            --pm-navy-light: #1d416b;
            --pm-blue: #285d91;
            --pm-blue-light: #e5edf7;
            --pm-border: #d4deea;
            --pm-text: #26384d;
            --pm-muted: #718096;
            color: var(--pm-text);
            max-width: 100%;
            padding-bottom: 24px !important;
        }

        .payment-method-create-page .payment-method-card {
            background: #fff;
            border: 1px solid var(--pm-border);
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(20, 43, 74, 0.07);
            overflow: hidden;
            margin: 0 auto;
        }

        .payment-method-create-page .payment-method-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 25px 30px;
            background: linear-gradient(135deg, #f8fafd, #edf3fa);
            border-bottom: 1px solid var(--pm-border);
        }

        .payment-method-create-page .payment-method-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--pm-navy);
            color: #fff;
            font-size: 25px;
        }

        .payment-method-create-page .payment-method-card-header h2 {
            color: var(--pm-navy);
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0 0 5px;
        }

        .payment-method-create-page .payment-method-card-header p {
            color: var(--pm-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        .payment-method-create-page .payment-method-card-body {
            padding: 30px;
        }

        .payment-method-create-page .form-section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--pm-navy);
            font-size: 1rem;
            font-weight: 700;
            padding-bottom: 12px;
            margin-bottom: 22px;
            border-bottom: 1px solid #e8edf4;
        }

        .payment-method-create-page .form-section-title i {
            color: var(--pm-blue);
            font-size: 1.1rem;
        }

        .payment-method-create-page .form-group-custom {
            margin-bottom: 25px;
        }

        .payment-method-create-page .form-label-custom {
            display: block;
            color: var(--pm-text);
            font-size: 0.91rem;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .payment-method-create-page .required-mark {
            color: #dc3545;
            margin-left: 3px;
        }

        .payment-method-create-page .input-icon-wrapper {
            position: relative;
        }

        .payment-method-create-page .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: var(--pm-muted);
            font-size: 1.05rem;
            pointer-events: none;
        }

        .payment-method-create-page .form-control-custom {
            display: block;
            width: 100%;
            min-height: 48px;
            padding: 11px 14px 11px 43px;
            border: 1px solid var(--pm-border);
            border-radius: 8px;
            background: #fff;
            color: var(--pm-text);
            font-size: 0.94rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .payment-method-create-page .form-control-custom::placeholder {
            color: #9aa7b7;
        }

        .payment-method-create-page .form-control-custom:focus {
            border-color: var(--pm-blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, 0.12);
        }

        .payment-method-create-page .form-control-custom.is-invalid {
            border-color: #dc3545;
        }

        .payment-method-create-page .form-control-custom.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .payment-method-create-page .field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 7px;
            color: #dc3545;
            font-size: 0.85rem;
        }

        .payment-method-create-page .form-help {
            display: block;
            margin-top: 9px;
            color: var(--pm-muted);
            font-size: 0.82rem;
        }

        .payment-method-create-page .form-help i {
            margin-right: 4px;
        }

        .payment-method-create-page .form-note {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            margin-top: 8px;
            border: 1px solid #d8e5f4;
            border-radius: 9px;
            background: #f5f8fc;
            color: var(--pm-text);
        }

        .payment-method-create-page .form-note>i {
            color: var(--pm-blue);
            font-size: 1.25rem;
            margin-top: 1px;
        }

        .payment-method-create-page .form-note strong {
            display: block;
            color: var(--pm-navy);
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .payment-method-create-page .form-note p {
            margin: 0;
            color: var(--pm-muted);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .payment-method-create-page .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
            padding-top: 22px;
            border-top: 1px solid #e8edf4;
        }

        .payment-method-create-page .btn-cancel-custom,
        .payment-method-create-page .btn-submit-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 43px;
            padding: 10px 19px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
        }

        .payment-method-create-page .btn-cancel-custom {
            color: var(--pm-text);
            background: #fff;
            border: 1px solid var(--pm-border);
        }

        .payment-method-create-page .btn-cancel-custom:hover {
            background: #f1f5f9;
            border-color: #b8c7d9;
            color: var(--pm-navy);
        }

        .payment-method-create-page .btn-submit-custom {
            color: #fff;
            background: var(--pm-navy);
            border: 1px solid var(--pm-navy);
        }

        .payment-method-create-page .btn-submit-custom:hover {
            background: var(--pm-navy-light);
            border-color: var(--pm-navy-light);
            transform: translateY(-1px);
        }

        .payment-method-create-page .btn-submit-custom:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .payment-method-create-page .payment-method-alert {
            max-width: 900px;
            margin: 0 auto 18px;
            border-radius: 9px;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .payment-method-create-page .payment-method-card-header {
                padding: 20px;
                align-items: flex-start;
            }

            .payment-method-create-page .payment-method-icon {
                width: 44px;
                height: 44px;
                font-size: 21px;
            }

            .payment-method-create-page .payment-method-card-header h2 {
                font-size: 1.1rem;
            }

            .payment-method-create-page .payment-method-card-body {
                padding: 22px 18px;
            }

            .payment-method-create-page .form-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .payment-method-create-page .btn-cancel-custom,
            .payment-method-create-page .btn-submit-custom {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('paymentMethodForm');
            const nameInput = document.getElementById('name');
            const submitButton = document.getElementById('submitPaymentMethod');

            if (!form || !nameInput || !submitButton) return;

            form.addEventListener('submit', function(event) {
                const name = nameInput.value.trim();

                if (name.length < 3) {
                    event.preventDefault();
                    nameInput.value = name;
                    nameInput.classList.add('is-invalid');
                    nameInput.focus();

                    let error = form.querySelector('.client-validation-error');

                    if (!error) {
                        error = document.createElement('div');
                        error.className = 'field-error client-validation-error';
                        nameInput.closest('.form-group-custom').appendChild(error);
                    }

                    error.innerHTML =
                        '<i class="bi bi-exclamation-circle"></i>' +
                        'El nombre debe tener al menos 3 caracteres.';

                    return;
                }

                nameInput.value = name;
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>' +
                    ' Registrando...';
            });

            nameInput.addEventListener('input', function() {
                nameInput.classList.remove('is-invalid');

                const error = form.querySelector('.client-validation-error');
                if (error) error.remove();
            });
        });
    </script>
@endsection
