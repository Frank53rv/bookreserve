<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BookReserve') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'BookReserve') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('web.categories.index') }}">Categorías</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.books.index') }}">Libros</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.clients.index') }}">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.reservations.index') }}">Reservas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.returns.index') }}">Devoluciones</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.inventory-records.index') }}">Ingresos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('web.movements.index') }}">Movimientos</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="py-4">
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @yield('content')
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
