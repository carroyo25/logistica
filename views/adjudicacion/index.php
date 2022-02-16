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
    <div class="modal zindex5" id="waitmodal">
        <div class="loader"></div>
    </div>
    <div class="mensaje msj_error">
        <span></span>
    </div>
    <div class="modal" id="modalProcess">
        <form action="#" autocomplete="off" id="formProcess">
            <input type="hidden" name="cod_pedido" id="cod_pedido">
            <input type="hidden" name="cod_proy" id="cod_proy">
            <input type="hidden" name="cod_cost" id="cod_cost">
            <input type="hidden" name="cod_area" id="cod_area">
            <input type="hidden" name="cod_transporte" id="cod_transporte">
            <input type="hidden" name="cod_estdoc" id="cod_estdoc" value="2">
            <input type="hidden" name="cod_registro" id="cod_registro" value="2">
            <input type="hidden" name="cod_solicitante" id="cod_solicitante">
            <input type="hidden" name="cod_tipo" id="cod_tipo">
            <input type="hidden" name="estado" id="estado" value="2">
            <input type="hidden" name="atencion" id="atencion" value="3">
            <div class="process ">
                <div class="sides_process w85p">
                <div class="descrip_title">
                    <span>Datos Generales</span>
                    <div>
                        <button type="button" id="saveItem" title="Grabar Adjudicacion">
                            <span><i class="far fa-save"></i> Grabar Adjudicación</span> 
                        </button>
                        <button type="button" id="cancelItem" title="Cancelar Pedido">
                            <i class="fas fa-ban"></i> Cancelar Adjudicación
                        </button>
                        <button type="button" id="closeItem" title="Enviar Pedido">
                            <i class="fas fa-door-closed"></i> Enviar a verificación
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
                            
                            <div class="input_process g2items desplegable">
                                <label for="proyecto" class="w100px">Proyecto :</label>
                                <input type="text" name="proyecto" id="proyecto" class="pl20 mayusculas desactivado">
                            </div>
                            <div class="input_process g2items">
                                <label for="area" class="w100px">Area/Of. :</label>
                                <input type="text" name="area" id="area" class="pl20 mayusculas desactivado">
                            </div>
                            <div class="input_process g2items">
                                <label for="area" class="w100px">C.Costos. :</label>
                                <input type="text" name="costos" id="costos" class="pl20 mayusculas desactivado">
                            </div>
                        </div>
                        <div class="process_right">
                            <div class="input_process g2items">
                                <label for="transporte" class="w100px">Transporte :</label>
                                <input type="text" name="transporte" id="transporte" class="pl20 mayusculas desactivado">
                            </div>
                            <div class="input_process g2items">
                                <label for="concepto" class="w100px">Concepto :</label>
                                <textarea name="concepto" id="concepto" rows="3" class="desactivado"></textarea>
                            </div>
                            <div class="input_process g2items">
                                <label for="solicitante" class="w100px">Solicitante</label>
                                <input type="text" name="solicitante" id="solicitante" class="pl20 mayusculas desactivado">
                            </div>
                        </div>
                        <div class="process_estate">
                            <div class="input_process g2items">
                                <label for="registro" class="w100px">Est.Doc.:</label>
                                <input type="text" name="registro" id="registro" class="pl20 mayusculas" readonly>
                            </div>
                            <div class="input_process g2items">
                                <label for="tipo" class="w100px">Tipo</label>
                                <input type="text" name="tipo" id="tipo" class="pl20 mayusculas">
                            </div>
                        </div>
                    </div>
                    <div class="descrip_title">
                        <span>Especificaciones Técnicas o Descripción del Item</span>
                    </div>
                    <div class="details_item">
                        <textarea name="espec_items" id="espec_items" rows="1" class="w100p desactivado"></textarea>
                    </div>
                    <div class="descrip_title">
                        <span>Detalles</span>
                        <div>
                            <button type="button" id="verProforma" title="Ver Proforma">
                                <i class="fas fa-check-double"></i> Ver Proforma
                            </button>
                        </div>
                    </div>
                    <div class="process_items">
                        <div>
                            <table id="detalle_pedido" class="w100p">

                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
        <a href="#" id="closeModalProcess" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalItems">
        <div class="dialogMany">
            <div>
                <form method="POST" id="formGenDoc">
                    <input type="hidden" name="id_prov" id="id_prov">
                    <input type="hidden" name="id_mon" id="id_mon">
                    <input type="hidden" name="id_alma" id="id_alma">
                    <input type="hidden" name="id_entrega" id="id_entrega">
                    <input type="hidden" name="id_pago" id="id_pago">
                    <input type="hidden" name="retencion" id="retencion">

                    <h3 id="rqTitle">Orden de Compra</h3>
                    <hr>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Fecha</label>
                        <input type="date" id="fecharq" name="fecharq" class="w50p" value="<?php echo date("Y-m-d");?>" readonly>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Proveedor</label>
                        <input type="text" id="provrq" name="provrq" readonly>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Direccion</label>
                        <input type="text" id="dirrq" name="dirrq" readonly>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">RUC</label>
                        <input type="text" id="rucrq" name="rucrq" class="w50p" readonly>
                    </div>
                    <div class="input_model g2itemsSeparate desplegable">
                        <label for="">Moneda *</label>
                        <input type="text" id="monrq" name="monrq" class="w50p">
                        <div class="seleccion seleccion_pedido">
                            <ul id="listaMonedas">
                                <?php echo $this->monedas ?>
                            </ul>
                        </div>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Importe</label>
                        <input type="text" id="importerq" name="importerq" class="w50p drch pr10" readonly>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Fecha de entrega</label>
                        <input type="date" id="fentrega" name="fentrega" class="w30p">
                    </div>
                    <div class="input_model g2itemsSeparate desplegable">
                        <label for="">Plazo de Entrega *</label>
                        <input type="text" id="entregarq" name="entregarq" class="w50p drch pr10">
                        <div class="seleccion seleccion_pedido">
                            <ul id="listaEntrega">
                                <?php echo $this->entrega ?>
                            </ul>
                        </div>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Lugar Entrega *</label>
                        <input type="text" id="lEntrega" name="lEntrega" class="pl10 mayusculas">
                        <div class="seleccion seleccion_pedido">
                            <ul id="lugarEntrega">
                                <?php echo $this->lugar?>
                            </ul>
                        </div>
                    </div>
                    <div class="input_model g2itemsSeparate desplegable">
                        <label for="">Condicion de pago</label>
                        <input type="text" id="pagorq" name="pagorq" class="w50p">
                        <div class="seleccion seleccion_pedido">
                            <ul id="listaPago">
                                <?php echo $this->pagos ?>
                            </ul>
                        </div>
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Nro. Cotización</label>
                        <input type="text" id="cotrq" name="cotrq" class="w50p drch pr10">
                    </div>
                    <div class="input_model g2itemsSeparate">
                        <label for="">Observaciones</label>
                        <textarea name="obsrq" id="obsrq" rows="1"></textarea>
                    </div>
                    <hr>
                    <div class="dialogManyButtons">
                        <button class="button_dialog" type="button" id="btnItemAcept"><i class="fas fa-clipboard-check"></i> Aceptar</button>
                        <button class="button_dialog" type="button" id="btnItemCancel"><i class="fas fa-ban"></i> Anular</button>
                        <button class="button_dialog" type="button" id="btnItemAtach"><i class="fas fa-paperclip"></i></i> Adjuntar</button>
                        <button class="button_dialog" type="button" id="btnItemPreview"><i class="fas fa-eye"></i> Vista Previa</button>
                    </div>
                </form>
            </div>
        </div>
        <a href="#" class="buttonClose" id="closeModalItems"><i class="fas fa-reply-all"></i></a>    
    </div>
    <div class="modal zindex3" id="modalPreviewCot">
        <div class="insidePreview">
            <div class="atachListDiv">
                <ul id="atachList">   
                    
                </ul>
            </div>
            <div class="preview" id="previewAttach">
                <object data="" type="application/pdf"></object>
            </div>
        </div>
        <a href="#" id="closeViewAtach" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalPreviewMan">
        <div class="insidePreview">
            <iframe src=""></iframe>
        </div>
        <a href="#" id="closeModalPreview" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal zindex3" id="modalAtach">
        <div class="dialogContainer w50p">
            <div class="dialogTitle">
                <h4>Adjuntar Archivos</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <div class="titulos">
                    <h3>Seleccionar Archivos</h3>
                    <a href="#" id="pickFiles"><i class="far fa-calendar-plus"></i></a>
                </div>
                <form action="<?php echo constant('URL');?>pedidos/uploadsAtachs" id="fileAtachs" enctype='multipart/form-data'>
                    <input type="file" name="uploadAtach[]" id="uploadAtach" multiple class="oculto">
                    <div>
                        <table id="tableAdjuntos" class="w100p con_border espacio_tabla_0">
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
    <div class="modal zindex3" id="modalAtachItems">
        <div class="dialogContainer w50p">
            <div class="dialogTitle">
                <h4>Adjuntar Archivos</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <div class="titulos">
                    <h3>Seleccionar Archivos</h3>
                </div>
                <form action="<?php echo constant('URL');?>adjudicacion/uploadsAtachsItems" id="fileAtachsItems" enctype='multipart/form-data'>
                    <input type="file" name="file1" id="file1" class="oculto manual">
                    <input type="file" name="file2" id="file2" class="oculto manual">
                    <input type="file" name="file3" id="file3" class="oculto manual">
                    <input type="file" name="file4" id="file4" class="oculto manual">
                    <input type="file" name="file5" id="file5" class="oculto manual">
                    <input type="hidden" name="fileProdId" id="fileProdId">
                    <input type="hidden" name="fileDetId" id="fileDetId"> 
                    <div>
                        <table id="tableAdjuntosItems" class="w100p con_border espacio_tabla_0 ">
                            <thead>
                                <tr>
                                    <th class="con_borde">Descripcion</th>
                                    <th class="con_borde w40p">Archivo</th>
                                    <th class="con_borde">Tamaño</th>
                                    <th class="con_borde">...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="con_borde pl20">SDS</td>
                                    <td class="con_borde pl20 "><span></span></td>
                                    <td class="con_borde drch pr10"></td>
                                    <td class="con_borde centro"><a href="#" data-file="file1" id="file1"><i class="far fa-calendar-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td class="con_borde pl20">CERTIFICADO DE CALIDAD</td>
                                    <td class="con_borde pl20"><span></span></td>
                                    <td class="con_borde drch pr10"></td>
                                    <td class="con_borde centro"><a href="#" data-file="file2" id="file2"><i class="far fa-calendar-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td class="con_borde pl20">HOJA DE RESUMEN</td>
                                    <td class="con_borde pl20"><span></span></td>
                                    <td class="con_borde drch pr10"></td>
                                    <td class="con_borde centro"><a href="#" data-file="file3" id="file3"><i class="far fa-calendar-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td class="con_borde pl20">CERTIFICADO DE CALIBRACION</td>
                                    <td class="con_borde pl20"><span></span></td>
                                    <td class="con_borde drch pr10"></td>
                                    <td class="con_borde centro"><a href="#" data-file="file4" id="file4"><i class="far fa-calendar-plus"></i></a></td>
                                </tr>
                                <tr>
                                    <td class="con_borde pl20">PROTOCOLO DE PRUEBA</td>
                                    <td class="con_borde pl20"><span></span></td>
                                    <td class="con_borde drch pr10"></td>
                                    <td class="con_borde centro"><a href="#" data-file="file5" id="file5"><i class="far fa-calendar-plus"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="options">
                        <button id="btnConfirmAtachItems" class="botones" type="submit">Aceptar</button>
                        <button id="btnCancelAtachItems" class="botones" type="reset">Cancelar</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Estudio de Mercado</p>
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
                                <th class="con_borde">Motivo</th>
                                <th class="con_borde">Area</th>
                                <th class="con_borde">Proyecto/Sede</th>
                                <th class="con_borde">Responsable</th>
                                <th class="con_borde">Aprobado</th>
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
    <script src="<?php echo constant('URL');?>public/js/adjudicacion.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>