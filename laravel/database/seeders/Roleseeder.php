<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class Roleseeder extends Seeder
{
    public function run(): void
    {
        /* SE CREAN LOS ROLES */
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Tecnico']);
        $role3 = Role::create(['name' => 'Cliente']);

        /* PERMISOS PARA ORDENES */
        Permission::create(['name' => 'ordenes.page', 'description' => 'Ver el modulo de Ordenes'])->syncRoles([$role1]);
        Permission::create(['name' => 'ordenes.create', 'description' => 'Crear Ordenes'])->syncRoles([$role1]);

        /* PERMISOS DE ROLES */
        Permission::create(['name' => 'roles.page', 'description' => 'Ver Roles'])->syncRoles([$role1]);

        /* PERMISOS PARA CLIENTES */
        Permission::create(['name' => 'clientes.page', 'description' => 'Ver el modulo de Clientes'])->syncRoles([$role1]);
        Permission::create(['name' => 'clientes.create', 'description' => 'Crear Clientes'])->syncRoles([$role1]);

        /* PERMISOS PARA USUARIOS */
        Permission::create(['name' => 'usuarios.index', 'description' => 'Ver el modulo de Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'ordenes.editar', 'description' => 'Editar Ordenes'])->syncRoles([$role1]);


        /* SE ASIGNAN LOS PERMISOS */
    }
}
