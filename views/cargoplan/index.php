<!DOCTYPE html>
<html lang="es">
<head>
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
    <div class="modal zindex7" id="modalPreview">
        <div class="insidePreview">
            <iframe src=""></iframe>
        </div>
        <a href="#" id="closeModalPreview" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex5" id="modalProcess">
        <div class="process">
            <div class="sides_process w50p">
                <div class="descrip_title">
                    <span>Detalles Cargo Plan</span>
                    <div>
                        <button type="button" id="btnCerrarDetalle" title="Cerrar" class="boton1">
                            <span><i class="far fa-save"></i> Cerrar </span>
                        </button>
                    </div>
                </div>
                <div class="processBody">
                    <div id="data_item">
                        <div>
                            <label for="codigo">Código</label>
                            <input type="text" name="codigo" id="codigo" readonly class="pl10">
                        </div>
                        <div>
                            <label for="producto">Descripción</label>
                            <input type="text" name="producto" id="producto" readonly class="pl10">
                        </div>
                        <div>
                            <label for="cantsolicitada">Cant. </br> Solicitada</label>
                            <input type="text" name="cantsolicitada" id="cantsolicitada" class="drch pr10" readonly>
                        </div>
                        <div>
                            <label for="cantaprobada">Cant.</br>Aprobada</label>
                            <input type="text" name="cantaprobada" id="cantaprobada" class="drch pr10" readonly>
                        </div>
                        <div>
                            <label for="estado">Estado:</label>
                            <input type="text" name="estado" id="estado" class="centro destino" readonly>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="nropedido">N° Pedido:</label>
                            <input type="text" name="nropedido" id="nropedido" class="drch pr10" readonly>
                        </div>
                        <div>
                            <label for="tipo_pedido">Tipo</label>
                            <input type="text" name="tipo_pedido" id="tipo_pedido" class="centro" readonly>
                        </div>
                        <div>
                            <label for="emision_pedido">Fecha</br>Emisión</label>
                            <input type="date" name="emision_pedido" id="emision_pedido" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_pedido">Fecha</br>Aprobación</label>
                            <input type="date" name="aprobacion_pedido" id="aprobacion_pedido" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <label for="aprobado_por">Aprobado por:</label>
                            <input type="text" name="aprobado_por" id="aprobado_por" class="pl10" readonly>
                        </div>
                        <div>
                            <a href="#" id="pdfpedido" class="callpreview"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div id="data_orden">
                        <div>
                            <label for="orden">N° Orden:</label>
                            <input type="text" name="nroorden" id="nroorden" class="drch pr10" readonly>
                        </div>
                        <div>
                            <label for="emision_orden">Fecha</br>Emisión</label>
                            <input type="date" name="emision_orden" id="emision_orden" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_logistica">Fec. Aprob.</br>Logistica</label>
                            <input type="date" name="aprobacion_logistica" id="aprobacion_logistica" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_operaciones">Fec. Aprob.</br>Operaciones</label>
                            <input type="date" name="aprobacion_operaciones" id="aprobacion_operaciones" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_finanzas">Fec. Aprob.</br>Finanzas</label>
                            <input type="date" name="aprobacion_finanzas" id="aprobacion_finanzas" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <a href="#" id="pdforden" class="callpreview"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="ingreso">N° Ingreso:</label>
                            <input type="text" name="ingreso" id="ingreso" class="drch pr5" readonly>
                        </div>
                        <div>
                            <label for="fecha_ingreso">Fecha Ingreso</label>
                            <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <a href="#" id="pdfingreso" class="callpreview"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="salida">N°. Salida:</label>
                            <input type="text" name="salida" id="salida" class="drch pr5" readonly>
                        </div>
                        <div>
                            <label for="fecha_salida">Fecha Salida</label>
                            <input type="date" name="fecha_salida" id="fecha_salida" class="centro unstyled" readonly>
                        </div>
                        <div>
                            <a href="#" id="pdfsalida" class="callpreview"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div>
                            <label for="proveedor">Nombre Proveedor:</label>
                            <input type="text" name="proveedor" id="proveedor" class="pl10" readonly>
                        </div>
                        <div>
                            <label for="ruc">R.U.C</label>
                            <input type="text" name="ruc" id="ruc" class="pl10" readonly>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="observaciones">Observaciones:</label>
                            <textarea name="observaciones" id="observaciones" class="pl10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Cargo Plan</p>
                <?php require 'views/menusimple.php'; ?>
            </div>       
            <div class="formulario">
                <div class="bannerCompuesto">
                    <div>
                        <div class="lineaInterna">
                            <div class="w20p">
                                <label for="tipo">Tipo :</label>
                                <select name="tipo" id="tipo">
                                    <option value="-1" class="oculto">Seleccione opcion</option>
                                    <option value="1">Bienes</option>
                                    <option value="2">Servicios</option>
                                </select>
                            </div>
                            <div class="w40p">
                                <label for="ccostos">Proyecto/Sede</label>
                                <select name="ccostos" id="ccostos" class="w70p">
                                    <option value="-1" class="oculto">Seleccione opcion</option>
                                    <option value="1">200 - Sede Lima</option>
                                    <option value="1">200.1 - Almacen Pucallpa</option>
                                    <option value="2">2800 - WHCP21 Compresión</option>
                                </select>
                            </div>
                            <div>
                                <label for="orden">Orden</label>
                                <input type="text" name="orden" id="orden">
                            </div>
                            <div>
                                <label for="orden">Pedido</label>
                                <input type="text" name="pedido" id="pedido">
                            </div>
                        </div>
                        <div class="lineaInterna">
                            <div>
                                <label for="fecOrigen">Del: </label>
                                <input type="date" name="fecOrigen" id="fecOrigen">
                            </div>
                            <div>
                                <label for="fecFinal">Al: </label>
                                <input type="date" name="fecFinal" id="fecFinal">
                            </div>
                            <div class="w30p">
                                <label for="orden">Almacen</label>
                                <select name="almacen" id="almacen" class="w70p">
                                    <option value="-1" class="oculto">Seleccione opcion</option>
                                    <option value="1">200 - Sede Lima</option>
                                    <option value="1">200.1 - Almacen Pucallpa</option>
                                    <option value="2">2800 - WHCP21 Compresión</option>
                                </select>
                            </div>
                            <div class="w30p">
                                <label for="descripcion">Descripción</label>
                                <input type="text" name="descripcion" id="descripcion" class="w70p">
                            </div>
                        </div>
                    </div>
                    <div class="comandoBanner">
                        <button type="button" class="w100p">Procesar</button>
                    </div>
                </div>
                <div class="pedidos">
                    <table class="w100p con_borde" id="tabla_ingresos">
                        <thead>
                            <tr>
                                <th class="con_borde w55px">Items</th>
                                <th class="con_borde w70px">Estado</br>Actual</th>
                                <th class="con_borde w60px">Código</br>Proyecto</th>
                                <th class="con_borde">Area Solicitante</th>
                                <th class="con_borde w70px">Prioridad</th>
                                <th class="con_borde w40px">Tipo</th>
                                <th class="con_borde w40px">Año</th>
                                <th class="con_borde w50px">Nro. </br> Pedido</th>
                                <th class="con_borde w80px">Creación </br> Pedido</th>
                                <th class="con_borde w150px">Aprobacion </br>Pedido</th>
                                <th class="con_borde w100px">Código</th>
                                <th class="con_borde w40px">UND</th>
                                <th class="con_borde">Descripción</th>
                                <th class="con_borde w90px">Cantidad</th>
                                <th class="con_borde w60px">Orden</th>
                                <th class="con_borde w80px">Fecha</br>Orden</th>
                                <th class="con_borde w60px">...</th>
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
    <script src="<?php echo constant('URL');?>public/js/cargoplan.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>