<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class DescuentoProductos
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
    public function GetAllDescuentoProductos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $descuento_productos=DB::table('cmd_descuento_producto')
                ->orderBy('id', 'desc')
                ->get();
        foreach($descuento_productos as $descuento_producto){
            $descuento_producto->Marcas=DB::table('cmd_omar')->where('code',$descuento_producto->MarcaCode)->first();
        }
        return $descuento_productos;
    }
    public function GetIDDescuentoProductos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $descuento_productos=DB::table('cmd_descuento_producto')
                ->where('id', $args['id'])
                ->first();
        $descuento_productos->Marcas=DB::table('cmd_omar')->where('code',$descuento_productos->MarcaCode)->first(); 
        return $descuento_productos;
    }
}
