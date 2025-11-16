@php
    $bookCollection = $books instanceof \Illuminate\Support\Collection ? $books : collect($books);
    $selectedId = $item['id_libro'] ?? '';
    $selectedBook = $bookCollection->firstWhere('id_libro', (int) $selectedId);
    $selectedStock = (int) ($selectedBook->stock_actual ?? 0);
    $hasSelection = filled($selectedId);
    $stockLabel = match (true) {
        ! $hasSelection => 'Sin seleccionar',
        $selectedStock <= 0 => 'Sin stock',
        $selectedStock < 3 => 'Stock bajo',
        default => 'Stock disponible',
    };
    $stockClass = match (true) {
        ! $hasSelection => 'bg-secondary-subtle text-secondary-emphasis',
        $selectedStock <= 0 => 'bg-danger-subtle text-danger-emphasis',
        $selectedStock < 3 => 'bg-warning-subtle text-warning-emphasis',
        default => 'bg-success-subtle text-success-emphasis',
    };
@endphp

<div class="reservation-item border rounded-3 p-3 bg-white shadow-sm" data-reservation-item>
    <div class="row g-3 align-items-end">
        <div class="col-lg-7">
            <label class="form-label">Libro</label>
            <select class="form-select" data-name="items[__INDEX__][id_libro]" name="items[{{ $index }}][id_libro]" data-book-select required>
                <option value="" data-stock="0" data-title="Ningún libro seleccionado">Selecciona un libro</option>
                @foreach ($bookCollection as $book)
                    @php
                        $optionStock = (int) $book->stock_actual;
                        $isSelected = (string) $selectedId === (string) $book->id_libro;
                        $disableOption = $optionStock === 0 && ! $isSelected;
                    @endphp
                    <option
                        value="{{ $book->id_libro }}"
                        data-stock="{{ $optionStock }}"
                        data-title="{{ $book->titulo }}"
                        @selected($isSelected)
                        @if($disableOption) disabled @endif
                    >
                        {{ $book->titulo }} · {{ $optionStock }} disp.
                        @if($disableOption)
                            (Sin stock)
                        @endif
                    </option>
                @endforeach
            </select>
            <div class="d-flex align-items-center gap-2 mt-2 small text-muted">
                <span class="badge {{ $stockClass }}" data-stock-pill>{{ $stockLabel }}</span>
                <span data-stock-message>
                    @if (! $hasSelection)
                        Selecciona un libro para revisar el stock disponible.
                    @elseif ($selectedStock > 0)
                        {{ $selectedStock }} ejemplar{{ $selectedStock === 1 ? '' : 'es' }} disponible{{ $selectedStock === 1 ? '' : 's' }}
                    @else
                        No hay stock disponible para el título seleccionado.
                    @endif
                </span>
            </div>
        </div>
        <div class="col-lg-3">
            <label class="form-label">Cantidad</label>
            <input type="number" class="form-control" min="1" data-name="items[__INDEX__][cantidad]" name="items[{{ $index }}][cantidad]" value="{{ $item['cantidad'] ?? 1 }}" data-quantity-input required>
        </div>
        <div class="col-lg-2 text-lg-end">
            <button type="button" class="btn btn-outline-danger w-100" data-remove-item>
                <i class="bi bi-x-circle"></i>
                <span class="ms-1">Quitar</span>
            </button>
        </div>
    </div>
</div>
