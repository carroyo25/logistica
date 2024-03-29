<?php
    class AtencionModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerStock($cod){
            try {
                $salida = "";
                
                $sql = $this->db->connect()->prepare("SELECT
                                                        tb_almacen.cdesalm, 
	                                                    tb_almacen.ncodalm 
                                                    FROM
                                                        tb_almausu
                                                        INNER JOIN tb_almacen ON tb_almausu.ncodalm = tb_almacen.ncodalm 
                                                    WHERE
                                                        tb_almausu.id_cuser = :cod 
                                                        AND tb_almausu.nflgactivo = 1");
                $sql->execute(["cod"=>$_SESSION['id_user']]);
                $rowCount = $sql->rowcount();
                if ($rowCount > 0){
                    while ($rs = $sql->fetch()) {

                        $cant = $this->obtenerExistencia($cod,$rs['ncodalm']);

                        $salida .='<tr>
                                        <td class="con_borde pl10">'.strtoupper($rs['cdesalm']).'</td>
                                        <td class="con_borde drch pr20">'.number_format($cant, 2, '.', ',').'</td>
                                    </tr>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }
        
        public function obtenerExistencia($prod,$alm){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        alm_movimdet.ncodalm1,
                                                        alm_movimdet.id_cprod,
                                                        SUM( alm_movimdet.ncantidad ) AS existencia 
                                                    FROM
                                                        alm_movimdet 
                                                    WHERE
                                                        alm_movimdet.ncodalm1 =:alm 
                                                        AND alm_movimdet.id_cprod =:prod
                                                    LIMIT 1");
                $sql->execute(["prod"=>$prod,"alm"=>$alm]);
                $row = $sql->fetchAll();

                return $row[0]['existencia'];
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerCostosProyectos(){

        }

        public function getAllUserRecords($user){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.lg_pedidocab.id_regmov,
                                                        logistica.lg_pedidocab.cnumero,
                                                        logistica.lg_pedidocab.ffechadoc,
                                                        logistica.lg_pedidocab.cconcepto,
                                                        logistica.lg_pedidocab.ffechaven,
                                                        logistica.lg_pedidocab.nEstadoDoc,
                                                        logistica.lg_pedidocab.id_cuser,
                                                        logistica.lg_pedidocab.ncodmov,
                                                        logistica.lg_pedidocab.nNivAten,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        atenciones.cdesprm2 AS atencion,
                                                        estados.cdesprm2 AS estado 
                                                    FROM
                                                        logistica.tb_proyusu
                                                        INNER JOIN logistica.lg_pedidocab ON tb_proyusu.ncodproy = lg_pedidocab.ncodpry
                                                        INNER JOIN logistica.tb_area ON lg_pedidocab.ncodarea = tb_area.ncodarea
                                                        INNER JOIN logistica.tb_proyecto1 ON lg_pedidocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2 
                                                    WHERE
                                                        tb_proyusu.id_cuser = :user 
                                                        AND tb_proyusu.nflgactivo = 1 
                                                        AND atenciones.ncodprm1 = 13 
                                                        AND estados.ncodprm1 = 4 
                                                        AND logistica.lg_pedidocab.nEstadoDoc = 3");
                $query->execute(["user"=>$user]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .='<tr class="h35px" data-idx="'.$row['id_regmov'].'">
                                <td class="con_borde centro">'.$row['cnumero'].'</td>
                                <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechaven'])).'</td>
                                <td class="con_borde pl10">'.$row['cconcepto'].'</td>
                                <td class="con_borde pl10">'.$row['ccodarea'].' '.$row['cdesarea'].'</td>
                                <td class="con_borde pl10">'.$row['ccodpry'].' '.$row['cdespry'].'</td>
                                <td class="con_borde pl10">'.$row['apellidos'].' '.$row['nombres'].'</td>
                                <td class="con_borde centro '. strtolower($row['estado']) .'">'.$row['estado'].'</td>
                                <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="editar"  title="editar"><i class="far fa-edit"></i></a></td>
                            </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function obtenerCabecera($idx){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                            logistica.lg_pedidocab.id_regmov,
                                                            logistica.lg_pedidocab.cnumero,
                                                            logistica.lg_pedidocab.ctipmov,
                                                            logistica.lg_pedidocab.ncodmov,
                                                            logistica.lg_pedidocab.ccoddoc,
                                                            logistica.lg_pedidocab.cserie,
                                                            logistica.lg_pedidocab.ffechadoc,
                                                            logistica.lg_pedidocab.ncodpry,
                                                            logistica.lg_pedidocab.ncodcos,
                                                            logistica.lg_pedidocab.ncodarea,
                                                            logistica.lg_pedidocab.ncodper,
                                                            logistica.lg_pedidocab.cconcepto,
                                                            logistica.lg_pedidocab.mdetalle,
                                                            logistica.lg_pedidocab.ctiptransp,
                                                            logistica.lg_pedidocab.nEstadoReg,
                                                            logistica.lg_pedidocab.nEstadoDoc,
                                                            logistica.lg_pedidocab.id_cuser,
                                                            logistica.lg_pedidocab.nflgactivo,
                                                            logistica.lg_pedidocab.nNivAten,
                                                            logistica.lg_pedidocab.ffechaven,
                                                            rrhh.tabla_aquarius.apellidos,
                                                            rrhh.tabla_aquarius.nombres,
                                                            rrhh.tabla_aquarius.dni,
                                                            logistica.tb_proyecto1.ccodpry,
                                                            logistica.tb_proyecto1.cdespry,
                                                            logistica.tb_area.ccodarea,
                                                            logistica.tb_area.cdesarea,
                                                            logistica.tb_ccostos.ccodcos,
                                                            logistica.tb_ccostos.cdescos,
                                                            transportes.cdesprm2 AS transporte,
                                                            transportes.ccodprm2 AS cod_transporte,
                                                            atenciones.cdesprm2 AS atencion,
                                                            estados.cdesprm2 AS estado 
                                                        FROM
                                                            logistica.lg_pedidocab
                                                            INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                            INNER JOIN logistica.tb_proyecto1 ON logistica.lg_pedidocab.ncodpry = logistica.tb_proyecto1.ncodpry
                                                            INNER JOIN logistica.tb_area ON logistica.lg_pedidocab.ncodarea = logistica.tb_area.ncodarea
                                                            INNER JOIN logistica.tb_ccostos ON logistica.lg_pedidocab.ncodcos = logistica.tb_ccostos.ncodcos
                                                            INNER JOIN logistica.tb_paramete2 AS transportes ON logistica.lg_pedidocab.ctiptransp = transportes.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2 
                                                        WHERE
                                                            logistica.lg_pedidocab.id_regmov = :cod 
                                                            AND atenciones.ncodprm1 = 13 
                                                            AND estados.ncodprm1 = 4
                                                            AND transportes.ncodprm1 = 7");
                $query->execute(["cod"=>$idx]);
                $rowcount = $query->rowcount();

                if ($rowcount>0){
                    while($row = $query->fetch()){
                                        $item['id_regmov']      = $row['id_regmov'];
                                        $item['ctipmov']        = $row['ctipmov'];
                                        $item['ncodmov']        = $row['ncodmov'];
                                        $item['ccoddoc']        = $row['ccoddoc'];
                                        $item['cserie']         = $row['cserie'];
                                        $item['cnumero']        = $row['cnumero'];
                                        $item['ffechadoc']      = $row['ffechadoc'];
                                        $item['ncodpry']        = $row['ncodpry'];
                                        $item['ncodcos']        = $row['ncodcos'];
                                        $item['ncodarea']       = $row['ncodarea'];
                                        $item['ncodper']        = $row['ncodper'];
                                        $item['cconcepto']      = $row['cconcepto'];
                                        $item['mdetalle']       = $row['mdetalle'];
                                        $item['ctiptransp']     = $row['ctiptransp'];
                                        $item['nEstadoReg']     = $row['nEstadoReg'];
                                        $item['nEstadoDoc']     = $row['nEstadoDoc'];
                                        $item['id_cuser']       = $row['id_cuser'];
                                        $item['nflgactivo']     = $row['nflgactivo'];
                                        $item['nNivAten']       = $row['nNivAten'];
                                        $item['apellidos']      = $row['apellidos'];
                                        $item['nombres']        = $row['nombres'];
                                        $item['dni']            = $row['dni'];
                                        $item['ccodpry']        = $row['ccodpry'];
                                        $item['cdespry']        = $row['cdespry'];
                                        $item['ccodarea']       = $row['ccodarea'];
                                        $item['cdesarea']       = $row['cdesarea'];
                                        $item['ccodcos']        = $row['ccodcos'];
                                        $item['cdescos']        = $row['cdescos'];
                                        $item['cod_transporte'] = $row['cod_transporte'];
                                        $item['transporte']     = $row['transporte'];
                                        $item['estado']         = $row['estado'];
                                        $item['atencion']       = $row['atencion'];
                                        $item['ffechaven']       = $row['ffechaven'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function obtenerDetallesId($idx){
            try {
                $salida = '';

                $query = $this->db->connect()->prepare("SELECT
                                                        lg_pedidodet.nidpedi,
                                                        lg_pedidodet.id_cprod,
                                                        ROUND( lg_pedidodet.ncantpedi, 2 ) AS cantidad,
                                                        lg_pedidodet.nEstadoPed,
                                                        lg_pedidodet.nFlgCalidad,
                                                        tb_unimed.nfactor,
                                                        tb_unimed.cabrevia,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod 
                                                    FROM
                                                        lg_pedidodet
                                                        INNER JOIN tb_unimed ON lg_pedidodet.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod 
                                                    WHERE
                                                        lg_pedidodet.id_regmov =:cod
                                                        AND lg_pedidodet.nflgactivo = 1");
                $query->execute(["cod"=>$idx]);
                $rowcount = $query->rowcount();
                $line = 0;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $line++;
                        $salida.='<tr class="lh1_2rem">
                            <td class="con_borde centro"><a href="'.$row['id_cprod'].'" data-topcion="stock" data-iddet="'.$row['nidpedi'].'"><i class="fas fa-boxes"></i></a></td>
                            <td class="con_borde drch pr20">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                            <td class="con_borde centro" data-indice="'.$row['id_cprod'].'">'. $row['id_cprod'] .'</td>
                            <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                            <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                            <td class="con_borde drch pr10">'.$row['cantidad'] .'</td>
                            <td class="con_borde"><input type="number" class="drch"></td>
                            <td class="con_borde"></td>
                            <td class="con_borde centro"></td>
                            <td class="con_borde centro"><input type="text" class="pl20"></td>
                            <td class="con_borde oculto">'. $row['nfactor'] .'</td>
                        </tr>';
                    }
                }

                return $salida;
            } catch (PDOexception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function actualizaDetalle($idx,$detalles,$est){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            
            for ($i=0; $i < $nreg; $i++) { 
                try {

                    if ( $est == 3 ){
                        $estado = $datos[$i]->alm == $datos[$i]->cant  ? 14:5;
                        $cant = $datos[$i]->alm; 
                    }else {
                        $cant = $datos[$i]->cant;
                        $estado = 14;
                    }
                    
                    $sql = $this->db->connect()->prepare("UPDATE lg_pedidodet SET ncantaten=:cant,comentaAlm=:obs,nEstadoReg=:est WHERE nidpedi =:cod");
                    $sql->execute([ "cod"=>$datos[$i]->item,
                                    "cant"=>$cant,
                                    "obs"=>$datos[$i]->obs,
                                    "est"=>$estado]);
                    

                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }

            $salida = $this->cambiarStatus($idx,$est);

            return $salida;
        }

        public function cambiarStatus($idx,$est){
            $salida = "";
            try {
                $sql = $this->db->connect()->prepare("UPDATE lg_pedidocab SET nEstadoDoc =:est WHERE id_regmov = :cod LIMIT 1");
                $sql->execute(["cod"=>$idx,"est"=>$est]);
                $rowCount = $sql->rowcount();
                if ($rowCount > 1)
                    $salida = true;

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function resumen($user,$estado) {
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        COUNT( lg_pedidocab.cnumero ) AS respuesta 
                                                        FROM
                                                            tb_proyusu
                                                            INNER JOIN lg_pedidocab ON tb_proyusu.ncodproy = lg_pedidocab.ncodpry 
                                                        WHERE
                                                            tb_proyusu.nflgactivo = 1 
                                                            AND tb_proyusu.id_cuser = :usr 
                                                            AND lg_pedidocab.nEstadoDoc = :est");
                $sql->execute(["usr"=>$user,"est"=>$estado]);
                $result = $sql->fetchAll();
                return $result[0]['respuesta'];

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>