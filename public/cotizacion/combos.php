<?php 
    require_once("connect.php");

    function general($pdo,$class){
        try {
            $sql = "SELECT tb_paramete2.ncodprm1,
                        tb_paramete2.ccodprm2,
                        tb_paramete2.cdesprm2 
                    FROM
                        tb_paramete2 
                    WHERE
                        ncodprm1 = ?";
            $statement = $pdo->prepare($sql);
            $statement -> execute(array($class));
            $result = $statement ->fetchAll();
            $rowaffect = $statement->rowCount($sql);
            $salida = '<option value="-1">Elija opcion</option>';

            if ($rowaffect > 0) {
                foreach ($result as $rs) {
                    $salida .= '<option value="'.$rs['ccodprm2'].'">'.$rs['cdesprm2'].'</option>';
                }
            }

            return $salida;
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function monedas($pdo){
        try {
            $sql = "SELECT
                        tb_moneda.ncodmon,
                        tb_moneda.cmoneda,
                        tb_moneda.dmoneda,
                        tb_moneda.cabrevia 
                    FROM
                        tb_moneda";
            $statement = $pdo->query($sql);
            $statement -> execute();
            $result = $statement ->fetchAll();
            $rowaffect = $statement->rowCount($sql);
            $salida = '<option value="-1">Elija opcion</option>';


            if ($rowaffect > 0) {
                foreach ($result as $rs) {
                    $salida .= '<option value="'.$rs['ncodmon'].'">'.$rs['dmoneda'].'</option>';
                }
            }

            return $salida;
        } catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }

    function datosProveedor($pdo,$proveedor){
        try {
            $sql = "SELECT
                    cm_entidadcon.cnombres 
                FROM
                    cm_entidadcon 
                WHERE
                    cm_entidadcon.id_centi = ?";
            $statement = $pdo->prepare($sql);
            $statement -> execute(array($proveedor));
            $result = $statement ->fetchAll();
            
            $contacto = $result[0]['cnombres'] != null ? $result[0]['cnombres'] : ""; 
            
            return $contacto;

        }  catch (PDOException $th) {
            echo $th->getMessage();
            return false;
        }
    }
?>