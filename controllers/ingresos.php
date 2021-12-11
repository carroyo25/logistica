<?php
    class Ingresos extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->almacenes  = $this->model->getWarehouses();
            $this->view->motivos    = $this->model->getMovs();
            $this->view->aprueba    = $this->model->getPersonal();
            $this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros
            
            $this->view->render('ingresos/index');
        }

        function nroIngreso(){
            $cod = $_POST['data'];

            $result = $this->model->genNumber($cod);

            echo json_encode($result);
        }

        function ordenes(){
            $result =  $this->model->getOrders();
            echo $result;
        }

        function ordenesPalabra(){
            $string = $_POST['palabra'];

            $result =  $this->model->getOrdersWord($string);

            echo $result;
        }

        function detallesOrdenes(){
            $cod = $_POST['codigo'];

            $result = $this->model->getOrderDetails($cod);

            echo $result;
        }

        function importOrder(){
            $cod = $_POST['codigo'];

            $result = $this->model->getOrdersByNumer($cod);

            echo json_encode($result);
        }

        function detailsOrder(){
            $cod = $_POST['codigo'];

            $result = $this->model->getOrderDetails($cod);

            echo $result;
        }

        function uploadDocuments(){
            // Count total files
            $countfiles = count($_FILES['uploadAtach']['name']);
            $files = array();
            // Looping all files
            for($i=0;$i<$countfiles;$i++){
                $ext = explode('.',$_FILES['uploadAtach']['name'][$i]);
                $filename = uniqid("gr").".".end($ext);
                $return = $filename ."~".$_FILES['uploadAtach']['name'][$i];

                // Upload file
                move_uploaded_file($_FILES['uploadAtach']['tmp_name'][$i],'public/adjuntos/'.$filename);
                array_push($files,$return);
            }

            $json_string = json_encode($files);
            echo $json_string;
        }

        function preview(){
            $ingreso =  $_POST['ndoc'];
            $condicion = $_POST['condicion'];
            $fecha = $_POST['fecha'];
            $proyecto = $_POST['proyecto'];
            $origen = $_POST['origen'];
            $movimiento = $_POST['movimiento'];
            $orden = $_POST['orden'];
            $pedido = $_POST['pedido'];
            $guia = $_POST['guia'];
            $autoriza = $_POST['autoriza'];
            $cargo = $_POST['cargo'];
            $entidad = $_POST['entidad'];
            $details = $_POST['details'];
            $tipo = $_POST['tipo'];
            $ruta = $_POST['ruta'];

            $result = $this->model->genPreview($ingreso,$condicion,$fecha,$proyecto,
                                                $origen,$movimiento,$orden,$pedido,$guia,
                                                $autoriza,$cargo,$entidad,$details,$tipo,$ruta);

            echo $result;
        }

        function nuevoIngreso(){
            $fecha = $_POST['fecha'];
            $origen = $_POST['origen'];
            $fcontable = $_POST['fcoontable'];
            $entidad = $_POST['entidad'];
            $guia = $_POST['guia'];
            $orden = $_POST['orden'];
            $pedido = $_POST['pedido'];
            $estado = $_POST['estado'];
            $autoriza = $_POST['autoriza'];
            $detalles = $_POST['detalles'];
            $series = $_POST['series'];
            $adjuntos = $_POST['adjuntos'];
            $proyecto = $_POST['cod_pry'];
            $area = $_POST['cod_area'];
            $costos = $_POST['cod_cos'];
            $cod_mov = $_POST['cod_mov'];
            $calidad = $_POST['calidad'];

            $result = $this->model->insertarIngreso($fecha,$origen,$fcontable,$entidad,$guia,$orden,$pedido,$estado,$autoriza,
                                                    $detalles,$series,$adjuntos,$proyecto,$area,$costos,$cod_mov,$calidad);

            echo json_encode($result);
        }

        function actualizaIngreso(){
            $index = $_POST['index']; 
            $guia = $_POST['guia'];
            $autoriza = $_POST['autoriza'];
            $detalles = $_POST['detalles'];
            $series = $_POST['series'];
            $adjuntos = $_POST['adjuntos'];

            $result = $this->model->actualizarIngreso($index,$guia,$autoriza,$detalles,$series,$adjuntos);

            echo json_encode($result);
        }

        function llamaIngresoPorId(){
            $nota = $_POST['nota'];

            $result = $this->model->cambiarNota($nota);

            echo json_encode($result);
        }
        
        function llamarDetallesCodigo(){
            $idx = $_POST['index'];

            $result = $this->model->listaDetallesCodigo($idx);

            echo $result;
        }

        function llamarAdjuntos(){
            $idx = $_POST['index'];

            $result = $this->model->listarAdjuntosCodigos($idx);

            echo $result;
        }

        function llamarSeries(){
            $idx = $_POST['index'];
            $prod = $_POST['prod'];

            $result = $this->model->listarSeriesProducto($idx,$prod);

            echo $result;
        }

        function cierreIngreso(){
            $cod = $_POST['cod'];
            $detalles = $_POST['details'];
            $condicion = $_POST['condicion'];
            $pedido = $_POST['pedido'];
            $orden = $_POST['orden'];
            $entidad = $_POST['entidad'];

            $result = $this->model->cerrarIngreso($cod,$detalles,$condicion,$pedido,$orden,$entidad);

            echo $result;
        }
    }
?>