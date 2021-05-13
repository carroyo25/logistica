<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png" />
    
    <title>Sistema de Control logístico - Ibis</title>
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
                <p class="workTitle">Almacenes</p>
                <?php require 'views/menus.php'; ?>
            </div>       
            <div class="formulario desactivado">
                <form action="#" method="POST" id="formItem">
                    <input type="hidden" name="index_almacen" id="index_almacen">
                    <input type="hidden" name="ubigeo" id="ubigeo">
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
                                    <label for="codigo_almacen" class="w250px">Código</label>
                                    <input type="text" name="codigo_almacen" id="codigo_almacen" class="desactivado">
                                </div>
                                <div class="inputfield">
                                    <label for="descripcion_almacen" class="w250px">* Descripción :</label>
                                    <input type="text" name="descripcion_almacen" id="descripcion_almacen" class="w40p mayusculas">
                                </div>
                                <hr>
                                <h4>Dirección Almacen</h4>
                                <div class="inputfield">
                                    <label for="via_tipo" class="w250px">Via Tipo :</label>
                                    <input type="text" name="via_tipo" id="via_tipo" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="via_nombre" class="w250px">Via Nombre :</label>
                                    <input type="text" name="via_nombre" id="via_nombre" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="numero" class="w250px">Nro :</label>
                                    <input type="text" name="numero" id="numero">
                                </div>
                                <div class="inputfield">
                                    <label for="zona" class="w250px">Zona :</label>
                                    <input type="text" name="zona" id="zona" class="w40p mayusculas">
                                </div>
                                <div class="inputfield">
                                    <label for="dpto" class="w250px">Departamento :</label>
                                    <input type="text" name="dpto" id="dpto" class="w40p">
                                    <div class="seleccion mh15vh seleccion_one_form" id="comboDpto">
                                            <ul id="lista">
                                                <?php echo $this->listdpto?>
                                            </ul>
                                        </div>
                                </div>
                                <div class="inputfield">
                                    <label for="prov" class="w250px">Provincia :</label>
                                    <input type="text" name="prov" id="prov" class="w40p">
                                    <div class="seleccion mh15vh seleccion_one_form" id="comboProv">
                                            <ul id="lista">
                                                <?php echo $this->listprov?>
                                            </ul>
                                        </div>
                                </div>
                                <div class="inputfield">
                                    <label for="dist" class="w250px">Distrito :</label>
                                    <input type="text" name="dist" id="dist" class="w40p">
                                    <div class="seleccion mh15vh seleccion_one_form" id="comboDist">
                                            <ul id="lista">
                                                <?php echo $this->listdist?>
                                            </ul>
                                        </div>
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
                                        <p>Total Almacenes</p>
                                        <p><?php echo $total?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-barcode"></i>
                                    <div>
                                        <p>Almacenes Activos</p>
                                        <p><?php echo $activos?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-toolbox"></i>
                                    <div>
                                        <p>Almacenes Inactivos</p>
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
    <script src="<?php echo constant('URL');?>public/js/almacenes.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>