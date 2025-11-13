@php
    $items = old('items', isset($return)
        ? $return->details->map(fn ($detail) => [
            'id_libro' => $detail->id_libro,
            'cantidad_devuelta' => $detail->cantidad_devuelta,
        ])->values()->all()
        : [['id_libro' => '', 'cantidad_devuelta' => 1]]
    );
@endphp

<div class="mb-3">
    <label for="id_cliente" class="form-label">Cliente</label>
    <select class="form-select" id="id_cliente" name="id_cliente" required>
        <option value="">Selecciona un cliente</option>
        @foreach ($clients as $id => $name)
            <option value="{{ $id }}" @selected(old('id_cliente', $return->id_cliente ?? '') == $id)>{{ $name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="id_reserva" class="form-label">Reserva asociada</label>
    <select class="form-select" id="id_reserva" name="id_reserva" required>
        <option value="">Selecciona una reserva</option>
        @foreach ($reservations as $id => $label)
            <option value="{{ $id }}" @selected(old('id_reserva', $return->id_reserva ?? '') == $id)>{{ $label }}</option>
        @endforeach
    </select>
    <small class="text-muted">Las devoluciones deben vincularse a la reserva original.</small>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <label for="fecha_devolucion" class="form-label">Fecha de devolución</label>
        <input type="datetime-local" class="form-control" id="fecha_devolucion" name="fecha_devolucion" value="{{ old('fecha_devolucion', isset($return) ? $return->fecha_devolucion?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
    </div>
    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" id="estado" name="estado" required>
            @foreach (['Completa', 'Parcial'] as $estado)
                <option value="{{ $estado }}" @selected(old('estado', $return->estado ?? 'Completa') === $estado)>{{ $estado }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Libros devueltos</h2>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-return-item">
            <i class="bi bi-plus-circle"></i> Agregar libro devuelto
        </button>
    </div>
    <div id="return-items" class="d-flex flex-column gap-3">
        @foreach ($items as $index => $item)
            @include('returns.partials.item-row', ['index' => $index, 'item' => $item, 'books' => $books])
        @endforeach
    </div>
    <p class="form-text mt-2">Cada devolución actualiza el movimiento y queda registrada con hora exacta.</p>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('return-items');
                const addButton = document.getElementById('add-return-item');
                const template = document.getElementById('return-item-template');

                const handleRemove = (button) => {
                    const row = button.closest('.return-item');
                    if (row && container.children.length > 1) {
                        row.remove();
                    }
                };

                container.querySelectorAll('[data-remove-item]').forEach(button => {
                    button.addEventListener('click', () => handleRemove(button));
                });

                addButton?.addEventListener('click', () => {
                    if (!template) {
                        return;
                    }

                    const nextIndex = container.children.length;
                    const clone = template.content.cloneNode(true);
                    clone.querySelectorAll('[data-name]').forEach(element => {
                        const baseName = element.getAttribute('data-name');
                        element.setAttribute('name', baseName.replace('__INDEX__', nextIndex));
                    });
                    clone.querySelectorAll('[data-remove-item]').forEach(button => {
                        button.addEventListener('click', () => handleRemove(button));
                    });
                    container.appendChild(clone);
                });
            });
        </script>
    @endpush
@endonce

<template id="return-item-template">
    @include('returns.partials.item-row', ['index' => '__INDEX__', 'item' => ['id_libro' => '', 'cantidad_devuelta' => 1], 'books' => $books])
</template>
