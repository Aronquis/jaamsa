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
    .myDiv {

  background-color: #EAEAFB;
  text-align: center;
}
.myDiv2 {

background-color: #E6E6EA;
}
</style>
</head>
<body>
    <div class="myDiv">
        <table width="100%" class="tabla2">
        <tr>
            <td width="100%" align="center"><img id="logo" src="{{ asset('https://jaamsaonline.com.pe/logo-jaamsa.png') }}" alt="" width="255" height="57"></td>
            
        </tr>
        </table>
        <table width="100%" >
        <tr>
            <td width="35%" ></td>
            <td width="36%" align="left"><h2>Registro de correo electrónico</h2></td>
            <td width="4%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><h3>Hola&nbsp;&nbsp;{{@$usuario->CardName}}</h3></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">Te damos la bienvenida a la familia Jaamsa.</span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">Gracias por registrarte en Jaamsaonline.com.pe, para acceder inicia sesión con tu login y contraseña.Tu usuario es: </span><span style="font-size: 1.1em;"><strong>({{@$usuario->E_Mail}})</strong></span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">Disfruta de los siguientes beneficios al iniciar a tu cuenta:</span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•	Podras proceder al checkout más rápido</span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•	Almacenar direcciones alternativas</span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•	Comprobar el estado de los pedidos</span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;•	Ver tu historial de pedidos </span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="25%" align="left"><span style="font-size: 1.1em;">Agregue&nbsp;&nbsp;</span><a href="https://jaamsaonline.com.pe/">jaamsaonline.com.pe</a>&nbsp;&nbsp; <span style="font-size: 1.1em;">a su libreta de direcciones para garantizar la recepción de futuros mensajes con notificaciones y promociones.</span></td>
            <td width="15%" ></td>
            <td width="25%" ></td>
        </tr>
        </table>
        <table width="100%" class="tabla2">
        <tr>
            <td width="35%" ></td>
            <td width="12%" align="center" colspan="2"><h3>Redes Sociales</h3></td>
            <td width="10%" align="center"><h4>SERVICIO AL CLIENTE</h4></td>
            <td width="14%" align="left"></td>
            <td width="0%" ></td>
            <td width="25%" ></td>
            
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="7%" align="right"><a href="https://www.facebook.com/jaamsa/"><img id="logo"  src="{{ asset('https://i0.pngocean.com/files/780/252/838/oculus-rift-facebook-computer-icons-beauty-chef.jpg') }}" alt="" width="50" height="50"></a></td>
            <td width="5%" align="left"><a href="https://instagram.com/jaamsa.pe"><img id="logo" src="{{ asset('https://i0.pngocean.com/files/750/461/292/logo-computer-icons-instagram.jpg') }}" alt="" width="50" height="50"></a></td>
            <td width="5%" align="center"><img id="logo" src="{{ asset('https://w7.pngwing.com/pngs/624/506/png-transparent-computer-icons-mobile-phones-telephone-handset-phone-icon-miscellaneous-angle-hand.png') }}" alt="" width="50" height="50"></td>
            <td width="14%" align="left"><a href="#">Privacidad Terminos y condiciones</a></td>
            <td width="5%" ></td>
            <td width="25%" ></td>
            
        </tr>
        <tr>
            <td width="35%" ></td>
            <td width="7%" align="right"></td>
            <td width="5%" align="left"></td>
            <td width="5%" align="center"><h4>01 512 0500</h4></td>
            <td width="14%" align="left"></td>
            <td width="5%" ></td>
            <td width="25%" ></td> 
        </tr>
        
        </table>
    </div>
    <div >
    <table>
        <tr>
            <td width="35%" ></td>
            <td width="36%" align="left" colspan="4"><h6>Descarga de Responsabilidad: 
Este mensaje contiene información confidencial y esta dirigido solamente al remitente especificado. Si usted no es el destinatario no debe tener acceso, distribuir ni copiar este e-mail. Notifique por favor al remitente inmediatamente si usted ha recibido este mensaje por error y eliminelo de su sistema. La transmisión del e-mail no se puede garantizar que sea segura, sin errores o como que la información podría ser interceptada, alterada, perdida, destruida, llegar atrasado, incompleto o contener virus, por lo tanto el remitente no acepta la responsabilidad por ningunos de los errores u omisiones en el contenido de este mensaje, que se presentan como resultado de la transmisión del e-mail. Si la verificación se requiere, por favor solicite una versión impresa.
</h6></td>
            <td width="0%" ></td>
            <td width="25%" ></td>
            
        </tr>
        </table>
    </div>
</body>
</html>