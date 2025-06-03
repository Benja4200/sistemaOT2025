<?php

namespace App\Http\Controllers\ControladorDispositivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dispositivo;

use App\Models\TipoOt;
use App\Models\PrioridadOt;
use App\Models\EstadoOt;
use App\Models\TipoVisita;
use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Sucursal;
use App\Models\Modelo;
use App\Models\Categoria;
class DispositivoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el número de resultados por página de la solicitud
        $perPage = $request->input('perPage', 7); // Valor por defecto 7

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 7; // Si filter_var devuelve false, usar 7
        $perPage = min($perPage, 200); // Limitar a un máximo de 200 registros

        // Obtener el término de búsqueda, si existe, que pudo venir de una redirección
        $search = $request->input('search');

        // Construir la consulta base para los dispositivos
        $query = Dispositivo::orderBy('id', 'desc');

        // Si hay un término de búsqueda (que podría venir de una redirección de 'buscar')
        // Este if se asegura de que la paginación con el término de búsqueda funcione en el index.
        if (!empty($search)) {
            $query->where('numero_serie_dispositivo', 'like', "%{$search}%")
                  ->orWhereHas('modelo', function ($q) use ($search) {
                      $q->where('nombre_modelo', 'like', "%{$search}%");
                  });
        }

        // Obtener los dispositivos paginados
        // appends() para mantener todos los parámetros de la URL (perPage y search)
        $dispositivos = $query->paginate($perPage)->appends($request->query());


        return view('dispositivo.dispositivo', compact('dispositivos', 'search'));
    }

    public function create()
    {
        $modelos = Modelo::all(); // Obtener todos los modelos
        $clientes = Cliente::all();
        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        $categorias = Categoria::all();
        return view('dispositivo.agregar', compact('modelos','clientes','sucursales','categorias'));
    }
    
    
    public function nuevoDispositivo()
    {
        $modelos = Modelo::all(); // Obtener todos los modelos
        $clientes = Cliente::all();
        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        $categorias = Categoria::all(); 
        return view('ordenes.agregarDispositivo', compact('modelos','clientes','sucursales','categorias'));
    }
    
    public function newDispositivo(Request $request)
    {
        $request->validate([
            'numero_serie_dispositivo' => 'required|string|max:255',
            'cod_modelo' => 'required|exists:modelo,id',
            'cod_sucursal' => 'required|exists:sucursal,id',
        ]);
        
        $tipos = TipoOt::all();
        $prioridades = PrioridadOt::all();
        $estados = EstadoOt::all();
        $tiposVisitas = TipoVisita::all();
        $tecnicos = Tecnico::all();
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        $sucursales = Sucursal::all();
        $modelodispositivos = Modelo::all();
        
        try {
            Dispositivo::create([
                'numero_serie_dispositivo' => $request->numero_serie_dispositivo,
                'cod_modelo' => $request->cod_modelo,
                'cod_sucursal' => $request->cod_sucursal,
            ]);
    
            return redirect()->route('ordenes.create')->with('success', 'Dispositivo creado exitosamente.')
            ->with(compact('tipos', 'prioridades', 'estados', 'tiposVisitas', 'tecnicos', 'clientes', 'servicios', 'sucursales', 'modelodispositivos'));
        } catch (\Exception $e) {
            return redirect()->route('dispositivos.index')->with('error', 'Error al crear el dispositivo: ' . $e->getMessage());
        }
    }
    public function getSucursales($clienteId)
    {
        $sucursales = Sucursal::where('cod_cliente', $clienteId)->get(); // Filtrar sucursales por cliente
        return response()->json($sucursales); // Devolver las sucursales en formato JSON
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_serie_dispositivo' => 'required|string|max:255',
            'cod_modelo' => 'required|exists:modelo,id',
            'cod_sucursal' => 'required|exists:sucursal,id',
        ]);
    
        try {
            Dispositivo::create([
                'numero_serie_dispositivo' => $request->numero_serie_dispositivo,
                'cod_modelo' => $request->cod_modelo,
                'cod_sucursal' => $request->cod_sucursal,
            ]);
    
            return redirect()->route('dispositivos.index')->with('success', 'Dispositivo creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('dispositivos.index')->with('error', 'Error al crear el dispositivo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $dispositivo = Dispositivo::with(['modelo', 'sucursal'])->findOrFail($id);
        return view('dispositivo.detalle', compact('dispositivo'));
    }


    public function edit($id)
    {
        
        $dispositivo = Dispositivo::findOrFail($id);
        $modelos = Modelo::all();
        $clientes = Cliente::all(); // Obtener todos los clientes
    
        // Obtener la sucursal asociada al dispositivo
        $categorias = Categoria::all();

        $sucursal = Sucursal::find($dispositivo->cod_sucursal);
        // Obtener las sucursales del cliente asociado a la sucursal
        $cliente = null;
        if($sucursal){
            $cliente = $sucursal->cliente;
            $sucursales = Sucursal::where('cod_cliente', $sucursal->cod_cliente)->get();
        }else{
            $sucursales = Sucursal::all();
        }
        
        return view('dispositivo.editar', compact('dispositivo', 'modelos', 'clientes', 'sucursales', 'sucursal','cliente','categorias'));
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'numero_serie_dispositivo' => 'required|string|max:255',
            'cod_modelo' => 'required|exists:modelo,id',
            'cod_sucursal' => 'required|exists:sucursal,id',
        ]);
    
        try {
            $dispositivo = Dispositivo::findOrFail($id);
            $dispositivo->update([
                'numero_serie_dispositivo' => $request->numero_serie_dispositivo,
                'cod_modelo' => $request->cod_modelo,
                'cod_sucursal' => $request->cod_sucursal,
            ]);
    
            return redirect()->route('dispositivos.index')->with('edit_success', 'Dispositivo actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('dispositivos.index')->with('error', 'Error al actualizar el dispositivo: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $dispositivo = Dispositivo::findOrFail($id);
        $dispositivo->delete();

        return back()->with('success', 'Dispositivo eliminado exitosamente.');
    }

    public function buscar(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 7); // Mantener el perPage de la solicitud si viene.

        // Validar que $perPage sea un número entero positivo.
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 7;
        $perPage = min($perPage, 200);

        // Si el término de búsqueda está vacío, redirigir al index sin el parámetro 'search'
        if (empty($search)) {
            return redirect()->route('dispositivos.index', ['perPage' => $perPage]);
        }

        $dispositivos = Dispositivo::where('numero_serie_dispositivo', 'like', "%{$search}%")
            ->orWhereHas('modelo', function ($q) use ($search) {
                $q->where('nombre_modelo', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'perPage' => $perPage]); // Asegurar que los parámetros se mantengan


        return view('dispositivo.dispositivo', compact('dispositivos', 'search'));
    }
    
    public function getModelosPorCategoria($categoriaId) {
        // Obtener modelos que pertenecen a la categoría a través de la sublínea
        $modelos = Modelo::whereHas('sublinea.linea.subcategoria.categoria', function($query) use ($categoriaId) {
            $query->where('id', $categoriaId);
        })->get();
        return response()->json($modelos);
    }
    
    public function getModelosPorSubcategoria($subcategoriaId) {
        // Obtener modelos que pertenecen a la subcategoría a través de la sublínea
        $modelos = Modelo::whereHas('sublinea.linea.subcategoria', function($query) use ($subcategoriaId) {
            $query->where('id', $subcategoriaId);
        })->get();
        return response()->json($modelos);
    }
    
    public function getModelosPorLinea($lineaId) {
        // Obtener modelos que pertenecen a la línea a través de la sublínea
        $modelos = Modelo::whereHas('sublinea.linea', function($query) use ($lineaId) {
            $query->where('id', $lineaId);
        })->get();
        return response()->json($modelos);
    }
    
    public function getModelosPorSublinea($sublineaId) {
        // Obtener modelos que pertenecen a la sublínea
        $modelos = Modelo::where('cod_sublinea', $sublineaId)->get();
        return response()->json($modelos);
    }
}
