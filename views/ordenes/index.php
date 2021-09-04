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
    <div class="modal zindex3" id="modalPedidos">
        <div class="insideWindow w70p">
            <div class="buscar">
                <i class="fas fa-search"></i>
                <input type="text" id="buscar" class="searchInput w98p">
            </div>
            <hr>
            <div class="mh30vh con_borde min15vh over_auto">
                <table id="tabla_lista_pedidos" class="w100p con_borde zebra f8rem">
                    <thead>
                        <tr class="titulo_tabla_zebra">
                            <th>Numero</th>
                            <th>Emisión</th>
                            <th>Vencimiento</th>
                            <th>Motivo</th>
                            <th>Area</th>
                            <th>Proyecto/Sede</th>
                            <th>Solicitante</th>
                            <th>..</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="drch">
                <a href="#" class="boton4" id="btnAceptarPedido">Aceptar</a>
                <a href="#" class="boton4" id="btnCancelarPedido">Cancelar</a>
            </div>
            <hr>
            <div class="display_grid g3items f8rem">
                <div>
                    <div class="display_grid g4items mb5px">
                        <label class="negrita">Número :</label>
                        <label class="con_borde pl20 h25px" id="numero"></label>
                        <label class="negrita">Emisión :</label>
                        <label class="con_borde pl20 h25px" id="fecha"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Proyecto :</label> 
                        <label class="con_borde pl20 h25px" id="proyecto"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Area :</label>
                        <label class="con_borde pl20 h25px" id="area"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">C.Costos :</label>
                        <label class="con_borde pl20 h25px" id="costos"></label>    
                    </div>
                </div>
                <div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Transporte</label>
                        <label class="con_borde pl20 h25px" id="transporte"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Concepto :</label>
                        <label class="con_borde pl20 h25px" id="concepto"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Solicitante :</label>
                        <label class="con_borde pl20 h25px" id="solicitante"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label  class="negrita">Detalles :</label>
                        <label class="con_borde pl20 h25px" id="espec_items"></label>    
                    </div>
                </div>
                <div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Tipo:</label>
                        <label class="con_borde pl20 h25px" id="tipo"></label>    
                    </div>
                    <div class="display_grid g2items mb5px">
                        <label class="negrita">Vencimiento</label>
                        <label class="con_borde pl20 h25px" id="fechaven"></label>    
                    </div>
                </div>
            </div>
            <div class="min15vh">
                <table id="tabla_detalle_pedidos" class="w100p con_borde zebra over_auto f7rem">
                    <thead>
                        <tr class="titulo_tabla_zebra">
                            <th>...</th>
                            <th>Item</th>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>UM</th>
                            <th>Cantidad</th>
                            <th>N° Parte</th>
                            <th>Adjudicado</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
           
    </div>
    <div class="modal" id="modalProcessOrden">
        <div class="process">
            <div class="sides_process">
                <ul class="tabs">
                    <li><a href="#" class="option_tab_selected">Descripción de la Orden</a></li>
                    <li class="oculto"><a href="tab2">Descripción del Pedido</a> </li>
                </ul>
                <div class="tab" id="tab1">
                    <form action="#" method="POST" id="formProcessOrder">
                        <input type="hidden" name="pedido" id="pedido">
                        <input type="hidden" name="nropedido" id="nropedido">
                        <input type="hidden" name="id_entidad" id="id_entidad">
                        <input type="hidden" name="cod_proyecto" id="cod_proyecto">
                        <input type="hidden" name="cod_area" id="cod_area">
                        <input type="hidden" name="cod_costos" id="cod_costos">
                        <input type="hidden" name="cod_transporte" id="cod_transporte">
                        <input type="hidden" name="cod_solicitante" id="cod_solicitante">

                        <div class="process_header3ec desactivado">
                            <div class="process_left">    
                                <div class="input_process g4items">
                                    <label for="numero" class="w100px">Número :</label>
                                    <input type="text" name="numOrd" id="numOrd" class="pl10" readonly>
                                    <label for="fecha" >Emisión :</label>
                                    <input type="date" name="fechaOrd" id="fechaOrd" class="pl10" value="<?php echo date("Y-m-d");?>">
                                </div>
                                <div class="input_process g2items">
                                    <label for="elaborado" class="w100px">Elaborado :</label>
                                    <input type="text" name="elaborado" id="elaborado" class="pl10 mayusculas desactivado" value="<?php echo $_SESSION['user']?>">
                                </div>
                                <div class="input_process g2items desplegable">
                                    <label for="proyectoOrd" class="w100px">Proyecto :</label>
                                    <input type="text" name="proyectoOrd" id="proyectoOrd" class="pl10 mayusculas" >
                                </div>
                                <div class="input_process g2items">
                                    <label for="areaOrd" class="w100px">Area/Of. :</label>
                                    <input type="text" name="areaOrd" id="areaOrd" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="costosOrd" class="w100px">C.Costos. :</label>
                                    <input type="text" name="costosOrd" id="costosOrd" class="pl10 mayusculas">
                                </div>
                            </div>
                            <div class="process_right">
                                <div class="input_process g2items">
                                    <label for="transporteOrd" class="w100px">Transporte :</label>
                                    <input type="text" name="transporteOrd" id="transporteOrd" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="conceptoOrd" class="w100px">Concepto :</label>
                                    <textarea name="conceptoOrd" id="conceptoOrd" rows="1" class="pl10"></textarea>
                                </div>
                                <div class="input_process g2items">
                                    <label for="detalleOrd" class="w100px">Detalle</label>
                                    <input type="text" name="detalleOrd" id="detalleOrd" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g4items">
                                    <label for="moneda" class="w100px">Moneda :</label>
                                    <input type="text" name="monedaOrd" id="monedaOrd" class="pl10" readonly>
                                    <label for="precio" class="w100px">Precio :</label>
                                    <input type="text" name="precioOrd" id="precioOrd" class="drch pr20">
                                </div>
                                <div class="input_process g2items">
                                    <label for="tipo" class="w100px">Tipo</label>
                                    <input type="text" name="tipoOrd" id="tipoOrd" class="pl10 mayusculas">
                                </div>
                            </div>
                            <div class="process_estate">
                                <div class="input_process g2items">
                                    <label for="entidad" class="w100px">Entidad :</label>
                                    <input type="text" name="entidad" id="entidad" class="pl10 mayusculas" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="ruc" class="w100px">RUC:</label>
                                    <input type="text" name="ruc" id="ruc" class="pl10 mayusculas" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="atencion" class="w100px">Atención:</label>
                                    <input type="text" name="atencion" id="atencion" class="pl10 mayusculas" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="descrip_title">
                            <span>Detalles</span>
                            <div>
                                <button type="button" id="btnImpPedido" title="Importar Pedido" class="boton1">
                                    <i class="fas fa-upload"></i> Importar Pedido
                                </button>
                                <button type="button" id="btnSendMail" title="Cierra la orden y la envia por correo" class="boton1">
                                    <i class="far fa-paper-plane"></i> Enviar Correo
                                </button>
                                <button type="button" id="btnGenDoc" title="Firmar Orden" class="boton1">
                                    <i class="far fa-file-pdf"></i> Vista Previa
                                </button>
                            </div>
                        </div>
                        <div class="process_items">
                            <div>
                                <table class="con_borde w100p" id="detalle_orden">
                                    <thead>
                                        <tr>
                                            <th class="con_borde w5p">Item</th>
                                            <th class="con_borde w8p">Codigo</th>
                                            <th class="con_borde w35p">Descripcion</th>
                                            <th class="con_borde w5p">UM</th>
                                            <th class="con_borde w5p">Cantidad</th>
                                            <th class="con_borde w5p">P.U.</th>
                                            <th class="con_borde w5p">Total</th>
                                            <th class="con_borde w8p">Nro. Parte</th>
                                            <th class="con_borde w8p">Nro. Pedido</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="oculto tab" id="tab2">
                    <div class="process_header desactivado">
                            <div class="process_left">    
                                <div class="input_process g4items">
                                    <label for="numero" class="w100px">Número :</label>
                                    <input type="number" name="numero" id="numero" class="pl10" readonly>
                                    <label for="fecha" class="w100px">Fec.Emisión :</label>
                                    <input type="date" name="fecha" id="fecha"  class="pl10">
                                </div>
                                <div class="input_process g2items">
                                    <label for="usuario" class="w100px">Usuario :</label>
                                    <input type="text" name="usuario" id="usuario" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g2items desplegable">
                                    <label for="proyecto" class="w100px">Proyecto :</label>
                                    <input type="text" name="proyecto" id="proyecto" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="area" class="w100px">Area/Of. :</label>
                                    <input type="text" name="area" id="area" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="area" class="w100px">C.Costos. :</label>
                                    <input type="text" name="costos" id="costos" class="pl10 mayusculas">
                                </div>
                            </div>
                            <div class="process_right">
                                <div class="input_process g2items">
                                    <label for="transporte" class="w100px">Transporte :</label>
                                    <input type="text" name="transporte" id="transporte" class="pl10 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="concepto" class="w100px">Concepto :</label>
                                    <textarea name="concepto" id="concepto" rows="4"></textarea>
                                </div>
                                <div class="input_process g2items">
                                    <label for="solicitante" class="w100px">Solicitante</label>
                                    <input type="text" name="solicitante" id="solicitante" class="pl10 mayusculas">
                                </div>
                            </div>
                            <div class="process_estate">
                                <div class="input_process g2items">
                                    <label for="registro" class="w100px">Est.Doc.:</label>
                                    <input type="text" name="registro" id="registro" class="pl10 mayusculas adjudicado" value="CERRADO" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="documento" class="w100px">Est.Registro:</label>
                                    <input type="text" name="documento" id="documento" class="pl10 mayusculas adjudicado" value="CERRADO" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="tipo" class="w100px">Tipo</label>
                                    <input type="text" name="tipo" id="tipo" class="pl10 mayusculas">
                                </div>
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
                                            <th class="con_borde w5p">Cantidad</th>
                                            <th class="con_borde w8p">Atendido con orden</th>
                                            <th class="con_borde w8p">Nro. Parte</th>
                                            <th class="con_borde w8p">Tipo</th>
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
                        <div class="details_item desactivado">
                            <textarea name="espec_items" id="espec_items" rows="3" class="w100p"></textarea>
                        </div>
                </div>
            </div>
        </div>
        <a href="#" id="closeModalProcess" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalPreview">
        <div class="insidePreview">
            <iframe src=""></iframe>
        </div>
        <a href="#" id="closeModalPreview" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Registro de Ordenes</p>
                <?php require 'views/menus.php'; ?>
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
                                <th class="con_borde w7p">Num.</th>
                                <th class="con_borde w7p">Emisión</th>
                                <th class="con_borde">Concepto</th>
                                <th class="con_borde">Area</th>
                                <th class="con_borde">Proyecto/Sede</th>
                                <th class="con_borde">Logística</th>
                                <th class="con_borde">Operaciones</th>
                                <th class="con_borde">Financiera</th>
                                <th class="con_borde">Atencion</th>
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
    <script src="<?php echo constant('URL');?>public/js/ordenes.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>