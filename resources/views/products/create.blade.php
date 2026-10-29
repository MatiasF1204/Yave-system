@extends('layouts.admin')

@section('content')
    <div class="container-fluid product-create-page p-0">

        {{-- Errores generales --}}
        @if ($errors->any())
            <div class="alert alert-danger product-create-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>
                    <strong>Revisá los datos ingresados.</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <div class="product-create-card">

            {{-- Cabecera de la tarjeta --}}
            <div class="form-card-heading">
                <div class="form-heading-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <h5>Información del producto</h5>
                    <p>Completá los datos del producto para incorporarlo al inventario.</p>
                </div>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-body">

                    {{-- Nombre --}}
                    <div class="form-group full-width">
                        <label for="name" class="custom-label">
                            Nombre del producto
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-tag input-icon"></i>

                            <input type="text" id="name" name="name"
                                class="custom-input @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Ej.: Detergente líquido 1 litro" maxlength="150" required>
                        </div>

                        @error('name')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group full-width">
                        <label for="description" class="custom-label">
                            Descripción
                            <span class="optional-label">(opcional)</span>
                        </label>

                        <div class="input-wrapper textarea-wrapper">
                            <i class="bi bi-card-text input-icon textarea-icon"></i>

                            <textarea id="description" name="description"
                                class="custom-input custom-textarea @error('description') is-invalid @enderror" rows="3" maxlength="1000"
                                placeholder="Describí las características del producto...">{{ old('description') }}</textarea>
                        </div>

                        @error('description')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-divider"></div>

                    {{-- Costos y precio --}}
                    <div class="form-section-title">
                        <i class="bi bi-currency-dollar"></i>
                        <div>
                            <h6>Costos y precio de venta</h6>
                            <p>Ingresá los valores para calcular el costo total y la ganancia.</p>
                        </div>
                    </div>

                    {{-- Costo del producto --}}
                    <div class="form-group">
                        <label for="cost" class="custom-label">
                            Costo del producto
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <span class="currency-symbol">$</span>

                            <input type="number" id="cost" name="cost"
                                class="custom-input currency-input @error('cost') is-invalid @enderror"
                                value="{{ old('cost') }}" placeholder="0,00" min="0" step="0.01" required>
                        </div>

                        @error('cost')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Costo de transporte --}}
                    <div class="form-group">
                        <label for="transport_cost" class="custom-label">
                            Costo del transporte
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <span class="currency-symbol">$</span>

                            <input type="number" id="transport_cost" name="transport_cost"
                                class="custom-input currency-input @error('transport_cost') is-invalid @enderror"
                                value="{{ old('transport_cost', 0) }}" placeholder="0,00" min="0" step="0.01"
                                required>
                        </div>

                        @error('transport_cost')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Precio de venta --}}
                    <div class="form-group">
                        <label for="price" class="custom-label">
                            Precio de venta
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <span class="currency-symbol">$</span>

                            <input type="number" id="price" name="price"
                                class="custom-input currency-input @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" placeholder="0,00" min="0" step="0.01" required>
                        </div>

                        @error('price')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Vista previa de costos --}}
                    <div class="cost-summary">
                        <div class="cost-summary-icon">
                            <i class="bi bi-calculator"></i>
                        </div>

                        <div class="cost-summary-content">
                            <span class="summary-label">Costo total estimado</span>
                            <strong id="total-cost-preview">$ 0,00</strong>
                            <small>Costo del producto + costo del transporte</small>
                        </div>

                        <div class="cost-summary-content profit-preview">
                            <span class="summary-label">Ganancia estimada</span>
                            <strong id="profit-preview">$ 0,00</strong>
                            <small>Precio de venta - costo total</small>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- Inventario --}}
                    <div class="form-section-title">
                        <i class="bi bi-boxes"></i>
                        <div>
                            <h6>Inventario y categorías</h6>
                            <p>Definí la cantidad inicial y las categorías del producto.</p>
                        </div>
                    </div>

                    {{-- Stock --}}
                    <div class="form-group">
                        <label for="stock" class="custom-label">
                            Stock inicial
                            <span class="required-mark">*</span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-boxes input-icon"></i>

                            <input type="number" id="stock" name="stock"
                                class="custom-input @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}"
                                placeholder="Cantidad de unidades" min="0" step="1" required>
                        </div>

                        <small class="field-hint">
                            <i class="bi bi-info-circle"></i>
                            Cantidad de unidades disponibles al registrar el producto.
                        </small>

                        @error('stock')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Categorías --}}
                    <div class="form-group full-width">
                        <label class="custom-label">
                            Categorías
                            <span class="required-mark">*</span>
                        </label>

                        <p class="field-description">
                            Seleccioná una o más categorías para clasificar el producto.
                        </p>

                        <div class="categories-grid">
                            @forelse ($categories as $category)
                                <label for="category{{ $category->id }}" class="category-option">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        id="category{{ $category->id }}" @checked(in_array($category->id, old('categories', [])))>

                                    <span class="category-checkbox">
                                        <i class="bi bi-check2"></i>
                                    </span>

                                    <span class="category-name">
                                        {{ $category->name }}
                                    </span>
                                </label>
                            @empty
                                <div class="categories-empty">
                                    <i class="bi bi-folder2-open"></i>
                                    <span>No hay categorías activas disponibles.</span>
                                </div>
                            @endforelse
                        </div>

                        @error('categories')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        @error('categories.*')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-divider"></div>

                    {{-- Imagen --}}
                    <div class="form-section-title">
                        <i class="bi bi-image"></i>
                        <div>
                            <h6>Imagen del producto</h6>
                            <p>Podés agregar una fotografía para identificarlo visualmente.</p>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="image" class="custom-label">
                            Imagen
                            <span class="optional-label">(opcional)</span>
                        </label>

                        <div class="image-upload-area" id="image-upload-area">
                            <input type="file" id="image" name="image" class="image-file-input"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                            <label for="image" class="image-upload-label">
                                <div class="upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>

                                <strong>Seleccioná una imagen</strong>
                                <span>JPG, JPEG, PNG o WEBP · Máximo 2 MB</span>

                                <span class="upload-button">
                                    <i class="bi bi-folder2-open"></i>
                                    Buscar archivo
                                </span>
                            </label>

                            <div class="image-preview" id="image-preview" hidden>
                                <img src="" alt="Vista previa de la imagen" id="preview-img">

                                <div class="preview-info">
                                    <strong id="preview-name">Imagen seleccionada</strong>
                                    <span id="preview-size"></span>

                                    <button type="button" class="remove-image" id="remove-image">
                                        <i class="bi bi-trash3"></i>
                                        Quitar imagen
                                    </button>
                                </div>
                            </div>
                        </div>

                        <small class="field-hint">
                            <i class="bi bi-info-circle"></i>
                            La imagen se utilizará para identificar el producto en el sistema.
                        </small>

                        @error('image')
                            <div class="field-error">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="field-error" id="image-client-error" hidden></div>
                    </div>

                </div>

                {{-- Acciones --}}
                <div class="form-actions">
                    <a href="{{ route('products.index') }}" class="btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-box-seam"></i>
                        Registrar producto
                    </button>
                </div>
            </form>
        </div>

        {{-- Nota informativa --}}
        <div class="product-create-note">
            <i class="bi bi-info-circle-fill"></i>
            <span>
                Verificá los costos, el precio de venta, el stock y las categorías antes de registrar el producto.
            </span>
        </div>

    </div>

    <style>
        /* =========================================
               ESTILOS EXCLUSIVOS DE REGISTRO DE PRODUCTOS
               ========================================= */

        .product-create-page {
            --navy: #142b4a;
            --navy-light: #1d416b;
            --blue: #285d91;
            --blue-light: #e5edf7;
            --border: #d4deea;
            --text: #26384d;
            --muted: #718096;

            color: var(--text);
            font-size: 15px;
            padding-bottom: 25px;
        }

        /* Alertas */

        .product-create-page .product-create-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 14px;
        }

        .product-create-page .product-create-alert>i {
            margin-top: 2px;
            font-size: 20px;
        }

        .product-create-page .product-create-alert ul {
            padding-left: 19px;
        }

        .product-create-page .product-create-alert li {
            margin-bottom: 4px;
            color: #970c0c;
        }

        /* Tarjeta principal */

        .product-create-page .product-create-card {
            overflow: hidden;
            max-width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(25, 48, 75, 0.045);
        }

        .product-create-page .form-card-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 23px 28px;
            border-bottom: 1px solid var(--border);
        }

        .product-create-page .form-heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 46px;
            width: 46px;
            height: 46px;
            color: var(--blue);
            background: var(--blue-light);
            border-radius: 11px;
            font-size: 22px;
        }

        .product-create-page .form-card-heading h5 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 17px;
            font-weight: 750;
        }

        .product-create-page .form-card-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .product-create-page .required-mark {
            color: #b42332;
            font-weight: 750;
        }

        .product-create-page .optional-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 400;
        }

        /* Secciones */

        .product-create-page .form-body {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 25px 28px;
            padding: 28px;
        }

        .product-create-page .form-group {
            min-width: 0;
        }

        .product-create-page .full-width {
            grid-column: 1 / -1;
        }

        .product-create-page .form-section-title {
            grid-column: 1 / -1;
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: -5px;
            color: var(--blue);
        }

        .product-create-page .form-section-title>i {
            margin-top: 2px;
            font-size: 19px;
        }

        .product-create-page .form-section-title h6 {
            margin: 0 0 4px;
            color: var(--navy);
            font-size: 15px;
            font-weight: 750;
        }

        .product-create-page .form-section-title p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        /* Inputs */

        .product-create-page .custom-label {
            display: block;
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .product-create-page .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .product-create-page .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            z-index: 1;
            color: #7c8da2;
            font-size: 17px;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .product-create-page .custom-input {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 48px;
            padding: 12px 15px 12px 44px;
            color: var(--text);
            background: #fff;
            border: 1px solid #cbd6e3;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .product-create-page .custom-input::placeholder {
            color: #9aa7b6;
        }

        .product-create-page .custom-input:hover {
            border-color: #aabbd0;
        }

        .product-create-page .custom-input:focus {
            background: #fff;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(40, 93, 145, 0.12);
        }

        .product-create-page .custom-input.is-invalid {
            border-color: #dc3545;
        }

        .product-create-page .custom-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .product-create-page .custom-textarea {
            min-height: 105px;
            padding: 13px 15px 13px 44px;
            resize: vertical;
            line-height: 1.6;
        }

        .product-create-page .textarea-wrapper {
            align-items: flex-start;
        }

        .product-create-page .textarea-icon {
            top: 16px;
            transform: none;
        }

        .product-create-page .currency-symbol {
            position: absolute;
            left: 15px;
            z-index: 1;
            color: #7c8da2;
            font-size: 14px;
            font-weight: 700;
            pointer-events: none;
        }

        .product-create-page .currency-input {
            padding-left: 34px;
        }

        .product-create-page .field-error {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 8px;
            color: #b42332;
            font-size: 13px;
            line-height: 1.5;
        }

        .product-create-page .field-error i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .product-create-page .field-hint {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            margin-top: 9px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .product-create-page .field-hint i {
            margin-top: 1px;
            flex-shrink: 0;
        }

        .product-create-page .field-description {
            margin: -2px 0 12px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .product-create-page .form-divider {
            grid-column: 1 / -1;
            height: 1px;
            margin: 0;
            background: #e7edf4;
        }

        /* Resumen de costos */

        .product-create-page .cost-summary {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) minmax(0, 1fr);
            align-items: center;
            gap: 18px;
            padding: 18px 20px;
            background: #f5f8fc;
            border: 1px solid #dce6f1;
            border-radius: 10px;
        }

        .product-create-page .cost-summary-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            color: var(--blue);
            background: #e5edf7;
            border-radius: 10px;
            font-size: 21px;
        }

        .product-create-page .cost-summary-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .product-create-page .summary-label {
            color: #65768b;
            font-size: 12px;
            font-weight: 600;
        }

        .product-create-page .cost-summary-content strong {
            color: var(--navy);
            font-size: 20px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .product-create-page .cost-summary-content small {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .product-create-page .profit-preview {
            padding-left: 18px;
            border-left: 1px solid #d4deea;
        }

        .product-create-page .profit-preview strong.profit-negative {
            color: #b42332;
        }

        .product-create-page .profit-preview strong.profit-positive {
            color: #28784d;
        }

        /* Categorías */

        .product-create-page .categories-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .product-create-page .category-option {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            min-height: 48px;
            padding: 11px 13px;
            margin: 0;
            color: var(--text);
            background: #fff;
            border: 1px solid #cbd6e3;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .product-create-page .category-option:hover {
            background: #f7faff;
            border-color: #a9bfd8;
        }

        .product-create-page .category-option:has(input:checked) {
            background: #edf4fb;
            border-color: var(--blue);
            box-shadow: 0 0 0 1px rgba(40, 93, 145, 0.08);
        }

        .product-create-page .category-option input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .product-create-page .category-checkbox {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 20px;
            width: 20px;
            height: 20px;
            color: transparent;
            background: #fff;
            border: 1px solid #b9c7d7;
            border-radius: 5px;
            font-size: 13px;
            transition: background 0.2s, border-color 0.2s;
        }

        .product-create-page .category-option input:checked+.category-checkbox {
            color: #fff;
            background: var(--blue);
            border-color: var(--blue);
        }

        .product-create-page .category-option input:focus-visible+.category-checkbox {
            outline: 3px solid rgba(40, 93, 145, 0.2);
            outline-offset: 2px;
        }

        .product-create-page .category-name {
            overflow-wrap: anywhere;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
        }

        .product-create-page .categories-empty {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 17px;
            color: #7b5c1b;
            background: #fff9e9;
            border: 1px solid #f0dfb1;
            border-radius: 8px;
            font-size: 13px;
        }

        .product-create-page .categories-empty i {
            font-size: 19px;
        }

        /* Imagen */

        .product-create-page .image-upload-area {
            overflow: hidden;
            border: 1px dashed #b9c9dc;
            border-radius: 10px;
            background: #f8fafd;
            transition: border-color 0.2s, background 0.2s;
        }

        .product-create-page .image-upload-area:hover {
            background: #f2f7fc;
            border-color: var(--blue);
        }

        .product-create-page .image-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }

        .product-create-page .image-upload-label {
            display: flex;
            align-items: center;
            flex-direction: column;
            gap: 8px;
            padding: 28px 18px;
            margin: 0;
            text-align: center;
            cursor: pointer;
        }

        .product-create-page .upload-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            margin-bottom: 3px;
            color: var(--blue);
            background: var(--blue-light);
            border-radius: 13px;
            font-size: 25px;
        }

        .product-create-page .image-upload-label>strong {
            color: var(--navy);
            font-size: 14px;
        }

        .product-create-page .image-upload-label>span {
            color: var(--muted);
            font-size: 12px;
        }

        .product-create-page .upload-button {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 5px;
            padding: 9px 14px;
            color: var(--navy);
            background: #fff;
            border: 1px solid #cbd6e3;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
        }

        .product-create-page .image-upload-label:focus-within {
            outline: 2px solid var(--blue);
            outline-offset: -4px;
        }

        .product-create-page .image-preview {
            display: flex;
            align-items: center;
            gap: 17px;
            padding: 18px;
            background: #fff;
        }

        .product-create-page .image-preview[hidden],
        .product-create-page #image-client-error[hidden] {
            display: none !important;
        }

        .product-create-page .image-preview img {
            display: block;
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            object-fit: contain;
            background: #f7f9fc;
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        .product-create-page .preview-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            min-width: 0;
        }

        .product-create-page .preview-info strong {
            max-width: 100%;
            color: var(--navy);
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .product-create-page .preview-info span {
            color: var(--muted);
            font-size: 12px;
        }

        .product-create-page .remove-image {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
            padding: 7px 10px;
            color: #a83240;
            background: #fff;
            border: 1px solid #eccbd0;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .product-create-page .remove-image:hover {
            background: #fff1f2;
        }

        .product-create-page #image-client-error {
            margin-top: 9px;
        }

        /* Botones */

        .product-create-page .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 28px;
            background: #f7f9fc;
            border-top: 1px solid var(--border);
        }

        .product-create-page .btn-cancel,
        .product-create-page .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 44px;
            padding: 11px 18px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
        }

        .product-create-page .btn-cancel {
            color: #53657b;
            background: #fff;
            border-color: #cbd6e3;
        }

        .product-create-page .btn-cancel:hover {
            color: var(--navy);
            background: #edf2f8;
            border-color: #b8c7d9;
        }

        .product-create-page .btn-submit {
            color: #fff;
            background: var(--navy);
            border-color: var(--navy);
        }

        .product-create-page .btn-submit:hover {
            color: #fff;
            background: var(--navy-light);
            border-color: var(--navy-light);
            transform: translateY(-1px);
        }

        .product-create-page .btn-cancel:focus-visible,
        .product-create-page .btn-submit:focus-visible {
            outline: 3px solid rgba(40, 93, 145, 0.3);
            outline-offset: 2px;
        }

        /* Nota informativa */

        .product-create-page .product-create-note {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
            padding: 14px 17px;
            color: #536b87;
            background: #edf4fb;
            border: 1px solid #d7e5f3;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.6;
        }

        .product-create-page .product-create-note i {
            margin-top: 2px;
            color: var(--blue);
            font-size: 17px;
        }

        /* Responsive */

        @media (max-width: 900px) {
            .product-create-page .categories-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .product-create-page .cost-summary {
                grid-template-columns: auto minmax(0, 1fr) minmax(0, 1fr);
                gap: 12px;
                padding: 16px;
            }

            .product-create-page .cost-summary-content strong {
                font-size: 18px;
            }
        }

        @media (max-width: 768px) {
            .product-create-page .form-body {
                gap: 23px;
                padding: 23px;
            }

            .product-create-page .form-card-heading {
                padding: 20px 23px;
            }

            .product-create-page .form-actions {
                padding: 18px 23px;
            }
        }

        @media (max-width: 576px) {
            .product-create-page .form-body {
                grid-template-columns: minmax(0, 1fr);
                gap: 22px;
                padding: 20px;
            }

            .product-create-page .full-width,
            .product-create-page .form-divider,
            .product-create-page .form-section-title,
            .product-create-page .cost-summary {
                grid-column: auto;
            }

            .product-create-page .form-card-heading {
                padding: 18px 20px;
            }

            .product-create-page .form-heading-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
                font-size: 19px;
            }

            .product-create-page .form-card-heading h5 {
                font-size: 16px;
            }

            .product-create-page .cost-summary {
                grid-template-columns: auto minmax(0, 1fr);
                gap: 13px;
            }

            .product-create-page .profit-preview {
                grid-column: 1 / -1;
                padding: 13px 0 0;
                border-top: 1px solid #d4deea;
                border-left: none;
            }

            .product-create-page .categories-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .product-create-page .image-preview {
                align-items: flex-start;
                gap: 12px;
                padding: 13px;
            }

            .product-create-page .image-preview img {
                width: 78px;
                height: 78px;
            }

            .product-create-page .form-actions {
                align-items: stretch;
                flex-direction: column-reverse;
                padding: 18px 20px;
            }

            .product-create-page .btn-cancel,
            .product-create-page .btn-submit {
                width: 100%;
            }

            .product-create-page .product-create-note {
                padding: 12px 14px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const page = document.querySelector('.product-create-page');

            if (!page) {
                return;
            }

            // Vista previa del costo total y la ganancia.
            const costInput = page.querySelector('#cost');
            const transportInput = page.querySelector('#transport_cost');
            const priceInput = page.querySelector('#price');
            const totalPreview = page.querySelector('#total-cost-preview');
            const profitPreview = page.querySelector('#profit-preview');

            const moneyFormatter = new Intl.NumberFormat('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            function parseAmount(input) {
                const value = Number.parseFloat(input.value);
                return Number.isFinite(value) ? value : 0;
            }

            function formatMoney(amount) {
                return '$ ' + moneyFormatter.format(amount);
            }

            function updateCostPreview() {
                const cost = parseAmount(costInput);
                const transport = parseAmount(transportInput);
                const price = parseAmount(priceInput);

                const total = cost + transport;
                const profit = price - total;

                totalPreview.textContent = formatMoney(total);
                profitPreview.textContent = formatMoney(profit);

                profitPreview.classList.toggle('profit-negative', profit < 0);
                profitPreview.classList.toggle('profit-positive', profit > 0);
            }

            [costInput, transportInput, priceInput].forEach(input => {
                input.addEventListener('input', updateCostPreview);
            });

            updateCostPreview();

            // Vista previa y validación básica de imagen.
            const imageInput = page.querySelector('#image');
            const uploadArea = page.querySelector('#image-upload-area');
            const uploadLabel = page.querySelector('.image-upload-label');
            const preview = page.querySelector('#image-preview');
            const previewImage = page.querySelector('#preview-img');
            const previewName = page.querySelector('#preview-name');
            const previewSize = page.querySelector('#preview-size');
            const removeButton = page.querySelector('#remove-image');
            const imageError = page.querySelector('#image-client-error');

            let currentObjectUrl = null;

            function clearImagePreview() {
                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                    currentObjectUrl = null;
                }

                previewImage.removeAttribute('src');
                preview.hidden = true;
                uploadLabel.hidden = false;
            }

            function showImageError(message) {
                imageError.innerHTML = '';
                const icon = document.createElement('i');
                icon.className = 'bi bi-exclamation-circle';

                imageError.appendChild(icon);
                imageError.appendChild(document.createTextNode(' ' + message));
                imageError.hidden = false;
            }

            function clearImageError() {
                imageError.hidden = true;
                imageError.textContent = '';
            }

            imageInput.addEventListener('change', function() {
                clearImageError();

                const file = this.files[0];

                if (!file) {
                    clearImagePreview();
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                const allowedExtensions = /\.(jpe?g|png|webp)$/i;
                const maxSize = 2 * 1024 * 1024;

                if (
                    !allowedTypes.includes(file.type) ||
                    !allowedExtensions.test(file.name)
                ) {
                    this.value = '';
                    clearImagePreview();
                    showImageError('El formato no es válido. Seleccioná una imagen JPG, JPEG, PNG o WEBP.');
                    return;
                }

                if (file.size > maxSize) {
                    this.value = '';
                    clearImagePreview();
                    showImageError('La imagen supera el tamaño máximo permitido de 2 MB.');
                    return;
                }

                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                }

                currentObjectUrl = URL.createObjectURL(file);
                previewImage.src = currentObjectUrl;
                previewName.textContent = file.name;
                previewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                uploadLabel.hidden = true;
                preview.hidden = false;
            });

            removeButton.addEventListener('click', function() {
                imageInput.value = '';
                clearImagePreview();
                clearImageError();
            });

            // Liberar la URL temporal cuando se abandona la página.
            window.addEventListener('beforeunload', function() {
                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                }
            });

            // Evitar enviar una imagen que haya fallado la validación del cliente.
            const form = imageInput.closest('form');

            form.addEventListener('submit', function(event) {
                const file = imageInput.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                const allowedExtensions = /\.(jpe?g|png|webp)$/i;

                if (
                    !allowedTypes.includes(file.type) ||
                    !allowedExtensions.test(file.name) ||
                    file.size > 2 * 1024 * 1024
                ) {
                    event.preventDefault();
                    showImageError('Revisá el formato y el tamaño de la imagen antes de continuar.');
                    uploadArea.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        });
    </script>
@endsection
