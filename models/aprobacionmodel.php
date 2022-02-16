<?php
    class AprobacionModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getAllUserRecords($user){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.lg_pedidocab.id_regmov,
                                                        logistica.lg_pedidocab.cnumero,
                                                        logistica.lg_pedidocab.ffechadoc,
                                                        logistica.lg_pedidocab.cconcepto,
                                                        logistica.lg_pedidocab.ffechaven,
                                                        logistica.lg_pedidocab.nEstadoDoc,
                                                        logistica.lg_pedidocab.id_cuser,
                                                        logistica.lg_pedidocab.ncodmov,
                                                        logistica.lg_pedidocab.nNivAten,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        atenciones.cdesprm2 AS atencion,
                                                        estados.cdesprm2 AS estado 
                                                    FROM
                                                        logistica.tb_proyusu
                                                        INNER JOIN logistica.lg_pedidocab ON tb_proyusu.ncodproy = lg_pedidocab.ncodpry
                                                        INNER JOIN logistica.tb_area ON lg_pedidocab.ncodarea = tb_area.ncodarea
                                                        INNER JOIN logistica.tb_proyecto1 ON lg_pedidocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2 
                                                    WHERE
                                                        tb_proyusu.id_cuser = :user 
                                                        AND tb_proyusu.nflgactivo = 1 
                                                        AND atenciones.ncodprm1 = 13 
                                                        AND estados.ncodprm1 = 4 
                                                        AND logistica.lg_pedidocab.nEstadoDoc = 3");
                $query->execute(["user"=>$user]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .='<tr class="h35px" data-idx="'.$row['id_regmov'].'">
                                <td class="con_borde centro">'.$row['cnumero'].'</td>
                                <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                <td class="con_borde pl10">'.$row['cconcepto'].'</td>
                                <td class="con_borde pl10">'.$row['ccodarea'].' '.$row['cdesarea'].'</td>
                                <td class="con_borde pl10">'.$row['ccodpry'].' '.$row['cdespry'].'</td>
                                <td class="con_borde pl10">'.$row['apellidos'].' '.$row['nombres'].'</td>
                                <td class="con_borde centro '. strtolower($row['estado']) .'">'.$row['estado'].'</td>
                                <td class="con_borde centro '. strtolower($row['atencion']) .'">'.$row['atencion'].'</td>
                                <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="editar"  title="editar"><i class="far fa-edit"></i></a></td>
                            </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                $e->getMessage();
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

                $totalAtach = $this->countAtachs($cod);

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
                                        $item['atachs']         = $totalAtach;
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
                                                        ROUND( lg_pedidodet.ncantpedi, 2 ) AS cantidad,
                                                        ROUND( lg_pedidodet.ncantaten, 2 ) AS atendido,
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
                        $line++;
                        $estadolinea = "";

                        $xaprobar = floatval($row['cantidad']) - floatval($row['atendido']);

                        if ( $xaprobar == 0 ) {
                            $estadolinea = "desactivado";
                        }

                        $salida.='<tr class="lh1_2rem">
                            <td class="con_borde drch pr20" data-iddetalle="'.$row['nidpedi'].'">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                            <td class="con_borde centro">'. $row['id_cprod'] .'</td>
                            <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                            <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                            <td class="con_borde drch pr10">'.$row['cantidad'] .'</td>
                            <td class="con_borde drch pr10">'.$row['atendido'] .'</td>
                            <td class="con_borde '.$estadolinea.'" contenteditable="true"><input type="number" class="drch" value="'. number_format($xaprobar, 2, '.', ',') .'"></td>
                            <td class="con_borde"><input type="text" class="pl10 sin_borde h35px w100p no_outline"></td>
                            <td class="con_borde centro"><input type="checkbox" checked></td>
                            <td class="con_borde oculto">'. $row['nfactor'] .'</td>
                        </tr>';
                    }
                }

                return $salida;
            } catch (PDOexception $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllMails(){
            try {
                $salida = "";
                $query = $this->db->connectrrhh()->query("SELECT corporativo, CONCAT(nombres,',',apellidos) AS nombres FROM tabla_aquarius 
                                                        WHERE corporativo <> 'NULL' ORDER BY apellidos ASC");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida .= '<tr data-correo="'.$row['corporativo'].'@sepcon.net'.'" class="pointertr">
                                        <td class="con_borde pl20">'.$row['nombres'].'</td>
                                        <td class="con_borde pl20">'.$row['corporativo'].'@sepcon.net'.'</td>
                                    </tr>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function cambiarEstatus($usuario,$pedido,$correos,$detalles){
            try {
                $sql = $this->db->connect()->prepare("UPDATE lg_pedidocab SET nEstadoDoc=:est,ncodaproba=:user 
                                                        WHERE id_regmov =:ped");
                $sql->execute(["est"=>4,"ped"=>$pedido,"user"=>$_SESSION['id_user']]);
                $rowcount = $sql->rowcount();

                if($rowcount > 0) {
                    $this->actualizarItems(4,$detalles);
                    $this->genOc($pedido,$usuario);
                    $this->sendmails($correos,$pedido);

                    return array("response"=>true);
                }else {
                    return array("response"=>false);
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function actualizarItems($estado,$detalles){
            try {
                $data = json_decode($detalles);
                for ($i=0; $i < count($data) ; $i++) { 
                    $sql = $this->db->connect()->prepare("UPDATE lg_pedidodet SET ncantapro=:aprob,
                                                            nEstadoReg=:est WHERE nidpedi =:cod");
                    $sql->execute(["aprob"=>$data[$i]->cantapr,
                                    "est"=>$estado,
                                    "cod"=>$data[$i]->iddetalle]);                    
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function sendmails($datos,$pedido){
            require_once("public/PHPMailer/PHPMailerAutoload.php");

            try {
                $data = json_decode($datos);
                $existe = false;

                $mensaje = $data[0]->msg;
                $mails = count($data);
                $title = utf8_decode("Pedido aprobado");

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

                for ($i=0; $i < $mails; $i++) {
				    $mail->addAddress($data[$i]->mail,$data[$i]->mail);
			    }
                $mail->Subject = $title;
                $mail->Body = html_entity_decode(utf8_decode($mensaje));

                $filename = "public/pedidos/aprobados/".$pedido.".pdf";

                if (file_exists( $filename )) {
                    $mail->AddAttachment($filename);
                }
                
                if (!$mail->send()) {
                    $mensaje = false;
			    }else {
                    $mensaje = true;
                }
                
                return $existe;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //genera la nota para el adjunto
        public function genOc($cod,$nombre){
            require_once("public/libsrepo/repopedidos.php");
            $query = $this->db->connect()->prepare("SELECT
                                                            logistica.lg_pedidocab.id_regmov,
                                                            logistica.lg_pedidocab.cnumero,
                                                            logistica.lg_pedidocab.ccoddoc,
                                                            logistica.lg_pedidocab.cserie,
                                                            logistica.lg_pedidocab.ffechadoc,
                                                            logistica.lg_pedidocab.cconcepto,
                                                            logistica.lg_pedidocab.mdetalle,
                                                            logistica.lg_pedidocab.ctipmov,
                                                            logistica.lg_pedidocab.id_cuser,
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
                                                            atenciones.cdesprm2 AS atencion
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
                                    $ser = $row['cserie'];
                                    $num = $row['cnumero'];
                                    $fec = $row['ffechadoc'];
                                    $con = $row['cconcepto'];
                                    $esp = $row['mdetalle'];
                                    $usr = $row['id_cuser'];
                                    $sol = $row['dni']." ".$row['nombres']." ".$row['apellidos'];
                                    $pry = $row['ccodpry']." ".$row['cdespry'];
                                    $are = $row['ccodarea']." ".$row['cdesarea'];
                                    $cos = $row['ccodcos']." ".$row['cdescos'];
                                    $tra = $row['cod_transporte']." ".$row['transporte'];
                                    $dti = $row['ctipmov'];
                                    $mmt = "";
                                    $cla = $row['atencion'] == 2 ? "URGENTE":"NORMAL";
                                    $msj = "APROBADO";
                                    $reg = "";
                                    $apr = $nombre;
                }
            }

            $filename = "public/pedidos/aprobados/".$cod.".pdf";

            if(file_exists($filename))
                unlink($filename);
            
            $pdf = new PDF($num,$fec,$pry,$cos,$are,$con,$mmt,$cla,$tra,$usr,$sol,$reg,$esp,$dti,$msj,$apr);
		    $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetWidths(array(10,15,70,8,10,17,15,15,15,15));
            $pdf->SetFont('Arial','',5);
            
            
            $query = $this->db->connect()->prepare("SELECT
                                                    lg_pedidodet.nidpedi,
                                                    lg_pedidodet.id_cprod,
                                                    ROUND( lg_pedidodet.ncantpedi, 2 ) AS cantidad,
                                                    ROUND( lg_pedidodet.ncantapro, 2 ) AS aprobada,
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

            $lc = 0;
            $rc = 1;

            if ($rowcount > 0) {
                while ($row = $query->fetch()) {
                    $pdf->SetAligns(array("L","L","L","L","R","L","L","L","L","L"));
                    $pdf->Row(array(str_pad($rc,3,"0",STR_PAD_LEFT),
                                $row['id_cprod'],
                                utf8_decode($row['cdesprod']),
                                $row['cabrevia'],
                                $row['aprobada'], //preguntar si aca debe ir las cantidades por aprobar
                                '',
                                '',
                                '',
                                '',
                                ''));
                    $lc++;
                    $rc++;

                    if ($lc == 52) {
                        $pdf->AddPage();
                        $lc = 0;
                    }

                }
            }

            $pdf->Output($filename,'F');
        }

        public function getAtachs($cod){
            try {
                $query = $this->db->connect()->prepare("SELECT creferencia,cdocumento FROM lg_regdocumento WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0){
                    $salida = "";
                    while($row = $query->fetch()){
                        $adjunto =  constant("URL")."/public/adjuntos/".$row['creferencia'];
                        $salida .='<li><a href="'.$adjunto.'" class="atachDoc"><i class="fas fa-mail-bulk"></i><span>'.$row['cdocumento'].'</span></a></li>';
                    }
                }else{
                    $salida = 0;
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function countAtachs($cod){
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

        public function resumen($user,$estado) {
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        COUNT( lg_pedidocab.cnumero ) AS respuesta 
                                                        FROM
                                                            tb_proyusu
                                                            INNER JOIN lg_pedidocab ON tb_proyusu.ncodproy = lg_pedidocab.ncodpry 
                                                        WHERE
                                                            tb_proyusu.nflgactivo = 1 
                                                            AND tb_proyusu.id_cuser = :usr 
                                                            AND lg_pedidocab.nEstadoDoc = :est");
                $sql->execute(["usr"=>$user,"est"=>$estado]);
                $result = $sql->fetchAll();
                return $result[0]['respuesta'];

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>