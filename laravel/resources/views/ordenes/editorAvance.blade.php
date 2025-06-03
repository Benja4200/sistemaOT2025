@extends('layouts.tailwind_master')

@section('content')

    <main id="main-content" class="flex-grow bg-gray-100 py-6" style="min-height: 100vh;">
        <div class="container px-[5px]">
            <div class="flex flex-col">
                
                <div class="flex justify-between">
                    <h2 class="text-2xl font-semibold">Avances de la OT: {{ $orden->numero_ot }}</h2>
                    
                    <a href="{{ route('misOrdenes') }}" class="bg-[#cc0066] text-white rounded p-1 hover:opacity-50 flex items-center">
                        <i class="fa-solid fa-circle-left"></i> Regresar
                    </a>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <div class="flex gap-1">
                        <button id="btnAgregarAvance" class="bg-[#cc0066] text-white rounded p-1 hover:opacity-50 cursor-pointer">
                            Agregar avance
                        </button>
                        
                        <button id="btnIniciarOtForm" class="bg-[#00b312] text-white rounded p-1 hover:opacity-50 cursor-pointer">
                            Iniciar OT
                        </button>
                        
                        <button id="btnPendienteOtForm" class="bg-[#ffff00] text-black rounded p-1 hover:opacity-50 cursor-pointer">
                            Poner Pendiente
                        </button>
                        
                        <button id="btnFinalizarOtForm" class="bg-[#db0000] text-white rounded p-1 hover:opacity-50 cursor-pointer">
                            Finalizar Ot
                        </button>
                    </div>
                </div>

                <!-- Fondo oscuro para modales -->
                <div id="fondoOscuro" class="hidden fixed inset-0 bg-black opacity-75 z-40"></div>
                
                <!-- Modal para agregar avance -->
                @if($orden->estado->descripcion_estado_ot !== 'Finalizada')
                <div id="fondoOscuroAgregarAvance" class="hidden fixed top-0 left-0 w-full h-full bg-black opacity-75 z-50"></div>
                
                <div id="formularioAgregarAvance" class="bg-white hidden w-[90%] rounded absolute top-[5%] left-1/2 transform -translate-x-1/2 z-50 md:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div class="flex gap-1 px-3 justify-between">
                        <p class="text-gray-400 text-xs my-2">Agregar avance</p>
                        
                        <button id="btnCerrarFormularioAgregarAvance" class="bg-[#c90300] hover:bg-[#ff2c29] my-2 rounded-full w-8 h-8 flex items-center justify-center" aria-label="Cerrar">
                            X
                        </button>
                    </div>
                    
                    <div class="bg-gray-200 px-4 py-2 text-xl font-semibold mx-2 rounded-t-sm">Agregar Avance</div>
                    <div class="px-4 py-6">
                        @if(session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Avance Agregado',
                                    text: "{{ session('success') }}",
                                    confirmButtonText: 'Aceptar'
                                });
                            </script>
                        @endif
                        
                        @if ($errors->any())
                            <script>
                                @foreach ($errors->all() as $error)
                                    Swal.fire({
                                        icon: 'error',
                                        title: '¡Error!',
                                        text: "{{ $error }}",
                                        confirmButtonText: 'Aceptar'
                                    });
                                @endforeach
                            </script>
                        @endif

                        <form action="{{ route('ordenes.avances.store', $orden->numero_ot) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="comentario_avance" class="block text-gray-700">Descripcion avance</label>
                                <input type="text" name="comentario_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                            </div>
                        
                            <div class="mb-4">
                                <label for="fecha_avance" class="block text-gray-700">Fecha</label>
                                <input type="datetime-local" name="fecha_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                            </div>
                        
                            <div class="mb-4">
                                <label for="tiempo_avance" class="block text-gray-700">Tiempo en mins</label>
                                <input type="number" name="tiempo_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                            </div>
                        
                            <div class="mb-4">
                                <label for="imagen_avance" class="block text-gray-700">Imagen del avance</label>
                                <input type="file" id="imagen_avance" name="imagen_avance" accept="image/*" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                            </div>
                        
                            <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none cursor-pointer">
                                <i class="fas fa-save"></i> Guardar Avance
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Modal para iniciar OT -->
                <div id="fondoOscuroIniciar" class="hidden fixed top-0 left-0 w-full h-full bg-black opacity-75 z-50"></div>
                
                <div id="formularioInicioOt" class="hidden w-full px-2 absolute top-[3%] left-1/2 transform -translate-x-1/2 z-50 md:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div class="flex gap-1 px-3 justify-between items-center">
                        <h1 class="text-gray-400 text-xs mb-1 mt-2">Iniciar Ot</h1>
                        
                        <button id="btnCerrarFormularioIniciar" class="bg-[#c90300] hover:bg-[#ff2c29] rounded-full w-8 h-8 mt-1 flex items-center justify-center" aria-label="Cerrar">
                            X
                        </button>
                    </div>
                    
                    <div class="mt-1 bg-white shadow rounded-lg">
                        <div class="bg-gray-200 px-4 py-2 text-xl font-semibold">Iniciar OT</div>
                            
                        <div class="px-4 py-6">
                            @if(session('fechaCalculada'))
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'OT Iniciada',
                                        text: "La OT ha sido iniciada exitosamente. Fecha calculada: {{ session('fechaCalculada') }}. Tiempo total de tareas: {{ session('tiempoTotalTareas') }} minutos.",
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif

                            @if(session('error'))
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: '¡Error!',
                                        text: '{{ session('error') }}',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif
                            
                            <form action="{{ route('ordenes.iniciaravancesot', $orden->numero_ot) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="comentario_avance" class="block text-gray-700">Descripcion avance Inicio</label>
                                    <input type="text" name="comentario_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                </div>
    
                                <div class="mb-4">
                                    <label for="fecha_avance" class="block text-gray-700">Fecha incio</label>
                                    <input type="datetime-local" name="fecha_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                </div>
    
                                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none cursor-pointer">
                                    <i class="fas fa-check-circle"></i> Inciar OT
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Modal para pendiente OT -->
                <div id="fondoOscuroPendiente" class="hidden fixed top-0 left-0 w-full h-full bg-black opacity-75 z-50"></div>
                
                <div id="formularioPendienteOt" class="hidden w-full px-2 absolute top-[10%] left-1/2 transform -translate-x-1/2 z-50 md:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div class="flex gap-1 px-3 justify-between items-center">
                        <h1 class="text-gray-400 text-xs mb-2 mt-2">Pendiente OT</h1>
                        <button id="btnCerrarFormularioPendiente" class="bg-[#c90300] hover:bg-[#ff2c29] mt-1 rounded-full w-8 h-8 flex items-center justify-center" aria-label="Cerrar">
                            X
                        </button>
                    </div>
                    
                    <div class="mt-1 bg-white shadow rounded-lg">
                        <div class="bg-gray-200 px-4 py-2 text-xl font-semibold">Marcar OT como Pendiente</div>
                            
                        <div class="px-4 py-6">
                            @if(session('success'))
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: '{{ session('success') }}',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif
                            
                            @if(session('error'))
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: '¡Error!',
                                        text: '{{ session('error') }}',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            @endif
    
                            <form action="{{ route('ordenesxt.pendientex', $orden->numero_ot) }}" method="POST">
                                @csrf
                    
                                <div class="mb-4">
                                    <label for="comentario_avance" class="block text-gray-700">Comentario</label>
                                    <input type="text" name="comentario_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                </div>
                    
                                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 focus:outline-none cursor-pointer">
                                    <i class="fas fa-exclamation-circle"></i> Marcar como Pendiente
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Modal para finalizar OT -->
                <div id="fondoOscuroFinalizar" class="hidden fixed top-0 left-0 w-full h-full bg-black opacity-75 z-50"></div>
                
                <div id="formularioFinalizarOt" class="hidden w-full px-2 absolute top-[7%] left-1/2 transform -translate-x-1/2 z-50 md:w-1/2 lg:w-1/3 xl:w-1/4">
                    <div class="flex gap-1 px-3 justify-between">
                        <h1 class="text-gray-400 text-xs mb-2 mt-2">Finalizar OT</h1>
                        
                        <button id="btnCerrarFormularioFinalizar" class="bg-[#c90300] hover:bg-[#ff2c29] mt-1 rounded-full w-8 h-8 flex items-center justify-center" aria-label="Cerrar">
                            X
                        </button>
                    </div>
                    
                    <div class="mt-1 bg-white shadow rounded-lg">
                        <div class="bg-gray-200 px-4 py-2 text-xl font-semibold">Finalizar OT</div>
                        <div class="px-4 py-6">
                            <form action="{{ route('ordenes.finalizar', $orden->numero_ot) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="comentario_avance" class="block text-gray-700">Descripcion avance Final</label>
                                    <input type="text" name="comentario_avance"
                                        class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                </div>

                                <div class="mb-4">
                                    <label for="fecha_avance" class="block text-gray-700">Fecha Final</label>
                                    <input type="datetime-local" name="fecha_avance"
                                        class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                </div>

                                <button type="submit"
                                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 focus:outline-none cursor-pointer">
                                    <i class="fas fa-check-circle"></i> Finalizar OT
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de avances -->
                <div class="mt-2 bg-white shadow rounded-lg">
                    <div class="bg-gray-200 px-4 py-2 text-xl font-semibold">Lista de Avances</div>
                    <div class="px-4 py-6">
                        <ul class="space-y-4">
                            @foreach($orden->avances as $avance)
                                <li class="flex flex-col p-4 bg-gray-50 rounded-lg shadow relative">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <strong class="text-gray-700">{{ $avance->fecha_avance }}</strong>
                                            <span class="text-gray-600 block"><strong>Descripcion avance: </strong>{{ $avance->comentario_avance }}</span>
                                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm inline-block mt-1">{{ $avance->tiempo_avance }} mins</span>
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            <!-- Botón para editar avance -->
                                            <button onclick="abrirModalEditar({{ $avance->id }})" class="text-yellow-600 hover:text-yellow-800">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            
                                            <!-- Botón para eliminar avance -->
                                            <button onclick="abrirModalEliminar({{ $avance->id }})" class="text-red-500 hover:text-red-700">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if($avance->imagen)
                                    <img src="{{ asset($avance->imagen) }}" alt="Imagen del avance" class="img-fluid mt-2 rounded-lg w-50 h-50 object-cover">

                                    @else
                                        <span class="text-gray-500 mt-1">No hay imagen disponible</span>
                                    @endif
                                    
                                    <!-- Modal de edición para este avance (oculto inicialmente) -->
                                    <div id="modal-editar-{{ $avance->id }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
                                        <div class="relative p-4 bg-white w-full max-w-2xl m-auto mt-20 rounded-lg">
                                            <div class="flex justify-between items-center border-b pb-2">
                                                <h3 class="text-lg font-semibold">Editar Avance</h3>
                                                <button onclick="cerrarModal('modal-editar-{{ $avance->id }}')" class="text-gray-500 hover:text-gray-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <form id="form-editar-{{ $avance->id }}" onsubmit="guardarEdicion(event, {{ $avance->id }})" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PUT">
                                                    
                                                    <div class="mb-4">
                                                        <label for="comentario_avance" class="block text-gray-700">Descripción del avance</label>
                                                        <textarea name="comentario_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>{{ $avance->comentario_avance }}</textarea>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label for="fecha_avance" class="block text-gray-700">Fecha</label>
                                                        <input type="datetime-local" name="fecha_avance" value="{{ date('Y-m-d\TH:i', strtotime($avance->fecha_avance)) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label for="tiempo_avance" class="block text-gray-700">Tiempo (mins)</label>
                                                        <input type="number" name="tiempo_avance" value="{{ $avance->tiempo_avance }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg" required>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label for="imagen_avance" class="block text-gray-700">Nueva imagen (opcional)</label>
                                                        <input type="file" name="imagen_avance" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                                                    </div>
                                                    
                                                    @if($avance->imagen)
                                                    <div class="mb-4">
                                                        <label class="block text-gray-700">Imagen actual</label>
                                                        <img src="{{ asset($avance->imagen) }}" alt="Imagen actual" class="mt-1 w-32 h-32 object-cover rounded-lg">
                                                    </div>
                                                    @endif
                                                    
                                                    <div class="flex justify-end gap-4 mt-4">
                                                        <button type="button" onclick="cerrarModal('modal-editar-{{ $avance->id }}')" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                            Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal de eliminación para este avance (oculto inicialmente) -->
                                    <div id="modal-eliminar-{{ $avance->id }}" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
                                        <div class="relative p-4 bg-white w-full max-w-md m-auto mt-20 rounded-lg">
                                            <div class="flex justify-between items-center border-b pb-2">
                                                <h3 class="text-lg font-semibold">Eliminar Avance</h3>
                                                <button onclick="cerrarModal('modal-eliminar-{{ $avance->id }}')" class="text-gray-500 hover:text-gray-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <p class="mb-4">¿Estás seguro de que deseas eliminar este avance?</p>
                                                
                                                <form id="form-eliminar-{{ $avance->id }}" onsubmit="confirmarEliminacion(event, {{ $avance->id }})">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    
                                                    <div class="flex justify-end gap-4">
                                                        <button type="button" onclick="cerrarModal('modal-eliminar-{{ $avance->id }}')" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @if($orden->avances->isNotEmpty())
                    <div class="mt-6 bg-white shadow rounded-lg">
                        <div class="bg-gray-200 px-4 py-2 text-xl font-semibold">Ultimo Avance para la Finalizacion</div>
                        <div class="px-4 py-6">
                            <p><strong>Descripcion Avance:</strong> {{ $orden->avances->last()->comentario_avance }}</p>
                            <p><strong>Fecha:</strong> {{ $orden->avances->last()->fecha_avance }}</p>
                            <p><strong>Tiempo:</strong> {{ $orden->avances->last()->tiempo_avance }} mins</p>
                        </div>
                    </div>
                @endif

                <script>
                    // Funciones para manejar los modales
                    function abrirModalEditar(avanceId) {
                        document.getElementById('fondoOscuro').classList.remove('hidden');
                        document.getElementById(`modal-editar-${avanceId}`).classList.remove('hidden');
                    }
                    
                    function abrirModalEliminar(avanceId) {
                        document.getElementById('fondoOscuro').classList.remove('hidden');
                        document.getElementById(`modal-eliminar-${avanceId}`).classList.remove('hidden');
                    }
                    
                    function cerrarModal(modalId) {
                        document.getElementById('fondoOscuro').classList.add('hidden');
                        document.getElementById(modalId).classList.add('hidden');
                    }
                    
                    // Función para guardar la edición
                    function guardarEdicion(event, avanceId) {
                        event.preventDefault();
                        
                        const form = document.getElementById(`form-editar-${avanceId}`);
                        const formData = new FormData(form);
                        
                        fetch(`/avances/${avanceId}/actualizar`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: data.mensaje,
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.mensaje || 'Ocurrió un error al actualizar el avance',
                                    confirmButtonText: 'Aceptar'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al enviar la solicitud',
                                confirmButtonText: 'Aceptar'
                            });
                            console.error('Error:', error);
                        });
                    }
                    
                    // Función para confirmar eliminación
                    function guardarEdicion(event, avanceId) {
                        event.preventDefault();
                        
                        const form = document.getElementById(`form-editar-${avanceId}`);
                        const formData = new FormData(form);
                        
                        // Mostrar loader o indicador de carga
                        const submitButton = form.querySelector('button[type="submit"]');
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                        submitButton.disabled = true;
                        
                        fetch(`/avances/${avanceId}/actualizar`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async response => {
                            const data = await response.json();
                            
                            if (!response.ok) {
                                // Si la respuesta no es OK, lanzar error con el mensaje del servidor
                                throw new Error(data.message || 'Error al actualizar el avance');
                            }
                            
                            return data;
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: data.mensaje,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Ocurrió un error al actualizar el avance',
                                confirmButtonText: 'Aceptar'
                            });
                        })
                        .finally(() => {
                            // Restaurar el botón a su estado original
                            submitButton.innerHTML = originalText;
                            submitButton.disabled = false;
                        });
                    }
                </script>
                
                <script>
                    // Script para el modal de agregar avance
                    const btnAgregarAvance = document.getElementById('btnAgregarAvance');
                    const formularioAgregarAvance = document.getElementById('formularioAgregarAvance');
                    const btnCerrarFormularioAgregarAvance = document.getElementById('btnCerrarFormularioAgregarAvance');
                    const fondoOscuroAgregarAvance = document.getElementById('fondoOscuroAgregarAvance');
                    
                    btnAgregarAvance.addEventListener('click', () => {
                        formularioAgregarAvance.classList.remove('hidden');
                        fondoOscuroAgregarAvance.classList.remove('hidden');
                    });
                    
                    btnCerrarFormularioAgregarAvance.addEventListener('click', () => {
                        formularioAgregarAvance.classList.add('hidden');
                        fondoOscuroAgregarAvance.classList.add('hidden');
                    });
                    
                    fondoOscuroAgregarAvance.addEventListener('click', () => {
                        formularioAgregarAvance.classList.add('hidden');
                        fondoOscuroAgregarAvance.classList.add('hidden');
                    });
                </script>
                
                <script>
                    // Script para el modal de iniciar OT
                    const btnIniciarOtForm = document.getElementById('btnIniciarOtForm');
                    const formularioInicioOt = document.getElementById('formularioInicioOt');
                    const btnCerrarFormularioIniciar = document.getElementById('btnCerrarFormularioIniciar');
                    const fondoOscuroIniciar = document.getElementById('fondoOscuroIniciar');
                    
                    btnIniciarOtForm.addEventListener('click', () => {
                        formularioInicioOt.classList.remove('hidden');
                        fondoOscuroIniciar.classList.remove('hidden');
                    });
                    
                    btnCerrarFormularioIniciar.addEventListener('click', () => {
                        formularioInicioOt.classList.add('hidden');
                        fondoOscuroIniciar.classList.add('hidden');
                    });
                    
                    fondoOscuroIniciar.addEventListener('click', () => {
                        formularioInicioOt.classList.add('hidden');
                        fondoOscuroIniciar.classList.add('hidden');
                    });
                </script>
                
                <script>
                    // Script para el modal de pendiente OT
                    const btnPendienteOtForm = document.getElementById('btnPendienteOtForm');
                    const formularioPendienteOt = document.getElementById('formularioPendienteOt');
                    const btnCerrarFormularioPendiente = document.getElementById('btnCerrarFormularioPendiente');
                    const fondoOscuroPendiente = document.getElementById('fondoOscuroPendiente');
                    
                    btnPendienteOtForm.addEventListener('click', () => {
                        formularioPendienteOt.classList.remove('hidden');
                        fondoOscuroPendiente.classList.remove('hidden');
                    });
                    
                    btnCerrarFormularioPendiente.addEventListener('click', () => {
                        formularioPendienteOt.classList.add('hidden');
                        fondoOscuroPendiente.classList.add('hidden');
                    });
                    
                    fondoOscuroPendiente.addEventListener('click', () => {
                        formularioPendienteOt.classList.add('hidden');
                        fondoOscuroPendiente.classList.add('hidden');
                    });
                </script>
                
                <script>
                    // Script para el modal de finalizar OT
                    const btnFinalizarOtForm = document.getElementById('btnFinalizarOtForm');
                    const formularioFinalizarOt = document.getElementById('formularioFinalizarOt');
                    const btnCerrarFormularioFinalizar = document.getElementById('btnCerrarFormularioFinalizar');
                    const fondoOscuroFinalizar = document.getElementById('fondoOscuroFinalizar');
                    
                    btnFinalizarOtForm.addEventListener('click', () => {
                        formularioFinalizarOt.classList.remove('hidden');
                        fondoOscuroFinalizar.classList.remove('hidden');
                    });
                    
                    btnCerrarFormularioFinalizar.addEventListener('click', () => {
                        formularioFinalizarOt.classList.add('hidden');
                        fondoOscuroFinalizar.classList.add('hidden');
                    });
                    
                    fondoOscuroFinalizar.addEventListener('click', () => {
                        formularioFinalizarOt.classList.add('hidden');
                        fondoOscuroFinalizar.classList.add('hidden');
                    });
                </script>

            </div>
        </div>
    </main>
@endsection