<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TipoVisitaController;
use App\Http\Controllers\TipoViviendaController;
use App\Http\Controllers\HabitanteController;
use App\Http\Controllers\GaleriaVisitanteController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\CondominioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ViviendaController;
use App\Http\Controllers\IngresoController;

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
        return Inertia::render('Dashboard');
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

    /* ACTUALIZAR INFORMACION DE USUARIO*/
    Route::put('/user/updateinformacion/{user}',[UserController::class, 'updateInformacion'])->name('user.updateinformacion');
});