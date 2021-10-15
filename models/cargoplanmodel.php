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
                                                        lg_pedidodet.id_orden,
                                                        lg_pedidodet.id_salida,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        lg_pedidodet.ncantpedi,
                                                        lg_pedidodet.nfactor,
                                                        lg_pedidodet.ncodmed,
                                                        lg_pedidodet.ncantaten,
                                                        lg_pedidodet.ncodmon,
                                                        lg_pedidodet.nEstadoReg,
                                                        lg_pedidodet.pedido,
                                                        lg_pedidodet.orden,
                                                        estados.cdesprm2 AS estado,
                                                        lg_pedidocab.cnumero,
                                                        tb_proyecto1.ccodpry,
                                                        tb_area.cdesarea,
                                                        lg_pedidocab.ffechadoc 
                                                    FROM
                                                        lg_pedidodet
                                                        INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN tb_paramete2 AS estados ON lg_pedidodet.nEstadoReg = estados.ccodprm2
                                                        INNER JOIN lg_pedidocab ON lg_pedidodet.id_regmov = lg_pedidocab.id_regmov
                                                        INNER JOIN tb_proyecto1 ON lg_pedidocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN tb_area ON lg_pedidocab.ncodarea = tb_area.ncodarea 
                                                    WHERE
                                                        estados.ncodprm1 = 4");
                $query->execute();
                $rowCount = $query->rowcount();
                if ($rowCount){
                    while ($rs = $query->fetch()) {
                        $salida.='<tr>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
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
    }
?>