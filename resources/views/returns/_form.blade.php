<div class="mb-3">
    <label for="id_cliente" class="form-label">Cliente</label>
    <select class="form-select" id="id_cliente" name="id_cliente" required>
        <option value="">Selecciona un cliente</option>
        @foreach ($clients as $id => $name)
            <option value="{{ $id }}" @selected(old('id_cliente', $return->id_cliente ?? '') == $id)>{{ $name }}</option>
        @endforeach
    </select>
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
