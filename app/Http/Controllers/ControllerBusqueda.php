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
use App\PautaRecorte;
use App\ProgramaAV;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use PHPExcel_Shared_Date;

ini_set('memory_limit','512M');

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
            $dataResponse->push(['id'=>$pautaPrensa->id, 'fechaPauta'=>$pautaPrensa->fechaPauta, 'tipoPauta'=>$pautaPrensa->tipoPauta, 'nombreMedio'=>$pautaPrensa->nombreMedio, 'nombreSP'=>$pautaPrensa->nombreSeccion, 'titular'=>$pautaPrensa->titular,'valor'=>1]);
        }
        foreach ($pautasTv as $pautaTv) {
            $dataResponse->push(['id'=>$pautaTv->id, 'fechaPauta'=>$pautaTv->fechaPauta, 'tipoPauta'=>$pautaTv->tipoPauta, 'nombreMedio'=>$pautaTv->nombreMedio, 'nombreSP'=>$pautaTv->nombrePrograma, 'titular'=>$pautaTv->titular,'valor'=>1]);
        }
        foreach ($pautasRadio as $pautaRadio) {
            $dataResponse->push(['id'=>$pautaRadio->id, 'fechaPauta'=>$pautaRadio->fechaPauta, 'tipoPauta'=>$pautaRadio->tipoPauta, 'nombreMedio'=>$pautaRadio->nombreMedio, 'nombreSP'=>$pautaRadio->nombrePrograma, 'titular'=>$pautaRadio->titular,'valor'=>1]);
        }
        foreach ($pautasInternet as $pautaInternet) {
            $dataResponse->push(['id'=>$pautaInternet->id, 'fechaPauta'=>$pautaInternet->fechaPauta, 'tipoPauta'=>$pautaInternet->tipoPauta, 'nombreMedio'=>$pautaInternet->nombreMedio, 'nombreSP'=>"", 'titular'=>$pautaInternet->titular,'valor'=>1]);
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
                            <input type="checkbox" name="checkPauta" class="checkitem" id="checkPauta" value="'.$tipoPauta."-".$id.'" checked="true" onclick="cambiarValorPauta(this);">
                        </div>';
            })
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,40)}}...</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,12)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,15)}}</span>')
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
                                //echo "aqui";
                                if(strpos($palabra, '-')!==0){
                                    //echo "aquitambien";
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
                $dataResponse->push(['id'=>$pautaPrensa->id, 'fechaPauta'=>$pautaPrensa->fechaPauta, 'tipoPauta'=>$pautaPrensa->tipoPauta, 'nombreMedio'=>$pautaPrensa->nombreMedio, 'nombreSP'=>$pautaPrensa->nombreSeccion, 'titular'=>$pautaPrensa->titular,'valor'=>1]);
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
                    $dataResponse->push(['id'=>$pautaTv->id, 'fechaPauta'=>$pautaTv->fechaPauta, 'tipoPauta'=>$pautaTv->tipoPauta, 'nombreMedio'=>$pautaTv->nombreMedio, 'nombreSP'=>$pautaTv->nombrePrograma, 'titular'=>$pautaTv->titular,'valor'=>1]);
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
                    $dataResponse->push(['id'=>$pautaRadio->id, 'fechaPauta'=>$pautaRadio->fechaPauta, 'tipoPauta'=>$pautaRadio->tipoPauta, 'nombreMedio'=>$pautaRadio->nombreMedio, 'nombreSP'=>$pautaRadio->nombrePrograma, 'titular'=>$pautaRadio->titular,'valor'=>1]);
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
                $dataResponse->push(['id'=>$pautaInternet->id, 'fechaPauta'=>$pautaInternet->fechaPauta, 'tipoPauta'=>$pautaInternet->tipoPauta, 'nombreMedio'=>$pautaInternet->nombreMedio, 'nombreSP'=>"", 'titular'=>$pautaInternet->titular,'valor'=>1]);
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
                            <input type="checkbox" name="checkPauta" class="checkitem" id="checkPauta" value="'.$tipoPauta."-".$id.'" checked="true" onclick="cambiarValorPauta(this);">
                        </div>';
            })
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,40)}}...</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,15)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,15)}}</span>')
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
                            <input type="checkbox" name="checkPauta" class="checkitem" id="checkPauta" value="'.$tipoPauta."-".$id.'" checked="true"  onclick="cambiarValorPauta(this);">
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
        $arrayFiltrado = session()->get('arrayFiltrado');
        $arrayPrensa = array();
        $arrayTv = array();
        $arrayRadio = array();
        $arrayInternet = array();
        foreach ($arrayFiltrado as $pauta) {
            if($pauta['valor']==1){
                if($pauta['tipoPauta']=='Prensa'){
                    $arrayPrensa[] = $pauta['id'];
                }
                if($pauta['tipoPauta']=='Television'){
                    $arrayTv[] = $pauta['id'];
                }
                if($pauta['tipoPauta']=='Radio'){
                    $arrayRadio[] = $pauta['id'];
                }
                if($pauta['tipoPauta']=='Internet'){
                    $arrayInternet[] = $pauta['id'];
                }
            }
        }

        $selectPrensa = "pautasPrensa.id as idpauta, pautasPrensa.fechaPauta as fecha,pautasPrensa.tipoPauta as tipoPauta,mediosPrensa.nombreMedio as nombreMedio,seccionesPrensa.nombreSeccion as nombreSeccion,pautasPrensa.titular as titular,pautasPrensa.texto as texto,pautasPrensa.equivalencia as equivalencia,pautasPrensa.mediosPrensa_id as idMedio";

        $selectTv = "pautasTv.id as idpauta, pautasTv.fechaPauta as fecha,pautasTv.tipoPauta as tipoPauta,mediosAV.nombreMedio as nombreMedio,programasAV.nombrePrograma as nombrePrograma,pautasTv.titular as titular,pautasTv.texto as texto,pautasTv.equivalencia as equivalencia";

        $selectRadio = "pautasRadio.id as idpauta, pautasRadio.fechaPauta as fecha,pautasRadio.tipoPauta as tipoPauta,mediosAV.nombreMedio as nombreMedio,programasAV.nombrePrograma as nombrePrograma,pautasRadio.titular as titular,pautasRadio.texto as texto,pautasRadio.equivalencia as equivalencia";

        $selectInternet = "pautasInternet.id as idpauta, pautasInternet.fechaPauta as fecha,pautasInternet.tipoPauta as tipoPauta,mediosInternet.nombreMedio as nombreMedio,pautasInternet.titular as titular,pautasInternet.texto as texto,pautasInternet.equivalencia as equivalencia";

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

        $arrayResultados = array();
        $arrayCabeceras = array();
        $arrayCabeceras[]= "Fecha";
        $arrayCabeceras[]= "Tipo de Medio";
        $arrayCabeceras[]= "Medio";
        $arrayCabeceras[]= "Sección/Programa";
        $arrayCabeceras[]= "Titular";
        $arrayCabeceras[]= "Texto";
        $arrayCabeceras[]= "Equivalencia";
        $arrayCabeceras[]= "Link";
        
        foreach ($resultadosPrensa as $resultado) {
            $fecha = $resultado->fecha;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $pauta = array();
            $pauta['fecha'] = PHPExcel_Shared_Date::FormattedPHPToExcel($año,$mes,$dia);
            $pauta['tipoPauta'] = $resultado->tipoPauta;
            $pauta['nombreMedio'] = $resultado->nombreMedio;
            $pauta['nombrePrograma'] = $resultado->nombreSeccion;
            $pauta['titular'] = $resultado->titular;
            $pauta['texto'] = $resultado->texto;
            if($resultado->equivalencia>0){
                $pauta['equivalencia'] = number_format($resultado->equivalencia,2);
            }else{
                $pauta['equivalencia'] = number_format(0,2);
            }
            $pauta['link'] = '=HYPERLINK("http://servicios.noticiasperu.pe/gui/view/VistaPautaPrensa.php?idPauta='.$resultado->idpauta.'&bool=1","Abrir")';
            $arrayResultados[] = $pauta;
        }

        foreach ($resultadosTv as $resultado) {
            $fecha = $resultado->fecha;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $pauta = array();
            $pauta['fecha'] = PHPExcel_Shared_Date::FormattedPHPToExcel($año,$mes,$dia);
            $pauta['tipoPauta'] = $resultado->tipoPauta;
            $pauta['nombreMedio'] = $resultado->nombreMedio;
            $pauta['nombrePrograma'] = $resultado->nombrePrograma;
            $pauta['titular'] = $resultado->titular;
            $pauta['texto'] = $resultado->texto;
            if($resultado->equivalencia>0){
                $pauta['equivalencia'] = number_format($resultado->equivalencia,2);
            }else{
                $pauta['equivalencia'] = number_format(0,2);
            }
            $pauta['link'] = '=HYPERLINK("http://servicios.noticiasperu.pe/gui/view/VistaPautaPrensa.php?idPauta='.$resultado->idpauta.'&bool=1","Abrir")';
            $arrayResultados[] = $pauta;
        }
        foreach ($resultadosRadio as $resultado) {
            $fecha = $resultado->fecha;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $pauta = array();
            $pauta['fecha'] = PHPExcel_Shared_Date::FormattedPHPToExcel($año,$mes,$dia);
            $pauta['tipoPauta'] = $resultado->tipoPauta;
            $pauta['nombreMedio'] = $resultado->nombreMedio;
            $pauta['nombrePrograma'] = $resultado->nombrePrograma;
            $pauta['titular'] = $resultado->titular;
            $pauta['texto'] = $resultado->texto;
            if($resultado->equivalencia>0){
                $pauta['equivalencia'] = number_format($resultado->equivalencia,2);
            }else{
                $pauta['equivalencia'] = number_format(0,2);
            }
            $pauta['link'] = '=HYPERLINK("http://servicios.noticiasperu.pe/gui/view/VistaPautaPrensa.php?idPauta='.$resultado->idpauta.'&bool=1","Abrir")';
            $arrayResultados[] = $pauta;
        }
        foreach ($resultadosInternet as $resultado) {
            $fecha = $resultado->fecha;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $pauta = array();
            $pauta['fecha'] = PHPExcel_Shared_Date::FormattedPHPToExcel($año,$mes,$dia);
            $pauta['tipoPauta'] = $resultado->tipoPauta;
            $pauta['nombreMedio'] = $resultado->nombreMedio;
            $pauta['nombrePrograma'] = "";
            $pauta['titular'] = $resultado->titular;
            $pauta['texto'] = $resultado->texto;
            if($resultado->equivalencia>0){
                $pauta['equivalencia'] = number_format($resultado->equivalencia,2);
            }else{
                $pauta['equivalencia'] = number_format(0,2);
            }
            $pauta['link'] = '=HYPERLINK("http://servicios.noticiasperu.pe/gui/view/VistaPautaInternet.php?idPauta='.$resultado->idpauta.'&bool=0","Abrir")';
            $arrayResultados[] = $pauta;
        }

        /*return Excel::create("ExcelNoticias",function($excel) use ($arrayResultados,$arrayCabeceras){
            $excel->setTitle("Title");
            $excel->sheet("Hoja 1",function($sheet) use ($arrayResultados,$arrayCabeceras){
                $sheet->fromArray($arrayResultados,null,"A2",null,false);
                $sheet->row(1,$arrayCabeceras);
                $sheet->row(1,function($row){
                    $row->setBackground('#33BEFF');
                    $row->setFontWeight('bold');
                });
                $sheet->setWidth(array('A'=>10,'B'=>10,'C'=>15,'D'=>15,'E'=>40,'F'=>50,'G'=>10,'H'=>10));
                $sheet->setColumnFormat(array('B:F'=>'@','G'=>'0.00','A'=>'YYYY/MM/DD'));
                $sheet->cells('F2:F5',function($cell){
                    $cell->setAlignment('justified');
                });
            });
        })->download("xls");*/

        $headers = [
                    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                    'Content-type'        => "Content-type: application/vnd.ms-excel",
                    'Content-Type'        => 'application/octet-stream',
                    'Content-Type'        => 'application/download',
                    'Content-Disposition' => 'attachment; filename=NoticiasPerú-Pautas-'.date('Y-m-d').'.xls',
                    'Expires'             => '0',
                    'Pragma'              => 'public'
            ];

            array_unshift($arrayResultados, array_keys($arrayResultados[0]));

            $callback = function() use ($arrayResultados)
            {
                $FH = fopen('php://output', 'w');

                echo "<html>";
                echo " <meta charset='UTF-8'>";
                echo '<style type="text/css">
                        .style3 {
                            font-family: Verdana, Arial, Helvetica, sans-serif;
                            font-size: 14px;
                            font-weight: bold;
                            color: #FFFFFF;
                        }
                        .style5 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 15px; font-weight: bold; }
                        .style8 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
                        -->
                        .filaColor6{
                            font-family: Calibri;
                            font-size: 14px;
                            color: #000000;
                            background-color:#C6D5E1;
                            border:solid 0px #CCCCCC;
                            height:16px;
                        }

                        .estiloTitulo_letra3_pie_pagina_plomo
                        {   padding-left:11px;
                            font-size: 14px;
                            letter-spacing:0px;
                            padding-left:10px;
                            padding-top:3px;
                            padding-bottom:3px;
                            font-family: Arial, Helvetica, sans-serif;
                            background-color:#A4A4A4;
                            height:15px;
                            color:#FFFFFF;
                            font-size: 11px;
                            font-family: Calibri;
                            font-weight: lighter;
                        }

                        .letraMayuscula2 {
                            font-family: "Berlin Sans FB";
                            /*font-size: 15px;*/
                            font-size: 16px;
                        }
                        body {
                            background-color: #FDFDFD;
                            padding:0; /* BORRAMOS LOS RELLENOS */
                            margin:0; /* BORRAMOS LOS MARGENES*/
                            }
                        #contenedor {

                          margin:auto;
                          border-left:1px solid black;
                          border-right:1px solid black;
                          }

                        p {
                            margin: 0 auto;
                        }

                        </style>';

                       
                echo '<body marginwidth="0" leftmargin="0" topmargin="0" style="margin-bottom:0px; margin-top:0px;"><table width="720" border="0" align="center" cellpadding="0" cellspacing="0" ><tr><td valign="top">';
                echo '<div align="left" ></div>
                        <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0"><tr align="center" ><td width="80" height="23"><span class="style5">Fecha</span></td><td width="90"><span class="style5">Tipo de Medio</span></td><td width="100" ><span class="style5">Medio</span></td><td width="100"><span class="style5">Sección/Programa</span></td> <td width="700"><span class="style5">Titular</span></td>  <td width="700" ><span class="style5">Texto</span></td><td width="90" ><span class="style5">Equivalencia</span></td><td width="80" ><span class="style5">Link</span></td></tr>';
                for ($i=1; $i <count($arrayResultados) ; $i++) {
                    echo '    <tr align="center" ><td valign="top"><span >'.$arrayResultados[$i]['fecha'].'</span></td><td valign="top">'.$arrayResultados[$i]['tipoPauta'].'</td><td valign="top">'.$arrayResultados[$i]['nombreMedio'].'</td><td valign="top"><span >'.$arrayResultados[$i]['nombrePrograma'].'</span></td><td valign="top"><span >'.$arrayResultados[$i]['titular'].'</span></td><td valign="top"><span >'.$arrayResultados[$i]['texto'].'</span></td> <td valign="top"><span >'.$arrayResultados[$i]['equivalencia'].'</span></td> <td valign="top"><a href="'.$arrayResultados[$i]['link'].'&bool=0">Abrir</a></td>';
                }

                echo "</td></tr></table></body>";
                echo "</html>";

                fclose($FH);
            };

            return Response::stream($callback, 200, $headers);
        
    }

    public function cambiarValorPauta(Request $request){
        $arrayFiltrado = session()->get('arrayFiltrado');
        $nvoArray = array();
        $idpauta = 0;
        foreach ($arrayFiltrado as $pauta) {
            if($pauta['tipoPauta']==$request->tipo && $pauta['id']==$request->idpauta){
                $pauta['valor'] = $request->value;
                $idpauta = $pauta['id'];
            }
            $nvoArray[] = $pauta;
        }
        //print_r($nvoArray);
        echo $idpauta;
        session()->put('arrayFiltrado',$nvoArray);
        return response()->json("Terminado");
    }

    public function cambiarValorTodo(Request $request){
        $arrayFiltrado = session()->get('arrayFiltrado');
        $nvoArray = array();
        foreach ($arrayFiltrado as $pauta) {
            $pauta['valor'] = $request->value;
            $nvoArray[] = $pauta;
        }
        session()->put('arrayFiltrado',$nvoArray);
        return response()->json("Terminado");
    }

    public function importarMediosPrensa(){
        $medios24 = DB::connection('web_noticias')->table('medio_prensas')
                    ->select('idMedioPrensa','nombreMedioPrensa','tipo_medio_prensas_idTipoMedioPrensa as tipoMedio')
                    ->get();

        foreach ($medios24 as $medio) {
            $existe = MedioPrensa::find($medio->idMedioPrensa);
            if($existe == null){
                $medioPrensa = new MedioPrensa();
                $medioPrensa->id = $medio->idMedioPrensa;
                $medioPrensa->nombreMedio = $medio->nombreMedioPrensa;
                if($medio->tipoMedio == 1){
                    $medioPrensa->subTipoMedio = 'Diario';
                }else{
                    $medioPrensa->subTipoMedio = 'Revista';
                }
                $medioPrensa->tipoMedios_id = 1;
                $medioPrensa->save();

                print_r($medio);
                echo '</br>';
            }
        }

        return response()->json("ok");
    }

    public function importarSeccionesPrensa(){
        $secciones24 = DB::connection('web_noticias')->table('secciones')
                    ->select('idSeccion','numeroSeccion','nombreSeccion','medio_prensas_idMedioPrensa as idMedio')
                    ->get();

        foreach ($secciones24 as $seccion) {
            $existe = SeccionPrensa::find($seccion->idSeccion);
            if($existe==null){
                $seccionPrensa = new SeccionPrensa();
                $seccionPrensa->id = $seccion->idSeccion;
                $seccionPrensa->numeroSeccion = $seccion->numeroSeccion;
                $seccionPrensa->nombreSeccion = $seccion->nombreSeccion;
                $seccionPrensa->mediosPrensa_id = $seccion->idMedio;
                $seccionPrensa->save();

                print_r($seccion);
                echo '</br>';
            }
            
        }

        return response()->json($arrayMedios);
    }

    public function importarPautasPrensa24(){

        //$fechaInicio = date('2018-02-01');
        $fechaActual = date('Y-m-d');
        $fechaInicio = date("Y-m-d",strtotime($fechaActual."- 1 days"));
        while(strtotime($fechaInicio)<=strtotime($fechaActual)){
            echo $fechaInicio.'</br>';
        
        //importacion dia a dia
        $pautas24 = DB::connection('web_noticias_prensa')->table('tbl_pauta_prensa')
                    //->whereBetween('fecha',[$fechaInicio,$fechaActual])
                    ->where('fecha','=',$fechaInicio)
                    ->select('idpauta_prensa','fecha','pagina','idseccion','titular','idmedio','fecha_registro','codigo','estado','tipo_servicio','texto','equivalencia')
                    ->get();

        foreach ($pautas24 as $pauta) {
            try{
                $existe = PautaPrensa::find($pauta->idpauta_prensa);
                if($existe == null){
                    $nuevoMedio = DB::connection('web_noticias')->table('medios_prensa_ordenados')
                                    ->where('medios_prensa_mal_id','=',$pauta->idmedio)
                                    ->first();

                    $nuevaSeccion = DB::connection('web_noticias')->table('secciones_ordenas')
                                    ->join('secciones','secciones_ordenas.secciones_idSeccion','secciones.idSeccion')
                                    ->where('secciones_ordenas.secciones_mal_idseccion','=',$pauta->idseccion)
                                    ->where('secciones.medio_prensas_idMedioPrensa','=',$nuevoMedio->medio_prensas_idMedioPrensa)
                                    ->value('secciones_ordenas.secciones_idSeccion');

                    $pautaNueva = new PautaPrensa();
                    $pautaNueva->id = $pauta->idpauta_prensa;
                    $pautaNueva->titular = $pauta->titular;
                    $pautaNueva->texto = $pauta->texto;
                    $pautaNueva->fechaPauta = $pauta->fecha;
                    $pautaNueva->paginas = $pauta->pagina;
                    $pautaNueva->fechaRegistro = $pauta->fecha_registro;
                    $pautaNueva->tipoPauta = $pauta->tipo_servicio;
                    //$pautaNueva->equivalencia = $pauta->equivalencia;
                    $pautaNueva->estado = $pauta->estado;
                    $pautaNueva->mediosPrensa_id = $nuevoMedio->medio_prensas_idMedioPrensa;
                    $pautaNueva->seccionesPrensa_id = $nuevaSeccion;
                    $pautaNueva->save();

                    /*$recortes24 = DB::connection('web_noticias_prensa')->table('tbl_pauta_recorte')
                            ->where('idpauta_servicio','=',$pautaNueva->id)
                            ->select('idpauta_servicio','alto','ancho','codigo','equivalencia')
                            ->get();*/

                    $recortes = DB::connection('intranet_prensa')->table('recortes')
                            ->where('pauta_prensas_idPautaPrensa','=',$pautaNueva->id)
                            ->where('ruta','not like','%preview%')
                            ->select('alto','ancho','pagina','ruta','pauta_prensas_idPautaPrensa')
                            ->get();

                    //$equivalencia = 0;
                    /*$codigo = $pauta->codigo;
                    $fecha = $pauta->fecha;
                    $año = substr($fecha, 0,4);
                    $mes = substr($fecha, 5,2);
                    $dia = substr($fecha, 8,2);*/
                    $totalRecortes = count($recortes);
                    $varR = 0;

                    foreach ($recortes as $recorte) {
                        $varR++;
                        $recorteNuevo = new PautaRecorte();
                        $recorteNuevo->pautasPrensa_idPauta = $recorte->pauta_prensas_idPautaPrensa;
                        $recorteNuevo->alto = $recorte->alto;
                        $recorteNuevo->ancho = $recorte->ancho;
                        $recorteNuevo->pagina = $recorte->pagina;
                        //$equivalencia = $equivalencia + $recorte->equivalencia;
                        //$recorteNuevo->rutaImagen =$codigo."_".$varR."_".$totalRecortes.".jpg";
                        $recorteNuevo->rutaImagen =$recorte->ruta;
                        $recorteNuevo->save();
                    }

                    /*$pautaNueva->equivalencia = $equivalencia;
                    $pautaNueva->save();*/
                }
            }catch(\Exception $e){
                echo $pauta->idpauta_prensa.'</br>';
                echo substr($e, 0,250).'</br>';
            }
        }

        $fechaInicio = date("Y-m-d",strtotime($fechaInicio."+ 1 days"));
        }


        return response()->json("Terminado");
    }

    public function importarPautasTv24(){
        $fechaActual = date('Y-m-d');
        $fechaInicio = date("Y-m-d",strtotime($fechaActual."- 2 days"));
        //$fechaInicio = '2018-01-01';

        $pautas24 = DB::connection('noticias_24')->table('pautatv')
                    ->whereBetween('Fecha',[$fechaInicio,$fechaActual])
                    ->select('NumID','Titular','Texto','Fecha','Hora','Duracion','equivalencia','FechaRegistro','tipo_servicio','Programa')
                    ->get();

        foreach ($pautas24 as $pauta) {
            try{
                $existe = PautaTv::find($pauta->NumID);
                if($existe == null){
                    $pautatv = new PautaTv();
                    $pautatv->id = $pauta->NumID;
                    $pautatv->titular = $pauta->Titular;
                    $pautatv->texto = $pauta->Texto;
                    $pautatv->fechaPauta = $pauta->Fecha;
                    $pautatv->horaPauta = $pauta->Hora;
                    $pautatv->duracion = $pauta->Duracion;
                    $pautatv->equivalencia = $pauta->equivalencia;
                    $pautatv->fechaRegistro = $pauta->FechaRegistro;
                    $pautatv->tipoPauta = $pauta->tipo_servicio;
                    $pautatv->programasAV_id = $pauta->Programa;
                    $fecha = $pauta->Fecha;
                    $año = substr($fecha, 0,4);
                    $mes = substr($fecha, 5,2);
                    $dia = substr($fecha, 8,2);
                    $pautatv->rutaVideo = "http://servicios.noticiasperu.pe/medios/tv/mp4_9/".$año."/".$mes."/".$dia."/".$pauta->NumID.".mp4";
                    $pautatv->save();
                }
            }catch(\Exception $e){
                echo $pauta->NumID.'</br>';
                echo substr($e, 0,250).'</br>';
            }
        }
    }

    public function importarPautasRadio24(){
        $fechaActual = date('Y-m-d');
        $fechaInicio = date("Y-m-d",strtotime($fechaActual."- 2 days"));
        //$fechaInicio = '2018-01-01';

        $pautas24 = DB::connection('noticias_24')->table('pautaradio')
                    ->whereBetween('Fecha',[$fechaInicio,$fechaActual])
                    ->select('NumID','Titular','Texto','Fecha','Hora','Duracion','equivalencia','FechaRegistro','tipo_servicio','Programa')
                    ->get();

        foreach ($pautas24 as $pauta) {
            try{
                $existe = PautaRadio::find($pauta->NumID);
                if($existe == null){
                    $pautaradio = new PautaRadio();
                    $pautaradio->id = $pauta->NumID;
                    $pautaradio->titular = $pauta->Titular;
                    $pautaradio->texto = $pauta->Texto;
                    $pautaradio->fechaPauta = $pauta->Fecha;
                    $pautaradio->horaPauta = $pauta->Hora;
                    $pautaradio->duracion = $pauta->Duracion;
                    $pautaradio->equivalencia = $pauta->equivalencia;
                    $pautaradio->fechaRegistro = $pauta->FechaRegistro;
                    $pautaradio->tipoPauta = $pauta->tipo_servicio;
                    $pautaradio->programasAV_id = $pauta->Programa;
                    $fecha = $pauta->Fecha;
                    $año = substr($fecha, 0,4);
                    $mes = substr($fecha, 5,2);
                    $dia = substr($fecha, 8,2);
                    $pautaradio->rutaAudio = "http://servicios.noticiasperu.pe/medios/radio/".$año."/".$mes."/".$dia."/".$pauta->NumID.".mp3";
                    $pautaradio->save();
                }
            }catch(\Exception $e){
                echo $pauta->NumID.'</br>';
                echo substr($e, 0,250).'</br>';
            }
        }
    }

    public function importarPautasInternet24(){
        $fechaActual = date('Y-m-d');
        $fechaInicio = date("Y-m-d",strtotime($fechaActual."- 5 days"));
        //$fechaInicio = '2018-01-01';

        $pautas24 = DB::connection('noticias_24')->table('pautainternetweb')
                    ->whereBetween('Fecha',[$fechaInicio,$fechaActual])
                    ->select('NumID','Titular','Texto','Fecha','equivalencia','FechaRegistro','tipo_servicio','Ruta','Medio')
                    ->get();

        foreach ($pautas24 as $pauta) {
            try{
                $existe = PautaInternet::find($pauta->NumID);
                if($existe == null){
                    $pautainternet = new PautaInternet();
                    $pautainternet->id = $pauta->NumID;
                    $pautainternet->titular = $pauta->Titular;
                    $pautainternet->texto = $pauta->Texto;
                    $pautainternet->fechaPauta = $pauta->Fecha;
                    $pautainternet->equivalencia = $pauta->equivalencia;
                    $pautainternet->fechaRegistro = $pauta->FechaRegistro;
                    $pautainternet->tipoPauta = $pauta->tipo_servicio;
                    $pautainternet->mediosInternet_id = $pauta->Medio;
                    $pautainternet->rutaWeb = $pauta->Ruta;
                    $fecha = $pauta->Fecha;
                    $año = substr($fecha, 0,4);
                    $mes = substr($fecha, 5,2);
                    $dia = substr($fecha, 8,2);
                    $pautainternet->rutaImagen = "http://servicios.noticiasperu.pe/medios/internet/".$año."/".$mes."/".$dia."/".base64_encode($pauta->NumID.'-'.$pauta->FechaRegistro).".jpg";
                    $pautainternet->save();
                }
            }catch(\Exception $e){
                echo $pauta->NumID.'</br>';
                echo substr($e, 0,250).'</br>';
            }
        }
    }

    public function importarMediosInternet(){
        $medios24 = DB::connection('noticias')->table('medioav')
                    ->where('Tipo','=',4)
                    ->select('NumID','Tipo','Nombre')
                    ->distinct()
                    ->get();

        foreach ($medios24 as $medio) {
            $existe = MedioInternet::find($medio->NumID);
            if(count($existe)==0){
                $medioInternet = new MedioInternet();
                $medioInternet->id = $medio->NumID;
                $medioInternet->nombreMedio = $medio->Nombre;
                $medioInternet->tipoMedios_id = $medio->Tipo;
                $medioInternet->save();

                print_r($medio);
                echo '</br>';
            }
        }

        return response()->json('ok');
    }

    public function importarMediosAudioVisuales(){
        $medios24 = DB::connection('noticias')->table('medios')
                    ->select('idMedio','nombre','señal','tipo_medios_idTipoMedio')
                    ->distinct()
                    ->get();

        foreach ($medios24 as $medio) {
            $existe = MedioAV::find($medio->idMedio);
            if(count($existe)==0){
                $medioav = new MedioAV();
                $medioav->id = $medio->idMedio;
                $medioav->nombreMedio = $medio->nombre;
                $medioav->detalles = $medio->señal;
                $medioav->tipoMedios_id = $medio->tipo_medios_idTipoMedio;
                $medioav->save();

                print_r($medio);
                echo '</br>';
            }
        }

        return response()->json('ok');
    }

    public function importarProgramasAudioVisuales(){
        $programas24 = Db::connection('noticias')->table('programa')
                        ->select('NumID','Nombre','Medio')
                        ->distinct()
                        ->get();

        foreach ($programas24 as $programa) {
            $existe = ProgramaAV::find($programa->NumID);
            if(count($existe)==0){
                $mediosOrd = DB::connection('noticias')->table('medios_ordenados')
                            ->where('medios26_idMedio','=',$programa->Medio)
                            ->first();

                if($mediosOrd!=null){
                    $medioNvo = $mediosOrd->medios_idMedio;
                    $programaav = new ProgramaAV();
                    $programaav->id = $programa->NumID;
                    $programaav->nombrePrograma = $programa->Nombre;
                    $programaav->mediosAV_id = $medioNvo;
                    $programaav->save();

                    print_r($programa);
                    echo '</br>';
                }

            }
        }

        return response()->json('ok');
    }

    public function corregirRecortes(){
        $fechaInicio = date('2018-02-07');
        $fechaFin = date('Y-m-d');

        while(strtotime($fechaInicio)<=strtotime($fechaFin)){
            echo "Fecha:".$fechaInicio.'</br>';
            $pautasPrensa = PautaPrensa::where('fechaPauta','=',$fechaInicio)
                            ->select('id','titular')
                            //->limit(50)
                            ->get();

            foreach ($pautasPrensa as $pauta) {
                $antiguo = false;
                $recortes = DB::connection('intranet_prensa')->table('recortes')
                            ->where('pauta_prensas_idPautaPrensa','=',$pauta->id)
                            ->where('ruta','NOT LIKE','%preview%')
                            ->select('ruta','pauta_prensas_idPautaPrensa as idpauta','alto','ancho','pagina')
                            ->get();

                if(count($recortes)==0){
                    $recortes = DB::connection('web_noticias')->table('tbl_pauta_recorte')
                            ->where('idpauta_servicio','=',$pauta->id)
                            ->select('idpauta_servicio as idpauta','alto','ancho')
                            ->get();

                    $codigo = DB::connection('web_noticias_prensa')->table('tbl_pauta_prensa')
                                ->where('idpauta_prensa','=',$pauta->id)
                                ->value('codigo');

                    $antiguo=true;
                }

                $totalRecortes = count($recortes);
                $i = 0; 
                echo $pauta->id.'</br>';

                foreach ($recortes as $recorte) {
                    $i++;
                    if($antiguo){
                        $existe = PautaRecorte::where('rutaImagen','=',$codigo.'_'.$i.'.jpg')
                                    ->get();
                    }else{
                        $existe = PautaRecorte::where('rutaImagen','=',$recorte->ruta)
                                    ->get();
                    }
                    if(count($existe)==0){
                        $recorteNuevo = new PautaRecorte();
                        $recorteNuevo->pautasPrensa_idPauta = $recorte->idpauta;
                        $recorteNuevo->alto = $recorte->alto;
                        $recorteNuevo->ancho = $recorte->ancho;
                        if($antiguo){
                            $rutaNueva = $recorteNuevo->rutaImagen = $codigo.'_'.$i.'.jpg';
                        }else{
                            $recorteNuevo->pagina = $recorte->pagina;
                            $rutaNueva = $recorteNuevo->rutaImagen = $recorte->ruta;
                        }
                        $recorteNuevo->save();
                        echo $rutaNueva.'</br>';
                    }else{
                        echo "Ya existe</br>";
                    }
                    
                }
                echo '</br>';
            }
            $fechaInicio = date("Y-m-d",strtotime($fechaInicio."+ 1 days"));
        }

        return response()->json("Terminado");
    }

}