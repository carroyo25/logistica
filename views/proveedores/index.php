<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?php echo constant('URL')?>public/img/logo.png" />
    <title>Sistema de Control logístico - Ibis</title>
</head>
<body>
    <div class="modal" id="modalSeek">
        <div class="search">
            <div>
                <div class="divSearch">
                    <i class="fas fa-search"></i>
                    <input type="search" name="" id="inputSearchItems">
                </div>
                <div class="listData">
                    <table id="tableSeek">
                        <thead>
                            <tr>
                                <th>Razón Social</th>
                                <th>Nombre Comercial</th>
                                <th>Nro. Documento</th>
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
    <div class="modal" id="dialogContact">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Agregar Contacto</h4>
                <a href="#" id="closeDialog">X</a>
            </div>
            <hr>
            <form action="#" method="POST" id="formContact">
                <div class="dialogBody">
                    <div class="inputDialog">
                        <label for="nom_contact">* Nombre :</label>
                        <input type="text" name="nom_contact" id="nom_contact" placeholder="Nombre del contacto" class="mayusculas">
                    </div>
                    <div class="inputDialog">
                        <label for="mail_contac">Correo Electrónico:</label>
                        <input type="mail" name="mail_contac" id="mail_contac" placeholder="alguien@dominio.com" class="minusculas">
                    </div>
                    <div class="inputDialog">
                        <label for="dir_contac">Dirección :</label>
                        <input type="text" name="dir_contac" id="dir_contac" placeholder="Dirección del contacto" class="mayusculas">
                    </div>
                    <div class="inputDialog">
                        <label for="telefono_contact">* Teléfono :</label>
                        <input type="text" name="telefono_contact" id="telefono_contact" placeholder="Teléfono">
                    </div>
                    <div class="options">
                        <button id="btnAceptarContac" class="botones">Agregar</button>
                        <button id="btnCancelarContac" class="botones">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" id="dialogBank">
        <div class="dialogContainer w35p">
            <div class="dialogTitle">
                <h4>Agregar Contacto</h4>
                <a href="#" id="closeDialog">X</a>
            </div>
            <hr>
            <form action="#" method="POST" id="formFinancial">
                <input type="hidden" name="moneda_num" id="moneda_num">
                <input type="hidden" name="banco_cod" id="banco_cod">
                <div class="dialogBody">
                    <div class="inputDialog">
                        <label for="numero_cuenta">* N° Cuenta :</label>
                        <input type="text" name="numero_cuenta" id="numero_cuenta" placeholder="Número de cuenta" class="mayusculas">
                    </div>
                    <div class="inputDialog">
                        <label for="nombre_banco">* Entidad financiera :</label>
                        <input type="text" name="nombre_banco" id="nombre_banco" placeholder="Nombre entidad financiera" class="mayusculas">
                        <div class="seleccion mh40vh w90p" id="bancos">
                            <ul id="lista">
                                <?php echo $this->bancos?>
                            </ul>
                        </div>
                    </div>
                    <div class="inputDialog">
                        <label for="moneda">* Moneda :</label>
                        <input type="text" name="moneda" id="moneda" class="mayusculas">
                        <div class="seleccion mh40vh w90p" id="monedas">
                            <ul id="lista">
                                <?php echo $this->monedas?>
                            </ul>
                        </div>
                    </div>
                    <div class="inputDialog">
                        <label for="tipo_cuenta">* Tipo de cuenta :</label>
                        <input type="text" name="tipo_cuenta" id="tipo_cuenta" placeholder="Tipo de Cuenta" class="mayusculas">
                    </div>
                    <div class="options">
                        <button id="btnAceptarBank" class="botones">Agregar</button>
                        <button id="btnCancelarBank" class="botones">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php require 'views/header.php'; ?>
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workData">
            <div class="tilewindow">
                <p class="workTitle">Catalogo de Proveedores</p>
                <?php require 'views/menus.php'; ?>
            </div>
            <div class="formulario">
                <form action="#" method="POST" id="dataItem">
                    <input type="hidden" name="estado" id="estado" value="1">
                    <input type="hidden" name="index_proveedor" id="index_proveedor">
                    <input type="hidden" name="codigo_documento" id="codigo_documento">
                    <input type="hidden" name="codigo_persona" id="codigo_persona">
                    <input type="hidden" name="codigo_entidad" id="codigo_entidad">
                    <input type="hidden" name="codigo_pais" id="codigo_pais">
                    <input type="hidden" name="codigo_pago" id="codigo_pago">
                    <input type="hidden" name="ubigeo" id="ubigeo">
                    <input type="hidden" name="calificacion" id="calificacion" value="0">
                    <div class="banner">
                        <p id="descrip">Nombre : </p>
                    </div>
                    <div class="descripcion">
                        <div class="menu_horizontal">
                            <ul>
                                <li class="selected"><a href="#" data-index="1">Datos Generales</a></li>
                                <li><a href="#" data-index="2">Contactos</a></li>
                                <li><a href="#" data-index="3">Bancos</a></li>
                                <li><a href="#" data-index="4">Trazabilidad</a></li>
                            </ul>
                        </div>
                        <div class="datos" id="form1">
                            <div class="formbody desactivado">
                                <div class="izquierda">
                                    <div class="divInput">
                                        <label for="tip_doc" class="w250px">* Tipo de documento : </label>
                                        <input type="text" name="tip_doc" id="tip_doc" class="mayusculas w50p">
                                        <div class="seleccion" id="td">
                                            <ul id="lista">
                                                <?php echo $this->docstype?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <label for="tip_ent" class="w250px">* Tipo de Entidad : </label>
                                        <input type="text" name="tip_ent" id="tip_ent" class="mayusculas w50p">
                                        <div class="seleccion" id="te">
                                            <ul id="lista">
                                                <?php echo $this->entitype?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <label for="numero" class="w250px">* Numero :</label>
                                        <input type="text" name="numero_documento" id="numero_documento" class="mayusculas">
                                    </div>
                                    <div class="divInput">
                                        <label for="tip_persona" class="w250px">* Tipo de persona : </label>
                                        <input type="text" name="tip_persona" id="tip_persona" class="mayusculas w50p">
                                        <div class="seleccion" id="tp">
                                            <ul id="lista">
                                                <?php echo $this->persontype?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <label for="numero" class="w250px">* Razón Social :</label>
                                        <input type="text" name="razon_social" id="razon_social" class="mayusculas w50p">
                                    </div>
                                    <div class="divInput">
                                        <label for="correo" class="w250px">* Correo Electrónico :</label>
                                        <input type="mail" name="correo" id="correo" class="minusculas w50p">
                                    </div>
                                    <hr>
                                    <div class="divInput">
                                        <label for="ape_paterno" class="w250px">Apellido Paterno :</label>
                                        <input type="text" name="ape_paterno" id="ape_paterno" class="mayusculas">
                                    </div>
                                    <div class="divInput">
                                        <label for="ape_paterno" class="w250px">Apellido Materno :</label>
                                        <input type="text" name="ape_materno" id="ape_materno" class="mayusculas">
                                    </div>
                                    <div class="divInput">
                                        <label for="nombre_proveedor" class="w250px">Nombres :</label>
                                        <input type="text" name="nombre_proveedor" id="nombre_proveedor" class="mayusculas">
                                    </div>
                                    <hr>
                                    <div class="divInput">
                                        <label for="nombre_comercial" class="w250px">Nombre Comercial :</label>
                                        <input type="text" name="nombre_comercial" id="nombre_comercial" class="mayusculas w50p">
                                    </div>
                                    <div class="divInput">
                                        <label for="telefono" class="w250px">Teléfono :</label>
                                        <input type="number" name="telefono" id="telefono">
                                    </div>
                                    <div class="compuesto">
                                        <div class="one_item">
                                            <label for="pais">País :</label>
                                            <input type="text" name="pais" id="pais" class="mayusculas w50p">
                                            <div class="seleccion_grid mh25vh seleccion" id="listPais">
                                                <ul id="lista">
                                                    <?php echo $this->paises?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="one_item">
                                            <label for="direccion">* Dirección :</label>
                                            <input type="text" name="direccion" id="direccion" class="mayusculas w92p">
                                        </div>
                                        <div class="tree_items">
                                            <label for="numero">N° :</label>
                                            <input type="text" name="numero" id="numero" class="mayusculas">
                                            <label for="interior">Interior :</label>
                                            <input type="text" name="interior" id="interior" class="mayusculas">
                                            <label for="zona">Zona :</label>
                                            <input type="text" name="zona" id="zona" class="mayusculas">
                                        </div>
                                        <div class="tree_items_selection_tree desactivado">
                                            <div>
                                                <label for="dpto">Dpto. :</label>
                                                <input type="text" name="dpto" id="dpto" class="mayusculas">
                                                <div class="seleccion seleccion_grid" id="comboDpto">
                                                    <ul id="lista">
                                                        <?php echo $this->listdpto?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="prov">Prov. :</label>
                                                <input type="text" name="prov" id="prov" class="mayusculas">
                                                <div class="seleccion  seleccion_grid" id="comboProv">
                                                    <ul id="lista">
                                                        <?php echo $this->listprov?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="dist">Dist. :</label>
                                                <input type="text" name="dist" id="dist" class="mayusculas">
                                                <div class="seleccion seleccion_grid" id="comboDist">
                                                    <ul id="lista">
                                                        <?php echo $this->listdist?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="derecha">
                                    <div class="checkbox_box">
                                        <div>
                                            <input type="checkbox" name="agente_percepcion" id="agente_percepcion">
                                            <label for="agente_percepcion">Agente Percepción</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="agente_recepcion" id="agente_recepcion">
                                            <label for="agente_recepcion">Agente Retención</label>
                                        </div>
                                    </div>
                                    <div class="divInput">
                                        <label for="porcentaje" class="w20p">Porcentaje :</label>
                                        <input type="number" name="porcentaje" id="porcentaje" class="mayusculas w20p" min="0" max="100">
                                    </div>
                                    <div class="divInput">
                                        <label for="monto_minimo" class="w20p">Monto Mínimo :</label>
                                        <input type="number" name="monto_minimo" id="monto_minimo" class="mayusculas w20p" min="0">
                                    </div>
                                    <div class="radioGroup">
                                        <hr>
                                        <p>Condición</p>
                                        <div>
                                            <input type="radio" id="habido" name="condicion" value="1">
                                            <label for="habido">Habido</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="no_habido" name="condicion" value="2">
                                            <label for="no_habido">No Habido</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <p>DIGEMID</p>
                                    <div class="divInput">
                                        <label for="nro_registro" class="w20p">Nro. Registro :</label>
                                        <input type="number" name="nro_registro" id="nro_registro">
                                    </div>
                                    <div class="divInput">
                                        <label for="categoria" class="w20p">Categoria :</label>
                                        <input type="text" name="categoria" id="categoria" class="mayusculas">
                                    </div>
                                    <div class="divInput">
                                        <label for="situacion" class="w20p">Situación :</label>
                                        <input type="text" name="situacion" id="situacion" class="mayusculas">
                                    </div>
                                    <div class="radioGroupInline">
                                            <label for="empadronado" class="w20p">Empadronado :</label>
                                            <div>
                                                <input type="radio" id="empadronado_si" name="empadronado" value="1">
                                                <label for="empadronado_si">Si</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="empadronado_no" name="empadronado" value="2">
                                                <label for="empadronado_no">No</label>
                                            </div>
                                    </div>
                                    <div class="compuesto">
                                        <div class="one_item">
                                            <label for="pais">Tipo Pago :</label>
                                            <input type="text" name="pago" id="pago" class="mayusculas w50p">
                                            <div class="seleccion_grid mh25vh seleccion" id="listPagos">
                                                <ul id="listaPago">
                                                    <?php echo $this->pagos?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="datos" id="form2">
                            <a href="#" class="floatingButton desactivado" title="Añadir Documento" id="addContact"><i class="fas fa-plus"></i></a>
                            <div class="formtable">
                                <table id="tableContact">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Correo Electrónico</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="datos" id="form3">
                            <a href="#" class="floatingButton desactivado" title="Añadir Documento" id="addBank"><i class="fas fa-plus"></i></a>
                            <div class="formtable">
                                <table id="tableBanks">
                                    <thead>
                                        <tr>
                                            <th>N° de Cuenta</th>
                                            <th>Banco</th>
                                            <th>Moneda</th>
                                            <th>Tipo de Cuenta</th>
                                            <th class="oculto">cod_moneda</th>
                                            <th class="oculto">cod_banco</th>
                                            <th>...</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="datos" id="form4">
                        </div>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js"></script>
    <script src="<?php echo constant('URL');?>public/js/proveedores.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>