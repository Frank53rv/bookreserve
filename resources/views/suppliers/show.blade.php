@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-building"></i> {{ $supplier->nombre_comercial }}</h1>
            <p class="panel-subtitle">Detalle del proveedor y sus medios de contacto.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.suppliers.edit', $supplier) }}" class="btn btn-outline-soft"><i class="bi bi-pencil"></i> Editar</a>
            <a href="{{ route('web.suppliers.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="data-panel-body">
        <div class="row gy-3">
            <div class="col-md-6">
                <div class="data-field">
                    <span class="data-label">Nombre comercial</span>
                    <span class="data-value">{{ $supplier->nombre_comercial }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Razón social</span>
                    <span class="data-value">{{ $supplier->razon_social ?? '—' }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">RUC</span>
                    <span class="data-value">{{ $supplier->ruc ?? '—' }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="data-field">
                    <span class="data-label">Contacto</span>
                    <span class="data-value">{{ $supplier->contacto ?? '—' }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Teléfono</span>
                    <span class="data-value">{{ $supplier->telefono ?? '—' }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Correo</span>
                    <span class="data-value">{{ $supplier->correo ?? '—' }}</span>
                </div>
            </div>
        </div>

        @if ($supplier->notas)
            <div class="data-field mt-4">
                <span class="data-label">Notas</span>
                <div class="data-value">{{ $supplier->notas }}</div>
            </div>
        @endif

        <div class="data-field mt-4">
            <span class="data-label">Última actualización</span>
            <span class="data-value">{{ $supplier->updated_at?->format('d/m/Y H:i') }}</span>
        </div>
    </div>
</section>
@endsection
