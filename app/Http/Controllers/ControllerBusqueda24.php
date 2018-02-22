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
use App\ProgramaAV;

use App\PalabraClave;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use PHPExcel_Shared_Date;

class ControllerBusqueda24 extends Controller
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

    public function busqueda(){
        $fecha = date('Y-m-d');
        //$fecha = date('2017-11-23');

        $pautasPrensa = DB::connection('mysql_24_prensa')->table('tbl_pauta_prensa')
                    ->join('tbl_medio','tbl_pauta_prensa.idmedio','tbl_medio.idmedio')
                    ->join('tbl_seccion','tbl_pauta_prensa.idseccion','tbl_seccion.idseccion')
                    ->where('tbl_pauta_prensa.fecha','=',$fecha)
                    ->where('tbl_pauta_prensa.estado','=',1)
                    ->select('tbl_pauta_prensa.idpauta_prensa as id','tbl_pauta_prensa.fecha as fechaPauta','tbl_pauta_prensa.tipo_servicio as tipoPauta','tbl_medio.nombre as nombreMedio','tbl_pauta_prensa.idmedio as idMedio','tbl_seccion.nombre as nombreSeccion','tbl_pauta_prensa.titular as titular')
                    ->get();

        $pautasTv = DB::connection('mysql_24')->table('pautatv')
                    ->join('programa','pautatv.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->where('pautatv.Fecha','=',$fecha)
                    ->select('pautatv.NumID as id','pautatv.Fecha as fechaPauta','pautatv.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','programa.Nombre as nombrePrograma','pautatv.Titular as titular')
                    ->get();

        $pautasRadio = DB::connection('mysql_24')->table('pautaradio')
                    ->join('programa','pautaradio.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->where('pautaradio.Fecha','=',$fecha)
                    ->select('pautaradio.NumID as id','pautaradio.Fecha as fechaPauta','pautaradio.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','programa.Nombre as nombrePrograma','pautaradio.Titular as titular')
                    ->get();

        $pautasInternet = DB::connection('mysql_24')->table('pautainternetweb')
                    ->join('medioav','pautainternetweb.Medio','medioav.NumID')
                    ->where('pautainternetweb.Fecha','=',$fecha)
                    ->select('pautainternetweb.NumID as id','pautainternetweb.Fecha as fechaPauta','pautainternetweb.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','pautainternetweb.Titular as titular')
                    ->get();

        $dataResponse = new Collection;
        foreach ($pautasPrensa as $pautaPrensa) {
            if(strtotime($pautaPrensa->fechaPauta)==strtotime('2017-11-23')){
                $nombreMedio = DB::connection('mysql_24_noticias')->table('medio_prensas')->where('idMedioPrensa','=',$pautaPrensa->idMedio)->value('nombreMedioPrensa');
            }else{
                $nombreMedio = $pautaPrensa->nombreMedio;
            }
            $dataResponse->push(['id'=>$pautaPrensa->id, 'fechaPauta'=>$pautaPrensa->fechaPauta, 'tipoPauta'=>$pautaPrensa->tipoPauta, 'nombreMedio'=>$nombreMedio, 'nombreSP'=>$pautaPrensa->nombreSeccion, 'titular'=>$pautaPrensa->titular,'valor'=>1]);
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
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,30)}}...</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,8)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,10)}}</span>')
            ->rawColumns(['opciones','checkPauta','nombreMedio','nombreSP','titular'])
            ->make(true);
    }

    public function autocompleteMedios($id){
        $array=array();

        if(strpos($id,"2")!==false){
            $mediosTv =DB::connection('mysql_24')->table('medioav')->where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('Nombre','like','%'.$term.'%');
                })
                ->where('Tipo','=',2)
                ->select("NumID as id","Nombre as nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosTv as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
              }
        }
        if(strpos($id,"3")!==false){
            $mediosRadio =DB::connection('mysql_24')->table('medioav')->where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('Nombre','like','%'.$term.'%');
                })
                ->where('Tipo','=',3)
                ->select("NumID as id","Nombre as nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosRadio as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
              }
        }
        if(strpos($id,"1")!==false){
            $mediosPrensa =DB::connection('mysql_24_prensa')->table('tbl_medio')->where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('nombre','like','%'.$term.'%');
                })
                ->whereIn('idtipo_medio',[1,2])
                ->select("idmedio as id","nombre as nombreMedio")
                ->take(6)
                ->get();
            foreach ($mediosPrensa as $medio) {
                $array[]=['id'=>$medio->id,'value'=>$medio->nombreMedio];
            }
        }
        if(strpos($id,"4")!==false){
            $mediosInternet =DB::connection('mysql_24')->table('medioav')->where(function($data)
                { 
                  $term= Input::get('term');
                  $data->where('Nombre','like','%'.$term.'%');
                })
                ->where('Tipo','=',4)
                ->select("NumID as id","Nombre as nombreMedio")
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

    public function busquedaAvanzada24(Request $request)
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

        //print_r($arrayTextoQuery);

        if($request->filtroMedios==0){
            $filtroMedios = false;
        }else{
            $filtroMedios = true;
        }
        $mediosAV = DB::connection('mysql_24')->table('medioav')->where(function($query) use ($arrayMedios){
                        foreach ($arrayMedios as $medio) {
                            $query->orwhere('Nombre','like','%'.$medio.'%');
                        }
                    })
                    ->whereIn('Tipo',[2,3])
                    ->select('NumID as id')->get();
        $mediosAV2 = array();
        foreach ($mediosAV as $medioAV) {
            $mediosAV2[] = $medioAV->id;
        }
                    
        $mediosPrensa = DB::connection('mysql_24_prensa')->table('tbl_medio')->where(function($query) use ($arrayMedios){
                        foreach ($arrayMedios as $medio) {
                            $query->orwhere('nombre','like','%'.$medio.'%');
                        }
                    })
                    ->whereIn('idtipo_medio',[1,2])
                    ->select('idmedio as id')->get();

        $mediosPrensa2 = array();
        foreach ($mediosPrensa as $medioPrensa) {
            $mediosPrensa2[] = $medioPrensa->id;
        }

        $mediosInternet = DB::connection('mysql_24')->table('medioav')->where(function($query) use ($arrayMedios){
            foreach ($arrayMedios as $medio) {
                $query->orwhere('Nombre','like','%'.$medio.'%');
            }
        })
        ->where('Tipo','=',4)
        ->select('NumID as id')->get();

        $mediosInternet2 = array();
        foreach ($mediosInternet as $medioInternet) {
            $mediosInternet2[] = $medioInternet->id;
        }

        $dataResponse = new Collection;

        if((!$filtroMedios || count($mediosPrensa)!=0) && $checkPrensa=='true'){
            $pautasPrensa = DB::connection('mysql_24_prensa')->table('tbl_pauta_prensa')
                    ->join('tbl_medio','tbl_pauta_prensa.idmedio','tbl_medio.idmedio')
                    ->join('tbl_seccion','tbl_pauta_prensa.idseccion','tbl_seccion.idseccion')
                    ->whereBetween('tbl_pauta_prensa.fecha',[$fechaInicio,$fechaFin])
                    ->where('tbl_pauta_prensa.estado','=',1)
                    ->where(function($query) use ($mediosPrensa2,$filtroMedios){
                        if($filtroMedios != false){
                            if(count($mediosPrensa2)!=0){
                                $query->whereIn('tbl_pauta_prensa.idmedio',$mediosPrensa2);
                            }
                        }
                    })
                    ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(tbl_pauta_prensa.texto,tbl_pauta_prensa.titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                    })
                    ->select('tbl_pauta_prensa.idpauta_prensa as id','tbl_pauta_prensa.fecha as fechaPauta','tbl_pauta_prensa.tipo_servicio as tipoPauta','tbl_medio.nombre as nombreMedio','tbl_pauta_prensa.idmedio as idMedio','tbl_seccion.nombre as nombreSeccion','tbl_pauta_prensa.titular as titular')
                    ->get();

            foreach ($pautasPrensa as $pautaPrensa) {
                if(strtotime($pautaPrensa->fechaPauta)==strtotime('2017-11-23')){
                    $nombreMedio = DB::connection('mysql_24_noticias')->table('medio_prensas')->where('idMedioPrensa','=',$pautaPrensa->idMedio)->value('nombreMedioPrensa');
                }else{
                    $nombreMedio = $pautaPrensa->nombreMedio;
                }
                $dataResponse->push(['id'=>$pautaPrensa->id, 'fechaPauta'=>$pautaPrensa->fechaPauta, 'tipoPauta'=>$pautaPrensa->tipoPauta, 'nombreMedio'=>$nombreMedio, 'nombreSP'=>$pautaPrensa->nombreSeccion, 'titular'=>$pautaPrensa->titular,'valor'=>1]);
            }
        }//end if
        if(!$filtroMedios || count($mediosAV)!=0){
            if($checkTv=='true'){
                $pautasTv = DB::connection('mysql_24')->table('pautatv')
                    ->join('programa','pautatv.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautatv.Texto,pautatv.Titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        
                    })
                    ->where(function($query) use ($mediosAV2,$filtroMedios){
                        if($filtroMedios!=false){
                            if(count($mediosAV2)!=0){
                                $query->whereIn('medioav.NumID',$mediosAV2);
                            }
                        }
                    })
                    ->whereBetween('pautatv.Fecha',[$fechaInicio,$fechaFin])
                    ->select('pautatv.NumID as id','pautatv.Fecha as fechaPauta','pautatv.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','programa.Nombre as nombrePrograma','pautatv.Titular as titular')
                    ->get();

                foreach ($pautasTv as $pautaTv) {
                    $dataResponse->push(['id'=>$pautaTv->id, 'fechaPauta'=>$pautaTv->fechaPauta, 'tipoPauta'=>$pautaTv->tipoPauta, 'nombreMedio'=>$pautaTv->nombreMedio, 'nombreSP'=>$pautaTv->nombrePrograma, 'titular'=>$pautaTv->titular,'valor'=>1]);
                }
            }
            if($checkRadio=='true'){
                $pautasRadio = DB::connection('mysql_24')->table('pautaradio')
                    ->join('programa','pautaradio.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautaradio.Texto,pautaradio.Titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                        
                    })
                    ->where(function($query) use ($mediosAV2,$filtroMedios){
                        if($filtroMedios!=false){
                            if(count($mediosAV2)!=0){
                                $query->whereIn('medioav.NumID',$mediosAV2);
                            }
                        }
                    })
                    ->whereBetween('pautaradio.Fecha',[$fechaInicio,$fechaFin])
                    ->select('pautaradio.NumID as id','pautaradio.Fecha as fechaPauta','pautaradio.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','programa.Nombre as nombrePrograma','pautaradio.Titular as titular')
                    ->get();
                
                foreach ($pautasRadio as $pautaRadio) {
                    $dataResponse->push(['id'=>$pautaRadio->id, 'fechaPauta'=>$pautaRadio->fechaPauta, 'tipoPauta'=>$pautaRadio->tipoPauta, 'nombreMedio'=>$pautaRadio->nombreMedio, 'nombreSP'=>$pautaRadio->nombrePrograma, 'titular'=>$pautaRadio->titular,'valor'=>1]);
                }
            }
        }//end if
        if((!$filtroMedios || count($mediosInternet)!=0) && $checkInternet=='true'){
            $pautasInternet = DB::connection('mysql_24')->table('pautainternetweb')
                    ->join('medioav','pautainternetweb.Medio','medioav.NumID')
                    ->where(function($query) use ($arrayTextoQuery){
                            foreach ($arrayTextoQuery as $texto) {
                                $query->orWhereRaw('MATCH(pautainternetweb.Texto,pautainternetweb.Titular) AGAINST('.$texto.' IN BOOLEAN MODE)');
                            }
                            
                    })
                    ->where(function($query) use ($mediosInternet2,$filtroMedios){
                        if($filtroMedios!=false){
                            if(count($mediosInternet2)!=0){
                                $query->whereIn('medioav.NumID',$mediosInternet2);
                            }
                        }
                    })
                    ->whereBetween('pautainternetweb.Fecha',[$fechaInicio,$fechaFin])
                    ->select('pautainternetweb.NumID as id','pautainternetweb.Fecha as fechaPauta','pautainternetweb.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','pautainternetweb.Titular as titular')
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
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,30)}}...</span>')
            ->editColumn('nombreMedio','<span title="{{$nombreMedio}}" data-toogle="tooltip" data-placement="top">{{substr($nombreMedio,0,8)}}</span>')
            ->editColumn('nombreSP','<span title="{{$nombreSP}}" data-toogle="tooltip" data-placement="top">{{substr($nombreSP,0,10)}}</span>')
            ->rawColumns(['opciones','checkPauta','nombreMedio','nombreSP','titular'])
            ->make(true);
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
                if($pauta['tipoPauta']=='Television'){
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
                if($pauta['tipoPauta']=='Television'){
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
            ->editColumn('titular','<span title="{{$titular}}" data-toogle="tooltip" data-placement="top">{{substr($titular,0,30)}}...</span>')
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

        $selectPrensa = "tbl_pauta_prensa.idpauta_prensa as idpauta, tbl_pauta_prensa.fecha as fecha,tbl_pauta_prensa.tipo_servicio as tipoPauta,tbl_medio.nombre as nombreMedio,tbl_seccion.nombre as nombreSeccion,tbl_pauta_prensa.titular as titular,tbl_pauta_prensa.texto2 as texto,tbl_pauta_prensa.equivalencia as equivalencia,tbl_pauta_prensa.idmedio as idMedio";
        $selectTv = "pautatv.NumID as idpauta, pautatv.Fecha as fecha,pautatv.tipo_servicio as tipoPauta,medioav.Nombre as nombreMedio,programa.Nombre as nombrePrograma,pautatv.Titular as titular,pautatv.Texto as texto,pautatv.equivalencia as equivalencia";
        $selectRadio = "pautaradio.NumID as idpauta, pautaradio.Fecha as fecha,pautaradio.tipo_servicio as tipoPauta,medioav.Nombre as nombreMedio,programa.Nombre as nombrePrograma,pautaradio.Titular as titular,pautaradio.Texto as texto,pautaradio.equivalencia as equivalencia";
        $selectInternet = "pautainternetweb.NumID as idpauta, pautainternetweb.Fecha as fecha,pautainternetweb.tipo_servicio as tipoPauta,medioav.Nombre as nombreMedio,pautainternetweb.Titular as titular,pautainternetweb.Texto as texto,pautainternetweb.equivalencia as equivalencia";

        $resultadosPrensa = DB::connection('mysql_24_prensa')->table('tbl_pauta_prensa')
                    ->join('tbl_medio','tbl_pauta_prensa.idmedio','tbl_medio.idmedio')
                    ->join('tbl_seccion','tbl_pauta_prensa.idseccion','tbl_seccion.idseccion')
                    ->whereIn('tbl_pauta_prensa.idpauta_prensa',$arrayPrensa)
                    ->selectRaw($selectPrensa)
                    ->get();

        $resultadosTv = DB::connection('mysql_24')->table('pautatv')
                    ->join('programa','pautatv.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->whereIn('pautatv.NumID',$arrayTv)
                    ->selectRaw($selectTv)
                    ->get();

        $resultadosRadio = DB::connection('mysql_24')->table('pautaradio')
                    ->join('programa','pautaradio.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->whereIn('pautaradio.NumID',$arrayRadio)
                    ->selectRaw($selectRadio)
                    ->get();

        $resultadosInternet = DB::connection('mysql_24')->table('pautainternetweb')
                    ->join('medioav','pautainternetweb.Medio','medioav.NumID')
                    ->whereIn('pautainternetweb.NumID',$arrayInternet)
                    ->selectRaw($selectInternet)
                    ->get();

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
            if(strtotime($resultado->fechaPauta)==strtotime('2017-11-23')){
                $nombreMedio = DB::connection('mysql_24_noticias')->table('medio_prensas')->where('idMedioPrensa','=',$resultado->idMedio)->value('nombreMedioPrensa');
            }else{
                $nombreMedio = $resultado->nombreMedio;
            }
            $pauta['nombreMedio'] = $nombreMedio;
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

        return Excel::create("ExcelNoticias",function($excel) use ($arrayResultados,$arrayCabeceras){
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
        })->download("xls");
        
    }

    public function cambiarValorPauta(Request $request){
        $arrayFiltrado = session()->get('arrayFiltrado');
    }
    
}
