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
                    <table class="tableSeek">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
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
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Grupos</p>
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
                                <div>
                                    <label for="codigo" class="w250px">Código</label>
                                    <input type="text" name="codigo" id="codigo" class="desactivado w40p">
                                </div>
                                <div>
                                    <label for="descripcion" class="w250px">Descripción *</label>
                                    <input type="text" name="descripcion" id="descripcion" class="mayusculas w40p">
                                </div>
                            </div>
                            
                            <div class="banners posicion_absoluta">
                                <div>
                                    <i class="fas fa-cogs"></i>
                                    <div>
                                        <p>Total Grupos</p>
                                        <p><?php echo $this->totalGroups?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-barcode"></i>
                                    <div>
                                        <p>Grupos Activos</p>
                                        <p><?php echo $this->totalGroups?></p>
                                    </div>
                                </div>
                                <div>
                                    <i class="fas fa-toolbox"></i>
                                    <div>
                                        <p>Grupos Inactivos</p>
                                        <p>0</p>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <span id="flags" class="oculto"><?php echo $this->flags; ?></span>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/grupos.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>