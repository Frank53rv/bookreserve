<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReturnDetail;
use App\Models\ReturnHeader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReturnDetailController extends Controller
{
    public function create(Request $request): View
    {
        $returns = ReturnHeader::with('client')
            ->latest('fecha_devolucion')
            ->get()
            ->mapWithKeys(function ($return) {
                $client = optional($return->client);
                $label = sprintf('%s (%s)', $return->id_devolucion, trim(($client->nombre ?? '') . ' ' . ($client->apellido ?? '')) ?: 'Sin cliente');

                return [$return->getKey() => $label];
            });

        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $returnId = $request->query('return');

        return view('return-details.create', compact('returns', 'books', 'returnId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_devolucion' => ['required', 'exists:return_headers,id_devolucion'],
            'id_libro' => ['required', 'exists:books,id_libro'],
            'cantidad_devuelta' => ['required', 'integer', 'min:1'],
        ]);

        $detail = ReturnDetail::create($data);

        return redirect()->route('web.returns.show', $detail->returnHeader)->with('status', 'Detalle de devolución creado correctamente.');
    }

    public function edit(ReturnDetail $returnDetail): View
    {
        $returnDetail->load('returnHeader.client', 'book');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $returns = ReturnHeader::with('client')
            ->latest('fecha_devolucion')
            ->get()
            ->mapWithKeys(function ($return) {
                $client = optional($return->client);
                $label = sprintf('%s (%s)', $return->id_devolucion, trim(($client->nombre ?? '') . ' ' . ($client->apellido ?? '')) ?: 'Sin cliente');

                return [$return->getKey() => $label];
            });

        return view('return-details.edit', compact('returnDetail', 'books', 'returns'));
    }

    public function update(Request $request, ReturnDetail $returnDetail): RedirectResponse
    {
        $data = $request->validate([
            'id_libro' => ['required', 'exists:books,id_libro'],
            'cantidad_devuelta' => ['required', 'integer', 'min:1'],
        ]);

        $returnDetail->update($data);

        return redirect()->route('web.returns.show', $returnDetail->returnHeader)->with('status', 'Detalle de devolución actualizado correctamente.');
    }

    public function destroy(ReturnDetail $returnDetail): RedirectResponse
    {
        $return = $returnDetail->returnHeader;
        $returnDetail->delete();

        return redirect()->route('web.returns.show', $return)->with('status', 'Detalle de devolución eliminado correctamente.');
    }
}
