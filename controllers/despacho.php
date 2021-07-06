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

        function guiaRemision(){
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
            $detalles = $_POST['details'];

            $result = $this->model->guardarGuia($codmodalidadguia,$codtipoguia,$codalmacendestino,$codalmacenorigen,$codautoriza,$coddespacha,
                                                    $coddestinatario,$codentidad,$codchofer,$serieguia,$nroguia,$packinlist,$fecemin,$feenttrans,
                                                    $ruc,$razondest,$direccdest,$almorg,$viatiporg,$vianomorg,$nroorg,$intorg,$zonaorg,$viatipodest,$deporg,$distorg,
                                                    $provorg,$ubigorg,$mottrans,$modtras,$tenvio,$bultos,$peso,$observaciones,$autoriza,$despacha,$destinatario,
                                                    $raztransp,$ructransp,$dirtransp,$representate,$almdest,$vianomodest,$intdest,$zondest,$depdest,$distdest,
                                                    $provdest,$ubigdest,$dnicond,$detcond,$licencia,$certificado,$marca,$placa,$configveh,$proyecto,$detalles);

            echo $result;
        }

        function nuevoNroGuia(){
            $result = $this->model->genNroGuia();

            echo $result;
        }
    }
?>