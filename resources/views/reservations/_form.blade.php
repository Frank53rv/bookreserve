<div class="mb-3">
    <label for="id_cliente" class="form-label">Cliente</label>
    <select class="form-select" id="id_cliente" name="id_cliente" required>
        <option value="">Selecciona un cliente</option>
        @foreach ($clients as $id => $name)
            <option value="{{ $id }}" @selected(old('id_cliente', $reservation->id_cliente ?? '') == $id)>{{ $name }}</option>
        @endforeach
    </select>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <label for="fecha_reserva" class="form-label">Fecha de reserva</label>
        <input type="datetime-local" class="form-control" id="fecha_reserva" name="fecha_reserva" value="{{ old('fecha_reserva', isset($reservation) ? $reservation->fecha_reserva?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
    </div>
    <div class="col-md-6">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" id="estado" name="estado" required>
            @foreach (['Pendiente', 'Retirado', 'Cancelado'] as $estado)
                <option value="{{ $estado }}" @selected(old('estado', $reservation->estado ?? 'Pendiente') === $estado)>{{ $estado }}</option>
            @endforeach
        </select>
    </div>
</div>
