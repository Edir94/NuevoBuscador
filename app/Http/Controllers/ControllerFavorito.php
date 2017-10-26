<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tema;
use App\PalabraClave;

use Auth;

class ControllerFavorito extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        //$temas = Tema::all();
        //echo $temas;
        return view("favoritos.index");
    }

    public function mostrarTema(){

        $user= Auth::user()->id;

        $tema = PalabraClave::join('temas','palabrasClave.temas_id','temas.id')
                              ->where('palabrasClave.users_id','=',$user)
                              ->select('temas.nombre as nombreTema','temas.id as idTema')
                              ->distinct()
                              ->get();


        return Response()->json($tema);

    }


    public function mostrarPalabraClave($id){

        $user= Auth::user()->id;

        $palabraClave = PalabraClave::where('users_id','=',$user)
                                      ->where('temas_id','=',$id)
                                      ->select('id','palabraClave', 'temas_id as idTema')
                                      ->get();

        return Response()->json($palabraClave);

        print_r($palabraClave);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
