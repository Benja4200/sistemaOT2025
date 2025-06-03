@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                
                {{-- <a href="{{ route('ordenes.index') }}" class="btn btn-primary">Volver</a> --}}
            
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Avances de la OT: {{ $orden->numero_ot }}</h2>
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                        <i class="fas Example of arrow-left fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <!-- Mostrar lista de avances -->
                <div class="card mt-4">
                    <div class="card-header">Lista de Avances</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($orden->avances as $avance)
                            <li class="list-group-item">
                                <strong>{{ $avance->fecha_avance }}:</strong>
                                {{ $avance->comentario_avance }}
                                <span class="badge bg-info">{{ $avance->tiempo_avance }} horas</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Verifica si la OT está finalizada -->
                @if($orden->estado->descripcion_estado_ot !== 'Finalizada')
                <!-- Formulario para agregar nuevo avance -->
                <div class="card mt-4">
                    <div class="card-header">Agregar Avance</div>
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

                        <form action="{{ route('ordenes.avances.store', $orden->numero_ot) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="comentario_avance">Comentario</label>
                                <input type="text" name="comentario_avance" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="fecha_avance">Fecha</label>
                                <input type="date" name="fecha_avance" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="tiempo_avance">Tiempo en Horas</label>
                                <input type="number" name="tiempo_avance" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                <i class="fas fa-save"></i> Guardar Avance
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Formulario para finalizar la OT -->
                <div class="card mt-4">
                    <div class="card-header">Finalizar OT</div>
                    <div class="card-body">
                        <form action="{{ route('ordenes.finalizar', $orden->numero_ot) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="comentario_avance">Comentario Final</label>
                                <input type="text" name="comentario_avance" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="fecha_avance">Fecha Final</label>
                                <input type="date" name="fecha_avance" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="tiempo_avance">Tiempo en Horas</label>
                                <input type="number" name="tiempo_avance" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success" style="background-color: #28a745;">
                                <i class="fas fa-check-circle"></i> Finalizar OT
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <strong>La OT ya está finalizada.</strong> No se pueden agregar más avances ni finalizarla.
                </div>
                @endif

                <!-- Mostrar último avance (para la finalización) -->
                @if($orden->avances->isNotEmpty())
                <div class="card mt-4">
                    <div class="card-header">Último Avance para la Finalización</div>
                    <div class="card-body">
                        <p><strong>Comentario:</strong> {{ $orden->avances->last()->comentario_avance }}</p>
                        <p><strong>Fecha:</strong> {{ $orden->avances->last()->fecha_avance }}</p>
                        <p><strong>Tiempo:</strong> {{ $orden->avances->last()->tiempo_avance }} horas</p>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</main>
@endsection
