<?php
    class IngresosModel extends Model{

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
                                    <td class="con_borde pl10">'.$rs['cnumguia'].'</td>
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

        public function getWarehouses(){
            try {
                $salida = "";
                $sql = $this->db->connect()->query("SELECT
                                                    tb_almacen.ncodalm,
                                                    tb_almacen.ccodalm,
                                                    tb_almacen.cdesalm,
                                                    tb_almacen.nflgactivo 
                                                FROM
                                                    tb_almacen 
                                                WHERE
                                                    tb_almacen.nflgactivo");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if ($rowCount > 0) {
                    while ($row = $sql->fetch()) {
                        $salida.='<li><a href="'.$row['ncodalm'].'">'.strtoupper($row['cdesalm']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getParameters(){
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
                        $salida.='<option value="'.$row['ncodprm2'].'">'.$row['cdesprm2'].'</option>';
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

        public function getPersonal(){
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
                                                            tabla_aquarius.dcargo LIKE '%almacen%'");
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

        public function genNumber($cod){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                            COUNT(al_regmovi1.ncodalm1) AS numguia,
                                                            COUNT(al_regmovi1.nnromov) AS nummov 
                                                        FROM
                                                            al_regmovi1 
                                                        WHERE
                                                            al_regmovi1.ncodalm1 = :cod");
                $sql->execute(["cod"=>$cod]);

                $row = $sql->fetchAll();

                $salidajson = array("guia_nmr"=>str_pad($row[0]['numguia'] + 1,5,"0",STR_PAD_LEFT),
                                    "mov_nmr"=>str_pad($row[0]['nummov'] + 1,5,"0",STR_PAD_LEFT));

                return $salidajson;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getOrders(){
            try {
                $salida = "";
                $sql = $this->db->connect()->query("SELECT
                            lg_regabastec.ctipmov,
                            lg_regabastec.cper,
                            lg_regabastec.cmes,
                            lg_regabastec.cnumero,
                            lg_regabastec.ffechadoc,
                            lg_regabastec.id_regmov,
                            tb_area.cdesarea,
                            tb_proyecto1.ccodpry,
                            tb_proyecto1.cdespry,
                            tb_ccostos.ccodcos,
                            tb_ccostos.cdescos 
                        FROM
                            lg_regabastec
                            INNER JOIN tb_area ON lg_regabastec.ncodarea = tb_area.ncodarea
                            INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                            INNER JOIN tb_ccostos ON lg_regabastec.ncodcos = tb_ccostos.ncodcos
                            INNER JOIN tb_paramete2 ON lg_regabastec.nNivAten = tb_paramete2.ccodprm2
                            INNER JOIN lg_registro ON lg_regabastec.id_refpedi = lg_registro.id_regmov 
                        WHERE
                            tb_paramete2.ncodprm1 = 13 
                            AND lg_regabastec.nflgactivo = 1 
                            AND lg_regabastec.nEstadoDoc = 4 
                            AND lg_regabastec.ctipmov = 'B' 
                            AND lg_regabastec.cper = YEAR ( NOW( ) ) 
                            AND lg_regabastec.cmes = MONTH ( NOW( ) )
                            LIMIT 20");
                $sql->execute();
                $rowCount = $sql->rowcount();

                if ($rowCount > 0){
                    while ($row = $sql->fetch()) {
                        $salida .='<tr class="pointertr" data-id="'.$row['id_regmov'].'">
                                    <td class="con_borde centro">'.str_pad($row['cnumero'],5,"0",STR_PAD_LEFT).'-'.$row['cper'].'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cdespry']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cdescos']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cdesarea']).'</td>    
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                    <td class="con_borde centro"><a href="'.$row['id_regmov'].'"><i class="fas fa-exchange-alt"></i></a></td>
                                </tr>';
                    }
                }
                else{
                    $salida .= '<tr><td colspan="6" class="centro">No hay registros que mostrar</td></tr>';
                }

                return $salida;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getOrdersWord($string){
            try {
                $salida = "";
                $buscar = (int)$string;

                $sql = $this->db->connect()->prepare("SELECT
                                                    lg_regabastec.ctipmov,
                                                    lg_regabastec.cper,
                                                    lg_regabastec.cmes,
                                                    lg_regabastec.cnumero,
                                                    lg_regabastec.ffechadoc,
                                                    lg_regabastec.id_regmov,
                                                    tb_area.cdesarea,
                                                    tb_proyecto1.ccodpry,
                                                    tb_proyecto1.cdespry,
                                                    tb_ccostos.ccodcos,
                                                    tb_ccostos.cdescos 
                                                FROM
                                                    lg_regabastec
                                                    INNER JOIN tb_area ON lg_regabastec.ncodarea = tb_area.ncodarea
                                                    INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                                                    INNER JOIN tb_ccostos ON lg_regabastec.ncodcos = tb_ccostos.ncodcos
                                                    INNER JOIN tb_paramete2 ON lg_regabastec.nNivAten = tb_paramete2.ccodprm2
                                                    INNER JOIN lg_registro ON lg_regabastec.id_refpedi = lg_registro.id_regmov 
                                                WHERE
                                                    tb_paramete2.ncodprm1 = 13 
                                                    AND lg_regabastec.nflgactivo = 1 
                                                    AND lg_regabastec.nEstadoDoc = 4 
                                                    AND lg_regabastec.ctipmov = 'B' 
                                                    AND lg_regabastec.cnumero =:nord");
                $sql->execute(["nord"=>$buscar]);

                $rowCount = $sql->rowcount();

                if ($rowCount > 0){
                    while ($row = $sql->fetch()) {
                        $salida .='<tr class="pointertr" data-id="'.$row['id_regmov'].'">
                                    <td class="con_borde centro">'.str_pad($row['cnumero'],5,"0",STR_PAD_LEFT).'-'.$row['cper'].'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cdespry']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cdescos']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cdesarea']).'</td>    
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                    <td class="con_borde centro"><a href="'.$row['id_regmov'].'"><i class="fas fa-exchange-alt"></i></a></td>
                                </tr>';
                    }
                }
                else{
                    $salida .= '<tr><td colspan="6" class="centro">No hay registros que mostrar</td></tr>';
                }

                return $salida;
                
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getOrdersByNumer($codigo){
            $item = array();
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                                        logistica.lg_regabastec.id_regmov,
                                                        logistica.lg_regabastec.cper,
                                                        logistica.lg_regabastec.id_refpedi,
                                                        logistica.lg_regabastec.ffechadoc,
                                                        logistica.lg_regabastec.mdetalle,
                                                        logistica.lg_regabastec.cconcepto,
                                                        logistica.lg_regabastec.cdocPDF,
                                                        logistica.lg_regabastec.id_centi,
                                                        logistica.cm_entidad.crazonsoc,
                                                        logistica.lg_registro.cnumero AS pedido,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        logistica.tb_ccostos.ccodcos,
                                                        logistica.tb_ccostos.cdescos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        logistica.cm_entidad.cnumdoc,
                                                        logistica.lg_registro.cconcepto,
                                                        logistica.lg_regabastec.cnumero AS orden
                                                    FROM
                                                        logistica.lg_regabastec
                                                        INNER JOIN logistica.cm_entidad ON logistica.lg_regabastec.id_centi = logistica.cm_entidad.id_centi
                                                        INNER JOIN logistica.lg_registro ON logistica.lg_regabastec.id_refpedi = logistica.lg_registro.id_regmov
                                                        INNER JOIN logistica.tb_proyecto1 ON logistica.lg_regabastec.ncodpry = logistica.tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_area ON logistica.lg_regabastec.ncodarea = logistica.tb_area.ncodarea
                                                        INNER JOIN logistica.tb_ccostos ON logistica.lg_regabastec.ncodcos = logistica.tb_ccostos.ncodcos
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_registro.ncodper = rrhh.tabla_aquarius.internal 
                                                    WHERE
                                                        logistica.lg_regabastec.id_regmov = :cod");
                $sql->execute(["cod"=>$codigo]);
                $rowcount = $sql->rowcount();

                if ($rowcount > 0){
                    while ($row = $sql->fetch()) {
                        $item['codigo']     = $codigo;
                        $item['pedido']     = str_pad($row['pedido'],3,"0",STR_PAD_LEFT);
                        $item['fechadoc']   = $row['ffechadoc'];
                        $item['solicita']   = strtoupper($row['nombres'].' '.$row['apellidos']);
                        $item['proyecto']   = strtoupper($row['ccodpry'].' '.$row['cdespry']);
                        $item['costos']     = strtoupper($row['ccodcos'].' '.$row['cdescos']);
                        $item['area']       = strtoupper($row['ccodarea'].' '.$row['cdesarea']);
                        $item['concepto']   = $row['cconcepto'];
                        $item['detalle']    = $row['mdetalle'];
                        $item['entidad']    = $row['crazonsoc'];
                        $item['ruc']        = $row['cnumdoc'];
                        $item['orden']      = str_pad($row['orden'],5,"0",STR_PAD_LEFT).'-'.$row['cper'];
                        $item['pdf']        = 'public/ordenes/aprobadas/'.$row['cdocPDF'];
                        $item['id']         = uniqid("EM");
                        $item['idord']      = $row['id_regmov'];
                        $item['idped']      = $row['id_refpedi'];
                        $item['ident']      = $row['id_centi'];
                    }
                }

                return $item;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getOrderDetails($cod){
            try {
                $salida = "";

                $estados = $this->getParameters();

                 $sql=$this->db->connect()->prepare("SELECT
                                                    lg_detaabastec.id_regmov,
                                                    lg_detaabastec.niddeta,
                                                    lg_detaabastec.nidpedi,
                                                    lg_detaabastec.id_cprod,
                                                    lg_detaabastec.ncanti,
                                                    lg_detaabastec.ncodmed,
                                                    lg_detaabastec.nfactor,
                                                    lg_detaabastec.nvventa,
                                                    lg_detaabastec.ntotal,
                                                    cm_producto.ccodprod,
                                                    cm_producto.cdesprod,
                                                    tb_unimed.cabrevia 
                                                FROM
                                                    lg_detaabastec
                                                    INNER JOIN cm_producto ON lg_detaabastec.id_cprod = cm_producto.id_cprod
                                                    INNER JOIN tb_unimed ON lg_detaabastec.ncodmed = tb_unimed.ncodmed 
                                                WHERE
                                                    lg_detaabastec.id_regmov = :cod");
                $sql->execute(["cod"=>$cod]);
                $rowcount = $sql->rowcount();
                $cont=0;

                if ($rowcount > 0) {
                    while ($row = $sql->fetch()) {
                        $cont++;
                        $salida.='<tr class="lh1_2rem pointertr" data-id="'.$row['nidpedi'].'">
                                    <td class="con_borde centro"><a href="'.$row['nidpedi'].'" data-action="register"><i class="fas fa-barcode"></i></a></td>
                                    <td class="con_borde centro"><a href="'.$row['nidpedi'].'" data-action="delete"><i class="far fa-trash-alt"></i></a></td>
                                    <td class="centro con_borde" data-iddetpedido ="'.$row['nidpedi'].'"
                                                                 data-iddetorden ="'.$row['niddeta'].'"    
                                                                 data-factor="'.$row['nfactor'].'"
                                                                 data-coduni="'.$row['ncodmed'].'"
                                                                 data-idprod="'.$row['id_cprod'].'">
                                                                '.str_pad($cont,3,"0",STR_PAD_LEFT).'
                                    </td>
                                    <td class="centro con_borde">'.$row['ccodprod'].'</td>
                                    <td class="pl20 con_borde">'.$row['cdesprod'].'</td>
                                    <td class="con_borde centro">'.$row['cabrevia'].'</td>
                                    <td class="con_borde drch pr20">'.number_format($row['ncanti'], 2, '.', ',').'</td>
                                    <td class="con_borde centro"><input type="number" onClick="this.select();" class="drch pr10" value="'.number_format($row['ncanti'], 2, '.', ',').'"></td>
                                    <td class="con_borde"><select name="estado">'. $estados .'</select></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"></td>
                                    <td class="con_borde"><input type="date"></td>
                                </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function genPreview($ingreso,$condicion,$fecha,$proyecto,$origen,$movimiento,$orden,$pedido,$nguia,$nombre,$cargo,$entidad,$details){
            require_once("public/libsrepo/repoingreso.php");
            
            $datos = json_decode($details);
            $fecha_explode = explode("-",$fecha);
            $lc = 0;
            $rc = 0;

            $dia = $fecha_explode[2];
            $mes = $fecha_explode[1];
            $anio = $fecha_explode[0];

            $filename = "public/temp/".uniqid("RI").".pdf";

            if(file_exists($filename))
                unlink($filename);

            $nreg = count($datos);

            $pdf = new PDF($ingreso,$condicion,$dia,$mes,$anio,$proyecto,$origen,$movimiento,$orden,$pedido,$nguia,$nombre,$cargo);
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
                                            $datos[$rc]->cantidad,
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
            echo $filename;
        }

        public function insertarIngreso($ningreso,$fecha,$origen,$fcontable,$entidad,$guia,$orden,$pedido,$estado,$autoriza,$cod_mov,$num_mov,$detalles,$series,$adjuntos){
            $guia_actual = $this->genNumber($origen);
            $mensaje = "Ok";

            try {
                $cod = uniqid("NI");

                $guia_actual = $this->genNumber($origen);

                $fecha_explode = explode("-",$fecha); 
                
                $sql = $this->db->connect()->prepare("INSERT INTO al_regmovi1 SET id_regalm=:cod,ncodmov=:cmo,cper=:anio,cmes=:mes,ncodalm1=:cma,ffecdoc=:fec,
                                                                ffecconta=:fco,id_centi=:idt,cnumguia=:ngi,idref_pedi=:ped,id_userAprob=:apro,nEstadoDoc=:est,
                                                                nflgactivo=:flag,nnromov=:nmov,nnronota=:nnot,idref_abas=:ord");
                $sql->execute(["cod"=>$cod,
                                "cmo"=>$cod_mov,
                                "anio"=>$fecha_explode[0],
                                "mes"=>$fecha_explode[1],
                                "cma"=>$origen,
                                "fec"=>$fecha,
                                "fco"=>$fcontable,
                                "idt"=>$entidad,
                                "ngi"=>$guia,
                                "ped"=>$pedido,
                                "apro"=>$autoriza,
                                "est"=>$estado,
                                "flag"=>1,
                                "nmov"=>$guia_actual["mov_nmr"],
                                "nnot"=>$ningreso,
                                "ord"=>$orden
                                ]);
                $rowCount = $sql->rowcount();
                
                if ($rowCount == 1) {
                    $mensaje = "Registro insertado";
                    $this->insertarDetalles($cod,$detalles,$origen,$orden,$pedido,$estado);
                    $this->insertarSeries($cod,$series);
                    $this->insertarAdjuntos($cod,$adjuntos);
                    $this->saveAction("CREA",$cod,"INGRESOS ALMACEN",$_SESSION['user']);
                }else{
                    $mensaje = "Hubo un problema al crear el registro";
                }

                $salidajson = array("mensaje"=>$mensaje,
                                    "estado"=>true);
                return $salidajson;


            } catch (PDOException  $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function insertarDetalles($cod,$detalles,$origen,$estado){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            $rc = 0;

            for ($i=0; $i <= $nreg-1; $i++) { 
                try {
                    $sql=$this->db->connect()->prepare("INSERT INTO al_regmovi2 SET id_regalm=:cod,ncodalm1=:ori,id_cprod=:cpro,ncantidad=:cant,ncoduni=:uni,
                                        nfactor=:fac,niddetaped=:ped,niddetaord=:ord,nestadoreg=:est,nflgactivo=:flag,nfacmov=:tot");
                    $sql->execute(["cod"=>$cod,
                                    "ori"=>$origen,
                                    "cpro"=>$datos[$rc]->idprod,
                                    "cant"=>$datos[$rc]->cantidad,
                                    "uni"=>$datos[$rc]->coduni,
                                    "fac"=>$datos[$rc]->factor,
                                    "ped"=>$datos[$rc]->iddetped,
                                    "ord"=>$datos[$rc]->iddetord,
                                    "est"=>$estado,
                                    "flag"=>1,
                                    "tot"=> $datos[$rc]->cantidad*$datos[$rc]->factor]);

                    $rc++;
                } catch (PDOException $th) {
                    echo $th;
                    return false;
                }
            }
        }

       
        public function insertarAdjuntos($cod,$adjuntos){
            try {
                $data = json_decode($adjuntos);
                for ($i=0; $i < count($data); $i++) { 
                    $ref = explode("~",$data[$i]->nombre);

                    $nomb = $ref[1];
                    $cref = $ref[0];

                    $cmod = "REGISTRO ALMACEN";

                    $query = $this->db->connect()->prepare("INSERT INTO lg_regdocumento (id_regmov,nidrefer,cmodulo,cdocumento) 
                                                             VALUES (:codp,:cref,:cmod,:nomb)");
                    $query->execute(["codp"=>$cod,"cref"=>$cref,"cmod"=>$cmod,"nomb"=>$nomb]);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertarSeries($cod,$series){
            $datos = json_decode($series);
            $nreg = count($datos);
            $rc=0;

            for ($i=0; $i <= $nreg-1; $i++) { 
                try {
                    $sql=$this->db->connect()->prepare("INSERT INTO cm_prodserie SET id_cprod=:prod,cdesserie=:serie,nflgactivo=:flag,idref_alma=:cod");
                    $sql->execute([ "prod"=>$datos[$rc]->codpro,
                                    "serie"=>$datos[$rc]->serie,
                                    "flag"=>1,
                                    "cod"=>$cod]);
                    $rc++;
                } catch (PDOException $th) {
                    echo $th;
                    return false;
                }
            }
        }

        public function cambiarNota($index){
            try {
                $sql = $this->db->connect()->prepare("SELECT
                                            al_regmovi1.id_regalm,
                                            al_regmovi1.ncodmov,
                                            al_regmovi1.nnromov,
                                            al_regmovi1.nnronota,
                                            al_regmovi1.ncodalm1,
                                            al_regmovi1.ffecdoc,
                                            al_regmovi1.id_centi,
                                            al_regmovi1.cnumguia,
                                            al_regmovi1.ncodpry,
                                            al_regmovi1.ncodcos,
                                            al_regmovi1.idref_pedi,
                                            al_regmovi1.idref_abas,
                                            al_regmovi1.id_userAprob,
                                            al_regmovi1.nEstadoDoc,
                                            al_regmovi1.nflgactivo,
                                            al_regmovi1.ffecconta 
                                        FROM
                                            al_regmovi1
                                        WHERE 
                                            al_regmovi1.id_regalm=:cod");
                $sql->execute(["cod"=>$index]);

                $rs = $sql->fetchAll();

                $salidajson = array("idnota"=>$rs[0]['id_regalm'],
                                    "fechadoc"=>$rs[0]['ffecdoc'],
                                    "fechacont"=>$rs[0]['ffecconta'],);

                return $salidajson;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>