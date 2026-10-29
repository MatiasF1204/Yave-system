@extends('layouts.admin')

@section('content')
    <div class="container-fluid payment-method-edit-page p-0">

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger payment-method-edit-alert" role="alert">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>No se pudieron guardar los cambios.</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="payment-method-edit-card">

            {{-- Encabezado de la tarjeta --}}
            <div class="payment-method-edit-heading">
                <div class="payment-method-edit-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <div>
                    <h2>Editar método de pago</h2>
                    <p>Modificá la información del medio de pago registrado.</p>
                </div>
            </div>

            <div class="payment-method-edit-body">

                <div class="payment-method-edit-section-title">
                    <i class="bi bi-info-circle"></i>
                    Información del método
                </div>

                <form action="{{ route('admin.payment_methods.update', $payment_method->id) }}" method="POST"
                    id="paymentMethodEditForm">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="payment-method-edit-field">
                        <label for="name" class="payment-method-edit-label">
                            Nombre del método
                            <span class="required-mark">*</span>
                        </label>

                        <div class="payment-method-edit-input-wrapper">
                            <i class="bi bi-credit-card payment-method-edit-input-icon"></i>
                            <input type="text" id="name" name="name"
                                class="payment-method-edit-input @error('name') is-invalid @enderror"
                                value="{{ old('name', $payment_method->name) }}"
                                placeholder="Ej.: Efectivo, Transferencia, Mercado Pago" maxlength="100" required autofocus>
                        </div>

                        @error('name')
                            <div class="payment-method-edit-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="payment-method-edit-help">
                            Ingresá el nombre con el que se identificará este medio de pago.
                        </small>
                    </div>

                    {{-- Información adicional --}}
                    <div class="payment-method-edit-info">
                        <i class="bi bi-lightbulb"></i>
                        <p>
                            Verificá que el nombre sea claro para facilitar su selección
                            al momento de registrar una venta.
                        </p>
                    </div>

                    {{-- Acciones --}}
                    <div class="payment-method-edit-actions">
                        <a href="{{ route('admin.payment_methods.index') }}"
                            class="payment-method-edit-btn payment-method-edit-btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="payment-method-edit-btn payment-method-edit-btn-primary"
                            id="savePaymentMethodBtn">
                            <i class="bi bi-check-circle"></i>
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
        .payment-method-edit-page {
            --pm-navy: #142b4a;
            --pm-navy-light: #1d416b;
            --pm-blue: #285d91;
            --pm-blue-light: #e5edf7;
            --pm-border: #d4deea;
            --pm-text: #26384d;
            --pm-muted: #718096;
            color: var(--pm-text);
        }

        .payment-method-edit-page .payment-method-edit-card {
            width: 100%;
            margin: 0 auto;
            background: #fff;
            border: 1px solid var(--pm-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(20, 43, 74, 0.07);
        }

        .payment-method-edit-page .payment-method-edit-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 24px 28px;
            background: linear-gradient(135deg, #fff 0%, #f5f8fc 100%);
            border-bottom: 1px solid var(--pm-border);
        }

        .payment-method-edit-page .payment-method-edit-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--pm-blue-light);
            color: var(--pm-blue);
            font-size: 25px;
        }

        .payment-method-edit-page .payment-method-edit-heading h2 {
            margin: 0 0 5px;
            color: var(--pm-navy);
            font-size: 21px;
            font-weight: 700;
        }

        .payment-method-edit-page .payment-method-edit-heading p {
            margin: 0;
            color: var(--pm-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .payment-method-edit-page .payment-method-edit-body {
            padding: 27px 28px;
        }

        .payment-method-edit-page .payment-method-edit-section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 25px;
            padding-bottom: 13px;
            border-bottom: 1px solid #e8edf4;
            color: var(--pm-navy);
            font-size: 15px;
            font-weight: 700;
        }

        .payment-method-edit-page .payment-method-edit-section-title i {
            color: var(--pm-blue);
            font-size: 17px;
        }

        .payment-method-edit-page .payment-method-edit-field {
            margin-bottom: 22px;
        }

        .payment-method-edit-page .payment-method-edit-label {
            display: block;
            margin-bottom: 8px;
            color: var(--pm-text);
            font-size: 13px;
            font-weight: 650;
        }

        .payment-method-edit-page .required-mark {
            color: #dc3545;
        }

        .payment-method-edit-page .payment-method-edit-input-wrapper {
            position: relative;
        }

        .payment-method-edit-page .payment-method-edit-input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: var(--pm-muted);
            font-size: 17px;
            pointer-events: none;
        }

        .payment-method-edit-page .payment-method-edit-input {
            display: block;
            width: 100%;
            min-height: 47px;
            padding: 11px 14px 11px 43px;
            border: 1px solid var(--pm-border);
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: var(--pm-text);
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
        }

        .payment-method-edit-page .payment-method-edit-input::placeholder {
            color: #a0acba;
        }

        .payment-method-edit-page .payment-method-edit-input:focus {
            border-color: var(--pm-blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, .12);
        }

        .payment-method-edit-page .payment-method-edit-input.is-invalid {
            border-color: #dc3545;
        }

        .payment-method-edit-page .payment-method-edit-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, .1);
        }

        .payment-method-edit-page .payment-method-edit-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 7px;
            color: #dc3545;
            font-size: 12px;
        }

        .payment-method-edit-page .payment-method-edit-help {
            display: block;
            margin-top: 8px;
            color: var(--pm-muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .payment-method-edit-page .payment-method-edit-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 25px 0 27px;
            padding: 13px 15px;
            border: 1px solid #dce7f4;
            border-radius: 9px;
            background: #f5f8fc;
            color: var(--pm-blue);
        }

        .payment-method-edit-page .payment-method-edit-info i {
            margin-top: 1px;
            font-size: 17px;
        }

        .payment-method-edit-page .payment-method-edit-info p {
            margin: 0;
            color: #52677f;
            font-size: 12px;
            line-height: 1.6;
        }

        .payment-method-edit-page .payment-method-edit-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e8edf4;
        }

        .payment-method-edit-page .payment-method-edit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 650;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, border-color .2s, transform .2s;
        }

        .payment-method-edit-page .payment-method-edit-btn:hover {
            transform: translateY(-1px);
        }

        .payment-method-edit-page .payment-method-edit-btn-primary {
            background: var(--pm-navy);
            border-color: var(--pm-navy);
            color: #fff;
        }

        .payment-method-edit-page .payment-method-edit-btn-primary:hover {
            background: var(--pm-navy-light);
            border-color: var(--pm-navy-light);
            color: #fff;
        }

        .payment-method-edit-page .payment-method-edit-btn-secondary {
            background: #fff;
            border-color: var(--pm-border);
            color: var(--pm-text);
        }

        .payment-method-edit-page .payment-method-edit-btn-secondary:hover {
            background: #f5f7fa;
            border-color: #b9c8d9;
            color: var(--pm-navy);
        }

        .payment-method-edit-page .payment-method-edit-alert {
            max-width: 760px;
            margin: 0 auto 18px;
            border-radius: 9px;
            font-size: 13px;
        }

        .payment-method-edit-page .payment-method-edit-alert ul {
            padding-left: 18px;
        }

        @media (max-width: 576px) {
            .payment-method-edit-page .payment-method-edit-heading {
                padding: 20px 18px;
                gap: 12px;
            }

            .payment-method-edit-page .payment-method-edit-icon {
                width: 44px;
                height: 44px;
                font-size: 21px;
            }

            .payment-method-edit-page .payment-method-edit-heading h2 {
                font-size: 18px;
            }

            .payment-method-edit-page .payment-method-edit-body {
                padding: 22px 18px;
            }

            .payment-method-edit-page .payment-method-edit-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .payment-method-edit-page .payment-method-edit-btn {
                width: 100%;
            }
        }
    </style>
@endsection
