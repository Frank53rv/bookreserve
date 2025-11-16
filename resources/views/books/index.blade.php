@extends('layouts.app')

@push('styles')
<style>
    .book-hero {
        padding: clamp(2.25rem, 3vw, 3rem);
        background: rgba(7, 11, 30, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 28px;
        box-shadow: 0 25px 60px rgba(2, 6, 23, 0.55);
        backdrop-filter: blur(16px);
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
        background: rgba(56, 189, 248, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.35);
        font-weight: 600;
        color: var(--text-primary);
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
        background: radial-gradient(circle at 0% 0%, rgba(56, 189, 248, 0.15), transparent 55%),
            radial-gradient(circle at 100% 0%, rgba(192, 132, 252, 0.18), transparent 45%),
            rgba(7, 9, 28, 0.92);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 22px 50px rgba(2, 6, 23, 0.6);
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
        min-height: 250px;
    }

    .book-cover-wrapper {
        width: 100%;
        height: 220px;
        border-radius: 16px;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
    }

    .book-cover-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-cover-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 2.5rem;
    }

    .book-cover-placeholder small {
        font-size: 0.85rem;
    }

    .book-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        border: 1px solid rgba(255, 255, 255, 0.04);
        pointer-events: none;
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
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .book-card-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .book-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.08);
        color: var(--text-primary);
    }

    .book-chip-status {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(74, 222, 128, 0.12);
        color: #bbf7d0;
    }

    .book-chip-status.is-low {
        background: rgba(250, 204, 21, 0.12);
        color: #fef08a;
    }

    .book-chip-status.is-out {
        background: rgba(251, 113, 133, 0.15);
        color: #fecdd3;
    }

    .book-card-footer {
        margin-top: auto;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .book-insight {
        padding: 1.25rem;
        border-radius: 18px;
        background: rgba(8, 12, 38, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: grid;
        gap: 0.8rem;
    }

    .book-insight h3 {
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        color: var(--text-primary);
    }

    .book-insight p {
        margin: 0;
        font-size: 0.92rem;
        line-height: 1.45;
        color: var(--text-muted);
    }

    .book-insight-meta {
        display: grid;
        gap: 0.55rem;
        font-size: 0.85rem;
        color: rgba(226, 232, 240, 0.85);
    }

    .book-insight-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .empty-state {
        padding: 2.5rem;
        background: rgba(7, 11, 30, 0.85);
        border-radius: 28px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-align: center;
    }

    .empty-state-inner {
        color: var(--text-muted);
    }

    .empty-state-inner h3 {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    @media (max-width: 576px) {
        .book-card {
            padding: 1.5rem;
            min-height: auto;
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

                $summary = $book->editorial?->nombre
                    ? 'Publicado por ' . $book->editorial?->nombre . ' en ' . ($book->anio_publicacion ?? 'un año sin registrar') . '.'
                    : 'Este título aún no tiene editorial registrada. Completa la ficha para mejorar la información disponible.';

                $reservationCount = $book->reservation_details_count ?? 0;
            @endphp
            <article class="book-card">
                <div class="book-cover-wrapper">
                    @if ($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->titulo }}">
                    @else
                        <div class="book-cover-placeholder">
                            <i class="bi bi-book"></i>
                            <small>Sin portada</small>
                        </div>
                    @endif
                </div>
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
                    <span class="book-chip"><i class="bi bi-cash-coin"></i> ${{ number_format($book->precio_venta ?? 0, 2) }}</span>
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
                <div class="book-insight">
                    <h3><i class="bi bi-info-circle"></i> Vista rápida</h3>
                    <p>{{ \Illuminate\Support\Str::limit($summary, 150) }}</p>
                    <div class="book-insight-meta">
                        <span><i class="bi bi-hash"></i> ISBN: {{ $book->isbn ?? 'Sin registro' }}</span>
                        <span><i class="bi bi-calendar3"></i> Año publicación: {{ $book->anio_publicacion ?? 'Pendiente' }}</span>
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
