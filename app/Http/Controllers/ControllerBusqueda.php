<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PautaPrensa;
use App\PautaTv;
use App\PautaRadio;
use App\PautaInternet;
use Response;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class ControllerBusqueda extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function busquedaAvanzada(Request $request)
    {
        $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                    ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                    ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion','pautasPrensa.titular')
                    ->get();
        $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular')
                    ->get();
        $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular')
                    ->get();
        $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                    ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular')
                    ->get();

        $dataResponse = new Collection;
        foreach ($pautasPrensa as $pautaPrensa) {
            $dataResponse->push(['id'=>$pautaPrensa->id, 'fechaPauta'=>$pautaPrensa->fechaPauta, 'tipoPauta'=>$pautaPrensa->tipoPauta, 'nombreMedio'=>$pautaPrensa->nombreMedio, 'nombreSP'=>$pautaPrensa->nombreSeccion, 'titular'=>$pautaPrensa->titular]);
        }
        foreach ($pautasTv as $pautaTv) {
            $dataResponse->push(['id'=>$pautaTv->id, 'fechaPauta'=>$pautaTv->fechaPauta, 'tipoPauta'=>$pautaTv->tipoPauta, 'nombreMedio'=>$pautaTv->nombreMedio, 'nombreSP'=>$pautaTv->nombrePrograma, 'titular'=>$pautaTv->titular]);
        }
        foreach ($pautasRadio as $pautaRadio) {
            $dataResponse->push(['id'=>$pautaRadio->id, 'fechaPauta'=>$pautaRadio->fechaPauta, 'tipoPauta'=>$pautaRadio->tipoPauta, 'nombreMedio'=>$pautaRadio->nombreMedio, 'nombreSP'=>$pautaRadio->nombrePrograma, 'titular'=>$pautaRadio->titular]);
        }
        foreach ($pautasInternet as $pautaInternet) {
            $dataResponse->push(['id'=>$pautaInternet->id, 'fechaPauta'=>$pautaInternet->fechaPauta, 'tipoPauta'=>$pautaInternet->tipoPauta, 'nombreMedio'=>$pautaInternet->nombreMedio, 'nombreSP'=>"", 'titular'=>$pautaInternet->titular]);
        }

        //return Response::json($pautasPrensa);
        //return response()->json($dataResponse, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        return DataTables::of($dataResponse)->make(true);
    }
}
