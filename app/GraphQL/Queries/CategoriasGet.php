<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
class CategoriasGet
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
    
    public function categorias_hijo_nieto($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // TODO implement the resolver
        $categoriacmd=DB::table('cmd_categorias')
            ->select('cmd_categorias.U_CMD_IdCate','cmd_categorias.U_CMD_DesCat','cmd_categorias.Slug as SlugCat')
            ->where('cmd_categorias.U_CMD_Activo',1)
            ->get();
        foreach($categoriacmd as $CateCmd){
            $cate01=DB::table('cmd_subcategoria01')
                ->select('cmd_subcategoria01.U_CMD_IdSuCa01','cmd_subcategoria01.U_CMD_DeSuCa01','cmd_subcategoria01.Slug as SlugCa01')
                ->where('cmd_subcategoria01.U_CMD_IdCate',$CateCmd->U_CMD_IdCate)
                ->get();
            $CateCmd->data=$cate01;
            foreach($cate01 as $cata01){
                $cata01->data=DB::table('cmd_subcategoria02')
                    ->select('cmd_subcategoria02.U_CMD_IdSuCa02','cmd_subcategoria02.U_CMD_DeSuCa02','cmd_subcategoria02.Slug as SlugCa02')
                    ->where('cmd_subcategoria02.U_CMD_IdCate',$CateCmd->U_CMD_IdCate)
                    ->where('cmd_subcategoria02.U_CMD_IdSuCa01',$cata01->U_CMD_IdSuCa01)
                    ->get();
            }
        }
        return $categoriacmd;
    }
    /////////////////////////////////////
    public function Categoria_Busqueda($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $categorias=DB::table('cmd_subcategoria01')
            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_subcategoria01.U_CMD_IdCate')
            ->select('cmd_subcategoria01.*')
            ->where('cmd_categorias.Slug',$args['slug'])
            ->where('cmd_subcategoria01.Slug',$args['slug1'])
            ->get();
            foreach($categorias as $categoria){
                $categoria->data=DB::table('cmd_subcategoria02')
                        ->select('cmd_subcategoria02.*')
                        ->where('cmd_subcategoria02.U_CMD_IdCate',$categoria->U_CMD_IdCate)
                        ->where('cmd_subcategoria02.U_CMD_IdSuCa01',$categoria->U_CMD_IdSuCa01)
                        ->where('cmd_subcategoria02.Slug',$args['slug2'])
                        ->get();
            }

            return $categorias;
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $categorias=DB::table('cmd_subcategoria01')
            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_subcategoria01.U_CMD_IdCate')
            ->select('cmd_subcategoria01.*')
            ->where('cmd_categorias.Slug',$args['slug'])
            ->where('cmd_subcategoria01.Slug',$args['slug1'])
            ->get();
            foreach($categorias as $categoria){
                $categoria->data=DB::table('cmd_subcategoria02')
                        ->select('cmd_subcategoria02.*')
                        ->where('cmd_subcategoria02.U_CMD_IdCate',$categoria->U_CMD_IdCate)
                        ->where('cmd_subcategoria02.U_CMD_IdSuCa01',$categoria->U_CMD_IdSuCa01)
                        ->get();
            }

            return $categorias;
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $categorias=DB::table('cmd_subcategoria01')
            ->join('cmd_categorias','cmd_categorias.U_CMD_IdCate','=','cmd_subcategoria01.U_CMD_IdCate')
            ->select('cmd_subcategoria01.*')
            ->where('cmd_categorias.Slug',$args['slug'])
            ->get();
            foreach($categorias as $categoria){
                $categoria->data=DB::table('cmd_subcategoria02')
                        ->select('cmd_subcategoria02.*')
                        ->where('cmd_subcategoria02.U_CMD_IdCate',$categoria->U_CMD_IdCate)
                        ->where('cmd_subcategoria02.U_CMD_IdSuCa01',$categoria->U_CMD_IdSuCa01)
                        ->get();
            }

            return $categorias;
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $categorias=DB::table('cmd_subcategoria01')
            ->select('cmd_subcategoria01.*')
            ->get();
            foreach($categorias as $categoria){
                $categoria->data=DB::table('cmd_subcategoria02')
                        ->select('cmd_subcategoria02.*')
                        ->where('cmd_subcategoria02.U_CMD_IdCate',$categoria->U_CMD_IdCate)
                        ->where('cmd_subcategoria02.U_CMD_IdSuCa01',$categoria->U_CMD_IdSuCa01)
                        ->get();
            }

            return $categorias;
        }
        
    }
    
    public function gramajes($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $gramajes=[];
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $gramaje=DB::table('cmd_carrito_consolidado')
                    ->select('cmd_carrito_consolidado.Gramaje')
                    ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                    ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                    ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
                    ->distinct()
                    ->get();
            foreach($gramaje as $grama){
                if((String)$grama->Gramaje!=""){
                    array_push($gramajes,(String)$grama->Gramaje);
                }
            }
            sort($gramajes);
            return array_unique($gramajes);
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $gramaje=DB::table('cmd_carrito_consolidado')
                    ->select('cmd_carrito_consolidado.Gramaje')
                    ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                    ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                    ->distinct()
                    ->get();
            foreach($gramaje as $grama){
                if((String)$grama->Gramaje!=""){
                    array_push($gramajes,(String)$grama->Gramaje);
                }
            }
            sort($gramajes);
            return array_unique($gramajes);
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $gramaje=DB::table('cmd_carrito_consolidado')
                    ->select('cmd_carrito_consolidado.Gramaje')
                    ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                    ->distinct()
                    ->get();
            foreach($gramaje as $grama){
                if((String)$grama->Gramaje!=""){
                    array_push($gramajes,(String)$grama->Gramaje);
                }
            }
            sort($gramajes);
            return array_unique($gramajes);
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $gramaje=DB::table('cmd_carrito_consolidado')
                    ->select('cmd_carrito_consolidado.Gramaje')
                    ->distinct()
                    ->get();
            foreach($gramaje as $grama){
                if((String)$grama->Gramaje!=""){
                    array_push($gramajes,(String)$grama->Gramaje);
                }
            }
            sort($gramajes);
            return array_unique($gramajes);
        }
        
        
    }
    public function VelocidadBN($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $velocidades=[];
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_BN_Pag_Min')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
                ->distinct()
                ->get();
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_BN_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_BN_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_BN_Pag_Min')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->distinct()
                ->get();
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_BN_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_BN_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_BN_Pag_Min')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->distinct()
                ->get();
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_BN_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_BN_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_BN_Pag_Min')
                ->distinct()
                ->get();
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_BN_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_BN_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        
    }
    public function VelocidadColor($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_Color_Pag_Min')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
                ->distinct()
                ->get();
            $velocidades=[];
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_Color_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_Color_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_Color_Pag_Min')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->distinct()
                ->get();
            $velocidades=[];
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_Color_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_Color_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_Color_Pag_Min')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->distinct()
                ->get();
            $velocidades=[];
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_Color_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_Color_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $velocidad=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.Velocidad_Color_Pag_Min')
                ->distinct()
                ->get();
            $velocidades=[];
            foreach($velocidad as $velo){
                if((String)$velo->Velocidad_Color_Pag_Min!=""){
                    array_push($velocidades,(String)$velo->Velocidad_Color_Pag_Min);
                }
            }
            sort($velocidades);
            return array_unique($velocidades);
        }
        
    }
    public function CicloMensual($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $ciclo=DB::table('cmd_carrito_consolidado')
            ->select('cmd_carrito_consolidado.Ciclo_Mensual')
            ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
            ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
            ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
            ->distinct()
            ->get();
            $ciclos=[];
            foreach($ciclo as $cicl){
                if((String)$cicl->Ciclo_Mensual!=""){
                    array_push($ciclos,(String)$cicl->Ciclo_Mensual);
                }
            }
            sort($ciclos);
            return array_unique($ciclos);
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $ciclo=DB::table('cmd_carrito_consolidado')
            ->select('cmd_carrito_consolidado.Ciclo_Mensual')
            ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
            ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
            ->distinct()
            ->get();
            $ciclos=[];
            foreach($ciclo as $cicl){
                if((String)$cicl->Ciclo_Mensual!=""){
                    array_push($ciclos,(String)$cicl->Ciclo_Mensual);
                }
            }
            sort($ciclos);
            return array_unique($ciclos);
        }

        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $ciclo=DB::table('cmd_carrito_consolidado')
            ->select('cmd_carrito_consolidado.Ciclo_Mensual')
            ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
            ->distinct()
            ->get();
            $ciclos=[];
           
            foreach($ciclo as $cicl){
                if($cicl->Ciclo_Mensual!=""){
                    array_push($ciclos,$cicl->Ciclo_Mensual);
                   
                }
            }
            sort($ciclos,SORT_NUMERIC);
            return array_unique($ciclos);
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $ciclo=DB::table('cmd_carrito_consolidado')
            ->select('cmd_carrito_consolidado.Ciclo_Mensual')
            ->distinct()
            ->get();
            $ciclos=[];
            foreach($ciclo as $cicl){
                if((String)$cicl->Ciclo_Mensual!=""){
                    array_push($ciclos,(String)$cicl->Ciclo_Mensual);
                }
            }
            sort($ciclos);
            return array_unique($ciclos);
        }
       
    }
    public function Marcas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $marca=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.MARCA')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
                ->distinct()
                ->get();
            $marcas=[];
            foreach($marca as $marc){
                if((String)$marc->MARCA!=""){
                    array_push($marcas,(String)$marc->MARCA);
                }
            }
            sort($marcas);
            return array_unique($marcas);
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $marca=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.MARCA')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->distinct()
                ->get();
            $marcas=[];
            foreach($marca as $marc){
                if((String)$marc->MARCA!=""){
                    array_push($marcas,(String)$marc->MARCA);
                }
            }
            sort($marcas);
            return array_unique($marcas);
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $marca=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.MARCA')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->distinct()
                ->get();
            $marcas=[];
            foreach($marca as $marc){
                if((String)$marc->MARCA!=""){
                    array_push($marcas,(String)$marc->MARCA);
                }
            }
            sort($marcas);
            return array_unique($marcas);
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $marca=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.MARCA')
                ->distinct()
                ->get();
            $marcas=[];
            foreach($marca as $marc){
                if((String)$marc->MARCA!=""){
                    array_push($marcas,(String)$marc->MARCA);
                }
            }
            sort($marcas);
            return array_unique($marcas);
        }
        
    }
    public function Precios($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        $precios=null;
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $precios=DB::table('cmd_carrito_consolidado')
                ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
                ->select(DB::raw("(select max(`ItemPrice01`)) as PreciMax"),DB::raw("(select min(`ItemPrice01`)) as PreciMin"))
                ->first();
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $precios=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
            ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
            ->select(DB::raw("(select max(`ItemPrice01`)) as PreciMax"),DB::raw("(select min(`ItemPrice01`)) as PreciMin"))
            ->first();
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $precios=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
            ->select(DB::raw("(select max(`ItemPrice01`)) as PreciMax"),DB::raw("(select min(`ItemPrice01`)) as PreciMin"))
            ->first();
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $precios=DB::table('cmd_carrito_consolidado')
            ->join('cmd_itm1','cmd_itm1.ItemCode','=','cmd_carrito_consolidado.ID_PRODUCTO')
            ->select(DB::raw("(select max(`ItemPrice01`)) as PreciMax"),DB::raw("(select min(`ItemPrice01`)) as PreciMin"))
            ->first();
        }
        if(isset($precios->PreciMax)==true){
            return [$precios->PreciMin,$precios->PreciMax];
        }
    }
    public function Formatos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']!=""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $sub3=DB::table('cmd_subcategoria02')->where('Slug',$args['slug2'])->first();
            $formato=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.*')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_02',(int)$sub3->U_CMD_IdSuCa02)
                ->distinct()
                ->get();
            $formatos=[];
            foreach($formato as $forma){
                if((String)$forma->Formato_A0!=""){
                    array_push($formatos,(String)$forma->Formato_A0);
                }
                if((String)$forma->Formato_A1!=""){
                    array_push($formatos,(String)$forma->Formato_A1);
                }
                if((String)$forma->Formato_A4!=""){
                    array_push($formatos,(String)$forma->Formato_A4);
                }
                if((String)$forma->Formato_A3!=""){
                    array_push($formatos,(String)$forma->Formato_A3);
                }
            }
            sort($formatos);
            return array_unique($formatos);
        }
        if($args['slug']!="" && $args['slug1']!="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $sub2=DB::table('cmd_subcategoria01')->where('Slug',$args['slug1'])->first();
            $formato=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.*')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->where('cmd_carrito_consolidado.ID_SUBCATEGORIA_01',(int)$sub2->U_CMD_IdSuCa01)
                ->distinct()
                ->get();
            $formatos=[];
            foreach($formato as $forma){
                if((String)$forma->Formato_A0!=""){
                    array_push($formatos,(String)$forma->Formato_A0);
                }
                if((String)$forma->Formato_A1!=""){
                    array_push($formatos,(String)$forma->Formato_A1);
                }
                if((String)$forma->Formato_A4!=""){
                    array_push($formatos,(String)$forma->Formato_A4);
                }
                if((String)$forma->Formato_A3!=""){
                    array_push($formatos,(String)$forma->Formato_A3);
                }
            }
            sort($formatos);
            return array_unique($formatos);
        }
        if($args['slug']!="" && $args['slug1']=="" && $args['slug2']==""){
            $sub1=DB::table('cmd_categorias')->where('Slug',$args['slug'])->first();
            $formato=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.*')
                ->where('cmd_carrito_consolidado.ID_CATEGORIA',(int)$sub1->U_CMD_IdCate)
                ->distinct()
                ->get();
            $formatos=[];
            foreach($formato as $forma){
                if((String)$forma->Formato_A0!=""){
                    array_push($formatos,(String)$forma->Formato_A0);
                }
                if((String)$forma->Formato_A1!=""){
                    array_push($formatos,(String)$forma->Formato_A1);
                }
                if((String)$forma->Formato_A4!=""){
                    array_push($formatos,(String)$forma->Formato_A4);
                }
                if((String)$forma->Formato_A3!=""){
                    array_push($formatos,(String)$forma->Formato_A3);
                }
            }
            sort($formatos);
            return array_unique($formatos);
        }
        if($args['slug']=="" && $args['slug1']=="" && $args['slug2']==""){
            $formato=DB::table('cmd_carrito_consolidado')
                ->select('cmd_carrito_consolidado.*')
                ->distinct()
                ->get();
            $formatos=[];
            foreach($formato as $forma){
                if((String)$forma->Formato_A0!=""){
                    array_push($formatos,(String)$forma->Formato_A0);
                }
                if((String)$forma->Formato_A1!=""){
                    array_push($formatos,(String)$forma->Formato_A1);
                }
                if((String)$forma->Formato_A4!=""){
                    array_push($formatos,(String)$forma->Formato_A4);
                }
                if((String)$forma->Formato_A3!=""){
                    array_push($formatos,(String)$forma->Formato_A3);
                }
            }
            sort($formatos);
            return array_unique($formatos);
        }
    }
}
