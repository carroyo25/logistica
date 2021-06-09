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
                                                        al_regmovi1.nnronota,
                                                        al_regmovi1.ffecdoc,
                                                        YEAR ( al_regmovi1.ffecdoc ) AS anio,
                                                        al_regmovi1.nnromov,
                                                        al_regmovi1.id_regalm,
                                                        al_regmovi1.cnumguia,
                                                        al_regmovi1.cobserva,
                                                        al_regmovi1.nEstadoDoc,
                                                        lg_regabastec.cnumero AS orden,
                                                        tb_proyecto1.ccodpry,
                                                        tb_proyecto1.cdespry,
                                                        lg_registro.cnumero AS pedido,
                                                        tb_almacen.cdesalm,
                                                        tb_almacen.ccodalm,
                                                        tb_paramete2.cdesprm2 AS estado 
                                                    FROM
                                                        al_regmovi1
                                                        INNER JOIN lg_regabastec ON al_regmovi1.idref_abas = lg_regabastec.id_regmov
                                                        INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN lg_registro ON al_regmovi1.idref_pedi = lg_registro.id_regmov
                                                        INNER JOIN tb_almacen ON al_regmovi1.ncodalm1 = tb_almacen.ncodalm
                                                        INNER JOIN tb_paramete2 ON al_regmovi1.nEstadoDoc = tb_paramete2.ccodprm2 
                                                    WHERE
                                                        tb_paramete2.ncodprm1 = 4
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
                                                        logistica.al_regmovi1.id_regalm,
                                                        logistica.al_regmovi1.ncodmov,
                                                        logistica.al_regmovi1.nnromov,
                                                        logistica.al_regmovi1.nnronota,
                                                        logistica.al_regmovi1.ncodalm1,
                                                        logistica.al_regmovi1.ffecdoc,
                                                        logistica.al_regmovi1.id_centi,
                                                        logistica.al_regmovi1.cnumguia,
                                                        logistica.al_regmovi1.ncodpry,
                                                        logistica.al_regmovi1.ncodcos,
                                                        logistica.al_regmovi1.ncodarea,
                                                        logistica.al_regmovi1.idref_pedi,
                                                        logistica.al_regmovi1.idref_abas,
                                                        logistica.al_regmovi1.id_userAprob,
                                                        logistica.al_regmovi1.nEstadoDoc,
                                                        logistica.al_regmovi1.nflgactivo,
                                                        logistica.al_regmovi1.ffecconta,
                                                        logistica.tb_almacen.cdesalm,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.tb_ccostos.cdescos,
                                                        logistica.lg_regabastec.cnumero AS orden,
                                                        logistica.cm_entidad.cnumdoc,
                                                        logistica.cm_entidad.crazonsoc,
                                                        logistica.lg_regabastec.cdocPDF,
                                                        CONCAT( rrhh.tabla_aquarius.apellidos, ' ', rrhh.tabla_aquarius.nombres ) AS aprueba,
                                                        rrhh.tabla_aquarius.dcargo,
                                                        logistica.viewpedidos.cnumero AS pedido,
                                                        CONCAT( logistica.viewpedidos.apellidos, ' ', logistica.viewpedidos.nombres ) AS solicitante,
                                                        logistica.lg_movimiento.cdesmov,
                                                        logistica.lg_regabastec.mobserva,
                                                        logistica.viewpedidos.cconcepto,
                                                        logistica.tb_paramete2.cdesprm2 
                                                    FROM
                                                        logistica.al_regmovi1
                                                        INNER JOIN logistica.tb_almacen ON logistica.al_regmovi1.ncodalm1 = logistica.tb_almacen.ncodalm
                                                        INNER JOIN logistica.tb_proyecto1 ON logistica.al_regmovi1.ncodpry = logistica.tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_area ON logistica.al_regmovi1.ncodarea = logistica.tb_area.ncodarea
                                                        INNER JOIN logistica.tb_ccostos ON logistica.al_regmovi1.ncodcos = logistica.tb_ccostos.ncodcos
                                                        INNER JOIN logistica.lg_regabastec ON logistica.al_regmovi1.idref_abas = logistica.lg_regabastec.id_regmov
                                                        INNER JOIN logistica.cm_entidad ON logistica.al_regmovi1.id_centi = logistica.cm_entidad.id_centi
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.al_regmovi1.id_userAprob = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.viewpedidos ON logistica.al_regmovi1.idref_pedi = logistica.viewpedidos.id_regmov
                                                        INNER JOIN logistica.lg_movimiento ON logistica.al_regmovi1.ncodmov = logistica.lg_movimiento.ncodmov
                                                        INNER JOIN logistica.tb_paramete2 ON logistica.al_regmovi1.nEstadoDoc = logistica.tb_paramete2.ccodprm2 
                                                    WHERE
                                                        logistica.al_regmovi1.id_regalm = :cod
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
                                    "espec"=>$rs[0]['mobserva'],
                                    "documento"=>$rs[0]['cdesprm2'],
                                    "order_file"=>$rs[0]['cdocPDF']
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

                        $salida.='<option value="'.$row['ncodprm2'].'" '.$select.'>'.$row['cdesprm2'].'</option>';
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
                                                        al_regmovi2.id_regalm,
                                                        al_regmovi2.niddeta,
                                                        al_regmovi2.ncodalm1,
                                                        al_regmovi2.id_cprod,
                                                        al_regmovi2.ncantidad,
                                                        al_regmovi2.nfactor,
                                                        al_regmovi2.nsaldo,
                                                        al_regmovi2.niddetaped,
                                                        al_regmovi2.niddetaord,
                                                        al_regmovi2.nestadoreg,
                                                        al_regmovi2.nflgactivo,
                                                        al_regmovi2.cobsCalidad,
                                                        al_regmovi2.fregsys,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        cm_producto.ncodmed,
                                                        tb_unimed.cabrevia 
                                                    FROM
                                                        al_regmovi2
                                                        INNER JOIN cm_producto ON al_regmovi2.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                    WHERE
                                                        al_regmovi2.nflgactivo = 1 
                                                        AND al_regmovi2.id_regalm = :cod");
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
                    $sql = $this->db->connect()->prepare("UPDATE al_regmovi2 SET nestadoreg=:est,nflgCalidad=:flg,cobsCalidad=:obs WHERE niddeta=:cod");
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
                $sql = $this->db->connect()->prepare("UPDATE al_regmovi1 SET nEstadoDoc=:est WHERE id_regalm=:cod");
                $sql->execute(["cod"=>$idx,"est"=>8]);

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