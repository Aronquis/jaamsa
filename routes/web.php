<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //$usuario=DB::table('cmd_ocrds')->where('CardCode','C48331424')->first();

    //return response()->view('mensaje.mensajeBienvenida',['usuario'=>$usuario], 200);
    //$usuario=DB::table('cmd_ocrds')->where('CardCode','C72641188')->first();
    //@$pedido=DB::table('cmd_ordr')->where('DocEntry',98246)->first();
    //return response()->view('mensajePedido.mensaje',['usuario'=>$usuario,'pedido'=>$pedido], 200);
    /*
    $usuario=DB::table('cmd_ocrds')->where('CardCode','C72641188')->first();
    @$pedido=DB::table('cmd_ordr')->where('DocEntry',98246)->first();
    $detalle_pedido=DB::table('cmd_rdr1')
                ->join('cmd_carrito_consolidado','cmd_rdr1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
                ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_rdr1.ItemCode')
                ->where('DocEntry',$pedido->DocEntry)->get();
    return response()->view('mensajePedido.index',['usuario'=>$usuario,'pedido'=>$pedido,'detalle_pedido'=>$detalle_pedido], 200);
    
    return view('welcome');*/
    date_default_timezone_set('America/Lima');
    $ruta="https://pre1a.services.pagoefectivo.pe/v1/authorizations";
    $data = array(
        "accessKey"=>"YjY1NDU3OTVkOGQ4MDA1",
        "secretKey" =>'71MMzwDOVs7kxXH+j5zjkkKPxB+fv/Q1zaBcUPMF',
        "idService"=>"1185",
        "dateRequest"=>date('c'),
        "hashString"=>hash('sha256',"1185".".YjY1NDU3OTVkOGQ4MDA1".'.71MMzwDOVs7kxXH+j5zjkkKPxB+fv/Q1zaBcUPMF.'.date('c'))
    );
    $data_json = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ruta);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        )
    );
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respuesta  = curl_exec($ch);
    curl_close($ch);
    $leer_respuesta = json_decode($respuesta, true);
    if (isset($leer_respuesta['errors'])) {
        return $leer_respuesta['errors'];
    } else {
        ////SEGUNDA API ////
        $ruta2="https://pre1a.services.pagoefectivo.pe/v1/cips";
        $data2 = array(
            "currency"=> "PEN",
            "amount"=> 1.00,
            "transactionCode"=> "101",
            "adminEmail"=> "integrationproject.pe6@gmail.com",
            "dateExpiry"=> "2020-12-31 23:59:59-05:00",
            "paymentConcept"=> "Prueba - Validar",
            "additionalData"=> "datos adicionales",
            "userEmail"=> "integrationproject.pe6@gmail.com",
            "userId"=> "001",
            "userName"=> "Aron",
            "userLastName"=> "Avila",
            "userUbigeo"=> "010101", 
            "userCountry"=> "PERU",
            "userDocumentType"=> "DNI",
            "userDocumentNumber"=> "12345678",
            "userCodeCountry" => "+51",
            "userPhone" => "956957535",
            "serviceId"=> 1185
        );
        $data_json2 = json_encode($data2);
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $ruta2);
        curl_setopt(
            $ch2, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$leer_respuesta['data']['token'],
                'Origin: web',
                'Accept-Language: es-PE',
                'Content-Type: application/json',
            )
        );
        curl_setopt($ch2, CURLOPT_POST, 1);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch2, CURLOPT_POSTFIELDS,$data_json2);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $respuesta2  = curl_exec($ch2);
        curl_close($ch2);
        $leer_respuesta2 = json_decode($respuesta2, true);
        dd($leer_respuesta2);
        if (isset($leer_respuesta2['errors'])) {
            return $leer_respuesta2['errors'];
        }
        else{
            return  $leer_respuesta2;
        }
        //////
    }
});
