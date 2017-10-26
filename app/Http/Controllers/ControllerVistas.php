<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PautaPrensa;
use App\PautaTv;
use App\PautaRadio;
use App\PautaInternet;

class ControllerVistas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPrensa($id)
    {
        $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                    ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                    ->where('pautasPrensa.id','=',$id)
                    ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion','pautasPrensa.titular','pautasPrensa.texto')
                    ->get();
                    //echo $pautasPrensa;

        return view('vistas.vistaPrensa',['pautasPrensa'=>$pautasPrensa]);
    }

    public function indexTv($id)
    {
        $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasTv.id','=',$id)
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular','pautasTv.texto')
                    ->get();

        return view('vistas.vistaTv',['pautasTv'=>$pautasTv]);
    }

    public function indexRadio($id)
    {
        $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasRadio.id','=',$id)
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular','pautasRadio.texto')
                    ->get();

        return view('vistas.vistaRadio',['pautasRadio'=>$pautasRadio]);
    }

    public function indexInternet($id)
    {
        $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                    ->where('pautasInternet.id','=',$id)
                    ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular','pautasInternet.texto','pautasInternet.rutaImagen')
                    ->get();

        return view('vistas.vistaInternet',['pautasInternet'=>$pautasInternet]);
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
