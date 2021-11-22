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
    <div class="mensaje msj_error">
        <span></span>
    </div>
    <div class="modal zindex5" id="waitmodalCursorOffline">
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Entrega de Materiales</p>
                <?php require 'views/menusimple.php'; ?>
            </div>       
            <div class="formulario">
                <div class="banner">
                    <div>
                        <label for="nrodoc">DNI/CE</label>
                        <input type="text" name="nrodoc" id="nrodoc">
                    </div>
                    <div>
                        <label for="nomsol">Solicitante</label>
                        <input type="text" name="nomsol" id="nomsol">
                    </div>
                    <div>
                        <label for="mes">Mes</label>
                        <select name="mes" id="mes">
                            <option value="-1" class="oculto">Seleccione opcion</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                        </select>
                    </div>
                    <div>
                        <label for="anio">Año</label>
                        <input type="number" name="anio" id="anio" min="2020" max="3000">
                    </div>
                    <div>
                        <button type="button">Procesar</button>
                    </div>
                </div>
                <div class="pedidos">
                    <table class="w100p con_borde" id="tabla_pedidos">
                        <thead>
                            <tr>
                                <th class="con_borde w55px">Num. Salida</th>
                                <th class="con_borde">F.Entrega</th>
                                <th class="con_borde">Almacen</th>
                                <th class="con_borde ">Nro.Documento</th>
                                <th class="con_borde">Solicitante</th>
                                <th class="con_borde">...</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $this->registros?>
                        </tbody>
                    </table>
                </div>
                <div class="resumen_pedidos">
                    <!-- <?php 
                    //$activos = $this->actives;
                    //$inactivos = $this->inactives;
                    //$total = $activos + $inactivos;
                    ?> -->
                    <div class="banners_pedido posicion_absoluta">
                        <div>
                            <i class="fas fa-cogs"></i>
                            <div>
                                <p>Emitidos</p>
                                <!-- <p><?php echo $total?></p> -->
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-barcode"></i>
                            <div>
                                <p>Pendientes</p>
                                <!-- <p><?php echo $activos?></p> -->
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-toolbox"></i>
                            <div>
                                <p>Rechazados</p>
                                <!-- <p><?php echo $inactivos?></p> -->
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modalProcess">
        <form action="#" autocomplete="off" id="formProcess">
            <input type="hidden" id="cod_almacen" name="cod_almacen">
            <input type="hidden" id="cod_personal" name="cod_personal">
            <input type="hidden" id="cod_salida" name="cod_salida">

            <div class="process">
                <div class="two_sides_process w55p h85vh">
                        <div class="descrip_title">
                            <span>Datos Generales</span>
                            <div>
                                <button type="button" id="grabarDoc" title="Confirma Recepción" class="boton1">
                                    <span><i class="far fa-save"></i> Confirmar Salida </span>
                                </button>
                            </div>
                        </div>
                        <div class="process_header1ec">
                            <div class="process_left">
                                <div class="input_process g4items1001 desplegable">
                                    <label for="almacen" class="w100px">Almacen :</label>
                                    <input type="text" name="almacen" id="almacen" class="pl20 mayusculas">
                                    <div class="seleccion seleccion_pedido">
                                        <ul id="listaAlmacen">
                                            <?php echo $this->almacenes?>
                                        </ul>
                                    </div>
                                    <label for="fecha">Fecha :</label>
                                    <input type="date" name="fechaEntrega" id="fechaEntrega" value="<?=date("Y-m-d")?>">
                                </div>
                                <div class="input_process g4items100 desplegable">
                                    <label for="movalma" class="w100px">DNI/CE:</label>
                                    <input type="text" name="nrodocumento" id="nrodocumento" class="pl10">
                                    <label for="solicitante">Solicitante :</label>
                                    <input type="text" name="solic" id="solicitante" class="pl20 mayusculas" readonly>
                                </div>
                                <div class="input_process g2items desplegable">
                                    <label for="cargo" class="w100px">Cargo :</label>
                                    <input type="text" name="cargo" id="cargo" class="pl20 mayusculas" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="descrip_title">
                            <span>Detalles</span>
                            <div>
                                <button type="button" id="callItems" title="Agregar Items" class="boton1">
                                    <i class="fas fa-envelope-open-text"></i> Agregar Items
                                </button>
                            </div>
                        </div>
                        <div class="process_items">
                        <div>
                            <table class="con_borde w100p f8rem" id="detalle_despacho">
                                <thead>
                                    <tr>
                                        <th class="con_borde w2p">...</th>
                                        <th class="con_borde w3p">Item</th>
                                        <th class="con_borde w10p">Codigo</th>
                                        <th class="con_borde w30p">Descripcion</th>
                                        <th class="con_borde w5p">Unidad</th>
                                        <th class="con_borde w5p">Cantidad</th>
                                        <th class="con_borde w20p">Serie/N°.Parte</th>
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
        <a href="#" id="closeModalProcess" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalItems">
        <div class="search">
            <div>
                <div id="titleSearch">
                    <h3>Seleccionar Items : </h3>
                </div>
                <div class="divSearch">
                    <i class="fas fa-search"></i>
                    <input type="search" id="inputSearchItems">
                </div>
                <div class="listData">
                    <table id="tableItems">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Nro. Parte/Serie</th>
                                <th>Unidad</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <ul>
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="addItemWindow">
            <div class="titulo">
                <h3>Agregar Item</h3>
                <button type="button" id="closeNew"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="cuerpo">
                <form action="#" id="addItemForm">
                    <input type="hidden" name="itemTipo" id="itemTipo">
                    <input type="hidden" name="cod_unidad" id="cod_unidad">
                    <h3>Codificación Asignada</h3>
                    
                    <div class="enterData desplegable">
                        <label for="Clase">Grupo</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="grupo" id="grupo">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaGrupos"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="clase">Clase</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="clase" id="clase">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaClases"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="familia">Familia</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="familia" id="familia">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaFamilias"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="codigo_item">Codigo</label>
                        <input type="text" name="codigo_item" id="codigo_item" readonly>
                    </div>
                    <h3>Descripcion Item</h3>
                    <div class="enterData">
                        <label for="descrip">* Descripcion :</label>
                        <input type="text" name="descrip" id="descrip" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="nom_com">* Nombre Comercial :</label>
                        <input type="text" name="nom_com" id="nom_com" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="nom_cor">Nombre Corto :</label>
                        <input type="text" name="nom_cor" id="nom_cor" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="unidad">* Unidad :</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="unidad" id="unidad" class="mayusculas p20l">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaUnidades"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="detalles">* Detalles :</label>
                        <textarea name="detalles" id="detalles" rows="4"></textarea>
                    </div>
                    <div class="enterData">
                        <label for="marca">Marca :</label>
                        <input type="text" name="marca" id="marca" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="modelo">Modelo :</label>
                        <input type="text" name="modelo" id="modelo" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="nro_parte">Nro. Parte :</label>
                        <input type="text" name="nro_parte" id="nro_parte" class="mayusculas p20l">
                    </div>
                    <div class="opciones">
                        <button type="submit" id="submitItem">Registrar</button>
                        <button type="reset" id="resetItem">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        <a href="#" class="buttonClose" id="closeModalItems"><i class="fas fa-reply-all"></i></a>    
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/salidaalmacen.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>