@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow max-w-lg mx-auto">
    <h2 class="text-2xl font-bold mb-4">Registrar nueva categoría</h2>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre de la categoría</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
