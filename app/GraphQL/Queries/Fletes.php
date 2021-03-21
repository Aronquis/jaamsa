<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class Fletes
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
    public function FleteLima($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $flete=DB::table('CMD_Flete_Lima')
            ->where('Id_Distrito',$args['Id_Distrito'])
            ->first();
        return $flete;
    }
    public function Sedes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        
        $sedes=DB::table('cmd_sedes')
            ->get();
        return $sedes;
    }
    public function DepartamentosAgenciasSedes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $sedes=DB::table('cmd_flete_provincia')
            ->where('cmd_flete_provincia.DepCode_Des',$args['Id_Departamento'])
            ->get()
            ->unique('ProCode_Des');
        return $sedes;
    }
    
    public function getTarifas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $tarifas=DB::table('cmd_parametros')
                ->get();
        return $tarifas;
    }
    public function getAgencias($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $tarifas=DB::table('cmd_flete_provincia')
                ->where('cmd_flete_provincia.DepCode_Des',$args['Id_Departamento'])
                ->where('cmd_flete_provincia.ProCode_Des',$args['Id_Provincia'])
                ->get()
                ->unique('cmd_flete_provincia.DepCode_Des');
        return $tarifas;
    }
    public function GetPrecioTotalFlete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $total_flete=0;
        for ($i=0; $i <\count(@$args['input']); $i++) { 
            $escala=DB::table('cmd_carrito_consolidado')
                    ->where('cmd_carrito_consolidado.ID_PRODUCTO',@$args['input'][$i]['ItemCode'])->first();
            $flete=DB::table('cmd_flete_provincia')
                    ->where('cmd_flete_provincia.DepCode_Des',$args['Id_Departamento'])
                    ->where('cmd_flete_provincia.ProCode_Des',$args['Id_Provincia'])
                    ->where('cmd_flete_provincia.ESCALA',$escala->ESCALA)
                    ->first();
            $total_flete+=@$flete->Precio*@$args['input'][$i]['Quantity'];
        }
        return $total_flete;
    }
}
