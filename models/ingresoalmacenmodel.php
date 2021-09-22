<?php
    class IngresoAlmacenModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerGuiasDespacho($user){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.lg_docusunat.ffechdoc,
                                                        logistica.lg_docusunat.ffechtrasl,
                                                        logistica.lg_docusunat.cnumero,
                                                        logistica.alm_despachocab.ncodpry,
                                                        logistica.alm_despachocab.ncodarea,
                                                        logistica.alm_despachocab.ncodcos,
                                                        logistica.alm_despachocab.idref_ord,
                                                        logistica.lg_docusunat.ncodalm1,
                                                        logistica.lg_docusunat.ncodalm2,
                                                        almacen_origen.cdesalm AS almorigen,
                                                        almacen_destino.cdesalm AS destino,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_ccostos.ccodcos,
                                                        logistica.tb_ccostos.cdescos,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.lg_pedidocab.cnumero AS pedido,
                                                        logistica.lg_regabastec.cnumero AS orden,
                                                        CONCAT ( rrhh.tabla_aquarius.apellidos, ' ', rrhh.tabla_aquarius.nombres ) AS solicitante,
                                                        logistica.tb_almausu.id_cuser,
                                                        logistica.lg_docusunat.id_refmov,
                                                        logistica.alm_despachocab.id_regalm AS salida,
                                                        logistica.alm_recepcab.id_regalm AS ingreso 
                                                    FROM
                                                        logistica.lg_docusunat
                                                        INNER JOIN logistica.alm_despachocab ON lg_docusunat.id_salida = alm_despachocab.id_regalm
                                                        INNER JOIN logistica.tb_almacen AS almacen_origen ON lg_docusunat.ncodalm1 = almacen_origen.ncodalm
                                                        INNER JOIN logistica.tb_almacen AS almacen_destino ON lg_docusunat.ncodalm2 = almacen_destino.ncodalm
                                                        INNER JOIN logistica.tb_proyecto1 ON alm_despachocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_ccostos ON alm_despachocab.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN logistica.tb_area ON alm_despachocab.ncodarea = tb_area.ncodarea
                                                        INNER JOIN logistica.lg_pedidocab ON alm_despachocab.idref_pedi = lg_pedidocab.id_regmov
                                                        INNER JOIN logistica.lg_regabastec ON alm_despachocab.idref_ord = lg_regabastec.id_regmov
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_almausu ON logistica.lg_docusunat.ncodalm2 = logistica.tb_almausu.ncodalm
                                                        INNER JOIN logistica.alm_recepcab ON logistica.lg_regabastec.id_refpedi = logistica.alm_recepcab.idref_pedi 
                                                    WHERE
                                                        logistica.tb_almausu.nflgactivo = 1 
                                                        AND logistica.tb_almausu.id_cuser =:user");
                $query->execute(["user"=>$user]);
                $rowCount = $query->rowCount();

                if ($rowCount > 0) {
                    while ($rs = $query->fetch()) {
                        $salida .='<tr>
                                        <td class="con_borde centro">'.$rs['cnumero'].'</td>
                                        <td class="con_borde centro">'.$rs['ffechdoc'].'</td>
                                        <td class="con_borde pl20">'.$rs['ffechtrasl'].'</td>
                                        <td class="con_borde pl20">'.$rs['almorigen'].'</td>
                                        <td class="con_borde pl20 mayusculas">'.$rs['cdespry'].'</td>
                                        <td class="con_borde pl20 mayusculas">'.$rs['cdescos'].'</td>
                                        <td class="con_borde pl20 mayusculas">'.$rs['cdesarea'].'</td>
                                        <td class="con_borde centro">'.$rs['solicitante'].'</td>
                                        <td class="con_borde centro">'.$rs['pedido'].'</td>
                                        <td class="con_borde centro">'.$rs['orden'].'</td>
                                        <td class="con_borde centro"><a href="'.$rs['id_refmov'].'"data-ingreso="'.$rs['ingreso'].'"><i class="fas fa-pen-alt"></i></a></td>
                                    </tr>';
                    }
                }else{
                    $salida = '<tr><tr class="con_borde" span="12">No se encontraron registros</tr></tr>';
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obternerDespachoId($idx){
            try {
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.lg_docusunat.ffechdoc,
                                                        logistica.lg_docusunat.cnumero AS guia,
                                                        logistica.lg_docusunat.nbultos,
                                                        logistica.lg_docusunat.npesotot,
                                                        logistica.alm_despachocab.cnumguia,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.tb_ccostos.ccodcos,
                                                        logistica.tb_ccostos.cdescos,
                                                        logistica.alm_despachocab.id_regalm,
                                                        logistica.alm_despachocab.idref_ord,
                                                        logistica.alm_despachocab.idref_pedi,
                                                        logistica.alm_despachocab.idref_abas,
                                                        logistica.lg_pedidocab.cnumero AS pedido,
                                                        logistica.lg_pedidocab.ffechadoc AS fechapedido,
                                                        CONCAT ( rrhh.tabla_aquarius.apellidos, ' ', rrhh.tabla_aquarius.nombres ) AS solicita,
                                                        logistica.lg_regabastec.cnumero AS orden,
                                                        logistica.lg_regabastec.ffechadoc AS fechaorden,
                                                        logistica.lg_pedidocab.mdetalle,
                                                        logistica.tb_almacen.cdesalm,
                                                        LPAD( logistica.alm_despachocab.ncodmov, 5, 0 ) AS ncodmov,
                                                        logistica.lg_docusunat.ffechtrasl,
                                                        logistica.tb_paramete2.cdesprm2 AS estado 
                                                    FROM
                                                        logistica.lg_docusunat
                                                        INNER JOIN logistica.alm_despachocab ON lg_docusunat.id_salida = alm_despachocab.id_regalm
                                                        INNER JOIN logistica.tb_proyecto1 ON alm_despachocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_area ON alm_despachocab.ncodarea = tb_area.ncodarea
                                                        INNER JOIN logistica.tb_ccostos ON alm_despachocab.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN logistica.lg_pedidocab ON alm_despachocab.idref_pedi = lg_pedidocab.id_regmov
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.lg_regabastec ON logistica.alm_despachocab.idref_ord = logistica.lg_regabastec.id_regmov
                                                        INNER JOIN logistica.tb_almacen ON logistica.lg_docusunat.ncodalm1 = logistica.tb_almacen.ncodalm
                                                        INNER JOIN logistica.tb_paramete2 ON logistica.alm_despachocab.nEstadoDoc = logistica.tb_paramete2.ccodprm2 
                                                    WHERE
                                                        lg_docusunat.id_refmov = :idx 
                                                        AND logistica.tb_paramete2.ncodprm1 = 4");
                $query->execute(["idx"=>$idx]);
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

        public function obternerDetallesDespacho($idx){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        alm_despachodet.niddeta,
                                                        alm_despachodet.id_cprod,
                                                        alm_despachodet.ncantidad,
                                                        alm_despachodet.nfactor,
                                                        alm_despachodet.ncoduni,
                                                        alm_despachodet.nflgactivo,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        tb_unimed.cabrevia,
                                                        alm_despachodet.niddetaPed 
                                                    FROM
                                                        alm_despachodet
                                                        INNER JOIN cm_producto ON alm_despachodet.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN tb_unimed ON alm_despachodet.ncoduni = tb_unimed.ncodmed 
                                                    WHERE
                                                        alm_despachodet.id_regalm =:idx");
                $query->execute(["idx"=>$idx]);

                $rowCount = $query->rowCount();
                $item = 1;

                if ($rowCount > 0) {
                    while ($rs = $query->fetch()) {
                        $salida .= '<tr>
                                        <td class="con_borde centro"><input type="checkbox"></td>
                                        <td class="con_borde centro"><a href="'.$rs['niddetaPed'].'" data-idprod="'.$rs['id_cprod'].'"><i class="fas fa-barcode"></i></a></td>
                                        <td class="con_borde centro">'.str_pad($item++,3,0,STR_PAD_LEFT ).'</td>
                                        <td class="con_borde pl10">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde pl10">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde centro">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde drch pr10">'.number_format($rs['ncantidad'], 2, '.', ',').'</td>
                                        <td class="con_borde"><input type="text" class="pl20"></td>
                                        <td class="con_borde"></td>
                                    </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function consultarSeries($id,$ing){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                            cm_prodserie.id_cprod,
                                                            cm_prodserie.cdesserie 
                                                        FROM
                                                            cm_prodserie 
                                                        WHERE
                                                            cm_prodserie.id_cprod =:id 
                                                            AND cm_prodserie.idref_movi =:ing");
                $query->execute(["id"=>$id,"ing"=>$ing]);

                $rowCount = $query->rowcount();
                if ($rowCount > 0) {
                    $item = 1;
                    while ($rs = $query->fetch()) {
                        $salida .='<tr>
                                        <td class="con_borde centro"><input type="checkbox"></td>
                                        <td class="con_borde centro">'.str_pad($item++,3,0,STR_PAD_LEFT).'</td>
                                        <td class="con_borde pl10">'.$rs['cdesserie'].'</td>
                                        <td class="con_borde"><input type="text" class="pl20"></td>
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