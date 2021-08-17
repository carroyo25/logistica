<?php 
    $tipo = $_POST['tipo'];
    $identi = $_POST['identi'];

    if ($tipo == "cotizacion") {
        $target_dir = "../proformas/";
        $pedido = $_POST['codped']."_";
        $cotizacion = $_POST['ruc']."_";
        $entidad = $_POST['cotizacion'].'.pdf'; 
        $target_file = $target_dir.$pedido.$cotizacion.$entidad;
    }else {
        $target_dir = "../manuales/";
        $item = $identi.'_'.$_POST['item'].'.pdf';
        $target_file = $target_dir . $item;
    }
    
    $estado = false;
    $mensaje = "Disculpe, hubo un error al subir el archivo.";

    if ($_FILES["uploadfile"]["size"] > 400000) {
        $mensaje = "El archivo es demasiado grande.";
    }else {
        if (move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $target_file)) {
            $mensaje =  "El archivo fue subido correctamente.";
            $estado = true;
        }
    }
    
    $salida_json = array("mensaje"=>$mensaje,
                        "estado"=>$estado,
                        "archivo"=>$target_file,
                        "tipo"=>$tipo);

    echo json_encode($salida_json);
?>