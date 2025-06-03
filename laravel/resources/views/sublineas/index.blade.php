@extends('layouts.master')

@section('content')

    <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
        <div class="container-fluid">
            <h2 class="mb-4">Listado de Sublíneas</h2>

            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <a href="{{ route('sublineas.create') }}" class="btn btn-primary mb-3" style="background-color: #cc6633;">Agregar Sublínea</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Sublínea</th>
                        <th>Línea</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sublineas as $sublinea)
                        <tr>
                            <td>{{ $sublinea->id }}</td>
                            <td>{{ $sublinea->nombre_sublinea }}</td>
                            <td>{{ $sublinea->linea->nombre_linea ?? 'No asignada' }}</td>
                            <td>
                                <a href="{{ route('sublineas.show', $sublinea->id) }}" class="btn btn-info btn-sm" style="background-color: #cc0066; border-color: #cc0066;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sublineas.edit', $sublinea->id) }}" class="btn btn-warning btn-sm" style="background-color: #CC6633; border-color: #CC6633;">
                                    <i class="fas fa-edit text-white"></i>
                                </a>
                                <form action="{{ route('sublineas.destroy', $sublinea->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta sublínea?')">
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
