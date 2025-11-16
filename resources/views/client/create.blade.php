@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-4">Registrar nuevo cliente</h2>

    <form action="{{ route('client.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
            @error('full_name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" value="{{ old('dni') }}">
            @error('dni')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('client.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
