<div class="reservation-item border rounded-3 p-3 bg-white shadow-sm">
    <div class="row g-3 align-items-end">
        <div class="col-lg-7">
            <label class="form-label">Libro</label>
            <select class="form-select" data-name="items[__INDEX__][id_libro]" name="items[{{ $index }}][id_libro]" required>
                <option value="">Selecciona un libro</option>
                @foreach ($books as $bookId => $bookTitle)
                    <option value="{{ $bookId }}" @selected((string)($item['id_libro'] ?? '') === (string)$bookId)>{{ $bookTitle }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <label class="form-label">Cantidad</label>
            <input type="number" class="form-control" min="1" data-name="items[__INDEX__][cantidad]" name="items[{{ $index }}][cantidad]" value="{{ $item['cantidad'] ?? 1 }}" required>
        </div>
        <div class="col-lg-2 text-lg-end">
            <button type="button" class="btn btn-outline-danger w-100" data-remove-item>
                <i class="bi bi-x-circle"></i>
                <span class="ms-1">Quitar</span>
            </button>
        </div>
    </div>
</div>
