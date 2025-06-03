@extends('layouts.master')

@section('content')

<main id="main-content" class="col bg-faded p-2 flex-grow-1 border border-primary" style="min-height: 100vh;">

    <div class="col border border-secondary">

        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Técnicos</h2>
            <div class="d-flex align-items-center">
                <a href="{{ route('tecnicos.create') }}" class="btn btn-primary ms-auto"
                    style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="bi bi-plus"></i> Agregar
                </a>
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('tecnicos.buscar') }}" method="get" class="d-flex" style="gap: 4px;">
                <input type="text" name="search" id="search" class="form-control" placeholder="Buscar por...">
                <button type="submit" class="btn btn-primary"
                    style="background-color: #cc6633; border-color: #cc6633;">Buscar</button>
            </form>
        </div>

        <div class="table-responsive mt-3 border border-danger">
            <table class="table table-striped" id="tecnicos_tabledata">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">Id</th>
                        <th onclick="sortTable(1)">Nombre</th>
                        <th onclick="sortTable(2)">RUT</th>
                        <th onclick="sortTable(3)">Teléfono</th>
                        <th onclick="sortTable(4)">Correo</th>
                        <th onclick="sortTable(5)">Precio por Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tecnicos as $tecnico)
                        <tr>
                            <td>{{ $tecnico->id }}</td>
                            <td>{{ $tecnico->nombre_tecnico }}</td>
                            <td>{{ $tecnico->rut_tecnico }}</td>
                            <td>{{ $tecnico->telefono_tecnico }}</td>
                            <td>{{ $tecnico->email_tecnico }}</td>
                            <td>{{ $tecnico->precio_hora_tecnico }}</td>
                            <td>
                                <div class="d-flex justify-content-center" style="gap: 3px;">
                                    <a href="{{ route('tecnicos.show', $tecnico->id) }}" class="btn btn-primary me-1"
                                        style="background-color: #cc0066; border-color: #cc0066;">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="#" class="btn btn-primary me-1"
                                        style="background-color: #cc6633; border-color: #cc6633;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger"
                                        style="background-color: #d9534f; border-color: #d43f3a;">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            @if ($tecnicos->hasPages())
                <nav>
                    <ul class="pagination" style="color: #cc6633;">
                        {{-- First Page Link --}}
                        @if ($tecnicos->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link" style="color: #cc6633;">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $tecnicos->url(1) }}" rel="prev"
                                    style="color: #cc6633;">&laquo;</a>
                            </li>
                        @endif

                        {{-- Previous Page Link --}}
                        @if ($tecnicos->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link" style="color: #cc6633;">&lsaquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $tecnicos->previousPageUrl() }}" rel="prev"
                                    style="color: #cc6633;">&lsaquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($tecnicos->getUrlRange(max(1, $tecnicos->currentPage() - 5), min($tecnicos->lastPage(), $tecnicos->currentPage() + 5)) as $page => $url)
                            @if ($page == $tecnicos->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link"
                                        style="background-color: #cc6633; border-color: #cc6633;">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}" style="color: #cc6633;">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($tecnicos->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $tecnicos->nextPageUrl() }}" rel="next"
                                    style="color: #cc6633;">&rsaquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link" style="color: #cc6633;">&rsaquo;</span>
                            </li>
                        @endif

                        {{-- Last Page Link --}}
                        @if ($tecnicos->currentPage() == $tecnicos->lastPage())
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link" style="color: #cc6633;">&raquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $tecnicos->url($tecnicos->lastPage()) }}" rel="next"
                                    style="color: #cc6633;">&raquo;</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</main>
<script src="{{ asset('assets/js/ordenar/OrdenarTecnico.js') }}"></script>

@endsection
