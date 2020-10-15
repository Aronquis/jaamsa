<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\cmd_ocrd;
use App\Services\JWTServices;
use Firebase\JWT\JWT;
use JWTAuth;
use Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use Illuminate\Support\Facades\DB;
class Pedidos
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function UpdateDireccion($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $CardCode=$payload->nbf;
        ////// RefNum es estado
        //direccion facturacions
        $direccion=null;
        if($args['AdresType']=='B' && isset($CardCode)){
            DB::table('cmd_crd1')->where('CardCode',$CardCode)->where('AdresType','B')->update([
                'Address'=>$args['Address'],'Country'=>$args['Country'],
                'Direccion'=>$args['Direccion'],'Id_Provincia'=>$args['Id_Provincia'],'Id_Departamento'=>$args['Id_Departamento'],
                'Id_Distrito'=>$args['Id_Distrito'],'AdresType'=>$args['AdresType']
            ]);
            $direccion=DB::table('cmd_crd1')->where('CardCode',$CardCode)->where('AdresType','B')->first();
            $departamento=DB::table('cmd_departamento')->where('Code',$direccion->Id_Departamento)->first();
            $provincia=DB::table('cmd_provincia')
                    ->where('DepCode',$direccion->Id_Departamento)
                    ->where('Code',$direccion->Id_Provincia)
                    ->first();
            $distrito=DB::table('cmd_distrito')
                    ->where('DepCode',$direccion->Id_Departamento)
                    ->where('ProCode',$direccion->Id_Provincia)
                    ->where('Code',$direccion->Id_Distrito)
                    ->first();
            return[
                'CardCode'=>$direccion->CardCode,
                'Address'=>$direccion->Address,
                'Country'=>$direccion->Country,
                'Direccion'=>$direccion->Direccion,
                'Id_Departamento'=>$direccion->Id_Departamento,
                'Departamento'=>$departamento->Name,
                'Id_Provincia'=>$direccion->Id_Provincia,
                'Provincia'=>$provincia->Name,
                'Id_Distrito'=>$direccion->Id_Distrito,
                'Distrito'=>$distrito->Name,
                'AdresType'=>$direccion->AdresType,
            ];
        }
        if($args['AdresType']=='S'&& isset($CardCode)){
            DB::table('cmd_crd1')->where('CardCode',$CardCode)->where('AdresType','S')->update([
                'Address'=>$args['Address'],'Country'=>$args['Country'],
                'Direccion'=>$args['Direccion'],'Id_Provincia'=>$args['Id_Provincia'],'Id_Departamento'=>$args['Id_Departamento'],
                'Id_Distrito'=>$args['Id_Distrito'],'AdresType'=>$args['AdresType']
            ]);
            $direccion=DB::table('cmd_crd1')->where('CardCode',$CardCode)->where('AdresType','S')->first();
            $departamento=DB::table('cmd_departamento')->where('Code',$direccion->Id_Departamento)->first();
            $provincia=DB::table('cmd_provincia')
                    ->where('DepCode',$direccion->Id_Departamento)
                    ->where('Code',$direccion->Id_Provincia)
                    ->first();
            $distrito=DB::table('cmd_distrito')
                    ->where('DepCode',$direccion->Id_Departamento)
                    ->where('ProCode',$direccion->Id_Provincia)
                    ->where('Code',$direccion->Id_Distrito)
                    ->first();
            return[
                'CardCode'=>$direccion->CardCode,
                'Address'=>$direccion->Address,
                'Country'=>$direccion->Country,
                'Direccion'=>$direccion->Direccion,
                'Id_Departamento'=>$direccion->Id_Departamento,
                'Departamento'=>$departamento->Name,
                'Id_Provincia'=>$direccion->Id_Provincia,
                'Provincia'=>$provincia->Name,
                'Id_Distrito'=>$direccion->Id_Distrito,
                'Distrito'=>$distrito->Name,
                'AdresType'=>$direccion->AdresType,
            ];
        }
        else{
            return $direccion;
        }
    }
    public function UpdateEstado($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        ////// RefNum es estado
        if(isset($args['Id_EstPed'])==true && isset($args['RefNum'])==false){
            DB::table('cmd_ordr')
                ->where('DocEntry',$args['DocEntry'])
                ->update(['Id_EstPed'=>$args['Id_EstPed']]);
            $pedidos= DB::table('cmd_ordr')
                    ->where('DocEntry',$args['DocEntry'])
                    ->first();
            return [
                'DocEntry'=>$pedidos->DocEntry,
                'CardCode'=>$pedidos->CardCode,
                'DocCur'=>$pedidos->DocCur,
                'DocDate'=>$pedidos->DocDate,
                'DocTotal'=>$pedidos->DocTotal,
                'CMD_TipEnt'=>$pedidos->CMD_TipEnt,
                'CMD_MetPag'=>$pedidos->CMD_MetPag,
                'BankCode'=>$pedidos->BankCode,
                'TransDate'=>$pedidos->TransDate,
                'RefNum'=>$pedidos->RefNum,
                'CMD_FecExpPE'=>$pedidos->CMD_FecExpPE,
                'Id_EstPed'=>$pedidos->Id_EstPed,
                'OINV_Address'=>$pedidos->OINV_Address,
                'ODLN_Address'=>$pedidos->ODLN_Address,
            ];
        }
        if(isset($args['RefNum'])==true && isset($args['Id_EstPed'])==false){
            DB::table('cmd_ordr')
                ->where('DocEntry',$args['DocEntry'])
                ->update(['RefNum'=>$args['RefNum']]);
            $pedidos= DB::table('cmd_ordr')
                    ->where('DocEntry',$args['DocEntry'])
                    ->first();
            return [
                'DocEntry'=>$pedidos->DocEntry,
                'CardCode'=>$pedidos->CardCode,
                'DocCur'=>$pedidos->DocCur,
                'DocDate'=>$pedidos->DocDate,
                'DocTotal'=>$pedidos->DocTotal,
                'CMD_TipEnt'=>$pedidos->CMD_TipEnt,
                'CMD_MetPag'=>$pedidos->CMD_MetPag,
                'BankCode'=>$pedidos->BankCode,
                'TransDate'=>$pedidos->TransDate,
                'CMD_FecExpPE'=>$pedidos->CMD_FecExpPE,
                'RefNum'=>$pedidos->RefNum,
                'Id_EstPed'=>$pedidos->Id_EstPed,
                'OINV_Address'=>$pedidos->OINV_Address,
                'ODLN_Address'=>$pedidos->ODLN_Address,
            ];
        }
        
    }
    public function RegistrarPedido($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
       
        // TODO implement the resolver
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $CardCode=$payload->nbf;
        $pedido=null;
        DB::beginTransaction();
        try {
            ////7recup
            
            ///REGISTRAR DIRECCIONES
            @$boleta_facturacion=DB::table('cmd_crd1')->where('AdresType','B')->where('CardCode',$CardCode)->first();
            @$entrega=DB::table('cmd_crd1')->where('AdresType','S')->where('CardCode',$CardCode)->first();
            $OINV_Address="";
            $ODLN_Address="";
            if(isset($entrega->CardCode)==true && $args['AdresType']=='B'){
                DB::table('cmd_crd1')->where('CardCode',@$CardCode)->where('AdresType','B')->update([
                    'Address'=>@$args['Address'],'Country'=>@$args['Country'],
                    'Direccion'=>@$args['Direccion'],'Id_Provincia'=>@$args['Id_Provincia'],'Id_Departamento'=>@$args['Id_Departamento'],
                    'Id_Distrito'=>@$args['Id_Distrito'],'AdresType'=>'B'
                ]);
                $departamento=DB::table('cmd_departamento')->where('Code',$args['Id_Departamento'])->first();
                $provincia=DB::table('cmd_provincia')
                        ->where('DepCode',$args['Id_Departamento'])
                        ->where('Code',$args['Id_Provincia'])
                        ->first();
                $distrito=DB::table('cmd_distrito')
                        ->where('DepCode',$args['Id_Departamento'])
                        ->where('ProCode',$args['Id_Provincia'])
                        ->where('Code',$args['Id_Distrito'])
                        ->first();
                $OINV_Address=@$departamento->Name.'-'.@$provincia->Name.'-'.@$distrito->Name.'-'.@$args['Direccion'];   
            }
            if(isset($entrega->CardCode)==true && $args['AdresTypeEnvio']=='S'){
                $direcion_sede="";
                    if(@$args['Sede']!=""){
                        $direcion_sede=' SEDE:'.$args['Sede'];
                }
                if($args['Id_DepartamentoEnvio']!="15" && $args['AgeDir']!="" && $args['AgeDes']!="" && $args['AgeCode']!=""){
                    DB::table('cmd_crd1')->where('CardCode',$CardCode)->where('AdresType','S')->update([
                        'Address'=>@$args['AddressEnvio'],'Country'=>@$args['CountryEnvio'],'Direccion'=>@$args['AgeDes'].'-'.@$args['AgeDir'].@$direcion_sede,
                        'Id_Provincia'=>@$args['Id_ProvinciaEnvio'],'Id_Departamento'=>@$args['Id_DepartamentoEnvio'],'Id_Distrito'=>'','AdresType'=>'S'
                    ]);
                    $recu_provincia_envio=DB::table('cmd_flete_provincia')
                                        ->where('cmd_flete_provincia.DepCode_Des',$args['Id_DepartamentoEnvio'])
                                        ->where('cmd_flete_provincia.ProCode_Des',$args['Id_ProvinciaEnvio'])
                                        ->first();
                    $ODLN_Address=@$recu_provincia_envio->DepDes_Des.'-'.@$recu_provincia_envio->ProDes_Des.'-'.@$args['AgeDes'].'-'.@$args['AgeDir'].@$direcion_sede;
                }
                else{
                    DB::table('cmd_crd1')->where('CardCode',$CardCode)->where('AdresType','S')->update([
                        'Address'=>$args['AddressEnvio'],'Country'=>$args['CountryEnvio'],'Direccion'=>$args['DireccionEnvio'],
                        'Id_Provincia'=>$args['Id_ProvinciaEnvio'],'Id_Departamento'=>$args['Id_DepartamentoEnvio'],
                        'Id_Distrito'=>$args['Id_DistritoEnvio'],'AdresType'=>'S'
                    ]);
                    $departamento=DB::table('cmd_departamento')->where('Code',$args['Id_DepartamentoEnvio'])->first();
                    $provincia=DB::table('cmd_provincia')
                            ->where('DepCode',$args['Id_DepartamentoEnvio'])
                            ->where('Code',$args['Id_ProvinciaEnvio'])
                            ->first();
                    $distrito=DB::table('cmd_distrito')
                            ->where('DepCode',$args['Id_DepartamentoEnvio'])
                            ->where('ProCode',$args['Id_ProvinciaEnvio'])
                            ->where('Code',$args['Id_DistritoEnvio'])
                            ->first();
                    $ODLN_Address=@$departamento->Name.'-'.@$provincia->Name.'-'.@$distrito->Name.'-'.@$args['DireccionEnvio'].@$direcion_sede;
                }
                  
            }
            if(isset($boleta_facturacion->CardCode)==false && $args['AdresType']=='B'){
                DB::table('cmd_crd1')
                ->insert([
                    'CardCode'=>$CardCode,'Address'=>@$args['Address'],'Country'=>@$args['Country'],
                    'Direccion'=>@$args['Direccion'],'Id_Provincia'=>@$args['Id_Provincia'],'Id_Departamento'=>@$args['Id_Departamento'],
                    'Id_Distrito'=>@$args['Id_Distrito'],'AdresType'=>@$args['AdresType']
                ]);
                $departamento=DB::table('cmd_departamento')->where('Code',$args['Id_Departamento'])->first();
                $provincia=DB::table('cmd_provincia')
                        ->where('DepCode',$args['Id_Departamento'])
                        ->where('Code',$args['Id_Provincia'])
                        ->first();
                $distrito=DB::table('cmd_distrito')
                        ->where('DepCode',$args['Id_Departamento'])
                        ->where('ProCode',$args['Id_Provincia'])
                        ->where('Code',$args['Id_Distrito'])
                        ->first();
                $OINV_Address=@$departamento->Name.'-'.@$provincia->Name.'-'.@$distrito->Name.'-'.@$args['Direccion'];
            }
            if(isset($entrega->CardCode)==false && $args['AdresTypeEnvio']=='S'){
                $direcion_sede="";
                    if(@$args['Sede']!=""){
                        $direcion_sede=' SEDE:'.$args['Sede'];
                }
                if($args['Id_DepartamentoEnvio']!="15" && $args['AgeDir']!="" && $args['AgeDes']!="" && $args['AgeCode']!=""){
                    DB::table('cmd_crd1')
                    ->insert([
                        'CardCode'=>$CardCode,'Address'=>@$args['AddressEnvio'],'Country'=>@$args['CountryEnvio'],
                        'Direccion'=>@$args['AgeDes'].'-'.@$args['AgeDir'].@$direcion_sede,'Id_Provincia'=>@$args['Id_ProvinciaEnvio'],'Id_Departamento'=>@$args['Id_DepartamentoEnvio'],
                        'Id_Distrito'=>'','AdresType'=>@$args['AdresTypeEnvio']
                    ]);
                    $recu_provincia_envio=DB::table('cmd_flete_provincia')
                                        ->where('cmd_flete_provincia.DepCode_Des',@$args['Id_DepartamentoEnvio'])
                                        ->where('cmd_flete_provincia.ProCode_Des',@$args['Id_ProvinciaEnvio'])
                                        ->first();
                    $ODLN_Address=@$recu_provincia_envio->DepDes_Des.'-'.@$recu_provincia_envio->ProDes_Des.'-'.@$args['AgeDes'].'-'.@$args['AgeDir'].@$direcion_sede;
                }
                else{
                    DB::table('cmd_crd1')
                    ->insert([
                        'CardCode'=>$CardCode,'Address'=>@$args['AddressEnvio'],'Country'=>@$args['CountryEnvio'],
                        'Direccion'=>@$args['DireccionEnvio'],'Id_Provincia'=>@$args['Id_ProvinciaEnvio'],'Id_Departamento'=>@$args['Id_DepartamentoEnvio'],
                        'Id_Distrito'=>@$args['Id_DistritoEnvio'],'AdresType'=>@$args['AdresTypeEnvio']
                    ]);
                    $departamento=DB::table('cmd_departamento')->where('Code',$args['Id_DepartamentoEnvio'])->first();
                    $provincia=DB::table('cmd_provincia')
                            ->where('DepCode',$args['Id_DepartamentoEnvio'])
                            ->where('Code',$args['Id_ProvinciaEnvio'])
                            ->first();
                    $distrito=DB::table('cmd_distrito')
                            ->where('DepCode',$args['Id_DepartamentoEnvio'])
                            ->where('ProCode',$args['Id_ProvinciaEnvio'])
                            ->where('Code',$args['Id_DistritoEnvio'])
                            ->first();
                    $ODLN_Address=@$departamento->Name.'-'.@$provincia->Name.'-'.@$distrito->Name.'-'.@$args['DireccionEnvio'].@$direcion_sede;
                }
                
            }
            //validar
            $ultimoRegistro = (Int)DB::table('cmd_rdr1')->whereRaw('DocEntry = (select max(`DocEntry`) from cmd_rdr1)')->first()->DocEntry;
            $Total=0;
            foreach($args['data'] as $totalPrecio){
                $Total+=(Float)$totalPrecio['Price']*(Int)$totalPrecio['Quantity'];
            }
            DB::table('cmd_ordr')
                ->insert([
                    'DocEntry'=>@$ultimoRegistro+1,'CardCode'=>@$CardCode,'DocCur'=>@$args['DocCur'],'DocDate'=>@$args['DocDate'],
                    'DocTotal'=>@$Total,'CMD_MetPag'=>@$args['CMD_MetPag'],'CMD_TipEnt'=>@$args['CMD_TipEnt'],'BankCode'=>@$args['BankCode'],'TransDate'=>@$args['TransDate'],
                    'RefNum'=>@$args['RefNum'],'OINV_Address'=>$OINV_Address,'ODLN_Address'=>$ODLN_Address,'Id_EstPed'=>@$args['Id_EstPed'],
                    'CMD_FecExpPE'=>date('Y-m-d H:i:s',strtotime(@$args['CMD_FecExpPE'])),'Total_Flete'=>@$args['Precio']
            ]);
            foreach($args['data'] as $detallePedido){
                
                DB::table('cmd_rdr1')
                ->insert(['DocEntry'=>@$ultimoRegistro+1,'ItemCode'=>@$detallePedido['ItemCode'],
                'Quantity'=>(Int)@$detallePedido['Quantity'],'Price'=>(Float)@$totalPrecio['Price']
                ]);

                $producto_recu=DB::table('cmd_itm1')->where('ItemCode',@$detallePedido['ItemCode'])->first();
                $actualizar_stock=(Int)$producto_recu->OnHand-(Float)@$detallePedido['Quantity'];
                DB::table('cmd_itm1')
                    ->where('ItemCode',@$detallePedido['ItemCode'])
                    ->update([
                        'OnHand'=>$actualizar_stock,
                ]);
            }
        }
        catch (Exception $ex) {
            DB::rollback();
           
        }
        DB::commit();
        
        @$usuario=DB::table('cmd_ocrds')->where('CardCode',$CardCode)->first();
        
        @$pedido=DB::table('cmd_ordr')->where('DocEntry',$ultimoRegistro+1)->first();
        
        @$detalle_pedido=DB::table('cmd_rdr1')
                         ->join('cmd_carrito_consolidado','cmd_rdr1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
                         ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_rdr1.ItemCode')
                         ->where('DocEntry',@$pedido->DocEntry)->get();
        $direccion=@$args['DireccionEnvio'];
        $agencia=@$args['AgeDes'];
        /// Validar el tipo de documento
        $tipo_documento="NAN";
        switch (@$usuario->U_BPP_BPTD) {
            case 1:
                $tipo_documento="DNI"; 
                break;
            case 4:
                $tipo_documento="NAN";
                break;
            case 7:
                $tipo_documento="PAS"; 
                break;
            case 0:
                $tipo_documento="NAN"; 
                break;
        }
         
         Mail::send('mensajePedido.index',['usuario'=>$usuario,
                                            'direccion'=>$direccion,'agencia'=>$agencia,'pedido'=>$pedido,'detalle_pedido'=>$detalle_pedido], function($message) use ($email,$ultimoRegistro) {
            $message->to([$email,'postventa@jaamsa.com'])->subject
               ('Confirmacion de pedido #'.($ultimoRegistro+1));
            $message->from('postventa@jaamsa.com');
         });
        //////pasarela de pagos
        if($args['CMD_MetPag']==3){
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
                @$pedido->Pagos=$leer_respuesta['errors'];
            } else {
                ////SEGUNDA API ////
                $ruta2="https://pre1a.services.pagoefectivo.pe/v1/cips";
                $data2 = array(
                    "currency"=> "PEN",
                    "amount"=>number_format((Float)@$pedido->DocTotal + (Float)@$args['Precio'], 2, '.', ''),
                    "transactionCode"=> @$pedido->DocEntry,
                    "adminEmail"=> "postventa@jaamsaonline.com.pe",
                    "dateExpiry"=> (String)@$args['CMD_FecExpPE'],
                    "paymentConcept"=> "Venta",
                    "additionalData"=> "Venta",
                    "userEmail"=> @$usuario->E_Mail,
                    "userId"=> @$usuario->CardCode,
                    "userName"=> @$usuario->U_BPP_BPNO,
                    "userLastName"=> @$usuario->U_BPP_BPAP.' '.@$usuario->U_BPP_BPAM,
                    "userUbigeo"=> $args['Id_DepartamentoEnvio'].$args['Id_ProvinciaEnvio'].$args['Id_DistritoEnvio'], 
                    "userCountry"=> "PERU",
                    "userDocumentType"=> $tipo_documento,
                    "userDocumentNumber"=> @$usuario->LicTradNum,
                    "userCodeCountry" => "+51",
                    "userPhone" => @$usuario->Phone1,
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
                
                if (isset($leer_respuesta2['errors'])) {
                    @$pedido->Pagos=@$leer_respuesta2['errors'];
                }
                else{
                    DB::table('cmd_ordr')->where('DocEntry',$ultimoRegistro+1)->update([
                    'RefNum'=>@$leer_respuesta2['data']['cip'],
                    'url_pagoefectivo'=>$leer_respuesta2['data']['cipUrl']
                    ]);       
                    @$pedido->Pagos=@$leer_respuesta2['data'];
                }
                //////
            }
            return  $pedido;
        }
        else{
            return  $pedido;
        }
        
        
    }
}
