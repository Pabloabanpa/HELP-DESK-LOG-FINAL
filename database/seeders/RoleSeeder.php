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
        Permission::create(['name' => 'admin.user.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.user.destroy'])->syncRoles([$role1]);
        //PERMISOS PARA SOLICIUTDES
        Permission::create(['name' => 'admin.solicitud.index'])->syncRoles([$role1, $role2, $role3, $role4]);
        Permission::create(['name' => 'admin.solicitud.create'])->syncRoles([$role1, $role3]);
        Permission::create(['name' => 'admin.solicitud.edit'])->syncRoles([$role1, $role4]);
        Permission::create(['name' => 'admin.solicitud.destroy'])->syncRoles([$role1, $role4]);


        
        //PERMISOS PARA ATENCIONES
        Permission::create(['name' => 'admin.atencion.index'])->syncRoles([$role1, $role2, $role4]);
        Permission::create(['name' => 'admin.atencion.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.atencion.edit'])->syncRoles([$role1, $role2, $role4]);
        Permission::create(['name' => 'admin.atencion.destroy'])->syncRoles([$role1, $role2]);
        //PERMISOS PARA ANOTACIONES
        Permission::create(['name' => 'admin.anotacion.index'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.anotacion.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.anotacion.edit'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.anotacion.destroy'])->syncRoles([$role1, $role2]);

        //PERMISOS PARA OFICINAS
        Permission::create(['name' => 'admin.oficina.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.oficina.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.oficina.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.oficina.destroy'])->syncRoles([$role1]);

    }
}
