<?php

namespace App\Http\Controllers;

use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipo=TipoUsuario::where("estado",false)->get();
        return response()->json(["tipos"=>$tipo]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
}

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipo= new TipoUsuario();
        $tipo->tipo=$request->tipo;
        if($tipo->save()){
            return response()->json(['message'=>'Registro exitoso'],);
        }else
        return response()->json(['message'=>'Fallo al registrar'],);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoUsuario $tipoUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoUsuario $tipoUsuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoUsuario $tipoUsuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        $tipo=TipoUsuario::find($id);
        if($tipo){
            $tipo->estado=true;
            $tipo->save();
            return response()->json(['message'=>'Eliminado exitoso'],);
        }
        else{
            return response()->json([
                "message"=>"id no existente"
            ],);
        }
    }
    public function listTipoUsuario(){
        $tiposAndUser = DB::table("tipo_usuarios")
        ->join('users', 'tipo_usuarios.id', '=', 'users.tipo_id')
        ->select("users.id", "users.name", "users.email", "tipo_usuarios.id as id_tipo", "tipo_usuarios.tipo")
        ->get();
    
    return response()->json(["tiposAndUser" => $tiposAndUser]);
    
    }
    
        
}
