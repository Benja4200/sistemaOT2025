<?php

namespace App\Http\Controllers\ControladorParametros;

use App\Http\Controllers\Controller;
use App\Models\Sublinea;
use App\Models\Linea;
use App\Models\Subcategoria;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\TipoOt;
use App\Models\PrioridadOt;
use App\Models\EstadoOt;
use App\Models\TipoVisita;
use App\Models\TipoServicio;
use App\Models\Modelo;
use App\Models\Usuario;
use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\Contacto;
use App\Models\Servicio;
use App\Models\TecnicoServicio;
use App\Models\Tarea;
use App\Models\Dispositivo;
use App\Models\DispositivoOt;
use App\Models\TareaOt;
use App\Models\ContactoOt;
use App\Models\EquipoTecnico;
use App\Models\Avance;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ParametrosController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search', '');
        $sort = $request->input('sort', 'categoria'); // Valor por defecto
        $order = $request->input('order', 'asc'); 
        $perPage = $request->input('perPage', 5);
        
        $categorias = Categoria::where('nombre_categoria', 'like', "%{$search}%")->paginate(10);
        $subcategorias = Subcategoria::with('categoria')
            ->where('nombre_subcategoria', 'like', "%{$search}%")
            ->paginate(10);
        $lineas = Linea::with('subcategoria')
            ->where('nombre_linea', 'like', "%{$search}%")
            ->paginate(10);
        $sublineas = Sublinea::with('linea')
            ->where('nombre_sublinea', 'like', "%{$search}%")
            ->paginate(10);
        $marcas = Marca::where('nombre_marca', 'like', "%{$search}%")->paginate(10);
        $tipos_ot = TipoOt::where('descripcion_tipo_ot', 'like', "%{$search}%")->paginate(10);
        $prioridades_ot = PrioridadOt::where('descripcion_prioridad_ot', 'like', "%{$search}%")->paginate(10);
        $estados_ot = EstadoOt::where('descripcion_estado_ot', 'like', "%{$search}%")->paginate(10);
        $tipos_visita = TipoVisita::where('descripcion_tipo_visita', 'like', "%{$search}%")->paginate(10);
        $tipos_servicio = TipoServicio::where('descripcion_tipo_servicio', 'like', "%{$search}%")->paginate(10);
        $modelos = Modelo::with('marca', 'sublinea')
            ->where('nombre_modelo', 'like', "%{$search}%")
            ->paginate(10);
        $usuarios = Usuario::where('nombre_usuario', 'like', "%{$search}%")
            ->orWhere('email_usuario', 'like', "%{$search}%")
            ->paginate(10);
        $tecnicos = Tecnico::with('usuario')
            ->where('nombre_tecnico', 'like', "%{$search}%")
            ->paginate(10);
        $clientes = Cliente::where('nombre_cliente', 'like', "%{$search}%")
            ->orWhere('email_cliente', 'like', "%{$search}%")
            ->paginate(10);
        $sucursales = Sucursal::with('cliente')
            ->where('nombre_sucursal', 'like', "%{$search}%")
            ->paginate(10);
        $contactos = Contacto::with('sucursal')
            ->where('nombre_contacto', 'like', "%{$search}%")
            ->paginate(10);
        $servicios = Servicio::with('tipoServicio', 'sublinea')
            ->where('nombre_servicio', 'like', "%{$search}%")
            ->paginate(10);
        $tecnico_servicios = TecnicoServicio::with('tecnico', 'servicio')->paginate(10);
        $tareas = Tarea::with('servicio')
            ->where('nombre_tarea', 'like', "%{$search}%")
            ->paginate(10);
        $dispositivos = Dispositivo::with('modelo', 'sucursal')
            ->where('numero_serie_dispositivo', 'like', "%{$search}%")
            ->paginate(10);
        $dispositivos_ot = DispositivoOt::with('dispositivo', 'ot', 'detalles', 'accesorios')->paginate(10);
        $tareas_ot = TareaOt::with('tarea', 'ot')->paginate(10);
        $contactos_ot = ContactoOt::with('contacto', 'ot')->paginate(10);
        $equipos_tecnicos = EquipoTecnico::with('tecnico', 'ot')->paginate(10);
        $avances = Avance::with('ot')
            ->where('comentario_avance', 'like', "%{$search}%")
            ->paginate(10);

        //$categorias2 = Categoria::with(['subcategorias.lineas.sublines'])
        //->where('nombre_categoria', 'like', "%{$search}%")
        //->paginate(10);
        
        

        // Cargar todas las categorías con sus subcategorías, líneas y sublíneas
    $categorias2 = Categoria::with(['subcategorias.lineas.sublines'])->get();

    // Crear una colección para almacenar los elementos filtrados
    $items = collect();

    foreach ($categorias2 as $categoria) {
        // Si la categoría no tiene subcategorías, aún la agregamos
        if ($categoria->subcategorias->isEmpty()) {
            $items->push([
                'categoria' => $categoria->nombre_categoria,
                'subcategoria' => null,
                'linea' => null,
                'sublinea' => null,
                'categoria_id' => $categoria->id,
            ]);
        } else {
            foreach ($categoria->subcategorias as $subcategoria) {
                // Si la subcategoría no tiene líneas, aún la agregamos
                if ($subcategoria->lineas->isEmpty()) {
                    $items->push([
                        'categoria' => $categoria->nombre_categoria,
                        'subcategoria' => $subcategoria->nombre_subcategoria,
                        'linea' => null,
                        'sublinea' => null,
                        'categoria_id' => $categoria->id,
                    ]);
                } else {
                    foreach ($subcategoria->lineas as $linea) {
                        // Si la línea no tiene sublíneas, aún la agregamos
                        if ($linea->sublines->isEmpty()) {
                            $items->push([
                                'categoria' => $categoria->nombre_categoria,
                                'subcategoria' => $subcategoria->nombre_subcategoria,
                                'linea' => $linea->nombre_linea,
                                'sublinea' => null,
                                'categoria_id' => $categoria->id,
                            ]);
                        } else {
                            foreach ($linea->sublines as $sublinea) {
                                // Agregar la relación completa
                                $items->push([
                                    'categoria' => $categoria->nombre_categoria,
                                    'subcategoria' => $subcategoria->nombre_subcategoria,
                                    'linea' => $linea->nombre_linea,
                                    'sublinea' => $sublinea->nombre_sublinea,
                                    'categoria_id' => $categoria->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    // Filtrar los elementos según el término de búsqueda
    $items = $items->filter(function ($item) use ($search) {
        return stripos($item['categoria'], $search) !== false ||
               stripos($item['subcategoria'], $search) !== false ||
               stripos($item['linea'], $search) !== false ||
               stripos($item['sublinea'], $search) !== false;
    });

    // Ordenar los elementos
    $items = $items->sortBy($sort);
    if ($order === 'desc') {
        $items = $items->reverse();
    }

    // Paginación de los elementos
    $currentPage = $request->input('page', 1);
    $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
    $paginatedItems = new LengthAwarePaginator($currentItems, $items->count(), $perPage, $currentPage, [
        'path' => $request->url(),
        'query' => array_merge($request->query(), ['perPage' => $perPage]), // Mantener otros parámetros
    ]);

        
        return view('parametros.parametros', compact(
            'categorias',
            'subcategorias',
            'lineas',
            'sublineas',
            'marcas',
            'tipos_ot',
            'prioridades_ot',
            'estados_ot',
            'tipos_visita',
            'tipos_servicio',
            'modelos',
            'usuarios',
            'tecnicos',
            'clientes',
            'sucursales',
            'contactos',
            'servicios',
            'tecnico_servicios',
            'tareas',
            'dispositivos',
            'dispositivos_ot',
            'tareas_ot',
            'contactos_ot',
            'equipos_tecnicos',
            'avances',
            'search',
            'categorias2',
            'paginatedItems'
        ));
    }

    public function show($id)
    {
        $avance = Avance::with('ot')->findOrFail($id);
        return view('parametros.detalle', compact('avance'));
    }
}
