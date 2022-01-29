<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png" />
    
    <title>Sistema de Control logístico - Ibis</title>
</head>
<body>
    <div class="mensaje msj_error">
        <span></span>
    </div>
    <?php require 'views/headerlog.php'; ?>
    <div class="main">
        <div class="login">
            <form action="#" id="formLogin" autocomplete="no">
                <h1>Bienvenido</h1>
                <div class="icon">
                    <img src="<?php echo constant('URL')?>public/img/users.png" alt="">
                </div>
                <div class="data">
                    <div class="field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user" id="user" required  autocomplete="no">
                    </div>
                    <div class="field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" id="pass" required  autocomplete="no">
                    </div>
                </div>
                <div class="checks">
                    <input type="checkbox" name="seePassword" id="seePassword">
                    <label for="seePassword">Ver Clave</label>
                </div>
                <div class="command">
                    <button id="btnLogin">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/main.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>