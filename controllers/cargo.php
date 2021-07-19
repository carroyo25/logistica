<?php
    class Cargo extends Controller{
        function __construct()
        {
            parent::__construct();
        }

        function render(){
            $this->view->menu       = $this->model->acordeon($_SESSION['id_user']);
            $this->view->render('cargo/index');
        }
        
    }
?>