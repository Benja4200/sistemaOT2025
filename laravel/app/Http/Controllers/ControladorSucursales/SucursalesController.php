<?php

namespace App\Http\Controllers\ControladorSucursales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Dispositivo;
use Illuminate\Support\Facades\DB;

class SucursalesController extends Controller
{
    public function index(Request $request) // <-- Add Request $request here
    {
        // Obtener el número de resultados por página de la solicitud
        // Si no se especifica, usar 6 como valor por defecto.
        $perPage = $request->input('perPage', 6);

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6; // Si filter_var devuelve false, usar 6

        // Opcional: Establecer un límite máximo para evitar cargas excesivas de datos
        $perPage = min($perPage, 200); // Por ejemplo, no permitir más de 200 registros por página

        // Construir la consulta base para las sucursales
        $query = Sucursal::orderBy('id', 'desc')->with('cliente'); // Eager load cliente

        // Aplicar lógica de búsqueda si el campo 'search' está presente en la solicitud
        if ($request->filled('search')) {
            $searchSucursal = $request->input('search');
            
            $query->where(function ($q) use ($searchSucursal) {
                // Si es numérico, buscar por ID de sucursal
                if (is_numeric($searchSucursal)) {
                    $q->where('id', $searchSucursal);
                } else {
                    // Si no es numérico, buscar por campos de texto de sucursal
                    $q->where('nombre_sucursal', 'like', "%$searchSucursal%")
                      ->orWhere('telefono_sucursal', 'like', "%$searchSucursal%")
                      ->orWhere('direccion_sucursal', 'like', "%$searchSucursal%")
                      ->orWhereHas('cliente', function($subQuery) use ($searchSucursal) {
                          // Buscar por nombre del cliente asociado
                          $subQuery->where('nombre_cliente', 'like', "%$searchSucursal%");
                      });
                }
            });
        }

        // Paginación final de los resultados, manteniendo los filtros
        $sucursales = $query->paginate($perPage)->appends($request->except('page'));

        // Mensaje de error si no se encontraron sucursales después de un filtro
        if ($request->filled('search') && $sucursales->isEmpty()) {
            return redirect()->route('sucursales.index')->with('error', 'No se encontraron sucursales con ese término de búsqueda.');
        }

        return view('sucursales.sucursales', compact('sucursales'));
    }

    public function create(Request $request)
    {
        $clientes = Cliente::all();
        $from = $request->get('from');
        
        // Si en la URL viene idcliente, lo copiamos a cliente_id
        if ($request->has('idcliente')) {
            $clienteId = $request->query('idcliente');
            $request->merge(['cliente_id' => $clienteId]);
        } else {
            $clienteId = null; // Si no hay idcliente, lo dejamos como null
        }
    
        return view('sucursales.agregar', compact('clientes', 'clienteId', 'from'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre_sucursal' => 'required|string|max:255',
            'telefono_sucursal' => 'required|string|max:255',
            'direccion_sucursal' => 'required|string|max:255',
            'cliente_id' => 'required|exists:cliente,id',
        ]);
    
        // Crear la sucursal con el cliente_id
        Sucursal::create([
            'nombre_sucursal' => $request->nombre_sucursal,
            'telefono_sucursal' => $request->telefono_sucursal,
            'direccion_sucursal' => $request->direccion_sucursal,
            'cod_cliente' => $request->cliente_id
        ]);
    
        // Verificar si se vino de la vista de edición del cliente
        if ($request->has('from') && $request->get('from') === 'editar_cliente') {
            return redirect()->route('clientes.edit', $request->cliente_id)->with('success', 'Sucursal creada exitosamente.');
        }
    
        return redirect()->route('sucursales.index')->with('success', 'Sucursal creada exitosamente.');
    }
    public function buscar(Request $request)
    {
        $searchSucursal = $request->input('search');
        $perPage = $request->input('perPage', 6);
    
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 6;
        $perPage = min($perPage, 200);
    
        // Si el término de búsqueda está vacío, redirige al índice con un mensaje de error
        if (empty($searchSucursal)) {
            return redirect()->route('sucursales.index', ['perPage' => $perPage])->with('error', 'Por favor ingrese un término de búsqueda.');
        }
        
        $query = Sucursal::with('cliente');
    
        if (is_numeric($searchSucursal)) {
            $query->where('id', $searchSucursal);
        } else {
            $query->where('nombre_sucursal', 'like', "%$searchSucursal%")
                  ->orWhere('telefono_sucursal', 'like', "%$searchSucursal%")
                  ->orWhere('direccion_sucursal', 'like', "%$searchSucursal%")
                  ->orWhereHas('cliente', function($q) use ($searchSucursal) {
                      $q->where('nombre_cliente', 'like', "%$searchSucursal%");
                  });
        }
        
        $sucursales = $query->paginate($perPage)->appends($request->except('page'));
    
        // No redirecciones si no hay resultados. Simplemente pasa el mensaje a la vista.
        // La lógica de mostrar el mensaje se manejará en la vista Blade.
        return view('sucursales.sucursales', compact('sucursales', 'searchSucursal'));
    }




    public function show($id)
    {
        $sucursal = Sucursal::with(['cliente'])->findOrFail($id);
        return view('sucursales.detalle', compact('sucursal'));
    }

    public function edit($id, Request $request)
    {
        $sucursal = Sucursal::findOrFail($id);
        $clientes = Cliente::all();
        $from = $request->get('from');
        return view('sucursales.editar', compact('sucursal', 'clientes','from'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_sucursal' => 'required|string|max:255',
            'telefono_sucursal' => 'required|string|max:255',
            'direccion_sucursal' => 'required|string|max:255',
            'cliente_id' => 'required|exists:cliente,id', // Cambiado a 'cliente' en lugar de 'clientes'
        ]);

        // Encontrar la sucursal y actualizarla
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update([
            'nombre_sucursal' => $request->nombre_sucursal,
            'telefono_sucursal' => $request->telefono_sucursal,
            'direccion_sucursal' => $request->direccion_sucursal,
            'cod_cliente' => $request->cliente_id, // Asegúrate de que el nombre del campo en la tabla Sucursal sea 'cod_cliente'
        ]);
        
       
        if ($request->has('from') && $request->get('from') === 'editar_cliente') {
            return redirect()->route('clientes.edit', $request->cliente_id)->with('success', 'Sucursal actualizada exitosamente.');
        }
        
    
        return redirect()->route('sucursales.index')->with('success', 'Sucursal actualizada exitosamente.');
    }

    public function destroy($id)
    {
        // Obtener la sucursal por el ID
        $sucursal = Sucursal::findOrFail($id);
        
        
        $contactosAsociados = Contacto::where('cod_sucursal', $sucursal->id)->exists();
        if ($contactosAsociados) {
            return back()->withErrors(['error' => 'No se puede eliminar la sucursal porque tiene contactos asociados.']);
        }
        
        
        $dispositivosAsociados = Dispositivo::where('cod_sucursal', $sucursal->id)->exists();
        
        if ($dispositivosAsociados) {
            return back()->withErrors(['error' => 'No se puede eliminar la sucursal porque tiene dispositivos asociados.']);
        }


        // Eliminar la sucursal
        $sucursal->delete();

        // Mostrar el mensaje en la misma página y redirigir con JavaScript desde la vista
        return back()->with('success', 'Sucursal eliminada exitosamente.');
        //->with('success', 'Sucursal eliminada exitosamente.');
    }
}
