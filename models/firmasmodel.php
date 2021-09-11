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
                                                        lg_pedidocab.cconcepto,
                                                        lg_pedidocab.mobserva,
                                                        lg_regabastec.nfirmaLog,
                                                        lg_regabastec.nfirmaFin,
                                                        lg_regabastec.nfirmaOpe,
                                                        lg_pedidocab.cnumero AS pedido,
                                                        lg_regabastec.id_refpedi
                                                    FROM
                                                        lg_regabastec
                                                        INNER JOIN tb_area ON lg_regabastec.ncodarea = tb_area.ncodarea
                                                        INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN tb_ccostos ON lg_regabastec.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN tb_moneda ON lg_regabastec.ncodmon = tb_moneda.ncodmon
                                                        INNER JOIN tb_paramete2 ON lg_regabastec.nNivAten = tb_paramete2.ccodprm2
                                                        INNER JOIN lg_pedidocab ON lg_regabastec.id_refpedi = lg_pedidocab.id_regmov 
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

        public function obtenerOrdenesId($cod){
            try {
                $query = $this->db->connect()->prepare("SELECT
                                                        orden.id_regmov,
                                                        orden.id_refpedi,
                                                        orden.ctipmov,
                                                        orden.cnumero AS orden,
                                                        orden.ffechadoc,
                                                        orden.ffechaent,
                                                        orden.id_centi,
                                                        orden.ncodmon,
                                                        orden.ntcambio,
                                                        orden.nigv,
                                                        orden.nexonera,
                                                        orden.ntotal,
                                                        orden.ncodalm,
                                                        orden.ncodpry,
                                                        orden.ncodcos,
                                                        orden.ncodarea,
                                                        orden.ncodper,
                                                        orden.nEstadoDoc,
                                                        orden.id_cuser,
                                                        orden.ncodpago,
                                                        orden.nplazo,
                                                        orden.nfirmaLog,
                                                        orden.nfirmaFin,
                                                        orden.nfirmaOpe,
                                                        orden.cnumcot,
                                                        orden.cdocPDF,
                                                        orden.nflgactivo,
                                                        pagos.cdesprm2 AS pago,
                                                        entrega.cdesprm2 AS entrega,
                                                        logistica.cm_entidad.cnumdoc,
                                                        logistica.cm_entidad.crazonsoc,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_ccostos.ccodcos,
                                                        logistica.tb_ccostos.cdescos,
                                                        pedido.cnumero AS pedido,
                                                        pedido.cconcepto,
                                                        pedido.mdetalle,
                                                        transporte.cdesprm2,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        rrhh.tabla_aquarius.internal,
                                                        logistica.tb_moneda.cabrevia,
                                                        logistica.tb_moneda.dmoneda,
                                                        logistica.tb_almacen.cdesalm 
                                                    FROM
                                                        logistica.lg_regabastec AS orden
                                                        INNER JOIN logistica.tb_paramete2 AS pagos ON orden.ncodpago = pagos.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS entrega ON orden.nplazo = entrega.ccodprm2
                                                        INNER JOIN logistica.cm_entidad ON orden.id_centi = cm_entidad.id_centi
                                                        INNER JOIN logistica.tb_proyecto1 ON orden.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_area ON orden.ncodarea = tb_area.ncodarea
                                                        INNER JOIN logistica.tb_ccostos ON orden.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN logistica.lg_pedidocab AS pedido ON orden.id_refpedi = pedido.id_regmov
                                                        INNER JOIN logistica.tb_paramete2 AS transporte ON orden.ctiptransp = transporte.ccodprm2
                                                        INNER JOIN rrhh.tabla_aquarius ON pedido.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_moneda ON orden.ncodmon = logistica.tb_moneda.ncodmon
                                                        INNER JOIN logistica.tb_almacen ON orden.ncodalm = logistica.tb_almacen.ncodalm 
                                                    WHERE
                                                        orden.id_regmov = :cod 
                                                        AND pagos.ncodprm1 = 11 
                                                        AND entrega.ncodprm1 = 12 
                                                        AND transporte.ncodprm1 = 7");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowCount();
                
                if ($rowCount > 0) {
                    
                    $result = $query->fetchAll();
                    $contacto = $this->obtenerEntidadContacto($result[0]["id_centi"]);

                    $tipo = $result[0]["ctipmov"] == "B" ? "BIENES" : "SERVICIOS";

                    $salida = array("orden"=>$result[0]["id_regmov"],
                                    "pedido"=>$result[0]["id_refpedi"],
                                    "nropedido"=>$result[0]["pedido"],
                                    "id_entidad"=>$result[0]["id_centi"],
                                    "cod_proyecto"=>$result[0]["ncodpry"],
                                    "cod_area"=>$result[0]["ncodarea"],
                                    "cod_costos"=>$result[0]["ncodcos"],
                                    "cod_transporte"=>$result[0]["cdesprm2"],
                                    "cod_solicitante"=>$result[0]["internal"],
                                    "cod_almacen"=>$result[0]["ncodalm"],
                                    "ordenpdf"=>$result[0]["cdocPDF"],
                                    "tipoPedido"=>$result[0]["ctipmov"],
                                    "mon_abrevia"=>$result[0]["cabrevia"],
                                    "idmoneda"=>$result[0]["ncodmon"],
                                    "idpago"=>$result[0]["ncodpago"],
                                    "identrega"=>$result[0]["nplazo"],
                                    "cotizacion"=>$result[0]["cnumcot"],
                                    "numOrd"=>$result[0]['orden'],
                                    "elaborado"=>$result[0]['id_cuser'],
                                    "proyectoOrd"=>$result[0]['ccodpry'].' '.$result[0]['cdespry'],
                                    "areaOrd"=>$result[0]['ccodarea'].' '.$result[0]['cdesarea'],
                                    "costosOrd"=>$result[0]['ccodcos'].' '.$result[0]['cdescos'],
                                    "conceptoOrd"=>$result[0]['cconcepto'],
                                    "detalleOrd"=>$result[0]['mdetalle'],
                                    "precioOrd"=>number_format($result[0]['ntotal'], 2, '.', ','),
                                    "entrega"=>$result[0]['ffechaent'],
                                    "condpago"=>$result[0]['pago'],
                                    "condentrega"=>$result[0]['entrega'],
                                    "entidad"=>$result[0]['crazonsoc'],
                                    "ruc"=>$result[0]['cnumdoc'],
                                    "atencion"=> $contacto['nombre'],
                                    "transporteOrd"=>$result[0]['cdesprm2'],
                                    "lugarEntrega"=>$result[0]['cdesalm'],
                                    "monedaOrd" =>$result[0]['dmoneda'],
                                    "logistica" =>$result[0]['nfirmaLog'],
                                    "operaciones" =>$result[0]['nfirmaOpe'],
                                    "finanzas" =>$result[0]['nfirmaFin'],
                                    "tipoOrd" =>$tipo,
                                    "fechaOrd" =>$result[0]['ffechadoc']);
                    
                    return $salida;
                }
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
                                                    lg_detaabastec.npventa,
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
                                    <td class="con_borde drch pr20">'.number_format($row['npventa'], 2, '.', ',').'</td>
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
                                                            INNER JOIN logistica.tb_paramete2 AS transportes ON logistica.lg_pedidocab.ctiptransp = transportes.ncodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2 
                                                        WHERE
                                                            logistica.lg_pedidocab.id_regmov = :cod 
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
                                                        lg_pedidodet.nidpedi,
                                                        lg_pedidodet.id_cprod,
                                                        lg_pedidodet.ncantaten,
                                                        ROUND( lg_pedidodet.ncantpedi, 2 ) AS cantidad,
                                                        lg_pedidodet.nEstadoPed,
                                                        tb_unimed.nfactor,
                                                        tb_unimed.cabrevia,
                                                        estados.cdesprm2 AS estado,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        lg_regabastec.cper,
                                                        lg_regabastec.cnumero 
                                                    FROM
                                                        lg_pedidodet
                                                        INNER JOIN tb_unimed ON lg_pedidodet.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN tb_paramete2 AS estados ON lg_pedidodet.nEstadoPed = estados.ccodprm2
                                                        INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod
                                                        LEFT JOIN lg_regabastec ON lg_pedidodet.idreg_ref = lg_regabastec.id_regmov 
                                                    WHERE
                                                        lg_pedidodet.id_regmov = :cod 
                                                        AND estados.ncodprm1 = 4 
                                                        AND lg_pedidodet.nflgactivo = 1");
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
                            <td class="con_borde centro">'.number_format($row['ncantaten'], 2, '.', ',').'</td>
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
                        $salida.='<tr class="h35px">
                                    <td class="con_borde pl20 mayusculas">'.strtoupper($row['cnombres']).'</td>
                                    <td class="con_borde centro"><input type="date" value="'.$row['ffecha'].'" class="sin_borde" readonly></td>
                                    <td class="con_borde pl20 h35px">'.$row['ccomenta'].'</td>
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
                    case '7204': //Henry Peña
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaLog=:fir,codperLog=:usr WHERE id_regmov=:cod");
                        break;
                    case '7152': //Jose Paniagua
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaOpe=:fir,codperOpe=:usr WHERE id_regmov=:cod");
                        break;
                    case '1029': //Mauricio Virreyra
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaFin=:fir,codperFin=:usr WHERE id_regmov=:cod");
                        break;
                    case '1030': //Jorge Taborga
                        $sql = $this->db->connect()->prepare("UPDATE lg_regabastec SET nfirmaOpe=:fir,codperOpe=:usr WHERE id_regmov=:cod");
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

        public function obtenerEntidadContacto($cod){
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
    }
?>