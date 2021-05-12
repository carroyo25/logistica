<?php
    class Adjudicacion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros  = $this->model->getMainRecords(); //aca debo poner los primeros 20 registros
            $this->view->monedas    = $this->model->getCurrency();
            $this->view->pagos      = $this->model->getParameters(11);
            $this->view->entrega    = $this->model->getParameters(16);
            $this->view->lugar      = $this->model->getWareHouse();
            $this->view->render('adjudicacion/index');
        }

        function regById(){
            $cod = $_POST['data'];

            $return = $this->model->getRegById($cod);

            echo json_encode($return);
        }

        //detalles del pedido
        function detailsById(){
            $cod = $_POST['cod'];
            $tip = $_POST['tip'];

            $return = $this->model->getDetailsById($cod,$tip);

            echo $return;
        }
        
        function entities(){
            $cod = $_POST['cod'];
            $tipo = $_POST['tipo'];

            $result = $this->model->getEntities($cod,$tipo);

            echo json_encode($result);
        }

        function preview(){
            $pedido         = $_POST['pedido'];
            $proveedor      = $_POST['proveedor'];
            $idmoneda       = $_POST['idmoneda'];
            $tipo           = $_POST['tipo'];
            $condicion      = $_POST['condicion'];
            $fecha          = $_POST['fecha'];
            $moneda         = $_POST['moneda'];
            $plazo_entrega  = $_POST["plazo_entrega"];
            $lugar_entrega  = $_POST["lugar_entrega"];
            $cotizacion     = $_POST["cotizacion"];
            $pago           = $_POST["pago"];
            $importe        = $_POST["importe"];
            $fecha_entrega  = $_POST["fentrega"];
            
            $detalles = $_POST['details'];

            $result = $this->model->genPreview($pedido,$proveedor,$idmoneda,$tipo,$condicion,$fecha,$detalles,
                                                $moneda,$plazo_entrega,$lugar_entrega,$cotizacion,$pago,$importe,
                                                $fecha_entrega);

            echo $result;
        }

        function OCSHeader(){
            $pedido         = $_POST['pedido'];
            $fecha          = $_POST['fecha'];
            $tipo           = $_POST['tipo'];
            $proveedor      = $_POST['proveedor'];
            $moneda         = $_POST['moneda'];
            $importe        = $_POST['importe'];
            $almacen        = $_POST['almacen'];
            $proyecto       = $_POST['proyecto'];
            $costos         = $_POST['costos'];
            $area           = $_POST['area'];
            $espec          = $_POST['espec'];
            $solicitante    = $_POST['solicitante'];
            $pago           = $_POST['pago'];
            $entrega        = $_POST['entrega'];
            $fentrega       = $_POST['fentrega'];
            $cotizacion     = $_POST['cotizacion'];
            $detalles       = $_POST['details'];
            $atencion       = $_POST['atencion'];
            $transporte     = $_POST['transporte'];

            $datos = compact('pedido',
                             'fecha',
                             'tipo',
                             'proveedor',
                             'moneda',
                             'importe',
                             'almacen',
                             'proyecto',
                             'costos',
                             'area',
                             'espec',
                             'solicitante',
                             'pago',
                             'entrega',
                             'fentrega',
                             'cotizacion',
                             'detalles',
                             'atencion',
                             'transporte');

            $result = $this->model->insertOCSHeader($datos);

            echo $result;
        }

        //Subir cotizaciones
        function uploadsAtachs(){
            // Count total files
            $countfiles = count($_FILES['uploadAtach']['name']);
            $files = array();
            // Looping all files
            for($i=0;$i<$countfiles;$i++){
                $ext = explode('.',$_FILES['uploadAtach']['name'][$i]);
                $filename = uniqid("at").".".end($ext);
                $return = $filename ."~".$_FILES['uploadAtach']['name'][$i];

                // Upload file
                move_uploaded_file($_FILES['uploadAtach']['tmp_name'][$i],'public/cotizaciones/'.$filename);
                array_push($files,$return);
            }

            $json_string = json_encode($files);
            echo $json_string;
        }

        //grabar las corizaciones en la base de datos
        function saveCots(){
            $datos = $_POST['data'];
            
            $result = $this->model->insertCots($datos);

            echo $result;
        }

        function uploadsAtachsItems(){
            $files = array();
            $doc = array("sds","cc1","hds","cc2","pdp");
            $idx = 1;
            $file = "";
            $datos = [];
            $idpro = $_POST['fileProdId'];

            //para subir todos los archivos
            for ($i=0; $i < 5; $i++) { 
                $file = "file".$idx;

                if ( $_FILES[$file]['name'] != '' ) {
                    $ext = explode('.',$_FILES[$file]['name']);
                    $fileName = uniqid($doc[$i]).".".end($ext);
                    //move_uploaded_file($_FILES[$file]['tmp_name'],'public/manuales/'.$fileName);
                    $files['nombre_guardar'] = $fileName;
                    $files['nombre_archivo'] = $_FILES[$file]['name'];
                    $files['codigo_producto'] = $idpro;
                    array_push($datos,$files); 
                } 
                
                $idx++;
            }
            
            $nfiles = count($datos);
            $json_string = json_encode($datos);
            $this->model->uploadfilesItems($json_string,$idpro,$nfiles);
        }


        function itemClose(){
            $item = $_POST['data'];

            $this->model->finishCot($item);
        }

    }
?>