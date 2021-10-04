<?php
    class SalidaAlmacenModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerAlmacenes($user){
            try {
                $salida = "";
                $sql = $this->db->connect()->prepare("SELECT
                                                    tb_almacen.cdesalm,
                                                    tb_almacen.ncodalm 
                                                FROM
                                                    tb_almausu
                                                    INNER JOIN tb_almacen ON tb_almausu.ncodalm = tb_almacen.ncodalm 
                                                WHERE
                                                    tb_almausu.id_cuser = :user
                                                    AND tb_almausu.nflgactivo = 1");
                $sql->execute(["user"=>$user]);
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida.='<li><a href="'.$row['ncodalm'].'">'.strtoupper($row['cdesalm']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }


        public function obtenerNombreSolicitante($doc){
            try {
                
                $query = $this->db->connectrrhh()->prepare("SELECT
                                                            tabla_aquarius.dni,
                                                            CONCAT( tabla_aquarius.apellidos, ', ', tabla_aquarius.nombres ) AS nombres,
                                                            tabla_aquarius.dcostos,
                                                            tabla_aquarius.dsede,
                                                            tabla_aquarius.ccostos,
                                                            tabla_aquarius.internal,
	                                                        tabla_aquarius.dcargo  
                                                        FROM
                                                            tabla_aquarius 
                                                        WHERE
                                                            tabla_aquarius.dni =:doc");
                $query->execute(["doc"=>$doc]);
                $rowCount = $query->rowCount();

                

                if ($rowCount > 0) {
                    $docData = array();
                    while($row=$query->fetch(PDO::FETCH_ASSOC)){
                        $docData[] = $row;
                    }
                }

                return $docData;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerStock($almacen){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        alm_movimdet.ncodalm2,
                                                        alm_movimdet.id_cprod,
                                                        alm_movimdet.ncantidad,
                                                        alm_movimdet.niddeta,
                                                        alm_movimdet.ncoduni,
                                                        alm_movimdet.nfactor,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        cm_prodserie.cdesserie,
                                                        cm_prodserie.nEntregado,
                                                        cm_producto.cmarca,
                                                        cm_producto.cmodelo,
                                                        tb_unimed.cabrevia 
                                                    FROM
                                                        alm_movimdet
                                                        INNER JOIN cm_producto ON alm_movimdet.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN cm_prodserie ON alm_movimdet.id_cprod = cm_prodserie.id_cprod
                                                        INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                    WHERE
                                                        alm_movimdet.ncodalm2 = :almacen
                                                    LIMIT 20");
                $query->execute(["almacen"=>$almacen]);
                $rowCount = $query->rowcount();

                if ($rowCount) {
                    while ($rs = $query->fetch()) {
                        $salida .= '<tr class="pointertr" data-idprod="'.$rs['id_cprod'].'" data-niddeta="'.$rs['niddeta'].'">
                                        <td class="con_borde">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde">'.$rs['cdesserie'].'</td>
                                        <td class="con_borde">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde"><a href="'.$rs['cdesserie'].'"><i class="fas fa-exchange-alt"></i></a></td>
                                    </tr>';
                    }
                    
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function grabarSalida($cod,$almacen){
            try {
                //code...
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerIncialesProductos(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT
                                                            SUBSTR( cm_producto.cdesprod, 1, 1 ) AS inicial 
                                                        FROM
                                                            alm_movimdet
                                                            INNER JOIN cm_producto ON alm_movimdet.id_cprod = cm_producto.id_cprod 
                                                        WHERE
                                                            alm_movimdet.ncodalm2 = 2 
                                                        GROUP BY
                                                            SUBSTR( cm_producto.cdesprod, 1, 1 ) 
                                                        ORDER BY
                                                            cm_producto.cdesprod");
                $query->execute();

                while ($row = $query->fetch()) {
                    $salida .= '<li><a href="'.$row['inicial'].'">'.$row['inicial'].'</a></li>';
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function consultarPorInicial($inic,$almacen){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                            alm_movimdet.ncodalm2,
                                                            alm_movimdet.id_cprod,
                                                            alm_movimdet.ncantidad,
                                                            alm_movimdet.ncoduni,
                                                            alm_movimdet.nfactor,
                                                            cm_producto.ccodprod,
                                                            cm_producto.cdesprod,
                                                            cm_prodserie.cdesserie,
                                                            cm_prodserie.nEntregado,
                                                            cm_producto.cmarca,
                                                            cm_producto.cmodelo,
                                                            tb_unimed.cabrevia 
                                                        FROM
                                                            alm_movimdet
                                                            INNER JOIN cm_producto ON alm_movimdet.id_cprod = cm_producto.id_cprod
                                                            INNER JOIN cm_prodserie ON alm_movimdet.id_cprod = cm_prodserie.id_cprod
                                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                        WHERE
                                                            alm_movimdet.ncodalm2 = :almacen
                                                            AND SUBSTR( cm_producto.cdesprod, 1, 1 )=:letra");
                                                        
                $query->execute(["almacen"=>$almacen,"letra"=>$inic]);
                $rowCount = $query->rowcount();

                if ($rowCount) {
                    while ($rs = $query->fetch()) {
                        $salida .= '<tr class="pointertr" data-idprod="'.$rs['id_cprod'].'">
                                        <td class="con_borde">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde">'.$rs['cdesserie'].'</td>
                                        <td class="con_borde">'.$rs['cabrevia'].'</td>
                                    </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function consultarPorPalabra($palabra,$almacen){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                            alm_movimdet.ncodalm2,
                                                            alm_movimdet.id_cprod,
                                                            alm_movimdet.ncantidad,
                                                            alm_movimdet.ncoduni,
                                                            alm_movimdet.nfactor,
                                                            cm_producto.ccodprod,
                                                            cm_producto.cdesprod,
                                                            cm_prodserie.cdesserie,
                                                            cm_prodserie.nEntregado,
                                                            cm_producto.cmarca,
                                                            cm_producto.cmodelo,
                                                            tb_unimed.cabrevia 
                                                        FROM
                                                            alm_movimdet
                                                            INNER JOIN cm_producto ON alm_movimdet.id_cprod = cm_producto.id_cprod
                                                            INNER JOIN cm_prodserie ON alm_movimdet.id_cprod = cm_prodserie.id_cprod
                                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                        WHERE
                                                            alm_movimdet.ncodalm2 = :almacen
                                                            AND cm_producto.cdesprod LIKE :palabra");
                                                        
                $query->execute(["almacen"=>$almacen,"palabra"=>"%".$palabra."%"]);
                $rowCount = $query->rowcount();

                if ($rowCount) {
                    while ($rs = $query->fetch()) {
                        $salida .= '<tr class="pointertr" data-idprod="'.$rs['id_cprod'].'">
                                        <td class="con_borde">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde">'.$rs['cdesserie'].'</td>
                                        <td class="con_borde">'.$rs['cabrevia'].'</td>
                                    </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>