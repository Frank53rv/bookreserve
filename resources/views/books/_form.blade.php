<div class="mb-3">
    <label for="titulo" class="form-label">Título</label>
    <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $book->titulo ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="autor" class="form-label">Autor</label>
    <input type="text" class="form-control" id="autor" name="autor" value="{{ old('autor', $book->autor ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="cover_image" class="form-label">Portada del libro</label>
    @if (isset($book) && $book->cover_image)
        <div class="mb-2">
            <img src="{{ Storage::url($book->cover_image) }}" alt="Current cover" class="img-thumbnail" style="max-width: 200px;">
        </div>
    @endif
    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/jpg,image/webp">
    <small class="form-text text-muted">Formatos permitidos: JPEG, PNG, JPG, WEBP (máx. 2MB)</small>
</div>
<div class="mb-3">
    <label for="id_editorial" class="form-label">Editorial</label>
    <select class="form-select" id="id_editorial" name="id_editorial">
        <option value="">Selecciona una editorial</option>
        @foreach ($editorials as $id => $name)
            <option value="{{ $id }}" @selected(old('id_editorial', $book->id_editorial ?? '') == $id)>{{ $name }}</option>
        @endforeach
    </select>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <label for="anio_publicacion" class="form-label">Año de publicación</label>
        <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion" value="{{ old('anio_publicacion', $book->anio_publicacion ?? '') }}" min="1500" max="{{ now()->year + 1 }}">
    </div>
    <div class="col-md-4">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}">
    </div>
    <div class="col-md-4">
        <label for="id_categoria" class="form-label">Categoría</label>
        <select class="form-select" id="id_categoria" name="id_categoria" required>
            <option value="">Selecciona una categoría</option>
            @foreach ($categories as $id => $name)
                <option value="{{ $id }}" @selected(old('id_categoria', $book->id_categoria ?? '') == $id)>{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row g-3 mt-0">
    <div class="col-md-4">
        <label for="stock_actual" class="form-label">Stock actual</label>
        <input type="number" class="form-control" id="stock_actual" name="stock_actual" value="{{ old('stock_actual', $book->stock_actual ?? 0) }}" min="0" required>
    </div>
    <div class="col-md-4">
        <label for="precio_venta" class="form-label">Precio de venta</label>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" value="{{ old('precio_venta', $book->precio_venta ?? 0) }}" min="0" required>
        </div>
    </div>
    <div class="col-md-4">
        <label for="estado" class="form-label">Estado</label>
        <select class="form-select" id="estado" name="estado" required>
            <option value="Disponible" @selected(old('estado', $book->estado ?? 'Disponible') === 'Disponible')>Disponible</option>
            <option value="No disponible" @selected(old('estado', $book->estado ?? 'Disponible') === 'No disponible')>No disponible</option>
        </select>
    </div>
</div>
