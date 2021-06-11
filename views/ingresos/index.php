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
    <div class="modal" id="modalProcess">
        <form action="#" autocomplete="off" id="formProcess">
            <input type="hidden" name="id_ingreso" id="id_ingreso">
            <input type="hidden" name="id_entidad" id="id_entidad">
            <input type="hidden" name="cod_almacen" id="cod_almacen">
            <input type="hidden" name="cod_movimento" id="cod_movimento">
            <input type="hidden" name="cod_autoriza" id="cod_autoriza">
            <input type="hidden" name="cod_proyecto" id="cod_proyecto">
            <input type="hidden" name="cod_area" id="cod_area">
            <input type="hidden" name="cod_costos" id="cod_costos">
            <input type="hidden" name="order_file" id="order_file">
            <input type="hidden" name="cargo_almacen" id="cargo_almacen">
            <input type="hidden" name="idorden" id="idorden">
            <input type="hidden" name="idpedido" id="idpedido">
            <input type="hidden" name="estado" id="estado" value= "2">

            <div class="process">
                <div class="sides_process">
                        <div class="descrip_title">
                            <span>Datos Generales</span>
                            <div>
                                <button type="button" id="importOrd" title="Importar Orden">
                                    <i class="far fa-folder-open"></i> Importar Orden 
                                </button>
                                <button type="button" id="saveDoc" title="Grabar Registro">
                                    <span><i class="far fa-save"></i> Grabar Nota </span>
                                </button>
                                <button type="button" id="closeDoc" title="Cerrar Registro">
                                    <i class="fas fa-ban"></i> Cerrar Nota
                                </button>
                                <button type="button" id="cancelDoc" title="Cancelar Registro">
                                    <i class="fas fa-ban"></i> Cancelar Nota
                                </button>
                            </div>
                        </div>
                        <div class="process_header">
                            <div class="process_left">
                                <div class="input_process g4items">
                                    <label for="almacen" class="w100px">Almacen :</label>
                                    <input type="text" name="almacen" id="almacen" class="w300px pl10" placeholder="seleccione opcion">
                                    <div class="seleccion seleccion_pedido">
                                        <ul id="listaAlmacen">
                                            <?php echo $this->almacenes?>
                                        </ul>
                                    </div>
                                    <label for="nro_ingreso" class="w100px">Nro Ingreso :</label>
                                    <input type="text" name="nro_ingreso" id="nro_ingreso" class="w100px pl10" readonly>
                                </div>
                                <div class="input_process g4items">
                                    <label for="fechadoc" class="w100px">Fecha Doc. :</label>
                                    <input type="date" name="fechadoc" id="fechadoc" readonly value="<?php echo date("Y-m-d");?>" class="pl20">
                                    <label for="fechacont" class="w100px">Fec. Contable :</label>
                                    <input type="date" name="fechacont" id="fechacont" value="<?php echo date("Y-m-d");?>" class="pl20">
                                </div>
                                <div class="input_process g2items desplegable">
                                    <label for="proyecto" class="w100px">Proyecto :</label>
                                    <input type="text" name="proyecto" id="proyecto" class="pl20 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="area" class="w100px">Area/Of. :</label>
                                    <input type="text" name="area" id="area" class="pl20 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="costos" class="w100px">C.Costos. :</label>
                                    <input type="text" name="costos" id="costos" class="pl20 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="solicita" class="w100px">Solicita :</label>
                                    <input type="text" name="solicita" id="solicita" class="pl20 mayusculas" placeholder="seleccione opcion">
                                </div>
                                <div class="input_process g2items">
                                    <label for="aprueba" class="w100px">Aprueba :</label>
                                    <input type="text" name="aprueba" id="aprueba" class="pl20 mayusculas">
                                    <div class="seleccion seleccion_pedido">
                                        <ul id="listaAprueba">
                                            <?php echo $this->aprueba?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="process_right">
                                <div class="input_process g4items">
                                    <label for="tipomov" class="w100px">Tipo Mov :</label>
                                    <input type="text" name="tipomov" id="tipomov" class="w300px pl10" placeholder="seleccione opcion">
                                    <div class="seleccion seleccion_pedido">
                                        <ul id="listaMotivo">
                                            <?php echo $this->motivos?>
                                        </ul>
                                    </div>
                                    <label for="movalmacen" class="w100px">Mov.Almacén :</label>
                                    <input type="text" name="movalmacen" id="movalmacen" class="w100px pl10" readonly>
                                </div>
                                <div class="input_process g4items">
                                    <label for="nrord" class="w100px">Nro. Orden:</label>
                                    <input type="text" name="nrord" id="nrord" class="pl20">
                                    <label for="nroped" class="w100px">Nro. Pedido :</label>
                                    <input type="text" name="nroped" id="nroped" class="pl20">
                                </div>
                                <div class="input_process g4items">
                                    <label for="ruc" class="w100px">Nro. RUC:</label>
                                    <input type="text" name="nruc" id="nruc" class="pl20 ">
                                    <label for="nroguia" class="w150px">Nro. Guia Remisión:</label>
                                    <input type="text" name="nroguia" id="nroguia" class="pl20 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="entidad" class="w100px">Razón Social :</label>
                                    <input type="text" name="entidad" id="entidad" class="pl20 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="concepto" class="w100px">Concepto :</label>
                                    <textarea name="concepto" id="concepto" rows="2"></textarea>
                                </div>
                                <div class="input_process g2items">
                                    <label for="espec" class="w100px">Especificaciones </br> Técnicas :</label>
                                    <textarea name="espec" id="espec" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="process_estate">
                                <div class="input_process g2items">
                                    <label for="documento" class="w100px">Est.Doc.:</label>
                                    <input type="text" name="documento" id="documento" class="pl20 mayusculas proceso" readonly>
                                </div>
                                <div class="oculto">
                                    <label for="registro" class="w100px">Est.Registro:</label>
                                    <input type="text" name="registro" id="registro" class="pl20 mayusculas proceso" readonly>
                                </div>
                                <div class="input_process g2items">
                                    <label for="items" class="w100px">Nro. Items</label>
                                    <input type="text" name="items" id="items" class="pl20 mayusculas" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="descrip_title">
                            <span>Detalles</span>
                            <div>
                                <button type="button" id="docsAtach" title="Documentos Adjuntos/Estados">
                                    <i class="fas fa-envelope-open-text"></i> Documentos Adjuntos/Estados
                                </button>
                                <button type="button" id="orderDetail" title="Detalle Original de la Orden">
                                    <i class="fas fa-envelope-open-text"></i> Detalle Original de la Orden
                                </button>
                                <button type="button" id="preview" title="Vista Previa">
                                    <i class="fas fa-envelope-open-text"></i> Vista Previa
                                </button>
                            </div>
                        </div>
                        <div class="process_items">
                        <div>
                            <table class="con_borde w100p" id="detalle_ingreso">
                                <thead>
                                    <tr>
                                        <th class="con_borde w2p">...</th>
                                        <th class="con_borde w2p">...</th>
                                        <th class="con_borde w3p">Item</th>
                                        <th class="con_borde w10p">Codigo</th>
                                        <th class="con_borde w30p">Descripcion</th>
                                        <th class="con_borde w5p">Unidad</th>
                                        <th class="con_borde w5p">Cantidad </br> Pendiente</th>
                                        <th class="con_borde w5p">Cant. </br> a Ingresar</th>
                                        <th class="con_borde w10p">Estado </br> Bien</th>
                                        <th class="con_borde w10p">Ubicación </br> Física</th>
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
    <div class="modal zindex3" id="modalOrdenes">
        <div class="lists">
            <div class="barra_busqueda_interna">
                <div>
                    <label for="nroorden">Nro. Orden</label>
                    <input type="text" name="nroorden" id="nroorden">
                </div>
                <div>
                    <button>Buscar</button>
                    <button>Registrar Ingreso</button>
                </div>
            </div>
            <div class="lista">
                <table id="lista_ordenes" class="w100p con_borde tabla_lista" >
                    <thead>
                        <tr>
                            <th class="w8p">Nro.Orden</th>
                            <th>Proyecto</th>
                            <th>Centro de Costos</th>
                            <th>Area</th>
                            <th class="w8p">Fecha</th>
                            <th class="w5p">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <a href="#" id="closeModalOrders" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalSerie">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Registrar Series</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <div class="titulos">
                    <h4 id="descripProducto"></h4>
                    <h4 id="codigoProducto"></h4>
                    <h4 id="nroItemSerial" class="noculto"></h4>
                    <a href="#" id="addSerials"><i class="far fa-calendar-plus"></i></a>
                </div>
                <form action="">
                    <div>
                        <table id="detalle_series" class="w100p con_border espacio_tabla_0 ">
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
    <div class="modal zindex3" id="modalOrderDetail">
        <div class="insidePreview">
            <object data="" type="application/pdf"></object>
        </div>
        <a href="#" id="closeModalOrderDetail" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalAtach">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Adjuntar Archivos</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <div class="titulos">
                    <h3>Seleccionar Archivos</h3>
                    <a href="#" id="pickFiles"><i class="far fa-calendar-plus"></i></a>
                </div>
                <form action="<?php echo constant('URL');?>ingresos/uploadsAtachs" id="fileAtachs" enctype='multipart/form-data'>
                    <input type="file" name="uploadAtach[]" id="uploadAtach" multiple class="oculto">
                    <div>
                        <table id="tableAdjuntos" class="w100p con_border espacio_tabla_0 ">
                            <thead>
                                <tr>
                                    <th class="con_borde">Nombre</th>
                                    <th class="con_borde">Tamaño</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="options">
                        <button id="btnConfirmAtach" class="botones" type="button">Aceptar</button>
                        <button id="btnCancelAtach" class="botones" type="button">Cancelar</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
    <div class="modal zindex3" id="modalPreview">
        <div class="insidePreview">
            <!-- <object data="" type="application/pdf" id="dialogPreview"></object> -->
            <iframe src=""></iframe>
        </div>
        <a href="#" id="closeModalPreview" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex5" id="waitmodal">
        <div class="loader"></div>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Ingresos Almacen</p>
                <?php require 'views/menusimple.php'; ?>
            </div>       
            <div class="formulario">
                <div class="banner">
                    <div>
                        <label for="tipo">Almacen</label>
                        <select name="almacen" id="almacen">
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
                        <label for="anio">Año</label>
                        <input type="number" name="anio" id="anio" min="2020" max="3000" value="<?php echo date("Y")?>">
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
                        <button type="button">Procesar</button>
                    </div>
                </div>
                <div class="pedidos">
                    <table class="w100p con_borde" id="tabla_ingresos">
                        <thead>
                            <tr>
                                <th class="con_borde w55px">Num. Nota</th>
                                <th class="con_borde w100px">Fecha </br> Registro</th>
                                <th class="con_borde w60px">Registo </br> Mov.Alm</th>
                                <th class="con_borde">Almacen</th>
                                <th class="con_borde">Descripcion/Detalle </br> Proyecto - Oficina</th>
                                <th class="con_borde w60px">Año Doc.</th>
                                <th class="con_borde w60px">Nro. Orden</th>
                                <th class="con_borde w100px">Nro. Pedido</th>
                                <th class="con_borde w80px">Guia </br> Remisión</th>
                                <th class="con_borde w150px">Observaciones</th>
                                <th class="con_borde w60px">Estado </br> Documento</th>
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
    <div class="modal" id="dialogConfirm">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Pregunta</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h1>No ha grabado.. ¿Descartar los cambios? </h1> <!--alt 168-->   
                <div class="options">
                    <button id="btnYes" class="botones">Si</button>
                    <button id="btnNo" class="botones">No</button>
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