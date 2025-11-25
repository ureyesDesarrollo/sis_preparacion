<?php 
echo date("Y-m-d h:i:s") . "<br>";
echo date("Y-m-d H:i:s") . "<br>";
$fechaActual = new DateTime();
echo $fechaActual->format('Y-m-d h:i:s') . "<br>";
exit;