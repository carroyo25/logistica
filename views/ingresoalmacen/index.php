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
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Recepcion de Materiales</p>
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
                                <th class="con_borde w55px">Num. Guia</th>
                                <th class="con_borde">F.Emisión</th>
                                <th class="con_borde ">F.Traslado</th>
                                <th class="con_borde">Almacen Origen</th>
                                <th class="con_borde">Proyecto/Sede</th>
                                <th class="con_borde">Centro Costos</th>
                                <th class="con_borde">Area</th>
                                <th class="con_borde">Solicitante</th>
                                <th class="con_borde">Pedido</th>
                                <th class="con_borde">Orden</th>
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
            <input type="hidden" id="cod_despacho">
            <input type="hidden" id="cod_ingreso">
            <input type="hidden" id="alm_destino">
            <input type="hidden" id="cod_guia">
            <input type="hidden" id="cod_pedido">

            <div class="process">
                <div class="sides_process">
                        <div class="descrip_title">
                            <span>Datos Generales</span>
                            <div>
                                <button type="button" id="grabarDoc" title="Confirma Recepción" class="boton1">
                                    <span><i class="far fa-save"></i> Confirma Recepción </span>
                                </button>
                            </div>
                        </div>
                        <div class="process_header">
                            <div class="process_left">
                                <div class="input_process g4items">
                                    <label for="nrosalida" class="w100px">Nro. Guia :</label>
                                    <input type="text" name="nroguia" id="nroguia" class="pl10" readonly>
                                    <label for="movalma" class="w100px">Nro. Salida:</label>
                                    <input type="text" name="nrosalida" id="nrosalida" class="pl10" readonly>
                                </div>
                                <div class="input_process g4items">
                                    <label for="fechadoc" class="w100px">Fec. Emisión. :</label>
                                    <input type="date" name="fechadoc" id="fechadoc" readonly value="<?php echo date("Y-m-d");?>" class="pl20" readonly>
                                    <label for="fechacont" class="w100px">Fec. Traslado :</label>
                                    <input type="date" name="fechatras" id="fechatras" value="<?php echo date("Y-m-d");?>" class="pl20" readonly>
                                </div>
                                <div class="input_process g2items desplegable">
                                    <label for="proyecto" class="w100px">Proyecto :</label>
                                    <input type="text" name="proyecto" id="proyecto" class="pl20 mayusculas" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="solicita" class="w100px">Solicita :</label>
                                    <input type="text" name="solicita" id="solicita" class="pl20 mayusculas" readonly>
                                </div>
                            </div>
                            <div class="process_right">
                                <div class="input_process g2items">
                                    <label for="almacen" class="w100px">Almacen Orig.:</label>
                                    <input type="text" name="almacen" id="almacen" class="pl10" readonly>
                                </div>
                                <div class="input_process g4items">
                                    <label for="nroped" class="w100px">Nro. Pedido</label>
                                    <input type="text" name="nroped" id="nroped" class="pl20" readonly>
                                    <label for="fecped" class="w100px">Fecha Doc.:</label>
                                    <input type="date" name="fecped" id="fecped" class="pl20" readonly>
                                </div>
                                <div class="input_process g4items">
                                    <label for="nrord" class="w100px">Nro. OC:</label>
                                    <input type="text" name="nrord" id="nrord" class="pl20 " readonly>
                                    <label for="fecord" class="w100px">Fecha Doc.:</label>
                                    <input type="date" name="fecord" id="fecord" class="pl20 " readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="espec" class="w100px" >Observaciones:</label>
                                    <textarea name="espec" id="espec" rows="1" class="mayusculas " readonly></textarea>
                                </div>
                            </div>
                            <div class="process_estate">
                                <div class="input_process g2items">
                                    <label for="estadoc" class="w100px">Est.Doc.:</label>
                                    <input type="text" name="estadoc" id="estadoc" class="pl20 mayusculas proceso" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="items" class="w100px">Nro. Items:</label>
                                    <input type="text" name="items" id="items" class="pl20 drch pr20" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="items" class="w100px">Total Bultos:</label>
                                    <input type="text" name="bultos" id="bultos" class="pr20 drch " readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="items" class="w100px">Peso:</label>
                                    <input type="text" name="peso" id="peso" class="pr20 drch " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="descrip_title">
                            <span>Detalles</span>
                            <div>
                                <button type="button" id="docsAtach" title="Documentos Adjuntos" class="boton1">
                                    <i class="fas fa-envelope-open-text"></i> Documentos Adjuntos
                                </button>
                                <button type="button" id="orderDetail" title="Orden de compra asociada" class="boton1">
                                    <i class="fas fa-envelope-open-text"></i> Orden de compra asociada
                                </button>
                                <button type="button" id="guiaDetail" title="Guia de Remision - Envio y transporte" class="boton1">
                                    <i class="fas fa-envelope-open-text"></i> Guia de Remision - Envio y transporte
                                </button>
                            </div>
                        </div>
                        <div class="process_items">
                        <div>
                            <table class="con_borde w100p" id="detalle_despacho">
                                <thead>
                                    <tr>
                                        <th class="con_borde w2p">...</th>
                                        <th class="con_borde w2p">...</th>
                                        <th class="con_borde w3p">Item</th>
                                        <th class="con_borde w10p">Codigo</th>
                                        <th class="con_borde w30p">Descripcion</th>
                                        <th class="con_borde w5p">Unidad</th>
                                        <th class="con_borde w5p">Cantidad</th>
                                        <th class="con_borde w20p">Observaciones</th>
                                        <th class="con_borde w10p">Fecha </br> Vencimiento</th>
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
    <div class="modal zindex5" id="waitmodalCursorOffline">
    </div>
    <div class="modal zindex3" id="modalSerie">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Verificar Series</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <div class="titulos f7rem">
                    <h4 id="descripProducto"></h4>
                    <h4 id="codigoProducto" class="oculto"></h4>
                    <h4 id="nroItemSerial" class="oculto"></h4>
                    <a href="#" id="checkAll"><i class="far fa-check-square"></i></a>
                </div>
                <form>
                    <div>
                        <table id="detalle_series" class="w100p con_border espacio_tabla_0 f7rem">
                            <thead>
                                <tr>
                                    <th class="con_borde">...</th>
                                    <th class="con_borde">Item</th>
                                    <th class="con_borde">Nro. Serie</th>
                                    <th class="con_borde">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="options">
                        <button id="btnConfirmSerial" class="botones" type="button">Aceptar</button>
                        <button id="btnCancelSerial" class="botones" type="button">Cancelar</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/ingresoalmacen.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>