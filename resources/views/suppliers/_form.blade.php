<div class="mb-3">
    <label for="nombre_comercial" class="form-label">Nombre comercial</label>
    <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" value="{{ old('nombre_comercial', $supplier->nombre_comercial ?? '') }}" required>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <label for="contacto" class="form-label">Contacto</label>
        <input type="text" class="form-control" id="contacto" name="contacto" value="{{ old('contacto', $supplier->contacto ?? '') }}">
    </div>
    <div class="col-md-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $supplier->telefono ?? '') }}">
    </div>
    <div class="col-md-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo', $supplier->correo ?? '') }}">
    </div>
</div>
<div class="row g-3 mt-0">
    <div class="col-md-4">
        <label for="identificacion" class="form-label">Identificación fiscal</label>
        <input type="text" class="form-control" id="identificacion" name="identificacion" value="{{ old('identificacion', $supplier->identificacion ?? '') }}">
    </div>
    <div class="col-md-4">
        <label for="condiciones_pago" class="form-label">Condiciones de pago</label>
        <input type="text" class="form-control" id="condiciones_pago" name="condiciones_pago" value="{{ old('condiciones_pago', $supplier->condiciones_pago ?? '') }}">
    </div>
    <div class="col-md-4">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $supplier->direccion ?? '') }}">
    </div>
</div>
