<?php
    class Aprobacion extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros    = $this->model->getAllUserRecords();
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
            $msj = "APROBADO";
            $apr = $datos[0]->aprueba;

            
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
            
            echo $filename;
        }

        /*function updateReg(){
            $cod = $_POST['cod'];
            $usr = $_POST['iu'];

            $result = $this->model->changeStatus($cod,$usr);

            echo $result;
        }

        function updateDetails(){
            $datos = $_POST['data'];

            $result = $this->model->changeDetailStatus($datos);

            echo $result;
        }*/

        function aprobar(){
            $cod = $_POST['cod'];
            $usr = $_POST['iu'];
            $datos = $_POST['detalles'];

            $result = $this->model->aprobarItems($cod,$usr,$datos);

            echo $result;
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
            $retorno = $this->model->getAllUserRecords();

            echo $retorno;
        }
    }
?>