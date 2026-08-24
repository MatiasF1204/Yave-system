@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mx-auto">
        <h2 class="text-2xl font-bold mb-4">Registrar nuevo usuario</h2>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Nombre --}}
            <div class="mb-3">
                <label class="form-label">Nombre completo</label>

                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>

                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>

                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>

                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label class="form-label">Contraseña</label>

                <input type="password" name="password" class="form-control" required>

                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirmar contraseña --}}
            <div class="mb-3">
                <label class="form-label">Confirmar contraseña</label>

                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">
                Registrar usuario
            </button>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>
    </div>
@endsection
