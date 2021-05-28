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
            //$this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros
            
            $this->view->render('ingresos/index');
        }

        function nroIngreso(){
            $cod = $_POST['data'];

            $result = $this->model->genNumber($cod);

            echo str_pad($result,6,"0",STR_PAD_LEFT);
        }

        function ordenes(){
            $result =  $this->model->getOrders();

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
                $filename = uniqid("at").".".end($ext);
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

            $result = $this->model->genPreview($ingreso,$condicion,$fecha,$proyecto,$origen,$movimiento,$orden,$pedido,$guia,$autoriza,$cargo,$entidad,$details);

            echo $result;
        }

        function nuevoIngreso(){
            $ningreso = $_POST['ningreso'];
            $fecha = $_POST['fecha'];
            $origen = $_POST['origen'];
            $fcontable = $_POST['fcoontable'];
            $entidad = $_POST['entidad'];
            $guia = $_POST['guia'];
            $orden = $_POST['orden'];
            $pedido = $_POST['pedido'];
            $estado = $_POST['estado'];
            $autoriza = $_POST['autoriza'];
            $cod_mov = $_POST['cod_mov'];
            $num_mov = $_POST['num_mov'];
            $detalles = $_POST['detalles'];
            $series = $_POST['series'];
            $adjuntos = $_POST['adjuntos'];

            $result = $this->model->insertarIngreso($ningreso,$fecha,$origen,$fcontable,$entidad,$guia,$orden,$pedido,$estado,$autoriza,$cod_mov,$num_mov,$detalles,$series,$adjuntos);

            echo json_encode($result);
        }
        
    }
?>