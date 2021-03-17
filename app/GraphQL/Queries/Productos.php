<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
class Productos
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
    
    public function getProductos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            ->join('cmd_ocla','cmd_ocla.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })
            //->where('cmd_categorias.U_CMD_Activo',1)
            ->where('cmd_ocla.U_CMD_ClaIte',$args['type'])
            ->orderBy('cmd_ocla.U_CMD_Orden', 'desc')
            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);   
        foreach($productos as $producto){
            if((int)$producto->OnHand>0){
                $producto->STATESTOCK="DISPONIBLE";
            }
            else{
                $producto->STATESTOCK="AGOTADO";
            }      
        }
        return['NroItems'=>$productos->total(),'data'=>$productos];
    }
    public function getProductoID($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')
            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })
            ->where('cmd_categorias.U_CMD_Activo',1)
            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->where('cmd_carrito_consolidado.ID_PRODUCTO',$args['id_producto'])
            ->first();
        if((int)$productos->OnHand>0){
            $productos->STATESTOCK="DISPONIBLE";
        }
        else{
            $productos->STATESTOCK="AGOTADO";
        }
        
        return $productos;
    }
    public function getProductoRelacionados($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $productos_relacionados=null;
        if(isset($args['slug'])==true && isset($args['slug1'])==true && isset($args['slug2'])==true){
            $slug=DB::table('cmd_categorias')->where('cmd_categorias.Slug',$args['slug'])->first();
            $slug1=DB::table('cmd_subcategoria01')->where('cmd_subcategoria01.Slug',$args['slug1'])->first();
            $slug2=DB::table('cmd_subcategoria02')->where('cmd_subcategoria02.Slug',$args['slug2'])->first();
            $productos_relacionados=DB::table('cmd_carrito_consolidado')
                ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
                
                ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')
                ->join('cmd_subcategoria01',function($join){
                    $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                        ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
                })
                ->join('cmd_subcategoria02',function($join){
                    $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                        ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                        ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
                })
                ->where('cmd_categorias.U_CMD_Activo',1)
                ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',$slug->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',$slug1->U_CMD_IdSuCa01)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',$slug2->U_CMD_IdSuCa02)
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
                foreach($productos_relacionados as $relacionados){
                    if((Int)$relacionados->OnHand>0){
                        
                        $relacionados->STATESTOCK="DISPONIBLE";
                    }
                    else{
                        $relacionados->STATESTOCK="AGOTADO";
                    }
                }
                return ['NroItems'=>$productos_relacionados->total(),'data'=>$productos_relacionados];
        }
        if(isset($args['slug'])==true && isset($args['slug1'])==true && isset($args['slug2'])==false){
            $slug=DB::table('cmd_categorias')->where('cmd_categorias.Slug',$args['slug'])->first();
            $slug1=DB::table('cmd_subcategoria01')->where('cmd_subcategoria01.Slug',$args['slug1'])->first();
            $productos_relacionados=DB::table('cmd_carrito_consolidado')
                ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

                ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')
                ->join('cmd_subcategoria01',function($join){
                    $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                        ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
                })
                ->join('cmd_subcategoria02',function($join){
                    $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                        ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                        ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
                })
                ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
                ->where('cmd_categorias.U_CMD_Activo',1)
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',$slug->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',$slug1->U_CMD_IdSuCa01)
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
                foreach($productos_relacionados as $relacionados){
                    if((Int)$relacionados->OnHand>0){
                        
                        $relacionados->STATESTOCK="DISPONIBLE";
                    }
                    else{
                        $relacionados->STATESTOCK="AGOTADO";
                    }
                }
                return ['NroItems'=>$productos_relacionados->total(),'data'=>$productos_relacionados];
        }
        if(isset($args['slug'])==true && isset($args['slug1'])==false && isset($args['slug2'])==false){
            $slug=DB::table('cmd_categorias')->where('cmd_categorias.Slug',$args['slug'])->first();
            $productos_relacionados=DB::table('cmd_carrito_consolidado')
                ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

                ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')
                ->join('cmd_subcategoria01',function($join){
                    $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                        ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
                })
                ->join('cmd_subcategoria02',function($join){
                    $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                        ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                        ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
                })
                ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
                ->where('cmd_categorias.U_CMD_Activo',1)
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',$slug->U_CMD_IdCate)
                ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
                foreach($productos_relacionados as $relacionados){
                    if((Int)$relacionados->OnHand>0){
                        
                        $relacionados->STATESTOCK="DISPONIBLE";
                    }
                    else{
                        $relacionados->STATESTOCK="AGOTADO";
                    }
                }
                return ['NroItems'=>$productos_relacionados->total(),'data'=>$productos_relacionados];
        }
       
    }
    public function getProductoSlug($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })

            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->where('cmd_categorias.U_CMD_Activo',1)
            ->where('cmd_carrito_consolidado.slug', 'LIKE',"%".$args["slug"]."%")
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach($productos as $products){
                    if((Int)$products->OnHand>0){
                            
                        $products->STATESTOCK="DISPONIBLE";
                    }
                    else{
                        $products->STATESTOCK="AGOTADO";
                    }
                }
        return ['NroItems'=>$productos->total(),'data'=>$productos];
    }
    public function getProductosSlugRelacionados($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $product=DB::table('cmd_carrito_consolidado')
        ->where('cmd_carrito_consolidado.slug',$args["slug"])
        ->first();
       
        $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })

            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->where('cmd_categorias.U_CMD_Activo',1)
            ->where('cmd_carrito_consolidado.ID_PRODUCTO','!=',@$product->ID_PRODUCTO)
            ->where('cmd_carrito_consolidado.ID_CATEGORIA',@$product->ID_CATEGORIA)
            ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',@$product->ID_SUBCATEGORIA_01)
            ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',@$product->ID_SUBCATEGORIA_02)
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
        foreach($productos as $products){
            if((Int)$products->OnHand>0){
                        
                $products->STATESTOCK="DISPONIBLE";
            }
            else{
                $products->STATESTOCK="AGOTADO";
            }
        }
        return ['NroItems'=>$productos->total(),'data'=>$productos];
    }
    public function BusquedaAvansada($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $array_comnsulta=[];
        if(\count($args['precio'])>0){
            $consulta=['cmd_itm1.ItemPrice01', '>=',(float)$args["precio"][0]];
            array_push($array_comnsulta,$consulta);
            $consulta=['cmd_itm1.ItemPrice01', '<=',(float)$args["precio"][1]];
            array_push($array_comnsulta,$consulta);
        }
        if(isset($args["marca"])==true){
            $consulta=['cmd_carrito_consolidado.MARCA', 'LIKE',"%".$args["marca"]."%"];
            array_push($array_comnsulta,$consulta);
        }
        if(\count($args['categoria'])>0){
            foreach($args['categoria'] as $categorias){
                $consulta0=['cmd_carrito_consolidado.ID_CATEGORIA', 'LIKE',"%".(int)$categorias['categoria']."%"];
                array_push($array_comnsulta,$consulta0);
                $consulta1=['cmd_carrito_consolidado.ID_SUBCATEGORIA_01', 'LIKE',"%".(int)$categorias['categoria01']."%"];
                array_push($array_comnsulta,$consulta1);
                foreach($categorias['categoria02'] as $subcate){
                    $consulta2=['cmd_carrito_consolidado.ID_SUBCATEGORIA_02', 'LIKE',"%".(int)$subcate."%"];
                    array_push($array_comnsulta,$consulta2);
                }
            }
        }
        if(isset($args["cicloMensual"])==true){
            $consulta=['cmd_carrito_consolidado.Ciclo_Mensual', 'LIKE',"%".$args["cicloMensual"]."%"];
            array_push($array_comnsulta,$consulta);
        }
        if(isset($args["granaje"])==true){
            $consulta=['cmd_carrito_consolidado.Gramaje', 'LIKE',"%".$args["granaje"]."%"];
            array_push($array_comnsulta,$consulta);
        }
        if(isset($args["formato"])==true){
            if($args["formato"]=="A0"){
                $consulta=['cmd_carrito_consolidado.Formato_A0', 'LIKE',"%".$args["formato"]."%"];
                array_push($array_comnsulta,$consulta);
            }
            if($args["formato"]=="A1"){
                $consulta=['cmd_carrito_consolidado.Formato_A1', 'LIKE',"%".$args["formato"]."%"];
                array_push($array_comnsulta,$consulta);
            }
            if($args["formato"]=="A3"){
                $consulta=['cmd_carrito_consolidado.Formato_A3', 'LIKE',"%".$args["formato"]."%"];
                array_push($array_comnsulta,$consulta);
            }
            if($args["formato"]=="A4"){
                $consulta=['cmd_carrito_consolidado.Formato_A4', 'LIKE',"%".$args["formato"]."%"];
                array_push($array_comnsulta,$consulta);
            }
        }
        if(isset($args["velocidadBN"])==true){
            $consulta=['cmd_carrito_consolidado.Velocidad_BN_Pag_Min', 'LIKE',"%".$args["velocidadBN"]."%"];
            array_push($array_comnsulta,$consulta);
        }
        if(isset($args["velocidadColor"])==true){
            $consulta=['cmd_carrito_consolidado.Velocidad_Color_Pag_Min', 'LIKE',"%".$args["velocidadColor"]."%"];
            array_push($array_comnsulta,$consulta);
        }
        ///////////////////////////////
        if(\count($array_comnsulta)>0){
            
            $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            
            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })

            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->where('cmd_categorias.U_CMD_Activo',1)
            ->where($array_comnsulta)
            ->paginate($perPage = $args['number_paginate'], $columns = ['*'], $pageName = 'page', $page = $args['page']);
            foreach($productos as $products){
                if((Int)$products->OnHand>0){
                            
                    $products->STATESTOCK="DISPONIBLE";
                }
                else{
                    $products->STATESTOCK="AGOTADO";
                }
            }
            return ['NroItems'=>$productos->total(),'data'=>$productos];
        }
        else{
            return ['NroItems'=>null,'data'=>null];
        }
    }
    public function BusquedaProductosSeisCampos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $palabras_aux = explode (" ", strtoupper($args["palabraClave"]));
        
        foreach($palabras_aux as $palabra){
            if($palabra=="EL"){
                $key = array_search('EL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="LA"){
                $key = array_search('LA', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="LOS"){
                $key = array_search('LOS', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="A"){
                $key = array_search('A', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="PARA"){
                $key = array_search('PARA', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="DE"){
                $key = array_search('DE', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="DEL"){
                $key = array_search('DEL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="AL"){
                $key = array_search('AL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
          
            if($palabra=="ES"){
                $key = array_search('ES', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="SON"){
                $key = array_search('SON', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="ME"){
                $key = array_search('ME', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="MI"){
                $key = array_search('MI', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="TU"){
                $key = array_search('TU', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="QUE"){
                $key = array_search('QUE', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="POR"){
                $key = array_search('POR', $palabras_aux);
                unset($palabras_aux[$key]);
            }
        }
        $palabras=[];
        foreach($palabras_aux as $palabra){
            array_push($palabras,$palabra);
        }
       
        $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })
            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.U_CMD_Activo','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->orwhere('cmd_carrito_consolidado.MARCA', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.ULTIMO_NRO_PARTE', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.HISTORICO_NUMERO_PARTE', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION_CATEGORIA', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_01', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_02', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_01', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_02', 'LIKE',"%".$palabras[0]."%")
            ->get();
        if(\count($palabras)>1){
            $array0=$productos->where('U_CMD_Activo',1)->pluck('ID_PRODUCTO')->toArray();
            for ($i=1; $i <\count($palabras) ; $i++) { 
                $productos_aux=DB::table('cmd_carrito_consolidado')
                    ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

                    ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

                    ->join('cmd_subcategoria01',function($join){
                        $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                            ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
                    })
                    ->join('cmd_subcategoria02',function($join){
                        $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                            ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                            ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
                    })
                    ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.U_CMD_Activo','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
                    ->orwhere('cmd_carrito_consolidado.MARCA', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.ULTIMO_NRO_PARTE', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.HISTORICO_NUMERO_PARTE', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION_CATEGORIA', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_01', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_02', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_01', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_02', 'LIKE',"%".$palabras[$i]."%")
                    ->get();
                    if(count($productos_aux)>0){
                        $array0=array_intersect($array0, $productos_aux->where('U_CMD_Activo',1)->pluck('ID_PRODUCTO')->toArray());        
                    }          
            }
            $productos=$productos->whereIn('ID_PRODUCTO',array_unique($array0));
            foreach($productos as $products){
                if((Int)$products->OnHand>0){
                                
                    $products->STATESTOCK="DISPONIBLE";
                }
                else{
                        $products->STATESTOCK="AGOTADO";
                }
            }
            $nroItems=\count($productos);
            $productos = $productos->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['NroItems'=>$nroItems,'data'=>$productos];
            
        }
        else{
            foreach($productos as $products){
                if((Int)$products->OnHand>0){
                                
                    $products->STATESTOCK="DISPONIBLE";
                }
                else{
                        $products->STATESTOCK="AGOTADO";
                }
            }
            $nroItems=\count($productos);
            $productos = $productos->forPage($args['page'], $args['number_paginate']); //Filter the page var

            return ['NroItems'=>$nroItems,'data'=>$productos];
        }
        
    }
    public function FiltrosDePalabraClave($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $palabras_aux = explode (" ", strtoupper($args["palabraClave"]));
        
        foreach($palabras_aux as $palabra){
            if($palabra=="EL"){
                $key = array_search('EL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="LA"){
                $key = array_search('LA', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="LOS"){
                $key = array_search('LOS', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="A"){
                $key = array_search('A', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="PARA"){
                $key = array_search('PARA', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="DE"){
                $key = array_search('DE', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="DEL"){
                $key = array_search('DEL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="AL"){
                $key = array_search('AL', $palabras_aux);
                unset($palabras_aux[$key]);
            }
          
            if($palabra=="ES"){
                $key = array_search('ES', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="SON"){
                $key = array_search('SON', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="ME"){
                $key = array_search('ME', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="MI"){
                $key = array_search('MI', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="TU"){
                $key = array_search('TU', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="QUE"){
                $key = array_search('QUE', $palabras_aux);
                unset($palabras_aux[$key]);
            }
            if($palabra=="POR"){
                $key = array_search('POR', $palabras_aux);
                unset($palabras_aux[$key]);
            }
        }
        $palabras=[];
        foreach($palabras_aux as $palabra){
            array_push($palabras,$palabra);
        }
        $productos=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

            ->join('cmd_subcategoria01',function($join){
                $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
            })
            ->join('cmd_subcategoria02',function($join){
                $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                    ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
            })
            ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.U_CMD_Activo','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
            ->orwhere('cmd_carrito_consolidado.MARCA', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.ULTIMO_NRO_PARTE', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.HISTORICO_NUMERO_PARTE', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION_CATEGORIA', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_01', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_02', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_01', 'LIKE',"%".$palabras[0]."%")
            ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_02', 'LIKE',"%".$palabras[0]."%")
            ->get();
        
        if(\count($palabras)>1){
            $array0=$productos->where('U_CMD_Activo',1)->pluck('ID_PRODUCTO')->toArray();
            for ($i=1; $i <\count($palabras) ; $i++) { 
                $productos_aux=DB::table('cmd_carrito_consolidado')
                    ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')

                    ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_carrito_consolidado.ID_CATEGORIA')

                    ->join('cmd_subcategoria01',function($join){
                        $join->on("cmd_subcategoria01.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                            ->on("cmd_subcategoria01.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01");
                    })
                    ->join('cmd_subcategoria02',function($join){
                        $join->on("cmd_subcategoria02.U_CMD_IdCate","=","cmd_carrito_consolidado.ID_CATEGORIA")
                            ->on("cmd_subcategoria02.U_CMD_IdSuCa01","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_01")
                            ->on("cmd_subcategoria02.U_CMD_IdSuCa02","=","cmd_carrito_consolidado.ID_SUBCATEGORIA_02");
                    })
                    ->select('cmd_carrito_consolidado.*','cmd_itm1.*','cmd_categorias.U_CMD_Activo','cmd_categorias.Slug as SlugCategoria','cmd_subcategoria01.Slug as SlugCategoria01','cmd_subcategoria02.Slug as SlugCategoria02')
                    ->orwhere('cmd_carrito_consolidado.MARCA', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.ULTIMO_NRO_PARTE', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.HISTORICO_NUMERO_PARTE', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION_CATEGORIA', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_01', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.DESCRIPCION_SUBCATEGORIA_02', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_01', 'LIKE',"%".$palabras[$i]."%")
                    ->orwhere('cmd_carrito_consolidado.CMD_Carrito_Sinonimo_02', 'LIKE',"%".$palabras[$i]."%")
                    ->get();
                    if(count($productos_aux)>0){
                        $array0=array_intersect($array0, $productos_aux->where('U_CMD_Activo',1)->pluck('ID_PRODUCTO')->toArray());       
                    }     
            }
            $productos=$productos->whereIn('ID_PRODUCTO',array_unique($array0));
            
            $filtros=(object)[];
            $gramaje=[];
            $velocidadBN=[];
            $velocidadColor=[];
            $cicloMensual=[];
            $marcas=[];
            $precios=[];
            $formatos=[];
            foreach($productos as $filtro){
                if($filtro->Gramaje!=""){
                    array_push($gramaje,trim($filtro->Gramaje));
                }
                if($filtro->Velocidad_BN_Pag_Min!=""){
                    array_push($velocidadBN,str_replace(" ", "",$filtro->Velocidad_BN_Pag_Min));
                }
                if($filtro->Velocidad_Color_Pag_Min!=""){
                    array_push($velocidadColor,str_replace(" ", "",$filtro->Velocidad_Color_Pag_Min));
                }
                if($filtro->Ciclo_Mensual!=""){
                    array_push($cicloMensual,$filtro->Ciclo_Mensual);
                }
                if($filtro->MARCA!=""){
                    array_push($marcas,$filtro->MARCA);
                }
                if($filtro->ItemPrice01!=""){
                    array_push($precios,(String)$filtro->ItemPrice01);
                }
                if($filtro->Formato_A0!=""){
                    array_push($formatos,(String)$filtro->Formato_A0);
                }
                if($filtro->Formato_A1!=""){
                    array_push($formatos,(String)$filtro->Formato_A1);
                }
                if($filtro->Formato_A3!=""){
                    array_push($formatos,(String)$filtro->Formato_A3);
                }
                if($filtro->Formato_A4!=""){
                    array_push($formatos,(String)$filtro->Formato_A4);
                }
            }
            sort($gramaje);
            sort($velocidadBN);
            sort($velocidadColor);
            sort($cicloMensual,SORT_NUMERIC);
            sort($marcas);
            sort($formatos);
            $filtros->gramajes=array_unique($gramaje);
            $filtros->velocidadBN=array_unique($velocidadBN);
            $filtros->velocidadColor=array_unique($velocidadColor);
            $filtros->cicloMensual=array_unique($cicloMensual);
            $filtros->marcas=array_unique($marcas);

            $filtros->precios=[min($precios),max($precios)];
            $filtros->formatos=array_unique($formatos);
            return $filtros;
        }
        else{
            $filtros=(object)[];
            $gramaje=[];
            $velocidadBN=array();
            $velocidadColor=[];
            $cicloMensual=[];
            $marcas=[];
            $precios=[];
            $formatos=[];
            foreach($productos as $filtro){
                if($filtro->Gramaje!=""){
                    array_push($gramaje,$filtro->Gramaje);
                }
                if($filtro->Velocidad_BN_Pag_Min!=""){
                    array_push($velocidadBN,str_replace(" ", "",$filtro->Velocidad_BN_Pag_Min));
                }
                if($filtro->Velocidad_Color_Pag_Min!=""){
                    array_push($velocidadColor,str_replace(" ", "",$filtro->Velocidad_Color_Pag_Min));
                }
                if($filtro->Ciclo_Mensual!=""){
                    array_push($cicloMensual,$filtro->Ciclo_Mensual);
                }
                if($filtro->MARCA!=""){
                    array_push($marcas,$filtro->MARCA);
                }
                if($filtro->ItemPrice01!=""){
                    array_push($precios,(String)$filtro->ItemPrice01);
                }
                if($filtro->Formato_A0!=""){
                    array_push($formatos,(String)$filtro->Formato_A0);
                }
                if($filtro->Formato_A1!=""){
                    array_push($formatos,(String)$filtro->Formato_A1);
                }
                if($filtro->Formato_A3!=""){
                    array_push($formatos,(String)$filtro->Formato_A3);
                }
                if($filtro->Formato_A4!=""){
                    array_push($formatos,(String)$filtro->Formato_A4);
                }
            }
            
            sort($gramaje);
            sort($velocidadBN);
            sort($velocidadColor);
            sort($cicloMensual);
            sort($marcas);
            sort($formatos);

            $filtros->gramajes=array_unique($gramaje);
            $filtros->velocidadBN=array_unique($velocidadBN);
            $filtros->velocidadColor=array_unique($velocidadColor);
            $filtros->cicloMensual=array_unique($cicloMensual);
            $filtros->marcas=array_unique($marcas);

            $filtros->precios=[min($precios),max($precios)];
            $filtros->formatos=array_unique($formatos);
            return $filtros;
        }
        
    }
    
}
