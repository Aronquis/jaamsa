<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudDescuentoFlete
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
        $id=DB::table('cmd_descuento_flete')->insertGetId([
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
        DB::table('cmd_descuento_flete_backup')->insertGetId([
            'TipoOferta'=>$args['TipoOferta'],
            'Subcategoria01'=>$args['Subcategoria01'],
            'Subcategoria02'=>$args['Subcategoria02'],
            'MarcaCode'=>$args['MarcaCode'],
            'FechaInicial'=>$args['FechaInicial'],
            'FechaFinal'=>$args['FechaFinal'],
            'Categoria'=>$args['Categoria'],
            'Valor'=>$args['Valor'],
            'Estado'=>$args['Estado'],
            'DescuentofleteID'=>$id,
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $descuento_flete=DB::table('cmd_descuento_flete')->where('id',$id)->first();
        $descuento_flete->Marcas=DB::table('cmd_omar')->where('code',$descuento_flete->MarcaCode)->first();
        return $descuento_flete;

    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $id=DB::table('cmd_descuento_flete')->where('id',$args['id'])->update([
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
        DB::table('cmd_descuento_flete_backup')->insertGetId([
            'TipoOferta'=>$args['TipoOferta'],
            'Subcategoria01'=>$args['Subcategoria01'],
            'Subcategoria02'=>$args['Subcategoria02'],
            'MarcaCode'=>$args['MarcaCode'],
            'FechaInicial'=>$args['FechaInicial'],
            'FechaFinal'=>$args['FechaFinal'],
            'Categoria'=>$args['Categoria'],
            'Valor'=>$args['Valor'],
            'Estado'=>$args['Estado'],
            'DescuentofleteID'=>$args['id'],
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s")
        ]);
        $descuento_flete=DB::table('cmd_descuento_flete')->where('id',$args['id'])->first();
        $descuento_flete->Marcas=DB::table('cmd_omar')->where('code',$descuento_flete->MarcaCode)->first();
        return $descuento_flete;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        DB::table('cmd_descuento_flete')->where('id',$args['id'])->delete();
        return "ELIMINADO";
    }
}
