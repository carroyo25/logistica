<?php
    class Almacenes extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->listdpto   = $this->model->getUbigeo(1);
            $this->view->listprov   = $this->model->getUbigeo(2);
            $this->view->listdist   = $this->model->getUbigeo(3);
            $this->view->actives    = $this->model->getTotals(1);
            $this->view->inactives  = $this->model->getTotals(0);
            $this->view->render('almacenes/index');
        }

        function createCode(){
            $salida = $this->model->generateCode();

            echo str_pad($salida,3,"0",STR_PAD_LEFT);
        }

        function newReg(){
            $ubi = $_POST['ubigeo'];
            $coa = $_POST['codigo_almacen'];
            $dea = $_POST['descripcion_almacen'];
            $vti = $_POST['via_tipo'];
            $vin = $_POST['via_nombre'];
            $num = $_POST['numero'];
            $zon = $_POST['zona'];
            $est = $_POST['estado'];
            $dep = $_POST['dpto'];
            $pro = $_POST['prov'];
            $dis = $_POST['dist'];

            $datos = compact("ubi","coa","dea","vti","vin","num","zon","est","dep","pro","dis");

            $resultado = $this->model->insert($datos);
            
            echo $resultado;
        }

        function updReg(){
            $idx = $_POST['index_almacen'];
            $ubi = $_POST['ubigeo'];
            $coa = $_POST['codigo_almacen'];
            $dea = $_POST['descripcion_almacen'];
            $vti = $_POST['via_tipo'];
            $vin = $_POST['via_nombre'];
            $num = $_POST['numero'];
            $zon = $_POST['zona'];
            $est = $_POST['estado'];
            $dep = $_POST['dpto'];
            $pro = $_POST['prov'];
            $dis = $_POST['dist'];

            $datos = compact("idx","ubi","coa","dea","vti","vin","num","zon","est","dep","pro","dis");

            $resultado = $this->model->update($datos);
            
            echo $resultado;
        }

        function delReg(){
            $idx = $_POST['idx'];

            $salida = $this->model->delete($idx);

            echo $salida;
        }

        function listAll(){
            $salida = $this->model->getallItem();

            echo $salida;
        }

        function listById(){
            $idx = $_POST['idx'];

            $salida = $this->model->getItemById($idx);

            echo json_encode($salida);
        }
         
    }
?>