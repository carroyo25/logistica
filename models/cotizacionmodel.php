<?php
    class CotizacionModel extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getMainRecords(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT logistica.lg_registro.id_regmov,
                                                        logistica.lg_registro.ffechadoc,
                                                        logistica.lg_registro.cconcepto,
                                                        logistica.lg_registro.nEstadoDoc,
                                                        logistica.lg_registro.id_cuser,
                                                        logistica.lg_registro.ncodmov,
                                                        logistica.lg_registro.cnumero,
                                                        logistica.lg_registro.nNivAten,
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
                                                        logistica.lg_registro
                                                        INNER JOIN logistica.tb_proyecto1 ON logistica.lg_registro.ncodpry = logistica.tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_area ON logistica.lg_registro.ncodarea = logistica.tb_area.ncodarea
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_registro.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_registro.nNivAten = atenciones.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_registro.nEstadoDoc = estados.ccodprm2
                                                        INNER JOIN logistica.tb_sysusuario ON logistica.lg_registro.ncodaproba = logistica.tb_sysusuario.id_cuser 
                                                    WHERE
                                                        atenciones.ncodprm1 = 13 
                                                        AND estados.ncodprm1 = 4 
                                                        AND logistica.lg_registro.nEstadoDoc = 3");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida .='<tr class="h35px" data-idx="'.$row['id_regmov'].'">
                                        <td class="con_borde centro">'.$row['cnumero'].'</td>
                                        <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                        <td class="con_borde pl10">'.$row['cconcepto'].'</td>
                                        <td class="con_borde pl10">'.$row['ccodarea'].' '.$row['cdesarea'].'</td>
                                        <td class="con_borde pl10">'.$row['ccodpry'].' '.$row['cdespry'].'</td>
                                        <td class="con_borde pl10">'.$row['apellidos'].' '.$row['nombres'].'</td>
                                        <td class="con_borde pl10">'.strtoupper($row['cnombres']).'</td>
                                        <td class="con_borde centro '. strtolower($row['estado']) .'">'.$row['estado'].'</td>
                                        <td class="con_borde centro '. strtolower($row['atencion']) .'">'.$row['atencion'].'</td>
                                        <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="editar"  title="editar"><i class="far fa-edit"></i></a></td>
                                        <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="cambiar" title="cambiar atencion"><i class="fas fa-highlighter"></i></a></td>
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
                                                            logistica.lg_registro.id_regmov,
                                                            logistica.lg_registro.cnumero,
                                                            logistica.lg_registro.ctipmov,
                                                            logistica.lg_registro.ncodmov,
                                                            logistica.lg_registro.ccoddoc,
                                                            logistica.lg_registro.cserie,
                                                            logistica.lg_registro.ffechadoc,
                                                            logistica.lg_registro.ncodpry,
                                                            logistica.lg_registro.ncodcos,
                                                            logistica.lg_registro.ncodarea,
                                                            logistica.lg_registro.ncodper,
                                                            logistica.lg_registro.cconcepto,
                                                            logistica.lg_registro.mdetalle,
                                                            logistica.lg_registro.ctiptransp,
                                                            logistica.lg_registro.nEstadoReg,
                                                            logistica.lg_registro.nEstadoDoc,
                                                            logistica.lg_registro.id_cuser,
                                                            logistica.lg_registro.nflgactivo,
                                                            logistica.lg_registro.nNivAten,
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
                                                            logistica.lg_registro
                                                            INNER JOIN rrhh.tabla_aquarius ON logistica.lg_registro.ncodper = rrhh.tabla_aquarius.internal
                                                            INNER JOIN logistica.tb_proyecto1 ON logistica.lg_registro.ncodpry = logistica.tb_proyecto1.ncodpry
                                                            INNER JOIN logistica.tb_area ON logistica.lg_registro.ncodarea = logistica.tb_area.ncodarea
                                                            INNER JOIN logistica.tb_ccostos ON logistica.lg_registro.ncodcos = logistica.tb_ccostos.ncodcos
                                                            INNER JOIN logistica.tb_paramete2 AS transportes ON logistica.lg_registro.ctiptransp = transportes.ncodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_registro.nNivAten = atenciones.ccodprm2
                                                            INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_registro.nEstadoDoc = estados.ccodprm2 
                                                        WHERE
                                                            logistica.lg_registro.id_regmov = :cod 
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

        public function getDetailsById($cod,$tip){
            try {
                $salida = '';

                $query = $this->db->connect()->prepare("SELECT
                                                        lg_detapedido.nidpedi,
                                                        lg_detapedido.id_cprod,
                                                        ROUND( lg_detapedido.ncantpedi, 2 ) AS cantidad,
                                                        lg_detapedido.nEstadoPed,
                                                        tb_unimed.nfactor,
                                                        tb_unimed.cabrevia,
                                                        estados.cdesprm2 AS estado,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod 
                                                    FROM
                                                        lg_detapedido
                                                        INNER JOIN tb_unimed ON lg_detapedido.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN tb_paramete2 AS estados ON lg_detapedido.nEstadoPed = estados.ccodprm2
                                                        INNER JOIN cm_producto ON lg_detapedido.id_cprod = cm_producto.id_cprod 
                                                    WHERE
                                                        lg_detapedido.id_regmov =:cod 
                                                        AND estados.ncodprm1 = 4 
                                                        AND lg_detapedido.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $line = 0;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $line++;

                        $salida.='<tr class="lh1_2rem">
                            <td class="con_borde centro"><input type="checkbox"></td>
                            <td class="con_borde drch pr20" data-iddetalle="'.$row['nidpedi'].'">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                            <td class="con_borde centro" data-indice="'.$row['id_cprod'].'">'. $row['ccodprod'] .'</td>
                            <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                            <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                            <td class="con_borde drch pr10">'.$row['cantidad'] .'</td>
                            <td class="con_borde"></td>
                            <td class="oculto">'. $row['nfactor'] .'</td>
                        </tr>';
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
                                        <td  class="cursor_pointer con_borde pl20">'.strtolower($row['cemail']).'</td>
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
            $detalles = json_decode($items);

            $mensaje = $correos[0]->msg;

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
            //$mail->Password = 'aK8izG1WEQwwB1O';
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

            for ($i=0; $i < $data; $i++) {
			    $mail->addAddress($correos[$i]->mail,$correos[$i]->mail);
			}

            $row = 1;
            $bodycotiz =  "<html><body>";
            $bodycotiz .= "<h4>".$mensaje."</h4>";
            $bodycotiz .= "<table width='100%' cellpadding='0' cellspacing='0' border='1' font-family: arial;>";
            $bodycotiz .= "<thead>
                    <tr style='height: 30px; background: black; color: #fff; font-size: .9rem;'>
                            <th>Item</th>
                            <th>Codigo</th>
                            <th>Cantidad</th>
                            <th>Descripcion</th>
                            <th>Unidad</th>
                            <th>Nro. Parte</th>
                        </tr>
                    </thead><tbody>";
            for ($x =0;$x < count($detalles); $x++) {
                $bodycotiz .= "<tr style='font-size: .9rem;'>";
                $bodycotiz .= "<td border='1'>".$row."</td>";
                $bodycotiz .= "<td border='1'>".$detalles[$x]->coditem."</td>";
                $bodycotiz .= "<td border='1'>".$detalles[$x]->cantidad."</td>";
                $bodycotiz .= "<td border='1'>".$detalles[$x]->desitem."</td>";
                $bodycotiz .= "<td border='1'>".$detalles[$x]->unidad."</td>";
                $bodycotiz .= "<td border='1'></td>"; // hay que activar el numero de parte
                $bodycotiz .= "</tr>";

                $row++;
            }
            $bodycotiz .=  "</tbody></table><html><body>";

            $mail->Subject = $title;

            $mail->msgHTML(utf8_decode($bodycotiz));

           if($mail->send()){
                $salidajson = array("respuesta"=>true);
                $this->saveDetails($mails,$items);
                $this->saveHeader($mails);
            }else{
                $salidajson = array("respuesta"=>false);
            }
            
            return json_encode($salidajson);
            
            $mail->ClearAddresses();
        }

        public function saveHeader($mails){
            try {
                $correos = json_decode($mails);

                for ($i=0; $i < count($correos); $i++) {
                    $query = $this->db->connect()->prepare("INSERT INTO lg_regcotiza1 SET id_regmov=:idped, id_centi=:idprov");
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
                    for ($j=0; $j <count($correos) ; $j++) { 
                        $query = $this->db->connect()->prepare("INSERT INTO lg_regcotiza2 SET id_regmov=:idped,
                                                                                                niddet=:iddet,
                                                                                                id_cprod=:cprod,
                                                                                                cantcoti=:canti,
                                                                                                ncodmed=:facto,
                                                                                                id_centi=:prove,
                                                                                                nflgactivo=:flag,
                                                                                                cestadodoc=:esta");
                        $query->execute(["idped"=>$correos[$j]->codped,
                                         "iddet"=>$detalles[$i]->iddetalle,
                                         "cprod"=>$detalles[$i]->indice,
                                         "canti"=>$detalles[$i]->cantidad,
                                         "facto"=>$detalles[$i]->factor,
                                         "prove"=>$correos[$j]->codprov,
                                         "flag"=>1,
                                         "esta"=>0]);
                    }
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function changeStatus($cod){
            $estatus = 4;
            $salida = "";
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_registro SET nEstadoReg=:reg, nEstadoDoc=:doc WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod,"reg"=>$estatus,"doc"=>$estatus]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    $this->changeDetailStatus($cod,7);
                    $this->saveLog($cod,"Cotizaciones");
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
                $query = $this->db->connect()->prepare("UPDATE lg_detapedido SET nEstadoPed=:est WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod,"est"=>$est]);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function saveLog($cod,$action){
            try {
                $query = $this->db->connect()->prepare("INSERT INTO lg_seguimiento SET cmodulo=:mod, id_regmov=:cod, cproceso=:pro, ccoduser=:usr");
                $query->execute(["mod"=>$action,"cod"=>$cod,"usr"=>$_SESSION['iduser'],"pro"=>"cotizar"]);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function changePriority($cod)
        {
            $priority = 2;

            try {
                $query = $this->db->connect()->prepare("UPDATE lg_registro SET nNivAten=:aten WHERE id_regmov=:cod");
                $query->execute(["aten"=>$priority,"cod"=>$cod]);
            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }
    }
?>