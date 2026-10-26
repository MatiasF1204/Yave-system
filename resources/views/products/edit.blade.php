@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mx-auto">

        <h2 class="text-2xl font-bold mb-4">Editar producto</h2>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-3">
                <label class="form-label">Nombre del producto</label>

                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">

                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mb-3">
                <label class="form-label">Descripción</label>

                <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>

                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Costo del producto --}}
            <div class="mb-3">
                <label class="form-label">Costo del producto</label>

                <input type="number" name="cost" class="form-control" value="{{ old('cost', $product->cost) }}"
                    min="0" step="0.01">

                @error('cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Costo del transporte --}}
            <div class="mb-3">
                <label class="form-label">Costo del transporte</label>

                <input type="number" name="transport_cost" class="form-control"
                    value="{{ old('transport_cost', $product->transport_cost) }}" min="0" step="0.01">

                @error('transport_cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Precio de venta --}}
            <div class="mb-3">
                <label class="form-label">Precio de venta</label>

                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}"
                    min="0" step="0.01">

                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Stock --}}
            <div class="mb-3">
                <label class="form-label">Stock</label>

                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}"
                    min="0" step="1">

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
                            {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>

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

            {{-- Imagen actual --}}
            <div class="mb-3">
                <label class="form-label">Imagen actual</label>

                @if ($product->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="120"
                            height="120" style="object-fit: cover; border-radius: 8px;">
                    </div>
                @else
                    <p class="text-muted">El producto no tiene una imagen.</p>
                @endif
            </div>

            {{-- Nueva imagen --}}
            <div class="mb-3">
                <label class="form-label">Cambiar imagen</label>

                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

                <small class="text-muted">
                    Dejá este campo vacío para conservar la imagen actual.
                    Formatos permitidos: JPG, JPEG, PNG o WEBP. Máximo 2 MB.
                </small>

                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botones --}}
            <button type="submit" class="btn btn-success">
                Guardar cambios
            </button>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>
@endsection
