<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'super-admin']);
        $admin->givePermissionTo(Permission::all());

        $user = User::find(1);
        $user->assignRole([$admin]);

        $condominio = Role::create(['name' => 'CONDOMINIO']);
        $condominio->givePermissionTo([
            'CONDOMINIO.LISTAR',
            'CONDOMINIO.CREAR',
            'CONDOMINIO.EDITAR',
            'CONDOMINIO.ELIMINAR',
            'CONDOMINIO.MOSTRAR',

            'INGRESO.LISTAR',
            'INGRESO.CREAR',
            'INGRESO.EDITAR',
            'INGRESO.ELIMINAR',
            'INGRESO.MOSTRAR',
            'INGRESO.LISTAR',

            'HABITANTE.CREAR',
            'HABITANTE.EDITAR',
            'HABITANTE.ELIMINAR',
            'HABITANTE.MOSTRAR',

            'VISITANTE.CREAR',
            'VISITANTE.EDITAR',
            'VISITANTE.ELIMINAR',
            'VISITANTE.MOSTRAR',

            'VEHICULO.CREAR',
            'VEHICULO.EDITAR',
            'VEHICULO.ELIMINAR',
            'VEHICULO.MOSTRAR',

            'GALERIA_VISITANTE.CREAR',
            'GALERIA_VISITANTE.EDITAR',
            'GALERIA_VISITANTE.ELIMINAR',
            'GALERIA_VISITANTE.MOSTRAR',

            'GALERIA_VEHICULO.CREAR',
            'GALERIA_VEHICULO.EDITAR',
            'GALERIA_VEHICULO.ELIMINAR',
            'GALERIA_VEHICULO.MOSTRAR',
        ]);
        $user = User::find(2);
        $user->assignRole([$condominio]);

        $supervisor = Role::create(['name' => 'SUPERVISOR']);
        $supervisor->givePermissionTo([
            'CONDOMINIO.LISTAR',
            'INGRESO.LISTAR',
            'HABITANTE.LISTAR',
            'VISITANTE.LISTAR',
            'VEHICULO.LISTAR',
            'VIVIENDA.LISTAR',
            'GALERIA_VISITANTE.LISTAR',
            'GALERIA_VEHICULO.LISTAR'
        ]);
    }
}