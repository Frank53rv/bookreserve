@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">{{ $category->nombre }}</h1>
                <p class="mb-2"><strong>Descripción:</strong></p>
                <p class="mb-4">{{ $category->descripcion ?? 'Sin descripción registrada.' }}</p>
                <p class="text-muted mb-0">Creada el {{ $category->created_at?->format('d/m/Y H:i') }}</p>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('web.categories.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <div class="d-flex gap-2">
                    <a href="{{ route('web.categories.edit', $category) }}" class="btn btn-primary">Editar</a>
                    <form action="{{ route('web.categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar categoría?')">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
