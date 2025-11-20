@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-file-earmark-bar-graph"></i> Reportes Avanzados</h1>
            <p class="panel-subtitle">Genera reportes detallados en PDF o Excel para análisis del negocio</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.reports.create') }}" class="btn btn-primary btn-elevated">
                <i class="bi bi-plus-circle"></i> Generar Reporte
            </a>
        </div>
    </div>

    @if ($reportes->count())
        <div class="modern-table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Formato</th>
                        <th>Generado</th>
                        <th>Generado por</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportes as $reporte)
                        <tr>
                            <td class="table-cell-title">
                                <i class="bi bi-file-earmark-text"></i>
                                {{ $reporte->nombre }}
                            </td>
                            <td>
                                <span class="table-chip">
                                    {{ $tipos[$reporte->tipo] ?? $reporte->tipo }}
                                </span>
                            </td>
                            <td>
                                <span class="table-chip {{ $reporte->formato === 'pdf' ? 'danger' : 'success' }}">
                                    <i class="bi bi-filetype-{{ $reporte->formato }}"></i>
                                    {{ strtoupper($reporte->formato) }}
                                </span>
                            </td>
                            <td class="table-cell-note">
                                <i class="bi bi-calendar3"></i>
                                {{ $reporte->fecha_generacion?->format('d/m/Y H:i') }}
                            </td>
                            <td class="table-cell-note">
                                <i class="bi bi-person"></i>
                                {{ $reporte->generado_por ?? 'Sistema' }}
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('web.reports.download', $reporte) }}" class="btn btn-outline-soft">
                                        <i class="bi bi-download"></i> Descargar
                                    </a>
                                    <form action="{{ route('web.reports.destroy', $reporte) }}" method="POST" onsubmit="return confirm('¿Eliminar reporte?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-soft btn-outline-danger">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($reportes->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $reportes->links() }}
            </div>
        @endif
    @else
        <div class="panel-empty">
            <h3>No hay reportes generados</h3>
            <p>Comienza generando tu primer reporte para análisis de datos.</p>
            <a href="{{ route('web.reports.create') }}" class="btn btn-primary btn-elevated mt-3">
                <i class="bi bi-plus-circle"></i> Generar Primer Reporte
            </a>
        </div>
    @endif
</section>
@endsection
