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
    <div class="modal" id="modalProcess">
        <div class="process">
            <div class="sides_process w35p">
                <div class="descrip_title">
                    <span>Evaluación Proveedores</span>
                    <div>
                        <button type="button" id="btnCerrarDetalle" title="Cerrar" class="boton1">
                            <span><i class="far fa-save"></i> Cerrar </span>
                        </button>
                    </div>
                </div>
                <div class="vistapuntaje">
                    <div>
                        <div>
                            <label for="ruc">RUC</label>
                            <input type="text" name="ruc" id="ruc" readonly class="pl20">
                        </div>
                        <div>
                            <label for="nombre">Nombre :</label>
                            <input type="text" name="nombre" id="nombre" readonly class="w100p pl20">
                        </div>
                    </div>
                    <div>
                        <div>
                            <label for="pedido">N° Pedido</label>
                            <input type="text" name="pedido" id="pedido" readonly class="pl20">
                        </div>
                        <div>
                            <label for="nombre">N° Orden</label>
                            <input type="text" name="orden" id="orden" readonly class="pl20">
                        </div>
                        <div>
                            <label for="tipo">Tipo</label>
                            <input type="text" name="tipo" id="tipo" readonly class="pl20">
                        </div>
                    </div>
                    <h3>Calificación</h3>
                    <div>
                        <div>
                            <div>
                                <span>Participacion</span>
                                <input type="text" name="participa" id="participa" readonly class="drch pr20">
                            </div>
                            <div>
                                <span>Entrega</span>
                                <input type="text" name="entrega" id="entrega" readonly class="drch pr20">
                            </div>
                            <div>
                                <span>Calidad</span>
                                <input type="text" name="calidad" id="calidad" readonly class="drch pr20">
                            </div>
                            <div>
                                <span>Almacen</span>
                                <input type="text" name="almacen" id="almacen" readonly class="drch pr20">
                            </div>
                        </div>
                        <div class="valor">
                            <span id="puntuacion"></span>
                        </div>
                    </div>
                    
                    <h3>Calificación General</h3>
                    <div>
                        <div>
                            <span>Ordenes Atendidas :</span>
                            <input type="text" name="atenciones" id="atenciones" readonly class="drch pr20">
                        </div>
                        <div>
                            <span>Puntaje Acumulado :</span>
                            <input type="text" name="puntaje_total" id="puntaje_total" readonly class="drch pr20">
                        </div>
                        <div>
                            <span>Calificación Proveedor :</span>
                            <input type="text" name="calificacion_general" id="calificacion_general" readonly class="pl20" value="BUENA">
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
                <p class="workTitle">Calificación de Proveedores</p>
                <?php require 'views/menusimple.php'; ?>
            </div>       
            <div class="formulario">
                <div class="bannerCompuesto">
                    <div>
                        <div class="lineaInterna">
                            <div class="w40p">
                                <label for="ccostos">Proveedor</label>
                                <select name="ccostos" id="ccostos" class="w70p">
                                    <option value="-1" class="oculto">Seleccione opcion</option>
                                    <option value="1">200 - Sede Lima</option>
                                    <option value="1">200.1 - Almacen Pucallpa</option>
                                    <option value="2">2800 - WHCP21 Compresión</option>
                                </select>
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
                                <th class="con_borde">Nombre</th>
                                <th class="con_borde w60px">Pedido</th>
                                <th class="con_borde">Orden</th>
                                <th class="con_borde w100px">Participacion</th>
                                <th class="con_borde w100px">Entrega</th>
                                <th class="con_borde w100px">Calidad</th>
                                <th class="con_borde w100px">Almacen</th>
                                <th class="con_borde w100px">Total</th>
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
    <div class="modal" id="modalProcess">
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
                   
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/evaluacion.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>