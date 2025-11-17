@extends('layouts.app')

@push('styles')
<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        padding: 1.5rem;
        border-radius: 20px;
        background: radial-gradient(circle at 0% 0%, rgba(56, 189, 248, 0.12), transparent 60%),
            rgba(7, 11, 30, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 45px rgba(2, 6, 23, 0.55);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .metric-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: rgba(56, 189, 248, 0.15);
        color: var(--accent-2);
    }

    .metric-value {
        font-size: clamp(1.75rem, 3vw, 2.25rem);
        font-weight: 700;
        color: var(--text-primary);
    }

    .metric-label {
        font-size: 0.92rem;
        color: var(--text-muted);
        letter-spacing: 0.02em;
    }

    .chart-container {
        padding: 1.75rem;
        border-radius: 24px;
        background: rgba(7, 11, 30, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 22px 50px rgba(2, 6, 23, 0.6);
        margin-bottom: 1.5rem;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .chart-title {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .data-table-compact {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table-compact th {
        padding: 0.65rem 1rem;
        text-align: left;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .data-table-compact td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
    }

    .data-table-compact tr:hover td {
        background: rgba(255, 255, 255, 0.03);
    }

    .period-filter {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .period-btn {
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: transparent;
        color: var(--text-muted);
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .period-btn:hover,
    .period-btn.active {
        border-color: var(--accent-2);
        background: rgba(56, 189, 248, 0.12);
        color: var(--accent-2);
    }

    .alert-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(251, 113, 133, 0.15);
        color: var(--danger);
    }

    .chart-canvas {
        height: 300px;
        max-height: 300px;
    }

    @media (max-width: 768px) {
        .chart-canvas {
            height: 250px;
            max-height: 250px;
        }
        .metrics-grid {
            grid-template-columns: 1fr;
        }

        .chart-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div>
            <h1 class="page-heading"><i class="bi bi-speedometer2"></i> Dashboard</h1>
            <p class="page-subheading">Métricas en tiempo real y estadísticas del negocio</p>
        </div>
        <div class="period-filter">
            <a href="{{ route('dashboard', ['period' => 7]) }}" class="period-btn {{ $period == 7 ? 'active' : '' }}">7 días</a>
            <a href="{{ route('dashboard', ['period' => 30]) }}" class="period-btn {{ $period == 30 ? 'active' : '' }}">30 días</a>
            <a href="{{ route('dashboard', ['period' => 90]) }}" class="period-btn {{ $period == 90 ? 'active' : '' }}">90 días</a>
            <a href="{{ route('dashboard', ['period' => 365]) }}" class="period-btn {{ $period == 365 ? 'active' : '' }}">1 año</a>
        </div>
    </div>

    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon"><i class="bi bi-book"></i></div>
            <div class="metric-value">{{ number_format($metrics['total_books']) }}</div>
            <div class="metric-label">Libros en catálogo</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="metric-value">{{ number_format($metrics['low_stock_books']) }}</div>
            <div class="metric-label">Stock bajo (≤3)</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon"><i class="bi bi-people"></i></div>
            <div class="metric-value">{{ number_format($metrics['total_clients']) }}</div>
            <div class="metric-label">Clientes registrados</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="metric-value">{{ number_format($metrics['active_reservations']) }}</div>
            <div class="metric-label">Reservas activas</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon"><i class="bi bi-receipt"></i></div>
            <div class="metric-value">{{ number_format($metrics['total_sales']) }}</div>
            <div class="metric-label">Ventas ({{ $period }}d)</div>
        </div>
        <div class="metric-card">
            <div class="metric-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="metric-value">Gs. {{ number_format($metrics['sales_revenue'], 0, ',', '.') }}</div>
            <div class="metric-label">Ingresos ({{ $period }}d)</div>
        </div>
    </div>
</section>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="bi bi-graph-up"></i> Ventas en el tiempo</h3>
            </div>
            <canvas id="salesChart" class="chart-canvas"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="bi bi-pie-chart"></i> Reservas por estado</h3>
            </div>
            <canvas id="reservationsChart" class="chart-canvas"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="bi bi-bar-chart"></i> Ventas por categoría</h3>
            </div>
            <canvas id="categoriesChart" class="chart-canvas"></canvas>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="bi bi-exclamation-circle"></i> Alertas de stock bajo
                    @if($lowStockBooks->count())
                        <span class="alert-badge"><i class="bi bi-bell"></i> {{ $lowStockBooks->count() }}</span>
                    @endif
                </h3>
            </div>
            @if($lowStockBooks->count())
                <div style="max-height: 300px; overflow-y: auto;">
                    <table class="data-table-compact">
                        <thead>
                            <tr>
                                <th>Libro</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockBooks as $book)
                                <tr>
                                    <td>
                                        <a href="{{ route('web.books.show', $book) }}" class="text-decoration-none" style="color: var(--accent-2);">
                                            {{ $book->titulo }}
                                        </a>
                                    </td>
                                    <td>{{ $book->category?->nombre ?? 'Sin categoría' }}</td>
                                    <td>
                                        <span class="table-chip {{ $book->stock_actual === 0 ? 'danger' : 'warning' }}">
                                            {{ $book->stock_actual }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 2rem;">✅ Sin alertas de stock</p>
            @endif
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="bi bi-trophy"></i> Top 10 libros más populares</h3>
            </div>
            <table class="data-table-compact">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Reservas</th>
                        <th>Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topBooks as $index => $book)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('web.books.show', $book) }}" class="text-decoration-none" style="color: var(--accent-2);">
                                    {{ Str::limit($book->titulo, 40) }}
                                </a>
                            </td>
                            <td>{{ $book->reservations_count }}</td>
                            <td>{{ $book->sales_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title"><i class="bi bi-star"></i> Top 10 clientes más activos</h3>
            </div>
            <table class="data-table-compact">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Reservas</th>
                        <th>Compras</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topClients as $index => $client)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('web.clients.show', $client) }}" class="text-decoration-none" style="color: var(--accent-2);">
                                    {{ $client->nombre }} {{ $client->apellido }}
                                </a>
                            </td>
                            <td>{{ $client->reservations_count }}</td>
                            <td>{{ $client->sales_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart.js default config
    Chart.defaults.color = 'rgba(226, 232, 240, 0.7)';
    Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.08)';
    Chart.defaults.font.family = "'Space Grotesk', 'Plus Jakarta Sans', system-ui";

    // Sales over time chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: 'Ventas',
                data: @json($salesChart->pluck('count')),
                borderColor: '#38bdf8',
                backgroundColor: 'rgba(56, 189, 248, 0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // Reservations by status chart
    const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
    new Chart(reservationsCtx, {
        type: 'doughnut',
        data: {
            labels: @json($reservationsByStatus->keys()),
            datasets: [{
                data: @json($reservationsByStatus->values()),
                backgroundColor: ['#38bdf8', '#c084fc', '#f472b6', '#4ade80'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Sales by category chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(categoriesCtx, {
        type: 'bar',
        data: {
            labels: @json($salesByCategory->pluck('nombre')),
            datasets: [{
                label: 'Ingresos (Gs.)',
                data: @json($salesByCategory->pluck('total_revenue')),
                backgroundColor: 'rgba(192, 132, 252, 0.6)',
                borderColor: '#c084fc',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
