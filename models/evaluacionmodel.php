<?php
    class EvaluacionModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerRegistros(){
            try {
                $salida = "";
                $sql = $this->db->connect()->query("SELECT 
                                                    tb_califica.idreg,
                                                    cm_entidad.crazonsoc,
                                                    lg_pedidocab.cnumero AS pedido,
                                                    lg_regabastec.cnumero AS orden,
                                                    tb_califica.nParticipa,
                                                    tb_califica.nEntrega,
                                                    tb_califica.nCalidad,
                                                    tb_califica.nAlmacen,
                                                    tb_califica.nParticipa + tb_califica.nEntrega + tb_califica.nCalidad + tb_califica.nAlmacen AS puntaje,
                                                    cm_entidad.id_centi, 
	                                                lg_pedidocab.ctipmov 
                                                FROM
                                                    tb_califica
                                                    INNER JOIN cm_entidad ON tb_califica.id_centi = cm_entidad.id_centi
                                                    INNER JOIN lg_pedidocab ON tb_califica.id_pedido = lg_pedidocab.id_regmov
                                                    INNER JOIN lg_regabastec ON tb_califica.id_orden = lg_regabastec.id_regmov");
                $sql->execute();
                $rowCount = $sql->rowcount();
                $item = 0;

                if ($rowCount){
                    while ($rs = $sql->fetch()) {
                        $item++;
                        
                        $estado = $rs['puntaje'] == 35 ? 'maximo':0;
                        
                        $salida .='<tr>
                                <td class="con_borde centro">'.str_pad($item,3,0,STR_PAD_LEFT).'</td>
                                <td class="con_borde pl20" ">'.$rs['crazonsoc'].'</td>
                                <td class="con_borde centro">'.$rs['pedido'].'</td>
                                <td class="con_borde centro">'.$rs['orden'].'</td>
                                <td class="con_borde drch pr20">'.$rs['nParticipa'].'</td>
                                <td class="con_borde drch pr20">'.$rs['nEntrega'].'</td>
                                <td class="con_borde drch pr20">'.$rs['nCalidad'].'</td>
                                <td class="con_borde drch pr20">'.$rs['nAlmacen'].'</td>
                                <td class="con_borde drch pr20 '.$estado.'" >'.$rs['puntaje'].'</td>
                                <td class="con_borde centro"><a href="'.$rs['idreg'].'" 
                                            data-ruc="'.$rs['id_centi'].'"
                                            data-tipo="'.$rs['ctipmov'].'"><i class="far fa-edit"></i></a></td>
                            </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function calificar($id,$enti){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                    COUNT( tb_califica.id_centi ) AS atenciones,
                                    SUM( tb_califica.nParticipa + tb_califica.nEntrega + tb_califica.nCalidad + tb_califica.nAlmacen ) AS puntaje 
                                FROM
                                    tb_califica 
                                WHERE
                                    tb_califica.id_centi =:enti 
                                    AND tb_califica.id_orden IS NOT NULL");
                $sql->execute(["enti"=>$enti]);
                $result= $sql->fetchAll();

                return array("puntaje"=>$result[0]['puntaje'],"atenciones"=>$result[0]['atenciones']);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>