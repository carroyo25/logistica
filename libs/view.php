<?php
    class View{
        function __construct()
        {
            //echo "<p>Base View</p>";
        }

        function render($nombre){
            require 'views/' .$nombre. '.php';
        }
    }
?>