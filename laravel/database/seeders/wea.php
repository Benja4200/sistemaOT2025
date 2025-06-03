<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        /* Buscar los roles por su nombre en la base de datos */
        $role1 = Role::firstOrCreate(['name' => 'Administrador'], ['guard_name' => 'web', 'description' => 'Todos los permisos']);
        $role2 = Role::firstOrCreate(['name' => 'Técnicos'], ['guard_name' => 'web', 'description' => 'Permisos para los técnicos']);
        $role3 = Role::firstOrCreate(['name' => 'Administrativo'], ['guard_name' => 'web', 'description' => 'Administrativo']);

        /* PERMISOS PARA ÓRDENES */
        $perm1 = Permission::firstOrCreate(['name' => 'ordenes.page'], ['description' => 'Ver el módulo de Órdenes']);
        $perm2 = Permission::firstOrCreate(['name' => 'ordenes.create'], ['description' => 'Crear Órdenes']);

        /* PERMISOS DE ROLES */
        $perm3 = Permission::firstOrCreate(['name' => 'roles.page'], ['description' => 'Ver Roles']);

        /* PERMISOS PARA CLIENTES */
        $perm4 = Permission::firstOrCreate(['name' => 'clientes.page'], ['description' => 'Ver el módulo de Clientes']);
        $perm5 = Permission::firstOrCreate(['name' => 'clientes.create'], ['description' => 'Crear Clientes']);

        /* PERMISOS PARA USUARIOS */
        $perm6 = Permission::firstOrCreate(['name' => 'usuarios.index'], ['description' => 'Ver el módulo de Usuarios']);
        $perm7 = Permission::firstOrCreate(['name' => 'ordenes.editar'], ['description' => 'Editar Órdenes']);

        /* ASIGNAR PERMISOS A ROLES */
        $role1->syncPermissions([$perm1, $perm2, $perm3, $perm4, $perm5, $perm6, $perm7]); // Admin
        $role2->syncPermissions([$perm1, $perm7]); // Técnicos solo pueden ver órdenes y editarlas
        $role3->syncPermissions([]); // Administrativo sin permisos (ajusta según necesidad)
    }
}
