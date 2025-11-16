@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-pencil-square"></i> Editar proveedor</h1>
            <p class="panel-subtitle">Actualiza los datos de {{ $supplier->nombre_comercial }}.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.suppliers.show', $supplier) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver ficha</a>
            <a href="{{ route('web.suppliers.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="data-panel-body">
        <form action="{{ route('web.suppliers.update', $supplier) }}" method="POST" class="modern-form">
            @csrf
            @method('PUT')
            @include('suppliers._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-elevated"><i class="bi bi-check-circle"></i> Guardar cambios</button>
            </div>
        </form>
    </div>
</section>
@endsection
