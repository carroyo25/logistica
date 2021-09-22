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

            $result = $this->model->genPreview($ingreso,$condicion,$fecha,$proyecto,$origen,$movimiento,$orden,$pedido,$guia,$autoriza,$cargo,$entidad,$details,$tipo);

            echo $result;
        }

        /*function guiaRemision(){
            $codmodalidadguia = $_POST['codmodalidadguia'];
            $codtipoguia = $_POST['codtipoguia'];
            $codalmacendestino = $_POST['codalmacendestino'];
            $codalmacenorigen = $_POST['codalmacenorigen'];
            $codautoriza = $_POST['codautoriza'];
            $coddespacha = $_POST['coddespacha'];
            $coddestinatario = $_POST['coddestinatario'];
            $codentidad = $_POST['codentidad'];
            $codchofer = $_POST['codchofer'];
            $serieguia = $_POST['serieguia'];
            $nroguia = $_POST['nroguia'];
            $packinlist = $_POST['packinlist'];
            $fecemin = $_POST['fecemin'];
            $feenttrans = $_POST['feenttrans'];
            $ruc = $_POST['ruc'];
            $razondest = $_POST['razondest'];
            $direccdest = $_POST['direccdest'];
            $almorg = $_POST['almorg'];
            $viatiporg = $_POST['viatiporg'];
            $vianomorg = $_POST['vianomorg'];
            $nroorg = $_POST['nroorg'];
            $intorg = $_POST['intorg'];
            $zonaorg = $_POST['zonaorg'];
            $deporg = $_POST['deporg'];
            $distorg = $_POST['distorg'];
            $provorg = $_POST['provorg'];
            $ubigorg = $_POST['ubigorg'];
            $mottrans = $_POST['mottrans'];
            $modtras = $_POST['modtras'];
            $tenvio = $_POST['tenvio'];
            $bultos = $_POST['bultos'];
            $peso = $_POST['peso'];
            $observaciones = $_POST['observaciones'];
            $autoriza = $_POST['autoriza'];
            $despacha = $_POST['despacha'];
            $destinatario = $_POST['destinatario'];
            $raztransp = $_POST['raztransp'];
            $ructransp = $_POST['ructransp'];
            $dirtransp = $_POST['dirtransp'];
            $representate = $_POST['representate'];
            $almdest = $_POST['almdest'];
            $vianomodest = $_POST['vianomodest'];
            $intdest = $_POST['intdest'];
            $zondest = $_POST['zondest'];
            $viatipodest = $_POST['viatipodest'];
            $nrodest = $_POST['nrodest'];
            $depdest = $_POST['depdest'];
            $distdest = $_POST['distdest'];
            $provdest = $_POST['provdest'];
            $ubigdest = $_POST['ubigdest'];
            $dnicond = $_POST['dnicond'];
            $detcond = $_POST['detcond'];
            $licencia = $_POST['licencia'];
            $certificado = $_POST['certificado'];
            $marca = $_POST['marca'];
            $placa = $_POST['placa'];
            $configveh = $_POST['configveh'];
            $proyecto = $_POST['proyecto'];
            $costos = $_POST['costos'];
            $salida = $_POST['salida'];
            $detalles = $_POST['details'];

            $result = $this->model->guardarGuia($codmodalidadguia,$codtipoguia,$codalmacendestino,$codalmacenorigen,$codautoriza,$coddespacha,
                                                $coddestinatario,$codentidad,$codchofer,$serieguia,$nroguia,$packinlist,$fecemin,$feenttrans,
                                                $ruc,$razondest,$direccdest,$almorg,$viatiporg,$vianomorg,$nroorg,$intorg,$zonaorg,$viatipodest,$nrodest,$deporg,$distorg,
                                                $provorg,$ubigorg,$mottrans,$modtras,$tenvio,$bultos,$peso,$observaciones,$autoriza,$despacha,$destinatario,
                                                $raztransp,$ructransp,$dirtransp,$representate,$almdest,$vianomodest,$intdest,$zondest,$depdest,$distdest,
                                                $provdest,$ubigdest,$dnicond,$detcond,$licencia,$certificado,$marca,$placa,$configveh,$proyecto,$costos,
                                                $salida,$detalles);

            echo $result;
        }*/

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
            //$documento = $_POST['documento'];
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
    }
?>