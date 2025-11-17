@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-plus-square"></i> Registrar venta</h1>
            <p class="panel-subtitle">Completa los datos del comprobante y detalla los libros que se entregan al cliente.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.sales.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="data-panel-body">
        <form action="{{ route('web.sales.store') }}" method="POST" class="modern-form" novalidate>
            @csrf
            <div class="row g-4">
                <div class="col-md-4">
                    <label for="fecha_venta" class="form-label">Fecha de venta</label>
                    <input type="datetime-local" name="fecha_venta" id="fecha_venta" class="form-control" value="{{ old('fecha_venta', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_venta')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        @foreach (\App\Models\SaleHeader::STATES as $state)
                            <option value="{{ $state }}" @selected(old('estado', 'Pagada') === $state)>{{ $state }}</option>
                        @endforeach
                    </select>
                    @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="id_cliente" class="form-label">Cliente (opcional)</label>
                    <select name="id_cliente" id="id_cliente" class="form-select">
                        <option value="">Venta sin registro de cliente</option>
                        @foreach ($clients as $clientId => $clientName)
                            <option value="{{ $clientId }}" @selected(old('id_cliente') == $clientId)>{{ $clientName }}</option>
                        @endforeach
                    </select>
                    @error('id_cliente')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="metodo_pago" class="form-label">Método de pago</label>
                    <input type="text" name="metodo_pago" id="metodo_pago" class="form-control" maxlength="80" value="{{ old('metodo_pago') }}" placeholder="Efectivo, tarjeta, yape, etc.">
                    @error('metodo_pago')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="notas" class="form-label">Notas</label>
                    <textarea name="notas" id="notas" rows="1" class="form-control" placeholder="Observaciones internas del ticket">{{ old('notas') }}</textarea>
                    @error('notas')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="surface-divider"></div>

            <div>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h2 class="h5 mb-0"><i class="bi bi-list-check"></i> Ítems de la venta</h2>
                        <p class="text-muted small mb-0">Indica los libros vendidos, cantidades y precios unitarios.</p>
                    </div>
                    <button type="button" class="btn btn-outline-soft" id="addSaleItem"><i class="bi bi-plus"></i> Añadir libro</button>
                </div>
                @error('items')<div class="text-danger small mb-3">{{ $message }}</div>@enderror
                <div id="saleItemsContainer" class="d-flex flex-column gap-3"></div>
                <div class="text-end mt-3">
                    <span class="status-pill"><i class="bi bi-cash-stack"></i> Total estimado: <strong id="saleTotal">Gs. 0</strong></span>
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary btn-elevated"><i class="bi bi-receipt"></i> Guardar venta</button>
            </div>
        </form>
    </div>
</section>

<template id="sale-item-template">
    <div class="modern-surface p-3 sale-item-card" data-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <h3 class="h6 mb-0"><i class="bi bi-book"></i> Ítem #<span class="sale-item-number"></span></h3>
            <button type="button" class="btn btn-outline-soft sale-item-remove"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Libro</label>
                <select name="items[__INDEX__][id_libro]" class="form-select sale-item-book" required>
                    <option value="">Selecciona un libro</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id_libro }}" data-stock="{{ $book->stock_actual }}" data-price="{{ $book->precio_venta }}">{{ $book->titulo }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Stock disponible: <span class="sale-item-stock">—</span></small>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad</label>
                <input type="number" name="items[__INDEX__][cantidad]" class="form-control sale-item-qty" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio unitario (Gs.)</label>
                <input type="number" name="items[__INDEX__][precio_unitario]" step="1" min="0" class="form-control sale-item-price" placeholder="Auto">
            </div>
        </div>
        <div class="text-end mt-2">
            <small class="text-muted">Subtotal: <strong class="sale-item-subtotal">Gs. 0</strong></small>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('saleItemsContainer');
        const templateHtml = document.getElementById('sale-item-template').innerHTML.trim();
        const addBtn = document.getElementById('addSaleItem');
        const totalLabel = document.getElementById('saleTotal');
        const previousItems = Object.values(@json(old('items', [])));
        let index = 0;

        const formatCurrency = new Intl.NumberFormat('es-PY', { style: 'currency', currency: 'PYG', minimumFractionDigits: 0 });

        function updateItemNumbers() {
            container.querySelectorAll('.sale-item-card').forEach((card, idx) => {
                card.querySelector('.sale-item-number').textContent = idx + 1;
            });
        }

        function updateTotals() {
            let total = 0;
            container.querySelectorAll('.sale-item-card').forEach((card) => {
                const qty = parseFloat(card.querySelector('.sale-item-qty').value) || 0;
                const priceInput = card.querySelector('.sale-item-price');
                const select = card.querySelector('.sale-item-book');
                const fallbackPrice = parseFloat(select.selectedOptions[0]?.dataset.price ?? 0);
                const price = parseFloat(priceInput.value) || fallbackPrice || 0;
                const subtotal = qty * price;
                card.querySelector('.sale-item-subtotal').textContent = formatCurrency.format(subtotal || 0);
                total += subtotal;
            });
            totalLabel.textContent = formatCurrency.format(total);
        }

        function updateStockFeedback(card) {
            const select = card.querySelector('.sale-item-book');
            const stockLabel = card.querySelector('.sale-item-stock');
            const option = select.selectedOptions[0];
            const stock = option?.dataset.stock ?? '—';
            stockLabel.textContent = stock;
            if (option?.dataset.price && !card.querySelector('.sale-item-price').value) {
                card.querySelector('.sale-item-price').value = option.dataset.price;
            }
        }

        function bindCardEvents(card) {
            const select = card.querySelector('.sale-item-book');
            const qty = card.querySelector('.sale-item-qty');
            const price = card.querySelector('.sale-item-price');
            const removeBtn = card.querySelector('.sale-item-remove');

            select.addEventListener('change', () => {
                updateStockFeedback(card);
                updateTotals();
            });
            qty.addEventListener('input', updateTotals);
            price.addEventListener('input', updateTotals);
            removeBtn.addEventListener('click', () => {
                card.remove();
                if (!container.childElementCount) {
                    addItem();
                } else {
                    updateItemNumbers();
                    updateTotals();
                }
            });
        }

        function addItem(prefill = {}) {
            const html = templateHtml.replace(/__INDEX__/g, index++);
            const fragment = document.createElement('div');
            fragment.innerHTML = html;
            const card = fragment.firstElementChild;
            container.appendChild(card);

            if (prefill.id_libro) {
                card.querySelector('.sale-item-book').value = prefill.id_libro;
            }
            if (prefill.cantidad) {
                card.querySelector('.sale-item-qty').value = prefill.cantidad;
            }
            if (prefill.precio_unitario) {
                card.querySelector('.sale-item-price').value = prefill.precio_unitario;
            }

            bindCardEvents(card);
            updateStockFeedback(card);
            updateItemNumbers();
            updateTotals();
        }

        addBtn.addEventListener('click', () => addItem());

        if (previousItems.length) {
            previousItems.forEach(item => addItem(item));
        } else {
            addItem();
        }
    });
</script>
@endpush
