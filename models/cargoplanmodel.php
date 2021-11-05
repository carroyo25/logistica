<?php
    class CargoPlanModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerRegistros(){
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT
                lg_pedidodet.id_centi,
                lg_pedidodet.id_cprod,
                lg_pedidodet.id_ingreso,
                lg_pedidodet.id_salida,
                cm_producto.ccodprod,
                cm_producto.cdesprod,
                lg_pedidodet.ncantpedi,
                lg_pedidodet.nfactor,
                lg_pedidodet.ncantaten,
                lg_pedidodet.nEstadoReg,
                estados.cdesprm2 AS estado,
                lg_pedidocab.cnumero AS pedido,
                tb_proyecto1.ccodpry,
                tb_area.cdesarea,
                lg_pedidocab.ffechadoc AS fechapedido,
                atencion.cdesprm2 AS atencion,
                lg_regabastec.cnumero AS orden,
                lg_regabastec.id_regmov,
                tb_moneda.dmoneda,
                lg_pedidocab.ctipmov,
                lg_pedidocab.cper,
                tb_sysusuario.cnombres,
                tb_unimed.cabrevia,
                lg_pedidodet.ncantapro,
                lg_regabastec.ffechadoc AS fechaorden,
                lg_pedidodet.nidpedi   
            FROM
                lg_pedidodet
                INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod
                INNER JOIN tb_paramete2 AS estados ON lg_pedidodet.nEstadoReg = estados.ccodprm2
                INNER JOIN lg_pedidocab ON lg_pedidodet.id_regmov = lg_pedidocab.id_regmov
                INNER JOIN tb_proyecto1 ON lg_pedidocab.ncodpry = tb_proyecto1.ncodpry
                INNER JOIN tb_area ON lg_pedidocab.ncodarea = tb_area.ncodarea
                INNER JOIN tb_paramete2 AS atencion ON lg_pedidodet.nTipoAten = atencion.ccodprm2
                LEFT JOIN lg_regabastec ON lg_pedidodet.id_regmov = lg_regabastec.id_refpedi
                LEFT JOIN tb_moneda ON lg_regabastec.ncodmon = tb_moneda.ncodmon
                LEFT JOIN tb_sysusuario ON lg_pedidocab.ncodaproba = tb_sysusuario.id_cuser
                INNER JOIN tb_unimed ON lg_pedidodet.ncodmed = tb_unimed.ncodmed 
            WHERE
                estados.ncodprm1 = 4 
                AND atencion.ncodprm1 = 13");
                $query->execute();
                $item = 1;
                $rowCount = $query->rowcount();
                if ($rowCount){
                    while ($rs = $query->fetch()) {
                        $fechaOrden = is_null($rs['fechaorden']) ? " ":date("d/m/Y", strtotime($rs['fechaorden']));
                        $salida.='<tr>
                                    <td class="con_borde centro">'.str_pad($item++,4,0,STR_PAD_LEFT).'</td>
                                    <td class="con_borde '.strtolower($rs['estado']).' pl10">'.$rs['estado'].'</td>
                                    <td class="con_borde pl10">'.$rs['ccodpry'].'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cdesarea']).'</td>
                                    <td class="con_borde '.strtolower($rs['atencion']).' pl10">'.$rs['atencion'].'</td>
                                    <td class="con_borde centro">'.$rs['ctipmov'].'</td>
                                    <td class="con_borde centro">'.$rs['cper'].'</td>
                                    <td class="con_borde centro">'.$rs['pedido'].'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['fechapedido'])).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cnombres']).'</td>
                                    <td class="con_borde centro">'.$rs['ccodprod'].'</td>
                                    <td class="con_borde centro">'.$rs['cabrevia'].'</td>
                                    <td class="con_borde pl10">'.$rs['cdesprod'].'</td>
                                    <td class="con_borde drch pr10">'.number_format($rs['ncantapro'], 2, '.', ',').'</td>
                                    <td class="con_borde centro">'.$rs['orden'].'</td>
                                    <td class="con_borde centro">'.$fechaOrden.'</td>
                                    <td class="con_borde centro"><a href="'.$rs['nidpedi'].'"><i class="fas fa-search"></i></a></td>
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