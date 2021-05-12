<?php
    class Main extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->render('main/index');
        }

        function loginUser(){
            $user   = $_POST['user'];
            $clave  = $_POST['pass'];

            $result = $this->model->getByUserPass($user, $clave);

            echo json_encode($result);
        }
    }
?>