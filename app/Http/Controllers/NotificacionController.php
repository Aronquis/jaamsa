<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    //
    
    public function Notificacion(Request $request){
        try{
            $hashString=$request->header('PE-Signature');
            $data_requets=$request->getContent();
            if(isset($hashString)==true){
                
                $token=hash_hmac('sha256', $data_requets, '71MMzwDOVs7kxXH+j5zjkkKPxB+fv/Q1zaBcUPMF');
    
                $transCode=$request->get('data');
                $fecha_pago=$transCode['paymentDate'];
                $id_pedido=$transCode['transactionCode'];
                
                @$pedido=DB::table('cmd_ordr')->where('DocEntry',$id_pedido)->first();
                #falta validar esto && $token==$hashString
                if(isset($pedido->DocEntry)==true ){
                    DB::table('cmd_ordr')->where('DocEntry',$id_pedido)->update([
                        'Id_EstPed'=>2,
                        'TransDate'=>date('Y-m-d H:i:s',strtotime($fecha_pago))
                    ]);
                }
                else{
                    abort(500);
                }
                return \response()->json(http_response_code());
            }
            else{
                abort(404, 'Unauthorized action.');
            }
        }
        catch(\Illuminate\Database\QueryException $ex){
            abort(404, 'Unauthorized action.');
        }
        
    }
}
