<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png"/>
    <title>Sistema de Control Logístico - Ibis</title>
</head>
<body>
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
                                <th class="w100px">Nro Doc.</th>
                                <th>Apellidos y Nombres</th>
                                <th>...</th>
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
    <?php require 'views/header.php'; ?>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Personal</p>
                <?php require 'views/menus.php'; ?>
            </div>       
            <div class="formulario desactivado">
                <form action="#" method="POST" id="formItem">
                    <input type="hidden" name="codigo_personal" id="codigo_personal">
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
                                    <label for="nro_documento" class="w250px">Nro. Documento</label>
                                    <input type="text" name="nro_documento" id="nro_documento">
                                </div>
                                <div class="inputfield">
                                    <label for="estado" class="w250px">Estado</label>
                                    <input type="text" name="estado" id="estado">
                                </div>
                                <div class="inputfield">
                                    <label for="apellidos" class="w250px">Apellidos :</label>
                                    <input type="text" name="apellidos" id="apellidos" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="nombres" class="w250px">Nombres :</label>
                                    <input type="text" name="nombres" id="nombres" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="centro_costos" class="w250px">Centro de Costos :</label>
                                    <input type="text" name="centro_costos" id="centro_costos" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="sede" class="w250px">Sede :</label>
                                    <input type="text" name="sede" id="sede" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="correo" class="w250px">Correo Electrónico:</label>
                                    <input type="text" name="correo" id="correo" class="w40p minusculas">
                                </div>
                                <hr>
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
                                        <p>Total Personal</p>
                                        <p><?php echo $total?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-barcode"></i>
                                    <div>
                                        <p>Personal Activo</p>
                                        <p><?php echo $activos?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-toolbox"></i>
                                    <div>
                                        <p>Personal Inactivo</p>
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
    <script src="<?php echo constant('URL');?>public/js/personal.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>