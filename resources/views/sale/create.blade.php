@extends('layouts.admin')

@section('content')

    <div class="min-h-screen bg-slate-100">
        <div class="mx-auto max-w-7xl">

            {{-- MENSAJES --}}
            @if (session('success'))
                <div
                    class="mb-3 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-base font-medium text-emerald-800">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-base text-red-800">
                    <p class="mb-2 font-bold">No se pudo registrar la venta:</p>
                    <ul class="list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sales.store') }}" method="POST" id="sale-form" class="space-y-4" novalidate>
                @csrf

                {{-- DATOS DEL TICKET --}}
                <section class="rounded-xl border border-slate-300 bg-white p-3 shadow-sm sm:p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        {{-- VENDEDOR --}}
                        <div>
                            <label class="mb-2 block text-base font-bold text-slate-800">Vendedor</label>
                            <div
                                class="flex min-h-11 items-center rounded-lg border border-slate-300 bg-slate-100 px-3 py-2.5">
                                <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                            </div>
                        </div>

                        {{-- BUSCAR CLIENTE --}}
                        <div>
                            <label for="client-dni" class="mb-2 block text-base font-bold text-slate-800">
                                Buscar cliente por DNI <span class="text-red-600">*</span>
                            </label>

                            <div class="flex">
                                <input type="text" id="client-dni" inputmode="numeric" autocomplete="off"
                                    placeholder="Ingresá el DNI"
                                    class="min-w-0 w-full rounded-l-lg border border-slate-300 px-3 py-2.5 text-base text-slate-800 outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-200">

                                <button type="button" id="search-client"
                                    class="inline-flex items-center justify-center gap-2 rounded-r-lg bg-blue-800 px-4 text-sm font-bold text-white transition hover:bg-blue-950">
                                    <i class="bi bi-search"></i>
                                    Buscar
                                </button>
                            </div>

                            <input type="hidden" name="client_id" id="client-id" value="{{ old('client_id') }}">

                            <p id="client-feedback" class="mt-1.5 text-sm text-slate-500" aria-live="polite">
                                Ingresá el DNI y presioná Buscar.
                            </p>
                        </div>

                        {{-- CLIENTE SELECCIONADO --}}
                        <div>
                            <label class="mb-2 block text-base font-bold text-slate-800">Cliente seleccionado</label>
                            <div id="client-card"
                                class="flex min-h-11 items-center rounded-lg border border-dashed border-slate-300 bg-slate-100 px-3 py-2.5">
                                <span id="client-name" class="font-semibold text-slate-500">
                                    Ningún cliente seleccionado
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="mt-4 border-t border-slate-200 pt-3">
                        <label for="observations" class="mb-2 block text-base font-bold text-slate-800">
                            Observaciones
                            <span class="text-sm font-normal text-slate-500">(opcional)</span>
                        </label>

                        <textarea name="observations" id="observations" rows="2" maxlength="5000"
                            placeholder="Observaciones o aclaraciones sobre el ticket..."
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-700 focus:ring-2 focus:ring-blue-200">{{ old('observations') }}</textarea>
                    </div>
                </section>

                {{-- PRODUCTOS --}}
                <section class="rounded-xl border border-slate-300 bg-white shadow-sm">

                    <div
                        class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-3 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                <i class="bi bi-box-seam mr-2 text-blue-800"></i>Productos
                            </h2>
                            <p class="mt-0.5 text-sm text-slate-600">Agregá los artículos que lleva el cliente.</p>
                        </div>

                        <button type="button" id="add-product"
                            class="inline-flex items-center justify-center gap-2 self-start rounded-lg border border-blue-800 bg-blue-800 px-4 py-2.5 text-base font-bold text-white shadow-sm transition hover:border-blue-950 hover:bg-blue-950 sm:self-auto">
                            <i class="bi bi-plus-circle"></i>
                            Agregar producto
                        </button>
                    </div>

                    <div class="p-2 sm:p-3">
                        <div class="overflow-x-auto rounded-lg border border-slate-200">
                            <table class="w-full min-w-[800px] text-left text-base">
                                <thead>
                                    <tr
                                        class="border-b border-slate-300 bg-slate-200 text-sm uppercase tracking-wide text-slate-800">
                                        <th class="px-3 py-3 font-bold">Producto</th>
                                        <th class="px-3 py-3 font-bold">Stock</th>
                                        <th class="px-3 py-3 font-bold">Cantidad</th>
                                        <th class="px-3 py-3 font-bold">Precio unitario</th>
                                        <th class="px-3 py-3 text-right font-bold">Subtotal</th>
                                        <th class="px-3 py-3 text-center font-bold">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="products-body" class="divide-y divide-slate-200"></tbody>
                            </table>
                        </div>

                        <div id="products-empty"
                            class="mt-2 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-center">
                            <i class="bi bi-cart3 text-3xl text-blue-800"></i>
                            <p class="mt-1 text-base font-semibold text-slate-700">Todavía no agregaste productos</p>
                            <p class="mt-1 text-sm text-slate-500">Presioná «Agregar producto» para comenzar.</p>
                        </div>

                        <div class="mb-2 mt-4 flex justify-end">
                            <div class="w-full rounded-xl border border-blue-200 bg-blue-50 p-3 sm:max-w-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-base font-bold text-blue-950">TOTAL</span>
                                    <span id="sale-total" class="text-2xl font-extrabold tabular-nums text-blue-900">$
                                        0,00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- MEDIOS DE PAGO --}}
                <section class="overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">
                    <div
                        class="flex flex-col gap-3 border-b border-slate-200 bg-slate-50 px-3 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                <i class="bi bi-credit-card mr-2 text-blue-800"></i>Medios de pago
                            </h2>
                            <p class="mt-0.5 text-sm text-slate-600">Podés combinar distintos medios de pago.</p>
                        </div>

                        <button type="button" id="add-payment"
                            class="inline-flex items-center justify-center gap-2 self-start rounded-lg border border-blue-800 bg-blue-800 px-4 py-2.5 text-base font-bold text-white shadow-sm transition hover:border-blue-950 hover:bg-blue-950 sm:self-auto">
                            <i class="bi bi-plus-circle"></i>
                            Agregar pago
                        </button>
                    </div>

                    <div class="space-y-3 p-3">
                        <div id="payments-body" class="space-y-3"></div>

                        <div id="payments-empty"
                            class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-4 text-center">
                            <i class="bi bi-wallet2 text-2xl text-blue-800"></i>
                            <p class="mt-1 text-base text-slate-600">Agregá al menos un medio de pago.</p>
                        </div>

                        {{-- RESUMEN DE PAGOS --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-xl border border-slate-300 bg-white p-3">
                                <p class="text-sm font-semibold text-slate-600">Total de la venta</p>
                                <p id="payment-sale-total" class="mt-1 text-xl font-bold text-slate-900">$ 0,00</p>
                            </div>

                            <div class="rounded-xl border border-blue-200 bg-blue-50 p-3">
                                <p class="text-sm font-semibold text-blue-800">Dinero recibido</p>
                                <p id="payment-total" class="mt-1 text-xl font-bold text-blue-950">$ 0,00</p>
                            </div>

                            <div id="payment-balance-card" class="rounded-xl border border-amber-300 bg-amber-50 p-3">
                                <p class="text-sm font-semibold text-amber-900">Diferencia pendiente</p>
                                <p id="payment-balance" class="mt-1 text-xl font-bold text-amber-800">$ 0,00</p>
                            </div>
                        </div>

                        <p id="payment-feedback" class="text-sm font-medium text-slate-600" aria-live="polite">
                            La suma de los importes aplicados debe coincidir con el total.
                        </p>
                    </div>
                </section>

                {{-- ERRORES DE VALIDACIÓN DEL FORMULARIO --}}
                <div id="sale-validation-errors"
                    class="hidden rounded-xl border border-red-200 bg-red-50 p-4 text-red-700" role="alert"
                    aria-live="polite">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill mt-0.5 text-red-600"></i>
                        <div>
                            <p class="font-bold">Revisá los siguientes puntos antes de registrar la venta:</p>
                            <ul id="sale-validation-errors-list" class="mt-2 list-disc space-y-1 pl-5 text-sm"></ul>
                        </div>
                    </div>
                </div>

                {{-- BOTONES FINALES --}}
                <div class="flex flex-col-reverse gap-3 pb-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('sales.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-6 py-3 text-base font-semibold text-slate-700 transition hover:bg-slate-100">
                        <i class="bi bi-x-circle"></i>
                        Cancelar
                    </a>

                    <button type="submit" id="submit-sale"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-blue-900 bg-blue-900 px-7 py-3 text-base font-bold text-white shadow-sm transition hover:border-blue-950 hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <i class="bi bi-check-circle"></i>
                        Registrar venta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clients = @json($clientsData);
            const products = @json($productsData);
            const paymentMethods = @json($paymentMethodsData);
            const oldProducts = @json(old('products', []));
            const oldPayments = @json(old('payments', []));

            const form = document.getElementById('sale-form');
            const productsBody = document.getElementById('products-body');
            const paymentsBody = document.getElementById('payments-body');
            const productsEmpty = document.getElementById('products-empty');
            const paymentsEmpty = document.getElementById('payments-empty');
            const clientDni = document.getElementById('client-dni');
            const clientId = document.getElementById('client-id');
            const clientName = document.getElementById('client-name');
            const clientCard = document.getElementById('client-card');
            const clientFeedback = document.getElementById('client-feedback');
            const submitButton = document.getElementById('submit-sale');
            const validationErrors = document.getElementById('sale-validation-errors');
            const validationErrorsList = document.getElementById('sale-validation-errors-list');

            function showValidationErrors(messages) {
                validationErrorsList.replaceChildren();

                messages.forEach(function(message) {
                    const item = document.createElement('li');
                    item.textContent = message;
                    validationErrorsList.appendChild(item);
                });

                validationErrors.classList.remove('hidden');
                validationErrors.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            function clearValidationErrors() {
                validationErrorsList.replaceChildren();
                validationErrors.classList.add('hidden');
            }

            let productIndex = 0;
            let paymentIndex = 0;
            let saleTotalAmount = 0;

            const money = new Intl.NumberFormat('es-AR', {
                style: 'currency',
                currency: 'ARS',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            function setClientFeedback(message, success = false) {
                clientFeedback.textContent = message;
                clientFeedback.className = 'mt-1.5 text-sm ' +
                    (success ? 'text-emerald-700' : 'text-red-600');
            }

            function clearClient() {
                clientId.value = '';
                clientName.textContent = 'Ningún cliente seleccionado';
                clientName.className = 'font-semibold text-slate-500';
                clientCard.className =
                    'flex min-h-11 items-center rounded-lg border border-dashed border-slate-300 bg-slate-100 px-3 py-2.5';
            }

            function findClient() {
                const enteredDni = clientDni.value.trim().replace(/\D/g, '');

                if (!enteredDni) {
                    clearClient();
                    setClientFeedback('Ingresá el DNI del cliente.');
                    return;
                }

                const client = clients.find(function(item) {
                    return String(item.dni ?? '').replace(/\D/g, '') === enteredDni;
                });

                if (!client) {
                    clearClient();
                    setClientFeedback('No se encontró un cliente con ese DNI.');
                    return;
                }

                clientId.value = client.id;
                clientName.textContent = client.full_name || [client.name, client.surname].filter(Boolean).join(
                        ' ') ||
                    'Nombre no disponible';
                clientName.className = 'font-semibold text-slate-800';

                clientCard.className =
                    'flex min-h-11 items-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5';

                setClientFeedback('Cliente encontrado correctamente.', true);
            }

            document.getElementById('search-client').addEventListener('click', findClient);

            clientDni.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    findClient();
                }
            });

            clientDni.addEventListener('input', function() {
                clearClient();
                setClientFeedback('Presioná Buscar para identificar al cliente.');
            });

            // ---------------------------------------------------------
            // PRODUCTOS
            // ---------------------------------------------------------

            function addProductRow(initial = {}) {
                const index = productIndex++;
                const row = document.createElement('tr');

                row.className = 'border-b border-slate-100 align-top';
                row.dataset.productRow = 'true';

                const productCell = document.createElement('td');
                productCell.className = 'px-3 py-3';

                const picker = document.createElement('div');
                picker.className = 'relative min-w-60';

                const search = document.createElement('input');
                search.type = 'text';
                search.autocomplete = 'off';
                search.placeholder = 'Buscar producto...';
                search.className =
                    'product-search w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-200';

                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = `products[${index}][product_id]`;
                hiddenId.required = true;
                hiddenId.className = 'product-id';

                // El menú se agrega al body para que no quede cortado por la tabla.
                const dropdown = document.createElement('div');
                dropdown.className =
                    'product-dropdown fixed z-[9999] hidden max-h-60 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-xl';

                const selectedInfo = document.createElement('p');
                selectedInfo.className = 'mt-1 text-xs text-slate-500';

                picker.append(search, hiddenId);
                productCell.append(picker, selectedInfo);
                document.body.appendChild(dropdown);

                function positionDropdown() {
                    if (dropdown.classList.contains('hidden')) return;

                    const rect = search.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.bottom;
                    const spaceAbove = rect.top;
                    const availableSpace = Math.max(spaceBelow, spaceAbove);
                    const maxHeight = Math.max(80, Math.min(240, availableSpace - 12));

                    dropdown.style.left =
                        `${Math.max(4, Math.min(rect.left, window.innerWidth - rect.width - 4))}px`;
                    dropdown.style.width = `${Math.min(rect.width, window.innerWidth - 8)}px`;
                    dropdown.style.maxHeight = `${maxHeight}px`;

                    if (spaceBelow >= 160 || spaceBelow >= spaceAbove) {
                        dropdown.style.top = `${rect.bottom + 4}px`;
                    } else {
                        dropdown.style.top = `${Math.max(4, rect.top - maxHeight - 4)}px`;
                    }
                }

                const stockCell = document.createElement('td');
                stockCell.className = 'px-3 py-3';

                const stockLabel = document.createElement('span');
                stockLabel.className =
                    'stock-label inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600';
                stockLabel.textContent = '—';
                stockCell.appendChild(stockLabel);

                const quantityCell = document.createElement('td');
                quantityCell.className = 'px-3 py-3';

                const quantity = document.createElement('input');
                quantity.type = 'number';
                quantity.name = `products[${index}][quantity]`;
                quantity.min = '1';
                quantity.step = '1';
                quantity.required = true;
                quantity.value = initial.quantity || 1;
                quantity.className =
                    'quantity-input w-24 rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-200';
                quantityCell.appendChild(quantity);

                const priceCell = document.createElement('td');
                priceCell.className = 'px-3 py-3';

                const priceLabel = document.createElement('span');
                priceLabel.className = 'unit-price whitespace-nowrap font-medium text-slate-700';
                priceLabel.textContent = money.format(0);
                priceCell.appendChild(priceLabel);

                const subtotalCell = document.createElement('td');
                subtotalCell.className = 'px-3 py-3 text-right';

                const subtotalLabel = document.createElement('span');
                subtotalLabel.className = 'row-subtotal whitespace-nowrap font-bold text-slate-800';
                subtotalLabel.textContent = money.format(0);
                subtotalCell.appendChild(subtotalLabel);

                const actionCell = document.createElement('td');
                actionCell.className = 'px-3 py-3 text-center';

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.title = 'Quitar producto';
                remove.setAttribute('aria-label', 'Quitar producto');
                remove.className =
                    'inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-600 hover:bg-red-50';
                remove.innerHTML = '<i class="bi bi-trash3"></i>';
                actionCell.appendChild(remove);

                row.append(productCell, stockCell, quantityCell, priceCell, subtotalCell, actionCell);
                productsBody.appendChild(row);

                function renderOptions() {
                    dropdown.replaceChildren();

                    const term = search.value.trim().toLocaleLowerCase('es');

                    const available = products.filter(function(product) {
                        const matches = String(product.name ?? '')
                            .toLocaleLowerCase('es').includes(term);

                        const alreadySelectedElsewhere = Array.from(
                            productsBody.querySelectorAll('.product-id')
                        ).some(function(input) {
                            return input !== hiddenId &&
                                String(input.value) === String(product.id);
                        });

                        return matches &&
                            Number(product.stock) > 0 &&
                            !alreadySelectedElsewhere;
                    });

                    if (!available.length) {
                        const empty = document.createElement('div');
                        empty.className = 'px-3 py-3 text-sm text-slate-500';
                        empty.textContent = 'No se encontraron productos disponibles.';
                        dropdown.appendChild(empty);
                    }

                    available.forEach(function(product) {
                        const option = document.createElement('button');
                        option.type = 'button';
                        option.className =
                            'block w-full border-b border-slate-100 px-3 py-2.5 text-left hover:bg-blue-50';

                        const name = document.createElement('span');
                        name.className = 'block font-semibold text-slate-800';
                        name.textContent = product.name;

                        const details = document.createElement('span');
                        details.className = 'mt-1 block text-xs text-slate-500';
                        details.textContent =
                            `Stock: ${product.stock} · Precio: ${money.format(Number(product.price))}`;

                        option.append(name, details);

                        option.addEventListener('click', function() {
                            hiddenId.value = product.id;
                            search.value = product.name;
                            selectedInfo.textContent = 'Producto seleccionado';
                            stockLabel.textContent = product.stock;
                            priceLabel.textContent = money.format(Number(product.price));
                            dropdown.classList.add('hidden');
                            updateProducts();
                        });

                        dropdown.appendChild(option);
                    });

                    dropdown.classList.remove('hidden');
                    positionDropdown();
                }

                search.addEventListener('focus', renderOptions);
                search.addEventListener('click', renderOptions);

                search.addEventListener('input', function() {
                    hiddenId.value = '';
                    selectedInfo.textContent = '';
                    updateProducts();
                    renderOptions();
                });

                search.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        dropdown.classList.add('hidden');
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!picker.contains(event.target) && !dropdown.contains(event.target)) {
                        dropdown.classList.add('hidden');
                    }
                });

                window.addEventListener('scroll', positionDropdown, true);
                window.addEventListener('resize', positionDropdown);

                quantity.addEventListener('input', updateProducts);

                remove.addEventListener('click', function() {
                    dropdown.remove();
                    row.remove();
                    updateProducts();
                });

                if (initial.product_id) {
                    const previous = products.find(function(product) {
                        return String(product.id) === String(initial.product_id);
                    });

                    if (previous) {
                        hiddenId.value = previous.id;
                        search.value = previous.name;
                        selectedInfo.textContent = 'Producto seleccionado';
                        stockLabel.textContent = previous.stock;
                        priceLabel.textContent = money.format(Number(previous.price));
                    }
                }

                updateProducts();
            }

            function updateProducts() {
                let total = 0;

                productsBody.querySelectorAll('[data-product-row]').forEach(function(row) {
                    const productId = row.querySelector('.product-id').value;
                    const quantityInput = row.querySelector('.quantity-input');

                    const product = products.find(function(item) {
                        return String(item.id) === String(productId);
                    });

                    const quantity = Math.max(0, Number(quantityInput.value) || 0);
                    const price = product ? Number(product.price) : 0;
                    const stock = product ? Number(product.stock) : 0;
                    const subtotal = Math.round(price * quantity * 100) / 100;

                    row.querySelector('.stock-label').textContent = product ? stock : '—';
                    row.querySelector('.unit-price').textContent = money.format(price);
                    row.querySelector('.row-subtotal').textContent = money.format(subtotal);

                    quantityInput.max = product ? stock : '';
                    quantityInput.setCustomValidity(
                        product && quantity > stock ?
                        'La cantidad supera el stock disponible.' :
                        ''
                    );

                    total = Math.round((total + subtotal) * 100) / 100;
                });

                saleTotalAmount = total;

                productsEmpty.classList.toggle(
                    'hidden',
                    productsBody.querySelectorAll('[data-product-row]').length > 0
                );

                document.getElementById('sale-total').textContent = money.format(total);
                document.getElementById('payment-sale-total').textContent = money.format(total);

                updatePayments();
            }

            // ---------------------------------------------------------
            // MEDIOS DE PAGO
            // ---------------------------------------------------------

            function addPaymentRow(initial = {}) {
                const index = paymentIndex++;

                const row = document.createElement('div');
                row.dataset.paymentRow = 'true';
                row.className = 'rounded-xl border border-slate-200 bg-slate-50 p-4';

                const mainGrid = document.createElement('div');
                mainGrid.className = 'grid grid-cols-1 items-end gap-3 sm:grid-cols-[1fr_1fr_auto]';

                // MEDIO DE PAGO
                const methodWrapper = document.createElement('div');

                const methodLabel = document.createElement('label');
                methodLabel.className = 'mb-2 block text-sm font-semibold text-slate-700';
                methodLabel.textContent = 'Medio de pago';

                const methodSelect = document.createElement('select');
                methodSelect.name = `payments[${index}][payment_method_id]`;
                methodSelect.required = true;
                methodSelect.className =
                    'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-200';

                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Seleccionar medio';
                methodSelect.appendChild(placeholder);

                paymentMethods.forEach(function(method) {
                    const option = document.createElement('option');
                    option.value = method.id;
                    option.textContent = method.name;

                    if (String(initial.payment_method_id || '') === String(method.id)) {
                        option.selected = true;
                    }

                    methodSelect.appendChild(option);
                });

                methodWrapper.append(methodLabel, methodSelect);

                // IMPORTE APLICADO
                const amountWrapper = document.createElement('div');

                const amountLabel = document.createElement('label');
                amountLabel.className = 'mb-2 block text-sm font-semibold text-slate-700';
                amountLabel.textContent = 'Importe aplicado';

                const amountInput = document.createElement('input');
                amountInput.type = 'number';
                amountInput.name = `payments[${index}][amount]`;
                amountInput.min = '0.01';
                amountInput.step = '0.01';
                amountInput.required = true;
                amountInput.value = initial.amount ?? '';
                amountInput.placeholder = '0.00';
                amountInput.className =
                    'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-200';

                amountWrapper.append(amountLabel, amountInput);

                // BOTÓN QUITAR
                const actionWrapper = document.createElement('div');

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className =
                    'inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-3 text-sm font-semibold text-red-600 hover:bg-red-50 sm:w-auto';
                remove.innerHTML = '<i class="bi bi-trash3"></i><span>Quitar</span>';
                actionWrapper.appendChild(remove);

                mainGrid.append(methodWrapper, amountWrapper, actionWrapper);

                // PANEL DE EFECTIVO
                const cashPanel = document.createElement('div');
                cashPanel.className =
                    'cash-panel mt-4 hidden rounded-lg border border-emerald-200 bg-white p-4';

                const cashGrid = document.createElement('div');
                cashGrid.className = 'grid grid-cols-1 gap-3 sm:grid-cols-2';

                const receivedWrapper = document.createElement('div');

                const receivedLabel = document.createElement('label');
                receivedLabel.className = 'mb-2 block text-sm font-semibold text-slate-700';
                receivedLabel.textContent = 'Efectivo recibido ($)';

                const receivedInput = document.createElement('input');
                receivedInput.type = 'number';
                receivedInput.min = '0';
                receivedInput.step = '0.01';
                receivedInput.placeholder = 'Ej.: 10000';
                receivedInput.className =
                    'cash-received w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200';

                receivedWrapper.append(receivedLabel, receivedInput);

                const changeWrapper = document.createElement('div');

                const changeLabel = document.createElement('p');
                changeLabel.className = 'mb-2 text-sm font-semibold text-slate-700';
                changeLabel.textContent = 'Vuelto a entregar';

                const changeValue = document.createElement('p');
                changeValue.className =
                    'cash-change rounded-lg bg-emerald-50 px-3 py-2.5 text-lg font-bold text-emerald-700';
                changeValue.textContent = money.format(0);

                const changeHint = document.createElement('p');
                changeHint.className = 'cash-hint mt-1 text-xs text-slate-500';
                changeHint.textContent = 'El importe pendiente se calculará automáticamente.';

                changeWrapper.append(changeLabel, changeValue, changeHint);
                cashGrid.append(receivedWrapper, changeWrapper);
                cashPanel.appendChild(cashGrid);

                row.append(mainGrid, cashPanel);
                paymentsBody.appendChild(row);

                function updateCashPanel() {
                    const selected = methodSelect.selectedOptions[0];
                    const methodName = selected ?
                        selected.textContent.toLocaleLowerCase('es') :
                        '';

                    const isCash = methodName.includes('efectivo');

                    cashPanel.classList.toggle('hidden', !isCash);

                    amountInput.readOnly = isCash;
                    amountInput.min = isCash ? '0' : '0.01';
                    amountInput.placeholder = isCash ?
                        'Calculado automáticamente' :
                        '0.00';

                    amountInput.classList.toggle('bg-slate-100', isCash);
                    amountInput.classList.toggle('font-semibold', isCash);

                    if (!isCash) {
                        receivedInput.value = '';
                        receivedInput.required = false;
                    }

                    updatePayments();
                }

                methodSelect.addEventListener('change', updateCashPanel);
                amountInput.addEventListener('input', updatePayments);
                receivedInput.addEventListener('input', updatePayments);

                remove.addEventListener('click', function() {
                    row.remove();
                    updatePayments();
                });

                updateCashPanel();
            }

            function updatePayments() {
                const rows = Array.from(
                    paymentsBody.querySelectorAll('[data-payment-row]')
                );

                let appliedTotal = 0;
                let nonCashTotal = 0;
                let cashInputsValid = true;

                const cashRows = [];

                // Sumar los medios que no son efectivo.
                rows.forEach(function(row) {
                    const methodSelect = row.querySelector('select');
                    const amountInput = row.querySelector('input[name$="[amount]"]');
                    const selected = methodSelect.selectedOptions[0];
                    const methodName = selected ?
                        selected.textContent.toLocaleLowerCase('es') :
                        '';

                    if (methodName.includes('efectivo')) {
                        cashRows.push(row);
                    } else {
                        const amount = Math.max(0, Number(amountInput.value) || 0);
                        nonCashTotal = Math.round((nonCashTotal + amount) * 100) / 100;
                    }
                });

                // El primer medio de pago en efectivo cubre automáticamente el restante.
                const cashAmount = Math.max(
                    0,
                    Math.round((saleTotalAmount - nonCashTotal) * 100) / 100
                );

                cashRows.forEach(function(row, index) {
                    const amountInput = row.querySelector('input[name$="[amount]"]');

                    amountInput.readOnly = true;
                    amountInput.min = '0';
                    amountInput.value = index === 0 ? cashAmount.toFixed(2) : '0.00';
                    amountInput.placeholder = 'Calculado automáticamente';
                    amountInput.classList.add('bg-slate-100', 'font-semibold');
                });

                rows.forEach(function(row) {
                    const methodSelect = row.querySelector('select');
                    const amountInput = row.querySelector('input[name$="[amount]"]');
                    const receivedInput = row.querySelector('.cash-received');
                    const selected = methodSelect.selectedOptions[0];
                    const methodName = selected ?
                        selected.textContent.toLocaleLowerCase('es') :
                        '';

                    const isCash = methodName.includes('efectivo');
                    const applied = Math.max(0, Number(amountInput.value) || 0);

                    appliedTotal = Math.round((appliedTotal + applied) * 100) / 100;

                    if (!isCash) return;

                    const changeValue = row.querySelector('.cash-change');
                    const hint = row.querySelector('.cash-hint');

                    receivedInput.required = applied > 0;

                    const receivedText = receivedInput.value;
                    const received = Number(receivedText);
                    const hasReceived =
                        receivedText !== '' && Number.isFinite(received);

                    if (applied <= 0) {
                        changeValue.textContent = money.format(0);
                        hint.textContent = 'No queda importe por cobrar en efectivo.';
                        hint.className = 'cash-hint mt-1 text-xs text-slate-500';
                    } else if (!hasReceived) {
                        changeValue.textContent = '—';
                        hint.textContent = 'Ingresá cuánto efectivo te entregó el cliente.';
                        hint.className = 'cash-hint mt-1 text-xs text-slate-500';
                        cashInputsValid = false;
                    } else if (received < applied) {
                        changeValue.textContent = '—';
                        hint.textContent =
                            'El efectivo recibido es insuficiente. Falta dinero para completar el pago.';
                        hint.className = 'cash-hint mt-1 text-xs font-medium text-red-600';
                        cashInputsValid = false;
                    } else {
                        changeValue.textContent = money.format(received - applied);
                        hint.textContent = received > applied ?
                            'Vuelto que se entrega al cliente.' :
                            'No corresponde entregar vuelto.';
                        hint.className = 'cash-hint mt-1 text-xs text-emerald-700';
                    }
                });

                const difference = Math.round((saleTotalAmount - appliedTotal) * 100) / 100;

                document.getElementById('payment-total').textContent = money.format(appliedTotal);
                document.getElementById('payment-balance').textContent = money.format(difference);

                const balanceCard = document.getElementById('payment-balance-card');
                const balanceLabel = balanceCard.querySelector('p');
                const balanceValue = document.getElementById('payment-balance');
                const feedback = document.getElementById('payment-feedback');

                if (saleTotalAmount > 0 && Math.abs(difference) < 0.001) {
                    balanceCard.className =
                        'rounded-xl border border-emerald-200 bg-emerald-50 p-3';
                    balanceLabel.textContent = 'Diferencia pendiente';
                    balanceValue.className = 'mt-1 text-xl font-bold text-emerald-700';
                    feedback.className = 'text-sm font-medium text-emerald-700';
                    feedback.textContent = cashInputsValid ?
                        'Los importes cubren el total de la venta.' :
                        'Revisá el efectivo recibido para completar el pago.';
                } else if (difference < -0.001) {
                    balanceCard.className =
                        'rounded-xl border border-red-200 bg-red-50 p-3';
                    balanceLabel.textContent = 'Importe excedido';
                    balanceValue.className = 'mt-1 text-xl font-bold text-red-700';
                    feedback.className = 'text-sm font-medium text-red-700';
                    feedback.textContent = 'Los importes aplicados superan el total de la venta.';
                } else {
                    balanceCard.className =
                        'rounded-xl border border-amber-200 bg-amber-50 p-3';
                    balanceLabel.textContent = 'Diferencia pendiente';
                    balanceValue.className = 'mt-1 text-xl font-bold text-amber-700';
                    feedback.className = 'text-sm text-amber-700';
                    feedback.textContent = saleTotalAmount === 0 ?
                        'Agregá productos para calcular el total.' :
                        'Todavía falta cubrir parte del total de la venta.';
                }

                paymentsEmpty.classList.toggle('hidden', rows.length > 0);
            }

            document.getElementById('add-product').addEventListener('click', function() {
                addProductRow();
            });

            document.getElementById('add-payment').addEventListener('click', function() {
                addPaymentRow();
            });

            // Recuperar productos y pagos si el servidor devolvió errores.
            if (Array.isArray(oldProducts) && oldProducts.length) {
                oldProducts.forEach(addProductRow);
            }

            if (Array.isArray(oldPayments) && oldPayments.length) {
                oldPayments.forEach(addPaymentRow);
            }

            // Restaurar el cliente seleccionado.
            if (clientId.value) {
                const previousClient = clients.find(function(client) {
                    return String(client.id) === String(clientId.value);
                });

                if (previousClient) {
                    clientDni.value = previousClient.dni ?? '';
                    findClient();
                }
            }

            // ---------------------------------------------------------
            // VALIDACIÓN ANTES DE REGISTRAR LA VENTA
            // ---------------------------------------------------------

            form.addEventListener('submit', function(event) {
                const errors = [];
                const productRows = Array.from(
                    productsBody.querySelectorAll('[data-product-row]')
                );

                // Validar cliente.
                if (!clientId.value) {
                    errors.push('Buscá y seleccioná un cliente antes de continuar.');
                    setClientFeedback('Buscá y seleccioná un cliente antes de continuar.');
                }

                // Validar productos y cantidades.
                const invalidProducts = !productRows.length || productRows.some(function(row) {
                    const productId = row.querySelector('.product-id').value;
                    const quantity = row.querySelector('.quantity-input');
                    const product = products.find(function(item) {
                        return String(item.id) === String(productId);
                    });

                    return !product ||
                        !quantity.value ||
                        !Number.isInteger(Number(quantity.value)) ||
                        Number(quantity.value) < 1 ||
                        Number(quantity.value) > Number(product.stock);
                });

                if (invalidProducts) {
                    errors.push(
                        'Agregá productos válidos y verificá que las cantidades sean enteras y no superen el stock.'
                    );
                }

                // Actualizar los importes automáticos antes de validar los pagos.
                updatePayments();

                let paymentRows = Array.from(
                    paymentsBody.querySelectorAll('[data-payment-row]')
                );

                if (!paymentRows.length) {
                    errors.push('Agregá al menos un medio de pago.');
                } else {
                    // Quitar filas de efectivo cuyo importe automático sea cero.
                    // Esto ocurre cuando los otros medios ya cubren toda la venta.
                    paymentRows.forEach(function(row) {
                        const method = row.querySelector('select');
                        const amount = row.querySelector('input[name$="[amount]"]');
                        const methodName = method.selectedOptions[0] ?
                            method.selectedOptions[0].textContent.toLocaleLowerCase('es') :
                            '';

                        if (
                            methodName.includes('efectivo') &&
                            Number(amount.value || 0) <= 0
                        ) {
                            row.remove();
                        }
                    });

                    updatePayments();

                    paymentRows = Array.from(
                        paymentsBody.querySelectorAll('[data-payment-row]')
                    );

                    if (!paymentRows.length) {
                        errors.push('Agregá al menos un medio de pago con un importe válido.');
                    } else {
                        const cashRows = paymentRows.filter(function(row) {
                            const method = row.querySelector('select');
                            const methodName = method.selectedOptions[0] ?
                                method.selectedOptions[0].textContent.toLocaleLowerCase('es') :
                                '';

                            return methodName.includes('efectivo');
                        });

                        if (cashRows.length > 1) {
                            errors.push(
                                'Usá una sola fila de efectivo. El sistema calcula automáticamente el importe restante.'
                            );
                        }

                        const invalidPayment = paymentRows.some(function(row) {
                            const method = row.querySelector('select');
                            const amount = row.querySelector('input[name$="[amount]"]');
                            const received = row.querySelector('.cash-received');

                            if (
                                !method.value ||
                                !amount.value ||
                                !Number.isFinite(Number(amount.value)) ||
                                Number(amount.value) <= 0
                            ) {
                                return true;
                            }

                            const methodName = method.selectedOptions[0].textContent
                                .toLocaleLowerCase('es');

                            if (methodName.includes('efectivo')) {
                                return !received ||
                                    received.value === '' ||
                                    !Number.isFinite(Number(received.value)) ||
                                    Number(received.value) < Number(amount.value);
                            }

                            return false;
                        });

                        if (invalidPayment) {
                            errors.push(
                                'Revisá los medios de pago. Todos deben tener un importe válido y, si pagás en efectivo, el dinero recibido debe cubrir el importe pendiente.'
                            );
                        }

                        const appliedTotal = paymentRows.reduce(function(sum, row) {
                            return sum + Number(
                                row.querySelector('input[name$="[amount]"]').value || 0
                            );
                        }, 0);

                        if (
                            !invalidPayment &&
                            Math.abs(saleTotalAmount - appliedTotal) > 0.001
                        ) {
                            errors.push(
                                'No se puede registrar la venta: hay un importe pendiente o los pagos superan el total.'
                            );
                        }
                    }
                }

                if (errors.length) {
                    event.preventDefault();
                    showValidationErrors(errors);

                    if (!clientId.value) {
                        clientDni.focus({
                            preventScroll: true
                        });
                    }

                    return;
                }

                clearValidationErrors();
                submitButton.disabled = true;
                submitButton.classList.add('cursor-not-allowed', 'opacity-60');
                submitButton.innerHTML =
                    '<i class="bi bi-hourglass-split"></i> Registrando venta...';
            });
        });
    </script>

@endsection
