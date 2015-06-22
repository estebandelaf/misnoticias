<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title>MiSnOtIcIaS</title>
    </head>
    <body>
<?php
require('inc/inc.php');
MiSnOtIcIaS::mostrar(!empty($_GET['noticia'])?$_GET['noticia']:null);
?>
    </body>
</html>
