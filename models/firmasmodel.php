<?php
    class FirmasModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getMainRecords(){
            $salida = "";
            try {
                try {
                    $sql = $this->db->connect()->query("SELECT
                                                        lg_regabastec.ctipmov,
                                                        lg_regabastec.cnumero,
                                                        lg_regabastec.cper,
                                                        lg_regabastec.ffechadoc,
                                                        lg_regabastec.nNivAten,
                                                        lg_regabastec.id_regmov,
                                                        lg_regabastec.nflgactivo,
                                                        tb_area.cdesarea,
                                                        tb_proyecto1.ccodpry,
                                                        tb_proyecto1.cdespry,
                                                        tb_ccostos.ccodcos,
                                                        tb_ccostos.cdescos,
                                                        tb_moneda.dmoneda,
                                                        tb_paramete2.cdesprm2,
                                                        lg_registro.cconcepto,
                                                        lg_registro.mdetalle,
                                                        lg_regabastec.nfirmaLog,
                                                        lg_regabastec.nfirmaFin,
                                                        lg_regabastec.nfirmaOpe,
                                                        lg_registro.cnumero AS pedido,
                                                        lg_regabastec.id_refpedi
                                                    FROM
                                                        lg_regabastec
                                                        INNER JOIN tb_area ON lg_regabastec.ncodarea = tb_area.ncodarea
                                                        INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN tb_ccostos ON lg_regabastec.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN tb_moneda ON lg_regabastec.ncodmon = tb_moneda.ncodmon
                                                        INNER JOIN tb_paramete2 ON lg_regabastec.nNivAten = tb_paramete2.ccodprm2
                                                        INNER JOIN lg_registro ON lg_regabastec.id_refpedi = lg_registro.id_regmov 
                                                    WHERE
                                                        tb_paramete2.ncodprm1 = 13 
                                                        AND lg_regabastec.nflgactivo = 1");
                    $sql->execute();
                    $rowcount = $sql->rowcount();

                    if ($rowcount > 0) {
                         while ($row = $sql->fetch()) {
                            $num = str_pad($row['cnumero'],6,"0",STR_PAD_LEFT).'-'.$row['cper'];
                            $log = is_null($row['nfirmaLog']) ? '<i class="far fa-square"></i>' : '<i class="far fa-check-square"></i>';
                            $ope = is_null($row['nfirmaOpe']) ? '<i class="far fa-square"></i>' : '<i class="far fa-check-square"></i>';
                            $fin = is_null($row['nfirmaFin']) ? '<i class="far fa-square"></i>' : '<i class="far fa-check-square"></i>';

                            $salida .='<tr>
                                        <td class="con_borde pl20">'.$num.'</td>
                                        <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                        <td class="con_borde pl20">'.$row['cconcepto'].'</td>
                                        <td class="con_borde pl20">'.$row['cdesarea'].'</td>
                                        <td class="con_borde pl20">'.$row['ccodpry'].' '.$row['cdespry'].'</td>
                                        <td class="con_borde centro">'.$log.'</td>
                                        <td class="con_borde centro">'.$ope.'</td>
                                        <td class="con_borde centro">'.$fin.'</td>
                                        <td class="con_borde centro '.strtolower($row['cdesprm2']).'">'.strtoupper($row['cdesprm2']).'</td>
                                        <td class="con_borde centro"><a href="'.$row['id_regmov'].'"
                                                                     data-idpedido="'.$row['id_refpedi'].'"
                                                                     data-numerord="'.$num.'"
                                                                     data-nropedido="'.$row['pedido'].'"><i class="far fa-edit"></i></a></td>
                                    </tr>';
                         }   
                    }else {
                        $salida = '<tr><td colspan="11" class="centro">No hay registros</td></tr>';
                    }

                    return $salida;
                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getOrdHeader($cod){
            $item = array();
            try {
                $sql = $this->db->connect()->prepare("SELECT
                logistica.lg_regabastec.id_regmov,
                logistica.lg_regabastec.ctipmov,
                logistica.lg_regabastec.cserie,
                logistica.lg_regabastec.cnumero,
                logistica.lg_regabastec.ffechadoc,
                logistica.lg_regabastec.ffechaent,
                logistica.lg_regabastec.id_centi,
                logistica.lg_regabastec.ncodmon,
                logistica.lg_regabastec.ntotal,
                logistica.lg_regabastec.ncodalm,
                logistica.lg_regabastec.mdetalle,
                logistica.lg_regabastec.nNivAten,
                logistica.lg_regabastec.ncodpago,
                logistica.lg_regabastec.nplazo,
                logistica.lg_regabastec.cnumcot,
                logistica.lg_regabastec.nflgactivo,
                logistica.lg_regabastec.fregsys,
                logistica.lg_regabastec.nfirmaLog,
                logistica.lg_regabastec.nfirmaFin,
                logistica.lg_regabastec.nfirmaOpe,
                rrhh.tabla_aquarius.apellidos,
                rrhh.tabla_aquarius.nombres,
                logistica.tb_proyecto1.ccodpry,
                logistica.tb_proyecto1.cdespry,
                logistica.tb_area.ccodarea,
                logistica.tb_area.cdesarea,
                logistica.lg_regabastec.id_refpedi,
                logistica.tb_ccostos.ccodcos,
                logistica.tb_ccostos.cdescos,
                logistica.lg_regabastec.cper,
                logistica.lg_regabastec.cmes,
                transporte.cdesprm2 AS transporte,
                logistica.lg_registro.cconcepto,
                logistica.tb_moneda.dmoneda,
                logistica.cm_entidad.crazonsoc,
                logistica.cm_entidad.cnumdoc,
                atencion.cdesprm2 AS atencion
            FROM
                logistica.lg_regabastec
                INNER JOIN rrhh.tabla_aquarius ON logistica.lg_regabastec.ncodper = rrhh.tabla_aquarius.internal
                INNER JOIN logistica.tb_proyecto1 ON logistica.lg_regabastec.ncodpry = logistica.tb_proyecto1.ncodpry
                INNER JOIN logistica.tb_area ON logistica.lg_regabastec.ncodarea = logistica.tb_area.ncodarea
                INNER JOIN logistica.tb_ccostos ON logistica.lg_regabastec.ncodcos = logistica.tb_ccostos.ncodcos
                INNER JOIN logistica.tb_paramete2 AS transporte ON logistica.lg_regabastec.ctiptransp = transporte.ncodprm2
                INNER JOIN logistica.lg_registro ON logistica.lg_regabastec.id_refpedi = logistica.lg_registro.id_regmov
                INNER JOIN logistica.tb_moneda ON logistica.lg_regabastec.ncodmon = logistica.tb_moneda.ncodmon
                INNER JOIN logistica.cm_entidad ON logistica.lg_regabastec.id_centi = logistica.cm_entidad.id_centi
                INNER JOIN logistica.tb_paramete2 AS atencion ON logistica.lg_regabastec.nNivAten = atencion.ccodprm2 
            WHERE
                logistica.lg_regabastec.id_regmov = :cod 
                AND transporte.ncodprm1 = 7 
                AND atencion.ncodprm1 = 13");
                $sql->execute(["cod"=>$cod]);
                $rowcount = $sql->rowcount();

                if ($rowcount > 0){
                    while ($row = $sql->fetch()) {
                        $tipo = $row['ctipmov'] == "B"?"BIENES":"SERVICIOS";

                        $item['numero']     = str_pad($row['cnumero'],3,"0",STR_PAD_LEFT).'-'.$row['cper'];
                        $item['fechadoc']   = $row['ffechadoc'];
                        $item['elaborado']  = strtoupper($row['nombres'].' '.$row['apellidos']);
                        $item['proyecto']   = strtoupper($row['ccodpry'].' '.$row['cdespry']);
                        $item['costos']     = strtoupper($row['ccodcos'].' '.$row['cdescos']);
                        $item['area']       = strtoupper($row['ccodarea'].' '.$row['cdesarea']);
                        $item['transporte'] = $row['transporte'];
                        $item['concepto']   = $row['cconcepto'];
                        $item['detalle']    = $row['mdetalle'];
                        $item['total']      = round($row['ntotal'],2);
                        $item['moneda']     = $row['dmoneda'];
                        $item['tipo']       = $tipo;
                        $item['entidad']    = $row['crazonsoc'];
                        $item['ruc']        = $row['cnumdoc'];
                        $item['atencion']   = $row['atencion'];
                        $item['logistica']  = $row['nfirmaLog'];
                        $item['finanzas']   = $row['nfirmaFin'];
                        $item['operaciones']= $row['nfirmaOpe'];
                    }
                }

                return $item;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getOrderDetails($cod,$ped){
            $salida = "";
            try {
                $sql=$this->db->connect()->prepare("SELECT
                                                    lg_detaabastec.id_regmov,
                                                    lg_detaabastec.niddeta,
                                                    lg_detaabastec.nidpedi,
                                                    lg_detaabastec.id_cprod,
                                                    lg_detaabastec.ncanti,
                                                    lg_detaabastec.ncodmed,
                                                    lg_detaabastec.nfactor,
                                                    lg_detaabastec.nvventa,
                                                    lg_detaabastec.ntotal,
                                                    cm_producto.ccodprod,
                                                    cm_producto.cdesprod,
                                                    tb_unimed.cabrevia 
                                                FROM
                                                    lg_detaabastec
                                                    INNER JOIN cm_producto ON lg_detaabastec.id_cprod = cm_producto.id_cprod
                                                    INNER JOIN tb_unimed ON lg_detaabastec.ncodmed = tb_unimed.ncodmed 
                                                WHERE
                                                    lg_detaabastec.id_regmov = :cod");
                $sql->execute(["cod"=>$cod]);
                $rowcount = $sql->rowcount();
                $cont=0;

                if ($rowcount > 0) {
                    while ($row = $sql->fetch()) {
                        $cont++;
                        $salida.='<tr class="lh1_2rem">
                                    <td class="centro con_borde">'.str_pad($cont,3,"0",STR_PAD_LEFT).'</td>
                                    <td class="centro con_borde">'.$row['id_cprod'].'</td>
                                    <td class="pl20 con_borde">'.$row['cdesprod'].'</td>
                                    <td class="con_borde centro">'.$row['cabrevia'].'</td>
                                    <td class="con_borde drch pr20">'.round($row['ncanti'],2).'</td>
                                    <td class="con_borde drch pr20">'.round($row['nvventa'],2).'</td>
                                    <td class="con_borde drch pr20">'.round($row['ntotal'],2).'</td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde centro">'.str_pad($ped,6,"0",STR_PAD_LEFT).'</td>
                                </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getPedHeader($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                            logistica.lg_registro.id_regmov,
                                                            logistica.lg_registro.cnumero,
                                                            logistica.lg_registro.ctipmov,
                                                            logistica.lg_registro.ncodmov,
                                                            logistica.lg_registro.ccoddoc,
                                                            logistica.lg_registro.cserie,
                                                            logistica.lg_registro.ffechadoc,
                                                            logistica.lg_registro.ncodpry,
                                                            logistica.lg_registro.ncodcos,
                                                            logistica.lg_registro.ncodarea,
                                                            logistica.lg_registro.ncodper,
                                                            logistica.lg_registro.cconcepto,
                                                            logistica.lg_registro.mdetalle,
                                                            logistica.lg_registro.ctiptransp,
                                                            logistica.lg_registro.nEstadoReg,
                                                            logistica.lg_registro.nEstadoDoc,
                                                            logistica.lg_registro.id_cuser,
                                                            logistica.lg_registro.nflgactivo,
                                                            logistica.lg_registro.nNivAten,
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
                                                            logistica.lg_registro
                                                            INNER JOIN rrhh.tabla_aquarius ON logistica.lg_registro.ncodper = rrhh.tabla_aquarius.internal
                                                            INNER JOIN logistica.tb_proyecto1 ON logistica.lg_registro.ncodpry = logistica.tb_proyecto1.ncodpry
                                                            INNER JOIN logistica.tb_area ON logistica.lg_registro.ncodarea = logistica.tb_area.ncodarea
                                                            INNER JOIN logistica.tb_ccostos ON logistica.lg_registro.ncodcos = logistica.tb_ccostos.ncodcos
                                                            INNER JOIN logistica.tb_paramete2 AS transportes ON logistica.lg_registro.ctiptransp = transportes.ncodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_registro.nNivAten = atenciones.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_registro.nEstadoDoc = estados.ccodprm2 
                                                        WHERE
                                                            logistica.lg_registro.id_regmov = :cod 
                                                            AND atenciones.ncodprm1 = 13 
                                                            AND estados.ncodprm1 = 4");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                if ($rowcount>0){
                    while($row = $query->fetch()){
                                        $item['id_regmov']      = $row['id_regmov'];
                                        $item['ctipmov']        = $row['ctipmov'];
                                        $item['cserie']         = $row['cserie'];
                                        $item['cnumero']        = $row['cnumero'];
                                        $item['ffechadoc']      = $row['ffechadoc'];
                                        $item['cconcepto']      = $row['cconcepto'];
                                        $item['mdetalle']       = $row['mdetalle'];
                                        $item['nEstadoReg']     = $row['nEstadoReg'];
                                        $item['nEstadoDoc']     = $row['nEstadoDoc'];
                                        $item['id_cuser']       = $row['id_cuser'];
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
                    }
                }

                return $item;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getPedDetails($cod){
            try {
                $salida = '';
                
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_detapedido.nidpedi,
                                                        lg_detapedido.id_cprod,
                                                        ROUND( lg_detapedido.ncantpedi, 2 ) AS cantidad,
                                                        lg_detapedido.nEstadoPed,
                                                        tb_unimed.nfactor,
                                                        tb_unimed.cabrevia,
                                                        estados.cdesprm2 AS estado,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        lg_regabastec.cper,
                                                        lg_regabastec.cnumero 
                                                    FROM
                                                        lg_detapedido
                                                        INNER JOIN tb_unimed ON lg_detapedido.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN tb_paramete2 AS estados ON lg_detapedido.nEstadoPed = estados.ccodprm2
                                                        INNER JOIN cm_producto ON lg_detapedido.id_cprod = cm_producto.id_cprod
                                                        LEFT JOIN lg_regabastec ON lg_detapedido.idreg_ref = lg_regabastec.id_regmov 
                                                    WHERE
                                                        lg_detapedido.id_regmov = :cod 
                                                        AND estados.ncodprm1 = 4 
                                                        AND lg_detapedido.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $line = 0;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $line++;

                        $orden = $row['cnumero'] != null ? str_pad($row['cnumero'],5,"0",STR_PAD_LEFT).'-'.$row['cper']:"";//;

                        $salida.='<tr class="lh1_2rem">
                            <td class="con_borde drch pr20">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                            <td class="con_borde centro" data-indice="'.$row['id_cprod'].'">'. $row['ccodprod'] .'</td>
                            <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                            <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                            <td class="con_borde drch pr10">'.$row['cantidad'] .'</td>
                            <td class="con_borde centro">'. $orden .'</td>
                            <td class="con_borde"></td>
                            <td class="con_borde centro"></td>
                        </tr>';
                    }
                }

                return $salida;
            } catch (PDOexception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertComment($comment,$regmov,$date){
            try {
                $sql = $this->db->connect()->prepare("INSERT INTO lg_abascomenta SET id_regmov=:reg,id_cuser=:usr,ccomenta=:msg,ffecha=:dat,nflgactivo=:flg");
                $sql->execute([ "reg"=>$regmov,
                                "usr"=>$_SESSION['codper'],
                                "msg"=>$comment,
                                "dat"=>$date,
                                "flg"=>1]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getComments($codigo){
            $salida = "";
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                            lg_abascomenta.id_regmov,
                                            lg_abascomenta.id_cuser,
                                            lg_abascomenta.ccomenta,
                                            lg_abascomenta.ffecha,
                                            lg_abascomenta.nflgactivo,
                                            tb_sysusuario.cnombres 
                                        FROM
                                            lg_abascomenta
                                            INNER JOIN tb_sysusuario ON lg_abascomenta.id_cuser = tb_sysusuario.ccodper 
                                        WHERE
                                            lg_abascomenta.id_regmov =:cod");
                $sql->execute(["cod"=>$codigo]);
                $rowcount = $sql->rowcount();

                if ($rowcount > 0){
                    while ($row = $sql->fetch()) {
                        $salida.='<tr>
                                    <td class="con_borde pl20 mayusculas">'.strtoupper($row['cnombres']).'</td>
                                    <td class="con_borde centro"><input type="date" value="'.$row['ffecha'].'" readonly></td>
                                    <td class="con_borde pl20">'.$row['ccomenta'].'</td>
                                    <td class="con_borde centro"></td>
                                    <td class="con_borde centro"></td>
                                </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
            return $salida;
        }

        public function delComments($codigo){
            try {
                $sql = $this->db->connect()->prepare("");
                $sql->execute(["cod"=>$codigo]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function signOrder($codigo,$cargo){
            try {
                switch ($cargo) {
                    case '7204':
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaLog=:fir,codperLog=:usr WHERE id_regmov=:cod");
                        break;
                    case '7152':
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaOpe=:fir,codperOpe=:usr WHERE id_regmov=:cod");
                        break;
                    case '1029':
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaFin=:fir,codperFin=:usr WHERE id_regmov=:cod");
                        break;
                    case '5325':
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaFin=:fir,codperFin=:usr WHERE id_regmov=:cod");
                        break;
                }
                $sql->execute(["cod"=>$codigo,
                                "usr"=>$_SESSION['codper'],
                                "fir"=>1]);

                return $cargo;
            } catch (PDOException $th) {
                echo $th->getMessage();

                return false;
            }
        }
    }
?>