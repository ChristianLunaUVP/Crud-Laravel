<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Canciones;
use App\Models\Generos;

class CancionesController extends Controller
{
    
    public function index()
    {
        $canciones = Canciones::with('genero')->get();
        $generos = Generos::all();
        return view('canciones', compact('canciones', 'generos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'artista' => 'required|string|max:255',
        'duracion' => 'required|regex:/^\d{1,2}:\d{2}$/',
        'genero_id' => 'required|exists:generos,id',
        'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    list($minutos, $segundos) = explode(':', $request->duracion);
    $duracion = ($minutos * 60) + $segundos;

    $path = $request->file('imagen')->store('imagenes', 'public');

    Canciones::create([
        'titulo' => $request->titulo,
        'artista' => $request->artista,
        'duracion' => $duracion,
        'genero_id' => $request->genero_id,
        'imagen' => $path,
    ]);

    return redirect('/canciones')->with('success', 'Canción agregada correctamente');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $canciones = Canciones::find($id);
        return view('editcanciones', compact('canciones', 'generos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $canciones = Canciones::findOrFail($id);
        $generos = Generos::all();

        return view('editcanciones', compact('canciones', 'generos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'artista' => 'required|string|max:255',
        'duracion' => 'required|regex:/^\d{1,2}:\d{2}$/',
        'genero_id' => 'required|exists:generos,id',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    list($minutos, $segundos) = explode(':', $request->duracion);
    $duracion = ($minutos * 60) + $segundos;

    $canciones = Canciones::findOrFail($id);

    if ($request->hasFile('imagen')) {
        // Eliminar la imagen anterior
        if ($canciones->imagen) {
            Storage::delete('public/' . $canciones->imagen);
        }

        // Guardar la nueva imagen
        $path = $request->file('imagen')->store('imagenes', 'public');
        $canciones->imagen = $path;
    }

    $canciones->titulo = $request->titulo;
    $canciones->artista = $request->artista;
    $canciones->duracion = $duracion;
    $canciones->genero_id = $request->genero_id;
    $canciones->save();

    return redirect('/canciones')->with('success', 'Canción actualizada correctamente');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        $canciones = Canciones::findOrFail($id);

        // Verificamos si la canción tiene una imagen almacenada y la eliminamos
        if ($canciones->imagen) {
            Storage::disk('public')->delete($canciones->imagen);
        }

        // Eliminamos la canción de la base de datos
        $canciones->delete();

        return redirect('/canciones')->with('success', 'Canción eliminada correctamente');
    } catch (\Exception $e) {
        return redirect('/canciones')->with('error', 'No se puede eliminar esta canción porque está relacionada con otros registros.');
    }
}



    public function deleteImage($id)
    {
    $canciones = Canciones::findOrFail($id);

    // Eliminar la imagen
    if ($canciones->imagen) {
        Storage::delete('public/' . $canciones->imagen);
        $canciones->imagen = null;
        $canciones->save();
    }

    return redirect()->back()->with('success', 'Imagen eliminada correctamente');
    }
}