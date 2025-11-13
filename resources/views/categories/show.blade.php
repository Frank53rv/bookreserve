@extends('layouts.app')

@php
    $bookCount = $category->books()->count();
@endphp

@push('styles')
<style>
    .category-layout {
        display: grid;
        gap: 2rem;
    }

    .category-hero {
        position: relative;
        overflow: hidden;
        padding: clamp(2rem, 4vw, 3rem);
    }

    .category-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #e2e8f0 0%, #dbeafe 55%, #f8fafc 100%);
        opacity: 1;
        pointer-events: none;
    }

    .category-hero > * {
        position: relative;
        z-index: 1;
    }

    .category-identity {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .category-identity h1 {
        font-size: clamp(2.2rem, 4vw, 3rem);
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
    }

    .category-identity p {
        font-size: 1.05rem;
        color: rgba(15, 24, 46, 0.75);
        max-width: 48rem;
    }

    .category-metric {
        background: #0f172a;
        color: #e2e8f0;
        border-radius: 22px;
        padding: 1.5rem 2rem;
        min-width: 220px;
        text-align: right;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.35);
    }

    .category-metric-value {
        font-size: clamp(2.25rem, 4vw, 3.25rem);
        font-weight: 700;
        line-height: 1;
    }

    .category-metric-label {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.95rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        opacity: 0.7;
    }

    .category-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        font-size: 0.95rem;
        color: rgba(15, 24, 46, 0.65);
    }

    .category-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
    }

    .category-meta-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        background: #38bdf8;
        box-shadow: 0 0 0 5px #e0f2fe;
    }

    .category-actions {
        padding: clamp(1.75rem, 3vw, 2.5rem);
        display: grid;
        gap: 1.5rem;
    }

    .category-actions header {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .category-actions header h2 {
        font-size: 1.35rem;
        font-weight: 600;
        margin-bottom: 0;
    }

    .category-actions header span {
        color: rgba(15, 24, 46, 0.6);
        font-size: 0.95rem;
    }

    .category-action-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem;
        align-items: center;
    }

    .secondary-surface {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        display: inline-flex;
        gap: 1rem;
        align-items: center;
        font-weight: 500;
        color: rgba(15, 24, 46, 0.7);
    }

    .secondary-surface strong {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 1.1rem;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .category-hero {
            padding: 1.75rem;
        }

        .category-metric {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="category-layout">
    <section class="modern-surface category-hero">
        <div class="d-flex flex-column flex-lg-row align-items-lg-start justify-content-between gap-4">
            <div class="category-identity">
                <span class="status-pill"><i class="bi bi-award"></i> Colección destacada</span>
                <h1>{{ $category->nombre }}</h1>
                <p>{{ $category->descripcion ?? 'Sin descripción registrada para esta categoría. Agrega notas breves para que el equipo identifique su propósito.' }}</p>
            </div>
            <div class="category-metric">
                <span class="category-metric-value">{{ $bookCount }}</span>
                <span class="category-metric-label"><i class="bi bi-journal-bookmark"></i> Libros asociados</span>
            </div>
        </div>
        <div class="surface-divider"></div>
        <div class="category-meta">
            <span class="category-meta-item">
                <span class="category-meta-dot"></span>
                Creada el {{ $category->created_at?->timezone(config('app.timezone'))->locale(app()->getLocale())->translatedFormat('d \d\e F, Y') }}
            </span>
            <span class="category-meta-item">
                <span class="category-meta-dot"></span>
                Identificador interno: {{ $category->id_categoria }}
            </span>
        </div>
    </section>

    <section class="modern-surface category-actions">
        <header>
            <h2>Acciones rápidas</h2>
            <span>Gestiona esta categoría sin salir de la vista de detalle.</span>
        </header>
        <div class="category-action-toolbar">
            <a href="{{ route('web.categories.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Volver al listado</a>
            <a href="{{ route('web.categories.edit', $category) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil-square"></i> Editar categoría</a>
            <form action="{{ route('web.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Eliminar categoría?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-soft btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
            </form>
        </div>
        <div class="secondary-surface">
            <strong><i class="bi bi-lightbulb"></i> ¿Qué sigue?</strong>
            <span>Agrega nuevos libros para mantener el catálogo siempre al día.</span>
        </div>
    </section>
</div>
@endsection
