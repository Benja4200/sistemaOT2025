<?php
use App\Http\Controllers\ControladorOrdenesTecnicos\MisOrdenes;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ControladorOrdenes\OrdenesController;
use App\Http\Controllers\ControladorRoles\RolesController;
use App\Http\Controllers\ControladorClientes\ClientesController;
use App\Http\Controllers\ControladorSucursales\SucursalesController;
use App\Http\Controllers\ControladorServicios\ServiciosController;
use App\Http\Controllers\ControladorServicios\TareaServiciosController;
use App\Http\Controllers\ControladorTecnicos\TecnicosController;
use App\Http\Controllers\ControladorEquipos\ModeloController;

use App\Http\Controllers\ControladorMarcas\MarcasControlador;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\ControladorContactos\ContactosController;
use App\Http\Controllers\ControladorParametros\ParametrosController;
use App\Http\Controllers\ControladorDispositivo\DispositivoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController as UsuariosController;
use App\Http\Controllers\migrarController;
use App\Http\Controllers\PasswordUpdateController;
use App\Http\Controllers\ControladorOrdenes\AvancesController;
use App\Http\Controllers\ControladorParametros\CategoriaController;
use App\Http\Controllers\ControladorParametros\SubcategoriaController;
use App\Http\Controllers\ControladorParametros\LineaController;
use App\Http\Controllers\ControladorParametros\SublineaController;
use App\Http\Controllers\ControladorRepuestos\RepuestosControlador;
use App\Http\Controllers\ControladorImpresiones\impresionController;

use App\Http\Controllers\globalFuntions;

// ruta para actualizar contrasenas
Route::get('password-update', [PasswordUpdateController::class, 'index'])->name('password.update');

// ruta para migrar datos
Route::get('migrar', [migrarController::class, 'index']);

// ruta para procesar el envio del formulario de inicio de sesion
Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

// Ruta para mostrar el formulario de inicio de sesión
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para obtener los datos de las OTs en formato JSON
Route::get('/api/ots', [HomeController::class, 'obtenerDatosOt']);

Route::get('/datosparacrono/sd', [globalFuntions::class, 'datosparacrono']);

Route::get('/grafico-registros', function () {
    return view('home.home');
});

Route::get('/obtener-datos-por-anio', [HomeController::class, 'obtenerDatosPorAnio']);

Route::get('/obtener-datos-ordenes', [HomeController::class, 'obtenerDatosOrdenesPorEstado'])->name('obtenerDatosOrdenesPorEstado');

Route::get('/clientescantidad', [HomeController::class, 'obtenerCantidaClientes']);

Route::get('/obtenerOrdenesCantidadContador', [HomeController::class, 'obtenerOrdenesCantidadContador']);

Route::get('/marcas/cantidad', [HomeController::class, 'obtenerCantidadMarcas']);

Route::get('/tipo_servicios', [OrdenesController::class, 'getServiciosJson']);

Route::get('/ordenes/obtenerservicio/{id_servicio}', [OrdenesController::class, 'obtenerDatosServicio']);

