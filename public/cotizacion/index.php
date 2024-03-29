<?php
    require_once("consultas.php");
    require_once("combos.php");

    $version = rand(20,100);

    $pedido = $_GET['codped'];
    $proveedor = $_GET['codenti'];

    //aca tiene que ver otras condiciones

    $condicion = general($pdo,11);
    $plazo = general($pdo,12);
    $moneda = monedas($pdo);
    $entidad = obtenerProveedor($pdo,$proveedor);
    $items = obtenerItems($pdo,$pedido);
    $verificar = verificaParticipa($pdo,$pedido,$proveedor);
    $atencion = datosproveedor($pdo,$proveedor);
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
    <title>SEPCON - Solicitud de Cotización</title>
</head>
<body>
    <div class="mensaje msj_error">
        <span></span>
    </div>
    <div class="modal" id="modalWait">
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
    <div class="modal" id="dialogConfirm">
        <div class="cajaDialogoConfirm">
            <div class="mensajeConfirm">
                <span>¿Los datos a enviar son correctos?</span>
            </div>
            <div class="opciones">
                <button type="button" id="btnAceptarEnvio">Aceptar</button>
                <button type="button" id="btnCancelarEnvio">Cancelar</button>
            </div>
        </div>
    </div>
    <div class="modal" id="dialogVerifica">
        <div class="cajaDialogoConfirm">
            <div class="mensajeConfirm">
                <span>Los datos ya fueron registrados<span>
            </div>
        </div>
    </div>
    <form id="formCotizacion">
        <input type="hidden" id="tipo" name="tipo" value="cotizacion">
        <input type="hidden" id="identi" name="identi" value="<?php echo $proveedor;?>">
        <input type="hidden" id="item" name="item" value="">
        <input type="hidden" id="codped" name="codped" value="<?php echo $pedido;?>">
        <input type="file" name="uploadfile" id="uploadfile" multiple class="oculto" accept="application/pdf">
        <input type="hidden" name="codmoneda" id="codmoneda">
        <input type="hidden" name="codplazo" id="codplazo">
        <input type="hidden" name="archivo_cotizacion" id="archivo_cotizacion">
        <input type="hidden" name="verifica" id="verifica" value="<?php echo $verificar;?>">
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
                    <input type="text" name="atencion" id="atencion" class="mayusculas" value="<?php echo $atencion?>">
                </div>
            </div>
            <hr>
            <div class="datosCotizacion">
                <div class="datos grid6">
                    <label for="fechaDoc" class="derecha">Fecha Emisión :</label>
                    <input type="date" name="fechaDoc" id="fechaDoc" value="<?php echo date("Y-m-d")?>">
                    <label for="fechaVig" class="derecha">Fecha Vigencia :</label>
                    <input type="date" name="fechaVig" id="fechaVig">
                    <label for="cotizacion" class="derecha">Nro.Cotizacion:</label>
                    <input type="text" name="cotizacion" id="cotizacion">
                </div>
                <div class="datos grid4">
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
                    <select name="plazo" id="plazo">
                        <?php echo $plazo?>
                    </select>
                </div>
                <div class="datos grid4">
                    <label for="inclIgv"  class="derecha">Incluye IGV :</label>
                    <div class="dflex">
                        <input type="radio" name="igv" id="si" value="0.18">
                        <label for="si">Si</label>
                        <input type="radio" name="igv" id="no" value="0" checked>
                        <label for="no">No</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="tabla">
                <table id="detalle_pedido">
                    <thead>
                        <tr>
                            <th class="ancho5p">Item</th>
                            <th class="ancho30p">Descripción</th>
                            <th class="ancho5p">Und.</th>
                            <th class="ancho10p">Cant.</th>
                            <th class="ancho10p">Precio</th>
                            <th class="ancho10p">Total</th>
                            <th class="ancho10p">Fecha</br>Entrega</th>
                            <th class="">Observación</th>
                            <th class="ancho5p">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $items?>
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
                    <input type="text" name="subtotal" id="subtotal" class ="derecha padder" readonly>
                    <label for="igv" class="derecha">IGV :</label>
                    <input type="text" name="tigv" id="igv" class ="derecha padder" readonly>
                    <label for="total" class="derecha">Total :</label>
                    <input type="text" name="total" id="total" class ="derecha padder" readonly>
                </div>
            </div>
            <hr>
            <div class="opciones">
                <button type="button" id="btnAdjuntar" form="adjuntos">Adjuntar Cotización</button>
                <button type="button" id="btnEnviar">Enviar</button>
            </div>
        </div> 
    </form>
</body>
<script src="../js/jquery.js"></script>
<script src="../js/funciones.js"></script>
<script src="../js/solcot.js?v<?php echo $version?>"></script>
</html>