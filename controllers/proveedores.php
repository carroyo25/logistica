<?php
    class Proveedores extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->docstype   = $this->model->getParameters('1');
            $this->view->persontype = $this->model->getParameters('2');
            $this->view->entitype   = $this->model->getParameters('3');
            $this->view->pagos      = $this->model->getParameters('11');
            $this->view->listdpto   = $this->model->getUbigeo(1);
            $this->view->listprov   = $this->model->getUbigeo(2);
            $this->view->listdist   = $this->model->getUbigeo(3);
            $this->view->monedas    = $this->model->getMoneda();
            $this->view->paises     = $this->model->getCountry();
            $this->view->bancos     = $this->model->getBanks();
            $this->view->render('proveedores/index');
        }

        function checkExist()
        {
            $doc = $_POST['documento'];
            $raz = $_POST['razon'];

            $existe = $this->model->exist($doc,$raz);

            echo $existe;
        }

        function newProv() {
            $insert = false;

            $est  = $_POST['estado'];
            $idx  = $_POST['index_proveedor'];
            $cdoc = $_POST['codigo_documento'];
            $cper = $_POST['codigo_persona'];
            $cpai = $_POST['codigo_pais'];
            $cent = $_POST['codigo_entidad'];
            $pago = $_POST['codigo_pago'];
            $ubig = $_POST['ubigeo'];
            $ndoc = $_POST['numero_documento'];
            $razo = $_POST['razon_social'];
            $corr = $_POST['correo'];
            $apat = $_POST['ape_paterno'];
            $amat = $_POST['ape_materno'];
            $nomb = $_POST['nombre_proveedor'];
            $nomc = $_POST['nombre_comercial'];
            $tele = $_POST['telefono'];
            $dir  = $_POST['direccion'];
            $nro  = $_POST['numero'];
            $intr = $_POST['interior'];
            $zona = $_POST['zona'];
            $agpe = isset($_POST['agente_percepcion']) ? 1 : 0;
            $agre = isset($_POST['agente_recepcion']) ? 1 : 0;
            $porc = $_POST['porcentaje'];
            $mmin = $_POST['monto_minimo'];
            $cond = isset($_POST['condicion']) ? $_POST['condicion'] : 0;
            $nror = $_POST['nro_registro'];
            $cate = $_POST['categoria'];
            $situ = $_POST['situacion'];
            $empa = isset($_POST['empadronado']) ? $_POST['empadronado'] : 0;
            $cali = $_POST['calificacion'];

            $datos = compact("est","idx","cdoc","cper","cpai","cent","pago","ubig","ndoc","razo","corr","apat","amat","nomb",
                             "nomc","tele","dir","nro","intr","zona","agpe","agre","porc","mmin","cond","nror","cate",
                             "situ","empa","cali");

            $insert = $this->model->insert($datos);

            echo $insert;
        }

        function editProv() {
            $insert = false;

            $est  = $_POST['estado'];
            $idx  = $_POST['index_proveedor'];
            $cdoc = $_POST['codigo_documento'];
            $cper = $_POST['codigo_persona'];
            $cpai = $_POST['codigo_pais'];
            $cent = $_POST['codigo_entidad'];
            $pago = $_POST['codigo_pago'];
            $ubig = $_POST['ubigeo'];
            $ndoc = $_POST['numero_documento'];
            $razo = $_POST['razon_social'];
            $corr = $_POST['correo'];
            $apat = $_POST['ape_paterno'];
            $amat = $_POST['ape_materno'];
            $nomb = $_POST['nombre_proveedor'];
            $nomc = $_POST['nombre_comercial'];
            $tele = $_POST['telefono'];
            $dir  = $_POST['direccion'];
            $nro  = $_POST['numero'];
            $intr = $_POST['interior'];
            $zona = $_POST['zona'];
            $agpe = isset($_POST['agente_percepcion']) ? 1 : 0;
            $agre = isset($_POST['agente_recepcion']) ? 1 : 0;
            $porc = $_POST['porcentaje'];
            $mmin = $_POST['monto_minimo'];
            $cond = isset($_POST['condicion']) ? $_POST['condicion'] : 0;
            $nror = $_POST['nro_registro'];
            $cate = $_POST['categoria'];
            $situ = $_POST['situacion'];
            $empa = isset($_POST['empadronado']) ? $_POST['empadronado'] : 0;
            $cali = $_POST['calificacion'];

            $datos = compact("est","idx","cdoc","cper","cpai","cent","pago","ubig","ndoc","razo","corr","apat","amat","nomb",
                             "nomc","tele","dir","nro","intr","zona","agpe","agre","porc","mmin","cond","nror","cate",
                             "situ","empa","cali");

            $update = $this->model->update($datos);

            echo $update;
        }

        function remProv(){   
            $id = $_POST["id"];

            $salida = $this->model->delete($id);

            echo $salida;   
        }

        function newContacts() {
            $datos = $_POST['data'];
            $this->model->insertContacts($datos);
        }
        
        function newBanks() {
            $datos = $_POST['data'];
            $this->model->insertBanks($datos);
        }

        function listEnt() {
            $salida = $this->model->getAllEnt();

            echo $salida;
        }

        function seekById() {
            $id = $_POST['id'];

            $salida = $this->model->getEntById($id);
            
            echo json_encode($salida);
        }

        function getContactById() {
            $id = $_POST["id"];

            $salida = $this->model->showContacs($id);

            echo $salida;
        }

        function getBancktById() {
            $id = $_POST["id"];

            $salida = $this->model->showBanks($id);

            echo $salida;
        }
    }
?>