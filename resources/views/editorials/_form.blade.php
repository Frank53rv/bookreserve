<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $editorial->nombre ?? '') }}" required>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <label for="pais" class="form-label">País</label>
        <input type="text" class="form-control" id="pais" name="pais" value="{{ old('pais', $editorial->pais ?? '') }}">
    </div>
    <div class="col-md-6">
        <label for="contacto" class="form-label">Contacto</label>
        <input type="text" class="form-control" id="contacto" name="contacto" value="{{ old('contacto', $editorial->contacto ?? '') }}">
    </div>
</div>
<div class="mb-3 mt-3">
    <label for="sitio_web" class="form-label">Sitio web</label>
    <input type="url" class="form-control" id="sitio_web" name="sitio_web" value="{{ old('sitio_web', $editorial->sitio_web ?? '') }}">
</div>
