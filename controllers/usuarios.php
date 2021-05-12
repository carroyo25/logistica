<?php
    class Usuarios extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->usuarios = $this->model->getAllUsers();
            $this->view->render('usuarios/index');
        }
        
        function modulos(){
            $result = $this->model->getAllModules();
            echo $result;
        }

        function proyectos(){
            $result = $this->model->getallProys();

            echo $result;
        }

        function almacenes(){
            $result = $this->model->getAllAlmc();

            echo $result;
        }

        function newCode(){
            $result = $this->model->createCodeUser();
            echo $result;
        }

        function responsables(){
            $result = $this->model->getAllnames();
            echo $result;
        }

        function niveles(){
            $result = $this->model->getParameters(9);
            echo $result;
        }

        function estados(){
            $result = $this->model->getParameters(15);
            echo $result;
        }

        function newUser(){
            $cre = $_POST['cod_resp'];
            $cni = $_POST['cod_niv'];
            $ces = $_POST['cod_est'];
            $cod = $_POST['codigo'];
            $usr = $_POST['usuario'];
            $nom = $_POST['nombres'];
            $cla = $_POST['clave'];
            $des = $_POST['desde'];
            $has = $_POST['hasta'];
            $cca = $_POST['cod_cargo'];

            $datos = compact("cre","cni","ces","cod","usr","nom","cla","des","has","cca");


            $result = $this->model->insert($datos);

            echo $result;
        }

        function modules(){
            $datos = $_POST['data'];
            $this->model->insertModules($datos);
        }

        function proyects(){
            $datos = $_POST['data'];
            $this->model->insertProyects($datos);
        }

        //ingresar datos de los usuarios en los almacenes
        function warehouses(){
            $datos = $_POST['almacen'];
            $this->model->insertWarehouses($datos);
        }

        //ingresar datos de correos para aprobaciones
        function aprobaciones(){
            $datos = $_POST['data'];
            $this->model->insertAuthorization($datos);
        }

        function updateUser(){
            $cre = $_POST['cod_resp'];
            $cni = $_POST['cod_niv'];
            $ces = $_POST['cod_est'];
            $cod = $_POST['codigo'];
            $cca = $_POST['cod_cargo'];
            $usr = $_POST['usuario'];
            $nom = $_POST['nombres'];
            $cla = $_POST['clave'];
            $des = $_POST['desde'];
            $has = $_POST['hasta'];
            $old = $_POST['old_pass'];
            $id  = $_POST['cod_user'];

            $datos = compact("cre","cni","ces","cod","usr","nom","cla","des","has","old","id","cca");

            $result = $this->model->update($datos);

            echo $result;
        }

        function deleteUser(){
            $cod = $_POST['cod'];

            $result = $this->model->delete($cod);

            echo $result;
        }

        function allUsers(){
            $result = $this->model->getAllUsers();

            echo $result;
        }

        function showpass(){
            $cod = $_POST['data'];

            $result = $this->model->getPassById($cod);

            echo $result;
        }

        function userById(){
            $cod = $_POST['data'];

            $result = $this->model->getUserById($cod);

            echo json_encode($result);
        }

        function getModules(){
            $cod = $_POST['cod'];

            $result = $this->model->getModulesById($cod);

            echo $result;
        }

        function getProyects(){
            $cod = $_POST['cod'];

            $result = $this->model-> getProysById($cod);

            echo $result;
        }

        function correos(){
            $result = $this->model->getAllMails();

            echo $result;
        }

        function parametros(){
            $result = $this->model->getModAprob();
            echo $result;
        }

        function getWarehouse(){
            $cod = $_POST['cod'];

            $result = $this->model->getWareHousesById($cod);

            echo $result;
        }

        function getAutorization(){
            $cod = $_POST['cod'];

            $result = $this->model->getAuthorizationById($cod);

            echo $result;
        }
    }
?>