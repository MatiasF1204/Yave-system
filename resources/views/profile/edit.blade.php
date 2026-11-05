@extends('layouts.admin')

@section('content')
    <div class="profile-page">

        {{-- Encabezado --}}
        <div class="profile-heading">
            <div class="profile-heading-icon">
                <i class="bi bi-person-gear"></i>
            </div>

            <div>
                <h1>Mi perfil</h1>
                <p>Administrá tus datos personales y la seguridad de tu cuenta.</p>
            </div>
        </div>

        {{-- Mensajes generales --}}
        @if (session('success'))
            <div class="profile-alert profile-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->has('profile'))
            <div class="profile-alert profile-alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ $errors->first('profile') }}</span>
            </div>
        @endif

        <div class="profile-grid">

            {{-- Información personal --}}
            <section class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h2>Información personal</h2>
                        <p>Actualizá tu nombre y correo electrónico.</p>
                    </div>
                </div>

                <div class="profile-card-body">

                    <div class="profile-avatar">
                        <div class="profile-avatar-circle">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div>
                            <strong>{{ $user->name }}</strong>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="profile-field">
                            <label for="name">Nombre completo</label>

                            <div class="profile-input-wrapper">
                                <i class="bi bi-person"></i>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                                    placeholder="Ingresá tu nombre" maxlength="255" autocomplete="name" required autofocus
                                    class="@error('name') is-invalid @enderror">
                            </div>

                            @error('name')
                                <span class="profile-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-field">
                            <label for="email">Correo electrónico</label>

                            <div class="profile-input-wrapper">
                                <i class="bi bi-envelope"></i>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                                    placeholder="ejemplo@correo.com" maxlength="255" autocomplete="username" required
                                    class="@error('email') is-invalid @enderror">
                            </div>

                            @error('email')
                                <span class="profile-field-error">{{ $message }}</span>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="profile-verification">
                                    <i class="bi bi-exclamation-triangle-fill"></i>

                                    <div>
                                        <p>Tu correo electrónico todavía no está verificado.</p>

                                        <button type="submit" form="send-verification" class="profile-link-button">
                                            Reenviar correo de verificación
                                        </button>

                                        @if (session('status') === 'verification-link-sent')
                                            <span class="profile-verification-success">
                                                Se envió un nuevo enlace de verificación.
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="profile-form-footer">
                            <button type="submit" class="profile-button">
                                <i class="bi bi-check2-circle"></i>
                                Guardar cambios
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="profile-saved-message">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Cambios guardados
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </section>

            {{-- Seguridad y contraseña --}}
            <section class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-icon profile-card-icon-security">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <div>
                        <h2>Seguridad de la cuenta</h2>
                        <p>Cambiá tu contraseña para proteger el acceso.</p>
                    </div>
                </div>

                <div class="profile-card-body">

                    <div class="profile-security-notice">
                        <i class="bi bi-shield-check"></i>
                        <p>Usá una contraseña segura que no utilices en otros sitios.</p>
                    </div>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="profile-field">
                            <label for="update_password_current_password">
                                Contraseña actual
                            </label>

                            <div class="profile-input-wrapper">
                                <i class="bi bi-lock"></i>

                                <input id="update_password_current_password" name="current_password" type="password"
                                    placeholder="Ingresá tu contraseña actual" autocomplete="current-password" required
                                    class="@if ($errors->updatePassword->has('current_password')) is-invalid @endif">

                                <button type="button" class="profile-password-toggle"
                                    data-target="update_password_current_password" aria-label="Mostrar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            @if ($errors->updatePassword->has('current_password'))
                                <span class="profile-field-error">
                                    {{ $errors->updatePassword->first('current_password') }}
                                </span>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="update_password_password">
                                Nueva contraseña
                            </label>

                            <div class="profile-input-wrapper">
                                <i class="bi bi-key"></i>

                                <input id="update_password_password" name="password" type="password"
                                    placeholder="Ingresá una nueva contraseña" autocomplete="new-password" required
                                    class="@if ($errors->updatePassword->has('password')) is-invalid @endif">

                                <button type="button" class="profile-password-toggle"
                                    data-target="update_password_password" aria-label="Mostrar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            @if ($errors->updatePassword->has('password'))
                                <span class="profile-field-error">
                                    {{ $errors->updatePassword->first('password') }}
                                </span>
                            @endif
                        </div>

                        <div class="profile-field">
                            <label for="update_password_password_confirmation">
                                Confirmar nueva contraseña
                            </label>

                            <div class="profile-input-wrapper">
                                <i class="bi bi-shield-check"></i>

                                <input id="update_password_password_confirmation" name="password_confirmation"
                                    type="password" placeholder="Repetí la nueva contraseña" autocomplete="new-password"
                                    required>

                                <button type="button" class="profile-password-toggle"
                                    data-target="update_password_password_confirmation" aria-label="Mostrar contraseña">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            @if ($errors->updatePassword->has('password_confirmation'))
                                <span class="profile-field-error">
                                    {{ $errors->updatePassword->first('password_confirmation') }}
                                </span>
                            @endif
                        </div>

                        <div class="profile-form-footer">
                            <button type="submit" class="profile-button">
                                <i class="bi bi-shield-check"></i>
                                Actualizar contraseña
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="profile-saved-message">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Contraseña actualizada
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar u ocultar contraseñas.
            document.querySelectorAll('.profile-password-toggle').forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = document.getElementById(this.dataset.target);

                    if (!input) return;

                    const icon = this.querySelector('i');
                    const showingPassword = input.type === 'password';

                    input.type = showingPassword ? 'text' : 'password';

                    if (icon) {
                        icon.classList.toggle('bi-eye', !showingPassword);
                        icon.classList.toggle('bi-eye-slash', showingPassword);
                    }

                    this.setAttribute(
                        'aria-label',
                        showingPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'
                    );
                });
            });

            // Ocultar los mensajes de confirmación después de unos segundos.
            document.querySelectorAll('.profile-saved-message').forEach(function(message) {
                window.setTimeout(function() {
                    message.style.transition = 'opacity .35s ease';
                    message.style.opacity = '0';

                    window.setTimeout(function() {
                        message.remove();
                    }, 400);
                }, 3500);
            });

            // Desplazarse al primer campo con error.
            const firstError = document.querySelector(
                '.profile-input-wrapper input.is-invalid'
            );

            if (firstError) {
                firstError.focus({
                    preventScroll: true
                });
            }
        });
    </script>
@endsection
