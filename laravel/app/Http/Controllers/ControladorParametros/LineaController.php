<?php

namespace App\Http\Controllers\ControladorParametros;

use App\Http\Controllers\Controller;
use App\Models\Linea;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class LineaController extends Controller
{

    public function index()
    {
        $lineas = Linea::with('subcategoria')->paginate(10, ['*'], 'lineas_page');
        if ($request->ajax()) {
            return view('lineas.index', compact('lineas'))->render();
        }
        return view('lineas.index', compact('lineas'));
    }

    public function create($subcategoria_id = null)
    {
        $subcategorias = Subcategoria::all();
        return view('lineas.crear', compact('subcategorias', 'subcategoria_id'));
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre_linea' => 'required|string|max:255',
            'cod_subcategoria' => 'required|exists:subcategoria,id',
        ]);
    
        // Verificar si ya existe una línea con el mismo nombre en la subcategoría seleccionada
        $existingLinea = Linea::where('nombre_linea', $request->nombre_linea)
            ->where('cod_subcategoria', $request->cod_subcategoria)
            ->whereNull('deleted_at') // Asegúrate de que no esté eliminada (si usas soft deletes)
            ->first();
    
        if ($existingLinea) {
            return redirect()->back()
                ->withErrors(['nombre_linea' => 'Ya existe una línea con este nombre en la subcategoría seleccionada.'])
                ->withInput(); // Mantener los datos ingresados
        }
    
        // Crear la nueva línea
        $linea = Linea::create($request->all());
    
        $subcategoria = $linea->subcategoria->nombre_subcategoria ?? '';
    
        session()->flash('linea_nombre', $linea->nombre_linea);
        session()->flash('subcategoria_nombre', $subcategoria);
    
        return redirect()->route('subcategoria.edit', $linea->cod_subcategoria)->with('success', 'Línea creada exitosamente');
    }

    public function show($id)
    {
        $linea = Linea::with(['subcategoria', 'sublines'])->findOrFail($id);
        return view('lineas.detalle', compact('linea'));
    }

    public function edit($id)
    {
        $linea = Linea::findOrFail($id);
        $sublineas = $linea->sublines;
        $subcategorias = Subcategoria::all();
        return view('lineas.editar', compact('linea', 'subcategorias','sublineas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_linea' => 'required|string|max:255',
            'cod_subcategoria' => 'required|exists:subcategoria,id',
        ]);

        $linea = Linea::findOrFail($id);
        $linea->update($request->all());

        return redirect()->route('subcategoria.edit',$linea->cod_subcategoria )->with('success', 'Línea actualizada exitosamente');
    }

    // Elimina una línea (soft delete)
    public function destroy($id)
    {
        $linea = Linea::findOrFail($id);
        $subcategoria = $linea->cod_subcategoria;
        if ($linea->sublines()->count() > 0) {
            return redirect()->route('subcategoria.edit', $subcategoria)->with('delete_error', 'No se puede eliminar la linea porque tiene sublineas asociadas.');
        }
        $linea->delete();

        return redirect()->route('subcategoria.edit', $subcategoria)->with('success', 'Línea eliminada exitosamente');
    }
}
