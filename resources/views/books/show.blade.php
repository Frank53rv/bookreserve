@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">{{ $book->titulo }}</h1>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Autor</dt>
                    <dd class="col-sm-8">{{ $book->autor }}</dd>
                    <dt class="col-sm-4">Editorial</dt>
                    <dd class="col-sm-8">{{ $book->editorial?->nombre ?? 'Sin registrar' }}</dd>
                    <dt class="col-sm-4">Año</dt>
                    <dd class="col-sm-8">{{ $book->anio_publicacion ?? 'Sin registrar' }}</dd>
                    <dt class="col-sm-4">ISBN</dt>
                    <dd class="col-sm-8">{{ $book->isbn ?? 'Sin registrar' }}</dd>
                    <dt class="col-sm-4">Categoría</dt>
                    <dd class="col-sm-8">{{ $book->category?->nombre ?? 'Sin categoría' }}</dd>
                    <dt class="col-sm-4">Precio de venta</dt>
                    <dd class="col-sm-8">${{ number_format($book->precio_venta ?? 0, 2) }}</dd>
                    <dt class="col-sm-4">Stock actual</dt>
                    <dd class="col-sm-8">{{ $book->stock_actual }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">{{ $book->estado }}</dd>
                </dl>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('web.books.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('web.books.edit', $book) }}" class="btn btn-primary">Editar</a>
                    <form action="{{ route('web.books.destroy', $book) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar libro?')">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
