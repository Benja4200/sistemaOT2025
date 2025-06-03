<?php

namespace App\Http\Controllers\ControladorEquipos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Linea;
use App\Models\Sublinea;
use App\Models\Marca;
use App\Models\Repuesto;

use App\Models\TipoOt;
use App\Models\PrioridadOt;
use App\Models\EstadoOt;
use App\Models\TipoVisita;
use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Sucursal;
use App\Models\Modelo;
class ModeloController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el número de resultados por página de la solicitud, con un valor por defecto
        $perPage = $request->input('perPage', 10); // Valor por defecto 10 para modelos
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10;
        $perPage = min($perPage, 200);

        // Inicializar la consulta base
        $query = Modelo::query();

        // Aplicar los filtros de categoría, subcategoría, línea, sublínea, marca si existen
        $categoriaId = $request->input('categoria');
        $subcategoriaId = $request->input('subcategoria');
        $lineaId = $request->input('linea');
        $sublineaId = $request->input('sublinea');
        $marcaId = $request->input('marca');
        $search = $request->input('search'); // También pasamos 'search' a la vista para el placeholder

        if ($categoriaId) {
            $query->whereHas('sublinea.linea.subcategoria.categoria', function ($q) use ($categoriaId) {
                $q->where('id', $categoriaId);
            });
        }
        if ($subcategoriaId) {
            $query->whereHas('sublinea.linea.subcategoria', function ($q) use ($subcategoriaId) {
                $q->where('id', $subcategoriaId);
            });
        }
        if ($lineaId) {
            $query->whereHas('sublinea.linea', function ($q) use ($lineaId) {
                $q->where('id', $lineaId);
            });
        }
        if ($sublineaId) {
            $query->whereHas('sublinea', function ($q) use ($sublineaId) {
                $q->where('id', $sublineaId);
            });
        }
        if ($marcaId) {
            $query->where('cod_marca', $marcaId);
        }

        // Si hay un término de búsqueda, se aplicará al final de los filtros
        if (!empty($search)) {
            // Esto solo se aplica si se llega al index con un search
            // (e.g., después de una redirección de search() sin resultados)
            if (is_numeric($search)) {
                $query->where('id', $search);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre_modelo', 'like', "%{$search}%")
                      ->orWhere('part_number_modelo', 'like', "%{$search}%")
                      ->orWhere('desc_corta_modelo', 'like', "%{$search}%")
                      ->orWhere('desc_larga_modelo', 'like', "%{$search}%");
                });
            }
        }

        // Obtener los modelos paginados y adjuntar todos los parámetros de la solicitud
        $modelos = $query->orderBy('id', 'desc')->paginate($perPage)->appends($request->query());

        // Cargar datos para los selectores de filtro
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::when($categoriaId, function ($query) use ($categoriaId) {
            $query->whereHas('categoria', function ($q) use ($categoriaId) {
                $q->where('id', $categoriaId);
            });
        })->get();

        $lineas = Linea::when($subcategoriaId, function ($query) use ($subcategoriaId) {
            $query->whereHas('subcategoria', function ($q) use ($subcategoriaId) {
                $q->where('id', $subcategoriaId);
            });
        })->get();

        $sublineas = Sublinea::when($lineaId, function ($query) use ($lineaId) {
            $query->whereHas('linea', function ($q) use ($lineaId) {
                $q->where('id', $lineaId);
            });
        })->get();
        $marcas = Marca::all();

        return view('modelos.modelos', compact('modelos', 'categorias', 'subcategorias', 'lineas', 'sublineas', 'marcas', 'search'));
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $perPage = $perPage ?: 10;
        $perPage = min($perPage, 200);

        // Capturar todos los filtros existentes, excepto 'search' y 'page'
        $filters = $request->except(['search', 'page']);

        $query = Modelo::query();

        // Aplicar los filtros de categoría, subcategoría, etc. si vienen en la URL
        $categoriaId = $request->input('categoria');
        $subcategoriaId = $request->input('subcategoria');
        $lineaId = $request->input('linea');
        $sublineaId = $request->input('sublinea');
        $marcaId = $request->input('marca');

        if ($categoriaId) {
            $query->whereHas('sublinea.linea.subcategoria.categoria', function ($q) use ($categoriaId) {
                $q->where('id', $categoriaId);
            });
        }
        if ($subcategoriaId) {
            $query->whereHas('sublinea.linea.subcategoria', function ($q) use ($subcategoriaId) {
                $q->where('id', $subcategoriaId);
            });
        }
        if ($lineaId) {
            $query->whereHas('sublinea.linea', function ($q) use ($lineaId) {
                $q->where('id', $lineaId);
            });
        }
        if ($sublineaId) {
            $query->whereHas('sublinea', function ($q) use ($sublineaId) {
                $q->where('id', $sublineaId);
            });
        }
        if ($marcaId) {
            $query->where('cod_marca', $marcaId);
        }

        // Aplicar la lógica de búsqueda solo si hay un término de búsqueda
        if (!empty($search)) {
            if (is_numeric($search)) {
                $query->where('id', $search);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre_modelo', 'like', "%{$search}%")
                      ->orWhere('part_number_modelo', 'like', "%{$search}%")
                      ->orWhere('desc_corta_modelo', 'like', "%{$search}%")
                      ->orWhere('desc_larga_modelo', 'like', "%{$search}%");
                });
            }
        } else {
            // Si el término de búsqueda está vacío, redirigir al index manteniendo los otros filtros y perPage
            return redirect()->route('modelos.index', $request->query());
        }

        $modelos = $query->orderBy('id', 'desc')->paginate($perPage)->appends($request->query());

        // Si no hay resultados para la búsqueda actual
        if ($modelos->isEmpty()) {
            // Redirigir al index, removiendo solo el término de búsqueda de la URL
            // y manteniendo los filtros de categoría, subcategoría, etc. y perPage
            $redirectParams = $request->except(['search', 'page']);
            return redirect()->route('modelos.index', $redirectParams)
                             ->with('error', 'No se encontraron modelos con el término: "' . $search . '".');
        }

        // Cargar datos para los selectores de filtro
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::when($categoriaId, function ($query) use ($categoriaId) {
            $query->whereHas('categoria', function ($q) use ($categoriaId) {
                $q->where('id', $categoriaId);
            });
        })->get();
        $lineas = Linea::when($subcategoriaId, function ($query) use ($subcategoriaId) {
            $query->whereHas('subcategoria', function ($q) use ($subcategoriaId) {
                $q->where('id', $subcategoriaId);
            });
        })->get();
        $sublineas = Sublinea::when($lineaId, function ($query) use ($lineaId) {
            $query->whereHas('linea', function ($q) use ($lineaId) {
                $q->where('id', $lineaId);
            });
        })->get();
        $marcas = Marca::all();

        return view('modelos.modelos', compact('modelos', 'categorias', 'subcategorias', 'lineas', 'sublineas', 'marcas', 'search'));
    }



    // Realiza búsqueda de los modelos según un término dado
    public function buscar(Request $request)
    {
        // Actualizar el parámetro de búsqueda con un valor por defecto si está vacío
        $request->merge(['search' => $request->input('search', '')]);
        return $this->handleRequest($request);
    }

    // Muestra el detalle de un modelo específico
    public function show($id)
    {
        $modelo = Modelo::with(['sublinea.linea.subcategoria.categoria', 'marca', 'dispositivos.sucursal'])
            ->findOrFail($id);
        $modelosRelacionados = Modelo::where('id', '!=', $id)->get();
        $repuestosAsignados = $modelo->repuestos;
        return view('modelos.detalle', compact('modelo', 'modelosRelacionados','repuestosAsignados'));
    }

    // Muestra el formulario para crear un nuevo modelo
    public function create()
    {
        
        $categorias = Categoria::where('nombre_categoria', '!=', 'SERVICIO')->get();
        $subcategorias = Subcategoria::all();
        $lineas = Linea::all();
        $sublineas = Sublinea::all();
        $marcas = Marca::all();

        return view('modelos.agregar', compact('categorias', 'subcategorias', 'lineas', 'sublineas', 'marcas'));
    }
    
    public function asignarRepuestos($id)
    {
        $modelo = Modelo::findOrFail($id);
        $repuestos = Repuesto::all(); // Obtener repuestos no asignados
        $repuestosAsignados = $modelo->repuestos; // Obtener los repuestos asignados
        $repuestosAsignadosId = $modelo->repuestos()->pluck('repuesto.id'); // Obtener solo los IDs de los repuestos asignados
        return view('modelos.asignar', compact('modelo', 'repuestos', 'repuestosAsignados','repuestosAsignadosId'));
    }
    
    public function storeRepuestos(Request $request, $id)
    {
        // Validar que se hayan seleccionado repuestos
        $request->validate([
            'repuestos' => 'required|array',
            'repuestos.*' => 'exists:repuesto,id', // Asegúrate de que los IDs de repuestos existan
        ]);
    
        // Encontrar el modelo por su ID
        $modelo = Modelo::findOrFail($id);
    
        // Asignar los repuestos al modelo
        $modelo->repuestos()->sync($request->repuestos); // Asegúrate de que la relación esté definida en el modelo
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('modelos.index')->with('success', 'Repuestos asignados correctamente.');
    }

    public function nuevoModelo()
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        $lineas = Linea::all();
        $sublineas = Sublinea::all();
        $marcas = Marca::all();
        return view('ordenes.agregarModelo', compact('categorias', 'subcategorias', 'lineas', 'sublineas', 'marcas'));
    }

    public function newModelo(Request $request)
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
            'nombre_modelo' => 'required|string|max:255',
            'part_number_modelo' => 'nullable|string|max:255',
            'desc_corta_modelo' => 'nullable|string',
            'desc_larga_modelo' => 'nullable|string',
            //'cod_categoria' => 'required|integer',
            //'cod_subcategoria' => 'required|integer',
            //'cod_linea' => 'required|integer',
            'cod_sublinea' => 'required|integer',
            'cod_marca' => 'required|integer',
        ]);

        Modelo::create($request->all());

        return redirect()->route('ordenes.create')->with('success', 'Modelo agregado con éxito.')
        ->with(compact('tipos', 'prioridades', 'estados', 'tiposVisitas', 'tecnicos', 'clientes', 'servicios', 'sucursales', 'modelodispositivos'));
    }
    // Almacena un nuevo modelo en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:255',
            'part_number_modelo' => 'nullable|string|max:255',
            'desc_corta_modelo' => 'nullable|string',
            'desc_larga_modelo' => 'nullable|string',
            //'cod_categoria' => 'required|integer',
            //'cod_subcategoria' => 'required|integer',
            //'cod_linea' => 'required|integer',
            'cod_sublinea' => 'required|integer',
            'cod_marca' => 'required|integer',
        ]);

        Modelo::create($request->all());

        return redirect()->route('modelos.index')->with('success', 'Modelo agregado con éxito.');
    }

    public function edit(Request $request, $id)
    {
        // Obtener el modelo por su ID
        $modelo = Modelo::findOrFail($id);
        // Obtener las relaciones necesarias de manera separada
        $sublinea = $modelo->sublinea;  // Cargar sublinea relacionada
        $linea = $sublinea ? $sublinea->linea : null;  // Cargar la línea relacionada
        $subcategoria = $linea ? $linea->subcategoria : null;  // Cargar la subcategoría relacionada
        $categoria = $subcategoria ? $subcategoria->categoria : null;  // Cargar la categoría relacionada
        $marca = $modelo->marca;  // Cargar la marca relacionada
    
        // Obtener las categorías, subcategorías, líneas y sublíneas
        $categorias = Categoria::where('nombre_categoria', '!=', 'SERVICIO')->get();
        $subcategorias = Subcategoria::where('cod_categoria', $categoria ? $categoria->id : null)->get();
        $lineas = Linea::where('cod_subcategoria', $subcategoria ? $subcategoria->id : null)->get();
        $sublineas = Sublinea::where('cod_linea',$sublinea ? $sublinea->cod_linea : null)->get();
        $marcas = Marca::all();
    
        // Pasar los datos a la vista
        return view('modelos.editar', compact('modelo', 'categorias', 'subcategorias', 'lineas', 'sublineas', 'marcas', 'sublinea', 'linea', 'subcategoria', 'categoria', 'marca'));
    }


    // Actualiza un modelo existente en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_modelo' => 'required|string|max:255',
            'part_number_modelo' => 'nullable|string|max:255',
            'desc_corta_modelo' => 'nullable|string',
            'desc_larga_modelo' => 'nullable|string',
            //'cod_categoria' => 'required|integer',
            //'cod_subcategoria' => 'required|integer',
            //'cod_linea' => 'required|integer',
            'cod_sublinea' => 'required|integer',
            'cod_marca' => 'required|integer',
        ]);

        $modelo = Modelo::findOrFail($id);
        $modelo->update($request->all());

        return redirect()->route('modelos.index')->with('success', 'Modelo actualizado con éxito.');
    }

    // Elimina un modelo existente de la base de datos
    public function destroy($id)
    {
        $modelo = Modelo::findOrFail($id);
        $modelo->delete();

        return redirect()->route('modelos.index')->with('success', 'Modelo eliminado con éxito.');
    }
    
    public function getModelos($marca, $sublinea)
    {
        // Aquí puedes agregar la lógica que necesitas para obtener los modelos
        // basados en marca y sublinea. Por ejemplo:
    
        $modelos = Modelo::where('cod_marca', $marca)
                         ->where('cod_sublinea', $sublinea)
                         ->get();
    
        // Retornar la vista que te interesa, pasando los modelos obtenidos
        return view('modelos.editar', compact('modelos'));
    }


}
