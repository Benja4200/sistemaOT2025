<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos para módulo de Órdenes
        Permission::create(['name' => 'ordenes.index', 'description' => 'Ver el modulo de órdenes']);
        Permission::create(['name' => 'ordenes.create', 'description' => 'Crear órdenes']);
        Permission::create(['name' => 'ordenes.edit', 'description' => 'Editar órdenes']);
        Permission::create(['name' => 'ordenes.toggle_view', 'description' => 'Cambiar entre órdenes asignadas y todas las órdenes']);

        // Permisos para módulo de Roles
        Permission::create(['name' => 'roles.index', 'description' => 'Ver el modulo de roles']);
        Permission::create(['name' => 'roles.create', 'description' => 'Crear roles']);
        Permission::create(['name' => 'roles.edit', 'description' => 'Editar roles']);
        Permission::create(['name' => 'roles.destroy', 'description' => 'Eliminar roles']);

        // Permisos para módulo de Clientes
        Permission::create(['name' => 'clientes.index', 'description' => 'Ver el modulo de clientes']);
        Permission::create(['name' => 'clientes.create', 'description' => 'Crear clientes']);

        // Permisos para módulo de Usuarios
        Permission::create(['name' => 'usuarios.index', 'description' => 'Ver el modulo de usuarios']);
        Permission::create(['name' => 'usuarios.create', 'description' => 'Crear usuarios']);
        Permission::create(['name' => 'usuarios.edit', 'description' => 'Editar usuarios']);
        Permission::create(['name' => 'usuarios.update', 'description' => 'Actualizar usuarios']);
        Permission::create(['name' => 'usuarios.destroy', 'description' => 'Eliminar usuarios']);
    }
}
