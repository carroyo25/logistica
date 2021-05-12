<?php
    class Grupo extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->flags = $this->model->getAccess(1,$_SESSION['id_user']);
            $this->view->totalGroups = $this->model->getTotalGroups();
            $this->view->render('grupo/index');
        }

        function newGroup(){
            $cod = $_POST['codigo'];
            $des = utf8_decode(strtoupper($_POST['descripcion']));
            $niv = "1";
            
            $datos = compact("cod","des","niv");

            $saveReg = $this->model->insertGroup($datos);

            if ($saveReg) {
                echo "Registro grabado";
            }else {
                echo "Error... registro no grabado";
            }
        }

        function modifyGroup(){
            $ind = $_POST['indice'];
            $des = utf8_decode(strtoupper($_POST['descripcion']));
            
            $datos = compact("ind","des");

            $saveReg = $this->model->updateGroup($datos);

            if ($saveReg) {
                echo "Registro actualizado";
            }else {
                echo "Error... registro no actualizado";
            }
        }

        function getLastGroupCode(){
            $codigo = $this->model->getGroupByLastCode();

            echo (int)$codigo + 1;
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

        function listGroups(){
            $listData = $this->model->getAllGroups();
            
            echo $listData;
        }
        
    }
?>