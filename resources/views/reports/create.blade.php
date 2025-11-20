@extends('layouts.app')

@push('styles')
<style>
    .report-config {
        display: grid;
        gap: 2rem;
    }

    .config-section {
        padding: 1.75rem;
        border-radius: 20px;
        background: rgba(7, 11, 30, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .config-section-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
    }

    .config-section-title i {
        color: var(--accent-2);
    }

    .modern-form {
        display: grid;
        gap: 1.25rem;
    }

    .form-group-inline {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<section class="modern-surface data-panel">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-graph-up-arrow"></i> Generar Reporte</h1>
            <p class="panel-subtitle">Configura los parámetros para tu reporte personalizado</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.reports.index') }}" class="btn btn-outline-soft">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('web.reports.generate') }}" method="POST" class="report-config">
        @csrf

        <div class="config-section">
            <h2 class="config-section-title">
                <i class="bi bi-gear"></i> Configuración General
            </h2>

            <div class="modern-form">
                <div>
                    <label for="nombre" class="form-label">Nombre del Reporte</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="form-group-inline">
                    <div>
                        <label for="tipo" class="form-label">Tipo de Reporte</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="">Selecciona un tipo</option>
                            @foreach ($tipos as $key => $label)
                                <option value="{{ $key }}" @selected(old('tipo') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="formato" class="form-label">Formato de Salida</label>
                        <select class="form-select" id="formato" name="formato" required>
                            @foreach ($formatos as $key => $label)
                                <option value="{{ $key }}" @selected(old('formato', 'pdf') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('formato')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="config-section">
            <h2 class="config-section-title">
                <i class="bi bi-funnel"></i> Filtros y Parámetros
            </h2>

            <div class="modern-form">
                <div class="form-group-inline">
                    <div>
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}">
                        @error('fecha_inicio')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}">
                        @error('fecha_fin')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div id="filtro-categoria" class="filter-group" style="display: none;">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $id => $nombre)
                            <option value="{{ $id }}" @selected(old('categoria') == $id)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="filtro-estado" class="filter-group" style="display: none;">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="Disponible">Disponible</option>
                        <option value="No disponible">No disponible</option>
                    </select>
                </div>

                <div id="filtro-stock-bajo" class="filter-group" style="display: none;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="stock_bajo" name="stock_bajo" value="1" @checked(old('stock_bajo'))>
                        <label class="form-check-label" for="stock_bajo">
                            Solo mostrar libros con stock bajo (≤3)
                        </label>
                    </div>
                </div>

                <div id="filtro-tipo-movimiento" class="filter-group" style="display: none;">
                    <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
                    <select class="form-select" id="tipo_movimiento" name="tipo_movimiento">
                        <option value="">Todos los tipos</option>
                        <option value="Entrada">Entrada</option>
                        <option value="Salida">Salida</option>
                        <option value="Ajuste">Ajuste</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" id="btnPreview" class="btn btn-outline-soft">
                <i class="bi bi-eye"></i> Vista Previa
            </button>
            <button type="submit" class="btn btn-primary btn-elevated">
                <i class="bi bi-download"></i> Generar y Descargar
            </button>
        </div>
    </form>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tipoSelect = document.getElementById('tipo');
        const filtros = {
            'inventario': ['filtro-categoria', 'filtro-estado', 'filtro-stock-bajo'],
            'ventas': ['filtro-estado'],
            'reservas': ['filtro-estado'],
            'movimientos': ['filtro-tipo-movimiento'],
            'clientes': [],
            'financiero': [],
        };

        function actualizarFiltros() {
            // Ocultar todos los filtros
            document.querySelectorAll('.filter-group').forEach(el => el.style.display = 'none');
            
            // Mostrar filtros relevantes
            const tipo = tipoSelect.value;
            if (filtros[tipo]) {
                filtros[tipo].forEach(filtroId => {
                    const el = document.getElementById(filtroId);
                    if (el) el.style.display = 'block';
                });
            }
        }

        tipoSelect.addEventListener('change', actualizarFiltros);
        actualizarFiltros();

        // Vista previa
        document.getElementById('btnPreview').addEventListener('click', () => {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            window.open(`{{ route('web.reports.preview') }}?${params}`, '_blank');
        });
    });
</script>
@endpush
@endsection
