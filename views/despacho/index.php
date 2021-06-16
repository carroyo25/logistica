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
    <div class="modal zindex3" id="modalNotas">
        <div class="lists">
            <div class="barra_busqueda_interna">
                <div>
                    <label for="nroorden">Nro. Ingreso</label>
                    <input type="text" name="buscarIngreso" id="buscarIngreso">
                </div>
            </div>
            <div class="lista">
                <table id="listaIngresos" class="w100p con_borde tabla_lista" >
                    <thead>
                        <tr>
                            <th class="w8p">Nro.Ingreso</th>
                            <th>Proyecto</th>
                            <th class="w8p">Fecha</th>
                            <th class="w5p">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <a href="#" id="closeModalNotas" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal" id="modalProcess">
        <form action="#" autocomplete="off" id="formProcess">
            <input type="hidden" name="id_ingreso" id="id_ingreso">
            <input type="hidden" name="id_salid" id="id_salid">
            <input type="hidden" name="id_entidad" id="id_entidad">
            <input type="hidden" name="cod_almacen" id="cod_almacen">
            <input type="hidden" name="cod_movimiento" id="cod_movimiento">
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
                                <button type="button" id="importarIngreso" title="Importar Orden">
                                    <i class="far fa-folder-open"></i> Importar Ingreso 
                                </button>
                                <button type="button" id="grabarDoc" title="Grabar Registro">
                                    <span><i class="far fa-save"></i> Grabar Salida </span>
                                </button>
                                <button type="button" id="cerrarDoc" title="Cerrar Registro">
                                    <i class="fas fa-ban"></i> Cerrar Salida
                                </button>
                                <button type="button" id="cancelarDoc" title="Cancelar Registro">
                                    <i class="fas fa-ban"></i> Cancelar Salida
                                </button>
                            </div>
                        </div>
                        <div class="process_header">
                            <div class="process_left">
                                <div class="input_process g4items">
                                    <label for="nrosalida" class="w100px">Nro.Salida :</label>
                                    <input type="text" name="nrosalida" id="nrosalida" class="pl10" readonly>
                                    <label for="movalma" class="w100px">Mod.Almacen:</label>
                                    <input type="text" name="movalma" id="movalma" class="pl10" readonly>
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
                                    <label for="solicita" class="w100px">Solicita :</label>
                                    <input type="text" name="solicita" id="solicita" class="pl20 mayusculas">
                                </div>
                                <div class="input_process g2items">
                                    <label for="aprueba" class="w100px">Aprueba :</label>
                                    <input type="text" name="aprueba" id="aprueba" class="pl20 mayusculas">
                                </div>
                            </div>
                            <div class="process_right">
                                <div class="input_process g2items">
                                    <label for="almacen" class="w100px">Almacen :</label>
                                    <input type="text" name="almacen" id="almacen" class="pl10"">
                                </div>
                                <div class="input_process g2items">
                                    <label for="tipomov" class="w100px">Tipo Mov :</label>
                                    <input type="text" name="tipomov" id="tipomov" class="pl10"">
                                    <div class="seleccion seleccion_pedido">
                                        <ul id="listaMotivo">
                                            <?php echo $this->motivos?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="input_process g4items">
                                    <label for="nroped" class="w100px">Nro. Pedido</label>
                                    <input type="text" name="nroped" id="nroped" class="pl20">
                                    <label for="fecped" class="w100px">Fecha Doc.:</label>
                                    <input type="date" name="fecped" id="fecped" class="pl20">
                                </div>
                                <div class="input_process g4items">
                                    <label for="nrord" class="w100px">Nro. OC:</label>
                                    <input type="text" name="nrord" id="nrord" class="pl20 ">
                                    <label for="fecord" class="w100px">Fecha Doc.:</label>
                                    <input type="date" name="fecord" id="fecord" class="pl20">
                                </div>
                                <div class="input_process g2items">
                                    <label for="espec" class="w100px" >Observaciones:</label>
                                    <textarea name="espec" id="espec" rows="2" readonly></textarea>
                                </div>
                            </div>
                            <div class="process_estate">
                                <div class="input_process g2items">
                                    <label for="documento" class="w100px">Est.Doc.:</label>
                                    <input type="text" name="documento" id="documento" class="pl20 mayusculas proceso" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="descrip_title">
                            <span>Detalles</span>
                            <div>
                                <button type="button" id="docsAtach" title="Documentos Adjuntos">
                                    <i class="fas fa-envelope-open-text"></i> Documentos Adjuntos
                                </button>
                                <button type="button" id="orderDetail" title="Orden de compra asociada">
                                    <i class="fas fa-envelope-open-text"></i> Orden de compra asociada
                                </button>
                                <button type="button" id="guiaDetail" title="Guia de Remision - Envio y transporte">
                                    <i class="fas fa-envelope-open-text"></i> Guia de Remision - Envio y transporte
                                </button>
                                <button type="button" id="preview" title="Vista Previa R SALIDA">
                                    <i class="fas fa-envelope-open-text"></i> Vista Previa R SALIDA
                                </button>
                                <button type="button" id="previewGuia" title="Vista Previa GUIA REMISION">
                                    <i class="fas fa-envelope-open-text"></i> Vista Previa GUIA REMISION
                                </button>
                            </div>
                        </div>
                        <div class="process_items">
                        <div>
                            <table class="con_borde w100p" id="detalle_despacho">
                                <thead>
                                    <tr>
                                        <th class="con_borde w2p">...</th>
                                        <th class="con_borde w3p">Item</th>
                                        <th class="con_borde w10p">Codigo</th>
                                        <th class="con_borde w30p">Descripcion</th>
                                        <th class="con_borde w5p">Unidad</th>
                                        <th class="con_borde w5p">Cantidad </br> Requerida</th>
                                        <th class="con_borde w5p">Cant. a</br>Despachar</th>
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
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workOneForm">
            <div class="tilewindow">
                <p class="workTitle">Despacho de Ordenes</p>
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
                    <table class="w100p con_borde" id="tabla_guias">
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
    <div class="modal zindex5" id="waitmodal">
        <div class="loader"></div>
    </div>
    <div class="modal zindex3" id="modalGuia">
        <div class="insideWindow w60p">
            <h3>Guia de Remisión y despacho</h3>
            <hr>
            <form action="" id="formguia">
                <input type="hidden" name="codmodalidadguia" id="codmodalidadguia">
                <input type="hidden" name="codtipoguia" id="codtipoguia">
                <input type="hidden" name="codalmacendestino" id="codalmacendestino">
                <input type="hidden" name="codautoriza" id="codautoriza">
                <input type="hidden" name="coddespacha" id="coddespacha">
                <input type="hidden" name="coddestinatario" id="coddestinatario">

                <div class="gridMain g2items50">
                    <div class="ladoIzquierdo">
                        <div class="mb5px">
                            <label class="mr4px">Numero Guia</label>
                            <input type="text" name="serieguia" id="serieguia" class="w10p">
                            <input type="text" name="nroguia" id="nroguia" class="w20p">
                            <label for="packinlist">Packing List/Guia Madre</label>
                            <input type="text" id="packinlist" name="packinlist" class="w23p">
                        </div>
                        <div class="mb5px">
                            <label for="fecemin" class="mr5px">Fecha Emisión</label>
                            <input type="date" name="fecemin" id="fecemin">
                            <label for="feenttrans" class="mr5px">Fecha Ent.Transpor.</label>
                            <input type="date" name="feenttrans" id="feenttrans">
                        </div>
                        <div>
                            <h4>Datos del destinatario</h4>
                            <div class="containData mb5px">
                                <label for="ruc" class="w15p">R.U.C.</label>
                                <input type="text" name="ruc" id="ruc" value="20504898173" readonly class="w80p mb5px">
                                <label for="razondest" class="w15p">Razón Social</label>
                                <input type="text" name="razondest" id="razondest" value="SERVICIOS PETROLEROS Y CONSTRUCCIONES SEPCON S.A.C" readonly class="w80p mb5px">
                                <label for="direccdest" class="w15p ">Dirección</label>
                                <input type="text" name="direccdest" id="direccdest" value="AV. SAN BORJA NORTE N° 445 - SAN BORJA-LIMA-PERU." class="w80p mb5px">
                            </div>
                        </div>
                        <div>
                            <h4>Domicilio de partida</h4>
                            <div  class="containData">
                                <label for="almorg" class="w15p">Alm.Origen</label>
                                <input type="text" name="almorg" id="almorg" class="w80p mb5px">
                            </div>
                                <div class="ml30px">
                                    <div>
                                        <label for="viatiporg" class="w15p">Via Tipo</label>
                                        <input type="text" name="viatiporg" id="viatiporg" class="mb5px w30p">
                                        <label for="vianomorg" class="w15p">Via Nombre</label>
                                        <input type="text" name="vianomorg" id="vianomorg" class="mb5px w30p">
                                    </div>
                                    <div>
                                        <label for="nroorg" class="w15p">N°</label>
                                        <input type="text" name="nroorg" id="nroorg" class="w20p mb5px">
                                        <label for="intorg" class="w10p">Interior</label>
                                        <input type="text" name="intorg" id="intorg" class="w20p mb5px">
                                        <label for="zonaorg">Zona</label>
                                        <input type="text" name="zonaorg" id="zonaorg" class="w18p mb5px">
                                    </div>
                                    <div>
                                        <label for="deporg" class="w15p">Dep.</label>
                                        <input type="text" name="deporg" id="deporg" class="mb5px w30p">
                                        <label for="distorg" class="w15p">Distrito</label>
                                        <input type="text" name="distorg" id="distorg" class="mb5px w30p">
                                    </div>
                                    <div>
                                        <label for="provorg" class="w15p">Prov.</label>
                                        <input type="text" name="provorg" id="provorg" class="mb5px w30p">
                                        <label for="ubigorg" class="w15p">Ubigeo.</label>
                                        <input type="text" name="ubigorg" id="ubigorg" class="mb5px w30p">
                                    </div>
                                </div>
                        </div>
                        <div>
                            <h4>Motivo</h4>
                            <div class="p_relative">
                                <label for="mottras" class="w25p">Motivo Traslado</label>
                                <input type="text" name="mottrans" id="mottrans" class="w70p mb5px">
                            </div>
                            <div class="p_relative">
                                <label for="modtras" class="w25p">Modalidad Traslado</label>
                                <input type="text" name="modtras" id="modtras" class="w70p mb5px">
                                <div class="seleccion seleccion_pedido ml30px">
                                    <ul id="listaModalidadGuia">
                                        <?php echo $this->modalidad?>
                                    </ul>
                                </div>
                            </div>
                            <div class="p_relative">
                                <label for="tenvio" class="w25p">Tipo Envio</label>
                                <input type="text" name="tenvio" id="tenvio" class="w70p mb5px">
                                <div class="seleccion seleccion_pedido ml30px">
                                    <ul id="listaTipoGuia">
                                        <?php echo $this->tipoenvio?>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <label for="bultos" class="w25p">N° Bultos/Palets</label>
                                <input type="number" name="bultos" id="bultos" class="w25p mb5px drch pr20">
                                <label for="peso">Peso Bruto Total</label>
                                <input type="number" name="peso" id="peso" class="w25p mb5px drch pr20">
                                <label>Kg.</label>
                            </div>
                            <div>
                                <label for="observaciones" class="w25p">Observaciones</label>
                                <input type="text" name="observaciones" id="observaciones" class="w70p mb5px">
                            </div>
                        </div>
                    </div>
                    <div class="ladoDerecho">
                        <div class="containData g2items17">
                            <div class="p_relative">
                                <label for="autoriza" class="w15p">Autoriza</label>
                                <input type="text" id="autoriza" name="autoriza" class="w75p mb5px">
                                <div class="seleccion seleccion_pedido ml-23px">
                                    <ul id="listaAutoriza">
                                        <?php echo $this->personal?>
                                    </ul>
                                </div>
                            </div>
                            <div class="p_relative">
                                <label for="despacha" class="w15p">Despacha</label>
                                <input type="text" id="despacha" name="despacha" class="w75p mb5px">
                                <div class="seleccion seleccion_pedido ml-23px">
                                    <ul id="listaDespacha">
                                        <?php echo $this->personal?>
                                    </ul>
                                </div>
                            </div>
                            <div class="p_relative">
                                <label for="destinatario" class="w15p">Destinatario</label>
                                <input type="text" id="destinatario" name="destinatario" class="w75p mb5px">
                                <div class="seleccion seleccion_pedido ml-23px">
                                    <ul id="listaDestinatario">
                                        <?php echo $this->personal?>
                                    </ul>
                                </div>
                            </div>
                            
                            <label for="representate" class="w15p">Representante</label>
                            <input type="text" id="representate" name="representate" class="w75p mb5px">
                        </div>
                        <div>
                            <h4>Empresa de Transporte</h4>
                            <div>
                                <div>
                                    <label for="ructransp" class="w15p">R.U.C.</label>
                                    <input type="text" name="ructransp" id="ructransp" class="w75p mb5px">
                                    <label for="raztransp" class="w15p">Razón Social</label>
                                    <input type="text" name="raztransp" id="raztransp" class="w75p mb5px">
                                    <label for="dirtransp" class="w15p">Dirección</label>
                                    <input type="text" name="dirtransp" id="dirtransp" class="w75p mb5px">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4>Domicilio de llegada</h4>
                            <div class="p_relative">
                                <label for="almdest" class="w15p">Alm.Destino</label>
                                <input type="text" name="almdest" id="almdest"  class="w75p mb5px">
                                <div class="seleccion seleccion_pedido ml-23px">
                                    <ul id="listaAlmacenDestino">
                                        <?php echo $this->almacenes?>
                                    </ul>
                                </div>
                            </div>
                            <div class="ml30px">
                                <div>
                                    <label for="viatipodest" class="w15p">Via Tipo</label>
                                    <input type="text" name="viatipodest" id="viatipodest" class="mb5px w30p">
                                    <label for="vianomodest">Via Nombre</label>
                                    <input type="text" name="vianomodest" id="vianomodest" class="mb5px w30p">
                                </div>
                                <div>
                                    <label for="viadest" class="w15p">N°</label>
                                    <input type="text" name="viadest" id="viadest" class="w20p mb5px">
                                    <label for="intdest" class="w10p">Interior</label>
                                    <input type="text" name="intdest" id="intdest" class="w20p mb5px">
                                    <label for="zondest">Zona</label>
                                    <input type="text" name="zondest" id="zondest" class="w15p mb5px">
                                </div>
                                <div>
                                    <label for="depdest" class="w15p">Dep.</label>
                                    <input type="text" name="depdest" id="depdest" class="mb5px">
                                    <label for="distdest" class="w15p">Dist.</label>
                                    <input type="text" name="distdest" id="distdest" class="mb5px">
                                </div>
                                <div>
                                    <label for="provdest" class="w15p">Prov.</label>
                                    <input type="text" name="provdest" id="provdest" class="mb5px">
                                    <label for="ubigdest" class="w15p">Ubigeo.</label>
                                    <input type="text" name="ubigdest" id="ubigdest" class="mb5px">
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4>Unidad de Transporte y Conductor</h4>
                            <div>
                                <label for="dnicond" class="w30p">DNI Conductor</label>
                                <input type="text"  id="dnicond" name="dnicond" class="w20p mb5px">
                                <input type="text" name="detcond" id="detcond" class="w40p mb5px">
                            </div>
                            <div>
                                <label for="licencia" class="w30p">N° Licencia de Conducir</label>
                                <input type="text" name="licencia" id="licencia" class="w60p mb5px">
                                <label for="certificado" class="w30p">N° Certificado de Inscripción</label>
                                <input type="text" name="certificado" id="certificado"  class="w60p mb5px">
                                <label for="marca" class="w30p">Marca de Vehículo</label>
                                <input type="text" name="marca" id="marca" class="w60p mb5px">
                                <label for="placa" class="w30p">N° Placa</label>
                                <input type="text" name="placa" id="placa" class="w60p mb5px">
                                <label for="configveh" class="w30p">Configuracion Vehicular</label>
                                <input type="text" name="configveh" id="configveh" class="w60p mb5px">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="optionGuia">
                    <button id="btnAceptarGuia">Aceptar</button>
                    <button id="btnCancelarGuia">Cancelar</button>
                </div>
            </form>
        </div>   
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/despacho.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>