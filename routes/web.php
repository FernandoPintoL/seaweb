<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TipoVisitaController;
use App\Http\Controllers\TipoViviendaController;
use App\Http\Controllers\HabitanteController;
use App\Http\Controllers\GaleriaVisitanteController;
use App\Http\Controllers\GaleriaVehiculoController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\CondominioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ViviendaController;
use App\Http\Controllers\IngresoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'canResetPassword' => true,
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $listado = DB::table('ingresos as i')
                        ->select('i.id as id',
                                'i.*',
                                'h.id as id_residente',
                                'p.name as name_residente',
                                'p.nroDocumento as nroDocumento_residente',
                                'vvd.id as id_vivienda',
                                'vvd.nroVivienda',
                                'v.id as id_visitante',
                                'v.is_permitido',
                                'v.description_is_no_permitido',
                                'pv.nroDocumento as nroDocumento_visitante',
                                'pv.name as name_visitante',
                                'tv.id as tv_id',
                                'tv.sigla as tv_sigla',
                                'tv.detalle as tv_detalle')
                        ->join('habitantes as h', 'h.id', '=', 'i.residente_habitante_id')
                        ->join('viviendas as vvd', 'h.vivienda_id', '=', 'vvd.id')
                        ->join('perfils as p', 'h.perfil_id', '=', 'p.id')
                        ->join('visitantes as v', 'v.id', '=', 'i.visitante_id')
                        ->join('perfils as pv', 'v.perfil_id', '=', 'pv.id')
                        ->join('tipo_visitas as tv', 'i.tipo_visita_id', '=', 'tv.id')
                        ->skip(0)
                        ->take(10)
                        ->orderBy('id', 'DESC')
                        ->get();
        return Inertia::render('Dashboard', ['listado' => $listado]);
    })->name('dashboard');
    /* CONDOMINIOS RUTAS */
    Route::resource('condominio', CondominioController::class);
    Route::post('/condominio/query',[CondominioController::class, 'query'])->name('condominio.query');
    /* TIPO DOCUMENTO RUTAS */
    Route::resource('tipodocumento', TipoDocumentoController::class);
    Route::post('/tipodocumento/query',[TipoDocumentoController::class, 'query'])->name('tipodocumento.query');
    /* TIPO VISITAS RUTAS */
    Route::resource('tipovisita', TipoVisitaController::class);
    Route::post('/tipovisita/query',[TipoVisitaController::class, 'query'])->name('tipovisita.query');
    /* TIPO VIVIENDAS RUTAS */
    Route::resource('tipovivienda', TipoViviendaController::class);
    Route::post('/tipovivienda/query',[TipoViviendaController::class, 'query'])->name('tipovivienda.query');
    /* VEHICULOS RUTAS */
    Route::resource('vehiculo', VehiculoController::class);
    Route::post('/vehiculo/query',[VehiculoController::class, 'query'])->name('vehiculo.query');
    /* VIVIENDA RUTAS */
    Route::resource('vehiculo', VehiculoController::class);
    Route::post('/vehiculo/query',[VehiculoController::class, 'query'])->name('vehiculo.query');
    Route::put('/vehiculo/update/{vehiculo}',[VehiculoController::class, 'updateVehiculo'])->name('vehiculo.updateVehiculo');
    /* HABITANTES RUTAS */
    Route::resource('habitante', HabitanteController::class);
    Route::post('/habitante/query',[HabitanteController::class, 'query'])->name('habitante.query');
    Route::post('/habitante/querySearchWeb',[HabitanteController::class, 'querySearchWeb'])->name('habitante.querySearchWeb');

    /* VISITANTES RUTAS */
    Route::resource('visitante', VisitanteController::class);
    Route::put('/visitante/update/{visitante}',[VisitanteController::class, 'updateWeb'])->name('visitante.updateWeb');
    Route::post('/visitante/query',[VisitanteController::class, 'query'])->name('visitante.query');
    Route::post('/visitante/querySearchWeb',[VisitanteController::class, 'querySearchWeb'])->name('visitante.querySearchWeb');

    /* VIVIENDA RUTAS */
    Route::resource('vivienda', ViviendaController::class);
    Route::post('/vivienda/query',[ViviendaController::class, 'query'])->name('vivienda.query');
    /* INGRESOS RUTAS */
    Route::resource('ingreso', IngresoController::class);
    Route::post('/ingreso/query',[IngresoController::class, 'query'])->name('ingreso.query');
    Route::put('/ingreso/update/{ingreso}',[IngresoController::class, 'updateIngreso'])->name('ingreso.updateIngreso');

    /* GALERIA VISITANTE */
    Route::resource('/galeriavisitante', GaleriaVisitanteController::class);
    Route::post('/galeriavisitante/query',[GaleriaVisitanteController::class, 'query'])->name('galeriavisitante.query');
    Route::post('/galeriavisitante/visitante',[GaleriaVisitanteController::class, 'getGaleriaVisitante'])->name('galeriavisitante.getGaleriaVisitante');
    Route::post('/galeriavisitante/uploadimage', [GaleriaVisitanteController::class,'uploadimage'])->name('galeriavisitante.uploadimage');
    Route::get('/galeriavisitante/descargar/{id}',[GaleriaVisitanteController::class, 'descargar'])->name('galeriavisitante.des');
    Route::get('/galeriavisitante/descargardbpath/{id}',[GaleriaVisitanteController::class, 'descargarDBPath'])->name('galeriavisitante.descargardbpath');
    Route::get('/galeriavisitante/descargardbphotopath/{id}',[GaleriaVisitanteController::class, 'descargarDBPhotoPath'])->name('galeriavisitante.descargardbphotopath');
    Route::get('/galeriavisitante/descargardirectoriopath/{id}',[GaleriaVisitanteController::class, 'descargarDirectorioPath'])->name('galeriavisitante.descargardirectoriopath');
    Route::get('/galeriavisitante/descargardirectoriourl/{id}',[GaleriaVisitanteController::class, 'descargarDirectorioUrl'])->name('galeriavisitante.descargarDirectorioUrl');

    /* GALERIA VEHICULO */
    Route::resource('/galeriavehiculo', GaleriaVehiculoController::class);

    /* ACTUALIZAR INFORMACION DE USUARIO*/
    Route::put('/user/updateinformacion/{user}',[UserController::class, 'updateInformacion'])->name('user.updateinformacion');
});
