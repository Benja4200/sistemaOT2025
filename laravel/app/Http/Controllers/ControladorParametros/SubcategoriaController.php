<?php

namespace App\Http\Controllers\ControladorParametros;

use App\Http\Controllers\Controller;
use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    public function index(Request $request)
    {

        $subcategorias = Subcategoria::with('categoria')->paginate(10, ['*'], 'subcategorias_page');


        if ($request->ajax()) {

            return view('subcategoria.index', compact('subcategorias'))->render();
        }


        return view('subcategoria.index', compact('subcategorias'));
    }

    public function create($categoria_id = null)
    {
        $categorias = Categoria::all();
        return view('subcategoria.crear', compact('categorias', 'categoria_id'));
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre_subcategoria' => 'required|string|max:255',
            'cod_categoria' => 'required|exists:categoria,id',
        ]);
    
        // Verificar si ya existe una subcategoría con el mismo nombre en la misma categoría
        $existingSubcategoria = Subcategoria::where('nombre_subcategoria', $request->nombre_subcategoria)
            ->where('cod_categoria', $request->cod_categoria)
            ->whereNull('deleted_at') // Asegúrate de que no esté eliminada (si usas soft deletes)
            ->first();
    
        if ($existingSubcategoria) {
            return redirect()->back()
                ->withErrors(['nombre_subcategoria' => 'Ya existe una subcategoría con este nombre en la categoría seleccionada.'])
                ->withInput(); // Mantener los datos ingresados
        }
    
        // Crear la nueva subcategoría
        $subcategoria = Subcategoria::create([
            'nombre_subcategoria' => $request->input('nombre_subcategoria'),
            'cod_categoria' => $request->input('cod_categoria'),
        ]);
    
        $categoria = Categoria::find($request->input('cod_categoria'));
    
        return redirect()->route('categoria.edit', $subcategoria->cod_categoria)->with([
            'success' => 'Subcategoría creada exitosamente',
            'subcategoria_nombre' => $subcategoria->nombre_subcategoria,
            'categoria_nombre' => $categoria->nombre_categoria,
        ]);
    }
    public function show($id)
    {
        $subcategoria = Subcategoria::with('categoria')->findOrFail($id);

        return view('subcategoria.detalle', compact('subcategoria'));
    }

    public function edit($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        $lineas = $subcategoria->lineas;
        $categorias = Categoria::all();
        return view('subcategoria.editar', compact('subcategoria', 'categorias','lineas'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_subcategoria' => 'required|string|max:255',
            'cod_categoria' => 'required|exists:categoria,id',
        ]);

        $subcategoria = Subcategoria::findOrFail($id);
        $subcategoria->update([
            'nombre_subcategoria' => $request->input('nombre_subcategoria'),
            'cod_categoria' => $request->input('cod_categoria'),
        ]);
        return redirect()->route('categoria.edit', $subcategoria->cod_categoria)->with('success', 'Subcategoría actualizada exitosamente');
    }

    public function destroy($id)
    {
        $subcategoria = Subcategoria::findOrFail($id);
        $categoria = $subcategoria->cod_categoria;
        if ($subcategoria->lineas()->count() > 0) {
            return redirect()->route('categoria.edit', $categoria)->with('delete_error', 'No se puede eliminar la subcategoria porque tiene lineas asociadas.');
        }
        $subcategoria->delete();

        return redirect()->route('categoria.edit', $categoria)->with('success', 'Subcategoría eliminada exitosamente');
    }
}
