<!DOCTYPE html>
<html lang="en">
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
    <div class="modal zindex3" id="modalOrderDetail">
        <div class="insidePreview">
            <iframe src=""></iframe>
        </div>
        <a href="#" id="closeModalOrderDetail" class="buttonClose"><i class="fas fa-reply-all"></i></a>
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
    <script src="<?php echo constant('URL');?>public/js/ingresos.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>