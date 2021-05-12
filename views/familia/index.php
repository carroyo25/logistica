<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png" />
    <title>Sistema de Control logístico - Ibis</title>
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
                    <table class="tableSeek">
                        <thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Clase</th>
                                <th>Familia</th>
                                <th>...</th>
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
    <div class="mensaje msj_error">
        <span></span>
    </div> 
    <?php require 'views/header.php'; ?>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                    <p class="workTitle">Familas</p>
                    <?php require 'views/menus.php'; ?>
                </div>
            <div class="formulario">
                <form action="#" method="POST">
                    <input type="hidden" name="indice" id="indice">
                    <div class="banner">
                        <p id="descrip"></p>
                    </div>
                    <div class="descripcion">
                        <div class="menu_horizontal">
                            <ul>
                                <li class="selected"><a href="#" data-index="1">Datos Básicos</a>  </li>
                            </ul>
                        </div>
                        <div class="datos" id="form1">
                            <div class="datainputs desactivado">
                                <div class="inputfield">
                                    <label for="codigo" class="w250px">Código</label>
                                    <input type="text" name="codigo_grupo" id="codigo_grupo" class="desactivado w40p">
                                </div>
                                <div class="inputfield desplegable">
                                    <label for="descrip" class="w250px">* Grupo</label>
                                    <input type="text" name="nombre_grupo" id="nombre_grupo" class="mayusculas w40p"  placeholder="Seleccione una opción">
                                    <div class="seleccion mh40vh seleccion_one_form">
                                        <ul id="listaGrupo">
                                            <?php echo $this->groupList;?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="datainputs desactivado">
                                <div>
                                    <label for="codigo" class="w250px">Código</label>
                                    <input type="text" name="codigo_clase" id="codigo_clase" class="desactivado w40p">
                                </div>
                                <div class="inputfield desplegable">
                                    <label for="Clase" class="w250px">* Clase</label>
                                    <input type="text" name="nombre_clase" id="nombre_clase" class="mayusculas w40p" placeholder="Seleccione una opción">
                                    <div class="seleccion mh40vh seleccion_one_form">
                                        <ul id="listaClase">
                                           <!-- <?php echo $this->classList;?> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="datainputs desactivado">
                                <div>
                                    <label for="codigo" class="w250px">Código</label>
                                    <input type="text" name="codigo_familia" id="codigo_familia" class="desactivado w40p">
                                </div>
                                <div>
                                    <label for="descrip" class="w250px">* Familia</label>
                                    <input type="text" name="descripcion" id="descripcion" class="mayusculas w40p">
                                </div>
                            </div>
                            <div class="banners posicion_absoluta">
                                <div>
                                    <i class="fas fa-cogs"></i>
                                    <div>
                                        <p>Total Familias</p>
                                        <p><?php echo $this->totalFamilies?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-barcode"></i>
                                    <div>
                                        <p>Familias Activas</p>
                                        <p><?php echo $this->totalFamilies?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-toolbox"></i>
                                    <div>
                                        <p>Familias Inactivas</p>
                                        <p>0</p>
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
    <script src="<?php echo constant('URL');?>public/js/familia.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>