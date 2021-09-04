<?php
    class OrdenesModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerOrdenesUser($user){
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
                        $salida = '<tr><td colspan="11" class="centro">No hay ordenes procesadas</td></tr>';
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

        public function generarNumeroOrden(){
            try {
                $query = $this->db->connect()->query("SELECT COUNT(lg_regabastec.id_regmov) AS numero FROM lg_regabastec");
                $query->execute();
                $result= $query->fetchAll();

                $numero = str_pad($result[0]['numero']+1,5,0,STR_PAD_LEFT); 

                return $numero;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function verPedidoActivados($user){
            try {
                $salida="";
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.tb_proyusu.ncodproy,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_ccostos.ccodcos,
                                                        logistica.tb_ccostos.cdescos,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        rrhh.tabla_aquarius.dni,
                                                        logistica.lg_pedidocab.cnumero,
                                                        logistica.lg_pedidocab.ffechadoc,
                                                        logistica.lg_pedidocab.ffechaven,
                                                        logistica.lg_pedidocab.cconcepto,
                                                        logistica.lg_pedidocab.id_regmov 
                                                    FROM
                                                        logistica.tb_proyusu
                                                        INNER JOIN logistica.lg_pedidocab ON tb_proyusu.ncodproy = lg_pedidocab.ncodpry
                                                        INNER JOIN logistica.tb_proyecto1 ON lg_pedidocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_ccostos ON lg_pedidocab.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN logistica.tb_area ON lg_pedidocab.ncodarea = tb_area.ncodarea
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal 
                                                    WHERE
                                                        tb_proyusu.nflgactivo = 1 
                                                        AND tb_proyusu.id_cuser = :user
                                                        AND lg_pedidocab.nEstadoDoc = 7 
                                                    ORDER BY
	                                                    logistica.lg_pedidocab.cnumero ASC");
                $query->execute(["user"=>$user]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0){
                    while ($rs = $query->fetch()) {
                        $salida .='<tr class="pointertr">
                                    <td class="con_borde centro">'.$rs['cnumero'].'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['ffechadoc'])).'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['ffechaven'])).'</td>>
                                    <td class="con_borde pl10">'.strtoupper($rs['cconcepto']).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cdesarea']).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cdespry']).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['nombres']." ".$rs['apellidos']).'</td>
                                    <td class="con_borde centro"><a href="'.$rs['id_regmov'].'"><i class="fas fa-exchange-alt"></i></a></td>
                                </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerPedidoPorId($cod){
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

        public function obtenerDetallesPorId($cod) {
            try {
                $salida="";
                $query = $this->db->connect()->prepare("SELECT
                                            lg_pedidodet.id_regmov,
                                            lg_pedidodet.nidpedi,
                                            lg_pedidodet.id_centi,
                                            lg_pedidodet.id_cprod,
                                            lg_pedidodet.ncodmed,
                                            lg_pedidodet.nfactor,
                                            lg_pedidodet.ncantapro,
                                            lg_pedidodet.nprecioref,
                                            lg_pedidodet.nEstadoPed,
                                            lg_pedidodet.nflgaprueba,
                                            lg_pedidodet.nflgactivo,
                                            cm_producto.ccodprod,
                                            cm_producto.cdesprod,
                                            tb_unimed.cabrevia,
                                            cm_entidad.cnumdoc,
                                            cm_entidad.crazonsoc 
                                        FROM
                                            lg_pedidodet
                                            INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod
                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed
                                            INNER JOIN cm_entidad ON lg_pedidodet.id_centi = cm_entidad.id_centi 
                                        WHERE
                                            lg_pedidodet.id_regmov = :cod 
                                            AND lg_pedidodet.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $x=1;

                if ($rowcount> 0) {
                    while ($rs = $query->fetch()) {
                        $salida .='<tr class="h25px">
                                        <td class="con_borde centro" data-unitario="'.$rs['nprecioref'].'"><input type="checkbox"></td>
                                        <td class="con_borde centro">'.str_pad($x,3,0,STR_PAD_LEFT).'</td>
                                        <td class="con_borde centro">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde pl10">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde centro">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde drch pr10">'.number_format($rs['ncantapro'], 2, '.', ',').'</td>
                                        <td class="con_borde pl10"></td>
                                        <td class="con_borde pl20">'.$rs['crazonsoc'].'</td>
                                    </tr>';
                        $x++;
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