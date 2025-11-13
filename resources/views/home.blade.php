@extends('layouts.app')

@section('content')
@php
    $quickLinks = collect($navigationItems)->reject(fn ($item) => $item['route'] === 'home');
@endphp

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
