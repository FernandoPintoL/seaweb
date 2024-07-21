<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\HabitanteController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\TipoVisitaController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\ViviendaController;
use App\Http\Controllers\GaleriaVisitanteController;
use App\Http\Controllers\GaleriaVehiculoController;

Route::get('/appuser', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::apiResource('/users', UserController::class);
Route::post('/appusers/consultar', [UserController::class,'consultar'])->name('appusers.consultar');
Route::post('/appusers/registerOnApi', [UserController::class,'registerOnApi'])->name('appusers.registerOnApi');
Route::post('/appusers/loginOnApi', [UserController::class,'loginOnApi'])->name('appusers.loginOnApi');
Route::post('/appusers/logout', [UserController::class,'logout'])->name('appusers.logout');

/* TIPO PERFIL RUTAS */
Route::apiResource('appperfil', PerfilController::class);
Route::post('/appperfil/query',[PerfilController::class, 'query'])->name('appperfil.query');
/* TIPO DOCUMENTO RUTAS */
Route::apiResource('apptipodocumento', TipoDocumentoController::class);
Route::post('/apptipodocumento/query',[TipoDocumentoController::class, 'query'])->name('apptipodocumento.query');
/* HABITANTES RUTAS */
Route::apiResource('apphabitante', HabitanteController::class);
Route::post('/apphabitante/query',[HabitanteController::class, 'query'])->name('apphabitante.query');
Route::get('/apphabitante/vivienda/{idvivienda}',[HabitanteController::class, 'getVivienda'])->name('apphabitante.getvivienda');
Route::get('/apphabitante/residente/{idresidente}',[HabitanteController::class, 'getResidente'])->name('apphabitante.getResidente');
Route::post('/apphabitante/querySearchWeb',[HabitanteController::class, 'querySearchWeb'])->name('apphabitante.querySearchWeb');
/* VISITANTES RUTAS */
Route::apiResource('appvisitante', VisitanteController::class);
Route::post('/appvisitante/query',[VisitanteController::class, 'query'])->name('appvisitante.query');
Route::post('/appvisitante/queryId',[VisitanteController::class, 'queryId'])->name('appvisitante.queryId');
/* VIVIENDAS RUTAS */
Route::apiResource('appvivienda', ViviendaController::class);
Route::post('/appvivienda/query',[ViviendaController::class, 'query'])->name('appvivienda.query');
/* INGRESOS RUTAS */
Route::apiResource('appingreso', IngresoController::class);
Route::post('/appingreso/query',[IngresoController::class, 'query'])->name('appingreso.query');

/** GALERIA DE VISITANTES **/
Route::apiResource('appgaleriavisitante', GaleriaVisitanteController::class);
Route::post('/appgaleriavisitante/uploadimage', [GaleriaVisitanteController::class,'uploadimage'])->name('appgaleriavisitante.uploadimage');
Route::post('/appgaleriavisitante/visitanteid',[GaleriaVisitanteController::class, 'getGaleriaVisitante'])->name('appgaleriavisitante.visitanteid');
/** GALERIA DE VEHICULOS **/
Route::apiResource('appgaleriavehiculo', GaleriaVehiculoController::class);
Route::post('/appgaleriavehiculo/uploadimage', [GaleriaVehiculoController::class,'uploadimage'])->name('appgaleriavehiculo.uploadimage');
Route::post('/appgaleriavehiculo/vehiculoid',[GaleriaVehiculoController::class, 'getGaleriaVehiculo'])->name('appgaleriavehiculo.vehiculoid');
/* TIPOVISITA RUTAS */
Route::apiResource('apptipovisita', TipoVisitaController::class);
Route::post('/apptipovisita/query',[TipoVisitaController::class, 'query'])->name('apptipovisita.query');
/* VEHICULOS RUTAS */
Route::apiResource('appvehiculo', VehiculoController::class);
Route::post('/appvehiculo/query',[VehiculoController::class, 'query'])->name('appvehiculo.query');
Route::post('/appvehiculo/queryId',[VehiculoController::class, 'queryId'])->name('appvehiculo.queryId');