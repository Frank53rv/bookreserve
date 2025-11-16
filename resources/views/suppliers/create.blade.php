@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-person-plus"></i> Nuevo proveedor</h1>
            <p class="panel-subtitle">Registra la información comercial para futuros ingresos.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.suppliers.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="data-panel-body">
        <form action="{{ route('web.suppliers.store') }}" method="POST" class="modern-form">
            @csrf
            @include('suppliers._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-elevated"><i class="bi bi-check-circle"></i> Guardar proveedor</button>
            </div>
        </form>
    </div>
</section>
@endsection
