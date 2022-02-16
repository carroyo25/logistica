<?php
    class Despacho extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros  = $this->model->getMainRecords();
            $this->view->modalidad  = $this->model->getParameters(22);
            $this->view->tipoenvio  = $this->model->getParameters(7);
            $this->view->almacenes  = $this->model->obtenerAlmacenes();
            $this->view->personal   = $this->model->obtenerPersonal();
            $this->view->motivos    = $this->model->getMovs();
            $this->view->entidad    = $this->model->obtenerEntidades();

            $this->view->render('despacho/index');
        }

        function notas(){
            $result = $this->model->llamarNotas();

            echo $result;
        }

        function notaId(){
            $idx = $_POST['idx'];

            $result = $this->model->llamarIngresoPorID($idx);

            echo json_encode($result);
        }

        function detallesIngresosId(){
            $idx = $_POST['idx'];

            $result = $this->model->llamarDetalleIngresoPorID($idx);

            echo $result;
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

            $result = $this->model->genPreview($ingreso,$condicion,$fecha,$proyecto,$origen,$movimiento,
                                                $orden,$pedido,$guia,$autoriza,$cargo,$entidad,$details,$tipo,
                                                $ruta);

            echo $result;
        }

        function nuevoNroGuia(){
            $result = $this->model->genNroGuia();

            echo $result;
        }

        function grabaSalida() {
            $id_ingreso = $_POST['id_ingreso'];
            $id_salida = $_POST['id_salida'];
            $id_entidad = $_POST['id_entidad'];
            $cod_almacen = $_POST['cod_almacen'];
            $cod_movimiento = $_POST['cod_movimiento'];
            $cod_autoriza = $_POST['cod_autoriza'];
            $cod_proyecto = $_POST['cod_proyecto'];
            $cod_area = $_POST['cod_area'];
            $cod_costos = $_POST['cod_costos'];
            $order_file = $_POST['order_file'];
            $cargo_almacen = $_POST['cargo_almacen'];
            $idorden = $_POST['idorden'];
            $idpedido = $_POST['idpedido'];
            $entidad = $_POST['entidad'];
            $docguia = $_POST['docguia'];
            $nrosalida = $_POST['nrosalida'];
            $movalma = $_POST['movalma'];
            $fechadoc = $_POST['fechadoc'];
            $fechacont = $_POST['fechacont'];
            $proyecto = $_POST['proyecto'];
            $solicita = $_POST['solicita'];
            $aprueba = $_POST['aprueba'];
            $almacen = $_POST['almacen'];
            $tipomov = $_POST['tipomov'];
            $nroped = $_POST['nroped'];
            $fecped = $_POST['fecped'];
            $nrord = $_POST['nrord'];
            $fecord = $_POST['fecord'];
            $espec = $_POST['espec'];
            $details = $_POST['details'];

           $result = $this->model->insertarSalida($id_ingreso,$id_salida,$id_entidad,$cod_almacen,$cod_movimiento,$cod_autoriza,
                                                $cod_proyecto,$cod_area,$cod_costos,$order_file,$cargo_almacen,$idorden,$idpedido,
                                                $entidad,$docguia,$nrosalida,$movalma,$fechadoc,$fechacont,$proyecto,$solicita,
                                                $aprueba,$almacen,$tipomov,$nroped,$fecped,$nrord,$fecord,$espec,$details);

            echo $result;
        }

        function salidaId() {
            $idx = $_POST['idx'];

            $result = $this->model->buscarSalidaId($idx);

            echo json_encode($result);
        }

        function detallesGuiaId() {
            $idx = $_POST['id'];

            $result = $this->model->buscarDellatesId($idx);

            echo $result;
        }

        function guiaRemision(){
            $cabecera = $_POST['cabecera'];
            $detalles = $_POST['detalles'];
            $salida = $_POST['salida'];

            $result = $this->model->grabarGuiaRemision($cabecera,$detalles,$salida);

            echo $result;
        }

        function terminaSalida(){
            $ingreso =  $_POST['ingreso'];
            $salida  = $_POST['salida'];
            $pedido = $_POST['pedido'];
            $detalles = $_POST['detalles'];

            $result = $this->model->cerrarSalida($ingreso,$salida,$pedido,$detalles);

            echo $result;
        }

        function actualizaPrincipal() {
            $result = $this->model->getMainRecords();
            echo $result;
        }

        function impresion(){
            if(file_exists("public/exe/print.exe")){
                echo system('PDFtoPrinter.exe');                
            }else {
                echo "no existe";
            }
        }
    }
?>