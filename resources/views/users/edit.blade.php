@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">Editar Usuario</h2>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Contraseña --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Dejar en blanco si no desea cambiarla">
            </div>

            {{-- Mensaje de validación en caso de que la contraseña se edite por la misma --}}
            @error('password')
                <div class="text-danger mb-2">
                    {{ $message }}
                </div>
            @enderror

            {{-- Botón --}}
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
