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
    <div class="modal" id="modalSeekCodes">
        <div class="search">
            <div>
                <div class="divSearch">
                    <i class="fas fa-search"></i>
                    <input type="search" name="" id="inputSearchCodes">
                </div>
                <div class="listData">
                    <table class="tableSeekCodes">
                        <thead>
                            <tr>
                                <th class="w30p">Grupo</th>
                                <th class="w30p">Clase</th>
                                <th class="w30p">Familia</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="#" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal" id="modalSeekItems">
        <div class="search">
            <div>
                <div class="divSearch">
                    <i class="fas fa-search"></i>
                    <input type="search" name="" id="inputSearchItems">
                </div>
                <div class="listData">
                    <table id="tableSeekItems">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Nombre Comercial</th>
                                <th>Nombre Corto</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Nro. Parte</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="#" class="buttonClose"><i class="fas fa-reply-all"></i></a>
    </div>
    <div class="modal" id="dialogWindow">
        <div class="dialogContainer">
            <div class="dialogTitle">
                <h4>Agregar Documento</h4>
                <a href="#" id="closeDialog">X</a>
            </div>
            <hr>
            <form action="#" id="addDocument" method="POST">
                <input type="file" id="doc_file" name="doc_file" multiple class="oculto" accept="application/pdf,application/vnd.ms-excel,application/vnd.ms-word">
                <input type="hidden" name="codfile" id="codfile">
                <div class="dialogBody">
                    <div class="inputDialog">
                        <label for="docname">Descripcion :</label>
                        <input type="text" name="docname" id="docname" placeholder="">
                    </div>
                    <div class="inputDialog">
                        <label for="docfilename">Archivo :</label>
                        <input type="text" name="docfilename" id="docfilename" placeholder="seleccione el archivo" readonly>
                    </div>
                    <div class="options">
                        <button id="btnAceptar" class="botones">Aceptar</button>
                        <button id="btnCancelar" class="botones">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" id="dialogConfirm">
        <div class="dialogContainer">
            <div class="dialogTitle">
                <h4>Pregunta</h4>
            </div>
            <hr>
            <div class="dialogBody">
                <h1>Esta seguro de Eliminar?</h1>   
                <div class="options">
                    <button id="btnConfirm" class="botones">Aceptar</button>
                    <button id="btnCancel" class="botones">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modalWait">
        <div class="containerWait">
            <h1 class="title">Cargando...</h1>
            <div class="rainbow-marker-loader"></div>
        </div>
    </div>
    <div class="mensaje msj_error">
        <span></span>
    </div>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workData">
            <div class="tilewindow">
                <p class="workTitle">Catalogo de Servicios</p>
                <?php require 'views/menus.php'; ?>
            </div>
            <div class="formulario">
                <form action="#" method="POST" id="dataItem">
                    <input type="hidden" name="tipo_producto" id="tipo_producto" value="2">
                    <input type="hidden" name="cod_unidad" id="cod_unidad">
                    <input type="hidden" name="estado" id="estado" value="2">
                    <input type="hidden" name="index_producto" id="index_producto">

                    <input type="file" id="image_file" name="image_file" multiple class="oculto" accept="image/jpg">
                    
                    <div class="banner">
                        <p id="descrip">Nombre : </p>
                    </div>
                    <div class="descripcion">
                        <div class="menu_horizontal">
                            <ul>
                                <li class="selected"><a href="#" data-index="1">Datos Básicos</a></li>
                                <li><a href="#" data-index="3">Configuración</a></li>
                                <li><a href="#" data-index="4">Afectaciones</a></li>
                                <li><a href="#" data-index="5">Documentos</a></li>
                                <li><a href="#" data-index="7">Trazabilidad</a></li>
                                <li><a href="#" data-index="8">Historial</a></li>
                            </ul>
                        </div>
                        <div class="datos" id="form1">
                            <a href="#" class="floatingButton desactivado" title="Buscar Grupos/Clases/Familia" id="seekGroups"><i class="fas fa-atlas"></i></a>
                            <div class="formbody desactivado">
                                <div class="izquierda">
                                    <div class="divInput">
                                        <label for="descripcion" class="w250px">* Descripcion : </label>
                                        <input type="text" name="descripcion" id="descripcion" class="mayusculas w50p">
                                    </div>
                                    <div class="divInput">
                                        <label for="nombre_comercial" class="w250px">* Nombre Comercial :</label>
                                        <input type="text" name="nombre_comercial" id="nombre_comercial" class="mayusculas w50p">
                                    </div>
                                    <div class="divInput">
                                        <label for="nombre_corto" class="w250px">Nombre Corto :</label>
                                        <input type="text" name="nombre_corto" id="nombre_corto" class="mayusculas w50p">
                                    </div>
                                    <div class="divInput desplegable">
                                        <label for="unidad_medida" class="w250px">* Unidad de Medida :</label>
                                        <input type="text" name="unidad_medida" id="unidad_medida" class="mayusculas w50p" placeholder="seleccione una opcion">
                                        <div class="seleccion">
                                            <ul id="lista">
                                                <?php echo $this->unidades?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <label for="detalles" class="w250px">Detalles :</label>
                                        <textarea name="detalles" id="detalles" rows="5" class="mayusculas w50p"></textarea>
                                    </div>
                                </div>
                                <div class="derecha">
                                    <div class="divInfo">
                                        <label for="codigo" class="w15p">Código :</label>
                                        <input type="text" name="codigo" id="codigo" class="desactivado">
                                        <span id="codigo_preliminar"> Codigo Preliminar </span>
                                    </div>
                                    </br>
                                    <h4 class="nota_info">Codificación Asignada</h4>
                                    <div class="divInfo">
                                        <label for="grupo" class="w15p">Grupo :</label>
                                        <input type="text" name="grupo" id="grupo" class="desactivado">
                                        <span id="nombre_grupo">---</span>
                                    </div>
                                    <div class="divInfo">
                                        <label for="clase" class="w15p">Clase :</label>
                                        <input type="text" name="clase" id="clase" class="desactivado">
                                        <span id="nombre_clase">---</span>
                                    </div>
                                    <div class="divInfo">
                                        <label for="familia" class="w15p">Familia :</label>
                                        <input type="text" name="familia" id="familia" class="desactivado">
                                        <span id="nombre_familia">---</span>
                                    </div>
                                    </br>
                                    <h4 class="nota_info">Codificación SUNAT (Opcional)</h4>
                                    <div class="divInfo">
                                        <label for="segmento" class="w15p">Segmento :</label>
                                        <input type="text" name="segmento" id="segmento" class="desactivado">
                                        <span id="nombre_grupo">---</span>
                                    </div>
                                    <div class="divInfo">
                                        <label for="clase_sunat" class="w15p">Clase :</label>
                                        <input type="text" name="clase_sunat" id="clase_sunat" class="desactivado">
                                        <span id="nombre_clase_sunat">---</span>
                                    </div>
                                    <div class="divInfo">
                                        <label for="familia_sunat" class="w15p">Familia :</label>
                                        <input type="text" name="familia_sunat" id="familia_sunat" class="desactivado">
                                        <span id="nombre_familia_sunat">---</span>
                                    </div>
                                    <div class="divInfo">
                                        <label for="producto_sunat" class="w15p">Producto :</label>
                                        <input type="text" name="producto_sunat" id="producto_sunat" class="desactivado">
                                        <span id="producto_sunat">---</span>
                                    </div>
                                    <div class="divInfo">
                                        <label for="codigo_barra" class="w15p">Cod. Barras :</label>
                                        <input type="text" name="codigo_barra" id="codigo_barra" class="desactivado">
                                        <span id="producto_sunat">---</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="datos" id="form3">
                            <div class="formbody desactivado">
                                <div class="izquierda">
                                    <div class="divInput ml100px">
                                        <label for="origen">Origen :</label>
                                        <div class="radioGroup">
                                            <div>
                                                <input type="radio" id="compra" name="origen" value="1">
                                                <label for="compra">Compra</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="prodccion" name="origen" value="2">
                                                <label for="prodccion">Producción</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divInput ml100px">
                                        <label for="origen">Destino :</label>
                                        <div class="radioGroup">
                                            <div>
                                                <input type="radio" id="venta" name="destino" value="1">
                                                <label for="venta">Venta</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="consumo" name="destino" value="2">
                                                <label for="consumo">Consumo</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="activo_fijo" name="destino" value="3">
                                                <label for="activo_fijo">Act.Fijo</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="gasto" name="destino" value="4">
                                                <label for="gasto">Gasto</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <div class="checkbox">
                                            <div>
                                                <input type="checkbox" name="unidades_secundarias" id="unidades_secundarias">
                                                <label for="unidades_secundarias">Maneja Unidades Secundarias</label>
                                            </div>
                                            <div>
                                                <input type="checkbox" name="lotes" id="lotes">
                                                <label for="lotes">Maneja Lotes</label>
                                            </div>
                                            <div>
                                                <input type="checkbox" name="series_unicas" id="series_unicas">
                                                <label for="series_unicas">Series Unicas</label>
                                            </div>
                                            <div>
                                                <input type="checkbox" name="controlado_digemid" id="controlado_digemid">
                                                <label for="controlado_digemid">Producto Controlado por DIGEMID</label>
                                            </div>
                                            <div>
                                                <input type="checkbox" name="producto_relacionado" id="producto_relacionado">
                                                <label for="producto_relacionado">Producto Relacionado</label>
                                            </div>
                                            <div>
                                                <input type="checkbox" name="afecto_icbper" id="afecto_icbper">
                                                <label for="afecto_icbper">Afecto a ICBPER</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <div class="regespecial">
                                            <div>
                                                <input type="radio" id="no_afecto" name="reg_especial" value="1">
                                                <label for="no_afecto">No Afecto</label>
                                                <input type="radio" id="percepcion" name="reg_especial" value="2">
                                                <label for="percepcion">Percepción</label>
                                                <input type="radio" id="detraccion" name="reg_especial" value="3">
                                                <label for="detraccion">Detracción</label>
                                            </div>
                                            <div>
                                                <h4>Porcentaje</h4>
                                                <div>
                                                    <input type="text" name="porcentaje" id="porcentaje">
                                                    <input type="text" name="porcentaje1" id="porcentaje1" class="w100p">
                                                </div>
                                                <div>
                                                    <p>%</p>
                                                    <input type="text" name="porcentaje2" id="porcentaje2">
                                                    <p>cMonto Minimo S/.</p>
                                                    <input type="text" name="porcentaje3" id="porcentaje3" class="w40p">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="derecha">
                                    <div class="foto" class="oculto">
                                        <img id="img_preview" src="<?php echo constant('URL')?>public/img/noimagen.jpg" class="oculto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="datos" id="form4">
                        </div>
                        <div class="datos" id="form5">
                            <a href="#" class="floatingButton desactivado" title="Añadir Documento" id="addDoc"><i class="fas fa-archive"></i></a>
                            <div class="formtable">
                                <table id="documentRegister">
                                    <caption>Registro de Documentos</caption>
                                    <thead>
                                        <tr>
                                            <th>Descripcion</th>
                                            <th>Archivo</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="datos" id="form7">
                            <table id="tableTrazabilidad">
                                <caption>Trazabilidad</caption>
                                <thead>
                                    <tr>
                                        <th>OC</th>
                                        <th>Fecha</th>
                                        <th>Ubicacion</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div> 
                        <div class="datos" id="form8">
                            <div class="formtable">
                                <table id="tableHistory">
                                    <caption>Historial</caption>
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Accion</th>
                                            <th>Fecha/Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/servicios.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>