@extends('layouts.master')

@section('content')

<main class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center text-center mt-3">
            <h2>Rol</h2>
            <div class="d-flex align-items-center">
                <!-- Botón para agregar -->
                <a href="" class="btn btn-primary ms-auto" style="background-color: #cc6633; border-color: #cc6633;">
                    <i class="bi bi-plus"></i> Agregar
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <!-- Formulario de búsqueda -->
            <form action="#" method="get" class="input-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="Buscar por...">
                <button type="submit" class="btn btn-primary" style="background-color: #cc6633; border-color: #cc6633;">Buscar</button>
            </form>
        </div>

        <!-- Card de advertencia -->
        <div class="card bg-success text-white mt-3">
            <div class="card-body">
                <h5 class="card-title">Advertencia</h5>
                <p class="card-text">
                    Este mensaje es una advertencia genérica sobre los roles en tu sistema.
                </p>
            </div>
        </div>

        <!-- Tabla de roles -->
        <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped text-center shadow">
                <thead class="table-primary">
                    <tr>
                        <th>#ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo de fila de tabla -->
                    <tr>
                        <td>1</td>
                        <td>Rol Ejemplo</td>
                        <td>Descripción del Rol Ejemplo</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- Botones de acción -->
                                <a href="#" class="btn btn-primary me-2" style="background-color: #cc6633; border-color: #cc6633;">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="#" class="btn btn-danger delete-button">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Botones de paginación -->
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous" style="background-color: #cc6633; border-color: #cc6633; color: #fff;">
                        <span aria-hidden="true">&laquo; Anterior</span>
                    </a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next" style="background-color: #cc6633; border-color: #cc6633; color: #fff;">
                        <span aria-hidden="true">Siguiente &raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</main>

@endsection
