<!DOCTYPE html>
<html>
<head>
    <title>BOLETA DE VENTA</title>
    <style type="text/css">
    body{
        font-size: 16px;
        font-family: "Arial";
    }
    table{
        border-collapse: collapse;
    }
    td{
        padding: 3px 3px;
        font-size: 12px;
    }
    .h1{
        font-size: 21px;
        font-weight: bold;
    }
    .h2{
        font-size: 18px;
        font-weight: bold;
    }
    .tabla1{
        margin-bottom: 20px;
    }
    .tabla2 {
        margin-bottom: 20px;
    }
    .tabla3{
        margin-top: 15px;
    }
    .tabla3 td{
        border: 1px solid #000;
    }
    .tabla3 .cancelado{
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        border-top: 1px dotted #000;
        width: 200px;
    }
    .emisor{
        color: red;
    }
    .linea{
        border-bottom: 1px dotted #000;
    }
    .border{
        border: 1px solid #000;
    }
    .fondo{
        background-color: #dfdfdf;
    }
    .fisico{
        color: #fff;
    }
    .fisico td{
        color: #fff;
    }
    .fisico .border{
        border: 1px solid #fff;
    }
    .fisico .tabla3 td{
        border: 1px solid #fff;
    }
    .fisico .linea{
        border-bottom: 1px dotted #fff;
    }
    .fisico .emisor{
        color: #fff;
    }
    .fisico .tabla3 .cancelado{
        border-top: 1px dotted #fff;
    }
    .fisico .text{
        color: #000;
    }
    .fisico .fondo{
        background-color: #fff;
    }

</style>
</head>
<body>
    <div>
        <table width="100%" class="tabla2">
        <tr>
            <td width="30%" align="center"><img id="logo" src="{{ asset('https://jaamsaonline.com.pe/logo-jaamsa.png') }}" alt="" width="255" height="57"></td>
            <td width="70%"></td>
        </tr>
        </table>
        <table width="100%" class="tabla2">
            <tr>
                <td width="70%"><strong>Numero de pedido :{{$pedido->DocEntry}}</strong></td>
            </tr>
            <tr>
                <td width="70%"><strong>Hola :{{@$usuario->CardName}} !Gracias por preferirnos!</strong></td>
            </tr>
            <tr>
                <td width="70%">Te estaremos informando via correo cuando se confirme tu pago</td>
            </tr>
            <tr>
                <td width="70%">Esto puede tardar unos minutos</td>
            </tr>
        </table>
        <table width="100%" class="tabla2">
            
            <tr>
                <td width="7%"  class="fondo"><strong>DATOS PERSONALES</strong></td>
                <td width="10%" ><span class="text"></span></td>
                <td width="83%"></td>
            </tr>
          
            <tr>
                <td width="7%">CardCode:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->CardCode}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%">Nombres:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->CardName}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%">DNI/RUC:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->LicTradNum}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%">Email:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->E_Mail}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%">Telefono 1:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->Phone1}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%">Telefono 2:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->Phone2}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%">Celular:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->Cellular}}</span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="7%"  class="fondo"><strong>DIRECCIONES</strong></td>
                <td width="10%" ><span class="text"></span></td>
                <td width="83%"></td>
            </tr>
            <tr>
                <td width="15%">Metodo pago:</td>
                @if(@$pedido->CMD_MetPag==1)
                <td width="10%" class="linea"><span class="text">TRANSFERENCIA</span></td>
                @endif
                @if(@$pedido->CMD_MetPag==2)
                <td width="10%" class="linea"><span class="text">TARJETA DEBITO</span></td>
                @endif
                @if(@$pedido->CMD_MetPag==3)
                <td width="10%" class="linea"><span class="text">PAGOEFECTIVO</span></td>
                @endif
                <td width="75%"></td>
            </tr>
            <tr>
                <td width="15%">Tipo Envio:</td>
                @php
                switch(@$pedido->CMD_TipEnt){
                    case 1:
                        print_r('<td width="10%" class="linea"><span class="text">Envio a Domicilio</span></td>');
                    break;
                    case 2:
                        print_r('<td width="10%" class="linea"><span class="text">Envio a Agencia</span></td>');
                    break;
                    case 3:
                        print_r('<td width="10%" class="linea"><span class="text">Recojo en Tienda</span></td>');
                    break;
                }   
                @endphp
                <td width="75%"></td>
            </tr>
            <tr>
                <td width="15%">Fecha Pedido:</td>
                <td width="10%" class="linea"><span class="text">{{@$pedido->TransDate}}</span></td>
                <td width="75%"></td>
            </tr>
            <tr>
                <td width="15%">Direccion de Facturacion/Boleta:</td>
                <td width="10%" class="linea"><span class="text">{{@$pedido->OINV_Address}}</span></td>
                <td width="75%"></td>
            </tr>
            <tr>
                <td width="15%">Direccion de Envio:</td>
                <td width="30%" class="linea"><span class="text">{{@$pedido->ODLN_Address}}</span></td>
                <td width="55%"></td>
            </tr>
        </table>
        <table width="800px" class="tabla3">
            <tr>
                <td align="center" class="fondo" colspan="2"><strong>DETALLE DEL PEDIDO</strong></td>
            </tr>

            <tr>
                <td align="center" style="border:0;" class="emisor"><strong></strong></td>
            </tr>

            <tr>
                <td align="center" class="fondo"><strong>CANT.</strong></td>
                <td align="center" class="fondo"><strong>DESCRIPCIÓN</strong></td>
                <td align="center" class="fondo"><strong>P. UNITARIO</strong></td>
                <td align="center" class="fondo"><strong>IMPORTE</strong></td>
            </tr>
            @foreach(@$detalle_pedido as $deta)
                <tr>
                    <td width="7%">{{(Int)@$deta->Quantity}}</td>
                    <td width="59%">{{@$deta->DESCRIPCION}}</td>
                    <td width="16%" align="right">{{(float)@$deta->Price}}</td>
                    <td width="18%"  align="right"><?php  $subtotal=(float)@$deta->Quantity*(float)@$deta->Price; echo $subtotal;?></td>
                </tr>
                
            @endforeach
            <tr>
                <td width="7%">&nbsp;</td>
                <td width="59%">Concepto de Flete</td>
                <td width="16%" align="right">{{(Float)@$pedido->Total_Flete}}</td>
                <td width="18%"  align="right">{{(Float)@$pedido->Total_Flete}}</td>
            </tr>
            <tr>
                <td style="border:0;">&nbsp;</td>
                <td style="border:0;">&nbsp;</td>
                <td align="right"><strong>TOTAL S/.</strong></td>
                <td align="right"><span class="text">{{ (float)@$pedido->DocTotal+(Float)@$pedido->Total_Flete }}</span></td>
            </tr>
        </table>
    </div>
</body>
</html>