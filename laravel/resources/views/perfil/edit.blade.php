@extends('layouts.master')
 
 @section('content')
  <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
 <div class="container py-4">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card shadow-sm">
                 <div class="card-header card-header-custom">
                     <h5 class="mb-0">Editar Perfil</h5>
                 </div>
                 
                 <div class="card-body">
                     <form method="POST" action="{{ route('perfil.update') }}">
                         @csrf
                         @method('PUT')
                         
                         <div class="mb-3">
                             <label for="nombre_usuario" class="form-label">Nombre</label>
                             <input type="text" class="form-control" id="nombre_usuario" 
                                    name="nombre_usuario" value="{{ old('nombre_usuario', $user->nombre_usuario) }}" required>
                         </div>
                         
                         <div class="mb-3">
                             <label for="email_usuario" class="form-label">Email</label>
                             <input type="email" class="form-control" id="email_usuario" 
                                    name="email_usuario" value="{{ old('email_usuario', $user->email_usuario) }}" required>
                         </div>
                         
                         <div class="mb-3">
                             <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
                             <input type="password" class="form-control" id="password" name="password">
                         </div>
                         
                         <div class="mb-3">
                             <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                             <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                         </div>
                         
                         <div class="d-grid gap-2">
                             <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                             <a href="{{ route('perfil') }}" class="btn btn-secondary">Cancelar</a>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection