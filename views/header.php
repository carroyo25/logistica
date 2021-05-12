<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ibis</title>

    <link rel="stylesheet" href="<?php echo constant('URL')?>public/css/global.css?<?php echo constant('VERSION')?>">
    <link rel="stylesheet" href="<?php echo constant('URL')?>public/css/login.css?<?php echo constant('VERSION')?>">
    
    <link rel="stylesheet" href="<?php echo constant('URL')?>public/css/all.css">
    <link rel="stylesheet" href="<?php echo constant('URL')?>public/css/loader.css">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">-->
</head>
<body>
    <div class="cabecera">
        <h1>Ibis</h1>
        <div class="avatar">
            <p id="cnameuser" class="oculto"><?php echo $_SESSION['cnameuser'] ?></p>
            <p id="act_user" class="oculto"><?php echo $_SESSION['id_user'] ?></p>
            <p id="id_user" class="oculto"><?php echo $_SESSION['codper'] ?></p>
            <p id="cargo_user" class="oculto"><?php echo  $_SESSION['ncargo'] ?></p>
            <div class="foto">
                <a href="#"><img src="<?php echo constant('URL')?>public/img/avatar/man01.png"></a>
                <ul class="menuCabecera">
                    <li><a href="#">Cambiar Usuario</a></li>
                    <li><a href="/logistica/panel/">Ir al inicio</a></li>
                    <hr>
                    <li><a href="/logistica/main/">Cerrar Sesion</a></li>
                </ul>
            </div>
            <div class="userData">
                <h3><?php 
                    echo ucwords($_SESSION['cnombres']);
                ?></h3>
                <div>
                    <span> <i class="fas fa-circle"></i> En linea</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>