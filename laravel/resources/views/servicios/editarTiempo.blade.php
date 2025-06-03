@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Editar Tiempo de Tarea</h2>
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary" style="background-color: #cc6633; border-color: #cc6633;">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        Editar tiempo para la tarea {{ $tarea->nombre_tarea }} del servicio {{ $servicio->nombre_servicio }}
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
                        <form action="{{ route('actualizar.tiempo', ['servicioId' => $servicio->id, 'tareaId' => $tarea->id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="tiempo" class="form-label">Tiempo en minutos</label>
                                <input type="number" class="form-control" id="tiempo" name="tiempo" value="{{ $tarea->pivot->tiempo }}" required>
                                @error('tiempo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-save"></i> Actualizar Tiempo
                                </button>
                                <a href="{{ route('servicios.asignarTareas', $servicio->id) }}" class="btn btn-secondary" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection