@php
    $items = old('items', isset($reservation)
        ? $reservation->details->map(fn ($detail) => [
            'id_libro' => $detail->id_libro,
            'cantidad' => $detail->cantidad,
        ])->values()->all()
        : [['id_libro' => '', 'cantidad' => 1]]
    );
@endphp

<div class="mb-3">
    <label for="id_cliente" class="form-label">Cliente</label>
    <select class="form-select" id="id_cliente" name="id_cliente" required>
        <option value="">Selecciona un cliente</option>
        @foreach ($clients as $id => $name)
            <option value="{{ $id }}" @selected(old('id_cliente', $reservation->id_cliente ?? '') == $id)>{{ $name }}</option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Libros reservados</h2>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-reservation-item">
            <i class="bi bi-plus-circle"></i> Agregar libro
        </button>
    </div>

    <div id="reservation-items" class="d-flex flex-column gap-3">
        @foreach ($items as $index => $item)
            @include('reservations.partials.item-row', ['index' => $index, 'item' => $item, 'books' => $books])
        @endforeach
    </div>
    <p class="form-text mt-2">Cada registro se agrega automáticamente al historial de movimientos.</p>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('reservation-items');
                const addButton = document.getElementById('add-reservation-item');
                const template = document.getElementById('reservation-item-template');

                const handleRemove = (button) => {
                    const row = button.closest('.reservation-item');
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

<template id="reservation-item-template">
    @include('reservations.partials.item-row', ['index' => '__INDEX__', 'item' => ['id_libro' => '', 'cantidad' => 1], 'books' => $books])
</template>
<div class="row g-3">
    <div class="col-md-6">
        <label for="fecha_reserva" class="form-label">Fecha de reserva</label>
        <input type="datetime-local" class="form-control" id="fecha_reserva" name="fecha_reserva" value="{{ old('fecha_reserva', isset($reservation) ? $reservation->fecha_reserva?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
    </div>
    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" id="estado" name="estado" required>
            @foreach (App\Models\ReservationHeader::STATES as $estado)
                <option value="{{ $estado }}" @selected(old('estado', $reservation->estado ?? 'Pendiente') === $estado)>{{ $estado }}</option>
            @endforeach
        </select>
    </div>
</div>
