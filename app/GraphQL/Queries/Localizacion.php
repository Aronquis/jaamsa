<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
class Localizacion
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
    public function GetDepartamentos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $departamentos=DB::table('cmd_departamento')
            ->select('cmd_departamento.*')
            ->get();
        return $departamentos;

    }
    public function GetProvincias($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $provincias=DB::table('cmd_provincia')
            ->select('cmd_provincia.*')
            ->where('cmd_provincia.DepCode',$args['DepCode'])
            ->get();
        return $provincias;
    }
    public function GetDistritos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $distritos=DB::table('cmd_distrito')
            ->select('cmd_distrito.*')
            ->where('cmd_distrito.DepCode',$args['DepCode'])
            ->where('cmd_distrito.ProCode',$args['ProCode'])
            ->get();
        return $distritos;
    }
}
