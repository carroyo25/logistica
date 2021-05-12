<?php
    class Pedidos extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu         = $this->model->acordeon($_SESSION['id_user']);
            $this->view->costos       = $this->model->getAllCosts();
            $this->view->areas        = $this->model->getAllAreas();
            $this->view->transporte   = $this->model->getParameters(7);
            $this->view->tipo         = $this->model->getParameters(8);
            $this->view->proyecto     = $this->model->getAllProys();
            $this->view->registros    = $this->model->getAllUserRecords($_SESSION['user']);

            $this->view->render('pedidos/index');
        }
        
        function newRequest(){
            $requestCode = $this->model->genNewRequest();

            echo json_encode($requestCode);
        }

        function updRequest(){
            $cod = $_POST['cod'];

            $result = $this->model->getRecordById($cod);

            echo json_encode($result);
        }

        function updItems(){
            $cod = $_POST["cod"];
            $tipo = $_POST["tipo"];

            $result = $this->model->getDetailsById($cod,$tipo);

            echo $result;
        }

        function Items(){
            $tipo = $_POST['tipo'];
            $items = $this->model->getAllItems($tipo);
            
            echo $items;
        }

        function ItemsByLetter(){
            $tipo = $_POST['tipo'];
            $letra = $_POST['letra'];
            $items = $this->model->getAllItemsByLetter($tipo,$letra);
            
            echo $items;
        }

        function ItemsByWord(){
            $tipo = $_POST['tipo'];
            $palabra = $_POST['palabra'];
            $items = $this->model->getAllItemsByWord($tipo,$palabra);
            
            echo $items;
        }

        function getImportFile(){
            $temporal	 = $_FILES['uploadfile']['tmp_name'];
            $fileId      = uniqid().".xlsx";
            $tipo        = $_POST['operacion'];   

            if (move_uploaded_file($temporal,"public/import/".$fileId)) {
                echo "Archivo subido correctamente";
                $this->importItems($fileId,$tipo);
            }else {
                echo "No se pudo completar el proceso";
            }
        }

        function importItems($archivo,$tipo){
            require_once "public/PHPExcel/PHPExcel.php";
            
            $nombreArchivo = "public/import/".$archivo;
            $pedido = $tipo == 1 ? "BIEN":"SERV";
            
            $contador = 0;
            $salida="";

            $objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
            $objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            foreach ($objHoja as $iIndice=>$objCelda) 
            {
                $codigo         = $objCelda['B'];
                $descripcion    = $objCelda['C'];
                $unidad         = $objCelda['D'];
                $cantidad       = $objCelda['E'];

                if ($codigo != "" && $codigo !="CODIGO") {
                    
                    $contador++;
                    
                    $salida.= '<tr class="lh2rem">
                                <td class="con_borde centro"><a href="#"><i class="far fa-edit"></i></a></td>
                                <td class="con_borde centro"><a href="#"><i class="fas fa-eraser"></i></a></td>
                                <td class="con_borde drch pr20">'.$contador.'</td>
                                <td class="con_borde centro">'.$codigo.'</td>
                                <td class="con_borde pl10">'.$descripcion.'</td>
                                <td class="con_borde pl10">'.$unidad.'</td>
                                <td class="con_borde drch pr30">'.$cantidad.'</td>
                                <td class="con_borde proceso centro">PRO</td>
                                <td class="con_borde"></td>
                                <td class="con_borde"></td>
                                <td class="con_borde centro">'.$pedido.'</td>
                                <td class="con_borde oculto"></td>
                            </tr>';
                }
            }

            echo $salida;
        }

        function solicitantes(){
            $salida = $this->model->getAllNames();
            echo $salida;
        }

        function unidades(){
            $salida = $this->model->getAllUnits();
            echo $salida;
        }

        function grupos(){
            $salida = $this->model->getAllGroups();
            echo $salida;
        }

        function clases(){
            $grupo = $_POST['data'];
            $salida = $this->model->getAllClasesByCodGroup($grupo);
            echo $salida;
        }

        function familias(){
            $grupo = $_POST['datagrp'];
            $clase = $_POST['datacls'];
            $salida = $this->model->getAllFamiliasByCodGroupClass($grupo,$clase);
            echo $salida;
        }

        function newItem(){
            $tip = $_POST['itemTipo'];
            $cod = $_POST['codigo_item'];
            $dsc = $_POST['descrip'];
            $nco = $_POST['nom_com'];
            $ncr = $_POST['nom_cor'];
            $und = $_POST['cod_unidad'];
            $det = $_POST['detalles'];
            $mar = $_POST['marca'];
            $mod = $_POST['modelo'];
            $npa = $_POST['nro_parte'];
            $est = 1;

            $datos=compact("tip","cod","dsc","nco","ncr","und","det","mar","mod","npa","est");

            $retorno = $this->model->insertItem($datos);

            echo $retorno;          
        }

        function correos(){
            $result = $this->model->getAllMails();

            echo $result;
        }

        function pageNumbers(){
            $page = $_POST['number'];

            $result = $this->model->getTotalPages();

            echo $result;
        }

        function pageInitials() {
            $result = $this->model->getInitials();

            echo $result;

        }

        //sube los archivos adjuntos
        function uploadCotAtachs(){
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

        function registro(){
            $retorno = "";
            $codp = $_POST['cod_pedido'];
            $cpry = $_POST['cod_proy'];
            $ccos = $_POST['cod_cost'];
            $care = $_POST['cod_area'];
            $ctra = $_POST['cod_transporte'];
            $cest = $_POST['cod_estdoc'];
            $creg = $_POST['cod_registro'];
            $csol = $_POST['cod_solicitante'];
            $ctip = $_POST['cod_tipo'];
            $num  = $_POST['numero'];
            $fecr = $_POST['fecha'];
            $usr  = $_POST['usuario'];
            $conc = $_POST['concepto'];
            $tipo = $_POST['tipo'];
            $espe = $_POST['espec_items'];
            $est  = $_POST['estado'];
            $aten = $_POST['atencion'];

            $datos = compact("codp","cpry","ccos","care","ctra","cest","creg","csol","ctip","num","fecr","usr","conc","tipo","espe","est","aten");

            $retorno = $this->model->insertRequest($datos);
            
            echo $retorno;
        }

        function actualiza(){
            $retorno = "";
            $codp = $_POST['cod_pedido'];
            $cpry = $_POST['cod_proy'];
            $ccos = $_POST['cod_cost'];
            $care = $_POST['cod_area'];
            $ctra = $_POST['cod_transporte'];
            $cest = $_POST['cod_estdoc'];
            $creg = $_POST['cod_registro'];
            $csol = $_POST['cod_solicitante'];
            $ctip = $_POST['cod_tipo'];
            $num  = $_POST['numero'];
            $fecr = $_POST['fecha'];
            $usr  = $_POST['usuario'];
            $conc = $_POST['concepto'];
            $tipo = $_POST['tipo'];
            $espe = $_POST['espec_items'];
            $est  = $_POST['estado'];
            $aten = $_POST['atencion'];

            $datos = compact("codp","cpry","ccos","care","ctra","cest","creg","csol","ctip","num","fecr","usr","conc","tipo","espe","est","aten");

            $retorno = $this->model->updateRequest($datos);
            
            echo $retorno;
        }

        public function saveItemsTable(){
            $datos = $_POST['data'];
            $this->model->insertItemsTable($datos);
        } 
        
        public function saveAtachs(){
            $datos = $_POST['data'];
            $this->model->insertAtachs($datos);
        }

        //generar los pedidos
        function genPreview(){
            require_once("public/libsrepo/repopedidos.php");

            $datos = json_decode($_POST['data']);
            $filename = "public/temp/".uniqid().".pdf";

            if(file_exists($filename))
                unlink($filename);

            $num = $datos[0]->numero;
            $fec = $datos[0]->fecha;
            $usr = $datos[0]->usuario;
            $pry = $datos[0]->proyecto;
            $are = $datos[0]->area;
            $cos = $datos[0]->costos;
            $tra = $datos[0]->transporte;
            $con = $datos[0]->concepto;
            $sol = $datos[0]->solicitante;
            $reg = $datos[0]->registro;
            $esp = $datos[0]->espec_items;
            $dti = $datos[0]->doctip;
            $mmt = "";
            $cla = "NORMAL";
            $msj = "VISTA PREVIA";

            
            $pdf = new PDF($num,$fec,$pry,$cos,$are,$con,$mmt,$cla,$tra,$usr,$sol,$reg,$esp,$dti,$msj,"");
		    $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetWidths(array(10,15,70,8,10,17,15,15,15,15));
            $pdf->SetFont('Arial','',5);
            $lc = 0;
            $rc = 0; 

            $nreg = count($datos);

            for($i=1;$i<=$nreg;$i++){
			    $pdf->SetAligns(array("L","L","L","L","R","L","L","L","L","L"));
                $pdf->Row(array($datos[$rc]->item,
                                $datos[$rc]->coditem,
                                utf8_decode($datos[$rc]->descripcion),
                                $datos[$rc]->unidad,
                                $datos[$rc]->cantidad,
                                '',
                                '',
                                '',
                                '',
                                ''));
                
                $lc++;
                $rc++;

                if ($lc == 52) {
				    $pdf->AddPage();
				    $lc = 0;
			    }	
		    }

            $pdf->Output($filename,'F');
            
            echo $filename;
        }

        //actualizar la lista de pedidos
        function mainList(){
            $retorno = $this->model->getAllUserRecords($_SESSION['user']);

            echo $retorno;
        }

        //enviar el mail y el adjunto a los correos seleccionados
        function mailProcess(){
            $datos = $_POST['data'];

            $retorno = $this->model->sendmails($datos);

            echo $retorno;
        }
    }
    
?>