<?php
    class Familia extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->groupList = $this->model->getAllGroups();
            $this->view->totalFamilies=$this->model->getTotalFamily();
            $this->view->render('familia/index');
        }

        function getClassList() {
            $cod = $_POST['data'];

            $datos = compact("cod");

            $classList = $this->model->getAllClasesByCodGroup($datos);

            echo $classList;
        }

        function getFamilyCode() {
            $group = $_POST['codgroup'];
            $class = $_POST['codclass'];

            $datos = compact("group","class");

            $codigo = $this->model->genFamilyCode($datos);

            echo (int)$codigo + 1;

            return $codigo;
        }

        function existItem() {
            $item = utf8_decode(strtoupper($_POST['descripcion']));

            $datos = compact("item");

            $exist = $this->model->checkExist($datos);

            if ($exist == '0') {
                echo $exist;
            }else{
                echo $exist;
            }
        }

        function newFamily(){
            $gcod = $_POST['codigo_grupo'];
            $ccod = $_POST['codigo_clase'];
            $cfam = $_POST['codigo_familia'];
            $des = utf8_decode(strtoupper($_POST['descripcion']));
            $niv = '3';

            $datos = compact("gcod","ccod","cfam","des","niv");

            $savereg = $this->model->insert($datos);

            if ($savereg) {
                echo "Registro grabado";
            }else {
                echo "Error... registro no grabado";
            }
        }

        function modifyFamily() {
            $ind = $_POST['indice'];
            $des = utf8_decode(strtoupper($_POST['descripcion']));
            
            $datos = compact("ind","des");

            $saveReg = $this->model->update($datos);

            if ($saveReg) {
                echo "Registro actualizado";
            }else {
                echo "Error... registro no actualizado";
            }
        }

        function listFamilies(){
            $listData = $this->model->listAllClasses();
            
            echo $listData;
        }
        
    }
?>