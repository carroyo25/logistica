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
                            <input type="text" name="codigo" id="codigo" readonly>
                        </div>
                        <div>
                            <label for="descripcion">Descripción</label>
                            <input type="text" name="descripcion" id="descripcion" readonly>
                        </div>
                        <div>
                            <label for="cantsolicitada">Cant. </br> Solicitada</label>
                            <input type="text" name="cantsolicitada" id="cantsolicitada" readonly>
                        </div>
                        <div>
                            <label for="cantaprobada">Cant.</br>Aprobada</label>
                            <input type="text" name="cantaprobada" id="cantaprobada" readonly>
                        </div>
                        <div>
                            <label for="estado">Estado:</label>
                            <input type="text" name="estado" id="estado" readonly>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="pedido">N° Pedido:</label>
                            <input type="text" name="pedido" id="pedido" readonly>
                        </div>
                        <div>
                            <label for="tipo_pedido">Tipo</label>
                            <input type="text" name="tipo_pedido" id="tipo_pedido" readonly>
                        </div>
                        <div>
                            <label for="emsion_pedido">Fecha</br>Emisión</label>
                            <input type="text" name="emsion_pedido" id="emsion_pedido" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_pedido">Fecha</br>Aprobación</label>
                            <input type="text" name="aprobacion_pedido" id="aprobacion_pedido" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_pedido">Aprobado por:</label>
                            <input type="text" name="aprobacion_pedido" id="aprobacion_pedido" readonly>
                        </div>
                        <div>
                            <a href="#"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div id="data_orden">
                        <div>
                            <label for="orden">N° Orden:</label>
                            <input type="text" name="orden" id="orden" readonly>
                        </div>
                        <div>
                            <label for="tipo_orden">Tipo</label>
                            <input type="text" name="tipo_orden" id="tipo_orden" readonly>
                        </div>
                        <div>
                            <label for="emision_orden">Fecha</br>Emisión</label>
                            <input type="text" name="emision_orden" id="emision_orden" readonly>
                        </div>
                        <div>
                            <label for="aprobacion_orden">Fecha</br>Aprobación</label>
                            <input type="text" name="aprobacion_orden" id="aprobacion_orden" readonly>
                        </div>
                        <div>
                            <a href="#"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="ingreso">N° Ingreso:</label>
                            <input type="text" name="ingreso" id="ingreso" readonly>
                        </div>
                        <div>
                            <label for="fecha_ingreso">Fecha Ingreso</label>
                            <input type="text" name="fecha_ingreso" id="fecha_ingreson" readonly>
                        </div>
                        <div>
                            <a href="#"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="salida">N°. Salida:</label>
                            <input type="text" name="ingreso" id="ingreso" readonly>
                        </div>
                        <div>
                            <label for="fecha_salida">Fecha Salida</label>
                            <input type="text" name="fecha_ingreso" id="fecha_ingreson" readonly>
                        </div>
                        <div>
                            <a href="#"><i class="far fa-file-pdf"></i></a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div>
                            <label for="proveedor">Nombre Proveedor:</label>
                            <input type="text" name="proveedor" id="proveedor" readonly>
                        </div>
                        <div>
                            <label for="ruc">R.UC</label>
                            <input type="text" name="ruc" id="ruc" readonly>
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="observaciones">Observaciones:</label>
                            <textarea name="observaciones" id="observaciones"></textarea>
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