@extends('layouts.master')
@section('content')

    <main class="" style="min-height: 100vh;">
        
        <div style="padding-left: 5px;">
            <p style="margin: 0;"><strong>Cronograma de Ordenes de Trabajo y Tareas Asignadas a Tecnicos</strong></p>
        </div>

        <div class="m-1 rounded" style="display: flex; gap: 3px; border: 1px solid #7700ff; background-color: #CC0066; padding: 5px;">
            
            <div style="display: flex; gap: 3px; padding: 3px;">

                <div class="rounded"
                    style="background-color: #333333; display: flex; flex-direction: row; align-items: center; justify-content: center; padding-right: 2px;">

                    <label for="desde esta fecha" class="pl-1 text-white" style="font-size: 14px;">Selecciona primera
                        fecha:</label>
                    <input type="date" id="fecha" name="fecha" style="border-radius: 5px;">

                </div>

                <div class="rounded"
                    style="background-color: #333333; display: flex; flex-direction: row; align-items: center; justify-content: center; padding-right: 2px;">
                    <label for="fecha hasta" class="pl-1 text-white" style="font-size: 14px;">Selecciona segunda
                        fecha:</label>
                    <input type="date" id="fecha" name="fecha" style="border-radius: 5px;">
                </div>

                <button id="cargarDatosx" style="padding-top: 0; padding-bottom: 0; padding-left: 3px; padding-right: 3px; background-color: #4e4d4d; border: 1px solid white;">obtener carga de trabajo</button>

            </div>

            <div style="display: flex; align-items: center; gap: 3px;">
                <input type="text" id="searchtecnico" name="buscar por tecnico" class="form-control"
                    placeholder="Buscar tecnico o servicio" value="{{ request()->input('searchtecnico') }}">
                <div id="buscar-btn" class="btn btn-primary" 
                    style="display: flex; gap: 3px; align-items: center; background-color: #cc6633; border-color: #cc6633;">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </div>
                
                <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="background-color: #cc6633; border-color: #cc6633; width: 210px;">
                    <i class="fa-sharp fa-solid fa-filter-circle-xmark"></i> Eliminar Filtro
                </a>
            </div>

        </div>
        
        <style>
        
            .containerxtoopic {
                position: relative;
            }
            
            .info-servicio {
                display: flex;
                flex-direction: column;
                position: absolute;
                background-color: gray;
                color: white;
                padding: 10px;
                border-radius: 5px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 10;
                width: max-content;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0s linear 0s;
            }

            .img-servicio:hover + .info-servicio {
                display: block;
                opacity: 1;
                animation: shake 1000ms cubic-bezier(0.36, 0.07, 0.19, 0.97) forwards;
            }
            
            @keyframes shake {
                0% {
                    transform: rotate(0deg);
                }
            
                50% {
                    transform: rotate(5deg);
                }
            
                60% {
                    transform: rotate(-5deg);
                }
            
                70% {
                    transform: rotate(5deg);
                }
            
                80% {
                    transform: rotate(-5deg);
                }
            
                100% {
                    transform: rotate(0deg);
                }
            }
            
            .info-servicio::after {
                content: '';
                position: absolute;
                bottom: 18px;
                left: -10px;
                border-left: 10px solid transparent;
                border-right: 10px solid transparent;
                border-top: 10px solid gray;
            }
            
            .tooltip-lens {
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
                z-index: 5;
                animation: shake 1000ms cubic-bezier(0.36, 0.07, 0.19, 0.97) forwards;
                animation-delay: 2000ms;
            }

        </style>

        <div id="tablaCronograma" style="padding-left: 5px; padding-right: 5px; overflow-y: scroll; height: 400px;"> 
            <table class="table">
                <thead style="background-color: #4e4d4d; color: white;">
                    <tr>
                        <th style="text-align: center;">Nombre Tecnico</th>
                        <th style="text-align: center;">Tipo Servicio</th>
                        <th style="text-align: center;">Ot Pendientes</th>
                        <th style="text-align: center;">Ot Iniciadas</th>
                        <th style="padding: ;">Tareas Count</th>
                        <th style="padding: ;">Tiempo tarea</th>
                        <th style="padding: ;">Fecha Inicio</th>
                        <th style="padding: ;">Fecha Termino</th>
                        <th style="text-align: center;">Cronograma</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- los datos se cargaran aqui mediante AJAX -->
                </tbody>
            </table>
        </div>

        <script>
            function cargarDatosIniciales() {
                $.ajax({
                    url: '/datosparacrono/sd',
                    method: 'GET',
                    success: function(response) {
                        var datos = response.datostecnicosyservicio;
                        var tableBody = $('#tableBody');
                        tableBody.empty();
                    
                        datos.forEach(function(item, index) {
                            
                            var fechaInicio = item.fecha_inicio;
                            var fechaTermino = item.fecha_termino;
                    
                            // Convertir las fechas a objetos Date
                            var fechaInicioObj = fechaInicio ? new Date(fechaInicio) : null;
                            var fechaTerminoObj = fechaTermino ? new Date(fechaTermino) : null;
                    
                            // Formatear las fechas solo con año, mes y día (YYYY-MM-DD)
                            var fechaInicioFormateada = fechaInicioObj && !isNaN(fechaInicioObj.getTime()) ? 
                                `${fechaInicioObj.getFullYear()}-${(fechaInicioObj.getMonth() + 1).toString().padStart(2, '0')}-${fechaInicioObj.getDate().toString().padStart(2, '0')}` : 'N/A';
                    
                            var fechaTerminoFormateada = fechaTerminoObj && !isNaN(fechaTerminoObj.getTime()) ? 
                                `${fechaTerminoObj.getFullYear()}-${(fechaTerminoObj.getMonth() + 1).toString().padStart(2, '0')}-${fechaTerminoObj.getDate().toString().padStart(2, '0')}` : 'N/A';
                    
                            var cantidadTareas = 0;
                            var totalTiempoTareas = 0;
                    
                            // Iterar sobre las ordenes y extraer las fechas de cada una
                            item.ordenes_asignadas_key_pendientes.forEach(function(orden) {
                                
                                orden.tareas.forEach(function(tarea) {
                                    if (tarea.cantidad_tareas !== undefined && tarea.total_tiempo_tareas !== undefined) {
                                        cantidadTareas += tarea.cantidad_tareas;
                                        totalTiempoTareas += tarea.total_tiempo_tareas;
                                    }
                                });
                    
                                // Obtener las fechas de cada orden asignada
                                var fechaInicioOrden = orden.fecha_inicio;
                                var fechaTerminoOrden = orden.fecha_termino;
                                
                                // Guardar las fechas en localStorage
                                if (fechaInicioOrden && fechaTerminoOrden) {
                                    localStorage.setItem(`fechas_tecnico_${item.nombre_tecnico}`, JSON.stringify({
                                        fechaInicioOrden: fechaInicioOrden,
                                        fechaTerminoOrden: fechaTerminoOrden
                                    }));
                                    
                                    // Llamar a la función generarGrafico después de agregar el row al DOM
                                    setTimeout(function() {
                                        generarGrafico(index + 1, fechaInicioOrden, fechaTerminoOrden);
                                    }, 0); // Usamos un timeout con 0 para esperar que el DOM se actualice primero
                                } else {
                                    console.error(`Fechas no válidas para la orden del técnico ${item.nombre_tecnico}`);
                                }
                            });
                    
                            // Recuperar las fechas del localStorage para este técnico
                            var fechas = JSON.parse(localStorage.getItem(`fechas_tecnico_${item.nombre_tecnico}`));
                            var fechaInicioOrdenDesdeStorage = fechas ? fechas.fechaInicioOrden : 'N/A';
                            var fechaTerminoOrdenDesdeStorage = fechas ? fechas.fechaTerminoOrden : 'N/A';
                            
                            // Formatear las fechas para mostrar solo año, mes y día (YYYY-MM-DD)
                            var fechaInicioFormateadaDesdeStorage = fechaInicioOrdenDesdeStorage && fechaInicioOrdenDesdeStorage !== 'N/A' ? 
                                `${new Date(fechaInicioOrdenDesdeStorage).getFullYear()}-${(new Date(fechaInicioOrdenDesdeStorage).getMonth() + 1).toString().padStart(2, '0')}-${new Date(fechaInicioOrdenDesdeStorage).getDate().toString().padStart(2, '0')}` : 'N/A';
                            
                            var fechaTerminoFormateadaDesdeStorage = fechaTerminoOrdenDesdeStorage && fechaTerminoOrdenDesdeStorage !== 'N/A' ? 
                                `${new Date(fechaTerminoOrdenDesdeStorage).getFullYear()}-${(new Date(fechaTerminoOrdenDesdeStorage).getMonth() + 1).toString().padStart(2, '0')}-${new Date(fechaTerminoOrdenDesdeStorage).getDate().toString().padStart(2, '0')}` : 'N/A';
                    
                            // Generar la fila con las fechas recuperadas del localStorage
                            var row = `<tr id="tecnico-${index + 1}">
                                <td style="padding: 5px; text-align: center;">${item.nombre_tecnico}</td>
                                <td style="padding: 5px; text-align: center;">
                                    <img style="cursor: pointer;" src="{{ asset('assets/image/soporte-tecnico.png') }}" alt="icono Sc Informatica" class="img-servicio">
                                </td>
                                <td style="padding: 5px; text-align: center;">${item.cantidad_ordenes_pendientes}</td>
                                <td style="padding: 5px; text-align: center;">${item.canti_ini}</td>
                                <td style="padding: 5px; text-align: center;">${cantidadTareas}</td>
                                <td style="padding: 5px; text-align: center;">${totalTiempoTareas} min</td>
                                <td style="padding: 5px; text-align: center;">${fechaInicioFormateadaDesdeStorage}</td>
                                <td style="padding: 5px; text-align: center;">${fechaTerminoFormateadaDesdeStorage}</td>
                                <td style="padding: 5px;">
                                    <canvas id="grafico-tecnico-${index + 1}" width="300" height="20px"></canvas>
                                </td>
                            </tr>`;
                    
                            tableBody.append(row);
                        });
                        
                        const imgsServicio = document.querySelectorAll('.img-servicio');

                        imgsServicio.forEach(function(img, index) {
                            const divServicio = document.createElement('div');
                            divServicio.classList.add('info-servicio');
                            divServicio.style.opacity = 0;
                            divServicio.style.visibility = 'hidden';
                            divServicio.style.display = 'flex';
                            divServicio.style.flexDirection = 'column';
                            divServicio.style.position = 'absolute';
    
                            datos[index].tipo_servicios.forEach(service => {
                                const p = document.createElement('p');
                                p.textContent = service;
                                divServicio.appendChild(p);
                            });
    
                            img.parentElement.appendChild(divServicio);
    
                            img.addEventListener('mouseover', function() {
                                var imgPosition = img.getBoundingClientRect();
                                divServicio.style.top = imgPosition.top + 'px';
                                divServicio.style.left = imgPosition.right + 10 + 'px';
                                divServicio.style.transition = 'opacity 0.3s, visibility 0s linear 0.3s';
                                divServicio.style.opacity = 1;
                                divServicio.style.visibility = 'visible';
                            });
    
                            img.addEventListener('mouseout', function() {
                                divServicio.style.transition = 'opacity 0.3s, visibility 0s linear 0s';
                                divServicio.style.opacity = 0;
                                divServicio.style.visibility = 'hidden';
                            });
                        });
                
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al cargar los datos iniciales:', error);
                    }
                });
            }

            function generarGrafico(tecnico, fechaInicioOt, fechaEstimadaTermino) {
                const canvasId = `grafico-tecnico-${tecnico}`;
                const ctx = document.getElementById(canvasId)?.getContext('2d');
                
                if (!ctx) {
                    console.error(`No se encontró el canvas con ID: ${canvasId}`);
                    return;
                }
            
                // Verificar si ya existe un gráfico en el canvas, y destruirlo si es así
                if (window[canvasId] instanceof Chart) {
                    window[canvasId].destroy();
                }
            
                // Asegurarse de que las fechas sean válidas
                if (!fechaInicioOt || !fechaEstimadaTermino) {
                    console.error(`Fechas no válidas para el gráfico del técnico ${tecnico}`);
                    return;
                }
            
                // Convertir las fechas en objetos Date
                let fechaInicio = new Date(fechaInicioOt);
                let fechaFinEstimada = new Date(fechaEstimadaTermino);
            
                // Verificar si las fechas son válidas
                if (isNaN(fechaInicio) || isNaN(fechaFinEstimada)) {
                    console.error(`Fechas no válidas para el gráfico del técnico ${tecnico}`);
                    return;
                }
            
                // Formatear las fechas solo con año, mes y día (YYYY-MM-DD)
                const label = `${fechaInicio.getFullYear()}-${(fechaInicio.getMonth() + 1).toString().padStart(2, '0')}-${fechaInicio.getDate().toString().padStart(2, '0')} - ${fechaFinEstimada.getFullYear()}-${(fechaFinEstimada.getMonth() + 1).toString().padStart(2, '0')}-${fechaFinEstimada.getDate().toString().padStart(2, '0')}`;
            
                // Datos para el gráfico (solo una barra con el rango de fechas)
                const data = [1];  // Solo una barra con un valor "1"
            
                // Generar el gráfico con el rango de fechas
                try {
                    window[canvasId] = new Chart(ctx, {
                        type: 'bar', // Tipo de gráfico
                        data: {
                            labels: [label], // La etiqueta es el rango de fechas
                            datasets: [{
                                label: 'Rango de Fechas',
                                data: data,  // Solo una barra
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',  // Color de la barra
                                borderColor: 'rgba(75, 192, 192, 1)',  // Color del borde de la barra
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: false,
                                        text: 'Rango de Fechas'
                                    },
                                    ticks: {
                                        display: false
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        display: false
                                    },
                                    title: {
                                        display: false
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                    console.log(`Gráfico generado exitosamente para el técnico ${tecnico}`);
                } catch (error) {
                    console.error(`Error al generar el gráfico para el técnico ${tecnico}: ${error}`);
                }
            }


        
            cargarDatosIniciales();
        </script>

        <div class="selector-container-zelgaria m-1 rounded"
            style="gap: 3px; border: 1px solid #7700ff; background-color: #CC0066;">

            <div class="border-danger rounded weadiv1" style=" background-color: #333333">
                <label for="year" class="pl-1 text-white" style="font-size: 14px;">Seleccionar Año:</label>
                <select id="year" class="mt-1 mr-1" style="border-radius: 5px;">
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                </select>
            </div>

            <div class="border-danger rounded weadiv2" style="background-color: #333333">
                <label for="month" class="pl-1 text-white" style="font-size: 14px;">Seleccionar Mes:</label>
                <select id="month" class="mt-2 mr-1" style="border-radius: 5px;">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>

            <button id="cargarDatos"><strong>Cargar Datos</strong></button>
        </div>

        <script src="{{ asset('assets/js/modulosHome/funcionparaajustareltamanodelcontenedor.js') }}"></script>

        <div class="d-flex"
            style="border: 1px solid #ffffff; gap: 10px; background-color: #313131; padding-top: 5px; padding-bottom: 5px; justify-content: center;">

            <!-- grafico: ordenes iniciadas, pendientes y finalizadas -->
            <div class="mb-1 d-flex flex-column align-items-center"
                style="background-color: #444444; border-radius: 5px; flex-basis: 45%; max-width: 600px; min-width: 250px;">
                <p class="m-0 p-0 text-center text-white"><strong>Ordenes Iniciadas, Pendientes y Finalizadas</strong></p>
                <canvas id="ordenesChart" class="border border-info shadow-sm pb-1 rounded"
                    style="width: 90%; height: 200px;"></canvas>

                <div class="d-flex flex-column mt-1 pt-1 pl-1 pr-1 pb-1 text-white rounded"
                    style="gap: 2px; background-color: #5e5e5e; width: 80%;">
                    <p class="border m-0 pl-1 rounded">Ordenes <span style="color: #ffff00;">Pendientes:</span> <span
                            id="pendientes-count"></span></p>
                    <p class="border m-0 pl-1 rounded">Ordenes <span style="color: #ff0000;">Finalizadas:</span> <span
                            id="finalizadas-count"></span></p>
                    <p class="border m-0 pl-1 rounded">Ordenes <span style="color: #00ff2a;">iniciadas:</span> <span
                            id="iniciadas-count"></span></p>
                </div>
            </div>

            <!-- grafico de ordenes x year-->
            <div class="mb-1 d-flex flex-column align-items-center px-1"
                style="background-color: #444444; border-radius: 5px; flex-basis: 45%; max-width: 600px; min-width: 250px;">
                <p class="text-center m-0 text-white"><strong>Ordenes por año</strong></p>
                <canvas id="weachart" class="border border-info shadow-sm rounded"
                    style="width: 100%; margin-bottom: 2px;"></canvas>
            </div>

        </div>

        <script src="{{ asset('assets/js/modulosHome/logicaGraficos/pielogic.js') }}"></script>

        <script src="{{ asset('assets/js/modulosHome/logicaGraficos/logicgraficobarra.js') }}"></script>

        <div class=""
            style="background-image: url('{{ asset('assets/image/texture.png') }}'); gap: 20px; padding-left: 5px; padding-top: 7px; padding-bottom: 7px; display: flex; justify-content: center;">

            <div class="d-flex flex-column border border-dark rounded" style="width: 200px; height: 100px;">

                <div class="border border-dark"
                    style="position: relative; top: 5px; left: 85px; border-radius: 100%; width: 40px; display: flex; justify-content: center; padding: 4px;">
                    <i class="fa fa-users"></i>
                </div>

                <div class=""
                    style="display: flex; flex-direction: column; position: relative; top: 7px; text-align: center; height: 48px;">
                    <div class="" style="">
                        <span>+</span><span class="counter">0</span>
                    </div>
                    <p><strong>Clientes</strong></p>
                </div>

            </div>

            <div class="d-flex flex-column border border-dark rounded" style="align-items: center; width: 200px;">

                <div class="border border-dark"
                    style="position: relative; top: 5px; border-radius: 100%; width: 40px; text-align: center;">
                    <i class="fa fa-clipboard"></i>
                </div>

                <div class="d-flex flex-column w-100 text-center" style="position: relative; height: 56px; top: 7px;">
                    <div class="" style="">
                        <span>+</span><span class="ordenescontador">0</span>
                    </div>
                    <p><strong>Ordenes</strong></p>
                </div>

            </div>

            <div class="d-flex flex-column border border-dark rounded" style="align-items: center; width: 200px;">

                <div class="border border-dark"
                    style="position: relative; top: 5px; border-radius: 100%; width: 40px; text-align: center;">
                    <i class="fa fa-tags"></i>
                </div>

                <div class="d-flex flex-column w-100 text-center" style="position: relative; height: 50px; top: 7px;">

                    <div style="padding-right: 15px;">
                        <span>+</span><span class="counterMarcas">0</span>
                    </div>

                    <p><strong>Marcas</strong></p>
                </div>

            </div>

        </div>

        <script src="{{ asset('assets/js/modulosHome/logicaContadores/logicacont1.js') }}"></script>

        <script src="{{ asset('assets/js/modulosHome/logicaContadores/logicacont2.js') }}"></script>

        <script src="{{ asset('assets/js/modulosHome/logicaContadores/logicacont3.js') }}"></script>

        <!-- tabla con ultimas ordenes -->
        <div style="display: flex; padding-top: 0px; padding-left: 10px; height: 25px;">
            <p><strong>Lista con ultimas ordenes</strong></p>
        </div>

        <div class="ots-table-wrapper">
            <table class="ots-table">
                <thead>
                    <tr>
                        <th>Numero OT</th>
                        <th>Descripción</th>
                        <th>Horas OT</th>
                        <th>Comentario</th>
                        <th>Contacto</th>
                        <th>Servicio</th>
                        <th>Tecnico Encargado</th>
                        <th>Estado</th>
                        <th>Tipo de Visita</th>
                        <th>Prioridad</th>
                        <th>Tipo de OT</th>
                        <th>Fecha Fin Planificada</th>
                        <th>Fecha de Creacion</th>
                    </tr>
                </thead>
                <tbody id="otsTableBody">
                    <!-- Los datos de las OTs serán insertados aquí con JavaScript -->
                </tbody>
            </table>
        </div>

        <script src="{{ asset('assets/js/modulosHome/logicComponenteTablaUltimasOts/tablaUtimaOrdenes.js') }}"></script>

        </div>
        <!-- Content End -->
@endsection