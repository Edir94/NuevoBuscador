<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tema;
use App\PalabraClave;
use App\PautaRadio;
use App\PautaTv;
use App\PautaPrensa;
use App\PautaInternet;

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
        $user= Auth::user()->id;

        $fecha = date('Y-m-d');

        $tema = PalabraClave::join('temas','palabrasClave.temas_id','temas.id')
                              //->where('palabrasClave.users_id','=',$user)
                              ->select('temas.nombre as nombreTema','temas.id as idTema')
                              ->distinct()
                              ->get();

        /*$palabraClave = PalabraClave::where('users_id','=',$user)
                                      //->where('temas_id','=',$id)
                                      ->select('id','palabraClave', 'temas_id as idTema')
                                      ->get();
        */

        $palabraClave = PalabraClave::select('id','palabraClave', 'temas_id as idTema')
                                      ->get();

        $arrayTextoQuery = array();

        foreach ($palabraClave as $palabra) {
            $texto = $palabra->palabraClave;
            $textoQuery = "";
            $frase = 0;
            $palabras = explode(" ",$texto);
            foreach ($palabras as $palabra) {
                if(strpos($palabra, '"')!==false){
                    if(strpos($palabra,'"')===0){
                        $frase = 1;
                        $textoQuery = $textoQuery.str_replace('"', '+"', $palabra).' ';
                    }else{
                        $textoQuery = $textoQuery.str_replace('"', '" ', $palabra);
                    }
                }else{
                    if($frase == 0){
                        if(strpos($palabra, '-')!==false){
                            if(strpos($palabra, '-')!==0){
                                $textoQuery = $textoQuery.'+"'.str_replace('-', ' ', $palabra).'" ';
                            }else{
                                $textoQuery = $textoQuery.$palabra.' ';
                            }
                        }else{
                            $textoQuery = $textoQuery.'+'.$palabra.' ';
                        }
                    }else{
                        if(strpos($palabra, '-')!==false){
                            $textoQuery = $textoQuery.str_replace('-', ' ', $palabra).' ';
                        }else{
                            $textoQuery = $textoQuery.$palabra.' ';
                        }
                        
                    }
                }
            }
            //echo $textoQuery;
            $arrayTextoQuery[] = "'".$textoQuery."'";
        }

        $pautas = array();

        $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                        ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                        ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautasPrensa.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                $query->orWhereRaw('MATCH(pautasPrensa.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        })
                        ->where('pautasPrensa.fechaPauta','=',$fecha)
                        ->where('pautasPrensa.estado','=',1)
                        ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion as nombrePrograma','pautasPrensa.titular','pautasPrensa.texto')
                        ->get();

        foreach ($pautasPrensa as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasTv.fechaPauta','=',$fecha)
                    ->where(function($query) use ($arrayTextoQuery){
                                foreach ($arrayTextoQuery as $texto) {
                                    $query->orWhereRaw('MATCH(pautasTv.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                    $query->orWhereRaw('MATCH(pautasTv.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                }
                            })
                    ->where('pautasTv.estado','=',1)
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular','pautasTv.texto')
                    ->get();

        foreach ($pautasTv as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasRadio.fechaPauta','=',$fecha)
                    ->where(function($query) use ($arrayTextoQuery){
                        foreach ($arrayTextoQuery as $texto) {
                            $query->orWhereRaw('MATCH(pautasRadio.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            $query->orWhereRaw('MATCH(pautasRadio.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                        }
                    })
                    ->where('pautasRadio.estado','=',1)
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular','pautasRadio.texto')
                    ->get();

        
        foreach ($pautasRadio as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                        ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautasInternet.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                $query->orWhereRaw('MATCH(pautasInternet.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        })
                        ->where('pautasInternet.fechaPauta','=',$fecha)
                        ->where('pautasInternet.estado','=',1)
                        ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular','pautasInternet.texto')
                        ->get();

        foreach ($pautasInternet as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>"", 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        return view("favoritos.index2",['temas'=>$tema,'palabrasClave'=>$palabraClave,'pautas'=>$pautas]);
    }

    public function buscarxPC(Request $request){
        $textoCompleto = $request->palabraClave;
        $periodo = $request->periodo;
        $textoQuery = "";
        $frase = 0;
        $palabras = explode(" ",$textoCompleto);
        foreach ($palabras as $palabra) {
            if(strpos($palabra, '"')!==false){
                if(strpos($palabra,'"')===0){
                    $frase = 1;
                    $textoQuery = $textoQuery.str_replace('"', '+"', $palabra).' ';
                }else{
                    $textoQuery = $textoQuery.str_replace('"', '" ', $palabra);
                }
            }else{
                if($frase == 0){
                    if(strpos($palabra, '-')!==false){
                        if(strpos($palabra, '-')!==0){
                            $textoQuery = $textoQuery.'+"'.str_replace('-', ' ', $palabra).'" ';
                        }else{
                            $textoQuery = $textoQuery.$palabra.' ';
                        }
                    }else{
                        $textoQuery = $textoQuery.'+'.$palabra.' ';
                    }
                }else{
                    if(strpos($palabra, '-')!==false){
                        $textoQuery = $textoQuery.str_replace('-', ' ', $palabra).' ';
                    }else{
                        $textoQuery = $textoQuery.$palabra.' ';
                    }
                    
                }
            }
        }
        $texto = "'".$textoQuery."'";

        switch ($periodo) {
            case '0':
                $fechaInicio = $fechaFin = date('Y-m-d');
                break;
            case '1':
                $fechaInicio = $fechaFin = date("Y-m-d",strtotime(date('Y-m-d')."- 1 days"));
                break;
            case '2':
                $fechaFin = date('Y-m-d');
                $fechaInicio = date("Y-m-d",strtotime($fechaFin."- 7 days"));
                break;
            case '3':
                $fechaFin = date('Y-m-d');
                $fechaInicio = date("Y-m-d",strtotime($fechaFin."- 30 days"));
                break;
            case '4':
                list($diaI,$mesI,$añoI) = explode('/',$request->fechaInicio);
                list($diaF,$mesF,$añoF) = explode('/',$request->fechaFin);
                $fechaInicio = date($añoI.'-'.$mesI.'-'.$diaI);
                $fechaFin = date($añoF.'-'.$mesF.'-'.$diaF);
                break;
            default:
                break;
        }

        $pautas = array();

        $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                        ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                        ->where(function($query) use ($texto){
                                $query->orWhereRaw('MATCH(pautasPrensa.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                $query->orWhereRaw('MATCH(pautasPrensa.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                        })
                        ->whereBetween('pautasPrensa.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasPrensa.estado','=',1)
                        ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion as nombrePrograma','pautasPrensa.titular','pautasPrensa.texto')
                        ->get();

        foreach ($pautasPrensa as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->whereBetween('pautasTv.fechaPauta',[$fechaInicio,$fechaFin])
                    ->where(function($query) use($texto){
                        $query->orWhereRaw('MATCH(pautasTv.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                        $query->orWhereRaw('MATCH(pautasTv.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                    })
                    ->where('pautasTv.estado','=',1)
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular','pautasTv.texto')
                    ->get();

        foreach ($pautasTv as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->whereBetween('pautasRadio.fechaPauta',[$fechaInicio,$fechaFin])
                    ->where(function($query) use($texto){
                        $query->orWhereRaw('MATCH(pautasRadio.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                        $query->orWhereRaw('MATCH(pautasRadio.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                    })
                    ->where('pautasRadio.estado','=',1)
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular','pautasRadio.texto')
                    ->get();

        
        foreach ($pautasRadio as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                        ->where(function($query) use ($texto){
                                $query->orWhereRaw('MATCH(pautasInternet.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                $query->orWhereRaw('MATCH(pautasInternet.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                        })
                        ->whereBetween('pautasInternet.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasInternet.estado','=',1)
                        ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular','pautasInternet.texto')
                        ->get();

        foreach ($pautasInternet as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>"", 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        return response()->json($pautas);
    }

    public function buscarxPeriodo(Request $request){

        $periodo = $request->periodo;

        switch ($periodo) {
            case '0':
                $fechaInicio = $fechaFin = date('Y-m-d');
                break;
            case '1':
                $fechaInicio = $fechaFin = date("Y-m-d",strtotime(date('Y-m-d')."- 1 days"));
                break;
            case '2':
                $fechaFin = date('Y-m-d');
                $fechaInicio = date("Y-m-d",strtotime($fechaFin."- 7 days"));
                break;
            case '3':
                $fechaFin = date('Y-m-d');
                $fechaInicio = date("Y-m-d",strtotime($fechaFin."- 30 days"));
                break;
            case '4':
                list($diaI,$mesI,$añoI) = explode('/',$request->fechaInicio);
                list($diaF,$mesF,$añoF) = explode('/',$request->fechaFin);
                $fechaInicio = date($añoI.'-'.$mesI.'-'.$diaI);
                $fechaFin = date($añoF.'-'.$mesF.'-'.$diaF);
                break;
            default:
                break;
        }

        $palabraClave = PalabraClave::select('id','palabraClave', 'temas_id as idTema')
                                      ->get();

        $arrayTextoQuery = array();

        foreach ($palabraClave as $palabra) {
            $texto = $palabra->palabraClave;
            $textoQuery = "";
            $frase = 0;
            $palabras = explode(" ",$texto);
            foreach ($palabras as $palabra) {
                if(strpos($palabra, '"')!==false){
                    if(strpos($palabra,'"')===0){
                        $frase = 1;
                        $textoQuery = $textoQuery.str_replace('"', '+"', $palabra).' ';
                    }else{
                        $textoQuery = $textoQuery.str_replace('"', '" ', $palabra);
                    }
                }else{
                    if($frase == 0){
                        if(strpos($palabra, '-')!==false){
                            if(strpos($palabra, '-')!==0){
                                $textoQuery = $textoQuery.'+"'.str_replace('-', ' ', $palabra).'" ';
                            }else{
                                $textoQuery = $textoQuery.$palabra.' ';
                            }
                        }else{
                            $textoQuery = $textoQuery.'+'.$palabra.' ';
                        }
                    }else{
                        if(strpos($palabra, '-')!==false){
                            $textoQuery = $textoQuery.str_replace('-', ' ', $palabra).' ';
                        }else{
                            $textoQuery = $textoQuery.$palabra.' ';
                        }
                        
                    }
                }
            }
            //echo $textoQuery;
            $arrayTextoQuery[] = "'".$textoQuery."'";
        }

        $pautas = array();

        $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                        ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                        ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautasPrensa.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                $query->orWhereRaw('MATCH(pautasPrensa.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        })
                        ->whereBetween('pautasPrensa.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasPrensa.estado','=',1)
                        ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion as nombrePrograma','pautasPrensa.titular','pautasPrensa.texto')
                        ->get();

        foreach ($pautasPrensa as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->whereBetween('pautasTv.fechaPauta',[$fechaInicio,$fechaFin])
                    ->where(function($query) use ($arrayTextoQuery){
                                foreach ($arrayTextoQuery as $texto) {
                                    $query->orWhereRaw('MATCH(pautasTv.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                    $query->orWhereRaw('MATCH(pautasTv.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                }
                            })
                    ->where('pautasTv.estado','=',1)
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular','pautasTv.texto')
                    ->get();

        foreach ($pautasTv as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->whereBetween('pautasRadio.fechaPauta',[$fechaInicio,$fechaFin])
                    ->where(function($query) use ($arrayTextoQuery){
                        foreach ($arrayTextoQuery as $texto) {
                            $query->orWhereRaw('MATCH(pautasRadio.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            $query->orWhereRaw('MATCH(pautasRadio.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                        }
                    })
                    ->where('pautasRadio.estado','=',1)
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular','pautasRadio.texto')
                    ->get();

        
        foreach ($pautasRadio as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>$pauta->nombrePrograma, 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                        ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautasInternet.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                $query->orWhereRaw('MATCH(pautasInternet.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        })
                        ->whereBetween('pautasInternet.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasInternet.estado','=',1)
                        ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular','pautasInternet.texto')
                        ->get();

        foreach ($pautasInternet as $pauta) {
            $pautas[] = ['id'=>$pauta->id, 'fechaPauta'=>$pauta->fechaPauta, 'tipoPauta'=>$pauta->tipoPauta, 'nombreMedio'=>$pauta->nombreMedio, 'nombrePrograma'=>"", 'titular'=>$pauta->titular,'texto'=>$pauta->texto];
            //$pautas[] = $pauta;
        }

        return response()->json($pautas);
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
        phpinfo();
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
