<?php
    class SeguimientoModel extends Model{

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
                                                        lg_registro.mobserva,
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
                                                        logistica.lg_regabastec.mobserva,
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
                        $item['detalle']    = $row['mobserva'];
                        $item['total']      = number_format($row['ntotal'], 2, '.', ',');
                        $item['moneda']     = $row['dmoneda'];
                        $item['tipo']       = $tipo;
                        $item['entidad']    = $row['crazonsoc'];
                        $item['ruc']        = $row['cnumdoc'];
                        $item['atencion']   = $row['atencion'];
                        $item['logistica']  = $row['nfirmaLog'];
                        $item['finanzas']   = $row['nfirmaFin'];
                        $item['operaciones']= $row['nfirmaOpe'];
                        $item['identidad']  = $row['id_centi'];
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
                                    <td class="con_borde drch pr20">'.number_format($row['ncanti'], 2, '.', ',').'</td>
                                    <td class="con_borde drch pr20">'.number_format($row['nvventa'], 2, '.', ',').'</td>
                                    <td class="con_borde drch pr20">'.number_format($row['ntotal'], 2, '.', ',').'</td>
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

        public function generateDocument($codigo){
            require_once("public/libsrepo/repooc.php");
            
            try {
                if ($this->existDocument($codigo) != ""){
                    return 'public/ordenes/aprobadas/'.$this->existDocument($codigo);
                }else{
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
                                                                logistica.lg_regabastec.mdetalle,
                                                                logistica.lg_regabastec.nNivAten,
                                                                logistica.lg_regabastec.ncodpago,
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
                                                                atencion.cdesprm2 AS atencion,
                                                                logistica.tb_moneda.cmoneda,
                                                                logistica.tb_moneda.cabrevia,
                                                                logistica.tb_almacen.cdesalm,
                                                                plazos.cdesprm2 AS plazo,
                                                                pagos.cdesprm2 AS pago,
                                                                logistica.lg_registro.cnumero as nropedido
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
                                                                INNER JOIN logistica.tb_almacen ON logistica.lg_regabastec.ncodalm = logistica.tb_almacen.ncodalm
                                                                INNER JOIN logistica.tb_paramete2 AS plazos ON logistica.lg_regabastec.nplazo = plazos.ncodprm2
                                                                INNER JOIN logistica.tb_paramete2 AS pagos ON logistica.lg_regabastec.ncodpago = pagos.ncodprm2 
                                                            WHERE
                                                                logistica.lg_regabastec.id_regmov = :cod 
                                                                AND transporte.ncodprm1 = 7 
                                                                AND atencion.ncodprm1 = 13 
                                                                AND plazos.ncodprm1 = 16 
                                                                AND pagos.ncodprm1 = 11");
                    $sql->execute(["cod"=>$codigo]);
                    $rowcount = $sql->rowcount();

                    if ($rowcount > 0){
                        while ($row = $sql->fetch()) {
                            $proyecto       = $this->getDataPed($row['id_refpedi']);
                            $contacto       = $this->getContac($row['id_centi']);
                            $banco          = $this->getBank($row['id_centi'],$row['ncodmon']);
                            $distribuidor   = $this->getDataProvee($row['id_centi']);

                            $nombre_contacto    = isset($contacto['nombre']) ? $contacto['nombre'] : "";
                            $telefono_contacto  =  isset($contacto['telefono']) ? $contacto['telefono'] : "";
                            $mail_contacto      = isset($contacto['mail']) ? $contacto['mail'] : "";

                            $banco_entidad  = isset($banco['banco']) ? $banco['banco'] : "";
                            $modena_entidad = isset($banco['cmoneda']) ? $banco['cmoneda'] : "";
                            $cuenta_entidad = isset($banco['cta']) ? $banco['cta'] : "";

                            $info = $proyecto['codigo']. " " . $proyecto['nombre'];

                            $numdoc = date("Y")."-".str_pad($row['cnumero'],5,"0",STR_PAD_LEFT);

                            $anio = explode("-",$row['ffechadoc']);

                            if ($row['ctipmov'] == "B") {
                                $titulo = "ORDEN DE COMPRA" ;
                                $prefix = "OC";
                            }else{
                                $titulo = "ORDEN DE SERVICIO";
                                $prefix = "OS";
                            }

                            $simbolo = $row['cabrevia'];

                            $titulo = $titulo . " " . $numdoc;
                            $condicion = 1;
                            
                            $file = $codigo.".pdf";
                            $filename = "public/ordenes/aprobadas/".$file;

                            if(file_exists($filename))
                                unlink($filename);
                                
                                $pdf = new PDF($titulo,$condicion,$row['ffechadoc'],$row['dmoneda'],$row['plazo'],$row['cdesalm'],$row['cnumcot'],$row['ffechaent'],$row['pago'],$row['ntotal'],
                                            $info,$proyecto['detalle'],$proyecto['usuario'],
                                            $distribuidor['razon'],$distribuidor['ruc'],$distribuidor['direccion'],$distribuidor['telefono'],$distribuidor['correo'],$distribuidor['retencion'],
                                            $nombre_contacto,$telefono_contacto,$mail_contacto);
                                $pdf->AddPage();
                                $pdf->AliasNbPages();
                                $pdf->SetWidths(array(10,15,70,8,10,17,15,15,15,15));
                                $pdf->SetFont('Arial','',5);
                                $lc = 0;
                                $rc = 0;

                                $detalles = $this->getOrderDetailsToDoc($codigo,$row['nropedido']);
                                $nreg = count($detalles);

                                for ($i=1; $i <=$nreg ; $i++) { 
                                    $pdf->SetAligns(array("C","L","L","C","R","L","L","C","L","L"));
                                    $pdf->Row(array(str_pad($i,2,"0",STR_PAD_LEFT),
                                        $detalles[$rc]['id_cprod'],
                                        $detalles[$rc]['cdespro'],
                                        $detalles[$rc]['cabrevia'],
                                        number_format($detalles[$rc]['ncanti'], 2, '.', ','),
                                        '',
                                        '',
                                        $detalles[$rc]['ped'],
                                        '',
                                        ''));
                
                                    $lc++;
                                    $rc++;
                                    
                                    if ($lc == 52) {
                                        $pdf->AddPage();
                                        $lc = 0;
                                    }
                                }

                                $pdf->Ln(3);

                                $pdf->SetFillColor(229, 229, 229);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(20,6,"TOTAL :","LTB",0,"C",true);
                                $pdf->SetFont('Arial','B',8);
                                $pdf->Cell(140,6,$this->convertir($row['ntotal']),"TBR",0,"L",true); 
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(30,6,number_format($row['ntotal'], 2, '.', ','),"1",1,"R",true);
            
                                $pdf->Ln(1);
                                $pdf->SetFont('Arial',"","7");
                                $pdf->Cell(40,6,"Pedidos Asociados",1,0,"C",true);
                                $pdf->Cell(5,6,"",0,0);
                                $pdf->Cell(80,6,utf8_decode("Información Bancaria del Proveedor"),1,0,"C",true);
                                $pdf->Cell(10,6,"",0,0);
                                $pdf->Cell(40,6,"Valor Venta",0,0);
                                $pdf->Cell(20,6,number_format($row['ntotal'], 2, '.', ','),0,1);
                                
            
                                $pdf->Cell(10,4,utf8_decode("Año"),1,0);
                                $pdf->Cell(10,4,"Tipo",1,0);
                                $pdf->Cell(10,4,"Pedido",1,0);
                                $pdf->Cell(10,4,"Mantto",1,0);
                                $pdf->Cell(5,6,"",0,0);
                                $pdf->Cell(35,4,"Detalle del Banco",1,0);
                                $pdf->Cell(15,4,"Moneda",1,0);
                                $pdf->Cell(30,4,"Nro. Cuenta Bancaria",1,1);
            
                                $pdf->Cell(10,4,$anio[0],1,0);
                                $pdf->Cell(10,4,$row['ctipmov'],1,0);
                                $pdf->Cell(10,4,$proyecto['numero'],1,0);
                                $pdf->Cell(10,4,"",1,0);
                                $pdf->Cell(5,6,"",0,0);
                                $pdf->Cell(35,4,utf8_decode($banco_entidad),1,0);
                                $pdf->Cell(15,4,$modena_entidad,1,0);
                                $pdf->Cell(30,4,$cuenta_entidad,1,0);
                                $pdf->Cell(10,4,"",0,0);
                                $pdf->SetFont('Arial',"B","8");
                                $pdf->Cell(20,4,"TOTAL",1,0,"L",true);
                                $pdf->Cell(15,4,$simbolo,1,0,"C",true);
                                $pdf->Cell(20,4,number_format($row['ntotal'], 2, '.', ','),1,1,"R",true);
                        }

                        $pdf->Output($filename,'F');

                        $this->passDocument($codigo,$file);

                        return $filename;
                    }
                }
                    
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getContac($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                            cm_entidadcon.cnombres,
                                                            cm_entidadcon.ctelefono1,
                                                            cm_entidadcon.cemail 
                                                        FROM
                                                            cm_entidadcon 
                                                        WHERE
                                                            cm_entidadcon.id_centi =:cod 
                                                            LIMIT 1");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowcount();


                if ($rowCount > 0) {
                    
                    while ($row = $query->fetch()) {
                        $item['nombre']     = $row['cnombres'];
                        $item['telefono']   = $row['ctelefono1'];
                        $item['mail']       = $row['cemail'];
                    }
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }

        public function getBank($cod,$mon){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        cm_entidadbco.ncodbco,
                                                        cm_entidadbco.cnrocta,
                                                        cm_entidadbco.ctipcta,
                                                        tb_banco.cdesbco,
                                                        tb_moneda.dmoneda,
                                                        tb_moneda.cmoneda 
                                                    FROM
                                                        cm_entidadbco
                                                        INNER JOIN tb_banco ON cm_entidadbco.ncodbco = tb_banco.ccodbco
                                                        INNER JOIN tb_moneda ON cm_entidadbco.cmoneda = tb_moneda.ncodmon 
                                                    WHERE
                                                        cm_entidadbco.id_centi = :cod 
                                                        AND cm_entidadbco.cmoneda = :mon");
                $query->execute(["cod"=>$cod,"mon"=>$mon]);
                $rowCount = $query->rowcount();

                if ($rowCount > 0) {
                    while ($row = $query->fetch()) {
                        $item['cta']    = $row['cnrocta'];
                        $item['banco']  = $row['cdesbco'];
                        $item['moneda'] = $row['dmoneda'];
                        $item['cmoneda'] = $row['cmoneda'];
                    }
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }

        public function getDataProvee($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        cm_entidad.cnumdoc,
                                                        cm_entidad.crazonsoc,
                                                        cm_entidad.cviadireccion,
                                                        cm_entidad.ctelefono,
                                                        cm_entidad.cemail,
                                                        cm_entidad.nagenret 
                                                    FROM
                                                        cm_entidad 
                                                    WHERE
                                                        cm_entidad.id_centi = :cod");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowcount();

                if($rowCount > 0) {
                    while ($row = $query->fetch()) {
                        $item['ruc']        = $row['cnumdoc'];
                        $item['razon']      = $row['crazonsoc'];
                        $item['direccion']  = $row['cviadireccion'];
                        $item['telefono']   = $row['ctelefono'];
                        $item['correo']     = $row['cemail'];
                        $item['retencion']  = $row['nagenret'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getDataPed($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        lg_registro.cnumero,
                                                        lg_registro.ffechadoc,
                                                        lg_registro.id_cuser,
                                                        tb_proyecto1.cdespry,
                                                        tb_proyecto1.ccodpry,
                                                        lg_registro.mdetalle 
                                                    FROM
                                                        lg_registro
                                                        INNER JOIN tb_proyecto1 ON lg_registro.ncodpry = tb_proyecto1.ncodpry 
                                                    WHERE
                                                        lg_registro.id_regmov = :cod");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowcount();

                if($rowCount > 0) {
                    while ($row = $query->fetch()) {
                        $item['numero']     = $row['cnumero'];
                        $item['fecha']      = $row['ffechadoc'];
                        $item['usuario']    = $row['id_cuser'];
                        $item['nombre']     = $row['cdespry'];
                        $item['codigo']     = $row['ccodpry'];
                        $item['detalle']    = $row['mdetalle'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getOrderDetailsToDoc($cod,$ped){
            $item = array();
            $data = [];
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
                        $item['cont']       = $cont;
                        $item['id_cprod']   = $row['id_cprod'];
                        $item['cdespro']    = $row['cdesprod'];
                        $item['cabrevia']   = $row['cabrevia'];
                        $item['nvventa']    = $row['nvventa'];
                        $item['ncanti']     = $row['ncanti'];
                        $item['ntotal']     = $row['ntotal'];
                        $item['nidpedi']    = $row['nidpedi'];
                        $item['ped']        = $ped;

                        array_push($data,$item);
                    }
                }

                return $data;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function passDocument($codigo,$filename){
            try {
                $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET cdocPdf=:fil WHERE id_regmov=:cod");
                $sql->execute(["fil"=>$filename,
                               "cod"=>$codigo]);

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function existDocument($codigo){
            try {
                $sql = $this->db->connect()->prepare("SELECT cdocPdf FROM lg_regabastec WHERE id_regmov=:cod");
                $sql->execute(["cod"=>$codigo]);
                $result  = $sql->fetch();


               return $result[0];

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function sendMail($orden,$pedido,$correo,$mensaje){
            require_once("public/PHPMailer/PHPMailerAutoload.php");
            try {
                $filename = $this->getAtach($orden);

                if (!$filename) {
                    $filename = $this->generateDocument($orden);
                }

                $enviado = false;

                $origen = $_SESSION['user']."@sepcon.net";
                $nombre_envio = $_SESSION['nombres'];
                $title = utf8_decode("Atención de Orden");

                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->SMTPDebug = 2;
                $mail->Debugoutput = 'html';
                $mail->Host = 'mail.sepcon.net';
                $mail->SMTPAuth = true;
                $mail->Username = 'sistema_ibis@sepcon.net';
                $mail->Password = $_SESSION['password'];
                $mail->Port = 465;
                $mail->SMTPSecure = "ssl";
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => false
                    )
                );
                
                $mail->setFrom($origen,$nombre_envio);
                $mail->addAddress($correo,$correo);
                $mail->Subject = $title;

                $mail->msgHTML(utf8_decode($mensaje));

                $atach = "public/ordenes/aprobadas/".$filename;

                if (file_exists($atach)){
                    $mail->addAttachment($atach);
                }

                if (!$mail->send()) {
                    $enviado = false;
			    }else {
                    $enviado = true;
                    $this->updateRequest($orden,$pedido);
                    $this->updateOrder($orden);
                    $this->saveAction("CORREO",$orden,"SEGUIMIENTO",$_SESSION['user']);
                }

                return $enviado;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getAtach($orden){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                            lg_regabastec.id_regmov,
                                                            lg_regabastec.cdocPDF 
                                                        FROM
                                                            lg_regabastec 
                                                        WHERE
                                                            lg_regabastec.id_regmov = :ord");
                $sql->execute(["ord"=>$orden]);
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($rs = $sql->fetch()) {
                       $filename = $rs['cdocPDF'];
                    }
                }

                return $filename;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function updateRequest($idorden,$pedido){
            $ordenes = $this->getOrderDetailsToDoc($idorden,$pedido);
            $nreg = count($ordenes);

            for ($i=0; $i < $nreg; $i++) { 
                $sql = $this->db->connect()->prepare("UPDATE lg_detapedido SET idreg_ref=:ord WHERE nidpedi =:idreg");
                $sql->execute(["idreg"=>$ordenes[$i]['nidpedi'],"ord"=>$idorden]);
            }
        }

        public function updateOrder($orden){
            try {
                $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nEstadoDoc=:est WHERE id_regmov =:idreg");
                $sql->execute(["idreg"=>$orden,"est"=>4]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        
    }
?>