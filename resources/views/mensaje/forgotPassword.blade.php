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
<h3> Recuperar Contraseña</h3>
</br>
<h4><?php print_r('Email: '.@$usuario->E_Mail);?> </h4>
</br>
<h4><?php print_r('Password: '.@$usuario->Password);?></h4>
</br>

</div>
</body>
</html>