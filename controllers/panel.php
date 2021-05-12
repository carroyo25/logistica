<?php
    class Panel extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->user = $this->model->getUser();
            $this->view->menu = $this->model->acordeon($_SESSION['id_user']);
            $this->view->render('panel/index');
        }

        function getAccess(){
            $user = $_SESSION['id_user'];
            $result = $this->model->getVisibleActions($user);
            echo json_encode($result);
        }        
    }
?>