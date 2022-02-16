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
            <div class="process">
                <div class="sides_process w85p">
                <div class="descrip_title">
                    <span>Datos Generales</span>
                    <div>
                        <button type="button" id="saveItem" title="Grabar Pedido" class="oculto">
                            <span><i class="far fa-save"></i> Grabar Cotizacion</span> 
                        </button>
                        <button type="button" id="cancelItem" title="Cancelar Pedido">
                            <i class="fas fa-ban"></i> Cancelar Cotización
                        </button>
                        <button type="button" id="closeItem" title="Enviar Pedido">
                            <i class="fas fa-door-closed"></i> Cerrar Cotización
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
                            <div class="oculto">
                                <label for="usuario" class="w100px">Usuario :</label>
                                <input type="text" name="usuario" id="usuario" class="pl20 mayusculas desactivado">
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
                                <textarea name="concepto" id="concepto" rows="4" class="desactivado"></textarea>
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
                                <label for="documento" class="w100px">Vencimiento:</label>
                                <input type="date" name="vence" id="vence" class="pl20" readonly>
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
                            <button type="button" id="checkItems" title="Marcar todos">
                                <i class="fas fa-check-double"></i> Marcar Todos
                            </button>
                            <button type="button" id="selectDist" title="Seleccionar Proveedores">
                                <i class="fas fa-envelope-open-text"></i> Solicitar Cotizaciones
                            </button>
                            
                        </div>
                    </div>
                    <div class="process_items">
                        <div>
                            <table class="con_borde w100p" id="detalle_pedido">
                                <thead>
                                    <tr>
                                        <th class="con_borde w3p">...</th>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w8p">Codigo</th>
                                        <th class="con_borde w35p">Descripcion</th>
                                        <th class="con_borde w5p">UM</th>
                                        <th class="con_borde w5p">Cantidad</th>
                                        <th class="con_borde w8p">Nro. Parte</th>
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
    <div class="modal zindex3" id="dialogSend">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Solicitar Cotizacion</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h4>Items seleccionados : <span id="num_items"></span></h4>
                <div class="emails">
                    <table class="w100p con_borde table_dialog" id="listMailToSend">
                        <thead>
                            <tr>
                                <th class="con_boder">Lista de Correos</th>
                                <th class="con_boder"><a href="" id="callEmailList"><i class="far fa-address-book"></i></a></th>
                                <th class="oculto"></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <br>
                    <textarea name="mail_mssg" id="mail_mssg" class="w100p" rows="7" placeholder="Escribir Mensaje">
Estimado:
Por favor sirvase cotizar en el siguiente, según el dellate en el siguiente enlace.
Atte.
                    </textarea>
                </div>
                <div class="options">
                    <button id="btnSendConfirm" class="botones">Aceptar</button>
                    <button id="btnSendCancel" class="botones">Cancelar</button>
                </div>
            </div>
        </div>
        <div class="listEmails">
            <div class="titulo">
                <h3>Lista de Correos</h3>
                <button type="button" id="closeEmails"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="cuerpo">
                <form action="#" id="formEmails">
                    <div class="enterData">
                        <input type="text" name="searchEmailText" id="searchEmailText" placeholder="buscar">
                    </div>
                    <table id="corporativos" class="con_borde w100p espacio_tabla_0 mt10px">
                        <thead>
                            <tr>
                                <th class="con_borde">Nombre</th>
                                <th class="con_borde">Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="dialogConfirm">
        <div class="dialogContainer">
            <div class="dialogTitle">
                <h4>Pregunta</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h1>¿Pasar el pedido a Estudio de mercado? </h1> <!--alt 168-->   
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
                <p class="workTitle">Cotizaciones</p>
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
                                <p>Aprobados</p>
                                <!-- <p><?php echo $total?></p> -->
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-barcode"></i>
                            <div>
                                <p>Por cotizar</p>
                                <!-- <p><?php echo $activos?></p> -->
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-toolbox"></i>
                            <div>
                                <p>Cotizados</p>
                                <!-- <p><?php echo $inactivos?></p> -->
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/cotizacion.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>