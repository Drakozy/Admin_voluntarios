<?php

namespace App\Http\Controllers;

use App\Models\proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyectos = DB::table('proyectos')->get();
        return view('index',['proyectos' => $proyectos]);
    }

    public function inscribirse()
    {
        // Validar los datos recibidos del formulario
        $request->validate([
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        // Registrar la relación en la tabla proyecto_usuarios
        $usuario = Auth::user(); // Suponiendo que el usuario está autenticado
        $proyecto = Proyecto::findOrFail($request->proyecto_id);

        // Verificar si ya está inscrito
        if ($usuario->proyectos()->where('proyecto_id', $proyecto->id)->exists()) {
            return redirect()->back()->with('error', 'Ya estás inscrito en este proyecto.');
        }

        // Crear la relación en la tabla pivot proyecto_usuarios
        $usuario->proyectos()->attach($proyecto->id);

        return redirect()->route('Proyecto.index')->with('success', 'Inscripción realizada con éxito');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(proyecto $proyecto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(proyecto $proyecto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, proyecto $proyecto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(proyecto $proyecto)
    {
        //
    }
}
