<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'tecnico']);
        $role3 = Role::create(['name' => 'solicitante']);
        $role4 = Role::create(['name' => 'secretaria']);

        Permission::create(['name' => 'admin.home'])->syncRoles([$role1, $role2, $role3, $role4]);


        //PERMISOS PARA USUARIOS
        Permission::create(['name' => 'admin.users.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.users.destroy'])->syncRoles([$role1]);

        //PERMISOS PARA SOLICIUTDES
        Permission::create(['name' => 'admin.solicitudes.index'])->syncRoles([$role1, $role3, $role4]);
        Permission::create(['name' => 'admin.solicitudes.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.solicitudes.edit'])->syncRoles([$role1, $role3, $role4]);
        Permission::create(['name' => 'admin.solicitudes.destroy'])->syncRoles([$role1, $role3, $role4]);

        //PERMISOS PARA ATENCIONES
        Permission::create(['name' => 'admin.atenciones.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.atenciones.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.atenciones.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.atenciones.destroy'])->syncRoles([$role1, $role2]);

        //PERMISOS PARA ANOTACIONES
        Permission::create(['name' => 'admin.anotaciones.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.anotaciones.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.anotaciones.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.anotaciones.destroy'])->syncRoles([$role1, $role2]);

        //PERMISOS PARA OFICINAS
        Permission::create(['name' => 'admin.oficinas.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.oficinas.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.oficinas.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.oficinas.destroy'])->syncRoles([$role1]);

    }
}
