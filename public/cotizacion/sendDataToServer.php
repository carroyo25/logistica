<?php 
    require_once("connect.php");

    $datos = $_POST['datos'];
    $detalles = json_decode($_POST['detalles']);

    try {
        $incluye = $datos['igv'] != 0 ? 1 : 0;

        $sql = "INSERT INTO lg_proformacab SET  id_regmov = ?,id_centi = ?,ffechadoc = ?,ffechaplazo = ?,cnumero = ?,
                                                ccondpago = ?,ccondentrega = ?,ncodmon = ?,nafecIgv = ?,nsubtot = ?,
                                                nigv = ?,ntotal = ?,cobserva = ?,nflgactivo = 1";
        $statement = $pdo->prepare($sql);
		$statement -> execute(array($datos['codped'],$datos['identi'],$datos['fechaDoc'],$datos['fechaVig'],$datos['cotizacion'],
                                $datos['condicion'],$datos['plazo'],$datos['moneda'],$incluye,$datos['subtotal'],
                                $datos['igv'],$datos['total'],$datos['observaciones']));
        
        $rowaffect = $statement->rowCount($sql);

        if ($rowaffect > 0) {
            grabarDetalles($pdo,$detalles);
            calificarProveedor($pdo,$datos['codped'],$datos['identi']);

            return true;
        }
    } catch (PDOException $th) {
        echo $th->getMessage();
        return false;
    }

    function grabarDetalles($pdo,$detalles) {
        $nreg = count($detalles);

        for ($i=0; $i < $nreg; $i++) { 
            try {
                $sql = "INSERT INTO lg_proformadet SET id_regmov=?,id_cprod=?,niddet=?,cantcoti=?,precunit=?,ffechaent=?,cdetalle=?,id_centi=?,nflgactivo = 1";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($detalles[$i]->pedido,
                                          $detalles[$i]->codprod,
                                          $detalles[$i]->detped,
                                          $detalles[$i]->cantcot,
                                          $detalles[$i]->precio,
                                          $detalles[$i]->entrega,
                                          $detalles[$i]->observacion,
                                          $detalles[$i]->entidad));

            } catch (PDOException $th) {
                echo  $th->getMessage();
                return false;
            }
        }
    }

    function calificarProveedor($pdo,$entidad,$pedido){
        try {
            $id = uniqid("cal");
            $sql = "INSERT INTO tb_califica SET idreg=?,id_centi=?,id_pedido=?,nParticipa=?";
            $statement = $pdo->prepare($sql);
            $statement->execute(array($id,$entidad,$pedido,5));
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }
?>