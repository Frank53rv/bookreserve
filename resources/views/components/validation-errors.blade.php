@if ($errors->any())
    <div class="alert alert-danger">
        <h2 class="h6 mb-2">Revisa la información proporcionada:</h2>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
