<?php
    class IngresoAlmacen extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->registros = $this->model->obtenerGuiasDespacho($_SESSION['id_user']);
            $this->view->render('ingresoalmacen/index');
        }
        
        function guiaId(){
            $idx = $_POST['idx'];

            $result = $this->model->obternerDespachoId($idx);

            echo json_encode($result);
        }

        function detallesDepacho(){
            $idx = $_POST['idx'];

            $result = $this->model->obternerDetallesDespacho($idx);

            echo $result;
        }

        function series(){
            $id = $_POST['id'];
            $ing = $_POST['ing'];

            $result = $this->model->consultarSeries($id,$ing);

            echo $result;
        }


        function ingreso() {
            $idx = $_POST['idx'];
            $detalles = $_POST['detalles'];
            $almacen = $_POST['almacen'];
            $guia = $_POST['guia'];
            $pedido = $_POST['pedido'];

            $result = $this->model->actualizarAlmacen($idx,$detalles,$almacen,$guia,$pedido);

            echo $result;
        }

        function registros() {
            $result = $this->model->obtenerGuiasDespacho($_SESSION['id_user']);

            echo $result;
        }
    }
?>