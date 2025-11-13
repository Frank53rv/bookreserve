@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="h3 mb-3">Editar devolución</h1>
        @include('components.validation-errors')
        <form action="{{ route('web.returns.update', $return) }}" method="POST" class="card card-body shadow-sm">
            @csrf
            @method('PUT')
            @include('returns._form')
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('web.returns.show', $return) }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
