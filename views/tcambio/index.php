<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png"/>
    <title>Sistema de Control Logístico - Ibis</title>
</head>
<body>
    <?php require 'views/header.php'; ?>
    <div class="mensaje msj_error">
        <span></span>
    </div> 
    <div class="modal" id="dialogConfirm">
        <div class="dialogContainer">
            <div class="dialogTitle">
                <h4>Pregunta</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h1>Esta seguro de Eliminar?</h1>   
                <div class="options">
                    <button id="btnConfirm" class="botones">Aceptar</button>
                    <button id="btnCancel" class="botones">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Tipo de Cambio</p>
                <?php require 'views/menus.php'; ?>
            </div>       
            <div class="formulario desactivado">
                <form action="#" method="POST" id="formItem">
                    <input type="hidden" name="index_area" id="index_area">
                    <input type="hidden" name="estado" id="estado" value="1">
                    <div class="banner">
                        <p id="descrip"></p>
                    </div>
                    <div class="descripcion">
                        <div class="menu_horizontal">
                            <ul>
                                <li class="selected"><a href="#" data-index="1">Datos Básicos</a></li>
                            </ul>
                        </div>
                        <div class="datos" id="form1">
                            <div class="datainputs">
                                <table id="tcambio">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Moneda</th>
                                            <th>Comercial</th>
                                            <th>Compra</th>
                                            <th>Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $this->registros ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/tcambio.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>