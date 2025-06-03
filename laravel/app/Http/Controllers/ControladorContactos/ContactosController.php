<?php

namespace App\Http\Controllers\ControladorContactos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contacto;
use App\Models\Cliente;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
class ContactosController extends Controller
{
    // Mostrar una lista de contactos con paginacion
    public function index(Request $request) // <-- Add Request $request here
    {
        // Obtener el número de resultados por página de la solicitud
        // Si no se especifica, usar 6 como valor por defecto.
        $perPage = $request->input('perPage', 6);

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6; // Si filter_var devuelve false, usar 6

        // Opcional: Establecer un límite máximo para evitar cargas excesivas de datos
        $perPage = min($perPage, 200);

        // Construir la consulta base para los contactos
        // Load sucursal and cliente relationships eagerly
        $query = Contacto::orderBy('id', 'desc')->with(['sucursal.cliente']);

        // Aplicar lógica de búsqueda si el campo 'search' está presente en la solicitud
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search);
                } else {
                    $q->where('nombre_contacto', 'like', "%$search%")
                      ->orWhere('telefono_contacto', 'like', "%$search%")
                      ->orWhere('departamento_contacto', 'like', "%$search%")
                      ->orWhere('cargo_contacto', 'like', "%$search%")
                      ->orWhere('email_contacto', 'like', "%$search%")
                      ->orWhereHas('sucursal', function ($subQuery) use ($search) {
                          $subQuery->where('nombre_sucursal', 'like', "%$search%");
                      })
                      ->orWhereHas('sucursal.cliente', function ($subQuery) use ($search) {
                        $subQuery->where('nombre_cliente', 'like', "%$search%");
                    });
                }
            });
        }

        // Paginación final de los resultados, manteniendo los filtros
        $contactos = $query->paginate($perPage)->appends($request->except('page'));

        return view('contactos.contactos', compact('contactos'));
    }

    // Mostrar el formulario para crear un nuevo contacto
    public function create(Request $request)
    {
        $clienteId = $request->input('clienteId');
        $sucursalId = $request->input('sucursalId');
        $from = $request->get('from');
        $sucursalId = $request->get('sucursalId');
        $clientes = Cliente::all();
        $clienteSeleccionado = $clienteId ? Cliente::find($clienteId) : null;
        
        if($clienteSeleccionado != null)
        {
            $sucursales = $clienteSeleccionado->sucursal;
        }else{
            $sucursales = Sucursal::all();

        }
        $sucursalSeleccionada = $sucursalId ? Sucursal::find($sucursalId) : null;
        return view('contactos.agregar', compact('clientes', 'clienteSeleccionado', 'sucursalSeleccionada','from','sucursalId','sucursales'));
    }

    // Almacenar un nuevo contacto en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_contacto' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:255',
            'departamento_contacto' => 'nullable|string|max:255',
            'cargo_contacto' => 'nullable|string|max:255',
            'email_contacto' => 'required|email|max:255',
            'cod_sucursal' => 'required|exists:sucursal,id', // Corregido aquí
        ]);

        // Crear el contacto
        $contacto = Contacto::create([
            'nombre_contacto' => $request->nombre_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'departamento_contacto' => $request->departamento_contacto,
            'cargo_contacto' => $request->cargo_contacto,
            'email_contacto' => $request->email_contacto,
            'cod_sucursal' => $request->cod_sucursal,
        ]);
        
        if ($request->has('from') && $request->get('from') === 'editar_sucursal') {
            return redirect()->route('sucursales.edit', $contacto->cod_sucursal)->with('success', 'Contacto creado exitosamente.');
        }
    
        return redirect()->route('contactos.index')->with('success', 'Contacto creado exitosamente.');
    }

    // Mostrar el formulario para editar un contacto
    public function edit($id, Request $request)
    {
        $contacto = Contacto::findOrFail($id);
        $from = $request->get('from');
        $cliente = $contacto->sucursal->cliente; // Obtener el cliente asociado a la sucursal del contacto
        $sucursales = Sucursal::where('cod_cliente', $cliente->id)->get(); // Obtener sucursales del cliente
        $clientes = Cliente::all(); // Obtener todos los clientes
        return view('contactos.editar', compact('contacto', 'sucursales', 'clientes', 'cliente','from'));
    }

    // Actualizar un contacto en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_contacto' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:255',
            'departamento_contacto' => 'nullable|string|max:255',
            'cargo_contacto' => 'nullable|string|max:255',
            'email_contacto' => 'required|email|max:255',
            'cod_sucursal' => 'required|exists:sucursal,id', // Corregido aquí
        ]);

        // Encontrar y actualizar el contacto
        $contacto = Contacto::findOrFail($id);
        $contacto->update([
            'nombre_contacto' => $request->nombre_contacto,
            'telefono_contacto' => $request->telefono_contacto,
            'departamento_contacto' => $request->departamento_contacto,
            'cargo_contacto' => $request->cargo_contacto,
            'email_contacto' => $request->email_contacto,
            'cod_sucursal' => $request->cod_sucursal,
        ]);
        
        if ($request->has('from') && $request->get('from') === 'editar_sucursal') {
            return redirect()->route('sucursales.edit', $contacto->cod_sucursal)->with('success', 'Contacto actualizado exitosamente.');
        }

        return redirect()->route('contactos.index')->with('success', 'Contacto actualizado exitosamente.');
    }

    // Eliminar un contacto de la base de datos
    public function destroy_contacto($id)
    {
        $contacto = Contacto::findOrFail($id);
        
        $ordenesDeTrabajo = DB::table('contacto_ot')->where('cod_contacto', $contacto->id)->exists();
    
        
        if ($ordenesDeTrabajo) {
            return back()->withErrors(['error' => 'No se puede eliminar el contacto porque está asociado a una orden de trabajo.']);
        }
        
        $contacto->delete();

        return back()->with('success', 'Contacto eliminado exitosamente.');
        //->with('success', 'Contacto eliminado exitosamente.');
    }
    
    // Mostrar el detalle de un contacto
    public function show($id)
    {
        $contacto = Contacto::findOrFail($id);
        return view('contactos.detalle', compact('contacto'));
    }
    public function buscar(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 6); // <-- GET perPage from request

        // Validate perPage as in index method
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6;
        $perPage = min($perPage, 200);

        // Prepare query parameters for redirects to maintain state
        $queryParams = array_filter([
            'perPage' => $perPage,
            'search' => $search // Keep search parameter for redirect if it exists
        ]);

        if (empty($search)) {
            // Redirect to index, passing current perPage and an empty search
            return redirect()->route('contactos.index', $queryParams)->with('error', 'Por favor ingrese un término de búsqueda.');
        }

        $query = Contacto::with(['sucursal.cliente']); // Eager load relationships

        if (is_numeric($search)) {
            $query->where('id', $search);
        } else {
            $query->where('nombre_contacto', 'like', "%$search%")
                  ->orWhere('telefono_contacto', 'like', "%$search%")
                  ->orWhere('departamento_contacto', 'like', "%$search%")
                  ->orWhere('cargo_contacto', 'like', "%$search%")
                  ->orWhere('email_contacto', 'like', "%$search%")
                  ->orWhereHas('sucursal', function ($q) use ($search) {
                      $q->where('nombre_sucursal', 'like', "%$search%");
                  })
                  ->orWhereHas('sucursal.cliente', function ($q) use ($search) {
                    $q->where('nombre_cliente', 'like', "%$search%");
                });
        }
        
        $contactos = $query->orderBy('id', 'desc') // Ensure consistent ordering
                           ->paginate($perPage) // <-- Use $perPage
                           ->appends($request->except('page')); // <-- Use appends()

        if ($contactos->isEmpty()) {
            // Redirect to index, passing current perPage and search, and an error message
            return redirect()->route('contactos.index', $queryParams)->with('error', 'No se encontraron contactos con ese término de búsqueda.');
        }
    
        return view('contactos.contactos', compact('contactos'));
    }
}
