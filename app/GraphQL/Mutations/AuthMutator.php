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
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class AuthMutator
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
    public function UpdateUsuario($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $CardCode=$payload->nbf;
        
        DB::beginTransaction();
        try{
            $recup_email=DB::table('cmd_ocrds')->where('E_Mail',strtoupper($args['E_Mail']))->first();
            if(isset($recup_email->E_Mail)==false && strtoupper($args['E_Mail'])!=""){
                DB::table('cmd_ocrds')
                    ->where('CardCode',$CardCode)
                    ->update([
                        'CardName'=>$args['U_BPP_BPAP'].' '.$args['U_BPP_BPAM'].' '.$args['U_BPP_BPNO'],
                        'U_BPP_BPAP'=>$args['U_BPP_BPAP'],
                        'U_BPP_BPAM'=>$args['U_BPP_BPAM'],
                        'U_BPP_BPNO'=>$args['U_BPP_BPNO'],
                        'Phone1'=>$args['Phone1'],
                        'Phone2'=>$args['Phone2'],
                        'E_Mail'=>strtoupper($args['E_Mail']),
                        'Cellular'=>$args['Cellular'],
                        'Password'=>$args['Password']
                        ]);
            }
            else{
                if($args['Password']!=""){
                    DB::table('cmd_ocrds')
                    ->where('CardCode',$CardCode)
                    ->update(
                        [
                            'CardName'=>$args['U_BPP_BPAP'].' '.$args['U_BPP_BPAM'].' '.$args['U_BPP_BPNO'],
                            'U_BPP_BPAP'=>$args['U_BPP_BPAP'],
                            'U_BPP_BPAM'=>$args['U_BPP_BPAM'],
                            'U_BPP_BPNO'=>$args['U_BPP_BPNO'],
                            'Phone1'=>$args['Phone1'],
                            'Phone2'=>$args['Phone2'],
                            'Cellular'=>$args['Cellular'],
                            'Password'=>$args['Password']
                        ]);
                    }
                else{
                    DB::table('cmd_ocrds')
                    ->where('CardCode',$CardCode)
                    ->update(
                        [
                        'CardName'=>$args['U_BPP_BPAP'].' '.$args['U_BPP_BPAM'].' '.$args['U_BPP_BPNO'],
                        'U_BPP_BPAP'=>$args['U_BPP_BPAP'],
                        'U_BPP_BPAM'=>$args['U_BPP_BPAM'],
                        'U_BPP_BPNO'=>$args['U_BPP_BPNO'],
                        'Phone1'=>$args['Phone1'],
                        'Phone2'=>$args['Phone2'],
                        'Cellular'=>$args['Cellular'],
                        ]);
                }
                
            }
            

        } 
        catch(Exception $ex){
            DB::rollback();
            return $usuario;
        }
        DB::commit();
        $usuario=DB::table('cmd_ocrds')->where('CardCode',$CardCode)->first();
        return $usuario;
    }
    public function CrearUsuarios($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {////////////original datatable name cmd_ocrd
        DB::beginTransaction();
        try {
            $usuario=null;
            $validate = Validator::make(['E_Mail'=>strtoupper($args['E_Mail'])], [
                'E_Mail' => 'required|email|unique:cmd_ocrds,E_Mail',
            ]);
            $DNI=cmd_ocrd::where('LicTradNum',$args['LicTradNum'])->first();
            if ($validate->fails()) {
                return [
                    'CardCode'=>'EL EMAIL YA EXISTE',
                    'CardName'=>'EL EMAIL YA EXISTE',
                    'LicTradNum'=>'EL EMAIL YA EXISTE',
                    'U_BPP_BPAP'=>'EL EMAIL YA EXISTE',
                    'U_BPP_BPAM'=>'EL EMAIL YA EXISTE',
                    'U_BPP_BPNO'=>'EL EMAIL YA EXISTE',
                    'E_Mail'=>'EL EMAIL YA EXISTE'
                ];
            }
            if(@$DNI->LicTradNum!=''){
                return [
                    'CardCode'=>'EL DNI/RUC YA EXISTE',
                    'CardName'=>'EL DNI/RUC YA EXISTE',
                    'LicTradNum'=>'EL DNI/RUC YA EXISTE',
                    'U_BPP_BPAP'=>'EL DNI/RUC YA EXISTE',
                    'U_BPP_BPAM'=>'EL DNI/RUC YA EXISTE',
                    'U_BPP_BPNO'=>'EL DNI/RUC YA EXISTE',
                    'E_Mail'=>'EL DNI/RUC YA EXISTE'
                ];
            }
            $usuario=new cmd_ocrd(
                ['CardCode'=>'C'.$args['LicTradNum'],
                'CardName'=>$args['U_BPP_BPAP'].' '.$args['U_BPP_BPAM'].' '.$args['U_BPP_BPNO'],
                'LicTradNum'=>$args['LicTradNum'],
                'U_BPP_BPTP'=>$args['U_BPP_BPTP'],
                'U_BPP_BPTD'=>$args['U_BPP_BPTD'],
                'U_BPP_BPAP'=>$args['U_BPP_BPAP'],
                'U_BPP_BPAM'=>$args['U_BPP_BPAM'],
                'U_BPP_BPNO'=>$args['U_BPP_BPNO'],
                'E_Mail'=>strtoupper($args['E_Mail']),
                'Phone1'=>$args['Phone1'],
                'Phone2'=>$args['Phone2'],
                'Cellular'=>$args['Cellular'],
                'Password'=>$args['Password']
                ]);
            $usuario->save();

            $email=strtoupper($args['E_Mail']);
            Mail::send('mensaje.mensajeBienvenida',['usuario'=>$usuario], function($message) use ($email) {
            $message->to($email)->subject
               ('Registro de jaamsa Perú');
            $message->from('postventa@jaamsaonline.com.pe');
            });
            
        }
        catch (Exception $ex) {
            DB::rollback();
            return $usuario;
        }
        DB::commit();
        return [
            'CardCode'=>$usuario->CardCode,
            'CardName'=>$usuario->CardName,
            'LicTradNum'=>$usuario->LicTradNum,
            'U_BPP_BPTP'=>$usuario->U_BPP_BPTP,
            'U_BPP_BPTD'=>$usuario->U_BPP_BPTD,
            'U_BPP_BPAP'=>$usuario->U_BPP_BPAP,
            'U_BPP_BPAM'=>$usuario->U_BPP_BPAM,
            'U_BPP_BPNO'=>$usuario->U_BPP_BPNO,
            'E_Mail'=>$usuario->E_Mail,
            'Phone1'=>$usuario->Phone1,
            'Phone2'=>$usuario->Phone2,
            'Cellular'=>$usuario->Cellular,
            'apitoken'=>$usuario->apitoken
            ];
    }
    public function Login($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $usuario=DB::table('cmd_ocrds')->where('E_Mail',strtoupper($args['E_Mail']))->first();
       
        if (@$usuario->E_Mail == strtoupper($args['E_Mail']) && $usuario->Password == $args['Password']) {
            $payload = array(
                "iss" => strtoupper($args['E_Mail']),
                "aud" => $args['Password'],
                "iat" => $usuario->CardName,
                "nbf" => $usuario->CardCode
            );
            $token = JWT::encode($payload, '');
            DB::table('cmd_ocrds')
              ->where('cmd_ocrds.E_Mail', strtoupper($args['E_Mail']))
              ->update(['cmd_ocrds.api_token' => $token]);
            return [
                'CardCode'=>$usuario->CardCode,
                'CardName'=>$usuario->CardName,
                'LicTradNum'=>$usuario->LicTradNum,
                'U_BPP_BPTP'=>$usuario->U_BPP_BPTP,
                'U_BPP_BPTD'=>$usuario->U_BPP_BPTD,
                'U_BPP_BPAP'=>$usuario->U_BPP_BPAP,
                'U_BPP_BPAM'=>$usuario->U_BPP_BPAM,
                'U_BPP_BPNO'=>$usuario->U_BPP_BPNO,
                'Phone1'=>$usuario->Phone1,
                'Phone2'=>$usuario->Phone2,
                'Cellular'=>$usuario->Cellular,
                'E_Mail'=>$usuario->E_Mail,
                'apitoken'=>$token,
                'typeUser'=>$usuario->typeUser
            ];
        }
        return [
            'CardCode'=>"ERROR",
            'E_Mail'=>"ERROR",
            'apitoken'=>"ERROR"
        ];
    }
    public function FogotPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $usuario=cmd_ocrd::where('E_Mail',strtoupper($args['E_Mail']))->first();
        $email=strtoupper($args['E_Mail']);
        if (isset($usuario->E_Mail)) {

            Mail::send('mensaje.forgotPassword',['usuario'=>$usuario], function($message) use ($email) {
            $message->to($email)->subject
               ('Recuperar Contraseña');
            $message->from('jaamsa@jaamsa.com');
            });
            return "Se envio correctamente";
        }
        return "Error al recuperar";
    }

}
