<?php
    class Servicios extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->unidades = $this->model->listUnids();
            $this->view->render('servicios/index');
        }
        
        function newItem(){

            $saveItem = true;
            
            $derc = $_POST['descripcion'];
            $ncom = $_POST['nombre_comercial'];
            $ncor = $_POST['nombre_corto'];
            $codi = $_POST['codigo'];
            $ccat = $_POST['grupo'].$_POST['clase'].$_POST['familia'];
            $prsu = $_POST['producto_sunat'];
            $codb = $_POST['codigo_barra'];
            $deta = $_POST['detalles'];
            $orig = isset($_POST['origen']) ? 1 : 0;
            $dest = isset($_POST['destino'])  ? 1 : 0;
            $unse = isset($_POST['unidades_secundarias']) ? 1 : 0;
            $lote = isset($_POST['lotes']) ? 1 : 0;
            $srun = isset($_POST['series_unicas']) ? 1 : 0;
            $cond = isset($_POST['controlado_digemid']) ? 1 : 0;
            $prre = isset($_POST['producto_relacionado']) ? 1 : 0;
            $afic = isset($_POST['afecto_icbper']) ? 1 : 0;
            $rees = isset($_POST['reg_especial']) ? 1 : 0;
            $coun = $_POST['cod_unidad'];
            $esta = $_POST['estado'];
            $tipr = $_POST['tipo_producto'];

            $datos = compact("derc","ncom","ncor","codi","ccat","prsu","codb","deta","orig","dest","unse","lote","srun","cond","prre","afic","rees","coun","esta","tipr");

            $saveItem = $this->model->insert($datos);

            echo $saveItem;
        }

        function updateItem(){
            $saveItem = true;
            
            $derc = $_POST['descripcion'];
            $ncom = $_POST['nombre_comercial'];
            $ncor = $_POST['nombre_corto'];
            $unme = $_POST['cod_unidad'];
            $deta = $_POST['detalles'];
            $orig = isset($_POST['origen']) ? 1 : 0;
            $dest = isset($_POST['destino'])  ? 1 : 0;
            $unse = isset($_POST['unidades_secundarias']) ? 1 : 0;
            $lote = isset($_POST['lotes']) ? 1 : 0;
            $srun = isset($_POST['series_unicas']) ? 1 : 0;
            $cond = isset($_POST['controlado_digemid']) ? 1 : 0;
            $prre = isset($_POST['producto_relacionado']) ? 1 : 0;
            $afic = isset($_POST['afecto_icbper']) ? 1 : 0;
            $rees = isset($_POST['reg_especial']) ? 1 : 0;
            $id   = $_POST['index_producto'];

            $datos = compact("derc","ncom","ncor","unme","deta","orig","dest","unse","lote","srun","cond","prre","afic","rees","id");

            $updateItem = $this->model->update($datos);

            echo $updateItem;
        }

        function deleteItem(){
            $cod = $_POST['codigo'];
            
            $deleteItem = $this->model->delete($cod);
            
            echo $deleteItem;
        }

        function listGroups(){
            $listData = $this->model->getAllGroups();
            
            echo $listData;
        }

        function genNewCode(){
            $cod = substr($_POST['codigo'],0,8) ;

            $datos = compact("cod");

            $codigo = $this->model->generateCode($datos);

            echo str_pad($codigo+1,4,"0",STR_PAD_LEFT) ;
        }

        function getDataFile(){
            $temporal	 = $_FILES['doc_file']['tmp_name'];
            $fileId      = $_POST['codfile'];

            if (move_uploaded_file($temporal,"public/docs/".$fileId)) {
                echo "Archivo subido correctamente";
            }else {
                echo "No se pudo completar el proceso";
            }
        }

        function getAllItems(){
            
            $salida = $this->model->listItems();

            echo $salida;
        }

        function searchById(){
            $cod = $_POST['codigo'];

            $datos = compact("cod");

            $resultado = $this->model->listItemById($datos);

            echo json_encode($resultado) ;
        }

        function listDocs(){
            $cod = $_POST['data'];
            $resultado = $this->model->showDocs($cod);
            echo $resultado ;
        }

        function listHistory(){
            $cod = $_POST['data'];
            $resultado = $this->model->showHistory( $cod);
            echo $resultado ;
        }
    }
?>