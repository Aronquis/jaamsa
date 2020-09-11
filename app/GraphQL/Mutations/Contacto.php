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
            $m->to('contactanos@abrgrupoconsultor.pe');
            $m->subject(@$args['subject']);
            $m->from('contactanos@abrgrupoconsultor.pe',@$args['email']);
        });
           
        return ['typeContact'=>$args['typeContact'],
        'name'=>$args['name'],
        'dni'=>$args['dni'],
        'ruc'=>$args['ruc'],
        'email'=>$args['email'],
        'phone'=>$args['phone'],
        'schedule'=>$args['schedule'],
        'subject'=>$args['subject'],
        'bodyMessage'=>$args['bodyMessage']];
    }
    
}
