<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png" />
    <title>Sistema de Control logístico - Alfa</title>
</head>
<body>
    <?php require 'views/header.php'; ?>
    <div class="modal" id="dialogConfirm">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Pregunta</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h1>Desabilitar el usuario?</h1>   
                <div class="options">
                    <button id="btnConfirm" class="botones">Aceptar</button>
                    <button id="btnCancel" class="botones">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="dialogShow">
        <div class="dialogContainer w20p">
            <div class="dialogTitle">
                <h4>Mensaje</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h2 id="seepass"></h2>   
                <div class="options">
                    <button id="btnCloseShow" class="botones">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modalWait">
    </div>
    <div class="mensaje msj_error">
        <span></span>
    </div> 
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Cargo Plan</p>
            </div>
            <div class="formulario">
                <div class="banner">
                    <div>
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo">
                            <option value="-1" class="oculto">Seleccione opción</option>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>
                    <div>
                        <label for="tipo">Proyecto/Sede</label>
                        <select name="proyecto" id="proyecto">
                            <option value="-1" class="oculto">Seleccione opción</option>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>
                    <div>
                        <label for="orden">Orden</label>
                        <input type="text" name="orden" id="orden">
                    </div>
                    <div>
                        <label for="pedido">Pedido</label>
                        <input type="text" name="pedido" id="pedido">
                    </div>
                </div>
                <div class="banner">
                    <div>
                        <label for="fechadel">Fecha del:</label>
                        <input type="date" name="fechadel" id="fechadel">
                        <label for="fechadal">Al:</label>
                        <input type="date" name="fechadal" id="fechadal">
                    </div>
                    <div>
                        <label for="almacen">Almacen :</label>
                        <select name="almacen" id="almacen">
                            <option value="-1" class="oculto">Seleccione opción</option>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>
                    <div>
                        <label for="descripcion">Descripcion :</label>
                        <input type="text" name="descripcion" id="descripcion">
                    </div>
                </div>
                
                <div class="pedidos">
                    <table class="w100p con_borde" id="tabla_items">
                        <thead>
                            <tr>
                                <th class="con_borde w55px">Codigo.</th>
                                <th class="con_borde w100px">Login</th>
                                <th class="con_borde">Nombres</th>
                                <th class="con_borde">Nivel</th>
                                <th class="con_borde w100px">Estado</th>
                                <th class="con_borde w100px">Desde</th>
                                <th class="con_borde">Hasta</th>
                                <th class="con_borde">...</th>
                                <th class="con_borde">...</th>
                                <th class="con_borde">...</th>
                            </tr>
                        </thead>
                        <tbody>
                           <!-- <?php echo $this->usuarios?> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/cargo.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>