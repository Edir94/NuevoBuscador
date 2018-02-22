<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PautaPrensa;
use App\PautaTv;
use App\PautaRadio;
use App\PautaInternet;

use DB;

class ControllerVistas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPrensa($id)
    {
        /*$pautasPrensa = PautaPrensa::join('seccionesPrensa','seccionesPrensa.id','pautasPrensa.seccionesPrensa_id')
                    ->join('mediosPrensa','mediosPrensa.id','seccionesPrensa.mediosPrensa_id')
                    ->where('pautasPrensa.id','=',$id)
                    ->select('pautasPrensa.id','pautasPrensa.fechaPauta','pautasPrensa.tipoPauta','mediosPrensa.nombreMedio','seccionesPrensa.nombreSeccion','pautasPrensa.titular','pautasPrensa.texto')
                    ->get();*/

        $pautasPrensa = DB::connection('web_noticias_prensa_1')->table('tbl_pauta_prensa')
                    ->join('tbl_medio','tbl_pauta_prensa.idmedio','tbl_medio.idmedio')
                    ->join('tbl_seccion','tbl_pauta_prensa.idseccion','tbl_seccion.idseccion')
                    ->leftjoin('tbl_lectoria','tbl_medio.idmedio','tbl_lectoria.idmedio')
                    ->where('tbl_pauta_prensa.idpauta_prensa','=',$id)
                    ->select('tbl_pauta_prensa.idpauta_prensa as id','tbl_pauta_prensa.fecha as fechaPauta','tbl_pauta_prensa.tipo_servicio as tipoPauta','tbl_medio.nombre as nombreMedio','tbl_seccion.nombre as nombreSeccion','tbl_pauta_prensa.titular as titular','tbl_pauta_prensa.texto as texto','tbl_pauta_prensa.codigo as codigoImagen','tbl_medio.idtipo_medio as subTipoMedio','tbl_lectoria.lectoria as lectoria','tbl_pauta_prensa.pagina as paginas','tbl_pauta_prensa.idmedio as idMedio','tbl_pauta_prensa.equivalencia as equivalencia')
                    ->get();

        $pautasRecortes = DB::connection('intranet_prensa')->table('recortes')
                            ->where('pauta_prensas_idPautaPrensa','=',$id)
                            ->where('ruta','NOT LIKE','%preview%')
                            ->select('ruta','pauta_prensas_idPautaPrensa as idpauta','alto','ancho','pagina')
                            ->get();

        if(count($pautasRecortes)==0){

            $pautasRecortes = DB::connection('web_noticias')->table('tbl_pauta_recorte')
                        ->where('idpauta_servicio','=',$id)
                        ->select('idpauta_servicio as idpauta','alto','ancho','equivalencia')
                        ->orderBy('codigo','asc')
                        ->get();

        }

        $nroRecortes = count($pautasRecortes);

        $pauta = array();
        foreach ($pautasPrensa as $pautaPrensa) {
            $fecha = $pautaPrensa->fechaPauta;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $fechaPauta = $dia.'-'.$mes.'-'.$año;
            $equivalencia = $pautaPrensa->equivalencia; 
            $recortes = array();
            $i=0;
            $alto = 0;
            $ancho = 0;
            $nombreMedio = $pautaPrensa->nombreMedio;

            foreach ($pautasRecortes as $pautaRecorte) {
                $i++;
                //$equivalencia = $equivalencia + $pautaRecorte->equivalencia;
                $rutaImagen = "http://servicios.noticiasperu.pe/medios/Recortes1/".$año."/".$mes."/".$dia."/".$pautaRecorte->ruta;
                $recortes[] = ['rutaImagen'=>$rutaImagen,'codigo'=>$i];
                list($ancho,$alto) = getimagesize($rutaImagen);
                $DPI = 300;
                $CTE = 2.54;
                $ancho = round(($ancho * $CTE/$DPI),2);
                $alto = round(($alto * $CTE/$DPI),2);
                //$alto = $pautaRecorte->alto;
                //$ancho = $pautaRecorte->ancho;
            }
            $rutaPDF = "";
            if(count($pautasRecortes)>1){
                if(file_exists("http://servicios.noticiasperu.pe/medios/RecortePdf/".$pautaPrensa->codigoImagen.".pdf")){
                    $rutaPDF = "http://servicios.noticiasperu.pe/medios/RecortePdf/".$pautaPrensa->codigoImagen.".pdf";
                }
            }else{
                if($año>=2017 && $mes>=9 && $dia>=1){
                    $rutaPDF = "http://servicios.noticiasperu.pe/medios/Recortes/".$año."/".$mes."/".$dia."/OCRecortePDF/".$pautaPrensa->codigoImagen."_1.pdf";
                }else if($año>=2017 && $mes<=8 && $dia>=31){
                    $rutaPDF = "http://servicios.noticiasperu.pe/medios/Recortes3/".$año."/".$mes."/".$dia."/OCRecortePDF/".$pautaPrensa->codigoImagen."_1.pdf";
                }
            }
            $pauta[] = ['id'=>$pautaPrensa->id,'fechaPauta'=>$fechaPauta,'tipoPauta'=>$pautaPrensa->tipoPauta,'nombreMedio'=>$nombreMedio,'titular'=>$pautaPrensa->titular,'texto'=>$pautaPrensa->texto,'rutaPDF'=>$rutaPDF,'equivalencia'=>$equivalencia,'lectoria'=>$pautaPrensa->lectoria,'nombreSeccion'=>$pautaPrensa->nombreSeccion,'alto'=>$alto,'ancho'=>$ancho,'paginas'=>$pautaPrensa->paginas];
        }

        return view('vistas.vistaPrensa',['pautasPrensa'=>$pauta[0],'recortes'=>$recortes]);
    }

    public function indexTv($id)
    {
        /*$pautasTv = PautaTv::join('programasAV','programasAV.id','pautasTv.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasTv.id','=',$id)
                    ->select('pautasTv.id','pautasTv.fechaPauta','pautasTv.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasTv.titular','pautasTv.texto','pautasTv.rutaVideo','pautasTv.equivalencia')
                    ->get();*/

        $pautasTv = DB::connection('noticias_24')->table('pautatv')
                    ->join('programa','pautatv.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->where('pautatv.NumID','=',$id)
                    ->select('pautatv.NumID as id','pautatv.Fecha as fechaPauta','pautatv.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','programa.Nombre as nombrePrograma','pautatv.Titular as titular','pautatv.Texto as texto','pautatv.equivalencia as equivalencia','pautatv.Hora as horaPauta')
                    ->get();

        $pauta = array();
        foreach ($pautasTv as $pautaTv) {
            $fecha = $pautaTv->fechaPauta;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $fechaPauta = $dia.'-'.$mes.'-'.$año;
            if($pautaTv->equivalencia>0){
                $equivalencia = $pautaTv->equivalencia;
            }else{
                $equivalencia = 0;
            }
            $rutaVideo = "http://servicios.noticiasperu.pe/medios/tv/mp4_10/".$año."/".$mes."/".$dia."/".$pautaTv->id.".mp4";
            $pauta[] = ['id'=>$pautaTv->id,'fechaPauta'=>$fechaPauta,'tipoPauta'=>$pautaTv->tipoPauta,'nombreMedio'=>$pautaTv->nombreMedio,'nombrePrograma'=>$pautaTv->nombrePrograma,'titular'=>$pautaTv->titular,'texto'=>$pautaTv->texto,'rutaVideo'=>$rutaVideo,'equivalencia'=>$equivalencia,'horaPauta'=>substr($pautaTv->horaPauta,0,5)];    
        }

        return view('vistas.vistaTv',['pautasTv'=>$pauta[0]]);
    }

    public function indexRadio($id)
    {
        /*$pautasRadio = PautaRadio::join('programasAV','programasAV.id','pautasRadio.programasAV_id')
                    ->join('mediosAV','mediosAV.id','programasAV.mediosAV_id')
                    ->where('pautasRadio.id','=',$id)
                    ->select('pautasRadio.id','pautasRadio.fechaPauta','pautasRadio.tipoPauta','mediosAV.nombreMedio','programasAV.nombrePrograma','pautasRadio.titular','pautasRadio.texto','pautasRadio.rutaAudio')
                    ->get();*/

        $pautasRadio = DB::connection('noticias_24')->table('pautaradio')
                    ->join('programa','pautaradio.Programa','programa.NumID')
                    ->join('medioav','programa.Medio','medioav.NumID')
                    ->where('pautaradio.NumID','=',$id)
                    ->select('pautaradio.NumID as id','pautaradio.Fecha as fechaPauta','pautaradio.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','programa.Nombre as nombrePrograma','pautaradio.Titular as titular','pautaradio.Texto as texto','pautaradio.equivalencia as equivalencia','pautaradio.Hora as horaPauta')
                    ->get();

        $pauta = array();
        foreach ($pautasRadio as $pautaRadio) {
            $fecha = $pautaRadio->fechaPauta;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $fechaPauta = $dia.'-'.$mes.'-'.$año;
            if($pautaRadio->equivalencia>0){
                $equivalencia = $pautaRadio->equivalencia;
            }else{
                $equivalencia = 0;
            }
            $rutaAudio = "http://servicios.noticiasperu.pe/medios/radio/".$año."/".$mes."/".$dia."/".$pautaRadio->id.".mp3";
            $pauta[] = ['id'=>$pautaRadio->id,'fechaPauta'=>$fechaPauta,'tipoPauta'=>$pautaRadio->tipoPauta,'nombreMedio'=>$pautaRadio->nombreMedio,'nombrePrograma'=>$pautaRadio->nombrePrograma,'titular'=>$pautaRadio->titular,'texto'=>$pautaRadio->texto,'rutaAudio'=>$rutaAudio,'equivalencia'=>$equivalencia,'horaPauta'=>substr($pautaRadio->horaPauta,0,5)];
        }

        return view('vistas.vistaRadio',['pautasRadio'=>$pauta[0]]);
    }

    public function indexInternet($id)
    {
        /*$pautasInternet = PautaInternet::join('mediosInternet','mediosInternet.id','pautasInternet.mediosInternet_id')
                    ->where('pautasInternet.id','=',$id)
                    ->select('pautasInternet.id','pautasInternet.fechaPauta','pautasInternet.tipoPauta','mediosInternet.nombreMedio','pautasInternet.titular','pautasInternet.texto','pautasInternet.rutaImagen','pautasInternet.rutaWeb')
                    ->get();*/

        $pautasInternet = DB::connection('noticias_24')->table('pautainternetweb')
                    ->join('medioav','pautainternetweb.Medio','medioav.NumID')
                    ->where('pautainternetweb.NumID','=',$id)
                    ->select('pautainternetweb.NumID as id','pautainternetweb.Fecha as fechaPauta','pautainternetweb.tipo_servicio as tipoPauta','medioav.Nombre as nombreMedio','pautainternetweb.Titular as titular','pautainternetweb.Texto as texto','pautainternetweb.equivalencia','pautainternetweb.Ruta as rutaWeb','pautainternetweb.FechaRegistro as fechaRegistro')
                    ->get();

        $pauta = array();
        foreach ($pautasInternet as $pautaInternet) {
            $fecha = $pautaInternet->fechaPauta;
            $año = substr($fecha, 0,4);
            $mes = substr($fecha, 5,2);
            $dia = substr($fecha, 8,2);
            $fechaPauta = $dia.'-'.$mes.'-'.$año;
            if($pautaInternet->equivalencia>0){
                $equivalencia = $pautaInternet->equivalencia;
            }else{
                $equivalencia = 0;
            }
            $rutaImagen = "http://servicios.noticiasperu.pe/medios/internet/".$año."/".$mes."/".$dia."/".base64_encode($pautaInternet->id.'-'.$pautaInternet->fechaRegistro).".jpg";
            $pauta[] = ['id'=>$pautaInternet->id,'fechaPauta'=>$fechaPauta,'tipoPauta'=>$pautaInternet->tipoPauta,'nombreMedio'=>$pautaInternet->nombreMedio,'rutaWeb'=>$pautaInternet->rutaWeb,'titular'=>$pautaInternet->titular,'texto'=>$pautaInternet->texto,'rutaImagen'=>$rutaImagen,'equivalencia'=>$equivalencia];
        }

        return view('vistas.vistaInternet',['pautasInternet'=>$pauta[0]]);
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
