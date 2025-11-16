<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Editorial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EditorialController extends Controller
{
    public function index(): View
    {
        $editorials = Editorial::orderBy('nombre')->paginate(12);

        return view('editorials.index', compact('editorials'));
    }

    public function create(): View
    {
        return view('editorials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'pais' => ['nullable', 'string', 'max:120'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:150'],
        ]);

        Editorial::create($data);

        return redirect()->route('web.editorials.index')->with('status', 'Editorial creada correctamente.');
    }

    public function edit(Editorial $editorial): View
    {
        return view('editorials.edit', compact('editorial'));
    }

    public function update(Request $request, Editorial $editorial): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'pais' => ['nullable', 'string', 'max:120'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
            'contacto' => ['nullable', 'string', 'max:150'],
        ]);

        $editorial->update($data);

        return redirect()->route('web.editorials.index')->with('status', 'Editorial actualizada correctamente.');
    }

    public function destroy(Editorial $editorial): RedirectResponse
    {
        if ($editorial->books()->exists()) {
            return back()->with('status', 'No se puede eliminar la editorial porque tiene libros asociados.');
        }

        $editorial->delete();

        return redirect()->route('web.editorials.index')->with('status', 'Editorial eliminada correctamente.');
    }
}
