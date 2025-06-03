@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <h2 class="mb-4">Listado de Líneas</h2>

            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('lineas.create') }}" class="btn btn-primary mb-3" style="background-color: #cc6633;">Agregar Línea</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Línea</th>
                        <th>Subcategoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lineas as $linea)
                        <tr>
                            <td>{{ $linea->id }}</td>
                            <td>{{ $linea->nombre_linea }}</td>
                            <td>{{ $linea->subcategoria->nombre_subcategoria ?? 'Sin subcategoría' }}</td>
                            <td>
                                <a href="{{ route('lineas.show', $linea->id) }}" class="btn btn-info btn-sm" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('lineas.edit', $linea->id) }}" class="btn btn-warning btn-sm" style="background-color: #CC6633; border-color: #CC6633;">
                                    <i class="fas fa-edit text-white"></i>
                                </a>
                                <form action="{{ route('lineas.destroy', $linea->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta línea?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
