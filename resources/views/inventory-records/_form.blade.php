<div class="mb-3">
    <label for="id_libro" class="form-label">Libro</label>
    <select class="form-select" id="id_libro" name="id_libro" required>
        <option value="">Selecciona un libro</option>
        @foreach ($books as $id => $title)
            <option value="{{ $id }}" @selected(old('id_libro', $inventoryRecord->id_libro ?? '') == $id)>{{ $title }}</option>
        @endforeach
    </select>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
        <input type="datetime-local" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="{{ old('fecha_ingreso', isset($inventoryRecord) ? $inventoryRecord->fecha_ingreso?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
    </div>
    <div class="col-md-6">
        <label for="cantidad_ingresada" class="form-label">Cantidad ingresada</label>
        <input type="number" class="form-control" id="cantidad_ingresada" name="cantidad_ingresada" value="{{ old('cantidad_ingresada', $inventoryRecord->cantidad_ingresada ?? 1) }}" min="1" required>
    </div>
</div>
<div class="mb-3 mt-3">
    <label for="proveedor" class="form-label">Proveedor</label>
    <input type="text" class="form-control" id="proveedor" name="proveedor" value="{{ old('proveedor', $inventoryRecord->proveedor ?? '') }}">
</div>
<div class="mb-3">
    <label for="observacion" class="form-label">Observación</label>
    <textarea class="form-control" id="observacion" name="observacion" rows="3">{{ old('observacion', $inventoryRecord->observacion ?? '') }}</textarea>
</div>
