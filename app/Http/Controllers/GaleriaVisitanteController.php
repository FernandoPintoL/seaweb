<?php

namespace App\Http\Controllers;

use App\Models\GaleriaVisitante;
use App\Http\Requests\StoreGaleriaVisitanteRequest;
use App\Http\Requests\UpdateGaleriaVisitanteRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GaleriaVisitanteController extends Controller
{
    public function uploadimage(Request $request)
    {
        try {
            /* return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "messages" => $request->hasFile('file'),
                "messagesValue" => $request->hasFile('file'),
                "data" => $request->all()
            ]); */

            $response = null;
            $path     = null;

            if ($request->hasFile('file')) {
                $model = GaleriaVisitante::create([
                    "visitante_id" => $request->get("id"),
                    'created_at' => $request->created_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->created_at,
                    'updated_at' => $request->updated_at == null ? date_create('now')->format('Y-m-d H:i:s') : $request->updated_at
                ]);
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename = "cod" . $model->id . "-visitanteid" . $request->get("id") . '.' . $extension;
                // $path      = Storage::putFileAs( 'visitantes', $file, $filename);
                $path      = Storage::putFile('visitantes', $file, 'public');
                // $path = $file->storeAs('visitantes', $filename, 'public');
                if ($path == null || $path == 0) {
                    $model->delete();
                } else {
                    $url = Storage::url($path);
                    $response = $model->update([
                        'photo_path' => $url,
                        'detalle' => $path
                    ]);
                }
                // $path = Storage::putFileAs('/visitantes', $request->file('file'), $filename);
                // $path = Storage::disk('s3')->put('visitantes', $file);
            }
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $request->hasFile('file') && ($path != null || $path != 0),
                "isMessageError" => !$request->hasFile('file') && ($path == null || $path == 0),
                "message" => $path != null || $path != 0 ? "Archivos subidos" : "Archivos no subidos",
                "messageError" => $path != null || $path != 0 ? "Archivos subidos" : "Archivos no subidos",
                "data" => $response,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function getGaleriaVisitante(Request $request)
    {
        try {
            $responsse = GaleriaVisitante::where('visitante_id', '=', $request->get('visitante_id'))
                ->with('visitante')
                ->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function query(Request $request)
    {
        try {
            $queryStr    = $request->get('query');
            $queryUpper = strtoupper($queryStr);
            $responsse  = [];
            if ($request->get('skip') == null && $request->get('take') == null) {
                $responsse = DB::table('galeria_visitantes as gv')
                    ->select('gv.id as id', 'gv.detalle', 'gv.photo_path', 'gv.visitante_id', 'v.id as v_id', 'p.name', 'p.nroDocumento')
                    ->join('visitantes as v', 'gv.visitante_id', '=', 'v.id')
                    ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                    ->where('p.name', 'LIKE', "%" . $queryUpper . "%")
                    ->orWhere('p.nroDocumento', '=', $queryUpper)
                    // ->groupBy('gv.id', 'v.id', 'p.id')
                    ->orderBy('gv.id', 'DESC')
                    ->get();
            } else {
                $skip = $request->get('skip');
                $take = $request->get('take');
                $responsse = DB::table('galeria_visitantes as gv')
                    ->select('gv.id as id', 'gv.detalle', 'gv.photo_path', 'gv.visitante_id', 'v.id as v_id', 'p.name', 'p.nroDocumento')
                    ->join('visitantes as v', 'gv.visitante_id', '=', 'v.id')
                    ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                    ->where('p.name', 'LIKE', "%" . $queryUpper . "%")
                    ->orWhere('p.nroDocumento', 'LIKE', "%" . $queryUpper . "%")
                    ->skip($skip)
                    ->take($take)
                    ->orderBy('gv.id', 'DESC')
                    ->get();
            }
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function queryRange(Request $request)
    {
        try {
            $queryStart    = $request->get('queryStart');
            $queryEnd    = $request->get('queryEnd');
            $responsse  = [];
            $responsse = DB::table('galeria_visitantes as gv')
                ->select('gv.id as id', 'gv.detalle', 'gv.photo_path', 'gv.visitante_id', 'v.id as v_id', 'p.name', 'p.nroDocumento')
                ->join('visitantes as v', 'gv.visitante_id', '=', 'v.id')
                ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                ->whereBetween('gv.id', [$queryStart, $queryEnd])
                ->orderBy('gv.id', 'DESC')
                ->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function queryMayor(Request $request)
    {
        try {
            $queryStart    = $request->get('queryStart');
            $responsse  = [];
            $responsse = DB::table('galeria_visitantes as gv')
                ->select('gv.id as id', 'gv.detalle', 'gv.photo_path', 'gv.visitante_id', 'v.id as v_id', 'p.name', 'p.nroDocumento')
                ->join('visitantes as v', 'gv.visitante_id', '=', 'v.id')
                ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
                ->where('gv.id', '>=', $queryStart)
                ->orderBy('gv.id', 'DESC')
                ->get();
            // $responsse = GaleriaVisitante::where('id', '>=', $queryStart)->get();
            $cantidad = count($responsse);
            $str = strval($cantidad);
            return response()->json([
                "isRequest" => true,
                "isSuccess" => true,
                "isMessageError" => false,
                "message" => "$str datos encontrados",
                "messageError" => "",
                "data" => $responsse,
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $listado = DB::table('galeria_visitantes as gv')
            ->select('gv.id as id', 'gv.*', 'v.id as v_id', 'v.*', 'p.id as p_id', 'p.name', 'p.nroDocumento')
            ->join('visitantes as v', 'gv.visitante_id', '=', 'v.id')
            ->join('perfils as p', 'v.perfil_id', '=', 'p.id')
            ->groupBy('gv.id', 'v.id', 'p.id')
            ->orderBy('gv.id', 'DESC')
            ->skip(0)
            ->take(20)
            ->get();
        $galeria = GaleriaVisitante::all();
        $directorio = Storage::files('visitantes');
        return Inertia::render("GaleriaVisitante/Index", [
            'listado' => $listado,
            'galeria' => $galeria,
            'directorio' => $directorio
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGaleriaVisitanteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GaleriaVisitante $galeriaVisitante) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GaleriaVisitante $galeriaVisitante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGaleriaVisitanteRequest $request, GaleriaVisitante $galeriaVisitante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GaleriaVisitante $galeriavisitante)
    {
        try {
            $responseFile = Storage::delete($galeriavisitante->detalle);
            $responseData = $galeriavisitante->delete();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => $responseData && $responseFile,
                "isMessageError" => !$responseData && !$responseFile,
                "message" => $responseData && $responseFile ? "TRANSACCION CORRECTA" : "TRANSACCION INCORRECTA",
                "messageError" => "",
                "data" => [],
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function destroyApp(GaleriaVisitante $appgaleriaVisitante)
    {
        try {
            $existe = Storage::disk('public')->exists($appgaleriaVisitante->detalle);
            if ($existe) {
                Storage::disk('public')->delete($appgaleriaVisitante->detalle);
            }
            $responseData = $appgaleriaVisitante->delete();

            return response()->json([
                "isRequest" => true,
                "isSuccess" => $existe,
                "isMessageError" => !$existe,
                "message" => $responseData ? "TRANSACCION CORRECTA" : "TRANSACCION INCORRECTA",
                "messageError" => "",
                "data" => [],
                "statusCode" => 200
            ]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }


    public function descargar($id)
    {
        try {
            // $directorio = Storage::files( 'visitantes' );
            $galeria = GaleriaVisitante::findOrFail($id)->first();
            if ($galeria) {
                // $link = "https://sea-production-2d37.up.railway.app/storage/".$galeria->detalle;
                $link = $galeria->photo_path;
                return response()->download($link);
            } else {
                return response()->json([
                    "isRequest" => true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => "TRANSACCION INCORRECTA DIRECTORIO NO ENCONTRADO",
                    "messageError" => "",
                    "data" => [],
                    "statusCode" => 423
                ]);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function descargarDBPathDetalle($id)
    {
        try {
            // $directorio = Storage::files( 'visitantes' );
            $galeria = GaleriaVisitante::findOrFail($id)->first();
            if ($galeria) {
                $link = public_path($galeria->detalle);
                return response()->download($link);
            } else {
                return response()->json([
                    "isRequest" => true,
                    "isSuccess" => false,
                    "isMessageError" => true,
                    "message" => "TRANSACCION INCORRECTA DIRECTORIO NO ENCONTRADO",
                    "messageError" => "",
                    "data" => [],
                    "statusCode" => 423
                ]);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function descargarDirectorioPath($id)
    {
        try {
            $directorio = Storage::files('visitantes');
            $link = public_path("storage/" . $directorio[$id]);
            return response()->download($link);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }

    public function descargarDirectorioUrl($id)
    {
        try {
            $directorio = Storage::files('visitantes');
            $link = "https://sea-production-2d37.up.railway.app/storage/storage/" . $directorio[$id];
            return response()->download($link);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            return response()->json([
                "isRequest" => true,
                "isSuccess" => false,
                "isMessageError" => true,
                "message" => $message,
                "messageError" => "",
                "data" => [],
                "statusCode" => $code
            ]);
        }
    }
}