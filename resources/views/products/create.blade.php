@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mx-auto">
        <h2 class="text-2xl font-bold mb-4">Registrar nuevo producto</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nombre --}}
            <div class="mb-3">
                <label class="form-label">Nombre del producto</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <label class="form-label">Descripción (opcional)</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Costo del producto --}}
            <div class="mb-3">
                <label class="form-label">Costo del producto</label>
                <input type="number" name="cost" class="form-control" value="{{ old('cost') }}" min="0"
                    step="0.01">
                @error('cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Costo del transporte --}}
            <div class="mb-3">
                <label class="form-label">Costo del transporte</label>
                <input type="number" name="transport_cost" class="form-control" value="{{ old('transport_cost') }}"
                    min="0" step="0.01">
                @error('transport_cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Precio de venta --}}
            <div class="mb-3">
                <label class="form-label">Precio de venta</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0"
                    step="0.01">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Stock --}}
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" min="0"
                    step="1">
                @error('stock')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Categorías --}}
            <div class="mb-3">
                <label class="form-label">Categorías</label>

                @foreach ($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                            id="category{{ $category->id }}"
                            {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>

                        <label class="form-check-label" for="category{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach

                @error('categories')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                @error('categories.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Imagen --}}
            <div class="mb-3">
                <label class="form-label">Imagen del producto (opcional)</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">
                    Formatos permitidos: JPG, JPEG, PNG o WEBP. Máximo 2 MB.
                </small>

                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botones --}}
            <button type="submit" class="btn btn-success">
                Registrar
            </button>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </div>
@endsection
