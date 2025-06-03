@extends('layouts.master')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Asignar Tareas al Servicio</h2>
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        Asignar tareas al servicio {{ $servicio->nombre_servicio }}
                    </div>
                    <div class="card-body">
                        
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('asignar.tareas') }}" method="POST">
                            @csrf
                    
                            <input type="hidden" name="servicio" value="{{ $servicio->id }}">
                    
                            <div class="mb-3">
                                <label for="tareas" class="form-label">Seleccionar Tareas</label>
                                <select class="form-select select2" id="tareas" name="tareas[]" multiple="multiple" style="width:100%" required>
                                    @foreach ($tareas as $tarea)
                                        <option value="{{ $tarea->id }}">{{ $tarea->nombre_tarea }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div id="tiempos-container"></div>
                            
                            
                            
                            
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Asignar Tareas
                                </button>

                                <!-- Botón Cancelar -->
                                <a href="{{ route('servicios.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                            </form>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    Tareas Asignadas
                                </div>
                                <div class="card-body table-responsive">
                                    @if ($servicio->tareasServicio->isEmpty())
                                    <p>No hay tareas asociadas a este servicio.</p>
                                    @else
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre Tarea</th>
                                                <th>Tiempo Tarea en minutos</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($servicio->tareasServicio as $tareaAsignada)
                                            <tr>
                                                <td>{{ $tareaAsignada->nombre_tarea  }}</td>
                                                <td>{{ $tareaAsignada->pivot->tiempo  }}</td>
                                                <td class="d-flex justify-content-center" style="gap: 4px;">
                                                    <div class="btn-group d-flex justify-content-center" style="gap: 3px;">
                                                         <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                             style="background-color: #cc6633; color: white; height: 38px; display: flex; align-items: center; justify-content: center;">
                                                             Acciones
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-right">
                                                             <!-- Editar -->
                                                             <a class="dropdown-item" href="{{ route('editar.tiempo', ['servicioId' => $servicio->id, 'tareaId' => $tareaAsignada->id]) }}">
                                                                 <i class="fas fa-edit" style="color: #cc6633;"></i> Editar
                                                             </a>
                 
                                                             <!-- Eliminar -->
                                                             <form action="{{ route('eliminar.tarea', ['servicioId' => $servicio->id, 'tareaId' => $tareaAsignada->id]) }}" method="POST" class="delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item ms-1" style="background-color: white; color: rgb(33, 37, 41); border: none; transition: background-color 0.3s; " title="Eliminar tarea" onmouseover="this.style.backgroundColor='rgb(248, 249, 250)';" onmouseout="this.style.backgroundColor='white';">
                                                                    <i class="fas fa-trash-alt text-danger"></i> Eliminar
                                                                </button>
                                                            </form>
                                                             
                                                              
                                                                 
                                                         </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>




@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione una o más tareas",
            allowClear: true,
            language: {
                noResults: function() {
                    return "No se encontraron resultados"; // Mensaje personalizado
                },
            }
        });

        var taskNames = {};
        var tiempos = {}; // Objeto para almacenar los tiempos ingresados

        @foreach ($tareas as $tarea)
            taskNames[{{ $tarea->id }}] = "{{ $tarea->nombre_tarea }}";
        @endforeach

        $('#tareas').on('change', function() {
            var selectedTasks = $(this).val();
            var container = $('#tiempos-container');
            container.empty(); // Limpiar el contenedor

            selectedTasks.forEach(function(taskId) {
                // Obtener el tiempo almacenado si existe
                var tiempo = tiempos[taskId] || ''; // Usar valor almacenado o vacío

                // Crear un nuevo campo de entrada para el tiempo usando el nombre de la tarea
                var inputField = `
                    <div class="mb-3">
                        <label for="tiempo_${taskId}" class="form-label">Tiempo para tarea ${taskNames[taskId]} en minutos</label>
                        <input type="number" class="form-control" id="tiempo_${taskId}" name="tiempos[${taskId}]" value="${tiempo}" required>
                    </div>
                `;
                container.append(inputField);
            });
        });

        // Manejar el cambio en los campos de tiempo
        $(document).on('input', 'input[type="number"]', function() {
            var taskId = $(this).attr('id').split('_')[1]; // Obtener el ID de la tarea
            tiempos[taskId] = $(this).val(); // Almacenar el valor ingresado
        });
    });
</script>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Evitar el envío del formulario

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'No, cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Enviar el formulario si se confirma
                }
            });
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
