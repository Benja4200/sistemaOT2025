<?php
namespace App\Http\Controllers\ControladorMarcas;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarcasControlador extends Controller
{
    // Muestra la lista de marcas con paginación
   public function index(Request $request)
    {
        // Obtener el número de resultados por página de la solicitud
        $perPage = $request->input('perPage', 6);
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6;
        $perPage = min($perPage, 200);

        // Obtener el término de búsqueda
        $search = $request->input('search');

        // Inicializar la consulta base
        $query = Marca::query();

        // Aplicar la lógica de búsqueda si hay un término
        if (!empty($search)) {
            if (is_numeric($search)) {
                $query->where('id', $search);
            } else {
                $query->where('nombre_marca', 'like', "%{$search}%");
            }
        }

        // Obtener las marcas paginadas y adjuntar todos los parámetros de la solicitud
        $marcas = $query->orderBy('id', 'desc')->paginate($perPage)->appends($request->query());

      


        return view('marcas.index', compact('marcas', 'search'));
    }



    // Muestra el formulario para crear una nueva marca
    public function create()
    {
        return view('marcas.agregar');
    }

    // Almacena una nueva marca en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_marca' => 'required|string|max:255|unique:marca,nombre_marca', // Validación de unicidad
        ]);

        Marca::create($request->only('nombre_marca'));

        return redirect()->route('marcas.index')->with('success', 'Marca creada exitosamente');
    }

    // Muestra el detalle de una marca específica
    public function show($id)
    {
        $marca = Marca::findOrFail($id);
        return view('marcas.detalle', compact('marca'));
    }

    // Muestra el formulario para editar una marca
    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return view('marcas.editar', compact('marca'));
    }

    // Actualiza una marca existente en la base de datos
    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        $request->validate([
            'nombre_marca' => 'required|string|max:255|unique:marca,nombre_marca,' . $marca->id, // Validación de unicidad, ignorando el ID actual
        ]);

        $marca->update($request->only('nombre_marca'));

        return redirect()->route('marcas.index')->with('success', 'Marca actualizada exitosamente');
    }

    // Elimina (suavemente) una marca de la base de datos
    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete(); // Soft delete, gracias a SoftDeletes en el modelo

        return redirect()->route('marcas.index')->with('success', 'Marca eliminada exitosamente');
    }

    // Restaurar una marca eliminada suavemente
    public function restore($id)
    {
        $marca = Marca::onlyTrashed()->findOrFail($id);
        $marca->restore();

        return redirect()->route('marcas.index')->with('success', 'Marca restaurada exitosamente');
    }

    // Mostrar las marcas eliminadas suavemente
    public function trashed()
    {
        $marcas = Marca::onlyTrashed()->paginate(10);
        return view('marcas.trashed', compact('marcas'));
    }

    

}
