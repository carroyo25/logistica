<?php
    class Bienes extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->unidades = $this->model->listUnids();
            $this->view->iniciales = $this->model->getInitials();
            $this->view->render('bienes/index');
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

        function newItem(){

            $saveItem = true;
            
            $derc = $_POST['descripcion'];
            $ncom = $_POST['nombre_comercial'];
            $ncor = $_POST['nombre_corto'];
            $unme = $_POST['unidad_medida'];
            $codi = $_POST['codigo'];
            $ccat = $_POST['grupo'].$_POST['clase'].$_POST['familia'];
            $prsu = $_POST['producto_sunat'];
            $codb = $_POST['codigo_barra'];
            $deta = $_POST['detalles'];
            $marc = $_POST['marca'];
            $mode = $_POST['modelo'];
            $medi = $_POST['medida'];
            $colo = $_POST['color'];
            $peso = $_POST['peso'];
            $volu = $_POST['volumen'];
            $nopa = $_POST['nro_parte'];
            $coan = $_POST['cod_anexo'];
            $orig = isset($_POST['origen']) ? 1 : 0;
            $dest = isset($_POST['destino'])  ? 1 : 0;
            $unse = isset($_POST['unidades_secundarias']) ? 1 : 0;
            $lote = isset($_POST['lotes']) ? 1 : 0;
            $srun = isset($_POST['series_unicas']) ? 1 : 0;
            $cond = isset($_POST['controlado_digemid']) ? 1 : 0;
            $prre = isset($_POST['producto_relacionado']) ? 1 : 0;
            $afic = isset($_POST['afecto_icbper']) ? 1 : 0;
            $rees = isset($_POST['reg_especial']) ? 1 : 0;
            $rufo = $_POST['ruta_foto'];
            $coun = $_POST['cod_unidad'];
            $esta = $_POST['estado'];
            $tipr = $_POST['tipo_producto'];

            $datos = compact("derc","ncom","ncor","unme","codi","ccat","prsu","codb","deta","marc","mode","medi","colo","peso","volu",
                            "nopa","coan","orig","dest","unse","lote","srun","cond","prre","afic","rees","rufo","coun","esta","tipr");

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
            $marc = $_POST['marca'];
            $mode = $_POST['modelo'];
            $medi = $_POST['medida'];
            $colo = $_POST['color'];
            $peso = $_POST['peso'];
            $volu = $_POST['volumen'];
            $nopa = $_POST['nro_parte'];
            $coan = $_POST['cod_anexo'];
            $orig = isset($_POST['origen']) ? 1 : 0;
            $dest = isset($_POST['destino'])  ? 1 : 0;
            $unse = isset($_POST['unidades_secundarias']) ? 1 : 0;
            $lote = isset($_POST['lotes']) ? 1 : 0;
            $srun = isset($_POST['series_unicas']) ? 1 : 0;
            $cond = isset($_POST['controlado_digemid']) ? 1 : 0;
            $prre = isset($_POST['producto_relacionado']) ? 1 : 0;
            $afic = isset($_POST['afecto_icbper']) ? 1 : 0;
            $rees = isset($_POST['reg_especial']) ? 1 : 0;
            $rufo = $_POST['ruta_foto'];
            $coun = $_POST['cod_unidad'];
            $id   = $_POST['index_producto'];

            $datos = compact("derc","ncom","ncor","unme","deta","marc","mode","medi","colo","peso","volu","nopa","coan","orig",
                                "dest","unse","lote","srun","cond","prre","afic","rees","rufo","coun","id");

            $updateItem = $this->model->update($datos);

            echo $updateItem;
        }

        function deleteItem(){
            $cod = $_POST['codigo'];
            
            $deleteItem = $this->model->delete($cod);
            
            echo $deleteItem;
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

        function uploadImg(){
            $temporal	= $_FILES['image_file']['tmp_name'];
            $type       = $_FILES['image_file']['type'];
            $nombre     = "";
             
            if ($temporal != "") {
                switch ($type) {
                    case 'image/jpeg':
                        $original = imagecreatefromjpeg($temporal);
                        $ext = 'jpg';
                        break;
                    case 'image/png':
                        $original 	= imagecreatefrompng($temporal);
                        $ext = 'png';
                        break;
                }
    
                $nombre = uniqid().".".$ext;
    
                $ancho_original	= imagesx($original);
                $alto_original	= imagesy($original);
    
                //crear el lienzo vacio 520*400
                $ancho_nuevo 	= 520;
                $alto_nuevo		= 400; //round($ancho_nuevo * $alto_original / $ancho_original);
                 
                $copia = imagecreatetruecolor($ancho_nuevo,$alto_nuevo);
    
                //copiar original -> copia
                imagecopyresampled($copia, $original, 0, 0, 0, 0, $ancho_nuevo, 400, $ancho_original, $alto_original);
    
                //exportar guardar imagen
                imagejpeg($copia,"public/productos/".$nombre,50);
                 
                //elimina los datos temporales
                imagedestroy($original);
                imagedestroy($copia);
                 
                //devuelve el nombre de la foto

                echo $nombre;
            }
            
        }

        function existCode(){
            $cod = $_POST['itemCode'];

            $datos = compact("cod");

            $exist = $this->model->verifyCode($datos);

            echo $exist;
        }

        function chekExistItem(){

        }

        function history(){
            $datos = $_POST['data'];

            $this->model->insertDocs($datos);
        }

        function getAllItems(){
            
            $salida = $this->model->listItems();

            echo $salida;
        }

        function searchById(){
            $cod = $_POST['codigo'];

            $datos = compact("cod");

            $resultado = $this->model->listItemById($datos);

            echo json_encode($resultado);
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

        function ItemsByWord(){
            $tipo = $_POST['tipo'];
            $palabra = $_POST['palabra'];
            $items = $this->model->getAllItemsByWord($tipo,$palabra);
            
            echo $items;
        }

        function ItemsByLetter(){
            $tipo = $_POST['tipo'];
            $letra = $_POST['letra'];
            $items = $this->model->getAllItemsByLetter($tipo,$letra);
            
            echo $items;
        }
    }
?>