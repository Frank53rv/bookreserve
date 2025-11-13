<div class="mb-3">
    <label for="id_reserva" class="form-label">Reserva</label>
    <select class="form-select" id="id_reserva" name="id_reserva" @disabled(isset($reservationDetail)) required>
        <option value="">Selecciona una reserva</option>
        @foreach ($reservations as $id => $label)
            <option value="{{ $id }}" @selected(old('id_reserva', $reservationDetail->id_reserva ?? $reservationId ?? '') == $id)>{{ $label }}</option>
        @endforeach
    </select>
    @isset($reservationDetail)
        <input type="hidden" name="id_reserva" value="{{ $reservationDetail->id_reserva }}">
    @endisset
</div>
<div class="mb-3">
    <label for="id_libro" class="form-label">Libro</label>
    <select class="form-select" id="id_libro" name="id_libro" required>
        <option value="">Selecciona un libro</option>
        @foreach ($books as $id => $title)
            <option value="{{ $id }}" @selected(old('id_libro', $reservationDetail->id_libro ?? '') == $id)>{{ $title }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="cantidad" class="form-label">Cantidad</label>
    <input type="number" class="form-control" id="cantidad" name="cantidad" value="{{ old('cantidad', $reservationDetail->cantidad ?? 1) }}" min="1" required>
</div>
