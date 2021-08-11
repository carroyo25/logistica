<?php
    require_once("consultas.php");
    require_once("combos.php");

    $version = rand(20,100);

    $pedido = $_GET['codped'];
    $proveedor = $_GET['codenti'];
    
    $condicion = general($pdo,11);
    $moneda = monedas($pdo);
    $entidad = obtenerProveedor($pdo,$proveedor);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="../img/logo.png" />
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/cotizacion.css?ver<?php echo $version?>">
    <title>Document</title>
</head>
<body>
    <div class="modal" id="modalwait">
        <div class="sk-fading-circle">
            <div class="sk-circle1 sk-circle"></div>
            <div class="sk-circle2 sk-circle"></div>
            <div class="sk-circle3 sk-circle"></div>
            <div class="sk-circle4 sk-circle"></div>
            <div class="sk-circle5 sk-circle"></div>
            <div class="sk-circle6 sk-circle"></div>
            <div class="sk-circle7 sk-circle"></div>
            <div class="sk-circle8 sk-circle"></div>
            <div class="sk-circle9 sk-circle"></div>
            <div class="sk-circle10 sk-circle"></div>
            <div class="sk-circle11 sk-circle"></div>
            <div class="sk-circle12 sk-circle"></div>
        </div>
    </div>
    <div class="modal" id="uploadAtach">
        <a href="" id="closeDialog"></a>
        <div class="cajaDialogoAdjuntos">
            <div class="titulo">
                <span>Adjuntar Archivos</span>
                <a href=""><i class="far fa-window-close"></i></a>
            </div>
            <div class="listaArchivos">
                <ul>
                    <li>
                        <div class="cajaAdjunto">
                            <i class="far fa-file-pdf"></i>
                            <span>Nombre Archivo</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="opciones">
                <button type="button" id="btnAgregar">Agregar</button>
                <button type="button" id="btnAceptar">Aceptar</button>
            </div>
        </div>
    </div>
    <div class="modal" id="dialogConfirm">
        <div class="cajaDialogoConfirm">
            <div class="mensaje">
                <span>Enviar los Datos?</span>
            </div>
            <div class="opciones">
                <button type="button" id="btnAceptarEnvio">Aceptar</button>
                <button type="button" id="btnCancelarEnvio">Cancelar</button>
            </div>
        </div>
    </div>
    <form id="formCotizacion">
        
        <input type="hidden" name="codmoneda" id="codmoneda">
        <input type="hidden" name="codplazo" id="codplazo">
        <input type="file" name="adjuntos" id="adjuntos" class="oculto">
        <div class="main">
            <h3>Solicitud de Cotización</h3>
            <div class="cabecera">
                <div class="logo">
                    <img src="../img/logo.png" alt="">
                </div>
                <div class="entidad">
                    <label for="razon" class="derecha">Razón Social:</label>
                    <input type="text" name="razon" id="razon" readonly  value="<?php echo $entidad["razon"]?>">
                    <label for="ruc" class="derecha">RUC:</label>
                    <input type="text" name="ruc" id="ruc" readonly value="<?php echo $entidad["ruc"]?>">
                    <label for="atencion" class="derecha">Atencion:</label>
                    <input type="text" name="atencion" id="atencion">
                </div>
            </div>
            <hr>
            <div class="datosCotizacion">
                <div class="datos grid6">
                    <label for="fechaDoc" class="derecha">Fecha Emisión :</label>
                    <input type="date" name="fechaDoc" id="fechaDoc">
                    <label for="fechaVig" class="derecha">Fecha Vigencia :</label>
                    <input type="date" name="fechaVig" id="fechaVig">
                    <label for="cotizacion" class="derecha">Nro.Cotizacion:</label>
                    <input type="text" name="cotizacion" id="cotizacion">
                </div>
                <div class="datos grid4">
                    <label for="cuenta" class="derecha">Nro. Cuenta :</label>
                    <input type="text" name="cuenta" id="cuenta">
                    <label for="moneda" class="derecha">Moneda:</label>
                    <select name="moneda" id="moneda">
                        <?php echo $moneda?>
                    </select>
                </div>
                <div class="datos grid4">
                    <label for="condicion" class="derecha">Condicion Pago:</label>
                    <select name="condicion" id="condicion">
                        <?php echo $condicion?>
                    </select>
                    <label for="plazo" class="derecha">Plazo Entrega:</label>
                    <input type="text" name="plazo" id="plazo">
                </div>
                <div class="datos grid4">
                    <label for="inclIgv"  class="derecha">Incluye IGV :</label>
                    <div class="dflex">
                        <input type="radio" name="igv" id="si">
                        <label for="si">Si</label>
                        <input type="radio" name="igv" id="no">
                        <label for="no">No</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="tabla">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Descripción</th>
                            <th>Und.</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Fecha</br>Entrega</th>
                            <th>Observación</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                            <td class="con_borde alto35px"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="totales">
                <div class="cajaObs">
                    <label for="observaciones">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="5"></textarea>
                </div>
                <div class="cajaTotales">
                    <label for="subtotal" class="derecha">Sub Total :</label>
                    <input type="number" name="subtotal" id="subtotal" readonly>
                    <label for="igv" class="derecha">IGV :</label>
                    <input type="number" name="igv" id="subtotal" readonly>
                    <label for="total" class="derecha">Total :</label>
                    <input type="number" name="total" id="total" readonly>
                </div>
            </div>
            <hr>
            <div class="opciones">
                <button type="button" id="btnAdjuntar">Aceptar</button>
                <button type="button" id="btnEnviar">Enviar</button>
                <button type="button" id="btnCancelar">Cancelar</button>
            </div>
        </div> 
    </form>
</body>
<script src="../js/jquery.js"></script>
<script src="../js/solcot.js?v<?php echo $version?>"></script>
</html>