<?php

namespace App\GraphQL\Mutations;
use Illuminate\Support\Facades\Mail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Contacto
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary args that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        Mail::send('mensaje.index',['args' => $args], function ($m) use ($args) {
            $m->to([env('CORREO_ADMIN'),env('CORREO_FORMULARIO')]);
            $m->subject("Datos de formulario Jaamsa Online");
            $m->from('postventa@jaamsaonline.com.pe',@$args['email']);
        });
        return "ENVIADO";
    }
    
}
