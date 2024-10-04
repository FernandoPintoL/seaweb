<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //CONDOMINIOS
        $crear = Permission::create(['name' => 'CONDOMINIO.CREAR']);
        $editar = Permission::create(['name' => 'CONDOMINIO.EDITAR']);
        $eliminar = Permission::create(['name' => 'CONDOMINIO.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'CONDOMINIO.MOSTRAR']);
        $listar = Permission::create(['name' => 'CONDOMINIO.LISTAR']);

        //GALERIA VISITANTE
        $crear = Permission::create(['name' => 'GALERIA_VISITANTE.CREAR']);
        $editar = Permission::create(['name' => 'GALERIA_VISITANTE.EDITAR']);
        $eliminar = Permission::create(['name' => 'GALERIA_VISITANTE.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'GALERIA_VISITANTE.MOSTRAR']);
        $listar = Permission::create(['name' => 'GALERIA_VISITANTE.LISTAR']);


        //GALERIA VEHICULO
        $crear = Permission::create(['name' => 'GALERIA_VEHICULO.CREAR']);
        $editar = Permission::create(['name' => 'GALERIA_VEHICULO.EDITAR']);
        $eliminar = Permission::create(['name' => 'GALERIA_VEHICULO.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'GALERIA_VEHICULO.MOSTRAR']);
        $listar = Permission::create(['name' => 'GALERIA_VEHICULO.LISTAR']);


        //HABITANTES
        $crear = Permission::create(['name' => 'HABITANTE.CREAR']);
        $editar = Permission::create(['name' => 'HABITANTE.EDITAR']);
        $eliminar = Permission::create(['name' => 'HABITANTE.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'HABITANTE.MOSTRAR']);
        $listar = Permission::create(['name' => 'HABITANTE.LISTAR']);

        //INGRESOS
        $crear = Permission::create(['name' => 'INGRESO.CREAR']);
        $editar = Permission::create(['name' => 'INGRESO.EDITAR']);
        $eliminar = Permission::create(['name' => 'INGRESO.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'INGRESO.MOSTRAR']);
        $listar = Permission::create(['name' => 'INGRESO.LISTAR']);


        //PERMISOS
        $crear = Permission::create(['name' => 'PERMISO.CREAR']);
        $editar = Permission::create(['name' => 'PERMISO.EDITAR']);
        $eliminar = Permission::create(['name' => 'PERMISO.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'PERMISO.MOSTRAR']);
        $listar = Permission::create(['name' => 'PERMISO.LISTAR']);


        //ROLES
        $crear = Permission::create(['name' => 'ROLE.CREAR']);
        $editar = Permission::create(['name' => 'ROLE.EDITAR']);
        $eliminar = Permission::create(['name' => 'ROLE.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'ROLE.MOSTRAR']);
        $listar = Permission::create(['name' => 'ROLE.LISTAR']);


        //TIPO DOCUMENTOS
        $crear = Permission::create(['name' => 'TIPO_DOCUMENTO.CREAR']);
        $editar = Permission::create(['name' => 'TIPO_DOCUMENTO.EDITAR']);
        $eliminar = Permission::create(['name' => 'TIPO_DOCUMENTO.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'TIPO_DOCUMENTO.MOSTRAR']);
        $listar = Permission::create(['name' => 'TIPO_DOCUMENTO.LISTAR']);


        //TIPO VISITAS
        $crear = Permission::create(['name' => 'TIPO_VISITA.CREAR']);
        $editar = Permission::create(['name' => 'TIPO_VISITA.EDITAR']);
        $eliminar = Permission::create(['name' => 'TIPO_VISITA.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'TIPO_VISITA.MOSTRAR']);
        $listar = Permission::create(['name' => 'TIPO_VISITA.LISTAR']);

        //TIPO VIVIENDA
        $crear = Permission::create(['name' => 'TIPO_VIVIENDA.CREAR']);
        $editar = Permission::create(['name' => 'TIPO_VIVIENDA.EDITAR']);
        $eliminar = Permission::create(['name' => 'TIPO_VIVIENDA.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'TIPO_VIVIENDA.MOSTRAR']);
        $listar = Permission::create(['name' => 'TIPO_VIVIENDA.LISTAR']);

        //VEHICULOS
        $crear = Permission::create(['name' => 'VEHICULO.CREAR']);
        $editar = Permission::create(['name' => 'VEHICULO.EDITAR']);
        $eliminar = Permission::create(['name' => 'VEHICULO.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'VEHICULO.MOSTRAR']);
        $listar = Permission::create(['name' => 'VEHICULO.LISTAR']);


        //VISITANTES
        $crear = Permission::create(['name' => 'VISITANTE.CREAR']);
        $editar = Permission::create(['name' => 'VISITANTE.EDITAR']);
        $eliminar = Permission::create(['name' => 'VISITANTE.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'VISITANTE.MOSTRAR']);
        $listar = Permission::create(['name' => 'VISITANTE.LISTAR']);


        //VIVIENDAS
        $crear = Permission::create(['name' => 'VIVIENDA.CREAR']);
        $editar = Permission::create(['name' => 'VIVIENDA.EDITAR']);
        $eliminar = Permission::create(['name' => 'VIVIENDA.ELIMINAR']);
        $mostrar = Permission::create(['name' => 'VIVIENDA.MOSTRAR']);
        $listar = Permission::create(['name' => 'VIVIENDA.LISTAR']);

    }
}