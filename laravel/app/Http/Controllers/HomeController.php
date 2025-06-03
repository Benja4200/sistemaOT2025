<?php

namespace App\Http\Controllers;

use App\Models\Ot;
use App\Models\Cliente;
use App\Models\Marca;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    // mostrara la pagina de inicio
    public function index()
    {
        
        return view('home.home');
    }

    // obtener todos los registros de 'Ot' y devolverlos en formato JSON
    public function obtenerDatosOt()
    {
        // obtener todos los registros del modelo Ot
        $ots = Ot::with(['contacto', 'servicio', 'tecnicoEncargado', 'estado', 'tipoVisita', 'prioridad', 'tipo'])
            ->latest()
            ->paginate(10);  // usamos 'with' para incluir las relaciones y obtener los datos relacionados

        // devolvemos los datos como JSON
        return response()->json($ots); // enviamos una respuesta JSON
    }

    public function obtenerDatosPorAnio(Request $request)
    {
        // obtener el year desde la solicitud
        $year = $request->input('year');

        if ($year == null) {

            // si no se proporciona un year == null, usar el valor x default
            $year = $year ?? 2019;

        }

        $meses = [];
        $registrosPorMes = [];

        // obtener los registros para cada mes del año
        for ($month = 1; $month <= 12; $month++) {

            // Contar las órdenes para cada mes del año
            $registros = Ot::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // agregar el nombre del mes y el conteo de registros a los arrays
            $meses[] = Carbon::createFromFormat('m', $month)->format('F');  // nombre del mes
            $registrosPorMes[] = $registros;  // numero de registros en ese mes
        }

        // devolver los datos como respuesta JSON
        return response()->json([
            'meses' => $meses,
            'registrosPorMes' => $registrosPorMes
        ]);
    }

    public function obtenerDatosOrdenesPorEstado(Request $request)
    {
        // obtener los valores del año y mes desde la solicitud
        $year = $request->input('year', Carbon::now()->year);  // si no se proporciona, se usa el año actual
        $month = $request->input('month', Carbon::now()->month);  // si no se proporciona, se usa el mes actual

        // contamos las órdenes con estado 'pendiente' o 'finalizado'
        $ordenesPendientes = Ot::whereHas('estado', function ($query) {
            $query->where('descripcion_estado_ot', 'Pendiente');  // ajustar segun el nombre del campo en la tabla `EstadoOt`
        })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $ordenesFinalizadas = Ot::whereHas('estado', function ($query) {
            $query->where('descripcion_estado_ot', 'Finalizada');  // ajustar segun el nombre del campo en la tabla `EstadoOt`
        })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $ordenesIniciadas = Ot::whereHas('estado', function ($query) {
            $query->where('descripcion_estado_ot', 'Iniciada');  // ajustar segun el nombre del campo en la tabla `EstadoOt`
        })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // devolvemo los datos como JSON
        return response()->json([
            'ordenesPendientes' => $ordenesPendientes,
            'ordenesFinalizadas' => $ordenesFinalizadas,
            'ordenesIniciadas' => $ordenesIniciadas
        ]);
    }


    public function obtenerCantidaClientes()
    {

        $clientes = Cliente::orderBy('id', 'desc')->paginate(10);

        return response()->json(
            [
                'clientes' => $clientes->items(),
                'clientesCantidad' => $clientes->total(),
            ]
        );

    }

    public function obtenerOrdenesCantidadContador()
    {

        $ots = Ot::with(['contacto', 'servicio', 'tecnicoEncargado', 'estado', 'tipoVisita', 'prioridad', 'tipo'])
            ->paginate(50);

        return response()->json(['cantidadOrdenes' => $ots->total()]);

    }

    public function obtenerCantidadMarcas()
    {
        // obtenemos el total de marcas
        $cantidadMarcas = Marca::count();

        // devolvemos la respuesta en formato JSON
        return response()->json(['cantidadMarcas' => $cantidadMarcas]);
    }


}
