<?php
    class DespachoModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getMainRecords() {
            $salida = "";
            try {
                $sql = $this->db->connect()->query("SELECT
                                                        alm_despacho1.id_regalm,
                                                        alm_despacho1.ctipmov,
                                                        alm_despacho1.ncodmov,
                                                        alm_despacho1.nnronota,
                                                        alm_despacho1.nnromov,
                                                        alm_despacho1.cper,
                                                        alm_despacho1.ncodalm1,
                                                        alm_despacho1.ffecdoc,
                                                        YEAR ( alm_despacho1.ffecdoc ) AS anio,
                                                        alm_despacho1.cSerieguia,
                                                        alm_despacho1.cnumguia,
                                                        alm_despacho1.ncodpry,
                                                        alm_despacho1.idref_pedi,
                                                        alm_despacho1.nEstadoDoc,
                                                        alm_despacho1.idref_ord,
                                                        alm_despacho1.idref_abas,
                                                        tb_proyecto1.ccodpry,
                                                        tb_proyecto1.cdespry,
                                                        tb_almacen.cdesalm AS almacen,
                                                        lg_registro.cnumero AS pedido,
                                                        lg_regabastec.cnumero AS orden,
                                                        al_regmovi1.cnumguia AS guiaprove,
                                                        alm_despacho1.cobserva,
                                                        tb_paramete2.cdesprm2 AS estado 
                                                    FROM
                                                        alm_despacho1
                                                        INNER JOIN tb_proyecto1 ON alm_despacho1.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN tb_almacen ON alm_despacho1.ncodalm1 = tb_almacen.ncodalm
                                                        INNER JOIN lg_registro ON alm_despacho1.idref_pedi = lg_registro.id_regmov
                                                        INNER JOIN lg_regabastec ON alm_despacho1.idref_ord = lg_regabastec.id_regmov
                                                        INNER JOIN al_regmovi1 ON alm_despacho1.idref_abas = al_regmovi1.id_regalm
                                                        INNER JOIN tb_paramete2 ON alm_despacho1.nEstadoDoc = tb_paramete2.ccodprm2 
                                                    WHERE
                                                        tb_paramete2.ncodprm1 = 4 
                                                    LIMIT 30");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if  ($rowCount > 0) {
                    while ($rs = $sql->fetch()) {
                        $guiaRenision = $rs['cnumguia']!= null ? str_pad($rs['cnumguia'],5,"0",STR_PAD_LEFT):"";

                        $salida.='<tr>
                                    <td class="con_borde centro">'.str_pad($rs['nnronota'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['ffecdoc'])).'</td>
                                    <td class="con_borde centro">'.str_pad($rs['nnromov'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde pl20">'.strtoupper($rs['almacen']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($rs['ccodpry']." ".$rs['cdespry']).'</td>
                                    <td class="con_borde centro">'.$rs['anio'].'</td>
                                    <td class="con_borde centro">'.str_pad($rs['orden'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde centro">'.strtoupper($rs['guiaprove']).'</td>
                                    <td class="con_borde centro">'.str_pad($rs['pedido'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde centro">'.$guiaRenision.'</td>
                                    <td class="con_borde centro">'.strtoupper($rs['cobserva']).'</td>
                                    <td class="con_borde centro '.strtolower($rs['estado']).'">'.$rs['estado'].'</td>
                                    <td class="con_borde centro"><a href="'.$rs['id_regalm'].'"><i class="far fa-edit"></i></a></td>
                            </tr>';
                    }
                }else {
                    $salida .='<tr><td colspan="13" class="centro">No hay registros para mostrar</td></tr>';
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getParameters($param){
            $salida = "";
            try {
                $sql= $this->db->connect()->prepare("SELECT
                                                        tb_paramete2.ncodprm1,
                                                        tb_paramete2.ncodprm2,
                                                        tb_paramete2.cdesprm2 
                                                    FROM
                                                        tb_paramete2 
                                                    WHERE
                                                        ncodprm1 = :cod ");
                $sql->execute(["cod"=>$param]);
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida.='<li><a href="'.$row['ncodprm2'].'">'.strtoupper($row['cdesprm2']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getMovs(){
            $salida = "";
            try {
                $sql= $this->db->connect()->query("SELECT
                                                    lg_movimiento.ncodmov,
                                                    lg_movimiento.cdesmov 
                                                FROM
                                                    lg_movimiento");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida.='<li><a href="'.$row['ncodmov'].'">'.strtoupper($row['cdesmov']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerAlmacenes(){
            try {
                $salida = "";
                $sql = $this->db->connect()->query("SELECT
                                                    tb_almacen.ncodalm,
                                                    tb_almacen.ccodalm,
                                                    tb_almacen.cdesalm,
                                                    tb_almacen.nflgactivo,
                                                    tb_almacen.ncubigeo,
                                                    tb_almacen.cdespais,
                                                    tb_almacen.cdesdpto,
                                                    tb_almacen.cdesprov,
                                                    tb_almacen.cdesdist,
                                                    tb_almacen.ctipovia,
                                                    tb_almacen.cdesvia,
                                                    tb_almacen.cnrovia,
                                                    tb_almacen.cintevia,
                                                    tb_almacen.czonavia 
                                                FROM
                                                    tb_almacen 
                                                WHERE
                                                    tb_almacen.nflgactivo
                                                ORDER BY
	                                                tb_almacen.cdesalm");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida.='<li><a href="'.$row['ncodalm'].'" data-via="'.$row['ctipovia'].'" 
                                                                    data-nombre="'.$row['cdesvia'].'"
                                                                    data-nro="'.$row['cnrovia'].'"
                                                                    data-interior="'.$row['cintevia'].'"
                                                                    data-zona="'.$row['czonavia'].'"
                                                                    data-dpto="'.$row['cdesdpto'].'"
                                                                    data-prov="'.$row['cdesprov'].'"
                                                                    data-dist="'.$row['cdesdist'].'"
                                                                    data-ubigeo="'.$row['ncubigeo'].'">'.strtoupper($row['cdesalm']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerPersonal(){
            $salida = "";
            try {
                $sql = $this->db->connectrrhh()->query("SELECT
                                                        tabla_aquarius.internal,
                                                        tabla_aquarius.apellidos,
                                                        tabla_aquarius.nombres,
                                                        tabla_aquarius.ccargo,
                                                        tabla_aquarius.dcargo 
                                                    FROM
                                                        tabla_aquarius 
                                                    WHERE
                                                        tabla_aquarius.dcargo LIKE '%almacen%'
                                                    ORDER BY
                                                        tabla_aquarius.nombres");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida.='<li><a href="'.$row['internal'].'" data-cargo="'.$row['dcargo'].'">'.strtoupper($row['nombres']." ".$row['apellidos']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function llamarNotas(){
            $salida = "";
            try {
                $sql = $this->db->connect()->query("SELECT
                                                        al_regmovi1.id_regalm,
                                                        al_regmovi1.ctipmov,
                                                        al_regmovi1.nnronota,
                                                        al_regmovi1.ffecdoc,
                                                        al_regmovi1.ncodpry,
                                                        al_regmovi1.nflgactivo,
                                                        tb_proyecto1.ccodpry,
                                                        tb_proyecto1.cdespry,
                                                        al_regmovi1.nEstadoDoc 
                                                    FROM
                                                        al_regmovi1
                                                        INNER JOIN tb_proyecto1 ON al_regmovi1.ncodpry = tb_proyecto1.ncodpry 
                                                    WHERE
                                                        al_regmovi1.ctipmov = 'I' 
                                                        AND al_regmovi1.nflgactivo = 1 
                                                        AND al_regmovi1.nEstadoDoc = 8");
                $sql->execute();
                $rowCount = $sql->rowcount();
                if ($rowCount > 0) {
                    while ($rs = $sql->fetch()) {
                        $salida .='<tr class="lh1_2rem pointertr">
                                    <td class="con_borde pl20">'.str_pad($rs['nnronota'],5,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde pl20">'.$rs['ccodpry'].' - '.$rs['cdespry'].'</td>
                                    <td class="con_borde pl20">'.date("d/m/Y", strtotime($rs['ffecdoc'])).'</td>
                                    <td class="con_borde pl20"><a href="'.$rs['id_regalm'].'"><i class="fas fa-arrows-alt-h"></i></a></td>
                                </tr>';
                    }
                }else{
                    $salida .= '<tr><td colspan="4" class="centro">No se encontraron registros</td></tr>';
                }

                return $salida;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function llamarIngresoPorID($idx){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        logistica.al_regmovi1.id_regalm,
                                                        logistica.al_regmovi1.ctipmov,
                                                        logistica.al_regmovi1.nnromov,
                                                        logistica.al_regmovi1.nnronota,
                                                        logistica.al_regmovi1.cper,
                                                        logistica.al_regmovi1.cmes,
                                                        logistica.al_regmovi1.ncodalm1,
                                                        logistica.al_regmovi1.ffecdoc,
                                                        logistica.al_regmovi1.id_centi,
                                                        logistica.al_regmovi1.cnumguia,
                                                        logistica.al_regmovi1.ncodpry,
                                                        logistica.al_regmovi1.ncodarea,
                                                        logistica.al_regmovi1.ncodcos,
                                                        logistica.al_regmovi1.idref_pedi,
                                                        logistica.al_regmovi1.idref_abas,
                                                        logistica.al_regmovi1.cobserva,
                                                        logistica.al_regmovi1.id_userAprob,
                                                        logistica.al_regmovi1.nEstadoDoc,
                                                        logistica.al_regmovi1.nflgactivo,
                                                        CONCAT( logistica.tb_almacen.ccodalm, ' - ', logistica.tb_almacen.cdesalm ) AS almacen,
                                                        CONCAT( logistica.tb_proyecto1.ccodpry, ' - ', logistica.tb_proyecto1.cdespry ) AS proyecto,
                                                        logistica.viewpedidos.cnumero AS nroped,
                                                        CONCAT( logistica.viewpedidos.apellidos, ' ', logistica.viewpedidos.nombres ) AS solicita,
                                                        logistica.viewpedidos.id_regmov,
                                                        logistica.viewpedidos.mdetalle,
                                                        CONCAT( rrhh.tabla_aquarius.apellidos, ' ', rrhh.tabla_aquarius.nombres ) AS aprueba,
                                                        rrhh.tabla_aquarius.dcargo,
                                                        LPAD( logistica.lg_regabastec.cnumero, 6, 0 ) AS nrorden,
                                                        logistica.lg_regabastec.ffechadoc AS fechaOrden,
                                                        logistica.lg_regabastec.cdocPDF,
                                                        logistica.viewpedidos.ffechadoc AS fechaPedido,
                                                        logistica.viewpedidos.cconcepto,
                                                        CONCAT( logistica.tb_paramete2.ccodprm2, ' - ', logistica.tb_paramete2.cdesprm2 ) AS movimiento,
                                                        logistica.cm_entidad.crazonsoc 
                                                    FROM
                                                        logistica.al_regmovi1
                                                        INNER JOIN logistica.tb_almacen ON al_regmovi1.ncodalm1 = tb_almacen.ncodalm
                                                        INNER JOIN logistica.tb_proyecto1 ON al_regmovi1.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.viewpedidos ON al_regmovi1.idref_pedi = viewpedidos.id_regmov
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.al_regmovi1.id_userAprob = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.lg_regabastec ON logistica.al_regmovi1.idref_abas = logistica.lg_regabastec.id_regmov
                                                        INNER JOIN logistica.tb_paramete2 ON logistica.lg_regabastec.ctiptransp = logistica.tb_paramete2.ncodprm2
                                                        INNER JOIN logistica.cm_entidad ON logistica.al_regmovi1.id_centi = logistica.cm_entidad.id_centi 
                                                    WHERE
                                                        al_regmovi1.id_regalm = :cod 
                                                        AND logistica.tb_paramete2.ncodprm1 = 7 
                                                        LIMIT 1");
                $sql->execute(["cod"=>$idx]);
                
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    $docData = array();
                    //extrae los campos y los envia para el controlador
                    while($row=$sql->fetch(PDO::FETCH_ASSOC)){
                        $docData[] = $row;
                    }
                }

                $regid = uniqid("NS");
                
                $numdoc = $this->genNumberDoc($docData[0]['ncodalm1']);
                $nummov = $this->genNumberMov($docData[0]['ncodalm1']);
                
                $header = array("idreg"=>$regid,"nrodoc"=>$numdoc,"nromov"=>$nummov);
                array_push($docData,$header);
                
                return $docData;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function llamarDetalleIngresoPorID($idx){
            $salida = "";
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
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        cm_producto.ncodmed,
                                                        tb_unimed.cabrevia,
                                                        lg_detaabastec.ncanti,
                                                        tb_paramete2.cdesprm2 
                                                    FROM
                                                        al_regmovi2
                                                        INNER JOIN cm_producto ON al_regmovi2.id_cprod = cm_producto.id_cprod
                                                        INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN lg_detaabastec ON al_regmovi2.niddetaord = lg_detaabastec.niddeta
                                                        INNER JOIN tb_paramete2 ON al_regmovi2.nestadoreg = tb_paramete2.ncodprm2 
                                                    WHERE
                                                        al_regmovi2.nflgactivo = 1 
                                                        AND al_regmovi2.id_regalm = :cod 
                                                        AND tb_paramete2.ncodprm1 = 21");
                $sql->execute(["cod"=>$idx]);

                $rowCount = $sql->rowcount();
                $item = 1;
                if ($rowCount > 0) {
                    while ($rs = $sql->fetch()) {
                        $salida .='<tr>
                                        <td class="con_borde centro"><a href="'.$rs['niddeta'].'" data-action="delete"><i class="far fa-trash-alt"></i></a></td>
                                        <td class="centro con_borde" data-iddetpedido ="'.$rs['niddetaped'].'"
                                                                 data-iddetorden ="'.$rs['niddetaord'].'"    
                                                                 data-factor="'.$rs['nfactor'].'"
                                                                 data-coduni="'.$rs['ncodmed'].'"
                                                                 data-idprod="'.$rs['id_cprod'].'"
                                                                 data-nestado="'.$rs['nestadoreg'].'">
                                                                '.str_pad($item,3,"0",STR_PAD_LEFT).'
                                        <td class="con_borde centro">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde pl20">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde centro">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde drch pr10">'.number_format($rs['ncanti'], 2, '.', ',').'</td>
                                        <td class="con_borde drch pr10"><input type="number" value="'.number_format($rs['ncantidad'], 2, '.', ',').'" class="drch pr10px"></td>
                                        <td class="con_borde centro">'.$rs['cdesprm2'].'</td>
                                        <td class="con_borde centro"></td>
                                        <td class="con_borde centro"></td>
                                   </tr>';
                        $item++;
                    }
                }else{
                    $salida .= '<tr><td colspan="10" class="centro">No se encontraron registros</td></tr>';
                }

                return $salida;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        //ojo hacer el mismo procedimiento para los ingreoso
        public function genNumberDoc($cod){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                            COUNT(alm_movim1.ncodalm1) AS numguia
                                                        FROM
                                                            alm_movim1 
                                                        WHERE
                                                            alm_movim1.ncodalm1 = :cod
                                                        AND alm_movim1.ncodalm1 = 'S'");
                $sql->execute(["cod"=>$cod]);

                $row = $sql->fetchAll();

                $nro_doc =  str_pad($row[0]['numguia'] + 1,5,"0",STR_PAD_LEFT);                   

                return $nro_doc ;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function genNumberMov($cod){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                            COUNT(alm_movim1.nnromov) AS nummov 
                                                        FROM
                                                            alm_movim1 
                                                        WHERE
                                                            alm_movim1.ncodalm1 = :cod");
                $sql->execute(["cod"=>$cod]);

                $row = $sql->fetchAll();

                $nro_mov = str_pad($row[0]['nummov'] + 1,5,"0",STR_PAD_LEFT);

                return $nro_mov;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function guardarGuia($codmodalidadguia,$codtipoguia,$codalmacendestino,$codalmacenorigen,$codautoriza,$coddespacha,
                                    $coddestinatario,$codentidad,$codchofer,$serieguia,$nroguia,$packinlist,$fecemin,$feenttrans,
                                    $ruc,$razondest,$direccdest,$almorg,$viatiporg,$vianomorg,$nroorg,$intorg,$zonaorg,$viatipodest,$nrodest,$deporg,$distorg,
                                    $provorg,$ubigorg,$mottrans,$modtras,$tenvio,$bultos,$peso,$observaciones,$autoriza,$despacha,$destinatario,
                                    $raztransp,$ructransp,$dirtransp,$representate,$almdest,$vianomodest,$intdest,$zondest,$depdest,$distdest,
                                    $provdest,$ubigdest,$dnicond,$detcond,$licencia,$certificado,$marca,$placa,$configveh,$proyecto,$costos,
                                    $salida,$detalles){
            try {
                $id = uniqid("GR");
                $filename = "public/guias/".$id.".pdf";
                $ndoc = $this->genNroGuia();

                $sql = $this->db->connect()->prepare("INSERT lg_docusunat SET id_refmov=:id_refmov,ccodtdoc=:ccodtdoc,ffechdoc=:ffechdoc,ffechreg=:ffechreg,ffechtrasl=:ffechtrasl,
                                                                                ffechRecep=:ffechRecep,cserie=:cserie,cnumero=:cnumero,id_centi=:id_centi,cmotivo=:cmotivo,
                                                                                ccodmodtrasl=:ccodmodtrasl,cdesmodtrasl=:cdesmodtrasl,ctipoenvio=:ctipoenvio,nbultos=:nbultos,
                                                                                npesotot=:npesotot,cdniconduc=:cdniconduc,cdesconduc=:cdesconduc,cnrolicen=:cnrolicen,cnrocert=:cnrocert,
                                                                                cmarcaveh=:cmarcaveh,cplacaveh=:cplacaveh,cconfigveh=:cconfigveh,ncodalm1=:ncodalm1,ncodalm2=:ncodalm2,
                                                                                nEstadoImp=:nEstadoImp,cdocPDF=:cdocPDF,nflgactivo=:nflgactivo");
                    $sql->execute([ "id_refmov"=>$id,
                                    "ccodtdoc"=>'09',
                                    "ffechdoc"=>$fecemin,
                                    "ffechreg"=>null,
                                    "ffechtrasl"=>$feenttrans,
                                    "ffechRecep"=>null,
                                    "cserie"=>'0001',
                                    "cnumero"=>$ndoc,
                                    "id_centi"=>$codentidad,
                                    "cmotivo"=>$mottrans,
                                    "ccodmodtrasl"=>$modtras,
                                    "cdesmodtrasl"=>$observaciones,
                                    "ctipoenvio"=>$tenvio,
                                    "nbultos"=>$bultos,
                                    "npesotot"=>$peso,
                                    "cdniconduc"=>$dnicond,
                                    "cdesconduc"=>$detcond,
                                    "cnrolicen"=>$licencia,
                                    "cnrocert"=>$certificado,
                                    "cmarcaveh"=>$marca,
                                    "cplacaveh"=>$placa,
                                    "cconfigveh"=>$configveh,
                                    "ncodalm1"=>$codalmacenorigen,
                                    "ncodalm2"=>$codalmacendestino,
                                    "nEstadoImp"=>null,
                                    "cdocPDF"=>$filename,
                                    "nflgactivo"=>1]);
                    $rowCount = $sql->rowcount();

                    if ($rowCount > 0){
                        $guia = $this->genPreviewGuia($ruc,$razondest,$direccdest,$fecemin,$feenttrans,$raztransp,$ructransp,$dirtransp,
                                    $vianomorg,$nroorg,$zonaorg,$distorg,
                                    $vianomodest,$zondest,$viatipodest,$nrodest,$depdest,
                                    $modtras,$bultos,$peso,$observaciones,$proyecto,$detalles,$marca,$placa,$detcond,$licencia,
                                    $filename,$ndoc);
                        
                        
                    }


                    
                    return $guia;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function genPreviewGuia( $ruc,$razondest,$direccdest,$fecemin,$feenttrans,$raztransp,$ructransp,$dirtransp,
                                        $vianomorg,$nroorg,$zonaorg,$distorg,
                                        $vianomodest,$zondest,$viatipodest,$nrodest,$depdest,
                                        $modtras,$bultos,$peso,$observaciones,$proyecto,$detalles,$marca,$placa,$detcond,$licencia,
                                        $filename,$ndoc){
            
            require_once("public/libsrepo/guiarepo.php");

            $filename = "public/guias/".uniqid("GR").".pdf";

            if(file_exists($filename))
                unlink($filename);

            try {
                // Creación del objeto de la clase heredada
                
                $datos = json_decode($detalles);
                $nreg = count($datos);

                $pdf = new PDF($ndoc,$fecemin,$ruc,$razondest,$direccdest,$raztransp,$ructransp,$dirtransp,
                                $vianomorg,$nroorg,$distorg,$zonaorg,$feenttrans,$modtras,
                                $vianomodest,$nrodest,$zondest,$depdest,$marca,$placa,$detcond,$licencia);
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->SetWidths(array(10,15,15,147));
                $pdf->SetFillColor(255,255,255);
                $pdf->SetTextColor(0,0,0);
                
                $pdf->SetFont('Arial','',4);
                $lc = 0;
                $rc = 0;

                for($i=1;$i<=$nreg;$i++){
                    $pdf->SetX(13);
                    $pdf->SetCellHeight(5);
                    $pdf->SetAligns(array("R","R","C","L"));
                    $pdf->Row(array(str_pad($i,3,"0",STR_PAD_LEFT),
                                    $datos[$rc]->cantidad,
                                    $datos[$rc]->unidad,
                                    $datos[$rc]->coditem .' '. $datos[$rc]->descripcion));
                    $lc++;
                    $rc++;

                    if ($lc == 23) {
                        $pdf->AddPage();
                        $lc = 0;
                    }	
                }

                $pdf->Ln(1);
                $pdf->SetX(13);
                $pdf->MultiCell(190,2,utf8_decode($observaciones));
                $pdf->Ln(2);
                $pdf->SetX(13);
                $pdf->Cell(190,4,"Bultos :". $bultos,0,1);
                $pdf->SetX(13);
                $pdf->Cell(190,4,"Peso   :". $peso. "Kgs",0,1);
                $pdf->Output($filename,'F');
                return $filename;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function genPreview($ingreso,$condicion,$fecha,$proyecto,$origen,$movimiento,$orden,$pedido,$nguia,$nombre,$cargo,$entidad,$details,$tipo){
            require_once("public/libsrepo/repoingreso.php");
            try {
                $datos = json_decode($details);
                $fecha_explode = explode("-",$fecha);
                $lc = 0;
                $rc = 0;

                $dia = $fecha_explode[2];
                $mes = $fecha_explode[1];
                $anio = $fecha_explode[0];

                $filename = "public/temp/".uniqid("NS").".pdf";

                if(file_exists($filename))
                    unlink($filename);

                $nreg = count($datos);

                $pdf = new PDF($ingreso,$condicion,$dia,$mes,$anio,$proyecto,$origen,$movimiento,$orden,$pedido,$nguia,$nombre,$cargo,$tipo);
                // Creación del objeto de la clase heredada
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->SetWidths(array(5,15,55,8,12,20,45,15,15));
                $pdf->SetFont('Arial','',4);
                $lc = 0;

                for($i=1;$i<=$nreg;$i++){
                        $pdf->SetAligns(array("C","L","L","L","R","L","L","L","L"));
                        $pdf->Row(array(str_pad($i,3,"0",STR_PAD_LEFT),
                                                $datos[$rc]->coditem,
                                                utf8_decode($datos[$rc]->descripcion),
                                                $datos[$rc]->unidad,
                                                $datos[$rc]->cantdes,
                                                "",
                                                utf8_decode($entidad),
                                                $datos[$rc]->cestado,
                                                $datos[$rc]->ubicacion));
                        $lc++;
                        $rc++;
                        
                        if ($lc == 52) {
                            $pdf->AddPage();
                            $lc = 0;
                        }	
                    }
                    
                $pdf->Output($filename,'F');
                
                return $filename;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
            
        }

        //generar el numero de guia de despacho
        public function genNroGuia(){
            try {
                $sql = $this->db->connect()->query("SELECT
                COUNT( alm_despacho1.id_regalm ) AS nrodespacho 
            FROM
                alm_despacho1");
                $sql->execute();

                $row= $sql->fetchAll();

                return str_pad($row[0]['nrodespacho']+1,7,0,STR_PAD_LEFT);

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        //obtener transportes
        public function obtenerEntidades(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT
                                                        cm_entidad.id_centi,
                                                        cm_entidad.crazonsoc,
                                                        cm_entidad.cnumdoc,
                                                        cm_entidad.cviadireccion 
                                                    FROM
                                                        cm_entidad 
                                                    WHERE
                                                        cm_entidad.nflgactivo = 1
                                                        ORDER BY cm_entidad.crazonsoc");
                $query->execute();

                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['id_centi'].'" 
                                        data-razon="'.$row['crazonsoc'].'"
                                        data-ruc="'.$row['cnumdoc'].'"
                                        data-direccion="'.$row['cviadireccion'].'">'.strtoupper($row['crazonsoc']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertarSalida($id_ingreso,$id_salida,$id_entidad,$cod_almacen,$cod_movimiento,$cod_autoriza,
                                        $cod_proyecto,$cod_area,$cod_costos,$order_file,$cargo_almacen,$idorden,$idpedido,
                                        $entidad,$docguia,$nrosalida,$movalma,$fechadoc,$fechacont,$proyecto,$solicita,
                                        $aprueba,$almacen,$tipomov,$nroped,$fecped,$nrord,$fecord,$espec,$details){
            try {
                
                $fecha = explode("-",$fechadoc);
                $numdoc = $this->genNumberDoc($cod_almacen);
                $nummov = $this->genNumberMov($cod_almacen);
                
                $salida = "";
                $sql = $this->db->connect()->prepare("INSERT INTO alm_despacho1 SET id_regalm = :id,ctipmov = :ctipmov,ncodmov = :ncodmov,nnromov = :nnromov,
                                                                    cper = :cper,cmes = :cmes,ncodalm1 = :ncodalm1,ffecdoc = :ffecdoc,ffecconta = :ffecconta,
                                                                    id_centi = :id_centi,ncodpry = :ncodpry,ncodarea = :ncodarea,ncodcos = :ncodcos,
                                                                    idref_pedi = :idref_pedi,idref_abas=:idref_abas,idref_ord=:idref_ord,nnronota=:nnronota,
                                                                    cobserva = :cobserva,id_userAprob = :id_userAprob,nEstadoDoc = :nEstadoDoc,nflgactivo = :nflgactivo,
                                                                    nflgDespacho = :nflgDespacho");
                $sql->execute(["id"=>$id_salida,
                                "ctipmov"=>$cod_movimiento,
                                "ncodmov"=>$numdoc,
                                "nnromov"=>$nummov,
                                "cper"=>$fecha[0],
                                "cmes"=>$fecha[1],
                                "ncodalm1"=>$cod_almacen,
                                "ffecdoc"=>$fechadoc,
                                "ffecconta"=>$fechacont,
                                "id_centi"=>$id_entidad,
                                "ncodpry"=>$cod_proyecto,
                                "ncodarea"=>$cod_area,
                                "ncodcos"=>$cod_costos,
                                "idref_pedi"=>$idpedido,
                                "idref_abas"=>$id_ingreso,
                                "idref_ord"=>$idorden,
                                "nnronota"=>$numdoc,
                                "cobserva"=>$espec,
                                "id_userAprob"=>$cod_autoriza,
                                "nEstadoDoc"=>1,
                                "nflgDespacho"=>1,
                                "nflgactivo"=>1]);
                $rowCount= $sql->rowcount();

                if ($rowCount > 0){
                    $salida = true;

                    $this->insertarDetallesNota($cod_almacen,$details,$id_salida);
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function insertarDetallesNota($almacen,$detalles,$cod){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            $rc = 0;

            for ($i=0; $i < $nreg; $i++) { 
                try {
                    $sql=$this->db->connect()->prepare("INSERT INTO alm_despacho2 SET id_regalm=:cod,ncodalm1=:ori,id_cprod=:cpro,ncantidad=:cant,ncoduni=:uni,
                                                                                    nfactor=:fac,niddetaPed=:ped,niddetaOrd=:ord,nflgactivo=:flag");
                     $sql->execute(["cod"=>$cod,
                                    "ori"=>$almacen,
                                    "cpro"=>$datos[$rc]->idprod,
                                    "cant"=>$datos[$rc]->cantidad,
                                    "uni"=>$datos[$rc]->coduni,
                                    "fac"=>$datos[$rc]->factor,
                                    "ped"=>$datos[$rc]->iddetped,
                                    "ord"=>$datos[$rc]->iddetord,
                                    "flag"=>1]);

                    $this->changeDetailStatus($datos[$rc]->iddetped);
                    $rc++;
                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }
        }

        public function insertarDetallesGuia($almorig,$almdest,$detalles,$cod){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            $rc = 0;

            for ($i=0; $i < $nreg; $i++) { 
                try {
                    $sql=$this->db->connect()->prepare("INSERT INTO alm_movim2 SET id_regalm=:cod,ncodalm1=:ori,id_cprod=:cpro,ncantidad=:cant,ncoduni=:uni,
                                                                                    nfactor=:fac,niddetaped=:ped,niddetaord=:ord,nflgactivo=:flag");
                     $sql->execute(["cod"=>$cod,
                                    "ori"=>$almorig,
                                    "des"=>$almdest,
                                    "cpro"=>$datos[$rc]->idprod,
                                    "cant"=>$datos[$rc]->cantidad,
                                    "uni"=>$datos[$rc]->coduni,
                                    "fac"=>$datos[$rc]->factor,
                                    "ped"=>$datos[$rc]->iddetped,
                                    "ord"=>$datos[$rc]->iddetord,
                                    "flag"=>1]);

                    $this->changeDetailStatus($datos[$rc]->iddetped);
                    $rc++;
                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }
        }

        public function changeDetailStatus($codigo){
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_detapedido SET nEstadoPed = 9 WHERE nidpedi=:idp");
                $query->execute(["idp"=>$codigo]);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function cambiarEstadoSalida($codigo,$idguia){
            try {
                $sql = $this->db->connect()->prepare("");
                $sql->execute(["cod"=>$codigo,
                                "guia"=>$idguia,
                                "estado"=>2]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function buscarSalidaId($idx){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        logistica.alm_despacho1.id_regalm,
                                                        logistica.alm_despacho1.ctipmov,
                                                        logistica.alm_despacho1.ncodmov,
                                                        logistica.alm_despacho1.nnromov,
                                                        logistica.alm_despacho1.nnronota,
                                                        logistica.alm_despacho1.ncodalm1,
                                                        logistica.alm_despacho1.ffecdoc,
                                                        logistica.alm_despacho1.id_centi,
                                                        logistica.alm_despacho1.ncodpry,
                                                        logistica.alm_despacho1.idref_ord,
                                                        logistica.alm_despacho1.idref_pedi,
                                                        logistica.alm_despacho1.idref_abas,
                                                        logistica.alm_despacho1.cobserva,
                                                        logistica.alm_despacho1.id_userAprob,
                                                        logistica.alm_despacho1.nEstadoDoc,
                                                        logistica.alm_despacho1.cdocPDF,
                                                        logistica.alm_despacho1.nflgactivo,
                                                        logistica.tb_almacen.cdesalm,
                                                        logistica.alm_despacho1.ffecconta,
                                                        logistica.tb_almacen.ccodalm,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        CONCAT( autorizante.nombres, ' ', autorizante.apellidos ) AS autoriza,
                                                        autorizante.dcargo,
                                                        logistica.lg_registro.cnumero AS pedido,
                                                        logistica.lg_registro.ffechadoc AS fechapedido,
                                                        CONCAT( solicitante.nombres, ' ', solicitante.apellidos ) AS solicita,
                                                        logistica.lg_regabastec.cnumero AS orden,
                                                        logistica.lg_regabastec.ffechadoc AS fechaorden,
                                                        logistica.lg_movimiento.cdesmov,
                                                        logistica.tb_paramete2.cdesprm2,
                                                        logistica.al_regmovi1.cnumguia,
                                                        logistica.lg_regabastec.ncodcos,
                                                        logistica.lg_regabastec.ncodarea,
                                                        logistica.cm_entidad.crazonsoc,
                                                        logistica.cm_entidad.cnumdoc 
                                                    FROM
                                                        logistica.alm_despacho1
                                                        INNER JOIN logistica.tb_almacen ON alm_despacho1.ncodalm1 = tb_almacen.ncodalm
                                                        INNER JOIN logistica.tb_proyecto1 ON alm_despacho1.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN rrhh.tabla_aquarius AS autorizante ON logistica.alm_despacho1.id_userAprob = autorizante.internal
                                                        INNER JOIN logistica.lg_registro ON logistica.alm_despacho1.idref_pedi = logistica.lg_registro.id_regmov
                                                        INNER JOIN rrhh.tabla_aquarius AS solicitante ON logistica.lg_registro.ncodper = solicitante.internal
                                                        INNER JOIN logistica.lg_regabastec ON logistica.alm_despacho1.idref_ord = logistica.lg_regabastec.id_regmov
                                                        INNER JOIN logistica.lg_movimiento ON logistica.alm_despacho1.ctipmov = logistica.lg_movimiento.ncodmov
                                                        INNER JOIN logistica.tb_paramete2 ON logistica.alm_despacho1.nEstadoDoc = logistica.tb_paramete2.ccodprm2
                                                        INNER JOIN logistica.al_regmovi1 ON logistica.alm_despacho1.idref_abas = logistica.al_regmovi1.id_regalm
                                                        INNER JOIN logistica.cm_entidad ON logistica.alm_despacho1.id_centi = logistica.cm_entidad.id_centi 
                                                    WHERE
                                                        alm_despacho1.id_regalm = :cod 
                                                        AND logistica.tb_paramete2.ncodprm1 = 4 
                                                        LIMIT 1");
                $sql->execute(["cod"=>$idx]);

                $rs = $sql->fetchAll();

                $salidajson = array("id_ingreso"=>$rs[0]['idref_abas'],
                                    "id_salida"=>$rs[0]['id_regalm'],
                                    "id_entidad"=>$rs[0]['id_centi'],
                                    "cod_almacen"=>$rs[0]['ncodalm1'],
                                    "cod_movimiento"=>$rs[0]['ctipmov'],
                                    "cod_autoriza"=>$rs[0]['id_userAprob'],
                                    "cod_proyecto"=>$rs[0]['ncodpry'],
                                    "cod_area"=>$rs[0]['ncodarea'],
                                    "cod_costos"=>$rs[0]['ncodcos'],
                                    //"order_file"=>$rs[0][''],
                                    //"cargo_almacen"=>$rs[0][''],
                                    "idorden"=>$rs[0]['idref_ord'],
                                    "idpedido"=>$rs[0]['idref_pedi'],
                                    "entidad"=>$rs[0]['crazonsoc'],
                                    "guia"=>$rs[0]['cnumguia'],
                                    "nrosalida"=>str_pad($rs[0]['nnronota'],4,0,STR_PAD_LEFT),
                                    "movalma"=>str_pad($rs[0]['nnromov'],4,0,STR_PAD_LEFT),
                                    "fechadoc"=>$rs[0]['ffecdoc'],
                                    "fechacont"=>$rs[0]['ffecconta'],
                                    "proyecto"=>strtoupper($rs[0]['ccodpry']."-".$rs[0]['cdespry']),
                                    "solicita"=>$rs[0]['solicita'],
                                    "aprueba"=>$rs[0]['autoriza'],
                                    "almacen"=>strtoupper($rs[0]['ccodalm']."-".$rs[0]['cdesalm']),
                                    "tipomov"=>$rs[0]['cdesmov'],
                                    "nroped"=>$rs[0]['pedido'],
                                    "fecped"=>$rs[0]['fechapedido'],
                                    "nrord"=>str_pad($rs[0]['orden'],6,0,STR_PAD_LEFT),
                                    "fecord"=>$rs[0]['fechaorden'],
                                    "espec"=>$rs[0]['cobserva'],
                                    "estadoc"=>$rs[0]['cdesprm2'],
                                    "nestado"=>$rs[0]['nEstadoDoc']);

                return $salidajson;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function buscarDellatesId($idx){
            $salida = "";

            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                            alm_despacho2.id_regalm,
                                                            alm_despacho2.niddeta,
                                                            alm_despacho2.ncodalm1,
                                                            alm_despacho2.id_cprod,
                                                            alm_despacho2.nsaldo,
                                                            alm_despacho2.ncantidad,
                                                            alm_despacho2.nfactor,
                                                            alm_despacho2.niddetaOrd,
                                                            alm_despacho2.niddetaPed,
                                                            alm_despacho2.nestadoreg,
                                                            alm_despacho2.ncoduni,
                                                            cm_producto.ccodprod,
                                                            cm_producto.cdesprod,
                                                            tb_unimed.cabrevia,
                                                            tb_paramete2.cdesprm2 
                                                        FROM
                                                            alm_despacho2
                                                            INNER JOIN cm_producto ON alm_despacho2.id_cprod = cm_producto.id_cprod
                                                            INNER JOIN tb_unimed ON alm_despacho2.ncoduni = tb_unimed.ncodmed
                                                            INNER JOIN tb_paramete2 ON alm_despacho2.nestadoreg = tb_paramete2.ccodprm2 
                                                        WHERE
                                                            tb_paramete2.ncodprm1 = 21 
                                                            AND tb_paramete2.nflgactivo = 1 
                                                            AND alm_despacho2.id_regalm = :cod ");
                $sql->execute(["cod"=>$idx]);
                
                $rowCount = $sql->rowcount();
                $item = 1;
                
                if ($rowCount > 0){
                    while ($rs = $sql->fetch()) {
                        $salida .='<tr>
                                        <td class="con_borde centro"><a href="'.$rs['niddeta'].'" data-action="delete"><i class="far fa-trash-alt"></i></a></td>
                                        <td class="centro con_borde" data-iddetpedido ="'.$rs['niddetaPed'].'"
                                                                 data-iddetorden ="'.$rs['niddetaOrd'].'"    
                                                                 data-factor="'.$rs['nfactor'].'"
                                                                 data-coduni="'.$rs['ncoduni'].'"
                                                                 data-idprod="'.$rs['id_cprod'].'"
                                                                 data-nestado="'.$rs['nestadoreg'].'">
                                                                '.str_pad($item,3,"0",STR_PAD_LEFT).'
                                        <td class="con_borde centro">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde pl20">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde centro">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde drch pr10">'.number_format($rs['ncantidad'], 2, '.', ',').'</td>
                                        <td class="con_borde drch pr10">'.number_format($rs['ncantidad'], 2, '.', ',').'</td>
                                        <td class="con_borde centro">'.$rs['cdesprm2'].'</td>
                                        <td class="con_borde centro"></td>
                                        <td class="con_borde centro"></td>
                                   </tr>';
                        $item++;
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