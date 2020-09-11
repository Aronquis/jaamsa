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
            @if($pedido->CMD_MetPag==1)
            <table width="100%" class="tabla2">
            
                <tr>
                    <td width="30%" ><strong>MAQUINARIAS JAAMSA.SA</strong></td>
                    <td width="30%" ></td>
                    <td width="40%"></td>
                </tr>
                <tr>
                    <td width="30%" ><strong>Numero de cuenta :{{@$pedido->RefNum}}</strong></td>
                    <td width="30%" ></td>
                    <td width="40%"></td>
                </tr>
                <tr>
                    <td width="30%" ><strong>DNI/RUC :{{@$usuario->LicTradNum}}</strong></td>
                    <td width="30%" ></td>
                    <td width="40%"></td>
                </tr>
            </table>
            
            <table width="100%" class="tabla2">
                <tr>
                    <td width="100%" ><strong style="color: red;">Tienes 24 horas para realizar esta transferencia </strong></td>
                </tr>
            </table>
            <table width="100%" class="tabla2">
                <tr>
                    <td width="100%" >Una ves realizado el pago por favor envianos la constancia al siguiente correo <a href="#">transferencia@jaamsaonline.com.pe</a></td>
                </tr>
            </table>
            @endif
            @if(@$direccion!="" && @$agencia=="")
            <table width="100%" class="tabla2">
            <tr>
                <td width="20%"  class="fondo"><strong>A DOMICILIO</strong>
                    <br>
                    <strong>¡Gracias por tu compra!</strong>
                </td>
                <td width="10%" ><span class="text"></span></td>
                <td width="70%"></td>
            </tr>
            <tr>
                <td width="7%">,estamos preparando tu pedido para enviarlo. Te notificaremos cuando haya sido enviado. 
                </td>
                <td width="10%" ><span class="text"></span></td>
                <td width="83%"></td>
            </tr>
            </table>
            @endif
            @if(@$direccion=="" && @$agencia=="")
            <table width="100%" class="tabla2">
            <tr>
                <td width="20%"  class="fondo"><strong>RECOGO EN TIENDA</strong>
                    <br>
                    <strong>¡Gracias por tu compra!</strong>
                </td>
                <td width="10%" ><span class="text"></span></td>
                <td width="70%"></td>
            </tr>
            <tr>
                <td width="7%">,estamos preparando tu pedido para que lo recojas. Te notificaremos cuando esté listo. 
                </td>
                <td width="10%" ><span class="text"></span></td>
                <td width="83%"></td>
            </tr>
            </table>
            @endif
            @if(@$agencia!="")
            <table width="100%" class="tabla2">
            <tr>
                <td width="20%"  class="fondo"><strong>ENVIO A AGENCIA</strong>
                    <br>
                    <strong>¡Gracias por tu compra!</strong>
                </td>
                <td width="10%" ><span class="text"></span></td>
                <td width="70%"></td>
            </tr>
            <tr>
                <td width="7%">,estamos preparando tu pedido para que lo recojas. Te notificaremos cuando esté listo. 
                </td>
                <td width="10%" ><span class="text"></span></td>
                <td width="83%"></td>
            </tr>
            </table>
            @endif
            
        <table width="100%" class="tabla2">
            <tr>
                <td width="7%">Nombres:</td>
                <td width="10%" class="linea"><span class="text">{{@$usuario->CardName}}</span></td>
                <td width="83%"></td>
            </tr>
        </table>
        
    </div>
</body>
</html>