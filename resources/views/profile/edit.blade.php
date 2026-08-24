@extends('layouts.admin')

@section('content')
    <div>
        {{-- Información del perfil --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-4">
            <h2 class="text-2xl font-bold mb-4">
                Mi Perfil
            </h2>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-4">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Eliminar cuenta
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div> --}}
    </div>
@endsection
