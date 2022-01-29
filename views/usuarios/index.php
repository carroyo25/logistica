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
        <div class="containerWait">
            <h1 class="title">Cargando...</h1>
            <div class="rainbow-marker-loader"></div>
        </div>
    </div>
    <div class="mensaje msj_error">
        <span></span>
    </div>
    <div class="modal" id="modalProcess">
        <form action="#" autocomplete="off" id="formProcess">
            <input type="hidden" name="cod_user" id="cod_user">
            <input type="hidden" name="cod_resp" id="cod_resp">
            <input type="hidden" name="cod_niv" id="cod_niv">
            <input type="hidden" name="cod_est" id="cod_est">
            <input type="hidden" name="cod_cargo" id="cod_cargo">
            <input type="hidden" name="old_pass" id="old_pass">
            <div class="process">
                <div class="sides_process w85p">
                <div class="descrip_title">
                    <span>Datos Generales</span>
                    <div>
                        <button type="button" id="saveItem" title="Grabar Datos">
                            <span><i class="far fa-save"></i> Grabar Registro</span> 
                        </button>
                        <button type="button" id="cancelItem" title="Cancelar">
                            <i class="fas fa-ban"></i> Cancelar Registro
                        </button>
                    </div>
                </div>
                    <div class="process_header">
                        <div class="process_left">    
                            <div class="input_process g4items">
                                <label for="codigo" class="w100px">Código :</label>
                                <input type="number" name="codigo" id="codigo" class="pl20" readonly>
                            </div>
                            <div class="input_process g2items">
                                <label for="usuario" class="w100px">Usuario :</label>
                                <input type="text" name="usuario" id="usuario" class="pl20 mayusculas">
                            </div>
                            <div class="input_process g2items">
                                <label for="Nombres" class="w100px">Nombres :</label>
                                <input type="text" name="nombres" id="nombres" class="pl20 mayusculas" autocomplete="off">
                            </div>
                            <div class="input_process g2items">
                                <label for="clave" class="w100px">Clave. :</label>
                                <input type="password" name="clave" id="clave" class="pl20 mayusculas" autocomplete="off">
                            </div>
                        </div>
                        <div class="process_right">
                            <div class="input_process g2items">
                                <label for="responsable" class="w100px">Responsable :</label>
                                <input type="text" name="responsable" id="responsable" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaResponsable">
                                    </ul>
                                </div>
                            </div>
                            <div class="input_process g2items">
                                <label for="responsable" class="w100px">Cargo :</label>
                                <input type="text" name="cargo" id="cargo" class="pl20 mayusculas" readonly>
                            </div>
                            <div class="input_process g2items">
                                <label for="correo" class="w100px">Correo :</label>
                                <input type="mail" name="correo" id="correo" class="pl20 minusculas">
                            </div>
                            <div class="input_process g4items">
                                <label for="desde" class="w100px">Desde :</label>
                                <input type="date" name="desde" id="desde" class="pl20">
                                <label for="hasta" class="w100px">Hasta :</label>
                                <input type="date" name="hasta" id="hasta" class="pl20">
                            </div>
                        </div>
                        <div class="process_estate">
                            <div class="input_process g2items">
                                <label for="nivel" class="w100px">Nivel:</label>
                                <input type="text" name="nivel" id="nivel" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaNivel">
                                    </ul>
                                </div>
                            </div>
                            <div class="input_process g2items">
                                <label for="estado" class="w100px">Estado : </label>
                                <input type="text" name="estado" id="estado" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaEstado">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="descrip_title">
                        <span>Detalles</span>
                        <div>
                            <button type="button" id="addItem" title="Añadir Item">
                                <i class="far fa-plus-square"></i> Agregar
                            </button>
                        </div>
                    </div>
                    <div class="process_items">
                        <ul class="tabs_process">
                            <li><a href="#" class="tab_selected" data-tab="tab1">Accesos</a></li>
                            <li><a href="#" data-tab="tab2">Proyectos/Sedes</a></li>
                            <li><a href="#" data-tab="tab3">Almacénes</a></li>
                            <li><a href="#" data-tab="tab4">Aprobación</a></li>
                        </ul>
                        <div class="tab" id="tab1">
                            <table class="con_borde w100p" id="detalle_modulos">
                                <thead>
                                    <tr>
                                        <th class="con_borde w5p">...</th>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w35p">Módulo</th>
                                        <th class="con_borde w5p">Agregar</th>
                                        <th class="con_borde w5p">Modificar</th>
                                        <th class="con_borde w5p">Eliminar</th>
                                        <th class="con_borde w5p">Imprimir</th>
                                        <th class="con_borde w5p">Procesar</th>
                                        <th class="con_borde w5p">Visible</th>
                                        <th class="con_borde w5p">Estado</th>
                                        <th class="con_borde w5p">Todos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="tab oculto" id="tab2">
                            <table class="con_borde w100p" id="detalle_proyectos">
                                <thead>
                                    <tr>
                                        <th class="con_borde w5p">...</th>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w8p">Codigo</th>
                                        <th class="con_borde w35p">Descripcion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="tab oculto" id="tab3">
                            <table class="con_borde w100p" id="detalle_almacen">
                                <thead>
                                    <tr>
                                        <th class="con_borde w5p">...</th>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w8p">Codigo</th>
                                        <th class="con_borde w35p">Descripcion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="tab oculto" id="tab4">
                            <table class="con_borde w100p" id="detalle_aprobacion">
                                <thead>
                                    <tr>
                                        <th class="con_borde w5p">...</th>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w5p">Codigo</th>
                                        <th class="con_borde w30p">Módulo</th>
                                        <th class="con_borde w30p">Nombre</th>
                                        <th class="con_borde w30p">Correo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="rightList">
            <div class="titulo">
                <h3>Seleccione de la Lista</h3>
                <button type="button" id="closeItems"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="cuerpo">
                <form action="#" id="formItems">
                    <div class="enterData">
                        <input type="text" name="searchItemsText" id="searchItemsText" placeholder="buscar">
                    </div>
                    <table id="tabla_items" class="con_borde w100p espacio_tabla_0 mt10px">
                        <thead>
                            <tr>
                                <th class="con_borde">Código</th>
                                <th class="con_borde">Modulo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <a href="#" id="closeModalProcess" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Administrar Usuarios</p>
                <?php require 'views/menus.php'; ?>
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
                </div>
                <div class="pedidos">
                    <table class="w100p con_borde" id="tabla_usuarios">
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
                           <?php echo $this->usuarios?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/usuarios.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>