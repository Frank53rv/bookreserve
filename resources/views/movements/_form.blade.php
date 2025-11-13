<div class="row g-3">
    <div class="col-md-6">
        <label for="id_cliente" class="form-label">Cliente</label>
        <select class="form-select" id="id_cliente" name="id_cliente" required>
            <option value="">Selecciona un cliente</option>
            @foreach ($clients as $id => $name)
                <option value="{{ $id }}" @selected(old('id_cliente', $movement->id_cliente ?? '') == $id)>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="id_libro" class="form-label">Libro</label>
        <select class="form-select" id="id_libro" name="id_libro" required>
            <option value="">Selecciona un libro</option>
            @foreach ($books as $id => $title)
                <option value="{{ $id }}" @selected(old('id_libro', $movement->id_libro ?? '') == $id)>{{ $title }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row g-3 mt-0">
    <div class="col-md-6">
        <label for="tipo_movimiento" class="form-label">Tipo de movimiento</label>
        <select class="form-select" id="tipo_movimiento" name="tipo_movimiento" required>
            @foreach ($movementTypes as $value => $label)
                <option value="{{ $value }}" @selected(old('tipo_movimiento', $movement->tipo_movimiento ?? 'Entrada') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="fecha_movimiento" class="form-label">Fecha</label>
        <input type="datetime-local" class="form-control" id="fecha_movimiento" name="fecha_movimiento" value="{{ old('fecha_movimiento', isset($movement) ? $movement->fecha_movimiento?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
    </div>
</div>
<div class="row g-3 mt-0">
    <div class="col-md-4">
        <label for="cantidad" class="form-label">Cantidad</label>
        <input type="number" class="form-control" id="cantidad" name="cantidad" value="{{ old('cantidad', $movement->cantidad ?? 1) }}" min="1" required>
    </div>
    <div class="col-md-8">
        <label for="observacion" class="form-label">Observación</label>
        <input type="text" class="form-control" id="observacion" name="observacion" value="{{ old('observacion', $movement->observacion ?? '') }}">
    </div>
</div>
