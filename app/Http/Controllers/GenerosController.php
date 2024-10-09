<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Generos;

class GenerosController extends Controller
{
    
    public function index()
    {
        $generos = Generos::all();
        return view('generos', compact('generos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $generos = new Generos($request->input());
        $generos->saveOrFail();
        return redirect('generos');
    }

    public function show(string $id)
    {
        $genero = Generos::find($id);
        return view('editgenero', compact('genero'));
    }

    public function edit($id)
{
    $genero = Generos::findOrFail($id);
    return view('editgenero', compact('genero'));
}

    public function update(Request $request, $id)
{
    $genero = Generos::findOrFail($id);
    $genero->nombre = $request->input('nombre');
    $genero->save();

    return redirect('/generos')->with('success', 'Genero actualizado correctamente');
}

public function destroy(string $id)
{
    try {
        $genero = Generos::findOrFail($id);
        $genero->delete();

        // Redireccionamos con un mensaje de éxito si la eliminación fue exitosa
        return redirect('/generos')->with('success', 'Género eliminado correctamente');
    } catch (\Exception $e) {
        // Si ocurre un error (por ejemplo, si el género está en uso), redirigimos con un mensaje de error
        return redirect('/generos')->with('error', 'No se puede eliminar este género porque está siendo utilizado.');
    }
}
}
