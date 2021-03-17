<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class CrudBanners
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
        $id=DB::table('cmd_banners')->insertGetId([
            'Nombre'=>$args['Nombre'],
            'BannerUrl'=>$args['BannerUrl'],
            'Link'=>$args['Link'],
            'TipoLink'=>$args['TipoLink']
        ]);
        $banner=DB::table('cmd_banners')->where('Id',$id)->first();
        return $banner;
    }
    public function Update($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $id=DB::table('cmd_banners')->where('Id',$args['Id'])->update([
            'Nombre'=>$args['Nombre'],
            'BannerUrl'=>$args['BannerUrl'],
            'Link'=>$args['Link'],
            'TipoLink'=>$args['TipoLink']
        ]);
        $banner=DB::table('cmd_banners')->where('Id',$args['Id'])->first();
        return $banner;
    }
    public function Delete($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        DB::table('cmd_banners')->where('Id',$args['Id'])->delete();
        return "EXITO";
    }
}
