<?php 
    date_default_timezone_set('America/Lima'); 
?>
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
    <div class="modal zindex3" id="dialogSend">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Enviar Pedido</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <div class="emails">
                    <table class="w100p con_borde table_dialog" id="listMailToSend">
                        <thead>
                            <tr>
                                <th class="con_boder">Lista de Correos</th>
                                <th class="con_boder"><a href="" id="callEmailList"><i class="far fa-address-book"></i></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <br>
                    <textarea name="mail_mssg" id="mail_mssg" class="w100p" rows="7" placeholder="Escribir Mensaje"></textarea>
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
    <div class="modal zindex3" id="modalItems">
        <div class="search">
            <div>
                <div id="titleSearch">
                    <h3>Seleccionar Items : </h3>
                </div>
                <div class="divSearch">
                    <i class="fas fa-search"></i>
                    <input type="search" id="inputSearchItems">
                </div>
                <div class="listData">
                    <table id="tableItems">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Nro. Parte</th>
                                <th>Unidad</th>
                                <th class="oculto">Factor</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <ul>
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="addItemWindow">
            <div class="titulo">
                <h3>Agregar Item</h3>
                <button type="button" id="closeNew"><i class="far fa-times-circle"></i></button>
            </div>
            <div class="cuerpo">
                <form action="#" id="addItemForm">
                    <input type="hidden" name="itemTipo" id="itemTipo">
                    <input type="hidden" name="cod_unidad" id="cod_unidad">
                    <h3>Codificación Asignada</h3>
                    
                    <div class="enterData desplegable">
                        <label for="Clase">Grupo</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="grupo" id="grupo">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaGrupos"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="clase">Clase</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="clase" id="clase">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaClases"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="familia">Familia</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="familia" id="familia">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaFamilias"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="codigo_item">Codigo</label>
                        <input type="text" name="codigo_item" id="codigo_item" readonly>
                    </div>
                    <h3>Descripcion Item</h3>
                    <div class="enterData">
                        <label for="descrip">* Descripcion :</label>
                        <input type="text" name="descrip" id="descrip" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="nom_com">* Nombre Comercial :</label>
                        <input type="text" name="nom_com" id="nom_com" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="nom_cor">Nombre Corto :</label>
                        <input type="text" name="nom_cor" id="nom_cor" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="unidad">* Unidad :</label>
                        <img src="<?php echo constant('URL');?>public/img/ajax-loader.gif" id="img_wait_lista">
                        <input type="text" name="unidad" id="unidad" class="mayusculas p20l">
                        <div class="seleccion seleccion_lista">
                            <ul id="listaUnidades"></ul>
                        </div>
                    </div>
                    <div class="enterData">
                        <label for="detalles">* Detalles :</label>
                        <textarea name="detalles" id="detalles" rows="4"></textarea>
                    </div>
                    <div class="enterData">
                        <label for="marca">Marca :</label>
                        <input type="text" name="marca" id="marca" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="modelo">Modelo :</label>
                        <input type="text" name="modelo" id="modelo" class="mayusculas p20l">
                    </div>
                    <div class="enterData">
                        <label for="nro_parte">Nro. Parte :</label>
                        <input type="text" name="nro_parte" id="nro_parte" class="mayusculas p20l">
                    </div>
                    <div class="opciones">
                        <button type="submit" id="submitItem">Registrar</button>
                        <button type="reset" id="resetItem">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        <a href="#" class="buttonClose" id="closeModalItems"><i class="fas fa-reply-all"></i></a>    
    </div>
    <div class="modal zindex3" id="modalImport">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Importar</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h3>Seleccionar Archivo (max 100 items) </h3>
                <form action="#" id="importForm">
                    <input type="hidden" id="operacion" name="operacion">
                    <input type="file" name="uploadfile" id="uploadfile">
                    <div class="options">
                        <button id="btnConfirmImport" class="botones" type="submit">Aceptar</button>
                        <button id="btnCancelImport" class="botones" type="reset">Cancelar</button>
                    </div>
                </form>   
            </div>
        </div>
    </div>
    <div class="modal zindex3" id="modalPreview">
        <div class="insidePreview">
            <object data="" type="application/pdf"></object>
        </div>
        <a href="#" id="closeModalPreview" class="buttonClose"><i class="fas fa-reply-all"></i></a>
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
                <form action="<?php echo constant('URL');?>pedidos/uploadsAtachs" id="fileAtachs" enctype='multipart/form-data'>
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
                <div class="sides_process">
                <div class="descrip_title">
                    <span>Datos Generales</span>
                    <div>
                        <button type="button" id="saveItem" title="Grabar Pedido">
                            <span><i class="far fa-save"></i> Grabar Pedido</span> 
                        </button>
                        <button type="button" id="cancelItem" title="Cancelar Pedido">
                            <i class="fas fa-ban"></i> Cancelar Pedido
                        </button>
                        <button type="button" id="upAttach" title="Importar Adjuntos">
                            <i class="fas fa-upload"></i> Adjuntar Archivos
                        </button>
                        <button type="button" id="preview" title="Vista Previa">
                            <i class="fab fa-wpexplorer"></i> Vista Previa
                        </button>
                        <button type="button" id="sendItem" title="Enviar Pedido">
                            <i class="far fa-paper-plane"></i> Enviar Almacen
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
                        <div>
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
                                        <th class="con_borde w5p">...</th>
                                        <th class="con_borde w5p">...</th>
                                        <th class="con_borde w5p">Item</th>
                                        <th class="con_borde w8p">Codigo</th>
                                        <th class="con_borde w35p">Descripcion</th>
                                        <th class="con_borde w5p">UM</th>
                                        <th class="con_borde w5p">Cant. Pedida</th>
                                        <th class="con_borde w8p">Nro. Orden</th>
                                        <th class="con_borde w8p">Nro. Parte</th>
                                        <th class="con_borde w5p">Verificar</br>Calidad</th>
                                        <th class="con_borde w8p">Tipo</th>
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
    <div class="modal zindex5" id="waitmodal">
        <div class="loader"></div>
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
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Registro de Pedidos</p>
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
    <script src="<?php echo constant('URL');?>public/js/pedidos.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>