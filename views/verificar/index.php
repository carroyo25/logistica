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
    <div class="modal zindex3" id="modalPreview">
        <div class="insidePreview">
            <iframe src=""></iframe>
        </div>
        <a href="#" id="closeModalPreview" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal" id="modalProcess">
        <form action="#" autocomplete="off" id="formProcess">
            <input type="hidden" name="cod_pedido" id="cod_pedido">
            <input type="hidden" name="cod_proy" id="cod_proy">
            <input type="hidden" name="cod_cost" id="cod_cost">
            <input type="hidden" name="cod_area" id="cod_area">
            <input type="hidden" name="cod_transporte" id="cod_transporte">
            <input type="hidden" name="cod_estdoc" id="cod_estdoc" value="1">
            <input type="hidden" name="cod_registro" id="cod_registro" value="1">
            <input type="hidden" name="cod_solicitante" id="cod_solicitante">
            <input type="hidden" name="cod_tipo" id="cod_tipo">
            <input type="hidden" name="estado" id="estado" value="1">
            <input type="hidden" name="atencion" id="atencion" value="3">
            <div class="process">
                <div class="sides_process w85p">
                <div class="descrip_title">
                    <span>Datos Generales</span>
                    <div>
                        <button type="button" id="saveItem" title="Grabar Pedido">
                            <span><i class="far fa-save"></i> Grabar Revisión</span> 
                        </button>
                        <button type="button" id="cancelItem" title="Cancelar Pedido">
                            <i class="fas fa-ban"></i> Cancelar Revisión
                        </button>
                    </div>
                </div>
                    <div class="process_header">
                        <div class="process_left">    
                            <div class="input_process g4items">
                                <label for="numero" class="w100px">Número :</label>
                                <input type="number" name="numero" id="numero" class="pl20" readonly>
                                <label for="fecha" class="w100px">Fec.Emisión :</label>
                                <input type="date" name="fecha" id="fecha" value="<?php echo date("Y-m-d");?>" class="pl20">
                            </div>
                            <div class="input_process g2items">
                                <label for="usuario" class="w100px">Usuario :</label>
                                <input type="text" name="usuario" id="usuario" value="<?php echo $_SESSION['cnameuser'];?>" class="pl20 mayusculas desactivado">
                            </div>
                            <div class="input_process g2items desplegable">
                                <label for="proyecto" class="w100px">Proyecto :</label>
                                <input type="text" name="proyecto" id="proyecto" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaProyectos">
                                        <?php echo $this->proyecto?>
                                    </ul>
                                </div>
                            </div>
                            <div class="input_process g2items">
                                <label for="area" class="w100px">Area/Of. :</label>
                                <input type="text" name="area" id="area" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaAreas">
                                        <?php echo $this->areas?>
                                    </ul>
                                </div>
                            </div>
                            <div class="input_process g2items">
                                <label for="area" class="w100px">C.Costos. :</label>
                                <input type="text" name="costos" id="costos" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaCostos">
                                        <?php echo $this->costos?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="process_right">
                            <div class="input_process g2items">
                                <label for="transporte" class="w100px">Transporte :</label>
                                <input type="text" name="transporte" id="transporte" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaTransporte">
                                        <?php echo $this->transporte?>
                                    </ul>
                                </div>
                            </div>
                            <div class="input_process g2items">
                                <label for="concepto" class="w100px">Concepto :</label>
                                <textarea name="concepto" id="concepto" rows="3"></textarea>
                            </div>
                            <div class="input_process g2items">
                                <label for="solicitante" class="w100px">Solicitante</label>
                                <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                                <input type="text" name="solicitante" id="solicitante" class="pl20 mayusculas" placeholder="Seleccione una opcion">
                                <div class="seleccion seleccion_pedido">
                                    <ul id="listaSolicitante">
                                       
                                    </ul>
                                </div>
                            </div>
                            <div class="input_process g4items">
                                <label for="tipo" class="w100px">Tipo Pedido:</label>
                                <input type="text" name="tipo" id="tipo" class="pl20 mayusculas">
                                <div class="seleccion seleccion_pedido w30p">
                                    <ul id="listaTipo">
                                        <?php echo $this->tipo?>
                                    </ul>
                                </div>
                                <label for="fechaven" class="w100px">Fec.Vence :</label>
                                <input type="date" name="fechaven" id="fechaven" class="pl20">
                            </div>
                        </div>
                        <div class="process_estate">
                            <div class="input_process g2items">
                                <label for="registro" class="w100px">Est.Doc.:</label>
                                <input type="text" name="registro" id="registro" class="pl20 mayusculas proceso" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="descrip_title">
                        <span>Detalles</span>
                        <div class="oculto">
                            <button type="button" id="addItem" title="Añadir Item">
                                <i class="far fa-plus-square"></i> Agregar Item
                            </button>
                            <button type="button" id="imporItems" title="Importar Registros">
                                <i class="fas fa-file-import"></i> Importar Items
                            </button>
                        </div>
                    </div>
                    <div class="process_items">
                        <div>
                            <table class="con_borde w100p" id="detalle_pedido">
                                <thead>
                                    <tr>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w8p">Codigo</th>
                                        <th class="con_borde w35p">Descripcion</th>
                                        <th class="con_borde w5p">UM</th>
                                        <th class="con_borde w5p">Cant. Pedida</th>
                                        <th class="con_borde w5p">Atencion Almacen</th>
                                        <th class="con_borde w8p">Nro. Parte</th>
                                        <th class="con_borde w5p">Aprobar</th>
                                        <th class="con_borde w5p">Tipo</th>
                                        <th class="con_borde w5p">Adjunto</th>
                                        <th class="con_borde w8p">Comentarios</th>
                                        <th class="con_borde oculto">Factor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="descrip_title">
                        <span>Especificaciones Técnicas o Descripción del Item</span>
                    </div>
                    <div class="details_item">
                        <textarea name="espec_items" id="espec_items" rows="5" class="w100p"></textarea>
                    </div>
                </div>
            </div>
        </form>
        <a href="#" id="closeModalProcess" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex5" id="waitmodalCursorOffline">
    </div>
    <div class="modal" id="dialogConfirm">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Pregunta</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h1> ¿Los Datos con Correctos? </h1> <!--alt 168-->   
                <div class="options">
                    <button id="btnYes" class="botones">Si</button>
                    <button id="btnNo" class="botones">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Verificación de Pedidos</p>
                <?php require 'views/menusimple.php'; ?>
            </div>       
            <div class="formulario">
                <div class="banner">
                    <div>
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo">
                            <option value="-1" class="oculto">Seleccione opcion</option>
                            <option value="1">Bienes</option>
                            <option value="2">Servicios</option>
                        </select>
                    </div>
                    <div>
                        <label for="ccostos">Proyecto/Sede</label>
                        <select name="ccostos" id="ccostos">
                            <option value="-1" class="oculto">Seleccione opcion</option>
                            <option value="1">200 - Sede Lima</option>
                            <option value="1">200.1 - Almacen Pucallpa</option>
                            <option value="2">2800 - WHCP21 Compresión</option>
                        </select>
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
                                <th class="con_borde w55px">Num.</th>
                                <th class="con_borde w100px">Emisión</th>
                                <th class="con_borde w100px">Vencimiento</th>
                                <th class="con_borde">Motivo</th>
                                <th class="con_borde">Area</th>
                                <th class="con_borde">Proyecto/Sede</th>
                                <th class="con_borde">Responsable</th>
                                <th class="con_borde w100px">Estado Doc.</th>
                                <th class="con_borde w100px">Atención</th>
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
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/verificar.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>