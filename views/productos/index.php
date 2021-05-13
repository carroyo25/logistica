<?php
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
    <div class="main_panel">
        <?php require 'views/acordeon.php'; ?>
        <div class="workData">
            <p class="workTitle">Catalogo de Bienes</p>
            <div class="formulario">
                <form action="#" method="POST">
                    <div class="icons">
                        <a href="#"><i class="far fa-star"></i></a>
                        <a href="#"><i class="far fa-question-circle"></i></a>
                        <a href="#"><i class="fas fa-camera-retro"></i></a>
                        <a href="<?php constant('URL')?>/logistica/panel"><i class="far fa-times-circle"></i></a>
                    </div>
                    <div class="banner">
                        <p id="descrip">Nombre : </p>
                    </div>
                    <div class="descripcion">
                        <div class="menu_horizontal">
                            <ul>
                                <li class="selected"><a href="#" data-index="1">Datos Básicos</a>  </li>
                                <li><a href="#" data-index="2">Configuracion</a>    </li>
                                <li><a href="#" data-index="3">Especial</a> </li>
                                <li><a href="#" data-index="4">Documentos</a> </li>
                                <li><a href="#" data-index="5">Existencias</a> </li>
                                <li><a href="#" data-index="6">Trazabilidad</a> </li>
                                <li><a href="#" data-index="7">Componentes</a> </li>
                            </ul>
                        </div>
                        <div class="datos" id="form1">
                            <div class="commad">
                                <button>Ampliar descripción </button>
                            </div>
                            <div>
                                <label for="codigo">Codigo</label>
                                <input type="text" name="codigo" id="codigo" value="0000000000046">
                            </div>
                            <div>
                                <label for="descrip">Descripcion</label>
                                <input type="text" name="descrip" id="descrip" value="TECLADO MULTIMEDIA USB">
                            </div>
                            <div>
                                <label for="clave_corta">Clave Corta</label>
                                <input type="text" name="clave_corta" id="clave_corta" value="00046">
                            </div>
                            <div>
                                <label for="desc_corta">Descripcion corta</label>
                                <input type="text" name="desc_corta" id="desc_corta" value="TECLADO MULTIMEDIA USB">
                            </div>
                            <div>
                                <label for="dep">Departamento</label>
                                <input type="text" name="dep" id="dep" value="00000">
                                <span>SIN DEPARTAMENTO</span>
                            </div>
                            <div>
                                <label for="marca">Marca</label>
                                <input type="text" name="marca" id="marca" value="00003">
                                <span>ACTECK</span>
                            </div>
                            <div>
                                <label for="linea">Linea</label>
                                <input type="text" name="linea" id="linea" value="00003">
                                <span>ACCESORIOS DE COMPUTO</span>
                            </div>
                            <div>
                                <label for="familia">Familia</label>
                                <input type="text" name="familia" id="familia" value="00002">
                                <span>PRODUCTIVIDAD</span>
                            </div>
                            <div>
                                <label for="subfamilia">Sub-Familia</label>
                                <input type="text" name="subfamilia" id="subfamilia" value="00000">
                                <span>SIN SUB FAMILIA</span>
                            </div>
                            <div>
                                <label for="proveedor">Proveedor</label>
                                <input type="text" name="proveedor" id="proveedor" value="000000003">
                                <span>ACTECK DE MEXICO</span>
                            </div>
                            <div>
                                <label for="comprador">Comprador</label>
                                <input type="text" name="comprador" id="comprador" value="00000">
                                <span>SIN COMPRADOR</span>
                            </div>
                        </div> 
                        <div class="datos" id="form2">
                           <div class="">
                                <div>
                                    <label for="codigo">Tipo de Producto</label>
                                    <input type="text" name="codigo" id="codigo" value="Normal">
                                </div>
                                <div>
                                    <label for="codigo">Tipo de costeo</label>
                                    <input type="text" name="codigo" id="codigo" value="Promedio">
                                </div>
                                <div>
                                    <label for="codigo">Origen</label>
                                    <input type="radio" id="rb1" name="origen" value="1">
                                    <span>Compra</span>
                                    <input type="radio" id="rb2" name="origen" value="2">
                                    <span>Producion</span>
                                </div>
                                <div>
                                    <label for="codigo">Destino</label>
                                    <input type="radio" id="rd1" name="destino" value="1">
                                    <span>Venta</span>
                                    <input type="radio" id="rd2" name="destino" value="2">
                                    <span>Consumo</span>
                                    <input type="radio" id="rd3" name="destino" value="3">
                                    <span>Activo Fijo</span>
                                    <input type="radio" id="rd4" name="destino" value="4">
                                    <span>Gasto</span>
                                </div>
                                <div>
                                    <label for="codigo">Controlar</label>
                                    <input type="checkbox" id="rd1" name="destino" value="1">
                                    <span>Talla</span>
                                    <input type="checkbox" id="rd1" name="destino" value="1">
                                    <span>Color</span>
                                </div>
                                <div>
                                    <label for="linea">Unidad de Compra</label>
                                    <input type="text" name="uc" id="uc" value="PZ">
                                    <span>PIEZAS</span>
                                </div>
                                <div>
                                    <label for="linea">Unidad de Venta</label>
                                    <input type="text" name="uv" id="uv" value="PZ">
                                    <span>PIEZAS</span>
                                </div>
                                <div>
                                    <label for="linea">Unidad transferencia</label>
                                    <input type="text" name="ut" id="ut" value="PZ">
                                    <span>PIEZAS</span>
                                </div>
                                <div>
                                    <label for="linea">Moneda</label>
                                    <input type="text" name="moneda" id="moneda" value="Soles">
                                    <span>SOLES</span></span>
                                </div>
                                <div>
                                    <label for="linea">Tiempo de entrega</label>
                                    <input type="text" name="entrega" id="entrega">
                                </div>
                                <table>
                                    <caption>Conversion de Unidades</caption>
                                   <thead>
                                        <tr>
                                            <th>Cant</th>
                                            <th>U.Origen</th>
                                            <th>Equivalencia</th>
                                            <th>U.Destino</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                   </tbody>
                               </table>
                           </div>
                        </div>
                        <div class="datos" id="form3">
                            <div class="commad picture">
                                <img src="<?php echo constant("URL")?>/public/img/noimagen.jpg" alt="">
                            </div>
                            <div>
                                <label for="codigo">Numero de parte</label>
                                <input type="text" name="codigo" id="codigo" value="900000420000046">
                            </div>
                            <div>
                                <label for="codigo">EAN UPC</label>
                                <input type="text" name="codigo" id="codigo" value="0000000000046">
                            </div>
                            <div>
                                <label for="codigo">Cantidad de Empaque</label>
                                <input type="text" name="codigo" id="codigo" value="0">
                            </div>
                            <div>
                                <label for="codigo">Lleva O.C.</label>
                                <input type="radio" id="rb1" name="origen" value="1">
                                <span>Si</span>
                                <input type="radio" id="rb2" name="origen" value="2">
                                <span>No</span>
                            </div>
                            <div>
                                <label for="codigo">Fecha caducidad</label>
                                <input type="radio" id="rb1" name="origen" value="1">
                                <span>Si</span>
                                <input type="radio" id="rb2" name="origen" value="2">
                                <span>No</span>
                            </div>
                            <div>
                                <label for="codigo">Peso</label>
                                <input type="text" name="codigo" id="codigo" value="0">
                            </div>
                            <div>
                                <label for="codigo">Volumen</label>
                                <input type="text" name="codigo" id="codigo" value="0">
                            </div>
                            <table>
                                    <caption>Codigo del Proveedor</caption>
                                   <thead>
                                        <tr>
                                            <th>Proveedor</th>
                                            <th>Descripcion</th>
                                            <th>Codigo</th>
                                            <th>Clasificador</th>
                                            <th>Descuento</th>
                                            <th>...</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>x</td>
                                        </tr>
                                   </tbody>
                               </table>
                        </div>
                        <div class="datos" id="form4">
                            <table>
                                <caption>Documentos registrados</caption>
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Archivo</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Manual del Usuario</td>
                                        <td>wer3434534.pdf</td>
                                        <td><a href="#"><i class="far fa-eye"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                        <div class="datos" id="form5">
                            
                        </div>
                        <div class="datos" id="form6">
                            <table>
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
                                    <tr>
                                        <td>002530</td>
                                        <td>30/10/2020</td>
                                        <td>Almacen Pucallpa</td>
                                        <td>Custodia Almacen</td>
                                        <td>30</td>
                                        <td><a href="#"><i class="far fa-eye"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>002544</td>
                                        <td>30/10/2020</td>
                                        <td>Almacen Pucallpa</td>
                                        <td>En Transito</td>
                                        <td>30</td>
                                        <td><a href="#"><i class="far fa-eye"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/productos.js"></script>
    <!-- <script src="<?php echo constant('URL');?>public/js/funciones.js"></script> -->
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>