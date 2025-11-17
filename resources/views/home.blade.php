@extends('layouts.app')

@section('content')
@php
    $quickLinks = collect($navigationItems)
        ->reject(fn ($item) => isset($item['route']) && $item['route'] === 'home')
        ->flatMap(function ($item) {
            if (isset($item['dropdown'])) {
                return $item['dropdown'];
            }
            return [$item];
        });
    $heroMetrics = [
        [
            'label' => 'Reservas activas',
            'value' => number_format(\App\Models\ReservationHeader::query()->whereIn('estado', ['Reservado', 'Parcial'])->count()),
        ],
        [
            'label' => 'Clientes conectados',
            'value' => number_format(\App\Models\Client::count()),
        ],
        [
            'label' => 'Libros disponibles',
            'value' => number_format(\App\Models\Book::count()),
        ],
        [
            'label' => 'Ventas confirmadas',
            'value' => number_format(\App\Models\SaleHeader::count()),
        ],
    ];
@endphp

<section class="hero-spotlight">
    <div class="hero-content">
        <span class="holo-badge">
            <i class="bi bi-activity"></i>
            Flujo en vivo
        </span>
        <h1 class="hero-title">Gestiona reservas, inventario y ventas con el nuevo BookReserve.</h1>
        <p class="hero-description">Unifica todos los módulos de tu librería en una interfaz envolvente: controla lotes de compra, movimientos, reservas y tickets en segundos.</p>
        <div class="hero-actions">
            <a class="btn btn-elevated" href="{{ route('web.sales.create') }}">
                <i class="bi bi-bag-plus"></i>
                Registrar venta
            </a>
            <a class="btn-ghost" href="{{ route('web.movements.index') }}">
                <i class="bi bi-graph-up-arrow"></i>
                Ver movimientos
            </a>
        </div>
        <div class="hero-stats">
            @foreach ($heroMetrics as $metric)
                <div class="hero-stat">
                    <span class="stat-value">{{ $metric['value'] }}</span>
                    <span class="stat-label">{{ $metric['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-stars"></i> Panel principal</h1>
            <p class="panel-subtitle">Accesos rápidos para administrar cada módulo de BookReserve desde un único lugar.</p>
        </div>
    </div>

    <div class="data-panel-body">
        <div class="quick-links-grid">
            @foreach ($quickLinks as $link)
                <a href="{{ route($link['route']) }}" class="quick-link-card">
                    <span class="status-pill">
                        <i class="bi {{ $link['icon'] }}"></i>
                        {{ $link['label'] }}
                    </span>
                    <p>{{ $link['description'] }}</p>
                    <span class="cta">
                        Ir a la sección
                        <i class="bi bi-arrow-right-circle"></i>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
