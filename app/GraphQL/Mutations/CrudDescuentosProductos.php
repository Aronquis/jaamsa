<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudDescuentosProductos
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
    public function Create($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $id=DB::table('cmd_descuento_producto')->insertGetId([
            'TipoOferta'=>$args['TipoOferta'],
            'Subcategoria01'=>$args['Subcategoria01'],
            'Subcategoria02'=>$args['Subcategoria02'],
            'MarcaCode'=>$args['MarcaCode'],
            'FechaInicial'=>$args['FechaInicial'],
            'FechaFinal'=>$args['FechaFinal'],
            'Categoria'=>$args['Categoria'],
            'Estado'=>$args['Estado'],
            'Valor'=>$args['Valor']
        ]);
        DB::table('cmd_descuento_producto_backup')->insertGetId([
            'TipoOferta'=>$args['TipoOferta'],
            'Subcategoria01'=>$args['Subcategoria01'],
            'Subcategoria02'=>$args['Subcategoria02'],
            'MarcaCode'=>$args['MarcaCode'],
            'FechaInicial'=>$args['FechaInicial'],
            'FechaFinal'=>$args['FechaFinal'],
            'Categoria'=>$args['Categoria'],
            'Valor'=>$args['Valor'],
            'Estado'=>$args['Estado'],
            'DescuentoProductoID'=>$id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $descuento_producto=DB::table('cmd_descuento_producto')->where('id',$id)->first();
        $descuento_producto->Marcas=DB::table('cmd_omar')->where('code',$descuento_producto->MarcaCode)->first();
        return $descuento_producto;

    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $id=DB::table('cmd_descuento_producto')->where('id',$args['id'])->update([
            'TipoOferta'=>$args['TipoOferta'],
            'Subcategoria01'=>$args['Subcategoria01'],
            'Subcategoria02'=>$args['Subcategoria02'],
            'MarcaCode'=>$args['MarcaCode'],
            'FechaInicial'=>$args['FechaInicial'],
            'FechaFinal'=>$args['FechaFinal'],
            'Categoria'=>$args['Categoria'],
            'Estado'=>$args['Estado'],
            'Valor'=>$args['Valor']
        ]);
        DB::table('cmd_descuento_producto_backup')->insertGetId([
            'TipoOferta'=>$args['TipoOferta'],
            'Subcategoria01'=>$args['Subcategoria01'],
            'Subcategoria02'=>$args['Subcategoria02'],
            'MarcaCode'=>$args['MarcaCode'],
            'FechaInicial'=>$args['FechaInicial'],
            'FechaFinal'=>$args['FechaFinal'],
            'Categoria'=>$args['Categoria'],
            'Valor'=>$args['Valor'],
            'Estado'=>$args['Estado'],
            'DescuentoProductoID'=>$args['id'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $descuento_producto=DB::table('cmd_descuento_producto')->where('id',$args['id'])->first();
        $descuento_producto->Marcas=DB::table('cmd_omar')->where('code',$descuento_producto->MarcaCode)->first();
        return $descuento_producto;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        DB::table('cmd_descuento_producto')->where('id',$args['id'])->delete();
        return "ELIMINADO";
    }
}
