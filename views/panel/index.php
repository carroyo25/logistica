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
            <p class="workTitle">
                <h1>Panel de Control</h1>
                
                <br>
            </p>
            <div class="banners">
                <div>
                    <i class="fas fa-cogs"></i>
                    <div>
                        <p>ORDENES DE COMPRA</p>
                        <p>11</p>
                        <p>Ordenes Nuevas : 9</p>
                    </div>
                </div>
                <div>
                    <i class="fas fa-barcode"></i>
                    <div>
                        <p>PRODUCTOS</p>
                        <p>3</p>
                        <p>Productos Nuevos : 3</p>
                    </div>
                </div>
                <div>
                    <i class="fas fa-toolbox"></i>
                    <div>
                        <p>SERVICIOS</p>
                        <p>3</p>
                        <p>Servicios Nuevos : 19</p>
                    </div>
                </div>
                <div>
                    <i class="far fa-id-card"></i>
                    <div>
                        <p>CLIENTES</p>
                        <p>2</p>
                        <p>Clientes Nuevos : 19</p>
                    </div>
                </div>
            </div>
            <div class="showtables">
                <div>
                    <p>Ordenes de Compra</p>

                    <table>
                        <thead>
                            <tr>
                                <th>Orden N°</th>
                                <th>Proveedor</th>
                                <th>Fecha</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10</td>
                                <td>Joaquin Obed</td>
                                <td class="textocentro">11-03-2016</td>
                                <td class="textoderecha">101.70</td>  
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Joaquin Obed</td>
                                <td class="textocentro">15-04-2016</td>
                                <td class="textoderecha">101.70</td>  
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Joaquin Obed</td>
                                <td class="textocentro">21-01-2016</td>
                                <td class="textoderecha">26.25</td>  
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Joaquin Obed</td>
                                <td class="textocentro">16-08-2016</td>
                                <td class="textoderecha">55.96</td>  
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Joaquin Obed</td>
                                <td class="textocentro">28-02-2016</td>
                                <td class="textoderecha">0.00</td>  
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>Joaquin Obed</td>
                                <td class="textocentro">28-02-2016</td>
                                <td class="textoderecha">129.65</td>  
                            </tr>
                        </tbody>
                    </table>

                    <div>
                        <button>Nueva Orden</button>
                        <button>Ver todas las ordenes</button>
                    </div>
                </div>
                
                <div>
                    <p>Nuevos Productos</p>
                    <div>
                        <div>
                            <img src="" alt="">
                        </div>
                        <div>
                            <p>Monitor LED 42</p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <img src="" alt="">
                        </div>
                        <div>
                            <p>Monitor LED UHD 280</p>
                        </div>
                    </div>
                    <div>
                        <div>
                            <img src="" alt="">
                        </div>
                        <div>
                            <p>Disco duro</p>
                        </div>
                    </div>
                    <a href="#">Ver Catálogo de Productos</a>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo constant('URL');?>public/js/jquery.js"></script>
    <script src="<?php echo constant('URL');?>public/js/funciones.js?<?php echo constant('VERSION')?>"></script>
    <script src="<?php echo constant('URL');?>public/js/panel.js?<?php echo constant('VERSION')?>"></script>
</body>
</html>