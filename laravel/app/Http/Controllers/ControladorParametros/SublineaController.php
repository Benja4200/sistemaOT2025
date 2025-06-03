<?php

namespace App\Http\Controllers\ControladorParametros;

use App\Http\Controllers\Controller;
use App\Models\Sublinea;
use App\Models\Linea;
use App\Models\Servicio;
use App\Models\Modelo;

use Illuminate\Http\Request;

class SublineaController extends Controller
{
    public function index()
    {
        $sublineas = Sublinea::with('linea')->get();
        return view('sublineas.index', compact('sublineas'));
    }

    public function create($linea_id = null)
    {
        $lineas = Linea::all();
        return view('sublineas.crear', compact('lineas','linea_id'));
    }


    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre_sublinea' => 'required|string|max:255',
            'cod_linea' => 'required|exists:linea,id',
        ]);
    
        // Verificar si ya existe una sublínea con el mismo nombre en la línea seleccionada
        $existingSublinea = Sublinea::where('nombre_sublinea', $request->nombre_sublinea)
            ->where('cod_linea', $request->cod_linea)
            ->whereNull('deleted_at') // Asegúrate de que no esté eliminada (si usas soft deletes)
            ->first();
    
        if ($existingSublinea) {
            return redirect()->back()
                ->withErrors(['nombre_sublinea' => 'Ya existe una sublínea con este nombre en la línea seleccionada.'])
                ->withInput(); // Mantener los datos ingresados
        }
    
        // Crear la nueva sublínea
        $sublinea = Sublinea::create($request->all());
    
        $linea = $sublinea->linea->nombre_linea ?? '';
    
        session()->flash('sublinea_nombre', $sublinea->nombre_sublinea);
        session()->flash('linea_nombre', $linea);
    
        return redirect()->route('lineas.edit', $sublinea->cod_linea)->with('success', 'Sublínea creada exitosamente');
    }

    public function show($id)
    {
        $sublinea = Sublinea::with('linea')->findOrFail($id);
        return view('sublineas.detalle', compact('sublinea'));
    }

    public function edit($id)
    {
        $sublinea = Sublinea::findOrFail($id);
        $lineas = \App\Models\Linea::all();
        return view('sublineas.editar', compact('sublinea', 'lineas'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_sublinea' => 'required|string|max:255',
            'cod_linea' => 'required|exists:linea,id',
        ]);

        $sublinea = Sublinea::findOrFail($id);
        $sublinea->update($request->all());

        return redirect()->route('lineas.edit',$sublinea->cod_linea)->with('success', 'Sublínea actualizada exitosamente');
    }

    public function destroy($id)
    {
        $sublinea = Sublinea::findOrFail($id);
        
        $linea = $sublinea->cod_linea;
        // Verificar si la sublínea está siendo utilizada en servicios o dispositivos
        $servicioUsandoSublinea = Servicio::where('cod_sublinea', $sublinea->id)->exists();
        $dispositivoUsandoSublinea = Modelo::where('cod_sublinea', $sublinea->id)->exists();
    
        if ($servicioUsandoSublinea || $dispositivoUsandoSublinea) {
            return redirect()->route('lineas.edit',$sublinea->cod_linea)->with('delete_error', 'No se puede eliminar la sublínea porque está siendo utilizada en un servicio o modelo.');
        }
    
        // Si no está siendo utilizada, proceder a eliminar
        $sublinea->delete();
    
        return redirect()->route('lineas.edit',$linea)->with('success', 'Sublínea eliminada exitosamente');
    }
}
