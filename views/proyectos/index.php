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
    <div class="modal" id="modalSeek">
        <div class="search">
            <div>
                <div class="divSearch">
                    <i class="fas fa-search"></i>
                    <input type="search" name="inputSearch" id="inputSearch">
                </div>
                <div class="listData">
                    <table id="tableSeek">
                        <thead>
                            <tr>
                                <th class="w100px">Codigo</th>
                                <th>Descripcion</th>
                                <th class="w100px">...</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="#" id="closeModal" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
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
                <p class="workTitle">Proyectos</p>
                <?php require 'views/menus.php'; ?>
            </div>       
            <div class="formulario desactivado">
                <form action="#" method="POST" id="formItem">
                    <input type="hidden" name="index_proyecto" id="index_proyecto">
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
                                <div class="inputfield">
                                    <label for="codigo_proyecto" class="w250px">* Código : </label>
                                    <input type="text" name="codigo_proyecto" id="codigo_proyecto">
                                </div>
                                <div class="inputfield">
                                    <label for="descripcion_centro" class="w250px">* Descripción : </label>
                                    <input type="text" name="descripcion_proyecto" id="descripcion_proyecto" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="ubicacion_proyecto" class="w250px">Ubicación : </label>
                                    <input type="text" name="ubicacion_proyecto" id="ubicacion_proyecto" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="descripcion_centro" class="w250px">Responsable : </label>
                                    <input type="text" name="responsable_proyecto" id="responsable_proyecto" class="w40p mayusculas">
                                </div>
                            </div>
                           <?php 
                                $activos = $this->actives;
                                $inactivos = $this->inactives;
                                $total = $activos + $inactivos;
                            ?>
                            <div class="banners posicion_absoluta">
                                <div>
                                    <i class="fas fa-cogs"></i>
                                    <div>
                                        <p>Total Proyectos</p>
                                        <p><?php echo $total?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-barcode"></i>
                                    <div>
                                        <p>Proyectos Activos</p>
                                        <p><?php echo $activos?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-toolbox"></i>
                                    <div>
                                        <p>Proyectos Inactivos</p>
                                        <p><?php echo $inactivos?></p>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/proyectos.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>