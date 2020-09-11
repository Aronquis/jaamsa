<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\cmd_ocrd;
use App\Services\JWTServices;
use Firebase\JWT\JWT;
use JWTAuth;
use Hash;
use Validator;
class ClientePedidos
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
    public function ClientePedidos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $clientesPedidos=DB::table('cmd_ordr')
                ->join('cmd_ocrds','cmd_ordr.CardCode','=','cmd_ocrds.CardCode')
                ->orderByDesc("cmd_ordr.DocEntry")
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach($clientesPedidos as $pedidos){
            $pedidos->data1=DB::table('cmd_ocrds')->where('cmd_ocrds.CardCode',$pedidos->CardCode)->first();
        }
        return ['NroItems'=>$clientesPedidos->total(),'data'=>$clientesPedidos];
    }
    public function PedidosDetalle($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
     
        $pedidos=DB::table('cmd_rdr1')
            ->join('cmd_carrito_consolidado','cmd_carrito_consolidado.ID_PRODUCTO','=','cmd_rdr1.ItemCode')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            ->select('cmd_rdr1.*','cmd_carrito_consolidado.*','cmd_itm1.*')
            ->orderByDesc("cmd_rdr1.DocEntry")
            ->where('cmd_rdr1.DocEntry',$args['DocEntry'])->get();
        foreach($pedidos as $pedido){
            if((Int)$pedido->OnHand>0){              
                $pedido->STATESTOCK="DISPONIBLE";
            }
            else{
                $pedido->STATESTOCK="AGOTADO";
            }
        }
        return $pedidos; 
    }
    public function DireccionPedido($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $pedidos=DB::table('cmd_crd1')
                ->leftjoin('cmd_departamento','cmd_departamento.Code','=','cmd_crd1.Id_Departamento')
                ->leftjoin('cmd_provincia',function($join){
                    $join->on("cmd_provincia.DepCode","=","cmd_crd1.Id_Departamento")
                        ->on("cmd_provincia.Code","=","cmd_crd1.Id_Provincia");
                })
                ->leftjoin('cmd_distrito',function($join){
                    $join->on("cmd_distrito.DepCode","=","cmd_crd1.Id_Departamento")
                        ->on("cmd_distrito.ProCode","=","cmd_crd1.Id_Provincia")
                        ->on("cmd_distrito.Code","=","cmd_crd1.Id_Distrito");
                })
                ->select('cmd_crd1.*','cmd_departamento.Name as Departamento','cmd_provincia.Name as Provincia','cmd_distrito.Name as Distrito')
                ->where('cmd_crd1.CardCode',$args['CardCode'])
                ->orderByDesc("cmd_crd1.CardCode")
                ->get();
        
        return $pedidos; 
    }
    public function EstadoPago($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $estados=DB::table('cmd_oepe')
                ->get();
        return $estados; 
    }
    public function Bancos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $bancos=DB::table('cmd_odsc')
                ->get();
        return $bancos; 
    }
    public function ClientesDetalleDocEntry($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        
        $pedido=DB::table('cmd_ordr')
                ->where('cmd_ordr.DocEntry',$args['DocEntry'])
                ->first();
        $pedido->data=DB::table('cmd_rdr1')
                    ->join('cmd_carrito_consolidado','cmd_carrito_consolidado.ID_PRODUCTO','=','cmd_rdr1.ItemCode')
                    ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
                    ->where('cmd_rdr1.DocEntry',$args['DocEntry'])
                    ->orderByDesc("cmd_rdr1.DocEntry")
                    ->get();
        $pedido->data1=DB::table('cmd_ocrds')
                ->where('cmd_ocrds.CardCode',$pedido->CardCode)
                ->first();
        return $pedido; 
    }
    public function ClienteIdPedido($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Bearer=$context->request->headers->get('authorization');
        $token=substr($Bearer,7);
        $payload = JWT::decode($token, '', array('HS256'));
        $email=$payload->iss;
        $CardCode=$payload->nbf;
        
        $pedidos=DB::table('cmd_ordr')
                ->where('cmd_ordr.CardCode',$CardCode)
                ->orderByDesc("cmd_ordr.DocEntry")
                ->get();
        foreach($pedidos as $pedido){
                $pedido->data=DB::table('cmd_rdr1')
                ->join('cmd_carrito_consolidado','cmd_carrito_consolidado.ID_PRODUCTO','=','cmd_rdr1.ItemCode')
                ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
                ->select('cmd_rdr1.*','cmd_carrito_consolidado.*','cmd_itm1.*')
                ->where('cmd_rdr1.DocEntry',$pedido->DocEntry)->get();
        }
        return $pedidos; 
    }
    public function GetBanco($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $banco=DB::table('cmd_dsc1')
                ->where('cmd_dsc1.BankCode',$args['id_banco'])
                ->first();
        return $banco;
    }
    
}
