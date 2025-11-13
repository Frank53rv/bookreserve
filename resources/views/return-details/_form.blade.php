<div class="mb-3">
    <label for="id_devolucion" class="form-label">Devolución</label>
    <select class="form-select" id="id_devolucion" name="id_devolucion" @disabled(isset($returnDetail)) required>
        <option value="">Selecciona una devolución</option>
        @foreach ($returns as $id => $label)
            <option value="{{ $id }}" @selected(old('id_devolucion', $returnDetail->id_devolucion ?? $returnId ?? '') == $id)>{{ $label }}</option>
        @endforeach
    </select>
    @isset($returnDetail)
        <input type="hidden" name="id_devolucion" value="{{ $returnDetail->id_devolucion }}">
    @endisset
</div>
<div class="mb-3">
    <label for="id_libro" class="form-label">Libro</label>
    <select class="form-select" id="id_libro" name="id_libro" required>
        <option value="">Selecciona un libro</option>
        @foreach ($books as $id => $title)
            <option value="{{ $id }}" @selected(old('id_libro', $returnDetail->id_libro ?? '') == $id)>{{ $title }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="cantidad_devuelta" class="form-label">Cantidad devuelta</label>
    <input type="number" class="form-control" id="cantidad_devuelta" name="cantidad_devuelta" value="{{ old('cantidad_devuelta', $returnDetail->cantidad_devuelta ?? 1) }}" min="1" required>
</div>
