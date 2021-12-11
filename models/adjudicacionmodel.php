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
                                                            INNER JOIN logistica.tb_paramete2 AS transportes ON logistica.lg_pedidocab.ctiptransp = transportes.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2 
                                                        WHERE
                                                            logistica.lg_pedidocab.id_regmov = :cod 
                                                            AND atenciones.ncodprm1 = 13 
                                                            AND estados.ncodprm1 = 4 
                                                            AND transportes.ncodprm1 = 7");
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
                        $salida .= '<th colspan="5">'.$rs['crazonsoc'].'</th>';
                        $codpr[$proveedores] = $rs['id_centi'];
                        $proveedores++;
                    }
                }

                $salida .= '</tr><tr>';

                for ($i=0; $i <$proveedores ; $i++) { 
                    $salida .= '<th>Precio</th>
					            <th>F.Entrega</th>
					            <th>Dias</th>
					            <th>Adj.</th>
                                <th>...</th>';
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
                $item=1;
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
                        $detalle .= '<tr data-fila="'.$item.'">
                                        <td class="con_borde centro">'.str_pad($linea++,3,"0",STR_PAD_LEFT).'</td>
                                        <td class="con_borde pl10">'.$rs['cdesprod'].'</td>'.$this->detalleCotizacion($cod,$codpr,$rs['nidpedi']);
                        $item++;
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
                                                        lg_proformadet.cantcoti * lg_proformadet.precunit AS total,
                                                        CONCAT( lg_proformadet.id_regmov, '_', lg_proformadet.id_centi, '_', lg_proformadet.niddet, '.pdf' ) AS archivo,
                                                        DATE_FORMAT( lg_proformadet.fregsys, '%Y-%m-%d' ) AS emitido1,
                                                        DATEDIFF(
                                                            lg_proformadet.ffechaent,
                                                        DATE_FORMAT( lg_proformadet.fregsys, '%Y-%m-%d' )) AS dias,
                                                        tb_moneda.cabrevia, 
	                                                    lg_proformacab.nafecIgv 
                                                    FROM
                                                        lg_proformadet
                                                        INNER JOIN lg_proformacab ON lg_proformadet.id_centi = lg_proformacab.id_centi 
                                                        AND lg_proformadet.id_regmov = lg_proformacab.id_regmov
                                                        INNER JOIN tb_moneda ON lg_proformacab.ncodmon = tb_moneda.ncodmon 
                                                    WHERE
                                                        lg_proformadet.id_regmov = :cod
                                                        AND lg_proformadet.id_centi = :ent  
                                                        AND lg_proformadet.niddet = :nid");

                $query->execute(["cod"=>$cod,"ent"=>$enti[$i],"nid"=>$nid]);
                $rs = $query->fetchAll();
                $adjunto =  constant("URL")."/public/manuales/".$rs[0]['archivo'];

                $proformas.= '<td class="con_borde drch pr10">'.$rs[0]['cabrevia']." ".number_format($rs[0]['total'], 2, '.', ',').'</td>
                            <td class="con_borde centro">'.date("d/m/Y", strtotime($rs[0]['ffechaent'])).'</td>
                            <td class="con_borde drch pr10">'.$rs[0]['dias'].'</td>
                            <td class="con_borde centro"><a href="'.$adjunto.'"><i class="far fa-sticky-note"></i></a></td>
                            <td class="con_borde centro" data-position="'.$i.'" 
                                                         data-pedido="'.$rs[0]["id_regmov"].'" 
                                                         data-entidad="'.$rs[0]["id_centi"].'"
                                                         data-detalle="'.$rs[0]["niddet"].'"
                                                         data-precio="'.$rs[0]["precunit"].'">
                                <input type="checkbox" class="chkVerificado">
                            </td>';
            }

            return $proformas;
        }

        public function verProformasPdf($cod){
            $salida = "";

            try {
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_proformacab.id_regmov,
                                                        lg_proformacab.id_centi,
                                                        lg_proformacab.cnumero,
                                                        cm_entidad.crazonsoc,
                                                        cm_entidad.cnumdoc,
                                                        concat( lg_proformacab.id_regmov, '_', cm_entidad.cnumdoc,'_', lg_proformacab.cnumero,'.pdf' ) AS archivo 
                                                    FROM
                                                        lg_proformacab
                                                        INNER JOIN cm_entidad ON lg_proformacab.id_centi = cm_entidad.id_centi 
                                                    WHERE
                                                        lg_proformacab.id_regmov =:cod");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0){
                    $salida = "";
                    while($row = $query->fetch()){
                        $adjunto =  constant("URL")."/public/proformas/".$row['archivo'];
                        $salida .='<li><a href="'.$adjunto.'" class="atachDoc"><i class="fas fa-mail-bulk"></i><span>'.$row['crazonsoc'].'</span></a></li>';
                    }
                }else{
                    $salida = 0;
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function grabarDetallesCotizacion($detalles){
                $datos = json_decode($detalles);
                $nreg = count($datos);
                $actualizados = 0;
                $idpedido = $datos[1]->pedido;

                for ($i=0; $i <$nreg; $i++) { 
                    
                    if ($datos[$i]->lugar !== "" ) {
                        $query = $this->db->connect()->prepare("UPDATE lg_proformadet SET nflgAbastec = 1 
                                                                    WHERE id_centi=:entidad 
                                                                    AND niddet=:detalle 
                                                                    AND id_regmov=:pedido");

                        $query->execute(["entidad"=>$datos[$i]->entidad,"detalle"=>$datos[$i]->detalle,"pedido"=>$datos[$i]->pedido]);
                        
                        $actualizados++;
                    };
                }

                if ($actualizados > 0 ){
                    $this->grabarActualizarDetallesPedido($detalles);
                    $this->actualizarCabeceraPedido($idpedido);
                    $this->actualizarCabeceraAdjudicacion($idpedido);
                }

                return $actualizados;
        }

        public function grabarActualizarDetallesPedido($detalles){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            
            for ($i=0; $i < $nreg ; $i++) {
                if ($datos[$i]->lugar){
                    $query = $this->db->connect()->prepare("UPDATE lg_pedidodet 
                                                             SET id_centi=:entidad,nEstadoReg=6,nEstadoPed=6,nflgadjudicado=1,nprecioref=:unitario
                                                             WHERE nidpedi=:detalle");
                    $query->execute(["entidad"=>$datos[$i]->entidad,"detalle"=>$datos[$i]->detalle,"unitario"=>$datos[$i]->unitario]);
                } 
                
            }
        }

        //cambia el estado de la cabecera
        public function actualizarCabeceraPedido($id){
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_pedidocab SET nEstadoDoc=6 WHERE id_regmov=:id");
                $query->execute(["id"=>$id]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function actualizarCabeceraAdjudicacion($id){
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_proformacab SET nflgactivo=3 WHERE id_regmov=:id");
                $query->execute(["id"=>$id]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>

