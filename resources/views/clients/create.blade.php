@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="h3 mb-3">Nuevo cliente</h1>
        @include('components.validation-errors')
        <form action="{{ route('web.clients.store') }}" method="POST" class="card card-body shadow-sm">
            @csrf
            @include('clients._form')
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('web.clients.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
