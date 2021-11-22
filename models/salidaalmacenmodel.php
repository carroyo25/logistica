<?php
    class SalidaAlmacenModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerRegistros(){
            try {
                $salida = "";
                $sql = $this->db->connect()->prepare("SELECT
                                                    logistica.tb_almausu.ncodalm,
                                                    logistica.tb_almausu.id_cuser,
                                                    logistica.alm_salidacab.femic,
                                                    logistica.alm_salidacab.fentrega,
                                                    CONCAT( rrhh.tabla_aquarius.apellidos, ' ', rrhh.tabla_aquarius.nombres ) AS nombres,
                                                    rrhh.tabla_aquarius.dcargo,
                                                    rrhh.tabla_aquarius.dni,
                                                    logistica.tb_almacen.cdesalm,
                                                    logistica.alm_salidacab.n_salida,
                                                    logistica.alm_salidacab.idreg
                                                FROM
                                                    logistica.tb_almausu
                                                    INNER JOIN logistica.alm_salidacab ON tb_almausu.ncodalm = alm_salidacab.id_alm
                                                    INNER JOIN rrhh.tabla_aquarius ON logistica.alm_salidacab.id_solic = rrhh.tabla_aquarius.internal
                                                    INNER JOIN logistica.tb_almacen ON logistica.alm_salidacab.id_alm = logistica.tb_almacen.ncodalm 
                                                WHERE
                                                    tb_almausu.id_cuser =:user 
                                                    AND tb_almausu.nflgactivo = 1");
                $sql->execute(["user"=>$_SESSION['iduser']]);
                $rowCount = $sql->rowcount();

                if ($rowCount > 0){
                    while ($rs = $sql->fetch()) {
                        $salida .='<tr>
                                        <td class="con_borde centro">'.str_pad($rs['n_salida'],3,0,STR_PAD_LEFT).'</td>
                                        <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['femic'])).'</td>
                                        <td class="con_borde pl20">'.strtoupper($rs['cdesalm']).'</td>
                                        <td class="con_borde centro">'.$rs['dni'].'</td>
                                        <td class="con_borde pl20">'.strtoupper($rs['nombres']).'</td>
                                        <td class="con_borde centro"><a href="'.$rs['idreg'].'"><i class="far fa-edit"></i></a></td>
                                    </tr>';

                    }
                }

                return $salida;
            } catch (PDOException $th) {
               echo $th->getMessage();
               return false;
            }
        }

        public function consultarSalidaId($id){
            try {
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.alm_salidacab.id_alm,
                                                        logistica.alm_salidacab.id_solic,
                                                        logistica.alm_salidacab.n_salida,
                                                        logistica.alm_salidacab.femic,
                                                        logistica.alm_salidacab.fentrega,
                                                        logistica.alm_salidacab.idreg,
                                                        CONCAT( rrhh.tabla_aquarius.apellidos, ' ', rrhh.tabla_aquarius.nombres ) AS solcitante,
                                                        logistica.tb_almacen.cdesalm,
                                                        rrhh.tabla_aquarius.dcargo,
                                                        rrhh.tabla_aquarius.dni  
                                                    FROM
                                                        logistica.alm_salidacab
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.alm_salidacab.id_solic = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_almacen ON logistica.alm_salidacab.id_alm = logistica.tb_almacen.ncodalm 
                                                    WHERE
                                                        alm_salidacab.idreg =:id");
                $query->execute(["id"=>$id]);

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

        public function consultarDetalleSalida($id){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        alm_salidadet.idreg,
                                                        alm_salidadet.idsalida,
                                                        alm_salidadet.niddeta,
                                                        alm_salidadet.nunid,
                                                        alm_salidadet.idprod,
                                                        alm_salidadet.ncant,
                                                        alm_salidadet.nfactor,
                                                        alm_salidadet.cserie,
                                                        cm_producto.cdesprod,
                                                        tb_unimed.cabrevia,
                                                        cm_producto.ccodprod 
                                                    FROM
                                                        alm_salidadet
                                                        INNER JOIN cm_producto ON alm_salidadet.idprod = cm_producto.id_cprod
                                                        INNER JOIN tb_unimed ON alm_salidadet.nunid = tb_unimed.ncodmed 
                                                    WHERE
                                                        alm_salidadet.idsalida = :id");
                $query->execute(["id"=>$id]);
                $rowCount = $query->rowcount();
                $fila = 1;

                if ($rowCount > 0){
                    while ($rs = $query->fetch()) {
                        $salida .='<tr>
                                        <td class="con_borde" data-grabado="1"></td>
                                        <td class="con_borde centro">'.str_pad($fila++,3,0,STR_PAD_LEFT).'</td>
                                        <td class="con_borde pl20">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde pl20">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde centro">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde drch pr10">'.$rs['ncant'].'</td>
                                        <td class="con_borde pl10">'.$rs['cserie'].'</td>
                                    </tr>';
                    }
                }

                return $salida;
            } catch (\Throwable $th) {
                echo $th->getMessage();
                return false;
            }
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

                    return $docData;

                }else {
                    return false;
                }

                
                
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
                                                        alm_movimdet.cSerie,
                                                        cm_producto.cmarca,
                                                        cm_producto.cmodelo,
                                                        tb_unimed.cabrevia 
                                                    FROM
                                                        alm_movimdet
                                                        INNER JOIN cm_producto ON alm_movimdet.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                    WHERE
                                                        alm_movimdet.ncodalm2 = :almacen
                                                        AND ncantidad > 0
                                                    LIMIT 20");
                $query->execute(["almacen"=>$almacen]);
                $rowCount = $query->rowcount();

                if ($rowCount) {
                    while ($rs = $query->fetch()) {
                        
                        $salida .= '<tr class="pointertr" data-idprod="'.$rs['id_cprod'].'" 
                                                data-niddeta="'.$rs['niddeta'].'"
                                                data-factor="'.$rs['nfactor'].'"
                                                data-unidad="'.$rs['ncoduni'].'"
                                                data-existe="'.$rs['ncantidad'].'">
                                        <td class="con_borde">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde">'.$rs['cSerie'].'</td>
                                        <td class="con_borde">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde"><a href="'.$rs['cSerie'].'"><i class="fas fa-exchange-alt"></i></a></td>
                                    </tr>';
                    }
                    
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function grabarSalida($cabecera,$detalles){
            try {
                $id = uniqid();
                $salida = $this->numeroSalida($cabecera['cod_almacen']) + 1;
                $query=$this->db->connect()->prepare("INSERT INTO alm_salidacab SET idreg=:id,n_salida=:salida,id_alm=:almacen,
                                                                                    id_solic=:solicita,femic=:emision");
                $query->execute(["id"=>$id,
                                "salida"=>$salida,
                                "almacen"=>$cabecera['cod_almacen'],
                                "solicita"=>$cabecera['cod_personal'],
                                "emision"=>$cabecera['fechaEntrega']]);
                $rowCount = $query->rowcount();

                if ($rowCount > 0) {
                    $this->grabarDetalles($detalles,$id);
                    //$this->actualizarStock($detalles);
                    //$this->actualizarSerie($detalles);
                    return true;
                }

                return $cabecera['cod_almacen'];
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function grabarDetalles($detalles,$id){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            
            for ($i=0; $i < $nreg; $i++) { 
                try {
                    $query = $this->db->connect()->prepare("INSERT INTO alm_salidadet SET idsalida=:salida,niddeta=:item,idprod=:prod,
                                                                                        ncant=:cantidad,nfactor=:factor,cserie=:serie,
                                                                                        nunid=:unidad");
                    $query->execute(["salida"=>$id,
                                    "item"=>$datos[$i]->niddeta,
                                    "prod"=>$datos[$i]->idprod,
                                    "cantidad"=>$datos[$i]->cantidad,
                                    "factor"=>$datos[$i]->factor,
                                    "serie"=>$datos[$i]->serie,
                                    "unidad"=>$datos[$i]->unidad]);

                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }

            $this->actualizarStock($detalles);
            $this->actualizarSerie($detalles);
        }

        
        public function numeroSalida($almacen){
            try {
                $query = $this->db->connect()->prepare("SELECT COUNT(idreg) AS numero
                                                        FROM alm_salidacab 
                                                        WHERE id_alm =:almacen");
                $query->execute(["almacen"=>$almacen]);
                $result = $query->fetchAll();

                return $result[0]['numero'];
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerInicialesProductos(){
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
                        $estado = $rs['nEntregado'] == 1 ? "desactivado" : ""; 
                        
                        $salida .= '<tr class="pointertr '.$estado.'" data-idprod="'.$rs['id_cprod'].'" 
                                                data-niddeta="'.$rs['niddeta'].'"
                                                data-factor="'.$rs['nfactor'].'"
                                                data-unidad="'.$rs['ncoduni'].'"
                                                data-existe="'.$rs['ncantidad'].'">
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
                        $estado = $rs['nEntregado'] == 1 ? "desactivado" : ""; 
                        
                        $salida .= '<tr class="pointertr '.$estado.'" data-idprod="'.$rs['id_cprod'].'" 
                                                data-niddeta="'.$rs['niddeta'].'"
                                                data-factor="'.$rs['nfactor'].'"
                                                data-unidad="'.$rs['ncoduni'].'"
                                                data-existe="'.$rs['ncantidad'].'">
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

        public function actualizarStock($detalles){
            $datos = json_decode($detalles);
            $nreg = count($datos);

            for ($i=0; $i < $nreg; $i++) { 
                try {
                    $stock = $datos[$i]->existe - $datos[$i]->cantidad;
                    $sql = $this->db->connect()->prepare("UPDATE alm_movimdet SET ncantidad=:stock WHERE niddeta=:item");
                    $sql->execute(["item"=>$datos[$i]->niddeta,"stock"=>$stock]);
                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }
            
        }

        public function actualizarSerie($detalles){
            $datos = json_decode($detalles);
            $nreg = count($datos);

            for ($i=0; $i < $nreg ; $i++) { 
                try {
                    $sql = $this->db->connect()->prepare("UPDATE cm_prodserie SET nEntregado=1 WHERE cdesserie=:serie");
                    $sql->execute(["serie"=>$datos[$i]->serie]);
                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }
        }
    }
?>