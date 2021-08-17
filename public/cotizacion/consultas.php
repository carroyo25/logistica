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
            $sql = "SELECT
                        lg_regcotiza2.id_regmov,
                        lg_regcotiza2.niddet,
                        lg_regcotiza2.ncodmed,
                        lg_regcotiza2.id_cprod,
                        cm_producto.cdesprod,
                        tb_unimed.cabrevia,
                        lg_regcotiza2.cantcoti 
                    FROM
                        lg_regcotiza2
                        INNER JOIN cm_producto ON lg_regcotiza2.id_cprod = cm_producto.id_cprod
                        INNER JOIN tb_unimed ON lg_regcotiza2.ncodmed = tb_unimed.ncodmed 
                    WHERE
                        lg_regcotiza2.id_regmov = ?";
            $statement = $pdo->prepare($sql);
            $statement -> execute(array($pedido));
            $result = $statement ->fetchAll();
            $rowaffect = $statement->rowCount($sql);
            $salida = "";
            $item = 1;
            if ($rowaffect > 0) {
                foreach ($result as $rs) {
                    $salida .= '<tr>
                        <td class="con_borde alto35px padizq" 
                                data-detped="'.$rs['niddet'].'"
                                data-codprod="'.$rs['id_cprod'].'"
                                data-pedido="'.$rs['id_regmov'].'">'.str_pad($item,3,0,STR_PAD_LEFT).'</td>
                        <td class="con_borde alto35px padizq">'.$rs['cdesprod'].'</td>
                        <td class="con_borde alto35px centro">'.$rs['cabrevia'].'</td>
                        <td class="con_borde alto35px derecha padder cancot">'.number_format($rs['cantcoti'], 2, '.', ',').'</td>
                        <td class="con_borde alto35px"><input type="number" class="ancho100p derecha padder precio"></td>
                        <td class="con_borde alto35px derecha padder total"></td>
                        <td class="con_borde alto35px"><input type="date" class="ancho100p fechaentrega"></td>
                        <td class="con_borde alto35px"><input type="text"></td>
                        <td class="con_borde alto35px  centro"><a href="'.$rs['niddet'].'"><i class="fas fa-paperclip"></i></a></td>
                    </tr>';
                    $item++;
                }
            }

            return $salida;
        } catch (PDOException $th) {
            echo $th->getMessage();

            return false;
        }
    }

    function verificaParticipa($pdo,$pedido,$proveedor){
        try {
            $ret = false;
            $sql = "SELECT id_regmov FROM lg_proformacab WHERE id_regmov=? AND id_centi=?";
            $statement = $pdo->prepare($sql);
		    $statement -> execute(array($pedido,$proveedor));
		    $result = $statement ->fetchAll();
		    $rowaffect = $statement->rowCount($sql);

            if ($rowaffect > 0) {
                $ret = true;
            }

            return $ret;

        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }
?>