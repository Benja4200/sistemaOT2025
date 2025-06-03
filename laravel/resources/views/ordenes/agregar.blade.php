@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/ordenes.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

    <main class="col py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            {{-- <h3 class="mb-4">Home / Ordenes / Agregar</h3>
            <li class="nav-item {{ Request::is('ordenes/agregar') ? 'active' : '' }}">
                <a class="nav-link pl-0" href="{{ route('ordenes.create') }}">
                    <i class="fas fa-plus-circle"></i> <span>Agregar Orden</span>
                </a>
            </li> --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h2>Crear Orden </h2>
                <a href="{{ route('ordenes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                    <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                </a>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    
                   
                    <div class="d-flex justify-content-center mb-4">
                        <div>
                            <a href="{{ route('clientes.nuevoCliente') }}" class="btn btn-success" style="background-color: #28a745; border-color: #28a745; text-align: center;" title="Agregar cliente nuevo">
                                <i class="fas fa-user-friends"></i> Agregar Cliente 
                            </a>
                            
                            <a href="{{ route('modelos.nuevoModelo') }}" class="btn btn-info" style="background-color: #007bff; border-color: #007bff; text-align: center;" title="Agregar modelo nuevo">
                                <i class="fas fa-desktop"></i> Agregar Modelo
                            </a>
                            
                            <a href="{{ route('dispositivos.nuevoDispositivo') }}" class="btn btn-warning" style="background-color: #ffc107; border-color: #ffc107; text-align: center;" title="Agregar dispositivo">
                                <i class="fas fa-laptop"></i> Agregar Dispositivo
                            </a>
                        </div>
                    
                        
                    </div>
                    <form action="" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            <span id="errorDescripcion" class="errMessage"></span>
                            
                        </div>
                        
                        <div class="mb-3">
                            
                            <label for="ejecutivo" class="form-label">Ejecutivo de ventas</label>
                            
                            <select class="form-select form-control select2" id="ejecutivo" name="ejecutivo" style="width: 100%;">
                                
                                <option value="0">Seleccione un ejecutivo</option>
                                @foreach ($ejecutivos as $ejecutivo)
                                    <option value="{{ $ejecutivo->id }}">{{ html_entity_decode($ejecutivo->nombre_usuario) }}
                                    </option>
                                @endforeach
                                
                            </select>
                            
                            <span id="errorEjecutivo" class="errMessage"></span>
                            
                        </div>
                        
                        
                        <div class="mb-3">
                            
                            <label for="cliente" class="form-label">Cliente</label>
                            
                            <select class="form-select form-control select2" id="cliente" name="cliente" style="width: 100%;">
                                
                                <option value="0">Seleccione un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ html_entity_decode($cliente->nombre_cliente) }}
                                    </option>
                                @endforeach
                                
                            </select>
                            
                            <span id="errorCliente" class="errMessage"></span>
                            
                        </div>
                        
                        <div class="mb-3">
                            <label for="sucursal" class="form-label">Sucursal</label>

                            <select class="form-select form-control select2" id="sucursal" name="sucursal" style="width: 100%;">
                                <option value="0">Seleccione una sucursal</option>
                            </select>
                            <span id="errorSucursal" class="errMessage"></span>
                        </div>

                        <div class="mb-3">
                            
                            <div id="bloqueContactos" class="card" style="display:none">
                                
                                <div class="form-group p-4">
                                    
                                    <label for="contacto" class="form-label">Contacto(s)
                                    
                                        <ul class="list-group" id="contacto">
                                        </ul>
                                        
                                        <div id="pagination" class="pagination p-2">
                                            <button id="prev" type="button" class="btn btn-secondary m-2"
                                                disabled>Anterior</button>
                                                
                                            <button id="next" type="button" class="m-2" btn btn-secondary" style="border-radius: 3px">Siguiente</button>
                                            <span id="page-info">Página 1 de <span id="total-pages">1</span></span>
                                        </div>
                                        
                                    </label>
                                    
                                    <span id="errorContacto" class="errMessage"></span>
                                    
                                </div>

                            </div>
                            
                        </div>
                        
                        <div class="mb-3">
                        
                            <label for="servicio" class="form-label">Servicio</label>
                            
                            <select class="form-select form-control select2" id="servicio" name="servicio" disabled style="width: 100%;">
                                <option value="0">Seleccione un servicio</option>
                                @foreach ($servicios as $servicio)
                                    <option value="{{ $servicio->id }}">{{ html_entity_decode($servicio->nombre_servicio) }}
                                    @if($servicio->sublinea)
                                        - {{ $servicio->sublinea->nombre_sublinea }}
                                    @endif
                                    </option>
                                @endforeach
                            </select>
                            
                            <span id="errorServicio" class="errMessage"></span>
                            
                        </div>
                        
                        <div class="mb-3" id ="bloqueEncargado" style="display:none">
                            
                            <label for="tecnicoEncargado" class="form-label">Técnico encargado</label>
                            
                            <select class="form-select form-control select2" id="tecnicoEncargado" name="tecnicoEncargado" style="width: 100%;">
                                <option value="0">Seleccione un técnico</option>
                            </select>
                            
                            <span id="errorTecnicoEncargado" class="errMessage"></span>
                        </div>

                        <div id="bloqueEquipoTecnico" class="card" style="display:none">
                            
                            <div class="form-group p-4">
                                
                                <label for="equipoTecnico" class="form-label">Equipo técnico
                                    <ul class="list-group" id="equipoTecnico">
                                    </ul>
                                </label>
                                
                                <div id="pagination" class="pagination p-2">
                                    <button id="prevTecnicos" type="button" class="btn btn-secondary m-2"
                                        disabled>Anterior</button>
                                    <button id="nextTecnicos" type="button" class="m-2" btn btn-secondary"
                                        style="border-radius: 3px">Siguiente</button>
                                    <span id="page-info-tecnicos">Página 1 de <span
                                        id="total-pages-tecnicos">1</span></span>
                                </div>
                                
                                <span id="errorEquipoTecnico" class="errMessage"></span>
                                
                            </div>
                        </div>
                        
                        <input type="text" name="tipoServicio" id="tipoServicio" value="" hidden>
                        {{-- <div class="mb-3">
                            <label for="dispositivo" class="form-label">Dispositivo(s)</label>
                            <select class="form-select form-control" id="dispositivo" name="dispositivo">
                                <option value="0">Seleccione un dispositivo</option>
                            </select>
                        </div> --}}
                        
                        {{-- Bloque para los dispositivos de la OT  --}}
                        <div style="padding:5px;">
                        
                            <div class="row" id="bloqueDispositivos" style="display:none;">
                                <div class="col-md-12 block-relieve m-2" id="bloque-0">
                                    <div class="block-content">
                                    {{--
                                        <div class="form-group col-12">
                                            <label for="subcategoria" class="form-label">Subcategoría</label>
                                            <select class="form-select form-control" id="subcategoria" name="subcategoria" style="width: 100%;">
                                                <option value="0">Seleccione una subcategoría</option>
                                                
                                            </select>
                                            <span id="errorSubcategoria" class="errMessage"></span>
                                        </div>
                                    --}}
                                        <div class="form-group col-12">
                                            <label for="dispositivo-0">Dispositivo:</label>
                                            <select id="dispositivo-0" class="form-control">
                                                <option value="0">Seleccione un dispositivo</option>
                                                <!-- opciones de dispositivo -->
                                            </select>
                                            <span id="errorDispositivo-0" class="errMessage"></span>
                                        </div>
                                        Tareas
                                        <div class="form-group col-12" style=" overflow: scroll; max-height:300px">

                                            <ul class="list-group" id="tareas-0">
                                            </ul>

                                            {{-- <select id="tareas" class="form-control" multiple>
                                            <option value="">Seleccione una tarea</option>
                                            <!-- opciones de tareas -->
                                        </select> --}}
                                        </div>
                                        <span id="errorTareas-0" class="errMessage"></span>
                                        <div
                                            class="d-flex
                                                justify-content-between col-11 mt-3">
                                            <div class="row col-12">
                                                <div class="col-md-6">
                                                    <div id="detallesDispositivo" style="display: none;">
                                                        <input type="text" class="detalleSiNo" id="detalleSiNo" hidden>
                                                        <div class="m-2">
                                                            <label for="rayones">¿El Equipo Posee Rayones?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="rayones" id="rayonesSi" value="Mostrar">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="rayones" id="rayonesNo" value="NoMostrar">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="rayones-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="detallesRayones" id="detallesRayones"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorRayones-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="rupturas">¿El Equipo Posee Rupturas?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="rupturas" id="rupturasSi" value="Mostrar">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="rupturas" id="rupturasNo"
                                                                        value="NoMostrar">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="rupturas-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="detallesRupturas" id="detallesRupturas"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display:none">
                                                            </div>
                                                            <span id="errorRupturas-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="tornillos">¿El Equipo Posee Todos Los Tornillos
                                                                De
                                                                Su
                                                                Carcasa?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="tornillos" id="tornillosSi"
                                                                        value="NoMostrar">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="tornillos" id="tornillosNo"
                                                                        value="Mostrar">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="tornillos-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="detallesTornillos" id="detallesTornillos"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display: none">
                                                            </div>
                                                            <span id="errorTornillos-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="gomas">¿El Equipo Posee Las Gomas De La Base
                                                                En
                                                                Buen
                                                                Estado?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="gomas" id="gomasSi" value="NoMostrar">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="gomas" id="gomasNo" value="Mostrar">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="gomas-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="detallesGomas" id="detallesGomas"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display: none">
                                                            </div>
                                                            <span id="errorGomas-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="estado">Estado del equipo</label>
                                                            <input type="text" class="form-control" id="estado"
                                                                name="estado">
                                                            <span id="errorEstadoDis-0" class="errMessage"></span>
                                                        </div>

                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="observacion">Observaciones adicionales
                                                                (opcional)</label>
                                                            <input type="text" class="form-control" id="observacion"
                                                                name="observacion">
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary" id="botonAgregarDetalle"
                                                        type="button" style="font-size:14px"
                                                        onclick="mostrarDetalles()">Agregar
                                                        detalles</button>
                                                    <input type="hidden" name="bloqueNumero" value="-1"
                                                        class="bloqueNumero">
                                                    <button class="btn btn-danger botonCancelarDetalle"
                                                        id="botonCancelarDetalle" type="button"
                                                        style="font-size:14px; display:none">Cancelar</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <div id="accesoriosDispositivo" style="display: none;">
                                                        <input type="text" value="0" id="accesorioSiNo" hidden>

                                                        <div class="m-2">
                                                            <label for="cargador">¿El Equipo Posee Cargador?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="cargador" id="cargadorSi"
                                                                        value="MostrarCB">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="cargador" id="cargadorNo"
                                                                        value="NoMostrarCB">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="cargador-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="accesoriosCargador" id="accesoriosCargador"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorCargador-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="cable">¿El Equipo Posee Cable de
                                                                Poder?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="cable" id="cableSi" value="MostrarCA">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="cable" id="cableNo"
                                                                        value="NoMostrarCA">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="cable-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="accesoriosCable" id="accesoriosCable"
                                                                    placeholder="Ingrese observaciones"
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorCablePoder-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="adaptador">¿El Equipo Posee Adaptador de
                                                                Poder?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="adaptador" id="adaptadorSi"
                                                                        value="MostrarCA">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="adaptador" id="adaptadorNo"
                                                                        value="NoMostrarCA">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="adaptador-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="accesoriosAdaptador" id="accesoriosAdaptador"
                                                                    placeholder="Ingrese observaciones"
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorAdaptador-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="bateria">¿El Equipo Posee Batería?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="bateria" id="bateriaSi" value="MostrarCB">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="bateria" id="bateriaNo"
                                                                        value="NoMostrarCB">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="bateria-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="accesoriosBateria" id="accesoriosBateria"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorBateria-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="pantalla">¿El Equipo Posee Pantalla En Mal
                                                                Estado?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="pantalla" id="pantallaSi"
                                                                        value="MostrarPT">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="pantalla" id="pantallaNo"
                                                                        value="NoMostrarPT">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="pantalla-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="accesoriosPantalla" id="accesoriosPantalla"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorPantalla-0" class="errMessage"></span>
                                                        </div>
                                                        <hr>
                                                        <div class="m-2">
                                                            <label for="teclado">¿El Equipo Posee Teclado en Mal
                                                                Estado?</label>
                                                            <div class="form-check">
                                                                <label>
                                                                    <input class="form-check-input" type="radio"
                                                                        name="teclado" id="tecladoSi" value="MostrarPT">
                                                                    Si</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label>

                                                                    <input class="form-check-input" type="radio"
                                                                        name="teclado" id="tecladoNo"
                                                                        value="NoMostrarPT">
                                                                    No</label>
                                                            </div>
                                                            <div class="form-check textoInf" id="teclado-Texto">
                                                                <input class="form-control" type="text"
                                                                    name="accesoriosTeclado" id="accesoriosTeclado"
                                                                    placeholder="El equipo presenta..."
                                                                    style="display:none;">
                                                            </div>
                                                            <span id="errorTeclado-0" class="errMessage"></span>
                                                        </div>

                                                        <div id="DrumToner">
                                                            <hr>
                                                            <div class="m-2">
                                                                <label for="drum">¿El Equipo Posee Drum?</label>
                                                                <div class="form-check">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="drum" id="drumSi"
                                                                            value="NoMostrarTD">
                                                                        Si</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <label>

                                                                        <input class="form-check-input" type="radio"
                                                                            name="drum" id="drumNo"
                                                                            value="MostrarTD">
                                                                        No</label>
                                                                </div>
                                                                <div class="form-check textoInf" id="drum-Texto">
                                                                    <input class="form-control" type="text"
                                                                        name="accesoriosDrum" id="accesoriosDrum"
                                                                        placeholder="El equipo presenta..."
                                                                        style="display:none;">
                                                                </div>
                                                                <span id="errorDrum-0" class="errMessage"></span>
                                                            </div>
                                                            <hr>
                                                            <div class="m-2">
                                                                <label for="toner">¿El Equipo Posee Toner?</label>
                                                                <div class="form-check">
                                                                    <label>
                                                                        <input class="form-check-input" type="radio"
                                                                            name="toner" id="tonerSi"
                                                                            value="NoMostrarTD">
                                                                        Si</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <label>

                                                                        <input class="form-check-input" type="radio"
                                                                            name="toner" id="tonerNo"
                                                                            value="MostrarTD">
                                                                        No</label>
                                                                </div>
                                                                <div class="form-check textoInf" id="toner-Texto">
                                                                    <input class="form-control" type="text"
                                                                        name="accesoriosToner" id="accesoriosToner"
                                                                        placeholder="El equipo presenta..."
                                                                        style="display:none;">
                                                                </div>
                                                                <span id="errorToner-0" class="errMessage"></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <button class="btn btn-primary" id="botonAgregarAccesorio"
                                                        style="font-size:14px" type="button"
                                                        onclick="mostrarAccesorios()">Agregar
                                                        accesorios
                                                    </button>
                                                    <button class="btn btn-danger" id="botonCancelarAccesorio"
                                                        type="button" style="font-size:14px; display:none"
                                                        onclick="cancelarAccesorios()">Cancelar
                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                        <button class="btn btn-primary btn-add boton-inferior-derecha"
                                            type="button">+</button>
                                    </div>
                                </div>
                            </div>
                            {{-- Bloque para tareas para la Ot --}}
                            <div id="bloqueTareas" class="card" style="display:none;">
                                <div class="form-group  p-4">
                                    <label for="tareasSinDispositivo">Tareas:</label>
                                    <ul class="list-group" id="tareasSinDispositivo">
                                    </ul>
                                    <div id="pagination" class="pagination p-2">
                                        <button id="prevTareaSinDispo" type="button" class="btn btn-secondary m-2"
                                            disabled>Anterior</button>
                                        <button id="nextTareaSinDispo" type="button" class="m-2" btn btn-secondary"
                                            style="border-radius: 3px">Siguiente</button>
                                        <span id="page-info-tareas-sin-dispo">Página 1 de <span
                                                id="total-pages-tareas-sin-dispo">1</span></span>
                                    </div>
                                    <span id="errorTareasSinDispositivo" class="errMessage"></span>
                                </div>

                            </div>
                            
                            {{-- <div class="mb-3">
                                <label for="tareas" class="form-label">Tareas</label>
                                <select class="form-select form-control" id="tareas" name="tareas">
                                    <option value="0">Seleccione una tarea</option>
                                </select>
                            </div> --}}
                        
                        </div>
                        {{--
                        <div class="mb-3">
                            <label for="estadoOt" class="form-label">Estado</label>
                            <select class="form-select form-control select2" id="estadoOt" name="estadoOt" style="width: 100%;">
                                <option value="0">Seleccione un estado</option>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}">
                                        {{ html_entity_decode($estado->descripcion_estado_ot) }}</option>
                                @endforeach
                            </select>
                            <span id="errorEstado" class="errMessage"></span>
                        </div>
                        --}}
                        <div class="mb-3">
                            <label for="prioridad" class="form-label">Prioridad</label>
                            <select class="form-select form-control select2" id="prioridad" name="prioridad" style="width: 100%;">
                                <option value="0">Seleccione una prioridad</option>
                                @foreach ($prioridades as $prioridad)
                                    <option value="{{ $prioridad->id }}">
                                        {{ html_entity_decode($prioridad->descripcion_prioridad_ot) }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="errorPrioridad" class="errMessage"></span>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de orden de trabajo</label>
                            <select class="form-select form-control select2" id="tipo" name="tipo" style="width: 100%;">
                                <option value="0">Seleccione un tipo de orden de trabajo</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->descripcion_tipo_ot }}</option>
                                @endforeach
                            </select>
                            <span id="errorTipo" class="errMessage"></span>
                        </div>
                        <div class="mb-3">
                            <label for="tipoVisita" class="form-label">Tipo de visita</label>
                            <select class="form-select form-control select2" id="tipoVisita" name="tipoVisita" style="width: 100%;">
                                <option value="0">Seleccione un tipo de visita</option>
                                @foreach ($tiposVisitas as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->descripcion_tipo_visita }}</option>
                                @endforeach
                            </select>
                            <span id="errorTipoVisita" class="errMessage"></span>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                            <span id="errorFecha" class="errMessage"></span>
                        </div>
                        <div class="mb-3">
                            <label for="cotizacion" class="form-label">Cotización</label>
                            <input type="text" class="form-control" id="cotizacion" name="cotizacion">
                            <span id="errorCotizacion" class="errMessage"></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="button" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;" onclick="validar()">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            <a href="{{ route('ordenes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas fa-times-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/ordenes/crearOrden36.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione un cliente",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados"; // Mensaje personalizado
                    },
                }
            });
        });
    </script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
@endsection
