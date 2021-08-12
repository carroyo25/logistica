<?php 
    if(isset($_FILES["uploadfile"]["type"]))
    { 
        echo "se paso el adjunto";
    }else{
        echo "No se paso el adjunto";
    }
    
?>