@extends('layouts.admin')

@section('content')
    <div class="container-fluid product-edit-page p-0">

        {{-- Errores generales --}}
        @if ($errors->any())
            <div class="product-edit-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Hay algunos datos que necesitan atención.</strong>
                    <p>Revisá los campos marcados antes de guardar los cambios.</p>
                </div>
            </div>
        @endif

        <div class="product-edit-card">

            {{-- Encabezado interno --}}
            <div class="product-edit-heading">
                <div class="product-edit-heading-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h2>Editar producto</h2>
                    <p>Modificá la información del producto y guardá los cambios.</p>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                id="productEditForm">

                @csrf
                @method('PUT')

                {{-- Información básica --}}
                <div class="product-edit-section">
                    <div class="product-edit-section-title">
                        <i class="bi bi-box-seam"></i>
                        <span>Información del producto</span>
                    </div>

                    <div class="product-edit-grid">

                        {{-- Nombre --}}
                        <div class="product-edit-field">
                            <label for="name" class="product-edit-label">
                                Nombre del producto <span class="required">*</span>
                            </label>

                            <input type="text" name="name" id="name"
                                class="product-edit-input @error('name') is-invalid @enderror"
                                value="{{ old('name', $product->name) }}" placeholder="Ej.: Desinfectante lavanda 1 litro"
                                maxlength="150" required>

                            @error('name')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Stock --}}
                        <div class="product-edit-field">
                            <label for="stock" class="product-edit-label">
                                Stock disponible <span class="required">*</span>
                            </label>

                            <div class="product-edit-input-icon">
                                <i class="bi bi-boxes"></i>
                                <input type="number" name="stock" id="stock"
                                    class="product-edit-input @error('stock') is-invalid @enderror"
                                    value="{{ old('stock', $product->stock) }}" min="0" step="1" required>
                            </div>

                            @error('stock')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="product-edit-field product-edit-full">
                            <label for="description" class="product-edit-label">
                                Descripción
                            </label>

                            <textarea name="description" id="description"
                                class="product-edit-input product-edit-textarea @error('description') is-invalid @enderror" rows="4"
                                maxlength="1000" placeholder="Describí las características del producto...">{{ old('description', $product->description) }}</textarea>

                            <div class="product-edit-help">Máximo 1000 caracteres.</div>

                            @error('description')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="product-edit-divider"></div>

                {{-- Precios y costos --}}
                <div class="product-edit-section">
                    <div class="product-edit-section-title">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Costos y precio de venta</span>
                    </div>

                    <div class="product-edit-grid product-edit-prices">

                        {{-- Costo --}}
                        <div class="product-edit-field">
                            <label for="cost" class="product-edit-label">
                                Costo del producto <span class="required">*</span>
                            </label>

                            <div class="product-edit-input-icon">
                                <i class="bi bi-cash-stack"></i>
                                <input type="number" name="cost" id="cost"
                                    class="product-edit-input @error('cost') is-invalid @enderror"
                                    value="{{ old('cost', $product->cost) }}" min="0" step="0.01" required>
                            </div>

                            @error('cost')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Transporte --}}
                        <div class="product-edit-field">
                            <label for="transport_cost" class="product-edit-label">
                                Costo del transporte <span class="required">*</span>
                            </label>

                            <div class="product-edit-input-icon">
                                <i class="bi bi-truck"></i>
                                <input type="number" name="transport_cost" id="transport_cost"
                                    class="product-edit-input @error('transport_cost') is-invalid @enderror"
                                    value="{{ old('transport_cost', $product->transport_cost) }}" min="0"
                                    step="0.01" required>
                            </div>

                            @error('transport_cost')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Precio de venta --}}
                        <div class="product-edit-field">
                            <label for="price" class="product-edit-label">
                                Precio de venta <span class="required">*</span>
                            </label>

                            <div class="product-edit-input-icon">
                                <i class="bi bi-tag"></i>
                                <input type="number" name="price" id="price"
                                    class="product-edit-input @error('price') is-invalid @enderror"
                                    value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                            </div>

                            @error('price')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- Resumen dinámico --}}
                    <div class="product-edit-cost-summary">
                        <div class="product-edit-summary-item">
                            <span><i class="bi bi-calculator"></i> Costo total estimado</span>
                            <strong id="editTotalCost">$0,00</strong>
                        </div>

                        <div class="product-edit-summary-item">
                            <span><i class="bi bi-graph-up-arrow"></i> Ganancia estimada</span>
                            <strong id="editProfit">$0,00</strong>
                        </div>

                        <p>
                            <i class="bi bi-info-circle"></i>
                            Los importes se actualizan al modificar los valores. El cálculo definitivo
                            se realiza en el servidor.
                        </p>
                    </div>
                </div>

                <div class="product-edit-divider"></div>

                {{-- Categorías --}}
                <div class="product-edit-section">
                    <div class="product-edit-section-title">
                        <i class="bi bi-tags"></i>
                        <span>Categorías del producto</span>
                    </div>

                    <p class="product-edit-section-description">
                        Seleccioná una o varias categorías para clasificar el producto.
                    </p>

                    <div class="product-edit-category-grid">
                        @forelse ($categories as $category)
                            <label class="product-edit-category-option" for="editCategory{{ $category->id }}">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    id="editCategory{{ $category->id }}"
                                    {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>

                                <span class="product-edit-category-check">
                                    <i class="bi bi-check-lg"></i>
                                </span>

                                <span class="product-edit-category-name">
                                    {{ $category->name }}
                                </span>
                            </label>
                        @empty
                            <div class="product-edit-empty-categories">
                                <i class="bi bi-tags"></i>
                                <span>No hay categorías disponibles para seleccionar.</span>
                            </div>
                        @endforelse
                    </div>

                    @error('categories')
                        <small class="product-edit-error">{{ $message }}</small>
                    @enderror

                    @error('categories.*')
                        <small class="product-edit-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="product-edit-divider"></div>

                {{-- Imagen --}}
                <div class="product-edit-section">
                    <div class="product-edit-section-title">
                        <i class="bi bi-image"></i>
                        <span>Imagen del producto</span>
                    </div>

                    <p class="product-edit-section-description">
                        Podés conservar la imagen actual o seleccionar una nueva.
                    </p>

                    <div class="product-edit-image-layout">

                        {{-- Imagen actual --}}
                        <div class="product-edit-current-image">
                            <span class="product-edit-image-label">Imagen actual</span>

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="Imagen actual de {{ $product->name }}" id="editCurrentImage">

                                <span class="product-edit-image-caption">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Imagen guardada
                                </span>
                            @else
                                <div class="product-edit-no-image" id="editCurrentImagePlaceholder">
                                    <i class="bi bi-image"></i>
                                    <span>Sin imagen</span>
                                </div>
                            @endif
                        </div>

                        {{-- Nueva imagen --}}
                        <div class="product-edit-field">
                            <label for="image" class="product-edit-label">
                                Cambiar imagen
                            </label>

                            <label for="image" class="product-edit-upload" id="editUploadLabel">
                                <span class="product-edit-upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </span>

                                <strong>Seleccioná una nueva imagen</strong>
                                <span>JPG, JPEG, PNG o WEBP · Máximo 2 MB</span>

                                <span class="product-edit-upload-button">
                                    <i class="bi bi-folder2-open"></i>
                                    Elegir archivo
                                </span>
                            </label>

                            <input type="file" name="image" id="image" class="product-edit-file-input"
                                accept=".jpg,.jpeg,.png,.webp">

                            <div class="product-edit-new-image-preview" id="editNewImagePreview" hidden>
                                <img src="" alt="Vista previa de la nueva imagen" id="editNewImage">

                                <div class="product-edit-preview-info">
                                    <strong id="editImageName"></strong>
                                    <span id="editImageSize"></span>
                                </div>

                                <button type="button" id="editRemoveImage" class="product-edit-remove-image">
                                    <i class="bi bi-trash3"></i>
                                    Quitar nueva imagen
                                </button>
                            </div>

                            <div class="product-edit-image-note">
                                <i class="bi bi-info-circle"></i>
                                Si dejás este campo vacío, se conservará la imagen actual.
                            </div>

                            <div class="product-edit-error" id="editImageError" role="alert"></div>

                            @error('image')
                                <small class="product-edit-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="product-edit-actions">
                    <a href="{{ route('products.index') }}" class="product-edit-btn product-edit-btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="product-edit-btn product-edit-btn-primary">
                        <i class="bi bi-check2-circle"></i>
                        Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

    <style>
        .product-edit-page {
            --navy: #142b4a;
            --navy-light: #1d416b;
            --blue: #285d91;
            --blue-light: #e5edf7;
            --border: #d4deea;
            --text: #26384d;
            --muted: #718096;
            color: var(--text);
            font-size: 14px;
        }

        .product-edit-page .product-edit-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(20, 43, 74, .06);
            overflow: hidden;
        }

        .product-edit-page .product-edit-heading {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #fff, #f7faff);
        }

        .product-edit-page .product-edit-heading-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: grid;
            place-items: center;
            background: var(--blue-light);
            color: var(--navy);
            border-radius: 13px;
            font-size: 22px;
        }

        .product-edit-page .product-edit-heading h2 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 21px;
            font-weight: 750;
        }

        .product-edit-page .product-edit-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .product-edit-page .product-edit-section {
            padding: 25px 28px;
        }

        .product-edit-page .product-edit-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: var(--navy);
            font-size: 15px;
            font-weight: 750;
        }

        .product-edit-page .product-edit-section-title>i {
            color: var(--blue);
            font-size: 18px;
        }

        .product-edit-page .product-edit-section-description {
            margin: -10px 0 16px;
            color: var(--muted);
            font-size: 13px;
        }

        .product-edit-page .product-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .product-edit-page .product-edit-prices {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .product-edit-page .product-edit-field {
            min-width: 0;
        }

        .product-edit-page .product-edit-full {
            grid-column: 1 / -1;
        }

        .product-edit-page .product-edit-label {
            display: block;
            margin-bottom: 8px;
            color: #35465c;
            font-size: 13px;
            font-weight: 650;
        }

        .product-edit-page .required {
            color: #dc3545;
        }

        .product-edit-page .product-edit-input {
            display: block;
            width: 100%;
            min-height: 43px;
            padding: 10px 12px;
            border: 1px solid #ccd7e5;
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: var(--text);
            font: inherit;
            transition: border-color .2s, box-shadow .2s;
        }

        .product-edit-page .product-edit-input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, .12);
        }

        .product-edit-page .product-edit-input.is-invalid {
            border-color: #dc3545;
        }

        .product-edit-page .product-edit-textarea {
            min-height: 105px;
            resize: vertical;
        }

        .product-edit-page .product-edit-input-icon {
            position: relative;
        }

        .product-edit-page .product-edit-input-icon>i {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }

        .product-edit-page .product-edit-input-icon .product-edit-input {
            padding-left: 38px;
        }

        .product-edit-page .product-edit-help {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .product-edit-page .product-edit-error {
            display: block;
            margin-top: 6px;
            color: #c62828;
            font-size: 12px;
        }

        .product-edit-page .product-edit-divider {
            height: 1px;
            margin: 0 28px;
            background: #e8edf4;
        }

        .product-edit-page .product-edit-cost-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 22px;
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #f8faff;
        }

        .product-edit-page .product-edit-summary-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-edit-page .product-edit-summary-item span {
            color: var(--muted);
            font-size: 12px;
        }

        .product-edit-page .product-edit-summary-item span i {
            margin-right: 5px;
            color: var(--blue);
        }

        .product-edit-page .product-edit-summary-item strong {
            color: var(--navy);
            font-size: 21px;
            font-weight: 750;
        }

        .product-edit-page .product-edit-summary-item:last-of-type strong {
            color: #218653;
        }

        .product-edit-page .product-edit-cost-summary p {
            grid-column: 1 / -1;
            margin: 0;
            padding-top: 12px;
            border-top: 1px solid #e1e8f1;
            color: var(--muted);
            font-size: 12px;
        }

        .product-edit-page .product-edit-cost-summary p i {
            margin-right: 5px;
        }

        .product-edit-page .product-edit-category-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .product-edit-page .product-edit-category-option {
            position: relative;
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
            min-height: 54px;
            margin: 0;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }

        .product-edit-page .product-edit-category-option:has(input:checked) {
            border-color: var(--blue);
            background: #f2f7fd;
        }

        .product-edit-page .product-edit-category-option input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .product-edit-page .product-edit-category-check {
            display: grid;
            place-items: center;
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            border: 1px solid #bdcbdc;
            border-radius: 5px;
            color: transparent;
            background: #fff;
            font-size: 13px;
        }

        .product-edit-page .product-edit-category-option input:checked+.product-edit-category-check {
            border-color: var(--blue);
            color: #fff;
            background: var(--blue);
        }

        .product-edit-page .product-edit-category-option input:focus-visible+.product-edit-category-check {
            outline: 3px solid rgba(40, 93, 145, .2);
        }

        .product-edit-page .product-edit-category-name {
            overflow-wrap: anywhere;
            color: var(--text);
            font-size: 13px;
            font-weight: 550;
        }

        .product-edit-page .product-edit-empty-categories {
            display: flex;
            align-items: center;
            gap: 10px;
            grid-column: 1 / -1;
            padding: 15px;
            border: 1px dashed var(--border);
            border-radius: 10px;
            color: var(--muted);
        }

        .product-edit-page .product-edit-image-layout {
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .product-edit-page .product-edit-image-label {
            display: block;
            margin-bottom: 10px;
            color: #35465c;
            font-size: 13px;
            font-weight: 650;
        }

        .product-edit-page .product-edit-current-image {
            min-width: 0;
        }

        .product-edit-page .product-edit-current-image img {
            display: block;
            width: 160px;
            height: 160px;
            object-fit: cover;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #f8fafc;
        }

        .product-edit-page .product-edit-image-caption {
            display: block;
            margin-top: 9px;
            color: #218653;
            font-size: 12px;
        }

        .product-edit-page .product-edit-image-caption i {
            margin-right: 4px;
        }

        .product-edit-page .product-edit-no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 160px;
            height: 160px;
            border: 1px dashed #bdcbdc;
            border-radius: 12px;
            background: #f8fafc;
            color: var(--muted);
        }

        .product-edit-page .product-edit-no-image i {
            font-size: 32px;
        }

        .product-edit-page .product-edit-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 160px;
            padding: 20px;
            border: 1px dashed #aebfd3;
            border-radius: 12px;
            background: #f9fbfe;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }

        .product-edit-page .product-edit-upload:hover {
            border-color: var(--blue);
            background: #f1f6fc;
        }

        .product-edit-page .product-edit-upload-icon {
            color: var(--blue);
            font-size: 29px;
        }

        .product-edit-page .product-edit-upload strong {
            color: var(--navy);
            font-size: 13px;
        }

        .product-edit-page .product-edit-upload>span:not(.product-edit-upload-icon):not(.product-edit-upload-button) {
            color: var(--muted);
            font-size: 12px;
        }

        .product-edit-page .product-edit-upload-button {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 3px;
            padding: 8px 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--navy);
            font-size: 12px;
            font-weight: 650;
        }

        .product-edit-page .product-edit-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            clip-path: inset(50%);
        }

        .product-edit-page .product-edit-file-input:focus-visible+.product-edit-upload {
            outline: 3px solid rgba(40, 93, 145, .2);
        }

        .product-edit-page .product-edit-new-image-preview {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 13px;
            margin-top: 12px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
        }

        .product-edit-page .product-edit-new-image-preview[hidden] {
            display: none;
        }

        .product-edit-page .product-edit-new-image-preview img {
            width: 68px;
            height: 68px;
            flex: 0 0 68px;
            object-fit: cover;
            border-radius: 8px;
            background: #f3f6fa;
        }

        .product-edit-page .product-edit-preview-info {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 5px;
            min-width: 100px;
        }

        .product-edit-page .product-edit-preview-info strong {
            overflow-wrap: anywhere;
            color: var(--navy);
            font-size: 12px;
        }

        .product-edit-page .product-edit-preview-info span {
            color: var(--muted);
            font-size: 12px;
        }

        .product-edit-page .product-edit-remove-image {
            padding: 7px 10px;
            border: 1px solid #f0caca;
            border-radius: 7px;
            background: #fff7f7;
            color: #b42323;
            font-size: 12px;
            cursor: pointer;
        }

        .product-edit-page .product-edit-image-note {
            margin-top: 12px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .product-edit-page .product-edit-image-note i {
            margin-right: 4px;
        }

        .product-edit-page #editImageError:empty {
            display: none;
        }

        .product-edit-page .product-edit-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
            padding: 15px 18px;
            border: 1px solid #f1c6c6;
            border-radius: 10px;
            background: #fff6f6;
            color: #842029;
        }

        .product-edit-page .product-edit-alert>i {
            margin-top: 2px;
            font-size: 19px;
        }

        .product-edit-page .product-edit-alert strong {
            display: block;
            margin-bottom: 4px;
        }

        .product-edit-page .product-edit-alert p {
            margin: 0;
            font-size: 13px;
        }

        .product-edit-page .product-edit-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 28px;
            border-top: 1px solid var(--border);
            background: #fbfcfe;
        }

        .product-edit-page .product-edit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 17px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 650;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, border-color .2s, transform .2s;
        }

        .product-edit-page .product-edit-btn:hover {
            transform: translateY(-1px);
        }

        .product-edit-page .product-edit-btn-secondary {
            border-color: var(--border);
            background: #fff;
            color: #475569;
        }

        .product-edit-page .product-edit-btn-secondary:hover {
            border-color: #aebfd3;
            background: #f4f7fb;
            color: var(--navy);
        }

        .product-edit-page .product-edit-btn-primary {
            background: var(--navy);
            color: #fff;
        }

        .product-edit-page .product-edit-btn-primary:hover {
            background: var(--navy-light);
        }

        @media (max-width: 992px) {
            .product-edit-page .product-edit-prices {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .product-edit-page .product-edit-category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {

            .product-edit-page .product-edit-heading,
            .product-edit-page .product-edit-section {
                padding: 20px;
            }

            .product-edit-page .product-edit-grid,
            .product-edit-page .product-edit-prices {
                grid-template-columns: minmax(0, 1fr);
            }

            .product-edit-page .product-edit-full {
                grid-column: auto;
            }

            .product-edit-page .product-edit-image-layout {
                grid-template-columns: minmax(0, 1fr);
            }

            .product-edit-page .product-edit-category-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .product-edit-page .product-edit-divider {
                margin: 0 20px;
            }

            .product-edit-page .product-edit-actions {
                padding: 18px 20px;
            }
        }

        @media (max-width: 480px) {
            .product-edit-page .product-edit-heading {
                align-items: flex-start;
                padding: 18px 16px;
            }

            .product-edit-page .product-edit-section {
                padding: 20px 16px;
            }

            .product-edit-page .product-edit-heading h2 {
                font-size: 18px;
            }

            .product-edit-page .product-edit-cost-summary {
                grid-template-columns: minmax(0, 1fr);
            }

            .product-edit-page .product-edit-cost-summary p {
                grid-column: auto;
            }

            .product-edit-page .product-edit-divider {
                margin: 0 16px;
            }

            .product-edit-page .product-edit-actions {
                flex-direction: column-reverse;
                padding: 16px;
            }

            .product-edit-page .product-edit-btn {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const costInput = document.getElementById('cost');
            const transportInput = document.getElementById('transport_cost');
            const priceInput = document.getElementById('price');
            const totalCostDisplay = document.getElementById('editTotalCost');
            const profitDisplay = document.getElementById('editProfit');

            const imageInput = document.getElementById('image');
            const uploadLabel = document.getElementById('editUploadLabel');
            const previewContainer = document.getElementById('editNewImagePreview');
            const previewImage = document.getElementById('editNewImage');
            const imageName = document.getElementById('editImageName');
            const imageSize = document.getElementById('editImageSize');
            const removeImageButton = document.getElementById('editRemoveImage');
            const imageError = document.getElementById('editImageError');

            let previewUrl = null;

            function formatCurrency(value) {
                return new Intl.NumberFormat('es-AR', {
                    style: 'currency',
                    currency: 'ARS',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            function updateCostSummary() {
                const cost = Math.max(0, parseFloat(costInput.value) || 0);
                const transport = Math.max(0, parseFloat(transportInput.value) || 0);
                const price = Math.max(0, parseFloat(priceInput.value) || 0);

                const total = cost + transport;
                const profit = price - total;

                totalCostDisplay.textContent = formatCurrency(total);
                profitDisplay.textContent = formatCurrency(profit);
                profitDisplay.style.color = profit < 0 ? '#c62828' : '#218653';
            }

            [costInput, transportInput, priceInput].forEach(function(input) {
                input.addEventListener('input', updateCostSummary);
            });

            function clearPreviewUrl() {
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }
            }

            function resetNewImage() {
                clearPreviewUrl();
                imageInput.value = '';
                previewImage.src = '';
                previewContainer.hidden = true;
                uploadLabel.hidden = false;
                imageError.textContent = '';
            }

            imageInput.addEventListener('change', function() {
                imageError.textContent = '';

                const file = imageInput.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                const extensionAllowed = /\.(jpe?g|png|webp)$/i.test(file.name);

                if (!allowedTypes.includes(file.type) || !extensionAllowed) {
                    resetNewImage();
                    imageError.textContent = 'El archivo debe ser una imagen JPG, JPEG, PNG o WEBP.';
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    resetNewImage();
                    imageError.textContent = 'La imagen no puede superar los 2 MB.';
                    return;
                }

                clearPreviewUrl();
                previewUrl = URL.createObjectURL(file);

                previewImage.src = previewUrl;
                imageName.textContent = file.name;
                imageSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                uploadLabel.hidden = true;
                previewContainer.hidden = false;
            });

            removeImageButton.addEventListener('click', resetNewImage);

            document.getElementById('productEditForm').addEventListener('submit', function(event) {
                if (imageError.textContent.trim() !== '') {
                    event.preventDefault();
                    imageError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            window.addEventListener('beforeunload', clearPreviewUrl);

            updateCostSummary();
        });
    </script>
@endsection
