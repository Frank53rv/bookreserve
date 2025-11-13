@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="h3 mb-3">Registrar movimiento</h1>
        @include('components.validation-errors')
        <form action="{{ route('web.movements.store') }}" method="POST" class="card card-body shadow-sm">
            @csrf
            @include('movements._form')
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('web.movements.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
