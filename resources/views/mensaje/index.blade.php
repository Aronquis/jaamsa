<!DOCTYPE html>
<html>
<head>
<style>
body {
  background-color: lightblue;
}

h1 {
  color: white;
  text-align: center;
}

p {
  font-family: verdana;
  font-size: 20px;
}
</style>
</head>
<body>
<p></p>
<div>
<h3>Datos del formulario Web</h3>
<h4><?php print_r('Nombres: '.@$args['nombre']);?> </h4>
</br>
<h4><?php print_r('Celular: '.@$args['celular']);?></h4>
</br>
<h4><?php print_r('email: '.@$args['email']);?> </h4>
</br>
<h4><?php print_r('mensaje: '.@$args['mensaje']);?></h4>



</div>
</body>
</html>