Route::middleware(['auth'])->group(function () {
    // Ruta para cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Ruta para la página de inicio
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home.page');

    // rutas DE OT (Ordenes de Trabajo)
    Route::get('/ordenes/obtenerOrden/{id}', [OrdenesController::class, 'obtenerOrden']);
    Route::get('/ordenes', [OrdenesController::class, 'index'])->middleware('can:ordenes.index')->name('ordenes.index');

    Route::get('/ordenes/agregar', [OrdenesController::class, 'create'])->middleware('can:ordenes.create')->name('ordenes.create');
    Route::post('/ordenes/agregar', [OrdenesController::class, 'store'])->middleware('can:ordenes.create')->name('ordenes.store');
    Route::delete('/ordenes/deletd/{numero_ot}', [OrdenesController::class, 'destroy'])->name('ordenes.destroy');


    Route::get('/ordenes/buscar', [OrdenesController::class, 'buscar'])->name('ordenes.buscar');
    Route::get('/ordenes/{orden}', [OrdenesController::class, 'show'])->name('ordenes.show');
    Route::get('/ordenes/{orden}/editar', [OrdenesController::class, 'edit'])->name('ordenes.edit');
    Route::post('/ordenes/{orden}/editar', [OrdenesController::class, 'update'])->name('ordenes.update');
    Route::get('/tareas/{servicioId}', [OrdenesController::class, 'tareas']);
    Route::get('/sucursal/{clienteId}', [OrdenesController::class, 'sucursales']);
    Route::get('/contacto/{sucursalId}', [OrdenesController::class, 'contactos']);
    Route::get('/dispositivo/{sucursalId}/{servicioId}', [OrdenesController::class, 'dispositivos']);
    Route::get('/servicio/{servicioId}', [OrdenesController::class, 'servicioTipo']);
    Route::get('/tecnicos/{servicioId}', [OrdenesController::class, 'tecnicosServicio']);

    // rutas para TareaServiciosController

    Route::get('/tareas/servicio/{servicioId}', [OrdenesController::class, 'tareas']);
    Route::resource('tareas', TareaServiciosController::class);

    // Rutas de Clientes

    Route::get('clientes/buscar', [ClientesController::class, 'buscarcliente'])->name('clientes.buscarcliente');
    Route::resource('clientes', ClientesController::class);
    Route::get('nuevoCliente/crear', [ClientesController::class, 'nuevoCliente'])->name('clientes.nuevoCliente');
    Route::post('nuevoCliente/insertar', [ClientesController::class, 'newClient'])->name('clientes.nuevo');

    // rutas de tecnicos
    Route::get('tecnicos/asignar-servicio/{id}', [TecnicosController::class, 'asignarServicios'])->name('tecnicos.asignar_servicios');
    Route::post('tecnicos/{id}/servicios', [TecnicosController::class, 'storeServicios'])->name('tecnicos.store_servicios');
    
    
    Route::post('tecnicos/buscar', [TecnicosController::class, 'buscar'])->name('tecnicos.buscar');
    Route::get('/tecnicos/{servicioId}', [OrdenesController::class, 'tecnicosServicio']);
    Route::resource('tecnicos', TecnicosController::class);

    // ruta repuestos
    Route::get('/repuestos/search', [RepuestosControlador::class, 'search'])->name('repuestos.search');
    // repuestos route al usar resource se le integraran los metodos= index, create, store, show, edit, update, destroy
    Route::resource('repuestos', RepuestosControlador::class);

    // Rutas de Avances (Ordenes - Avances)
    Route::get('/ordenes/{numero_ot}/avances', [AvancesController::class, 'index'])->name('ordenes.avances');
    Route::post('/ordenes/{numero_ot}/avances', [AvancesController::class, 'store'])->name('ordenes.avances.store');
    Route::post('/ordenes/{numero_ot}/finalizar', [AvancesController::class, 'finalizar'])->name('ordenes.finalizar');
    
    Route::get('/ordenes/{numero_ot}/verAvances', [AvancesController::class, 'verAvances'])->name('ordenes.verAvances');


    // Rutas de Sucursales
    Route::get('sucursales/buscar', [SucursalesController::class, 'buscar'])->name('sucursales.buscar');
    Route::resource('sucursales', SucursalesController::class);

    // Rutas de Servicios
    Route::get('servicios/buscar', [ServiciosController::class, 'buscar'])->name('servicios.buscar');
    Route::resource('servicios', ServiciosController::class);

    // Rutas de Contactos
    Route::get('contactos/buscar', [ContactosController::class, 'buscar'])->name('contactos.buscar');
    Route::resource('contactos', ContactosController::class);

    // Rutas de Modelos
    
    Route::get('modelos/asignar-repuestos/{id}', [ModeloController::class, 'asignarRepuestos'])->name('modelos.asignar_repuestos');
    Route::post('modelos/{id}/repuestos', [ModeloController::class, 'storeRepuestos'])->name('modelos.store_repuestos');
    Route::get('modelos/{id}/edit', [ModeloController::class, 'edit'])->name('modelos.editar');
    Route::get('modelos/buscar', [ModeloController::class, 'buscar'])->name('modelos.buscar');
    Route::get('modelos/{marca}/{sublinea}', [ModeloController::class, 'getModelos']);
    
    Route::resource('modelos', ModeloController::class);
    Route::get('nuevoModelo/crear', [ModeloController::class, 'nuevoModelo'])->name('modelos.nuevoModelo');
    Route::post('nuevoModelo/insertar', [ModeloController::class, 'newModelo'])->name('modelos.nuevo');
    


    // Rutas de Usuarios
    Route::get('usuarios/buscar', [UsuariosController::class, 'buscar'])->name('usuarios.buscar');
    Route::resource('usuarios', UsuariosController::class);

    // Rutas de parametros
    Route::get('parametros', [ParametrosController::class, 'index'])->name('parametros.index');
    Route::get('parametros/{id}', [ParametrosController::class, 'show'])->name('parametros.show');

    // Rutas de Dispositivos
    Route::get('dispositivos/buscar', [DispositivoController::class, 'buscar'])->name('dispositivos.buscar');
    Route::resource('dispositivos', DispositivoController::class);
    Route::get('dispositivos/getSucursales/{clienteId}', [DispositivoController::class, 'getSucursales'])->name('getSucursales');
    Route::get('nuevoDispositivo/crear', [DispositivoController::class, 'nuevoDispositivo'])->name('dispositivos.nuevoDispositivo');
    Route::post('nuevoDispositivo/insertar', [DispositivoController::class, 'newDispositivo'])->name('dispositivos.nuevo');

    // Rutas de categorias
    Route::get('categoria/trashed', [CategoriaController::class, 'trashed'])->name('categoria.trashed');
    Route::post('categoria/{id}/restore', [CategoriaController::class, 'restore'])->name('categoria.restore');
    Route::delete('categoria/{id}/force-delete', [CategoriaController::class, 'forceDelete'])->name('categoria.forceDelete');
    Route::resource('categoria', CategoriaController::class);

    // Subcategorías por categoría
    Route::get('subcategoriasx/{categoriaId}', [globalFuntions::class, 'getSubcategorias'])->name('subcategoriasx');
    Route::resource('subcategoria', SubcategoriaController::class);
    Route::get('/subcategoria/create/{categoria_id?}', [SubcategoriaController::class, 'create'])->name('subcategoria.crear');

    // Rutas de Líneas
    Route::get('lineasx/{subcategoriaId}', [globalFuntions::class, 'getLineas'])->name('lineasx');
    Route::resource('lineas', LineaController::class);
    Route::get('/lineas/create/{subcategoria_id?}', [LineaController::class, 'create'])->name('lineas.crear');
    
    // Rutas de Sublíneas
    Route::get('sublineasx/{lineaId}', [globalFuntions::class, 'getSublineas'])->name('sublineasx');
    Route::resource('sublineas', SublineaController::class);
    Route::get('/sublineas/create/{linea_id?}', [SublineaController::class, 'create'])->name('sublineas.crear');
    
    // Rutas de Roles
    Route::get('roles/buscar', [RolesController::class, 'buscar'])->name('roles.buscar');
    Route::resource('roles', RolesController::class);

    Route::get('detalle_orden_tecnico/{id}', [MisOrdenes::class, 'detallers'])->name('detalleOrdenTecnicoz');

    Route::get( 'home_tecnico', [MisOrdenes::class, 'weaxd'])->name('tecnicohome');

    Route::get('/mis-ordenes', [MisOrdenes::class, 'ordentecnico'])->name('misOrdenes');

    Route::get('buscar-ordenes', [MisOrdenes::class, 'buscarOrdenes'])->name('buscarOrdenes');

    Route::get('/view_editor_avance/{numero_ot}', [globalFuntions::class, 'redireccionoA_editorAvance'])->name('editor_avance');

    Route::get('detalle_orden_tecnico/{id}', [MisOrdenes::class, 'detallers'])->name('detalleOrdenTecnicoz');

    Route::post('/avances/{id}/actualizar', [AvancesController::class, 'actualizarComentarioAvance'])->name('avances.actualizar');

    Route::get('/ordenes/{numero_ot}/pdf', [MisOrdenes::class, 'generarPDF'])->name('ordenes.pdf');
    
    Route::get('/nuevo-ruta', [TecnicosController::class, 'nuevoMetodo'])->name('nuevo.metodo');
    
    Route::get('/redirec_agre_tarea-ruta', [TareaServiciosController::class, 'redirec_agre_tarea'])->name('redirec_agre_tarea.metodo');
    
    Route::get('/ver_avance_tarea-ruta/{id}', [TareaServiciosController::class, 'ver_avance_tarea'])->name('ver_avance_tarea.metodo');
    
    Route::get('/ver_avance_tecnicos-ruta/{id}', [TecnicosController::class, 'ver_avance_tecnicos'])->name('ver_avance_tecnicos.metodo');
    
    Route::get('/edit_tecnico-ruta/{id}', [TecnicosController::class, 'edit_tecnico'])->name('edit_tecnico.metodo');
    
    Route::put('/update_tecnico-ruta/{id}', [TecnicosController::class, 'update_tecnico'])->name('update_tecnico.metodo');
    
    Route::delete('/destroy_contacto-ruta/{id}', [ContactosController::class, 'destroy_contacto'])->name('destroy_contacto.metodo');
    
    Route::get('buscar_tarea', [TareaServiciosController::class, 'buscar_tarea'])->name('buscar_tarea.metodo');
    
    Route::get('actividad_extra/create/{numero_ot}', [MisOrdenes::class, 'crearActividadExtra'])->name('actividad_extra.create');

    Route::post('actividad_extra', [MisOrdenes::class, 'storeacti'])->name('actividad_extra.storeacti');
    
    Route::post('/crear-tipo-servicio', [globalFuntions::class, 'crearTipoServicio'])->name('wea.creartiposervicio');
    
    Route::get('/buscar-tecnico', [globalFuntions::class, 'buscarTecnicoxd'])->name('buscarcrono.tecnicoyservicio');

    Route::delete('/tecnicos/{id}', [TecnicoController::class, 'destroy'])->name('tecnicos.borrar');
    
    Route::post('/tecnicoyserviciowea/storetex', [TecnicosController::class, 'storetex'])->name('tecnicoyserviciowea.storetex');
    
    Route::post('/ordenes/{numero_ot}/iniciaravancesot', [AvancesController::class, 'iniciarOt'])->name('ordenes.iniciaravancesot');
    
    Route::post('/ordenes/{numero_ot}/estado_apendiente', [AvancesController::class, 'pendienteOt'])->name('ordenesxt.pendientex');
    
    // En routes/web.php
    Route::delete('/avancesctx/{id}/eliminarctx', [AvancesController::class, 'eliminarctx'])->name('avancectx.eliminarctx');

    Route::get('/ordenes/impresion/{id}/{estadoFirma}', [impresionController::class, 'imprimirOt'])->name('imprimirOt');
    //rutas marcas
    Route::prefix('marcas')->name('marcas.')->group(function () {
        Route::get('/', [MarcasControlador::class, 'index'])->name('index');
        Route::get('/crear', [MarcasControlador::class, 'create'])->name('create');
        Route::post('/', [MarcasControlador::class, 'store'])->name('store');
        Route::get('/{id}', [MarcasControlador::class, 'show'])->name('show');
        Route::get('/{id}/editar', [MarcasControlador::class, 'edit'])->name('edit');
        Route::put('/{id}', [MarcasControlador::class, 'update'])->name('update');
        Route::delete('/{id}', [MarcasControlador::class, 'destroy'])->name('destroy');
       
        // Rutas especiales para soft delete
        Route::get('/papelera', [MarcasControlador::class, 'trashed'])->name('trashed');
        Route::post('/{id}/restaurar', [MarcasControlador::class, 'restore'])->name('restore');
    });
    
    // Rutas de perfil
     Route::prefix('perfil')->group(function () {
     Route::get('/', [\App\Http\Controllers\Admin\UserController::class, 'perfil'])->name('perfil');
     Route::get('/edit', [\App\Http\Controllers\Admin\UserController::class, 'editPerfil'])->name('perfil.edit');
     Route::put('/update', [\App\Http\Controllers\Admin\UserController::class, 'updatePerfil'])->name('perfil.update');
     })->middleware('auth');
     
    Route::get('/signature/create/{user}', [UserController::class, 'createSignature'])->name('signature.create');
    Route::post('/signature/store/{user}', [UserController::class, 'storeSignature'])->name('signature.store');
    Route::get('/signature/edit/{user}', [UserController::class, 'editSignature'])->name('signature.edit');
    Route::put('/signature/update/{user}', [UserController::class, 'updateSignature'])->name('signature.update');
    
    
     Route::match(['PUT', 'POST'], '/avances/{avance}/actualizar', [AvancesController::class, 'actualizar']);
     
    Route::get('/sucursales/create', [SucursalesController::class, 'create'])->name('sucursales.create');

    Route::get('/contactos/agregar/{clienteId}/{sucursalId}', [ContactosController::class, 'agregar'])->name('contactos.agregar');
    
    Route::get('/ordenes/{numero_ot}/finalizar', [MisOrdenes::class, 'mostrarFinalizarOt'])->name('ordenes.mostrarFinalizarOt');
    
    Route::get('/servicios/{servicioId}/asignar-tareas', [ServiciosController::class, 'mostrarAsignarTareas'])->name('servicios.asignarTareas');
    Route::post('/servicios/asignar-tareas', [ServiciosController::class, 'asignarTareas'])->name('asignar.tareas');
    Route::get('/servicios/{servicioId}/tareas/{tareaId}/editar', [ServiciosController::class, 'editarTiempo'])->name('editar.tiempo');
    Route::post('/servicios/{servicioId}/tareas/{tareaId}/actualizar', [ServiciosController::class, 'actualizarTiempo'])->name('actualizar.tiempo');
    
    Route::delete('/servicios/{servicioId}/tareas/{tareaId}', [ServiciosController::class, 'eliminarTarea'])->name('eliminar.tarea');
    
    Route::get('/dispositivos/getModelosPorCategoria/{categoriaId}', [DispositivoController::class, 'getModelosPorCategoria']);
    Route::get('/dispositivos/getModelosPorSubcategoria/{subcategoriaId}', [DispositivoController::class, 'getModelosPorSubcategoria']);
    Route::get('/dispositivos/getModelosPorLinea/{lineaId}', [DispositivoController::class, 'getModelosPorLinea']);
    Route::get('/dispositivos/getModelosPorSublinea/{sublineaId}', [DispositivoController::class, 'getModelosPorSublinea']);
    
    Route::post('/avances/{numero_ot}/reanudar', [AvancesController::class, 'reanudarOt'])->name('reanudar_ot');

    // Ruta específica para la búsqueda de modelos (debe ir ANTES del resource)
    Route::get('/modelos/search', [ModeloController::class, 'search'])->name('modelos.search');

    // Rutas de recurso para modelos
    Route::resource('modelos', ModeloController::class);

    // Si tienes rutas adicionales como getModelos, también asegúrate de que existan:
    Route::get('/modelos/get-modelos/{marca}/{sublinea}', [ModeloController::class, 'getModelos'])->name('modelos.get_modelos');
    
    Route::get('/ordenes/{orden}/repuestos', [OrdenesController::class, 'verRepuestosOrden'])->name('ordenes.repuestosUtilizados');
    Route::post('/ordenes/asignarRepuesto', [OrdenesController::class, 'asignarRepuestoOrden'])->name('ordenes.asignarRepuestoOrden');
    Route::put('/orden/repuesto/editar/{id}', [OrdenesController::class, 'editarRepuestoOrden'])->name('editar.repuesto');
    Route::delete('/orden/repuesto/eliminar/{id}', [OrdenesController::class, 'eliminarRepuestoOrden'])->name('eliminar.repuesto');

    Route::put('/actividad_extra/{id}', [MisOrdenes::class, 'updateacti'])->name('actividades-extra.update');
    Route::delete('/actividad_extra/borrar/{id}', [MisOrdenes::class, 'destroyacti'])->name('actividades-extra.destroy');
    
    Route::get('/firma-cliente/{ot_id}', [impresionController::class, 'vistaFirmaCliente'])->name('vistaFirmaCliente');
    Route::post('/guardar-firma-cliente', [impresionController::class, 'guardarFirmaCliente'])->name('guardarFirmaCliente');

});
