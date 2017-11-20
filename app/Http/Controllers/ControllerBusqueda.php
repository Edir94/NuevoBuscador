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
use App\SeccionPrensa;

use App\PalabraClave;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;

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

        session()->put('arrayBusqueda',$dataResponse);
        session()->put('arrayFiltrado',$dataResponse);
        //return response()->json($dataResponse, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        return DataTables::of($dataResponse)
            ->addColumn('opciones',function($dataResponse){
                $id = $dataResponse['id'];
                $tipoPauta = $dataResponse['tipoPauta'];
                return '<div align="center">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Abrir Pauta">
                                <button value="'.$tipoPauta."-".$id.'" onclick="AbrirPauta(this);" class="btn btn-sm btn-info Imagen" data-toggle="modal" data-target="#" data-id="'.$id.'"><i class="glyphicon glyphicon-globe"></i></button>
                            </a>
                        </div>';
            })
            ->addColumn('checkPauta',function($dataResponse){
                $id = $dataResponse['id'];
                $tipoPauta = $dataResponse['tipoPauta'];
                return '<div align="center">
                            <input type="checkbox" name="checkPauta" id="checkPauta" value="'.$tipoPauta."-".$id.'" checked="true">
                        </div>';
            })
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,35)}}</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,8)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,10)}}</span>')
            ->rawColumns(['opciones','checkPauta','nombreMedio','nombreSP','titular'])
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
        
        session()->put('arrayBusqueda',$dataResponse);
        session()->put('arrayFiltrado',$dataResponse);
        //return response()->json($dataResponse, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        return DataTables::of($dataResponse)
            ->addColumn('opciones',function($dataResponse){
                $id = $dataResponse['id'];
                $tipoPauta = $dataResponse['tipoPauta'];
                return '<div align="center">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Abrir Pauta">
                                <button value="'.$tipoPauta."-".$id.'" onclick="AbrirPauta(this);" class="btn btn-sm btn-info Imagen" data-toggle="modal" data-target="#" data-id="'.$id.'"><i class="glyphicon glyphicon-globe"></i></button>
                            </a>
                        </div>';
            })
            ->addColumn('checkPauta',function($dataResponse){
                $id = $dataResponse['id'];
                $tipoPauta = $dataResponse['tipoPauta'];
                return '<div align="center">
                            <input type="checkbox" name="checkPauta" id="checkPauta" value="'.$tipoPauta."-".$id.'" checked="true">
                        </div>';
            })
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,35)}}</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,8)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,10)}}</span>')
            ->rawColumns(['opciones','checkPauta','nombreMedio','nombreSP','titular'])
            ->make(true);
    }

    public function autocompleteMedios($id){
        $array=array();

        if(strpos($id,"2")!==false){
            $mediosTv =MedioAV::where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('nombreMedio','like','%'.$term.'%');
                })
                ->where('tipoMedios_id','=',2)
                ->select("id","nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosTv as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
              }
        }
        if(strpos($id,"3")!==false){
            $mediosRadio =MedioAV::where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('nombreMedio','like','%'.$term.'%');
                })
                ->where('tipoMedios_id','=',3)
                ->select("id","nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosRadio as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
              }
        }
        if(strpos($id,"1")!==false){
            $mediosPrensa =MedioPrensa::where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('nombreMedio','like','%'.$term.'%');
                })
                ->select("id","nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosPrensa as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
            }
        }
        if(strpos($id,"4")!==false){
            $mediosInternet =MedioInternet::where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('nombreMedio','like','%'.$term.'%');
                })
                ->select("id","nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosInternet as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
            }
        }
        if (count($array)) {
            return Response::json($array);
        }else{
            return ['id'=>'','value'=>''];
        }
    }

    public function cargarMediosFiltrado(){
        $nombreMedios = array();
        $arrayBusqueda = session()->get('arrayFiltrado');
        foreach ($arrayBusqueda as $medio) {
            $nombreMedios[] = $medio['nombreMedio'];
        }
        $nombreMedios = array_unique($nombreMedios);
        //session()->put('arrayMedios',$nombreMedios);
        return response()->json($nombreMedios, 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function filtradoRapido(Request $request)
    {
        $arrayBusqueda = session()->get('arrayBusqueda');

        $filtroPrensa = $request->filtroPrensa;
        $filtroTv = $request->filtroTv;
        $filtroRadio = $request->filtroRadio;
        $filtroInternet = $request->filtroInternet;
        $arrayMedios = $request->arrayMedios;

        $arrayFiltrado = new Collection;

        foreach ($arrayBusqueda as $pauta) {
            if(count($arrayMedios)==0){
                if($pauta['tipoPauta']=='Prensa'){
                    if($filtroPrensa=='true'){
                        $arrayFiltrado->push($pauta);
                    }
                }
                if($pauta['tipoPauta']=='Televisión'){
                    if($filtroTv=='true'){
                        $arrayFiltrado->push($pauta);
                    }
                }
                if($pauta['tipoPauta']=='Radio'){
                    if($filtroRadio=='true'){
                        $arrayFiltrado->push($pauta);
                    }
                }
                if($pauta['tipoPauta']=='Internet'){
                    if($filtroInternet=='true'){
                        $arrayFiltrado->push($pauta);
                    }
                }
            }else{
                $i=0;
                $verifica=true;
                while ($i<count($arrayMedios) && $verifica) {
                    if(strcmp($pauta['nombreMedio'], $arrayMedios[$i])==0){
                        $verifica = false;
                        $i = count($arrayMedios);
                    }
                    $i++;
                }
                if($pauta['tipoPauta']=='Prensa'){
                    if($filtroPrensa=='true'){
                        if($verifica){
                            $arrayFiltrado->push($pauta);
                        }
                    }
                }
                if($pauta['tipoPauta']=='Televisión'){
                    if($filtroTv=='true'){
                        if($verifica){
                            $arrayFiltrado->push($pauta);
                        }
                    }
                }
                if($pauta['tipoPauta']=='Radio'){
                    if($filtroRadio=='true'){
                        if($verifica){
                            $arrayFiltrado->push($pauta);
                        }
                    }
                }
                if($pauta['tipoPauta']=='Internet'){
                    if($filtroInternet=='true'){
                        if($verifica){
                            $arrayFiltrado->push($pauta);
                        }
                    }
                }
            }
        }
        session()->put('arrayFiltrado',$arrayFiltrado);
        return DataTables::of($arrayFiltrado)
            ->addColumn('opciones',function($arrayFiltrado){
                $id = $arrayFiltrado['id'];
                $tipoPauta = $arrayFiltrado['tipoPauta'];
                return '<div align="center">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Abrir Pauta">
                                <button value="'.$tipoPauta."-".$id.'" onclick="AbrirPauta(this);" class="btn btn-sm btn-info Imagen" data-toggle="modal" data-target="#" data-id="'.$id.'"><i class="glyphicon glyphicon-globe"></i></button>
                            </a>
                        </div>';
            })
            ->addColumn('checkPauta',function($arrayFiltrado){
                $id = $arrayFiltrado['id'];
                $tipoPauta = $arrayFiltrado['tipoPauta'];
                return '<div align="center">
                            <input type="checkbox" name="checkPauta" id="checkPauta" value="'.$tipoPauta."-".$id.'" checked="true">
                        </div>';
            })
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,35)}}</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,8)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,10)}}</span>')
            ->rawColumns(['opciones','checkPauta','nombreMedio','nombreSP','titular'])
            ->make(true);
    }

    public function exportarExcel(Request $request)
    {
        $select = "";
        if($request->checkFecha == 1){
            $select = $select."?fechaPauta as Fecha,";
        }
        if($request->checkTipoPauta == 1){
            $select = $select."?tipoPauta as TipoPauta,";
        }
        if($request->checkMedio == 1){
            $select = $select."123,";
        }
        if($request->checkPrograma == 1){
            $select = $select."456,";
        }
        if($request->checkTitular == 1){
            $select = $select."?titular as Titular,";
        }
        if($request->checkTexto == 1){
            $select = $select."?texto as Texto,";
        }
        if($request->checkEquivalencia == 1){
            $select = $select."?equivalencia as Equivalencia";
        }
        if(substr($select, -1)==","){
            $select = substr($select, 0,(strlen($select)-1));
        }
        $arrayFiltrado = session()->get('arrayFiltrado');
        $arrayPrensa = array();
        $arrayTv = array();
        $arrayRadio = array();
        $arrayInternet = array();
        foreach ($arrayFiltrado as $pauta) {
            if($pauta['tipoPauta']=='Prensa'){
                $arrayPrensa[] = $pauta['id'];
            }
            if($pauta['tipoPauta']=='Televisión'){
                $arrayTv[] = $pauta['id'];
            }
            if($pauta['tipoPauta']=='Radio'){
                $arrayRadio[] = $pauta['id'];
            }
            if($pauta['tipoPauta']=='Internet'){
                $arrayInternet[] = $pauta['id'];
            }
        }

        $selectPrensa = str_replace("?","pautasPrensa.", $select);
        $selectTv = str_replace("?","pautasTv.", $select);
        $selectRadio = str_replace("?","pautasRadio.", $select);
        $selectInternet = str_replace("?","pautasInternet.", $select);

        $selectPrensa = str_replace("123","mediosPrensa.nombreMedio as Medio", $selectPrensa);
        $selectTv = str_replace("123","mediosAV.nombreMedio as Medio", $selectTv);
        $selectRadio = str_replace("123","mediosAV.nombreMedio as Medio", $selectRadio);
        $selectInternet = str_replace("123","mediosInternet.nombreMedio as Medio", $selectInternet);

        $selectPrensa = str_replace("456","seccionesPrensa.nombreSeccion as ProgSec", $selectPrensa);
        $selectTv = str_replace("456","programasAV.nombrePrograma as ProgSec", $selectTv);
        $selectRadio = str_replace("456","programasAV.nombrePrograma as ProgSec", $selectRadio);
        $selectInternet = str_replace("456,","", $selectInternet);

        $resultadosPrensa = PautaPrensa::join('seccionesPrensa','pautasPrensa.seccionesPrensa_id','seccionesPrensa.id')
                        ->join('mediosPrensa','seccionesPrensa.mediosPrensa_id','mediosPrensa.id')
                        ->whereIn('pautasPrensa.id',$arrayPrensa)->selectRaw($selectPrensa)->get();

        $resultadosTv = PautaTv::join('programasAV','pautasTv.programasAV_id','programasAV.id')
                        ->join('mediosAV','programasAV.mediosAV_id','mediosAV.id')
                        ->whereIn('pautasTv.id',$arrayTv)->selectRaw($selectTv)->get();

        $resultadosRadio = PautaRadio::join('programasAV','pautasRadio.programasAV_id','programasAV.id')
                        ->join('mediosAV','programasAV.mediosAV_id','mediosAV.id')
                        ->whereIn('pautasRadio.id',$arrayRadio)->selectRaw($selectRadio)->get();

        $resultadosInternet = PautaInternet::join('mediosInternet','pautasInternet.mediosInternet_id','mediosInternet.id')
                        ->whereIn('pautasInternet.id',$arrayInternet)->selectRaw($selectInternet)->get();

        return Excel::create("ExcelNoticias",function($excel) use ($resultadosPrensa,$resultadosTv,$resultadosRadio,$resultadosInternet){
            $excel->setTitle("Title");
            $excel->sheet("Hoja 1",function($sheet) use ($resultadosPrensa,$resultadosTv,$resultadosRadio,$resultadosInternet){
                $sheet->fromArray($resultadosPrensa);
                $sheet->fromArray($resultadosTv);
                $sheet->fromArray($resultadosRadio);
                $sheet->fromArray($resultadosInternet);
                $sheet->row(1,function($row){
                    $row->setBackground('#33BEFF');
                    $row->setFontWeight('bold');
                });

            });
        })->download("xls");
        
    }

    /*public function importarMediosPrensa24(){
        $medios24 = DB::connection('mysql_24_prensa')->table('tbl_medio')
                    ->whereIn('idtipo_medio',[1,2])
                    ->select('idmedio as id','nombre as nombreMedio','idtipo_medio as subTipoMedio')
                    ->get();

        $arrayMedios = array();

        foreach ($medios24 as $medio24) {
            $medioPrensa = new MedioPrensa();
            $medioPrensa->id = $medio24->id;
            $medioPrensa->nombreMedio = $medio24->nombreMedio;
            if($medio24->subTipoMedio == 1){
                $medioPrensa->subTipoMedio = 'Diario';
            }else{
                $medioPrensa->subTipoMedio = 'Revista';
            }
            $medioPrensa->tipoMedios_id = 1;
            $medioPrensa->save();

            $arrayMedios[] = $medioPrensa->nombreMedio;
        }

        return response()->json($arrayMedios);
    }

    public function importarSeccionesPrensa24(){
        $medios24 = DB::connection('mysql_24_prensa')->table('tbl_seccion')
                    ->select('idseccion','nombre','estado')
                    ->get();

        $arrayMedios = array();
        echo count($medios24).'</br>';

        foreach ($medios24 as $medio24) {
            $medioPrensa = new SeccionPrensa();
            $medioPrensa->id = $medio24->idseccion;
            $medioPrensa->nombreSeccion = $medio24->nombre;
            $medioPrensa->estado = $medio24->estado;
            $medioPrensa->save();

            $arrayMedios[] = $medioPrensa->nombreSeccion;
        }

        return response()->json($arrayMedios);
    }

    public function importarPautasPrensa24(){
        $pautas24 = DB::connection('mysql_24_prensa')->table('tbl_pauta_prensa')
                    ->where('fecha','>=','2017-09-01')
                    ->select('idpauta_prensa','fecha','pagina','idseccion','titular','idmedio','fecha_registro','codigo','estado','tipo_servicio','texto2','');
    }*/

}