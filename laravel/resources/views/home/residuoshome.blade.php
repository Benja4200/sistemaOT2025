@extends('layouts.master')
@section('content')

<main class="">

    <div class="d-flex flex-column mseb-1 p-1">

        <h4 class="titulo">Lista de OTs</h4>

        <div class="tabla-container">
            <table class="ots-table">
                <thead style="position: sticky; top: 0; background-color: #4e4d4d; z-index: 10;">
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
    </div>

    <script>
        // Hacer la solicitud GET a la API cuando la página cargue
        $(document).ready(function () {
            fetchOts();
        });

        // Función para obtener los datos de la API
        function fetchOts() {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/ots',
                method: 'GET',
                success: function (response) {
                    // Limpiar la tabla antes de insertar los nuevos datos
                    $('#otsTableBody').empty();

                    // Iterar sobre los datos y agregar filas a la tabla
                    response.data.forEach(function (ot) {
                        const row = `
                            <tr style="">
                                <td style="padding: 8px 16px;">${ot.numero_ot}</td>
                                <td style="padding: 8px 16px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;">${ot.descripcion_ot}</td>
                                <td style="padding: 8px 16px;">${ot.horas_ot}</td>
                                <td style="padding: 8px 16px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;">${ot.comentario_ot}</td>
                                <td style="padding: 8px 16px;">${ot.contacto ? ot.contacto.nombre_contacto : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.servicio ? ot.servicio.nombre_servicio : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.tecnico_encargado ? ot.tecnico_encargado.nombre_tecnico : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.estado ? ot.estado.descripcion_estado_ot : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.tipo_visita ? ot.tipo_visita.descripcion_tipo_visita : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.prioridad ? ot.prioridad.descripcion_prioridad_ot : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.tipo ? ot.tipo.descripcion_tipo_ot : 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.fecha_fin_planificada_ot || 'N/A'}</td>
                                <td style="padding: 8px 16px;">${ot.created_at || 'N/A'}</td>
                            </tr>
                        `;
                        $('#otsTableBody').append(row); // Agregar la fila a la tabla
                    });

                },
                error: function (xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        }
    </script>

    <div class="d-flex m-1 p-1 rounded"
        style="gap: 3px; border: 1px solid #7700ff; background-color: #CC0066; width: 41%;">

        <div class="border border-danger pt-1 rounded" style="background-color: #333333">
            <label for="year" class="pl-1 text-white">Seleccionar Año:</label>
            <select id="year" class="mt-1 mr-1">
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
                <option value="2019">2019</option>
                <option value="2018">2018</option>
            </select>
        </div>

        <div class="border border-danger rounded" style="background-color: #333333">
            <label for="month" class="pl-1 text-white">Seleccionar Mes:</label>
            <select id="month" class="mt-2 mr-1">
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

    <div class="d-flex" style="border: 1px solid #ffffff; gap: 4px; background-color: #313131; padding-top: 5px; padding-bottom: 5px; padding-left: 5px;">

        <!-- primer grafico: cantidad de ordenes por mes -->
        <div class="mb-1 d-flex flex-column align-items-center px-1"
            style="background-color: #444444; border-radius: 5px; flex-basis: 33%; max-width: 33%; min-width: 250px;">
            <p class="text-center m-0 text-white"><strong>Registros de ordenes por Mes</strong></p>
            <canvas id="myChart" class="border border-info shadow-sm rounded"
                style="width: 100%; margin-bottom: 2px;"></canvas>
        </div>

        <!-- segundo grafico: ordenes iniciadas, pendientes y finalizadas -->
        <div class="mb-1 d-flex flex-column align-items-center"
            style="background-color: #444444; border-radius: 5px; flex-basis: 33%; max-width: 33%; min-width: 250px;">
            <p class="m-0 p-0 text-center text-white"><strong>Ordenes Iniciadas, Pendientes y Finalizadas</strong></p>
            <canvas id="ordenesChart" class="border border-info shadow-sm pb-1"
                style="width: 90%; height: 115px;"></canvas>

            <div class="d-flex flex-column mt-2 p-2 text-white rounded"
                style="gap: 2px; background-color: #5e5e5e; width: 80%;">
                <p class="border m-0 pl-1 rounded">Ordenes Pendientes: <span id="pendientes-count"></span></p>
                <p class="border m-0 pl-1 rounded">Ordenes Finalizadas: <span id="finalizadas-count"></span></p>
                <p class="border m-0 pl-1 rounded">Ordenes iniciadas: <span id="iniciadas-count"></span></p>
            </div>
        </div>

    </div>

    <div class="d-flex" style="background-image: url('{{ asset('assets/image/texture.png') }}'); justify-content: center; gap: 5px; padding: 5px;">

        <div class="d-flex flex-column" style="align-items: center; padding-top: 3px; width: 200px;">
            <div class="text-center border border-dark" style="border-radius: 100%; width: 40px;">
                <i class="fa fa-users"></i>
            </div>
            <h4 class="text-center">
                <div class="" style="padding-right: 15px;">
                    <span>+</span><span class="counter">0</span>
                </div>
                <p>Clientes</p>
            </h4>
        </div>

        <div class="d-flex flex-column" style="align-items: center; padding-top: 3px; width: 120px;">
            <div class="text-center border border-dark" style="border-radius: 100%; width: 40px;">
                <i class="fa fa-clipboard"></i>
            </div>

            <h4 class="d-flex flex-column">
                <div class="" style="padding-right: 10px;">
                    <span>+</span><span class="ordenescontador">0</span>
                </div>
                <p>Ordenes</p>
            </h4>
        </div>

        <div class="d-flex flex-column" style="align-items: center; padding-top: 3px; width: 120px;">
            <div class="text-center border border-dark" style="border-radius: 100%; width: 40px;">
                <i class="fa fa-tags"></i>
            </div>
            <h4 class="text-center d-flex flex-column">

                <div style="padding-right: 15px;">
                    <span>+</span><span class="counterMarcas">0</span>
                </div>

                <p>Marcas
            </h4>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Hacer el llamado Ajax para obtener la cantidad de marcas
            $.ajax({
                url: "/marcas/cantidad", // URL del controlador que devuelve la cantidad de marcas
                method: "GET", // Método de solicitud
                success: function (response) {
                    // Asumimos que 'response' contiene la cantidad de marcas
                    var cantidadMarcas = response.cantidadMarcas; // Asegúrate de que la respuesta tenga esta propiedad

                    // Función para animar el contador
                    animateCounterv3(cantidadMarcas, ".counterMarcas"); // Usamos la clase .counterMarcas
                },
                error: function (xhr, status, error) {
                    console.error("Hubo un error al obtener la cantidad de marcas: ", error);
                }
            });
        });

        // Función para animar el contador
        function animateCounterv3(targetValue, counterClass) {
            var currentValue = 0; // El valor inicial
            var increment = targetValue / 100; // Incremento por cada paso (ajustable)
            var duration = 1000; // Duración total de la animación en milisegundos (1 segundo)

            var interval = setInterval(function () {
                currentValue += increment;
                if (currentValue >= targetValue) {
                    currentValue = targetValue; // Asegurarse de que no sobrepase el valor
                    clearInterval(interval); // Detener la animación cuando llegue al valor final
                }
                $(counterClass).text(Math.round(currentValue)); // Actualizar el contador con la clase específica
            }, duration / 100); // Dividimos el tiempo entre 100 para hacer la animación más fluida
        }

    </script>

    <script>
        $(document).ready(function () {
            // Hacer el llamado Ajax para obtener la cantidad de clientes
            $.ajax({
                url: "/clientescantidad", // URL de tu controlador
                method: "GET", // Método de solicitud
                success: function (response) {
                    // Asumimos que 'response' contiene la cantidad de clientes
                    var cantidadClientes = response.clientesCantidad; // Asegúrate de que la respuesta tenga esta propiedad

                    // Función para animar el contador
                    animateCounterx(cantidadClientes);
                },
                error: function (xhr, status, error) {
                    console.error("Hubo un error al obtener la cantidad de clientes: ", error);
                }
            });
        });

        function animateCounterx(targetValue) {
            var currentValue = 0; // El valor inicial
            var increment = targetValue / 100; // Incremento por cada paso (ajustable)
            var duration = 1000; // Duración total de la animación en milisegundos (1 segundo)

            var interval = setInterval(function () {
                currentValue += increment;
                if (currentValue >= targetValue) {
                    currentValue = targetValue; // Asegurarse de que no sobrepase el valor
                    clearInterval(interval); // Detener la animación cuando llegue al valor final
                }
                $(".counter").text(Math.round(currentValue)); // Actualizar el contador
            }, duration / 100); // Dividimos el tiempo entre 100 para hacer la animación más fluida
        }

    </script>

    <script>
        $(document).ready(function () {
            // Hacer el llamado Ajax para obtener la cantidad de órdenes
            $.ajax({
                url: "/obtenerOrdenesCantidadContador", // URL de tu controlador para obtener las órdenes
                method: "GET", // Método de solicitud
                success: function (response) {
                    // Asumimos que 'response' contiene la cantidad de órdenes
                    var cantidadOrdenes = response.cantidadOrdenes; // Asegúrate de que la respuesta tenga esta propiedad

                    // Función para animar el contador
                    animateCounter(cantidadOrdenes, ".ordenescontador"); // Usamos una clase diferente para el contador de órdenes
                },
                error: function (xhr, status, error) {
                    console.error("Hubo un error al obtener la cantidad de órdenes: ", error);
                }
            });
        });

        // Función para animar el contador
        function animateCounter(targetValue, counterClass) {
            var currentValue = 0; // El valor inicial
            var increment = targetValue / 100; // Incremento por cada paso (ajustable)
            var duration = 1000; // Duración total de la animación en milisegundos (1 segundo)

            var interval = setInterval(function () {
                currentValue += increment;
                if (currentValue >= targetValue) {
                    currentValue = targetValue; // Asegurarse de que no sobrepase el valor
                    clearInterval(interval); // Detener la animación cuando llegue al valor final
                }
                $(counterClass).text(Math.round(currentValue)); // Actualizar el contador con la clase específica
            }, duration / 100); // Dividimos el tiempo entre 100 para hacer la animación más fluida
        }

    </script>

    <script>
        $(document).ready(function () {
            // Variable global para almacenar el grafico
            var myChartInstance = null;

            $('#cargarDatos').click(function () {
                var year = $('#year').val();  // Obtener el año seleccionado
                var month = $('#month').val();  // Obtener el mes seleccionado

                $.ajax({
                    url: '/obtener-datos-por-mes',  // URL de la API
                    method: 'GET',
                    data: { year: year, month: month },  // Enviar año y mes seleccionados
                    success: function (response) {

                        //console.log(response);

                        // Datos recibidos desde el API
                        var meses = response.meses;  // ["October"]
                        var registrosPorMes = response.registrosPorMes;  // [138]

                        // Obtener el contexto del canvas para el grafico
                        var ctx = document.getElementById('myChart').getContext('2d');

                        // Si ya existe un grafico, destruirlo
                        if (myChartInstance) {
                            myChartInstance.destroy();  // Destruir el grafico anterior
                        }

                        // Crear el grafico de barras
                        myChartInstance = new Chart(ctx, {
                            type: 'bar',  // Tipo de grafico
                            data: {
                                labels: meses,  // Nombre del mes recibido de la API
                                datasets: [{
                                    label: 'Registros por Mes',
                                    data: registrosPorMes,  // Datos de registros por mes (138)
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',  // Color de las barras
                                    borderColor: 'rgba(75, 192, 192, 1)',  // Color del borde de las barras
                                    borderWidth: 1  // Ancho del borde de las barras
                                }]
                            },
                            options: {
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'white'
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: 'white'
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: 'white'
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al cargar los datos:", error);
                    }
                });
            });
        });


    </script>

    <script>
        // Variable global para almacenar el gráfico
        var ordenesChartInstance = null;

        $(document).ready(function () {
            // Función para destruir el gráfico si ya existe
            function destroyChart(chart) {
                if (chart) {
                    chart.destroy();  // Destruir el gráfico existente
                }
            }

            // Cuando el usuario haga clic en el botón para cargar los datos
            $('#cargarDatos').click(function () {
                var year = $('#year').val();  // Obtener el año seleccionado
                var month = $('#month').val();  // Obtener el mes seleccionado

                // Hacer la solicitud AJAX para obtener los datos según el año y mes seleccionados
                $.ajax({
                    url: '/obtener-datos-ordenes',  // Ruta que definimos en el backend
                    method: 'GET',
                    data: {
                        year: year,  // Enviar el año seleccionado
                        month: month  // Enviar el mes seleccionado
                    },
                    success: function (data) {
                        // Actualizar los contadores en la página
                        $('#pendientes-count').text(data.ordenesPendientes);
                        $('#finalizadas-count').text(data.ordenesFinalizadas);
                        $('#iniciadas-count').text(data.ordenesIniciadas);

                        // Destruir el gráfico anterior (si existe)
                        destroyChart(ordenesChartInstance);

                        // Crear el gráfico nuevo
                        var ctx = document.getElementById('ordenesChart').getContext('2d');
                        ordenesChartInstance = new Chart(ctx, {
                            type: 'pie', // Tipo de gráfico (pastel)
                            data: {
                                labels: ['Pendientes', 'Finalizadas', 'Iniciadas'], // Etiquetas
                                datasets: [{
                                    label: 'Cantidad de Órdenes',
                                    data: [data.ordenesPendientes, data.ordenesFinalizadas, data.ordenesIniciadas], // Datos de las órdenes
                                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(3, 255, 0, 0.8)'], // Colores de las secciones
                                    borderColor: ['rgba(174, 0, 132, 0.8)', 'rgba(54, 162, 235, 1)', 'rgba(35, 255, 0, 0.8)'], // Colores del borde
                                    borderWidth: 1 // Ancho del borde
                                }]
                            },
                            options: {
                                responsive: false,  // Asegura que el gráfico sea adaptable al tamaño de la pantalla
                                plugins: {
                                    legend: {
                                        position: 'top',  // Ubicación de la leyenda
                                        labels: {
                                            color: 'white'
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (tooltipItem) {
                                                // Formateo de las etiquetas en el tooltip
                                                return tooltipItem.label + ': ' + tooltipItem.raw + ' órdenes';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al obtener los datos:', error);
                    }
                });
            });
        });


    </script>

    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today Sale</p>
                        <h6 class="mb-0">$1234</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Sale</p>
                        <h6 class="mb-0">$1234</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today Revenue</p>
                        <h6 class="mb-0">$1234</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Revenue</p>
                        <h6 class="mb-0">$1234</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sale & Revenue End -->


    <!-- Sales Chart Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Worldwide Sales</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="worldwide-sales"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Salse & Revenue</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="salse-revenue"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Sales Chart End -->


    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">ordenes de trabajo creadas</h6>
                <a href="">Show All</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col"><input class="form-check-input" type="checkbox"></th>
                            <th scope="col">Date</th>
                            <th scope="col">Invoice</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>01 Jan 2045</td>
                            <td>INV-0123</td>
                            <td>Jhon Doe</td>
                            <td>$123</td>
                            <td>Paid</td>
                            <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                        </tr>
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>01 Jan 2045</td>
                            <td>INV-0123</td>
                            <td>Jhon Doe</td>
                            <td>$123</td>
                            <td>Paid</td>
                            <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                        </tr>
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>01 Jan 2045</td>
                            <td>INV-0123</td>
                            <td>Jhon Doe</td>
                            <td>$123</td>
                            <td>Paid</td>
                            <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                        </tr>
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>01 Jan 2045</td>
                            <td>INV-0123</td>
                            <td>Jhon Doe</td>
                            <td>$123</td>
                            <td>Paid</td>
                            <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                        </tr>
                        <tr>
                            <td><input class="form-check-input" type="checkbox"></td>
                            <td>01 Jan 2045</td>
                            <td>INV-0123</td>
                            <td>Jhon Doe</td>
                            <td>$123</td>
                            <td>Paid</td>
                            <td><a class="btn btn-sm btn-primary" href="">Detail</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->


    <!-- Widgets Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-md-6 col-xl-4">
                <div class="h-100 bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">Messages</h6>
                        <a href="">Show All</a>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt=""
                            style="width: 40px; height: 40px;">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-0">Jhon Doe</h6>
                                <small>15 minutes ago</small>
                            </div>
                            <span>Short message goes here...</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt=""
                            style="width: 40px; height: 40px;">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-0">Jhon Doe</h6>
                                <small>15 minutes ago</small>
                            </div>
                            <span>Short message goes here...</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt=""
                            style="width: 40px; height: 40px;">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-0">Jhon Doe</h6>
                                <small>15 minutes ago</small>
                            </div>
                            <span>Short message goes here...</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center pt-3">
                        <img class="rounded-circle flex-shrink-0" src="img/user.jpg" alt=""
                            style="width: 40px; height: 40px;">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-0">Jhon Doe</h6>
                                <small>15 minutes ago</small>
                            </div>
                            <span>Short message goes here...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-xl-4">
                <div class="h-100 bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Calender</h6>
                        <a href="">Show All</a>
                    </div>
                    <div id="calender"></div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-xl-4">
                <div class="h-100 bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">To Do List</h6>
                        <a href="">Show All</a>
                    </div>
                    <div class="d-flex mb-2">
                        <input class="form-control bg-transparent" type="text" placeholder="Enter task">
                        <button type="button" class="btn btn-primary ms-2">Add</button>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-2">
                        <input class="form-check-input m-0" type="checkbox">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <span>Short task goes here...</span>
                                <button class="btn btn-sm"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-2">
                        <input class="form-check-input m-0" type="checkbox">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <span>Short task goes here...</span>
                                <button class="btn btn-sm"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-2">
                        <input class="form-check-input m-0" type="checkbox" checked>
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <span><del>Short task goes here...</del></span>
                                <button class="btn btn-sm text-primary"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center border-bottom py-2">
                        <input class="form-check-input m-0" type="checkbox">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <span>Short task goes here...</span>
                                <button class="btn btn-sm"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center pt-2">
                        <input class="form-check-input m-0" type="checkbox">
                        <div class="w-100 ms-3">
                            <div class="d-flex w-100 align-items-center justify-content-between">
                                <span>Short task goes here...</span>
                                <button class="btn btn-sm"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Widgets End -->


    <!-- Footer Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded-top p-4">
            <div class="row">
                <div class="col-12 col-sm-6 text-center text-sm-start">
                    &copy; <a href="#">Your Site Name</a>, All Right Reserved.
                </div>
                <div class="col-12 col-sm-6 text-center text-sm-end">
                    <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                    Designed By <a href="https://htmlcodex.com">HTML Codex</a>
                    </br>
                    Distributed By <a class="border-bottom" href="https://themewagon.com" target="_blank">ThemeWagon</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    </div>
    <!-- Content End -->
    @endsection
