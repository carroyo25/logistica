<?php
    class AdjudicacionModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getMainRecords(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT logistica.lg_pedidocab.id_regmov,
                                                        logistica.lg_pedidocab.ffechadoc,
                                                        logistica.lg_pedidocab.ffechaven,
                                                        logistica.lg_pedidocab.cconcepto,
                                                        logistica.lg_pedidocab.nEstadoDoc,
                                                        logistica.lg_pedidocab.id_cuser,
                                                        logistica.lg_pedidocab.ncodmov,
                                                        logistica.lg_pedidocab.cnumero,
                                                        logistica.lg_pedidocab.nNivAten,
                                                        DATEDIFF(lg_pedidocab.ffechaven,lg_pedidocab.ffechadoc) AS prioridad,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        atenciones.cdesprm2 AS atencion,
                                                        estados.cdesprm2 AS estado,
                                                        logistica.tb_sysusuario.cnombres 
                                                    FROM
                                                        logistica.lg_pedidocab
                                                        INNER JOIN logistica.tb_proyecto1 ON logistica.lg_pedidocab.ncodpry = logistica.tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_area ON logistica.lg_pedidocab.ncodarea = logistica.tb_area.ncodarea
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2
                                                        INNER JOIN logistica.tb_sysusuario ON logistica.lg_pedidocab.ncodaproba = logistica.tb_sysusuario.id_cuser 
                                                    WHERE
                                                        atenciones.ncodprm1 = 13 
                                                        AND estados.ncodprm1 = 4 
                                                        AND logistica.lg_pedidocab.nEstadoDoc = 5");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        if ($row['prioridad'] <= 7){
                            $prioridad = "urgente";
                        } else {
                            $prioridad = "normal";
                        }
                        $salida .='<tr class="h35px" data-idx="'.$row['id_regmov'].'">
                                        <td class="con_borde centro">'.$row['cnumero'].'</td>
                                        <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                        <td class="con_borde pl10">'.$row['cconcepto'].'</td>
                                        <td class="con_borde pl10">'.$row['ccodarea'].' '.$row['cdesarea'].'</td>
                                        <td class="con_borde pl10">'.$row['ccodpry'].' '.$row['cdespry'].'</td>
                                        <td class="con_borde pl10">'.$row['apellidos'].' '.$row['nombres'].'</td>
                                        <td class="con_borde pl10">'.strtoupper($row['cnombres']).'</td>
                                        <td class="con_borde centro '. strtolower($row['estado']) .'">'.$row['estado'].'</td>
                                        <td class="con_borde centro '. $prioridad .'">'.strtoupper($prioridad).'</td>
                                        <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="editar"  title="editar"><i class="far fa-edit"></i></a></td>
                                    </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getRegById($cod){
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
                                                            logistica.lg_pedidocab.ffechaven,
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
                                        $item['ncodmov']        = $row['ncodmov'];
                                        $item['ccoddoc']        = $row['ccoddoc'];
                                        $item['cserie']         = $row['cserie'];
                                        $item['cnumero']        = $row['cnumero'];
                                        $item['ffechadoc']      = $row['ffechadoc'];
                                        $item['ffechaven']      = $row['ffechaven'];
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
                    }
                }

                return $item;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function obtenerProformas($cod){
            try {
                $proveedores = 0;
                $codpr = [];
                $salida='<thead>
                            <tr>
                                <th rowspan="2">ITEM</th>
                                <th rowspan="2">DESCRIPCION</th>
                            ';
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_proformacab.id_regmov,
                                                        lg_proformacab.id_centi,
                                                        cm_entidad.crazonsoc 
                                                    FROM
                                                        lg_proformacab
                                                        INNER JOIN cm_entidad ON lg_proformacab.id_centi = cm_entidad.id_centi 
                                                    WHERE
                                                        lg_proformacab.id_regmov = :cod");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0){
                    while ($rs = $query->fetch()) {
                        $salida .= '<th colspan="4">'.$rs['crazonsoc'].'</th>';
                        $codpr[$proveedores] = $rs['id_centi'];
                        $proveedores++;
                    }
                }

                $salida .= '</tr><tr>';

                for ($i=0; $i <$proveedores ; $i++) { 
                    $salida .= '<th>Precio</th>
					            <th>F.Entrega</th>
					            <th>Dias</th>
					            <th>Adjunto</th>';
                }

                $salida .= '</tr></thead>';

                $cuerpo = $this->detalleProducto($cod,$codpr);

                $salida = $salida.$cuerpo;

                return $salida;
                
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function detalleProducto($cod,$codpr){
            try {
                $detalle = "<tbody>";
                $item=0;
                $linea = 1;
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_pedidodet.nidpedi,
                                                        lg_pedidodet.id_cprod,
                                                        cm_producto.cdesprod 
                                                    FROM
                                                        lg_pedidodet
                                                        INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod 
                                                    WHERE
                                                        lg_pedidodet.id_regmov = :cod");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
 
                if ($rowcount > 0){
                    while ($rs = $query->fetch()) {
                         $detalle .= '<tr>
                                        <td class="con_borde centro">'.str_pad($linea++,3,"0",STR_PAD_LEFT).'</td>
                                        <td class="con_borde pl10">'.$rs['cdesprod'].'</td>'.$this->detalleCotizacion($cod,$codpr,$rs['nidpedi']);
                    }
                }

                $detalle.="</tr></tbody>";

                return $detalle;

            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function detalleCotizacion($cod,$enti,$nid){
            $nreg = count($enti);
            $proformas = "";

            for ($i=0; $i < $nreg; $i++) { 
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_proformadet.id_regmov,
                                                        lg_proformadet.niddet,
                                                        lg_proformadet.id_centi,
                                                        lg_proformadet.cantcoti,
                                                        lg_proformadet.ffechaent,
                                                        lg_proformadet.precunit,
                                                        lg_proformadet.cantcoti * lg_proformadet.precunit AS total 
                                                    FROM
                                                        lg_proformadet 
                                                    WHERE
                                                        lg_proformadet.id_regmov =:cod 
                                                        AND lg_proformadet.id_centi =:ent 
                                                        AND lg_proformadet.niddet =:nid");

                $query->execute(["cod"=>$cod,"ent"=>$enti[$i],"nid"=>$nid]);
                $rs = $query->fetchAll();

                $proformas.= '<td>'.number_format($rs[0]['total'], 2, '.', ',').'</td>
                            <td>'.$rs[0]['ffechaent'].'</td>
                            <td></td>
                            <td>'.$nid.'</td>';
            }

            return $proformas;
        }
    }
?>