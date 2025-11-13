@extends('layouts.app')

@push('styles')
<style>
    .book-hero {
        padding: clamp(2.25rem, 3vw, 3rem);
    }

    .book-hero-inner {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .book-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .book-hero-metric {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1rem;
        border-radius: 999px;
        background: #e2e8f0;
        font-weight: 600;
        color: #0f172a;
    }

    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
    }

    .book-card {
        position: relative;
        overflow: hidden;
        padding: 1.75rem;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        box-shadow: 0 22px 42px rgba(15, 23, 42, 0.12);
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
        min-height: 230px;
    }

    .book-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #dbeafe 0%, #bae6fd 45%, #f0f9ff 100%);
        z-index: 0;
        opacity: 0.4;
    }

    .book-card > * {
        position: relative;
        z-index: 1;
    }

    .book-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .book-card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .book-card-subtitle {
        color: rgba(15, 24, 46, 0.6);
        font-size: 0.95rem;
    }

    .book-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.75rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #e0e7ff;
        color: #1d4ed8;
    }

    .book-chip-status {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.75rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #dcfce7;
        color: #15803d;
    }

    .book-chip-status.is-low {
        background: #fef3c7;
        color: #b45309;
    }

    .book-chip-status.is-out {
        background: #fee2e2;
        color: #b91c1c;
    }

    .book-preview {
        position: absolute;
        inset: 15px;
        padding: 1.4rem;
        border-radius: 20px;
        background: #0f172a;
        color: #e2e8f0;
        box-shadow: 0 20px 48px rgba(15, 23, 42, 0.45);
        opacity: 0;
        transform: translateY(10px) scale(0.97);
        transition: opacity 0.2s ease, transform 0.25s ease;
        pointer-events: none;
        display: grid;
        gap: 0.8rem;
    }

    .book-card:hover .book-preview,
    .book-card:focus-within .book-preview {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .book-preview h3 {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .book-preview p {
        margin: 0;
        font-size: 0.92rem;
        line-height: 1.45;
    }

    .book-preview-meta {
        display: grid;
        gap: 0.6rem;
        font-size: 0.85rem;
        color: #cbd5f5;
    }

    .book-preview-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .book-card-footer {
        margin-top: auto;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .empty-state {
        padding: 2.5rem;
    }

    .empty-state-inner {
        text-align: center;
        color: rgba(15, 24, 46, 0.65);
    }

    .empty-state-inner h3 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }

    @media (max-width: 576px) {
        .book-card {
            padding: 1.5rem;
            min-height: auto;
        }

        .book-preview {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<section class="modern-surface book-hero mb-4">
    <div class="book-hero-inner">
        <div>
            <h1 class="page-heading"><i class="bi bi-bookshelf"></i> Catálogo de libros</h1>
            <p class="page-subheading">Gestiona rápidamente el inventario, visualiza categorías y controla los préstamos con un vistazo.</p>
        </div>
        <div class="book-hero-actions">
            <a href="{{ route('web.books.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nuevo libro</a>
            <span class="book-hero-metric">
                <i class="bi bi-collection"></i>
                <span>En catálogo:</span>
                <strong>{{ $books->total() }}</strong>
            </span>
        </div>
    </div>
</section>

@if ($books->count())
    <section class="book-grid mb-4">
        @foreach ($books as $book)
            @php
                $stock = (int) $book->stock_actual;
                $statusModifier = '';
                if ($stock === 0) {
                    $statusModifier = 'is-out';
                } elseif ($stock < 3) {
                    $statusModifier = 'is-low';
                }

                $summary = $book->editorial
                    ? 'Publicado por ' . $book->editorial . ' en ' . ($book->anio_publicacion ?? 'un año sin registrar') . '.'
                    : 'Este título aún no tiene editorial registrada. Completa la ficha para mejorar la información disponible.';

                $reservationCount = $book->reservation_details_count ?? 0;
            @endphp
            <article class="book-card">
                <div class="book-card-header">
                    <div>
                        <h2 class="book-card-title"><i class="bi bi-journal-text me-2"></i>{{ $book->titulo }}</h2>
                        <p class="book-card-subtitle"><i class="bi bi-pen"></i> {{ $book->autor }}</p>
                    </div>
                    <span class="book-chip"><i class="bi bi-folder"></i>{{ $book->category?->nombre ?? 'Sin categoría' }}</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="book-chip-status{{ $statusModifier ? ' ' . $statusModifier : '' }}"><i class="bi bi-box-seam"></i> Stock: {{ $stock }}</span>
                    <span class="book-chip"><i class="bi bi-lightning"></i> Estado: {{ ucfirst($book->estado) }}</span>
                </div>
                <div class="book-card-footer">
                    <a href="{{ route('web.books.show', $book) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                    <a href="{{ route('web.books.edit', $book) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                    <form action="{{ route('web.books.destroy', $book) }}" method="POST" onsubmit="return confirm('¿Eliminar libro?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-soft btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
                    </form>
                </div>
                <div class="book-preview">
                    <h3><i class="bi bi-info-circle"></i> Vista rápida</h3>
                    <p>{{ \Illuminate\Support\Str::limit($summary, 150) }}</p>
                    <div class="book-preview-meta">
                        <span><i class="bi bi-hash"></i> ISBN: {{ $book->isbn ?? 'Sin registro' }}</span>
                        <span><i class="bi bi-calendar3"></i> Año de publicación: {{ $book->anio_publicacion ?? 'Pendiente' }}</span>
                        <span><i class="bi bi-graph-up"></i> Reservas activas: {{ $reservationCount }}</span>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    @if ($books->hasPages())
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    @endif
@else
    <section class="modern-surface empty-state">
        <div class="empty-state-inner">
            <h3>No hay libros todavía</h3>
            <p>Comienza registrando tus primeros títulos y mantén el inventario listo para las reservas.</p>
            <a href="{{ route('web.books.create') }}" class="btn btn-primary btn-elevated">Registrar libro</a>
        </div>
    </section>
@endif
@endsection
