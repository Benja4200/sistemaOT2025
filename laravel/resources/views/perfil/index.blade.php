@extends('layouts.master')
 
 @section('content')
 <main id="main-content" class="col bg-faded py-3 flex-grow-1" style="min-height: 100vh;">
 <div class="container py-4">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card shadow-sm">
                 <div class="card-header card-header-custom">
                     <h5 class="mb-0">Mi Perfil</h5>
                 </div>
                 
                 <div class="card-body">
                     <div class="row align-items-center mb-4">
                         <div class="col-md-3 text-center">
                             <img src="{{ asset('assets/image/LogoCli.png') }}" 
                                  class="img-thumbnail rounded-circle" 
                                  width="120" 
                                  alt="Avatar">
                         </div>
                         <div class="col-md-9">
                             <h4>{{ $user->nombre_usuario }}</h4>
                             <p class="mb-1"><strong>Email:</strong> {{ $user->email_usuario }}</p>
                             <p class="mb-1"><strong>Rol:</strong> 
                                 @foreach($user->roles as $role)
                                     <span class="badge bg-secondary">{{ $role->name }}</span>
                                 @endforeach
                             </p>
                             @if($user->created_at)
                                 <p class="mb-0"><strong>Miembro desde:</strong> 
                                     {{ $user->created_at->format('d/m/Y') }}
                                 </p>
                             @endif
                         </div>
                     </div>
 
                     <!-- Sección para información adicional -->
                     <div class="border-top pt-3">
                         <h5>Información Adicional</h5>
                         <!-- Agrega aquí más campos según necesites -->
                         
                         <!-- Ejemplo de botón para editar perfil -->
                         <div class="mt-3">
                             <a href="{{ route('perfil.edit') }}" class="btn btn-outline-primary">
                                 <i class="fas fa-edit"></i> Editar Perfil
                             </a>
                             @if(is_null($user->firma))
                                <a href="{{ route('signature.create', $user->id) }}" style="background-color: #cc6633; border-color: #cc6633;" class="btn btn-outline-primary">
                                    <i class="fas fa-pencil-alt"></i> Crear su firma
                                </a>
                            @else
                                <a href="{{ route('signature.edit', $user->id) }}" style="background-color: #cc6633; border-color: #cc6633;" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i> Editar Firma
                                </a>
                            @endif
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection