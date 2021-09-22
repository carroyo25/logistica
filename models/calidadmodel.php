<?php
    class CalidadModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getMainRecords(){
            $salida = "";
            try {
                $sql = $this->db->connect()->query("SELECT
                                                        alm_recepcab.nnronota,
                                                        alm_recepcab.ffecdoc,
                                                        YEAR ( alm_recepcab.ffecdoc ) AS anio,
                                                        alm_recepcab.nnromov,
                                                        alm_recepcab.id_regalm,
                                                        alm_recepcab.cnumguia,
                                                        alm_recepcab.cobserva,
                                                        alm_recepcab.nEstadoDoc,
                                                        lg_regabastec.cnumero AS orden,
                                                        tb_proyecto1.ccodpry,
                                                        tb_proyecto1.cdespry,
                                                        lg_pedidocab.cnumero AS pedido,
                                                        tb_almacen.cdesalm,
                                                        tb_almacen.ccodalm,
                                                        tb_paramete2.cdesprm2 AS estado 
                                                    FROM
                                                        alm_recepcab
                                                        INNER JOIN lg_regabastec ON alm_recepcab.idref_abas = lg_regabastec.id_regmov
                                                        INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN lg_pedidocab ON alm_recepcab.idref_pedi = lg_pedidocab.id_regmov
                                                        INNER JOIN tb_almacen ON alm_recepcab.ncodalm1 = tb_almacen.ncodalm
                                                        INNER JOIN tb_paramete2 ON alm_recepcab.nEstadoDoc = tb_paramete2.ccodprm2 
                                                    WHERE
                                                        tb_paramete2.ncodprm1 = 4
                                                        AND alm_recepcab.nEstadoDoc = 10
                                                    LIMIT 30");
                $sql->execute();
                $rowCount = $sql->rowcount();
                if ($rowCount > 0){
                    while ($rs = $sql->fetch()) {
                        $salida .= '<tr>
                                    <td class="con_borde centro">'.str_pad($rs['nnronota'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['ffecdoc'])).'</td>
                                    <td class="con_borde centro">'.str_pad($rs['nnromov'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde pl10">'.$rs['cdesalm'].'</td>
                                    <td class="con_borde pl10">'.$rs['cdespry'].'</td>
                                    <td class="con_borde centro">'.$rs['anio'].'</td>
                                    <td class="con_borde centro">'.str_pad($rs['orden'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde centro">'.$rs['pedido'].'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cnumguia']).'</td>
                                    <td class="con_borde">'.$rs['cobserva'].'</td>
                                    <td class="con_borde centro '.strtolower($rs['estado']).'">'.$rs['estado'].'</td>
                                    <td class="con_borde centro"><a href="'.$rs['id_regalm'].'"><i class="far fa-edit"></i></a></td>
                                </tr>';
                    }
                }else{
                    $salida = '<tr><td colspan="12" class="centro">No hay registros que mostrar</td></tr>';
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function cambiarNota($index){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                logistica.alm_recepcab.id_regalm,
                logistica.alm_recepcab.ncodmov,
                logistica.alm_recepcab.nnromov,
                logistica.alm_recepcab.nnronota,
                logistica.alm_recepcab.ncodalm1,
                logistica.alm_recepcab.ffecdoc,
                logistica.alm_recepcab.ffecrecep,
                logistica.alm_recepcab.id_centi,
                logistica.alm_recepcab.cnumguia,
                logistica.alm_recepcab.ncodpry,
                logistica.alm_recepcab.ncodarea,
                logistica.alm_recepcab.idref_pedi,
                logistica.alm_recepcab.ncodcos,
                logistica.alm_recepcab.idref_abas,
                logistica.alm_recepcab.nEstadoDoc,
                logistica.alm_recepcab.cdocPDF,
                logistica.alm_recepcab.nflgactivo,
                logistica.tb_proyecto1.ccodpry,
                logistica.tb_proyecto1.cdespry,
                logistica.tb_area.ccodarea,
                logistica.tb_area.cdesarea,
                logistica.tb_ccostos.ccodcos,
                logistica.tb_ccostos.cdescos,
                orden.cnumero AS orden,
                logistica.cm_entidad.cnumdoc,
                logistica.cm_entidad.crazonsoc,
                orden.cdocPDF,
                CONCAT( rrhh.autoriza.apellidos, ' ', rrhh.autoriza.nombres ) AS aprueba,
                pedido.cnumero AS pedido,
                pedido.cconcepto,
                CONCAT( rrhh.solicita.apellidos, ' ', rrhh.solicita.nombres ) AS solicitante,
                logistica.tb_paramete2.cdesprm2,
                logistica.alm_recepcab.id_userAprob,
                logistica.alm_recepcab.ffecconta,
                logistica.lg_movimiento.cdesmov,
                autoriza.dcargo,
                logistica.tb_almacen.cdesalm,
                pedido.mdetalle 
            FROM
                logistica.alm_recepcab
                INNER JOIN logistica.tb_proyecto1 ON alm_recepcab.ncodpry = tb_proyecto1.ncodpry
                INNER JOIN logistica.tb_area ON alm_recepcab.ncodarea = tb_area.ncodarea
                INNER JOIN logistica.tb_ccostos ON alm_recepcab.ncodcos = tb_ccostos.ncodcos
                INNER JOIN logistica.lg_regabastec AS orden ON alm_recepcab.idref_abas = logistica.orden.id_regmov
                INNER JOIN logistica.cm_entidad ON alm_recepcab.id_centi = cm_entidad.id_centi
                INNER JOIN rrhh.tabla_aquarius AS autoriza ON logistica.alm_recepcab.id_userAprob = autoriza.internal
                INNER JOIN logistica.lg_pedidocab AS pedido ON logistica.alm_recepcab.idref_pedi = logistica.pedido.id_regmov
                INNER JOIN rrhh.tabla_aquarius AS solicita ON pedido.ncodper = solicita.internal
                INNER JOIN logistica.tb_paramete2 ON logistica.alm_recepcab.nEstadoDoc = logistica.tb_paramete2.ccodprm2
                INNER JOIN logistica.lg_movimiento ON logistica.alm_recepcab.ncodmov = logistica.lg_movimiento.ncodmov
                INNER JOIN logistica.tb_almacen ON logistica.alm_recepcab.ncodalm1 = logistica.tb_almacen.ncodalm 
            WHERE
                alm_recepcab.id_regalm = :cod 
                AND logistica.tb_paramete2.ncodprm1 = 4");
                $sql->execute(["cod"=>$index]);

                $rs = $sql->fetchAll();

                $salidajson = array("id_ingreso"=>$rs[0]['id_regalm'],
                                    "cod_almacen"=>$rs[0]['ncodalm1'],
                                    "cod_movimento"=>$rs[0]['ncodmov'],
                                    "id_entidad"=>$rs[0]['id_centi'],
                                    "cod_autoriza"=>$rs[0]['id_userAprob'],
                                    "cod_proyecto"=>$rs[0]['ncodpry'],
                                    "cod_area"=>$rs[0]['ncodarea'],
                                    "cod_costos"=>$rs[0]['ncodcos'],
                                    "order_file"=>$rs[0]['cdocPDF'],
                                    "cargo_almacen"=>$rs[0]['dcargo'],
                                    "idorden"=>$rs[0]['idref_abas'],
                                    "idpedido"=>$rs[0]['idref_pedi'],
                                    "almacen"=>$rs[0]['cdesalm'],
                                    "fechadoc"=>$rs[0]['ffecdoc'],
                                    "fechacont"=>$rs[0]['ffecconta'],
                                    "nro_ingreso"=>str_pad($rs[0]['nnronota'],5,"0",STR_PAD_LEFT),
                                    "proyecto"=>$rs[0]['cdespry'],
                                    "area"=>$rs[0]['cdesarea'],
                                    "costos"=>$rs[0]['cdescos'],
                                    "solicita"=>$rs[0]['solicitante'],
                                    "aprueba"=>$rs[0]['aprueba'],
                                    "tipomov"=>$rs[0]['cdesmov'],
                                    "movalmacen"=>str_pad($rs[0]['nnromov'],5,"0",STR_PAD_LEFT),
                                    "nrord"=>str_pad($rs[0]['orden'],5,"0",STR_PAD_LEFT),
                                    "nroped"=>$rs[0]['pedido'],
                                    "nruc"=>$rs[0]['cnumdoc'],
                                    "nroguia"=>$rs[0]['cnumguia'],
                                    "entidad"=>$rs[0]['crazonsoc'],
                                    "concepto"=>$rs[0]['cconcepto'],
                                    "espec"=>$rs[0]['mdetalle'],
                                    "documento"=>$rs[0]['cdesprm2'],
                                    "order_file"=>$rs[0]['cdocPDF'],
                                    "estado"=>$rs[0]['cdesprm2'],
                                );
                return $salidajson;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getParameters($selected){
            $salida = "";
            try {
                $sql= $this->db->connect()->query("SELECT
                                                        tb_paramete2.ncodprm1,
                                                        tb_paramete2.ncodprm2,
                                                        tb_paramete2.cdesprm2 
                                                    FROM
                                                        tb_paramete2 
                                                    WHERE
                                                        ncodprm1 = 21 ");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $select = $selected == $row['ncodprm2'] ? "selected":"";

                        $salida.='<option value="'.$row['ccodprm2'].'" '.$select.'>'.$row['cdesprm2'].'</option>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function listaDetallesCodigo($index){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        alm_recepdet.id_regalm,
                                                        alm_recepdet.niddeta,
                                                        alm_recepdet.ncodalm1,
                                                        alm_recepdet.id_cprod,
                                                        alm_recepdet.ncantidad,
                                                        alm_recepdet.nfactor,
                                                        alm_recepdet.nsaldo,
                                                        alm_recepdet.niddetaped,
                                                        alm_recepdet.niddetaord,
                                                        alm_recepdet.nestadoreg,
                                                        alm_recepdet.nflgactivo,
                                                        alm_recepdet.cobsCalidad,
                                                        alm_recepdet.fregsys,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        cm_producto.ncodmed,
                                                        tb_unimed.cabrevia 
                                                    FROM
                                                        alm_recepdet
                                                        INNER JOIN cm_producto ON alm_recepdet.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                    WHERE
                                                        alm_recepdet.nflgactivo = 1 
                                                        AND alm_recepdet.id_regalm = :cod");
                $sql->execute(["cod"=>$index]);
                $rowCount= $sql->rowcount();
                $salida = "";
                $cont=0;

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida .=$cont++;
                        $salida.='<tr class="lh1_2rem pointertr" data-id="'.$row['niddeta'].'">
                                    <td class="con_borde centro"><a href="" data-action="register"><i class="fas fa-barcode"></i></a></td>
                                    <td class="centro con_borde" data-iddetpedido ="'.$row['niddetaped'].'"
                                                                 data-iddetorden ="'.$row['niddetaord'].'"    
                                                                 data-factor="'.$row['nfactor'].'"
                                                                 data-coduni="'.$row['ncodmed'].'"
                                                                 data-idprod="'.$row['id_cprod'].'"
                                                                 data-iddeta="'.$row['niddeta'].'">
                                                                '.str_pad($cont,3,"0",STR_PAD_LEFT).'
                                    </td>
                                    <td class="centro con_borde">'.$row['ccodprod'].'</td>
                                    <td class="pl20 con_borde">'.$row['cdesprod'].'</td>
                                    <td class="con_borde centro">'.$row['cabrevia'].'</td>
                                    <td class="con_borde drch pr20">'.number_format($row['ncantidad'], 2, '.', ',').'</td>
                                    <td class="con_borde"><select name="estado">'.  $this->getParameters($row['nestadoreg']) .'</select></td>
                                    <td class="con_borde"><input type="text" class="sin_borde pl20" value="'.strtoupper($row['cobsCalidad']).'"></td>
                                </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function listarSeriesProducto($idx,$prod){
            try {
                $salida = "";

                $sql = $this->db->connect()->prepare("SELECT
                                                    cm_prodserie.id_cprod,
                                                    cm_prodserie.ncodserie,
                                                    cm_prodserie.cdesserie,
                                                    cm_prodserie.idref_alma,
                                                    cm_prodserie.nflgactivo 
                                                FROM
                                                    cm_prodserie 
                                                WHERE
                                                    cm_prodserie.id_cprod =:prod 
                                                    AND cm_prodserie.idref_alma =:cod 
                                                    AND cm_prodserie.nflgactivo = 1");
                $sql->execute(["cod"=>$idx,"prod"=>$prod]);
                $rowCount = $sql->rowcount();
                $cont = 1;

                if ($rowCount > 0) {
                    while ($rs = $sql->fetch()) {
                        $salida.= '<tr>
                            <td class="con_borde centro"><i class="fas fa-barcode"></i></td>
                            <td class="con_borde centro">'.$cont.'</td>
                            <td class="con_borde pl20 mayusculas">'.$rs['cdesserie'].'</td>
                            <td class="con_borde"></td>
                        </tr>';
                        
                        $cont++;
                    }
                }

                echo $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function listarAdjuntosCodigos($index){
            try {
                $salida = "";
                $sql = $this->db->connect()->prepare("SELECT
                                                    lg_regdocumento.id_regmov,
                                                    lg_regdocumento.cdocumento,
                                                    lg_regdocumento.nflgactivo,
                                                    lg_regdocumento.nidrefer 
                                                FROM
                                                    lg_regdocumento 
                                                WHERE
                                                    lg_regdocumento.id_regmov = :cod 
                                                    AND lg_regdocumento.nflgactivo = 1");
                $sql->execute(["cod"=>$index]);
                $rowCount = $sql->rowcount();
                if ($rowCount > 0) {
                    while ($rs = $sql->fetch()) {
                        $adjunto =  constant("URL")."/public/adjuntos/".$rs['nidrefer'];
                        $salida .='<li><a href="'.$adjunto.'" class="atachDoc"><i class="fas fa-mail-bulk"></i><span>'.$rs['cdocumento'].'</span></a></li>';
                    }
                }
                
                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function actualizarDetallesNota($detalles){
            try {
                $datos = json_decode($detalles);
                $nreg = count($datos);

                for ($i=0; $i < $nreg ; $i++) { 
                    $sql = $this->db->connect()->prepare("UPDATE alm_recepdet SET nestadoreg=:est,nflgCalidad=:flg,cobsCalidad=:obs WHERE niddeta=:cod");
                    $sql->execute(["cod"=>$datos[$i]->niddeta,
                                   "est"=>$datos[$i]->nestado,
                                   "obs"=>$datos[$i]->observ,
                                   "flg"=>1]);

                    
                }

                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function actualizarEstadoNota($idx,$detalles){
            try {
                $sql = $this->db->connect()->prepare("UPDATE alm_recepcab SET nEstadoDoc=:est WHERE id_regalm=:cod");
                $sql->execute(["cod"=>$idx,"est"=>11]);

                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    $this->actualizarDetallesNota($detalles);
                    $this->saveAction("REVISA",$idx,"CONTROL DE CALIDAD",$_SESSION['user']);
                    return true;
                }else {
                    return false;
                }

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        
    }
?>