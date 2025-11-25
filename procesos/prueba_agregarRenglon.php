<?php 
/*Desarrollado por: Ca & Ce Technologies */
/*Contacto: mc.munoz.rz@gmail.com */
/*21 - Agosto - 2018*/
?>
<!DOCTYPE html>
<html>
<head>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
 
<script>
a = 0;
function agregarCampo(){
        a++;
 
        var div = document.createElement('div');
        div.setAttribute('class', 'form-inline');
            div.innerHTML = '<div class="cancion_'+a+' col-md-2""><input class="form-control" name="duracion_'+a+'" type="text"/></div>';
            document.getElementById('campos').appendChild(div);document.getElementById('campos').appendChild(div);
}
</script>
</head>
 
<body>
    
       <div class="col-md-1"><input type="button" class="btn btn-success" id="add_cancion()" onClick="agregarCampo()" value="+" /></div>

    <!-- El id="campos" indica que la función de JavaScript dejará aquí el resultado -->
    <div class="row" id="campos">
    </div> 
</body>
</html>