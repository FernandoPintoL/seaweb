<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Http\Requests\StorePerfilRequest;
use App\Http\Requests\UpdatePerfilRequest;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function query(Request $request){
        $habitantes = Perfil::with('tipoDocumento')->get();
        return response()->json($habitantes);
    }  
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    /* public function createPerfil(StorePerfilRequest $request){
        $perfil = Perfil::create($request);
    } */
    public function store(StorePerfilRequest $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfil $appperfil)
    {
        return response()->json([
                "isRequest"=> true,
                "success" => true,
                "messageError" => false,
                "message" => "consulta completa",
                "data" => $appperfil
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfil $perfil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerfilRequest $request, Perfil $perfil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}