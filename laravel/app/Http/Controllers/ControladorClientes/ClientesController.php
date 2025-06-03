<?php

namespace App\Http\Controllers\ControladorClientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Contacto;
use App\Models\TipoOt;
use App\Models\PrioridadOt;
use App\Models\EstadoOt;
use App\Models\TipoVisita;
use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Sucursal;
use App\Models\Modelo;
use Illuminate\Support\Facades\DB;

use App\Rules\Rut;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el número de resultados por página de la solicitud
        // Si no se especifica, usar 10 como valor por defecto.
        $perPage = $request->input('perPage', 10);

        // Validar que $perPage sea un número entero positivo.
        // Si no es un número válido o es menor que 1, se usará 10 como fallback.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10; // Si filter_var devuelve false (no es entero positivo), usar 10

        // Opcional: Establecer un límite máximo para evitar cargas excesivas de datos
        // Por ejemplo, no permitir más de 200 registros por página
        $perPage = min($perPage, 200);

        // Construir la consulta base para los clientes
        // Asegúrate de que 'Cliente' se refiere a tu modelo Cliente correcto (use App\Models\Cliente;)
        $query = Cliente::orderBy('id', 'desc'); // Ordenar por ID descendente

        // Aplicar lógica de búsqueda si el campo 'search' está presente en la solicitud
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('nombre_cliente', 'like', "%{$search}%") // Assuming 'nombre_cliente' is the column name
                  ->orWhere('rut_cliente', 'like', "%{$search}%")    // Assuming 'rut_cliente'
                  ->orWhere('email_cliente', 'like', "%{$search}%")  // Assuming 'email_cliente'
                  ->orWhere('web_cliente', 'like', "%{$search}%");   // Assuming 'web_cliente'
            });
        }

        // Paginación final de los resultados
        // appends($request->except('page')) es crucial para mantener los filtros al paginar
        $clientesxw = $query->paginate($perPage)->appends($request->except('page'));

        return view('clientes.view_clientes', compact('clientesxw'));
    }

    public function create()
    {
        return view('clientes.agregar');
    }
    
    public function nuevoCliente()
    {
        return view('ordenes.agregarNuevo');
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'rut_cliente' => [
                'required',
                'string',
                'max:20',
                new Rut(), 
                'unique:cliente,rut_cliente' 
            ],
            'email_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'web_cliente' => 'nullable|string|max:255',
        ]);

        // Crear un nuevo cliente
        Cliente::create([
            'nombre_cliente' => $request->input('nombre_cliente'),
            'rut_cliente' => $request->input('rut_cliente'),
            'email_cliente' => $request->input('email_cliente'),
            'telefono_cliente' => $request->input('telefono_cliente'),
            'web_cliente' => $request->input('web_cliente'),
        ]);

        // Mostrar el mensaje en la misma página y redirigir con JavaScript desde la vista
        return back()->with('success', 'Cliente agregado exitosamente. Serás redirigido en unos segundos.');
    }
    
    public function newClient(Request $request)
    {
        
        $tipos = TipoOt::all();
        $prioridades = PrioridadOt::all();
        $estados = EstadoOt::all();
        $tiposVisitas = TipoVisita::all();
        $tecnicos = Tecnico::all();
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $sucursales = Sucursal::all();
        $modelodispositivos = Modelo::all();
        
         $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'rut_cliente' => [
                'required',
                'string',
                'max:20',
                new Rut(), 
                'unique:cliente,rut_cliente' 
            ],
            'email_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'web_cliente' => 'nullable|string|max:255',
            'nombre_sucursal' => 'required|string|max:255',
            'telefono_sucursal' => 'required|string|max:255',
            'direccion_sucursal' => 'required|string|max:255',
            'nombre_contacto' => 'required|string|max:255',
            'telefono_contacto' => 'required|string|max:255',
            'departamento_contacto' => 'nullable|string|max:255',
            'cargo_contacto' => 'nullable|string|max:255',
            'email_contacto' => 'required|email|max:255',
        ]);
    
        DB::beginTransaction(); // Iniciar la transacción
    
        try {
            // Crear el cliente
            $cliente = Cliente::create([
                'nombre_cliente' => $request->input('nombre_cliente'),
                'rut_cliente' => $request->input('rut_cliente'),
                'email_cliente' => $request->input('email_cliente'),
                'telefono_cliente' => $request->input('telefono_cliente'),
                'web_cliente' => $request->input('web_cliente'),
            ]);
            // Verificar que el cliente se haya creado correctamente
            if (!$cliente) {
                return back()->withErrors(['error' => 'Error al crear el cliente.']);
            }
    
            // Crear la sucursal
            $sucursal = Sucursal::create([
                'nombre_sucursal' => $request->nombre_sucursal,
                'telefono_sucursal' => $request->telefono_sucursal,
                'direccion_sucursal' => $request->direccion_sucursal,
                'cod_cliente' => $cliente->id, // Asegúrate de que el ID esté disponible
            ]);
    
            // Crear el contacto
            Contacto::create([
                'nombre_contacto' => $request->nombre_contacto,
                'telefono_contacto' => $request->telefono_contacto,
                'departamento_contacto' => $request->departamento_contacto,
                'cargo_contacto' => $request->cargo_contacto,
                'email_contacto' => $request->email_contacto,
                'cod_sucursal' => $sucursal->id,
            ]);
    
            DB::commit(); // Confirmar la transacción
            return redirect()->route('ordenes.create') // Cambia esto por la ruta correcta
            ->with('success', 'Cliente agregado exitosamente.')
            ->with(compact('tipos', 'prioridades', 'estados', 'tiposVisitas', 'tecnicos', 'clientes', 'servicios', 'sucursales', 'modelodispositivos'));
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return back()->withErrors(['error' => 'Error al agregar el cliente: ' . $e->getMessage()]);
        }
    
    }

    public function buscarcliente(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // <-- GET perPage from request

        // Validate perPage as in index method
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10;
        $perPage = min($perPage, 200);

        // Prepare query parameters for redirects to maintain state
        $queryParams = array_filter([
            'perPage' => $perPage,
            'search' => $search // Keep search parameter for redirect if it exists
        ]);

        if (empty($search)) {
            // Redirect to index, passing current perPage and an empty search (to clear any previous search)
            return redirect()->route('clientes.index', $queryParams)->with('error', 'Por favor ingrese un término de búsqueda.');
        }

        $query = Cliente::orderBy('id', 'desc'); // Start building the query

        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhere('nombre_cliente', 'like', "%{$search}%")
              ->orWhere('rut_cliente', 'like', "%{$search}%")
              ->orWhere('email_cliente', 'like', "%{$search}%")
              ->orWhere('web_cliente', 'like', "%{$search}%");
        });

        // Use the perPage value from the request and append current filters to pagination links
        $clientesxw = $query->paginate($perPage)->appends($request->except('page')); // <-- Use $perPage and appends()

        if ($clientesxw->isEmpty()) {
            // Redirect to index, passing current perPage and search, and an error message
            // This ensures the perPage filter is maintained even if no results are found
            return redirect()->route('clientes.index', $queryParams)->with('error', 'No se encontraron clientes con ese término de búsqueda.');
        }

        return view('clientes.view_clientes', compact('clientesxw'));
    }

    public function show($id)
    {
        // Obtener el cliente por el ID
        $cliente = Cliente::findOrFail($id);

        // Pasar los datos del cliente a la vista de detalle
        return view('clientes.detalle', compact('cliente'));
    }

    public function edit($id)
    {
        // Obtener el cliente por el ID
        $cliente = Cliente::findOrFail($id);

        // Pasar los datos del cliente a la vista de edicion
        return view('clientes.editar', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'rut_cliente' => [
                'required',
                'string',
                'max:20',
                new Rut(), // Validación personalizada para el RUT
                'unique:cliente,rut_cliente,' . $id // Ignorar el RUT del cliente actual
            ],
            'email_cliente' => 'required|email|max:255',
            'telefono_cliente' => 'required|string|max:20',
            'web_cliente' => 'nullable|string|max:255',
        ]);

        // Obtener el cliente por el ID
        $cliente = Cliente::findOrFail($id);

        // Actualizar la información del cliente
        $cliente->update([
            'nombre_cliente' => $request->input('nombre_cliente'),
            'rut_cliente' => $request->input('rut_cliente'),
            'email_cliente' => $request->input('email_cliente'),
            'telefono_cliente' => $request->input('telefono_cliente'),
            'web_cliente' => $request->input('web_cliente'),
        ]);

        // Mostrar el mensaje en la misma página y redirigir con JavaScript desde la vista
        return back()->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Obtener el cliente por el ID
        $cliente = Cliente::findOrFail($id);
        
        $contactos = Contacto::whereIn('cod_sucursal', $cliente->sucursal()->pluck('id'))->pluck('id');

        // Verificar si alguno de los contactos está asociado a una orden de trabajo en contacto_ot
        $ordenesDeTrabajo = DB::table('contacto_ot')->whereIn('cod_contacto', $contactos)->exists();
    
        
        if ($ordenesDeTrabajo) {
            return back()->withErrors(['error' => 'No se puede eliminar el cliente porque está asociado a una orden de trabajo.']);
        }
        
        if ($cliente->sucursal()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar el cliente porque tiene sucursales asociadas.']);
        }
        
        // Eliminar el cliente
        $cliente->delete();

        // Mostrar el mensaje en la misma página y redirigir con JavaScript desde la vista
        return back()->with('success', 'Cliente eliminado exitosamente.');
        //->with('success', 'Cliente eliminado exitosamente. Serás redirigido en unos segundos.');
    }
}
