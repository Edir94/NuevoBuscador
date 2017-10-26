<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Input;

use App\PautaPrensa;
use App\PautaTv;
use App\PautaRadio;
use App\PautaInternet;
use App\MedioAV;
use App\MedioPrensa;
use App\MedioInternet;

use App\PalabraClave;
use Auth;

class ControllerBusqueda extends Controller
{
    private $arrayBusqueda;
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
        $fecha = date('Y-m-d');
        $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                    ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                    ->where('pautasPrensa.fechaPauta','=',$fecha)
                    ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion','pautasPrensa.titular')
                    ->get();
        $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasTv.fechaPauta','=',$fecha)
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular')
                    ->get();
        $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasRadio.fechaPauta','=',$fecha)
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular')
                    ->get();
        $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                    ->where('pautasInternet.fechaPauta','=',$fecha)
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

        $this->arrayBusqueda = $dataResponse;
        //echo "hola".$this->arrayBusqueda;
        //return response()->json($dataResponse, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        return DataTables::of($dataResponse)
            ->addColumn('opciones',function($dataResponse){
                $id = $dataResponse['id'];
                $tipoPauta = $dataResponse['tipoPauta'];
                return '<div align="center">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Abrir Pauta">
                                <button value="'.$tipoPauta."-".$id.'" onclick="AbrirPauta(this);" class="btn btn-sm btn-info Imagen" data-toggle="modal" data-target="#myCliente" data-id="'.$id.'"><i class="glyphicon glyphicon-globe"></i></button>
                            </a>
                        </div>';
            })
            ->rawColumns(['opciones'])
            ->editColumn('titular','{{substr($titular,0,40)}} ')
            ->make(true);
    }

    public function busquedaAvanzada2(Request $request)
    {
        $fechaInicio = $request->fechaInicio;
        $diaInicio = substr($fechaInicio,0,2);
        $mesInicio = substr($fechaInicio,3,2);
        $añoInicio = substr($fechaInicio,6,4);
        $fechaInicio = $añoInicio."-".$mesInicio."-".$diaInicio;
        $fechaFin = $request->fechaFin;
        $diaFin = substr($fechaFin,0,2);
        $mesFin = substr($fechaFin,3,2);
        $añoFin = substr($fechaFin,6,4);
        $fechaFin = $añoFin."-".$mesFin."-".$diaFin;
        $textoBusqueda = $request->textos;
        $arrayTextos = explode(', ', $textoBusqueda);
        $arrayTextos = array_unique($arrayTextos);
        $mediosBusqueda = $request->medios;
        $arrayMedios = explode(', ', $mediosBusqueda);
        $arrayMedios = array_unique($arrayMedios);
        $checkPrensa = $request->checkPrensa;
        $checkTv = $request->checkTv;
        $checkRadio = $request->checkRadio;
        $checkInternet = $request->checkInternet;

        $arrayTextoQuery = array();
        
        if($textoBusqueda != ""){
            foreach ($arrayTextos as $texto) {
                $textoQuery = "";
                $frase = 0;
                $palabras = explode(" ",$texto);
                foreach ($palabras as $palabra) {
                    if(strpos("(",$palabra)!==false){
                        $frase = 1;
                        $textoQuery = $textoQuery.'+"'.str_replace('(', '', $palabra).' ';
                    }else{
                        if($frase == 1){
                            if(strpos(")",$palabra)!==false){
                                $textoQuery = $textoQuery.str_replace(')', '', $palabra).'" ';
                                $frase = 0;
                            }else{
                                $textoQuery = $textoQuery.$palabra.' ';
                            }
                        }else{
                            if(strpos("-",$palabra)!==false){
                                $textoQuery = $textoQuery.str_replace('-', '-"',$palabra).'" ';
                            }else{
                                $textoQuery = $textoQuery.'+"'.$palabra.'" ';
                            }
                            
                        }
                    }
                }
                $arrayTextoQuery[] = $textoQuery;
            }
        }

        if(count($arrayMedios)==0){
            $filtroMedios = false;
        }else{
            $filtroMedios = true;
        }
        $mediosAV = MedioAV::where(function($query) use ($arrayMedios){
                        foreach ($arrayMedios as $medio) {
                            $query->orwhere('nombreMedio','like','%'.$medio.'%');
                        }
                    })
                    ->select('id')->get();
                    //echo $mediosAV;
        $mediosPrensa = MedioPrensa::where(function($query) use ($arrayMedios){
            foreach ($arrayMedios as $medio) {
                $query->orwhere('nombreMedio','like','%'.$medio.'%');
            }
        })
        ->select('id')->get();

        $mediosInternet = MedioInternet::where(function($query) use ($arrayMedios){
            foreach ($arrayMedios as $medio) {
                $query->orwhere('nombreMedio','like','%'.$medio.'%');
            }
        })
        ->select('id')->get();

        $dataResponse = new Collection;

        if((!$filtroMedios || count($mediosPrensa)!=0) && $checkPrensa=='true'){
            $pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                        ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                        ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                //$query->orwhere('pautasPrensa.texto','like','%'.$texto.'%');
                                $query->orWhereRaw('MATCH(pautasPrensa.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                //$query->orwhere('pautasPrensa.titular','like','%'.$texto.'%');
                                $query->orWhereRaw('MATCH(pautasPrensa.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        })
                        ->where(function($query) use ($mediosPrensa){
                            if(count($mediosPrensa)!=0){
                                $query->whereIn('mediosPrensa.id',$mediosPrensa);
                            }
                        })
                        ->whereBetween('pautasPrensa.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasPrensa.estado','=',1)
                        ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion','pautasPrensa.titular')
                        ->get();

            foreach ($pautasPrensa as $pautaPrensa) {
                $dataResponse->push(['id'=>$pautaPrensa->id, 'fechaPauta'=>$pautaPrensa->fechaPauta, 'tipoPauta'=>$pautaPrensa->tipoPauta, 'nombreMedio'=>$pautaPrensa->nombreMedio, 'nombreSP'=>$pautaPrensa->nombreSeccion, 'titular'=>$pautaPrensa->titular]);
            }
        }//end if
        if(!$filtroMedios || count($mediosAV)!=0){
            if($checkTv=='true'){
                $pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                            ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                            ->where(function($query) use ($arrayTextoQuery){
                                foreach ($arrayTextoQuery as $texto) {
                                    //$query->orwhere('pautasTv.texto','like','%'.$texto.'%');
                                    $query->orWhereRaw('MATCH(pautasTv.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                    //$query->orwhere('pautasTv.titular','like','%'.$texto.'%');
                                    $query->orWhereRaw('MATCH(pautasTv.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                }
                            })
                            ->where(function($query) use ($mediosAV){
                                if(count($mediosAV)!=0){
                                    $query->whereIn('mediosAV.id',$mediosAV);
                                }
                            })
                            ->whereBetween('pautasTv.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasTv.estado','=',1)
                            ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular')
                            ->get();

                foreach ($pautasTv as $pautaTv) {
                    $dataResponse->push(['id'=>$pautaTv->id, 'fechaPauta'=>$pautaTv->fechaPauta, 'tipoPauta'=>$pautaTv->tipoPauta, 'nombreMedio'=>$pautaTv->nombreMedio, 'nombreSP'=>$pautaTv->nombrePrograma, 'titular'=>$pautaTv->titular]);
                }
            }
            if($checkRadio=='true'){
                $pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                            ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                            ->where(function($query) use ($arrayTextoQuery){
                                foreach ($arrayTextoQuery as $texto) {
                                    //$query->orwhere('pautasRadio.texto','like','%'.$texto.'%');
                                    $query->orWhereRaw('MATCH(pautasRadio.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                    //$query->orwhere('pautasRadio.titular','like','%'.$texto.'%');
                                    $query->orWhereRaw('MATCH(pautasRadio.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                }
                            })
                            ->where(function($query) use ($mediosAV){
                                if(count($mediosAV)!=0){
                                    $query->whereIn('mediosAV.id',$mediosAV);
                                }
                            })
                            ->whereBetween('pautasRadio.fechaPauta',[$fechaInicio,$fechaFin])
                            ->where('pautasRadio.estado','=',1)
                            ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular')
                            ->get();

                foreach ($pautasRadio as $pautaRadio) {
                    $dataResponse->push(['id'=>$pautaRadio->id, 'fechaPauta'=>$pautaRadio->fechaPauta, 'tipoPauta'=>$pautaRadio->tipoPauta, 'nombreMedio'=>$pautaRadio->nombreMedio, 'nombreSP'=>$pautaRadio->nombrePrograma, 'titular'=>$pautaRadio->titular]);
                }
            }
        }//end if
        if((!$filtroMedios || count($mediosInternet)!=0) && $checkInternet=='true'){
            $pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                        ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                //$query->orwhere('pautasInternet.texto','like','%'.$texto.'%');
                                $query->orWhereRaw('MATCH(pautasInternet.texto) AGAINST('.$texto.' IN BOOLEAN MODE)');
                                //$query->orwhere('pautasInternet.titular','like','%'.$texto.'%');
                                $query->orWhereRaw('MATCH(pautasInternet.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        })
                        ->where(function($query) use ($mediosInternet){
                            if(count($mediosInternet)!=0){
                                $query->whereIn('mediosInternet.id',$mediosInternet);
                            }
                        })
                        ->whereBetween('pautasInternet.fechaPauta',[$fechaInicio,$fechaFin])
                        ->where('pautasInternet.estado','=',1)
                        ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular')
                        ->get();
            foreach ($pautasInternet as $pautaInternet) {
                $dataResponse->push(['id'=>$pautaInternet->id, 'fechaPauta'=>$pautaInternet->fechaPauta, 'tipoPauta'=>$pautaInternet->tipoPauta, 'nombreMedio'=>$pautaInternet->nombreMedio, 'nombreSP'=>"", 'titular'=>$pautaInternet->titular]);
            }
        }//end if
        
        $this->arrayBusqueda = $dataResponse;
        //echo "hola".$this->arrayBusqueda;
        //return response()->json($dataResponse, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        return DataTables::of($dataResponse)
            ->addColumn('opciones',function($dataResponse){
                $id = $dataResponse['id'];
                $tipoPauta = $dataResponse['tipoPauta'];
                return '<div align="center">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Abrir Pauta">
                                <button value="'.$tipoPauta."-".$id.'" onclick="AbrirPauta(this);" class="btn btn-sm btn-info Imagen" data-toggle="modal" data-target="#myCliente" data-id="'.$id.'"><i class="glyphicon glyphicon-globe"></i></button>
                            </a>
                        </div>';
            })
            ->rawColumns(['opciones'])
            ->editColumn('titular','{{substr($titular,0,40)}} ')
            ->make(true);
    }

    public function autocompleteMedios(){
        $mediosAV =MedioAV::where(function($data)
            { 
              $term= Input::get('term');
              $data->where('nombreMedio','like','%'.$term.'%');
            })
            ->select("id","nombreMedio")
            ->take(6)
            ->get();

        $mediosPrensa =MedioPrensa::where(function($data)
            { 
              $term= Input::get('term');
              $data->where('nombreMedio','like','%'.$term.'%');
            })
            ->select("id","nombreMedio")
            ->take(6)
            ->get();

        $mediosInternet =MedioInternet::where(function($data)
            { 
              $term= Input::get('term');
              $data->where('nombreMedio','like','%'.$term.'%');
            })
            ->select("id","nombreMedio")
            ->take(6)
            ->get();

      $array=array();
      foreach ($mediosAV as $medio) {
        $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
      }
      foreach ($mediosPrensa as $medio) {
        $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
      }
      foreach ($mediosInternet as $medio) {
        $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
      }
      if (count($array)) {
        return Response::json($array);
      }else{
        return ['id'=>'','value'=>''];
      }
    }

    public function filtradoRapido(Request $request)
    {
        
    }
}

