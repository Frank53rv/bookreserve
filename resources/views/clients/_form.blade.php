<div class="row g-3">
    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $client->nombre ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido', $client->apellido ?? '') }}" required>
    </div>
</div>
<div class="row g-3 mt-0">
    <div class="col-md-4">
        <label for="dni" class="form-label">DNI</label>
        <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni', $client->dni ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $client->telefono ?? '') }}">
    </div>
    <div class="col-md-4">
        <label for="correo" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo', $client->correo ?? '') }}" required>
    </div>
</div>
<div class="mb-3 mt-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $client->direccion ?? '') }}">
</div>
<div class="mb-3">
    <label for="fecha_registro" class="form-label">Fecha de registro</label>
    <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" value="{{ old('fecha_registro', isset($client) ? $client->fecha_registro?->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
</div>
