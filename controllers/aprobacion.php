<?php
    class Aprobacion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros  = $this->model->getAllUserRecords($_SESSION['id_user']);
            $this->view->aprobados  = $this->model->resumen($_SESSION['id_user'],4);
            $this->view->pendientes = $this->model->resumen($_SESSION['id_user'],3);
            $this->view->anulados   = $this->model->resumen($_SESSION['id_user'],18);
            $this->view->render('aprobacion/index');
        }
        
        function regById(){
            $cod = $_POST['data'];

            $return = $this->model->getRegById($cod);

            echo json_encode($return);
        }

        function detailsById()
        {
            $cod = $_POST['data'];

            $return = $this->model->getDetailsById($cod);

            echo $return;
        }

        function correos(){
            $result = $this->model->getAllMails();

            echo $result;
        }

        //generar los pedidos
        function genPreview(){
            require_once("public/libsrepo/repopedidos.php");
            
            $cabecera = $_POST['cabecera'];
            $datos = json_decode($_POST['detalles']);
            $filename = "public/temp/".uniqid().".pdf";

            if(file_exists($filename))
                unlink($filename);

            $num = $cabecera['numero'];
            $fec = $cabecera['fecha'];
            $usr = $cabecera['usuario'];
            $pry = $cabecera['proyecto'];
            $are = $cabecera['area'];
            $cos = $cabecera['costos'];
            $tra = $cabecera['transporte'];
            $con = $cabecera['concepto'];
            $sol = $cabecera['solicitante'];
            $reg = $cabecera['registro'];
            $esp = $cabecera['espec_items'];
            $dti = $cabecera['cod_tipo'];
            $mmt = "";
            $cla = $cabecera['atencion'] == 2 ? "URGENTE":"NORMAL";
            $msj = $_POST['mensaje'];
            $apr = strtoupper($_POST['aprueba']);

            $pdf = new PDF($num,$fec,$pry,$cos,$are,$con,$mmt,$cla,$tra,$usr,$sol,$reg,$esp,$dti,$msj,$apr);
		    $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetWidths(array(10,15,70,8,10,17,15,15,15,15));
            $pdf->SetFont('Arial','',5);
            $lc = 0;
            $rc = 0; 

            $nreg = count($datos);

            for($i=1;$i<=$nreg;$i++){
                $cantidad = $datos[$rc]->cantapr != "" ? $datos[$rc]->cantapr :  $datos[$rc]->cantped; 

			    $pdf->SetAligns(array("L","L","L","L","R","L","L","L","L","L"));
                $pdf->Row(array($datos[$rc]->item,
                                $datos[$rc]->coditem,
                                utf8_decode($datos[$rc]->descripcion),
                                $datos[$rc]->unidad,
                                $cantidad,
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
            
            echo json_encode(array("response"=>true,
                                    "archivo"=>$filename));
        }

        //actualizar el pedido y los itmes
        function apruebaPedido(){
            $usuario = $_POST['usuario'];
            $pedido = $_POST['pedido'];
            $correos = $_POST['correos'];
            $detalles = $_POST['items'];

            $result = $this->model->cambiarEstatus($usuario,$pedido,$correos,$detalles);

            echo json_encode($result);
        }

        //enviar el mail y el adjunto a los correos seleccionados
        function mailProcess(){
            $datos = $_POST['data'];

            $retorno = $this->model->sendmails($datos);

            echo $retorno;
        }

        //ver los adjuntos en la nota de pedido
        function attachs(){
            $cod = $_POST['cod'];

            $retorno = $this->model->getAtachs($cod);

            echo $retorno;
        }

        //repinta el cuadro de pedidos
        function mainList(){
            
            $retorno = $this->model->getAllUserRecords($_SESSION['id_user']);

            echo $retorno;
        }
    }
?>