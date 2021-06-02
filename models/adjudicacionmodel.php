<?php
    class AdjudicacionModel extends Model{

        public function __construct()
        {
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
                                                        AND logistica.lg_registro.nEstadoDoc = 4");
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
                $salida = "";

                $queryProd = $this->db->connect()->prepare("SELECT
                                                lg_regcotiza2.id_regmov,
                                                cm_producto.ccodprod,
                                                cm_producto.cdesprod,
                                                lg_regcotiza2.niddet,
                                                lg_regcotiza2.id_cprod
                                            FROM
                                                lg_regcotiza2
                                                INNER JOIN cm_producto ON lg_regcotiza2.id_cprod = cm_producto.id_cprod 
                                            WHERE
                                                lg_regcotiza2.id_regmov =:cod 
                                            GROUP BY
                                                lg_regcotiza2.id_cprod");
                $queryProd->execute(["cod"=>$cod]);

                while ($row = $queryProd->fetch()) {
                    
                    $rowspan = $this->getNumberFiles($row['id_cprod'],$cod);
                    $line = 0;
                   
                    $coditem = $row['niddet'];

                    $queryItem = $this->db->connect()->prepare("SELECT
                                                                lg_regcotiza2.id_regmov,
                                                                lg_regcotiza2.niddet,
                                                                lg_regcotiza2.ncodcot,
                                                                lg_regcotiza2.id_cprod,
                                                                ROUND( lg_regcotiza2.cantcoti, 2 ) AS cantidad,
                                                                lg_regcotiza2.precunit,
                                                                lg_regcotiza2.impuesto,
                                                                lg_regcotiza2.total,
                                                                lg_regcotiza2.cdetalle,
                                                                lg_regcotiza2.nflgactivo,
                                                                lg_regcotiza2.fregsys,
                                                                lg_regcotiza2.id_centi,
                                                                lg_regcotiza2.ncodmed,
                                                                lg_regcotiza2.cestadodoc,
                                                                cm_producto.ccodprod,
                                                                cm_producto.cdesprod,
                                                                cm_entidad.crazonsoc,
                                                                cm_entidad.cnumdoc,
                                                                tb_unimed.cabrevia,
                                                                tb_unimed.ncodmed 
                                                            FROM
                                                                lg_regcotiza2
                                                                INNER JOIN cm_producto ON lg_regcotiza2.id_cprod = cm_producto.id_cprod
                                                                INNER JOIN cm_entidad ON lg_regcotiza2.id_centi = cm_entidad.id_centi
                                                                INNER JOIN tb_unimed ON lg_regcotiza2.ncodmed = tb_unimed.ncodmed 
                                                            WHERE
                                                                lg_regcotiza2.nflgactivo = 1 
                                                                AND lg_regcotiza2.id_regmov =:cod
                                                                AND lg_regcotiza2.niddet =:item");
                    $queryItem->execute(["cod"=>$cod,"item"=>$coditem]);

                    while ($item = $queryItem->fetch()) {

                        $line++;
                        $state = $item['cestadodoc'] == 0 ? " fila_activo":" fila_inactivo";

                        if ($line == 1) {
                            $salida .= '<tr id="'.$line.'" data-iddetalle="'.$item['niddet'].'" class="'.$state.'">
                                            <td rowspan="'.$rowspan.'" class="borde_item_final centro titulo_producto"> 
                                                    <input type="checkbox">
                                            </td>
                                            <td rowspan="'.$rowspan.'" class="borde_item_final pl20 pr20 titulo_producto">
                                                <a href="'.$row['id_cprod'].'">'.$row['cdesprod'].'</a>
                                            </td>
                                            <td class="con_borde centro" data-idmov="'.$item['id_regmov'].'"><input type="checkbox"></td>
                                            <td class="con_borde centro" scope="row">
                                                <a href="'.$item['id_cprod'].'" data-idprov="'.$item['id_centi'].'" data-idprod="'.$item['ccodprod'].'" data-idreg="'.$item['niddet'].'" id="fileItemAtach">
                                                    <i class="fas fa-paperclip"></i>
                                                </a>
                                            </td>
                                            <td class="con_borde centro" data-iddetalle="'.$item['niddet'].'" data-prod="'.$row['cdesprod'].'">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                                            <td class="con_borde pl10" data-idproveedor="'.$item['id_centi'].'">'.strtoupper($item['crazonsoc']).'</td>
                                            <td class="con_borde centro" data-codmed="'.$item['ncodmed'].'">'. $item['cabrevia'] .'</td>
                                            <td class="con_borde drch pr10">'. $item['cantidad'] .'</td>
                                            <td class="con_borde"></td>
                                            <td class="con_borde"><input type="number" class="drch cantcot" value="'.$item['cantidad'].'"></td>
                                            <td class="con_borde drch pr10">18%</td>
                                            <td class="con_borde"><input type="number" class="drch punit"></td>
                                            <td class="con_borde drch pr10 negrita" data-idprod="'.$row['id_cprod'].'"></td>
                                            <td class="con_borde"><input type="date" class="fechaentrega"></td>
                                            <td class="con_borde">
                                                <select>
                                                    <option value="01">Nacional</option>
                                                    <option value="02">Internacional</option>
                                                </select>
                                            </td>
                                            <td class="con_borde drch pr10"><input type="text" class="pl10"></td>
                                            <td class="oculto">'. $item['ncodmed'] .'</td>
                                            <td class="oculto"></td>
                                            <td class="oculto"></td>
                                        </tr>';
                        }else{
                            if ($line == $rowspan) {
                                $borde = "borde_item_final";
                            }else {
                                $borde = "borde_item_normal";
                            }
                            $salida .= '<tr class="'.$borde.''.$state.'" data-iddetalle="'.$item['niddet'].'">
                                        <td class="oculto"></td>
                                        <td class="oculto"></td>
                                        <td class="con_borde centro" data-idmov="'.$item['id_regmov'].'"><input type="checkbox"></td>
                                        <td class="con_borde centro" scope="row">
                                            <a href="'.$item['id_cprod'].'" data-idprov="'.$item['id_centi'].'" data-idprod="'.$item['ccodprod'].'" data-idreg="'.$item['niddet'].'" id="fileItemAtach">
                                                <i class="fas fa-paperclip"></i>
                                            </a>
                                        </td>
                                        <td class="con_borde centro" data-iddetalle="'.$item['niddet'].'" data-prod="'.$row['cdesprod'].'">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                                        <td class="con_borde pl10" data-idproveedor="'.$item['id_centi'].'">'.strtoupper($item['crazonsoc']).'</td>
                                        <td class="con_borde centro" data-codmed="'.$item['ncodmed'].'">'. $item['cabrevia'] .'</td>
                                        <td class="con_borde drch pr10">'. $item['cantidad'] .'</td>
                                        <td class="con_borde"></td>
                                        <td class="con_borde"><input type="number" class="drch cantcot" value="'.$item['cantidad'].'"></td>
                                        <td class="con_borde drch pr10">18%</td>
                                        <td class="con_borde"><input type="number" class="drch punit"></td>
                                        <td class="con_borde drch pr10 negrita" data-idprod="'.$row['id_cprod'].'"></td>
                                        <td class="con_borde"><input type="date" class="fechaentrega"></td>
                                        <td class="con_borde">
                                            <select>
                                                <option value="01">Nacional</option>
                                                <option value="02">Internacional</option>
                                            </select>
                                        </td>
                                        <td class="con_borde drch pr10"><input type="text" class="pl10"></td>
                                        <td class="oculto">'. $item['ncodmed'] .'</td>
                                        <td class="oculto"></td>
                                        <td class="oculto"></td>
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
        
        //datos del proveedor
        public function getEntities($cod,$tipo){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT cm_entidad.crazonsoc,
                                                        cm_entidad.cviadireccion,
                                                        cm_entidad.cnumdoc,
                                                        tb_paramete2.cdesprm2,
                                                        cm_entidad.ncondpag,
                                                        cm_entidad.nagenret 
                                                    FROM
                                                        cm_entidad
                                                        INNER JOIN tb_paramete2 ON cm_entidad.ncondpag = tb_paramete2.ncodprm2 
                                                    WHERE
                                                        cm_entidad.id_centi =:cod 
                                                        AND tb_paramete2.ncodprm1 = 11");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $nrorq = date("Y")."-".str_pad($this->genDocNumber($tipo),5,"0",STR_PAD_LEFT);

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $item['crazonsoc']      = strtoupper($row['crazonsoc']);
                        $item['cviadireccion']  = strtoupper($row['cviadireccion']);
                        $item['cnumdoc']        = strtoupper($row['cnumdoc']);
                        $item['ncondpag']       = strtoupper($row['ncondpag']);
                        $item['cdesprm2']       = strtoupper($row['cdesprm2']);
                        $item['nagenret']       = strtoupper($row['nagenret']);
                        $item['nrorq']          = $nrorq;
                    }

                    return $item;
                }

            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }

        public function getCurrency(){
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT
                                                    tb_moneda.ncodmon,
                                                    tb_moneda.cmoneda,
                                                    tb_moneda.dmoneda 
                                                FROM
                                                    tb_moneda");
                $query->execute();
                $rowcount = $query->rowcount();


                if($rowcount > 0){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodmon'].'">'.strtoupper($row['dmoneda']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getWarehouse(){
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT
                                                        tb_almacen.ncodalm,
                                                        tb_almacen.cdesalm,
                                                        tb_almacen.cdesdpto,
                                                        tb_almacen.cdesprov,
                                                        tb_almacen.cdesvia,
                                                        tb_almacen.cnrovia 
                                                    FROM
                                                        tb_almacen 
                                                    WHERE
                                                        tb_almacen.nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();


                if($rowcount > 0){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodalm'].'">'.strtoupper($row['cdesalm'].' - '.$row['cdesdpto'].' - '.$row['cdesprov'].' - '.$row['cdesvia'].' - '.$row['cnrovia']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function getParameters($tipo) {
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT ncodprm2,ccodprm2,cdesprm2 FROM tb_paramete2 WHERE ncodprm1 =:tipo");
                $query->execute(["tipo"=>$tipo]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodprm2'].'">'.$row['cdesprm2'].'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function genDocNumber($tipo){
            try {
                $query = $this->db->connect()->prepare("SELECT ntipdoc FROM lg_regabastec WHERE ctipmov =:tipo");
                $query->execute(["tipo"=>$tipo]);
                $rowcount = $query->rowcount();

                return $rowcount+1;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getNumberFiles($cod,$mov){
            try {
                $query = $this->db->connect()->prepare("SELECT
                                                            lg_regcotiza2.id_regmov 
                                                        FROM
                                                            lg_regcotiza2 
                                                        WHERE
                                                            lg_regcotiza2.id_regmov = :mov 
                                                            AND lg_regcotiza2.id_cprod = :cod");
                                                            $query->execute(["cod"=>$cod,"mov"=>$mov]);
                                                            $rowCount = $query->rowcount();

                return $rowCount;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function genPreview($pedido,$proveedor,$idmoneda,$tipo,$condicion,$fecha,$detalles,$moneda,$plazo,$lugar,$cotizacion,$pago,$importe,$fentrega) {
            require_once("public/libsrepo/repooc.php");

            $contacto       = $this->getContac($proveedor);
            $banco          = $this->getBank($proveedor,$idmoneda);
            $distribuidor   = $this->getDataProvee($proveedor);
            $proyecto       = $this->getDataPed($pedido);

            $info = $proyecto['codigo']. " " . $proyecto['nombre'];

            $numdoc = date("Y")."-".str_pad($this->genDocNumber($tipo),5,"0",STR_PAD_LEFT);
            $anio = explode("-",$fecha);

            if ($tipo == "B") {
                $titulo = "ORDEN DE COMPRA" ;
                $prefix = "OC";
            }else{
                $titulo = "ORDEN DE SERVICIO";
                $prefix = "OS";
            }

            $simbolo = $moneda == "SOLES" ? 'S/.':'$'; 

            $titulo = $titulo . " " . $numdoc;
            
            $datos = json_decode($detalles);
            
            $filename = "public/temp/".uniqid($prefix).".pdf";

            if(file_exists($filename))
                unlink($filename);            
            
            $pdf = new PDF($titulo,$condicion,$fecha,$moneda,$plazo,$lugar,$cotizacion,$fentrega,$pago,$importe,
                            $info,$proyecto['detalle'],$proyecto['usuario'],
                            $distribuidor['razon'],$distribuidor['ruc'],$distribuidor['direccion'],$distribuidor['telefono'],$distribuidor['correo'],$distribuidor['retencion'],
                            $contacto['nombre'],$contacto['telefono'],$contacto['mail']);
            $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetWidths(array(10,15,70,8,10,17,15,15,15,15));
            $pdf->SetFont('Arial','',5);
            $lc = 0;
            $rc = 0; 
    
            $nreg = count($datos);

            for ($i=1; $i <=$nreg ; $i++) { 
                $pdf->SetAligns(array("C","L","L","L","R","L","L","L","L","L"));
                $pdf->Row(array(str_pad($i,2,"0",STR_PAD_LEFT),
                                $datos[$rc]->cpro,
                                $datos[$rc]->prod,
                                $datos[$rc]->unid,
                                $datos[$rc]->ccot,
                                '',
                                '',
                                $proyecto['numero'],
                                '',
                                ''));

                $lc++;
                $rc++;
                
                if ($lc == 52) {
                    $pdf->AddPage();
                    $lc = 0;
                }
            }

            $pdf->Ln(3);

            $pdf->SetFillColor(229, 229, 229);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(20,6,"TOTAL :","LTB",0,"C",true);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(140,6,$this->convertir($importe),"TBR",0,"L",true); //UN MIL OCHOCIENTOS Y 00/100 SOLES
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(30,6,$importe,"1",1,"R",true);

            $pdf->Ln(1);
            $pdf->SetFont('Arial',"","7");
            $pdf->Cell(40,6,"Pedidos Asociados",1,0,"C",true);
            $pdf->Cell(5,6,"",0,0);
            $pdf->Cell(80,6,utf8_decode("Información Bancaria del Proveedor"),1,0,"C",true);
            $pdf->Cell(10,6,"",0,0);
            $pdf->Cell(40,6,"Valor Venta",0,0);
            $pdf->Cell(20,6,$importe,0,1);
            

            $pdf->Cell(10,4,utf8_decode("Año"),1,0);
            $pdf->Cell(10,4,"Tipo",1,0);
            $pdf->Cell(10,4,"Pedido",1,0);
            $pdf->Cell(10,4,"Mantto",1,0);
            $pdf->Cell(5,6,"",0,0);
            $pdf->Cell(35,4,"Detalle del Banco",1,0);
            $pdf->Cell(15,4,"Moneda",1,0);
            $pdf->Cell(30,4,"Nro. Cuenta Bancaria",1,1);

            $pdf->Cell(10,4,$anio[2],1,0);
            $pdf->Cell(10,4,$tipo,1,0);
            $pdf->Cell(10,4,$proyecto['numero'],1,0);
            $pdf->Cell(10,4,"",1,0);
            $pdf->Cell(5,6,"",0,0);
            $pdf->Cell(35,4,utf8_decode($banco['banco']),1,0);
            $pdf->Cell(15,4,$banco['moneda'],1,0);
            $pdf->Cell(30,4,$banco['cta'],1,0);
            $pdf->Cell(10,4,"",0,0);
            $pdf->SetFont('Arial',"B","8");
            $pdf->Cell(20,4,"TOTAL",1,0,"L",true);
            $pdf->Cell(15,4,$simbolo,1,0,"C",true);
            $pdf->Cell(20,4,$importe,1,1,"R",true);


            $pdf->Output($filename,'F');
            
            echo $filename;           
        }

        public function getContac($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                            cm_entidadcon.cnombres,
                                                            cm_entidadcon.ctelefono1,
                                                            cm_entidadcon.cemail 
                                                        FROM
                                                            cm_entidadcon 
                                                        WHERE
                                                            cm_entidadcon.id_centi =:cod 
                                                            LIMIT 1");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowcount();


                if ($rowCount > 0) {
                    
                    while ($row = $query->fetch()) {
                        $item['nombre']     = $row['cnombres'];
                        $item['telefono']   = $row['ctelefono1'];
                        $item['mail']       = $row['cemail'];
                    }
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }

        public function getBank($cod,$mon){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        cm_entidadbco.ncodbco,
                                                        cm_entidadbco.cnrocta,
                                                        cm_entidadbco.ctipcta,
                                                        tb_banco.cdesbco,
                                                        tb_moneda.dmoneda 
                                                    FROM
                                                        cm_entidadbco
                                                        INNER JOIN tb_banco ON cm_entidadbco.ncodbco = tb_banco.ccodbco
                                                        INNER JOIN tb_moneda ON cm_entidadbco.cmoneda = tb_moneda.ncodmon 
                                                    WHERE
                                                        cm_entidadbco.id_centi = :cod 
                                                        AND cm_entidadbco.cmoneda = :mon");
                $query->execute(["cod"=>$cod,"mon"=>$mon]);
                $rowCount = $query->rowcount();

                if ($rowCount > 0) {
                    while ($row = $query->fetch()) {
                        $item['cta']    = $row['cnrocta'];
                        $item['banco']  = $row['cdesbco'];
                        $item['moneda'] = $row['dmoneda'];
                    }
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }

        public function getDataPed($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        lg_registro.cnumero,
                                                        lg_registro.ffechadoc,
                                                        lg_registro.id_cuser,
                                                        tb_proyecto1.cdespry,
                                                        tb_proyecto1.ccodpry,
                                                        lg_registro.mdetalle 
                                                    FROM
                                                        lg_registro
                                                        INNER JOIN tb_proyecto1 ON lg_registro.ncodpry = tb_proyecto1.ncodpry 
                                                    WHERE
                                                        lg_registro.id_regmov = :cod");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowcount();

                if($rowCount > 0) {
                    while ($row = $query->fetch()) {
                        $item['numero']     = $row['cnumero'];
                        $item['fecha']      = $row['ffechadoc'];
                        $item['usuario']    = $row['id_cuser'];
                        $item['nombre']     = $row['cdespry'];
                        $item['codigo']     = $row['ccodpry'];
                        $item['detalle']    = $row['mdetalle'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getDataProvee($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        cm_entidad.cnumdoc,
                                                        cm_entidad.crazonsoc,
                                                        cm_entidad.cviadireccion,
                                                        cm_entidad.ctelefono,
                                                        cm_entidad.cemail,
                                                        cm_entidad.nagenret 
                                                    FROM
                                                        cm_entidad 
                                                    WHERE
                                                        cm_entidad.id_centi = :cod");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowcount();

                if($rowCount > 0) {
                    while ($row = $query->fetch()) {
                        $item['ruc']        = $row['cnumdoc'];
                        $item['razon']      = $row['crazonsoc'];
                        $item['direccion']  = $row['cviadireccion'];
                        $item['telefono']   = $row['ctelefono'];
                        $item['correo']     = $row['cemail'];
                        $item['retencion']  = $row['nagenret'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertOCSHeader($datos) {
            try {
                $id = $datos['tipo'] == "B" ? uniqid('OC'):uniqid('OS');
                $nro = $this->genDocNumber($datos['tipo']);
                $cmes = date("m",strtotime($datos['fecha']));
                $cper = date("Y",strtotime($datos['fecha']));
                $serie = '0001';
                
                $query = $this->db->connect()->prepare("INSERT INTO lg_regabastec 
                                                        SET id_regmov=:id,id_refpedi=:ped,cper=:anio,cmes:=:mes,ctipmov=:tip,
                                                            cserie=:ser,cnumero=:cnum,ffechadoc=:fec,ffechaent=:ent,id_centi=:prov,ncodmon=:mon,
                                                            ntotal=:imp,ncodalm=:alm,ncodpry=:proy,ncodcos=:ccos,ncodarea=:area,mobserva=:det,
                                                            ncodper=:user,ncodpago=:pago,nplazo=:plaz,cnumcot=:coti,nNivAten=:aten,nflgactivo=:flag,
                                                            ctiptransp=:trsp");
                $query->execute(["id"=>$id,
                                 "ped"  =>$datos['pedido'],
                                 "anio" =>$cper,
                                 "mes"  =>$cmes,
                                 "tip"  =>$datos['tipo'],
                                 "ser"  =>$serie,
                                 "cnum" =>$nro,
                                 "fec"  =>$datos['fecha'],
                                 "prov" =>$datos['proveedor'],
                                 "mon"  =>$datos['moneda'],
                                 "imp"  =>$datos['importe'],
                                 "alm"  =>$datos['almacen'],
                                 "proy" =>$datos['proyecto'],
                                 "ccos" =>$datos['costos'],
                                 "area" =>$datos['area'],
                                 "det"  =>$datos['espec'],
                                 "user" =>$_SESSION['codper'],
                                 "pago" =>$datos['pago'],
                                 "plaz" =>$datos['entrega'],
                                 "coti" =>$datos['cotizacion'],
                                 "aten" =>$datos['atencion'],
                                 "ent"  =>$datos['fentrega'],
                                 "flag" =>1,
                                 "trsp" =>$datos['transporte']]);
                $rowCount = $query->rowcount();

                if ($rowCount > 0 ) {
                    $this->insertOCSDetails($datos['detalles'],$id,$datos['pedido']);
                    return $id;
                }

                return $rowCount;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function insertOCSDetails($datos,$idoc) {
            try {
                $detalles = json_decode($datos);
                $nreg = count($detalles);

                for ($i=0; $i < $nreg ; $i++) { 
                    try {
                        $query = $this->db->connect()->prepare("INSERT INTO lg_detaabastec SET id_regmov=:id,nidpedi=:idet,id_cprod=:cpro,
                                                                            ncanti=:cant,ncodmed=:idun,nfactor=:fact,nvventa=:puni,ntotal=:tota");
                        $query->execute(["id"=>$idoc,
                                        "idet"=>$detalles[$i]->idet,
                                        "cpro"=>$detalles[$i]->cpro,
                                        'cant'=>$detalles[$i]->ccot,
                                        "idun"=>$detalles[$i]->unid,
                                        "fact"=>$detalles[$i]->fact,
                                        "puni"=>$detalles[$i]->puni,
                                        "tota"=>$detalles[$i]->tota]);
                    } catch (PDOException $th) {
                        echo $th->getMessage();
                        return false;
                    }
                }

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function insertCots($datos){
            try {
                $data = json_decode($datos);
                for ($i=0; $i < count($data); $i++) { 
                    $ref = explode("~",$data[$i]->nombre);

                    $nomb = $ref[1];
                    $cref = $ref[0];

                    $codp = $data[$i]->coddoc;
                    $cmod = "COTIZACION";

                    $query = $this->db->connect()->prepare("INSERT INTO lg_regdocumento (id_regmov,nidrefer,cmodulo,cdocumento) 
                                                             VALUES (:codp,:cref,:cmod,:nomb)");
                    $query->execute(["codp"=>$codp,"cref"=>$cref,"cmod"=>$cmod,"nomb"=>$nomb]);
                }

                return true;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function uploadfilesItems($datos,$codigo,$nfiles){
            try {
                //tiene que crear otra tabla
                $data = json_decode($datos);

                for ($i=0; $i < $nfiles; $i++) { 
                    //echo $data[$i]->nombre_guardar;
                    try {
                        $sql = "INSERT INTO ";
                    } catch (PDOException $th) {
                        echo $th->getMessage();
                        return false;
                    }
                }

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function finishCot($item) {
            try {
                $sql = $this->db->connect()->prepare("UPDATE lg_regcotiza2 SET cestadodoc=:estado WHERE niddet=:item");
                $sql->execute(["item"=>$item,
                                "estado"=>1]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function cerrarPedido($pedido){
            
        }
    }
?>