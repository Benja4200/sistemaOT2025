<?php
namespace App\Http\Controllers\ControladorRepuestos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Repuesto;

class RepuestosControlador extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener el número de resultados por página de la solicitud, con un valor por defecto (ej. 6)
        $perPage = $request->input('perPage', 6);

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6; // Si filter_var devuelve false, usar 6
        $perPage = min($perPage, 200); // Limitar a un máximo de 200 registros

        // Obtener el término de búsqueda de la solicitud (puede venir de una redirección)
        $search = $request->input('search');

        // Construir la consulta base para los repuestos
        $query = Repuesto::orderBy('id', 'desc');

        // Aplicar el filtro de búsqueda si existe
        if (!empty($search)) {
            $query->where('nombre_repuesto', 'like', "%{$search}%")
                  ->orWhere('descripcion_repuesto', 'like', "%{$search}%")
                  ->orWhere('part_number_repuesto', 'like', "%{$search}%");
        }

        // Obtener los repuestos paginados, incluyendo los parámetros de búsqueda y perPage
        $respuestoxx = $query->paginate($perPage)->appends($request->query());

        // Si no se encontraron repuestos para la búsqueda actual (cuando se viene de 'search')
        // y se tiene un término de búsqueda, se añade un mensaje flash.
        if ($respuestoxx->isEmpty() && !empty($search)) {
            session()->flash('error', 'No se encontraron repuestos con el término: "' . $search . '".');
        }

        return view('repuestos.view_repuestos', compact('respuestoxx', 'search'));
    }

    public function create()
    {

        $hola = 'ks';

        return view('repuestos.create', compact('hola'));
    }

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre_repuesto' => 'required|string|max:255',
            'descripcion_repuesto' => 'required|string|max:255',
            'part_number_repuesto' => 'required|string|max:255',
        ]);

        // Guardar el repuesto en la base de datos
        DB::table('repuesto')->insert([
            'nombre_repuesto' => $request->nombre_repuesto,
            'descripcion_repuesto' => $request->descripcion_repuesto,
            'part_number_repuesto' => $request->part_number_repuesto,
        ]);

        // Redirigir después de guardar el repuesto
        return redirect()->route('repuestos.index')->with('success', 'Repuesto creado exitosamente');
    }

    public function show($id)
    {
        // Recuperamos el repuesto con el ID especificado
        $repuestozzz = Repuesto::with('modelos')->find($id);

        // Si no se encuentra el repuesto, redirige con un mensaje de error
        if (!$repuestozzz) {
            return redirect()->route('repuestos.index')->with('error', 'Repuesto no encontrado.');
        }

        // Devuelve la vista con los detalles del repuesto
        return view('repuestos.show', compact('repuestozzz'));
    }

    public function edit($id)
    {
        // Recuperamos el repuesto con el ID especificado
        $repuesto = DB::table('repuesto')->find($id);

        // Si no se encuentra el repuesto, redirige con un mensaje de error
        if (!$repuesto) {
            return redirect()->route('repuestos.index')->with('error', 'Repuesto no encontrado.');
        }

        // Devuelve la vista con los datos del repuesto para editar
        return view('repuestos.edit', compact('repuesto'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre_repuesto' => 'required|string|max:255',
            'descripcion_repuesto' => 'required|string|max:255',
            'part_number_repuesto' => 'required|string|max:255',
        ]);

        // Actualizar el repuesto en la base de datos
        $repuesto = Repuesto::findOrFail($id);
        $repuesto->update([
            'nombre_repuesto' => $request->input('nombre_repuesto'),
            'descripcion_repuesto' => $request->input('descripcion_repuesto'),
            'part_number_repuesto' => $request->input('part_number_repuesto'),
        ]);
        
        
        // Si la actualizacion fue exitosa, redirigimos con un mensaje de exito
        if ($repuesto) {
            return redirect()->route('repuestos.index')->with('success', 'Repuesto actualizado exitosamente.');
        }

        // Si no se actualizo, redirigimos con un mensaje de error
        return redirect()->route('repuestos.index')->with('error', 'No se pudo actualizar el repuesto.');
    }

    public function search(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('perPage', 6);

    // Validar que $perPage sea un número entero positivo.
    $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $perPage = $perPage ?: 6;
    $perPage = min($perPage, 200);

    // Si el término de búsqueda está vacío, redirigir al index con un mensaje de error y el perPage
    if (empty($search)) {
        return redirect()->route('repuestos.index', ['perPage' => $perPage])
                         ->with('error', 'Por favor ingrese un término de búsqueda para repuestos.');
    }

    $repuestos_query = Repuesto::query();

    if (is_numeric($search)) {
        $repuestos_query->where('id', $search);
    } else {
        $repuestos_query->where('nombre_repuesto', 'like', "%{$search}%")
                        ->orWhere('descripcion_repuesto', 'like', "%{$search}%")
                        ->orWhere('part_number_repuesto', 'like', "%{$search}%");
    }

    $respuestoxx = $repuestos_query->orderBy('id', 'desc')
                                    ->paginate($perPage)
                                    ->appends(['search' => $search, 'perPage' => $perPage]);

    // *** CAMBIO CLAVE AQUÍ: NO REDIRIGIR SI NO HAY RESULTADOS ***
    // En su lugar, la vista recibirá los $respuestoxx vacíos y un mensaje de error.
    return view('repuestos.view_repuestos', compact('respuestoxx', 'search'))
        ->with('error', $respuestoxx->isEmpty() ? 'No se encontraron repuestos con el término: "' . $search . '".' : null);
}




    public function destroy($id)
    {
        // Eliminar el repuesto con el ID especificado
        $repuesto = DB::table('repuesto')->where('id', $id)->first();

        // Si no se encuentra el repuesto, redirige con un mensaje de error
        if (!$repuesto) {
            return redirect()->route('repuestos.index')->with('error', 'Repuesto no encontrado.');
        }

        // Eliminar el repuesto
        DB::table('repuesto')->where('id', $id)->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('repuestos.index')->with('success', 'Repuesto eliminado exitosamente.');
    }





}

