<?php
    class CotizacionModel extends Model{

        public function __construct(){
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
                                                        AND logistica.lg_pedidocab.nEstadoDoc = 4");
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
                                                            AND transportes.ncodprm1 = 7 
                                                            AND atenciones.ncodprm1 = 13 
                                                            AND estados.ncodprm1 = 4");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                $totalCots = $this->countCots($cod);

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
                                        $item['atachs']         = $totalCots;
                    }
                }

                return $item;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getDetailsById($cod){
            try {
                $salida = '';

                $query = $this->db->connect()->prepare("SELECT
                                                        lg_pedidodet.nidpedi,
                                                        lg_pedidodet.id_cprod,
                                                        ROUND( lg_pedidodet.ncantapro, 2 ) AS cantidad,
                                                        lg_pedidodet.nEstadoPed,
                                                        tb_unimed.nfactor,
                                                        tb_unimed.cabrevia,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod 
                                                    FROM
                                                        lg_pedidodet
                                                        INNER JOIN tb_unimed ON lg_pedidodet.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod 
                                                    WHERE
                                                        lg_pedidodet.id_regmov =:cod
                                                        AND lg_pedidodet.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $line = 0;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        if ($row['cantidad'] > 0) {
                            $line++;
                            $salida.='<tr class="lh1_2rem">
                                <td class="con_borde centro"><input type="checkbox"></td>
                                <td class="con_borde drch pr20" data-iddetalle="'.$row['nidpedi'].'">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                                <td class="con_borde centro" data-indice="'.$row['id_cprod'].'">'. $row['id_cprod'] .'</td>
                                <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                                <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                                <td class="con_borde drch pr10">'. $row['cantidad'] .'</td>
                                <td class="con_borde"></td>
                                <td class="oculto">'. $row['nfactor'] .'</td>
                            </tr>';
                        }
                    }
                }

                return $salida;
            } catch (PDOexception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function countCots($cod){
            try {
                $query = $this->db->connect()->prepare("SELECT creferencia FROM lg_regdocumento WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod]);
                $rowcount=$query->rowcount();

                return $rowcount;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getDistMails() {
            $salida = "";
            try {
                $query = $this->db->connect()->query("SELECT cnumdoc,crazonsoc,cemail,id_centi FROM cm_entidad WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida .= '<tr class="pointer" data-correo="'.$row['cemail'].'" data-id="'.$row['id_centi'].'">
                                        <td class="cursor_pointer con_borde pl20">'.strtoupper($row['crazonsoc']).'</td>
                                        <td class="cursor_pointer con_borde pl20">'.strtolower($row['cemail']).'</td>
                                        <td class="oculto">'.$row['id_centi'].'</td>
                                    </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function sendMails($mails,$items){
            require_once("public/PHPMailer/PHPMailerAutoload.php");

            $correos = json_decode($mails);
            $respuesta = true;

            $data = count($correos);
            $title = utf8_decode("Solicitud de Cotización");

            $origen = $_SESSION['user']."@sepcon.net";
            $nombre_envio = $_SESSION['nombres'];

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->Host = 'mail.sepcon.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'sistema_ibis@sepcon.net';
            $mail->Password = $_SESSION['password'];
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => false
                )
            );
            
            $mail->setFrom($origen,$nombre_envio);

            //confirma la lectura del correo
            $mail->ConfirmReadingTo = $origen;
            $mail->Subject = $title;

            for ($i=0; $i < $data; $i++) {
			    $mail->addAddress($correos[$i]->mail,$correos[$i]->mail);
                $url = constant('URL').'public/cotizacion/?codped='.$correos[0]->codped.'&codenti='.$correos[$i]->codprov;

                $bodycotiz =  "<html><body>";

                $bodycotiz .=  '<div>
                                    <p>Estimado(a)</p>
                                    <p>Sirvase hacer hacer llegar la siguiente cotizacion, de acuerdo a lo solicitado</p>
                                    <a href="'.$url.'">Ver cotizacion</a>
                                </div>';
                $bodycotiz .=  "<html><body>";

                $mail->msgHTML(utf8_decode($bodycotiz));

                if($mail->send()){
                    $respuesta = true;
                }else {
                    $respuesta = false;
                };

                $mail->ClearAddresses();
			}

            if($respuesta){
                $salidajson = array("respuesta"=>$respuesta);
                $this->saveDetails($mails,$items);
                $this->saveHeader($mails);
            }else{
                $salidajson = array("respuesta"=>false);
            }
            
            return json_encode($salidajson);
        }

        public function saveHeader($mails){
            try {
                $correos = json_decode($mails);

                for ($i=0; $i < count($correos); $i++) {
                    $query = $this->db->connect()->prepare("INSERT INTO lg_regcotiza1 SET id_regmov=:idped, id_centi=:idprov, nflgactivo = 1");
                    $query->execute(["idped"=>$correos[$i]->codped,"idprov"=>$correos[$i]->codprov]);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function saveDetails($mails,$items){
            try {
                $correos = json_decode($mails);
                $detalles = json_decode($items);

                for ($i=0;$i<count($detalles);$i++){
                        $query = $this->db->connect()->prepare("INSERT INTO lg_regcotiza2 SET id_regmov=:idped,
                                                                                                niddet=:iddet,
                                                                                                id_cprod=:cprod,
                                                                                                cantcoti=:canti,
                                                                                                ncodmed=:facto,
                                                                                                id_centi=:prove,
                                                                                                nflgactivo=:flag,
                                                                                                cestadodoc=:esta");
                        $query->execute(["idped"=>$correos[$i]->codped,
                                         "iddet"=>$detalles[$i]->iddetalle,
                                         "cprod"=>$detalles[$i]->indice,
                                         "canti"=>$detalles[$i]->cantidad,
                                         "facto"=>$detalles[$i]->factor,
                                         "prove"=>$correos[$i]->codprov,
                                         "flag"=>1,
                                         "esta"=>0]);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function changeStatus($cod){
            $estatus = 5;
            $salida = "";
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_pedidocab SET nEstadoReg=:reg, nEstadoDoc=:doc WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod,"reg"=>$estatus,"doc"=>$estatus]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    $this->changeDetailStatus($cod,5);
                    $this->saveAction("ESTUDIO DE MERCADO",$cod,"COTIZACIONES",$_SESSION['user']);
                    $salida = $this->getMainRecords();
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function changeDetailStatus($cod,$est){
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_pedidodet SET nEstadoReg=:est WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod,"est"=>$est]);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function changePriority($cod)
        {
            $priority = 2;

            /*try {
                $query = $this->db->connect()->prepare("UPDATE lg_pedidocat SET nNivAten=:aten WHERE id_regmov=:cod");
                $query->execute(["aten"=>$priority,"cod"=>$cod]);
            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }*/
        }
    }
?>