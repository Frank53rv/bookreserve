<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $category->nombre ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $category->descripcion ?? '') }}</textarea>
</div>
