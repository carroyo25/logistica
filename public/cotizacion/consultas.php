<?php
    require_once("connect.php");

    function obtenerProveedor($pdo,$codenti){
        $sql = "SELECT cnumdoc,crazonsoc FROM cm_entidad WHERE id_centi=? LIMIT 1";
        $statement = $pdo->prepare($sql);
		$statement -> execute(array($codenti));
		$result = $statement ->fetchAll();
		$rowaffect = $statement->rowCount($sql);
        $salida = "";

        if ($rowaffect > 0) {
            $salida = array("ruc"=> $result[0]["cnumdoc"],
                                "razon"=>$result[0]["crazonsoc"]);
        }

        return $salida;
    }

    function obtenerItems($pdo,$pedido) {
        try {
            //code...
        } catch (PDOException $th) {
            echo $th->getMessage();

            return false;
        }
    }
?>