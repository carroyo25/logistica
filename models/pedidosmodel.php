<?php
    class PedidosModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getAllUserRecords($user){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                logistica.lg_pedidocab.id_regmov,
                                                logistica.lg_pedidocab.ffechadoc,
                                                logistica.lg_pedidocab.cconcepto,
                                                logistica.lg_pedidocab.ffechaven,
                                                logistica.lg_pedidocab.cconcepto,
                                                logistica.lg_pedidocab.nEstadoDoc,
                                                logistica.lg_pedidocab.id_cuser,
                                                logistica.lg_pedidocab.ncodmov,
                                                logistica.lg_pedidocab.cnumero,
                                                logistica.lg_pedidocab.nNivAten,
                                                logistica.tb_proyecto1.cdespry,
                                                logistica.tb_proyecto1.ccodpry,
                                                logistica.tb_area.ccodarea,
                                                logistica.tb_area.cdesarea,
                                                rrhh.tabla_aquarius.apellidos,
                                                rrhh.tabla_aquarius.nombres,
                                                atenciones.cdesprm2 AS atencion,
                                                estados.cdesprm2 AS estado 
                                            FROM
                                                logistica.lg_pedidocab
                                                INNER JOIN logistica.tb_proyecto1 ON logistica.lg_pedidocab.ncodpry = logistica.tb_proyecto1.ncodpry
                                                INNER JOIN logistica.tb_area ON logistica.lg_pedidocab.ncodarea = logistica.tb_area.ncodarea
                                                INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal
                                                INNER JOIN logistica.tb_paramete2 AS atenciones ON logistica.lg_pedidocab.nNivAten = atenciones.ccodprm2
                                                INNER JOIN logistica.tb_paramete2 AS estados ON logistica.lg_pedidocab.nEstadoDoc = estados.ccodprm2 
                                            WHERE
                                                logistica.lg_pedidocab.id_cuser = :usr 
                                                AND atenciones.ncodprm1 = 13 
                                                AND estados.ncodprm1 = 4");
                $query->execute(["usr"=>$user]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .='<tr class="h35px" data-idx="'.$row['id_regmov'].'">
                                <td class="con_borde centro">'.$row['cnumero'].'</td>
                                <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechaven'])).'</td>
                                <td class="con_borde pl10">'.$row['cconcepto'].'</td>
                                <td class="con_borde pl10">'.$row['ccodarea'].' '.$row['cdesarea'].'</td>
                                <td class="con_borde pl10">'.$row['ccodpry'].' '.$row['cdespry'].'</td>
                                <td class="con_borde pl10">'.$row['apellidos'].' '.$row['nombres'].'</td>
                                <td class="con_borde centro '. strtolower($row['estado']) .'">'.$row['estado'].'</td>
                                <td class="con_borde centro '. strtolower($row['atencion']) .'">'.$row['atencion'].'</td>
                                <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="editar"  title="editar"><i class="far fa-edit"></i></a></td>
                                <td class="con_borde centro"><a href="'.$row['id_regmov'].'" data-poption="cambiar" title="cambiar atencion"><i class="fas fa-highlighter"></i></a></td>
                            </tr>';
                    }
                }
                else {
                    $salida = '<tr><td colspan="12>No hay registros que mostrar</td></tr>';
                }

                return $salida;

            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getRecordById($cod){
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
                                                            logistica.lg_pedidocab.ffechaven,
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
                                        $item['ffechaven']       = $row['ffechaven'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getDetailsById($cod,$tipo){
            try {
                $salida = '';
                $t = $tipo == "B" ? "BIEN" : "SERVICIO";
                
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_pedidodet.nidpedi,
                                                        lg_pedidodet.id_cprod,
                                                        ROUND( lg_pedidodet.ncantpedi, 2 ) AS cantidad,
                                                        lg_pedidodet.nEstadoPed,
                                                        lg_pedidodet.nFlgCalidad,
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
                        $swcalidad = $row['nFlgCalidad'] == 1 ? '<i class="fas fa-check"></i>':' <i class="far fa-square"></i>';

                        $salida.='<tr class="lh1_2rem">
                            <td class="con_borde centro"><a href="#" data-topcion="edit"><i class="far fa-edit"></i></a></td>
                            <td class="con_borde centro"><a href="#" data-topcion="delete"><i class="fas fa-eraser"></i></a></td>
                            <td class="con_borde drch pr20">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                            <td class="con_borde centro" data-indice="'.$row['id_cprod'].'">'. $row['ccodprod'] .'</td>
                            <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                            <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                            <td class="con_borde" contenteditable="true"><input type="number" class="drch" value="'.$row['cantidad'] .'"></td>
                            <td class="con_borde"></td>
                            <td class="con_borde"></td>
                            <td class="con_borde centro">'. $swcalidad .'</td>
                            <td class="con_borde centro">'. $t .'</td>
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
        
        public function getAllCosts(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT ncodcos,ccodcos,cdescos FROM tb_ccostos WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodcos'].'">'.$row['ccodcos'].' '.strtoupper($row['cdescos']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllAreas() {
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT ncodarea,ccodarea,cdesarea FROM tb_area WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodarea'].'">'.$row['ccodarea'].' '.strtoupper($row['cdesarea']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
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
                        $salida.='<li><a href="'.$row['ccodprm2'].'">'.$row['ccodprm2'].' '.$row['cdesprm2'].'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllNames() {
            try {
                $salida = "";
                $query = $this->db->connectrrhh()->query("SELECT dni, CONCAT(apellidos,', ',nombres) AS nombres, internal FROM tabla_aquarius 
                                                        WHERE estado = 'AC' ORDER BY apellidos ASC");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['internal'].'">'.$row['dni'].' '.$row['nombres'].'</a></li>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllItems($tipo){
            try {
                $salida ="";
                $query  = $this->db->connect()->prepare("SELECT
                                                            cm_producto.id_cprod,
                                                            cm_producto.ccodprod,
                                                            cm_producto.cdesprod,
                                                            cm_producto.cdescomer,
                                                            cm_producto.cdestecni,
                                                            cm_producto.cmarca,
                                                            cm_producto.cmodelo,
                                                            cm_producto.fregsys,
                                                            cm_producto.ccolor,
                                                            cm_producto.cnroparte,
                                                            tb_unimed.cdesmed,
                                                            tb_unimed.cabrevia,
                                                            tb_unimed.nfactor,
                                                            tb_unimed.ncodmed 
                                                        FROM
                                                            cm_producto
                                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                        WHERE
                                                            cm_producto.nflgactivo = '1' 
                                                            AND cm_producto.ntipoprod = :tipo 
                                                        ORDER BY
                                                            cm_producto.ccodprod ASC
                                                        LIMIT 0,100");
                $query->execute(["tipo"=>$tipo]);

                while($row = $query->fetch()){
                    $salida.='<tr class="pointertr">
                                <td data-idprod = "'.$row['id_cprod'].'" data-unidad="'.$row['ncodmed'].'">'.$row['ccodprod'].'</td>
                                <td>'.strtoupper($row['cdesprod']).'</td>
                                <td>'.strtoupper($row['cmarca']).'</td>
                                <td>'.strtoupper($row['cmodelo']).'</td>
                                <td>'.strtoupper($row['cnroparte']).'</td>
                                <td>'.strtoupper($row['cabrevia']).'</td>
                                <td class="oculto">'.strtoupper($row['nfactor']).'</td>
                            </tr>';
                }
                
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllItemsByLetter($tipo,$letra){
            try {
                $salida ="";
                $query  = $this->db->connect()->prepare("SELECT
                                                            cm_producto.id_cprod,
                                                            cm_producto.ccodprod,
                                                            cm_producto.cdesprod,
                                                            cm_producto.cdescomer,
                                                            cm_producto.cdestecni,
                                                            cm_producto.cmarca,
                                                            cm_producto.cmodelo,
                                                            cm_producto.fregsys,
                                                            cm_producto.ccolor,
                                                            cm_producto.cnroparte,
                                                            tb_unimed.cdesmed,
                                                            tb_unimed.cabrevia,
                                                            tb_unimed.nfactor,
                                                            tb_unimed.ncodmed 
                                                        FROM
                                                            cm_producto
                                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                        WHERE
                                                            cm_producto.nflgactivo = '1' 
                                                            AND cm_producto.ntipoprod = :tipo
                                                            AND SUBSTR(cm_producto.cdesprod,1,1) = :letra
                                                        ORDER BY
                                                            cm_producto.cdesprod ASC");
                $query->execute(["tipo"=>$tipo,"letra"=>$letra]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while($row = $query->fetch()){
                        $salida.='<tr class="pointertr">
                                    <td data-idprod = "'.$row['id_cprod'].'" data-unidad="'.$row['ncodmed'].'">'.$row['ccodprod'].'</td>
                                    <td>'.strtoupper($row['cdesprod']).'</td>
                                    <td>'.strtoupper($row['cmarca']).'</td>
                                    <td>'.strtoupper($row['cmodelo']).'</td>
                                    <td>'.strtoupper($row['cnroparte']).'</td>
                                    <td>'.strtoupper($row['cabrevia']).'</td>
                                    <td class="oculto">'.strtoupper($row['nfactor']).'</td>
                                </tr>';
                    }
                }else {
                    $salida .= '<tr><td colspan="6" class="centro">NO HAY REGISTROS</td></tr>';
                }
                
                
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllItemsByWord($tipo,$palabra){
            try {
                $salida ="";
                $query  = $this->db->connect()->prepare("SELECT
                                                            cm_producto.id_cprod,
                                                            cm_producto.ccodprod,
                                                            cm_producto.cdesprod,
                                                            cm_producto.cdescomer,
                                                            cm_producto.cdestecni,
                                                            cm_producto.cmarca,
                                                            cm_producto.cmodelo,
                                                            cm_producto.fregsys,
                                                            cm_producto.ccolor,
                                                            cm_producto.cnroparte,
                                                            tb_unimed.cdesmed,
                                                            tb_unimed.cabrevia,
                                                            tb_unimed.nfactor,
                                                            tb_unimed.ncodmed 
                                                        FROM
                                                            cm_producto
                                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed 
                                                        WHERE
                                                            cm_producto.nflgactivo = '1' 
                                                            AND cm_producto.ntipoprod = :tipo
                                                            AND cm_producto.cdesprod LIKE :palabra
                                                        ORDER BY
                                                            cm_producto.cdesprod ASC");
                $query->execute(["tipo"=>$tipo,"palabra"=>"%".$palabra."%"]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while($row = $query->fetch()){
                        $salida.='<tr class="pointertr">
                                    <td data-idprod = "'.$row['id_cprod'].'" data-unidad="'.$row['ncodmed'].'">'.$row['ccodprod'].'</td>
                                    <td>'.strtoupper($row['cdesprod']).'</td>
                                    <td>'.strtoupper($row['cmarca']).'</td>
                                    <td>'.strtoupper($row['cmodelo']).'</td>
                                    <td>'.strtoupper($row['cnroparte']).'</td>
                                    <td>'.strtoupper($row['cabrevia']).'</td>
                                    <td class="oculto">'.strtoupper($row['nfactor']).'</td>
                                </tr>';
                    }
                }else {
                    $salida .= '<tr><td colspan="6" class="centro">NO HAY REGISTROS</td></tr>';
                }
                
                
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getInitials(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT
                                                            SUBSTR(cm_producto.cdesprod,1,1) AS inicial
                                                        FROM
                                                            cm_producto 
                                                        WHERE
                                                            cm_producto.nflgactivo = '1' 
                                                            AND cm_producto.ntipoprod = 1
                                                        GROUP BY
                                                            inicial
                                                        ORDER BY
                                                            cm_producto.cdesprod ASC");
                $query->execute();

                while ($row = $query->fetch()) {
                    $salida .= '<li><a href="'.$row['inicial'].'">'.$row['inicial'].'</a></li>';
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllGroups() {
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT
                            tb_clasprod.ccodgru,
                            tb_clasprod.cdesclas
                            FROM
                                tb_clasprod
                            WHERE 
                                tb_clasprod.nnivclas = 1
                            ORDER BY
                                tb_clasprod.cdesclas ASC");
                $query->execute();
                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ccodgru'].'">'.strtoupper($row['cdesclas']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllClasesByCodGroup($cod){
            try {
                $salida = "";

                $query = $this->db->connect()->prepare("SELECT tb_clasprod.ccodcla,tb_clasprod.cdesclas 
                                                        FROM tb_clasprod 
                                                        WHERE ccodgru = :cod 
                                                        AND tb_clasprod.nnivclas = 2");
                $query->execute(['cod' => $cod]);
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ccodcla'].'">'.strtoupper($row['cdesclas']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllFamiliasByCodGroupClass($grupo,$clase){
            try {
                $salida = "";

                $query = $this->db->connect()->prepare("SELECT
                                                        fam.ncodcata AS idx,
                                                        fam.cdesclas AS nombre_familia,
                                                        fam.ccodgru AS codigo_grupo,
                                                        fam.ccodcla AS codigo_clase,
                                                        fam.ccodfam AS codigo_familia 
                                                    FROM
                                                        tb_clasprod AS fam
                                                        INNER JOIN ( SELECT ccodgru, cdesclas AS cdesgru FROM tb_clasprod WHERE nnivclas = 1 ) gru ON fam.ccodgru = gru.ccodgru
                                                        INNER JOIN ( SELECT ccodgru, ccodcla, cdesclas AS cdescla FROM tb_clasprod WHERE nnivclas = 2 ) cla ON fam.ccodgru = cla.ccodgru 
                                                        AND fam.ccodcla = cla.ccodcla 
                                                    WHERE
                                                        NOT ISNULL( fam.ccodfam ) 
                                                        AND fam.cdesclas <> ''
                                                        AND fam.ccodgru = :grupo
                                                        AND fam.ccodcla = :clase
                                                    ORDER BY
                                                        gru.cdesgru");
                $query->execute(['grupo' => $grupo, 'clase'=>$clase]);
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['codigo_familia'].'">'.strtoupper($row['nombre_familia']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllUnits(){
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT
                                                    tb_unimed.ncodmed,
                                                    tb_unimed.ccodmed,
                                                    tb_unimed.cdesmed,
                                                    tb_unimed.nunisunat,
                                                    tb_unimed.nfactor
                                                    FROM
                                                    tb_unimed
                                                    ORDER BY
                                                    tb_unimed.cdesmed");
                
                $query->execute();
                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodmed'].'">'.strtoupper($row['cdesmed']).'</a></li>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllProys(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT ncodpry,ccodpry,cdespry FROM tb_proyecto1 WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodpry'].'">'.$row['ccodpry'].' '.strtoupper($row['cdespry']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //generar el codigo del nuevo producto
        public function genNewCode($cod){
            try {
                $query = $this->db->connect()->prepare("SELECT cm_producto.ccodprod FROM cm_producto WHERE SUBSTR(cm_producto.ccodprod,1,8) = :cod");
                $query->execute(["cod"=>$cod]);
                
                $rowcount = $query->rowCount();
                $codigo = str_pad($rowcount+1,4,"0",STR_PAD_LEFT);
                $respuesta = $cod.$codigo;

                return $respuesta;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //insertar el nuevo producto
        public function insertItem($datos){
            try {
                $id_pro = $this->genNewCode( $datos['cod'] );
                
                $idx = uniqid();

                $query = $this->db->connect()->prepare("INSERT INTO cm_producto (id_cprod,ccodprod,cdesprod,cdescomer,cdestecni,nunidsec,mdetalle,cmarca,cmodelo,
                                                                                cnroparte,nflgactivo,ntipoprod)
                                                         VALUES (:idx,:cod,:dsc,:nco,:ncr,:und,:det,:mar,:mod,:npa,:est,:tip)");
                $query->execute(["idx"=>$idx,
                                 "cod"=>$id_pro,
                                 "dsc"=>strtoupper($datos["dsc"]),
                                 "nco"=>strtoupper($datos["nco"]),
                                 "ncr"=>strtoupper($datos["ncr"]),
                                 "und"=>strtoupper($datos["und"]),
                                 "det"=>strtoupper($datos["det"]),
                                 "mar"=>strtoupper($datos["mar"]),
                                 "mod"=>strtoupper($datos["mod"]),
                                 "npa"=>strtoupper($datos["npa"]),
                                 "est"=>$datos["est"],
                                 "tip"=>$datos["tip"]]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0){
                    $salida = true;
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //generar el nuevo pedido
        public function genNewRequest(){
            try {
                $item = array();
                $query = $this->db->connect()->query("SELECT lg_pedidocab.id_regmov FROM lg_pedidocab");
                $query->execute();
                
                $rowcount = $query->rowCount();
                $numero = str_pad($rowcount+1,6,"0",STR_PAD_LEFT);
                $codigo = uniqid('PE');

                $item['codigo'] = $codigo;
                $item['numero'] = $numero;

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllMails(){
            try {
                $salida = "";
                $query = $this->db->connectrrhh()->query("SELECT
                                                            corporativo,
                                                            CONCAT( nombres, ', ', apellidos ) AS nombres 
                                                        FROM
                                                            tabla_aquarius 
                                                        WHERE
                                                            corporativo <> 'NULL' 
                                                            AND dcargo LIKE '%almacen%' 
                                                        ORDER BY
                                                            apellidos ASC");
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

        //grabar cabecera del producto
        public function insertRequest($datos){
            try {
                $cmes = date("m",strtotime($datos['fecr']));
                $cper = date("Y",strtotime($datos['fecr']));
                $nmov = 1;
                $serie = "0001";
                $codd = "PD";
                $accion = "crear";

                $query = $this->db->connect()->prepare("INSERT INTO lg_pedidocab SET id_regmov=:idx,cper=:cper,cmes=:cmes,ctipmov=:tmov,ncodmov=:nmov,
                                                                                 ccoddoc=:codd,cserie=:ser,cnumero=:num,ffechadoc=:fecr,ncodpry=:cpry,
                                                                                 ncodcos=:ccos,ncodarea=:care,ncodper=:csol,cconcepto=:conc,mdetalle=:espe,
                                                                                 ctiptransp=:ctra,nEstadoReg=:creg,nEstadoDoc=:cest,id_cuser=:usr,nflgactivo=:est,
                                                                                 nNivAten=:aten,ffechaven=:fven");
                $query->execute(["idx"  => $datos['codp'],
                                 "cpry" => $datos['cpry'],
                                 "ccos" => $datos['ccos'],
                                 "care" => $datos['care'],
                                 "ctra" => $datos['ctra'],
                                 "cest" => $datos['cest'],
                                 "creg" => $datos['creg'],
                                 "csol" => $datos['csol'],
                                 "num"  => $datos['num'],
                                 "fecr" => $datos['fecr'],
                                 "usr"  => $datos['usr'],
                                 "conc" => $datos['conc'],
                                 "espe" => $datos['espe'],
                                 "est"  => 1,
                                 "aten" => $datos['aten'],
                                 "cper" => $cper,
                                 "cmes" => $cmes,
                                 "tmov" => $datos['ctip'],
                                 "nmov" => $nmov,
                                 "ser"  => $serie,
                                 "codd" => $codd,
                                 "fven" => $datos['fven']]);
                
                $rowcount = $query->rowcount();

                $this->saveAction($accion,$datos['codp'],"PEDIDOS",$datos['usr']);

                return $rowcount;

            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }            
        }

        public function updateRequest($datos){
            try {
                $cmes = date("m",strtotime($datos['fecr']));
                $cper = date("Y",strtotime($datos['fecr']));
                $nmov = 1;
                $serie = "0001";
                $codd = "PD";
                $accion = "modificar";

                $query = $this->db->connect()->prepare("UPDATE lg_pedidocab SET cper=:cper,cmes=:cmes,ctipmov=:tmov,ncodmov=:nmov,
                                                                                 ccoddoc=:codd,cserie=:ser,cnumero=:num,ffechadoc=:fecr,ncodpry=:cpry,
                                                                                 ncodcos=:ccos,ncodarea=:care,ncodper=:csol,cconcepto=:conc,mdetalle=:espe,
                                                                                 ctiptransp=:ctra,nEstadoReg=:creg,nEstadoDoc=:cest,id_cuser=:usr,nflgactivo=:est,
                                                                                 nNivAten=:aten WHERE id_regmov=:idx");
                $query->execute(["idx"  => $datos['codp'],
                                 "cpry" => $datos['cpry'],
                                 "ccos" => $datos['ccos'],
                                 "care" => $datos['care'],
                                 "ctra" => $datos['ctra'],
                                 "cest" => $datos['cest'],
                                 "creg" => $datos['creg'],
                                 "csol" => $datos['csol'],
                                 "num"  => $datos['num'],
                                 "fecr" => $datos['fecr'],
                                 "usr"  => $datos['usr'],
                                 "conc" => $datos['conc'],
                                 "espe" => $datos['espe'],
                                 "est"  => $datos['est'],
                                 "aten" => $datos['aten'],
                                 "cper" => $cper,
                                 "cmes" => $cmes,
                                 "tmov" => $datos['ctip'],
                                 "nmov" => $nmov,
                                 "ser"  => $serie,
                                 "codd" => $codd]);
                
                $rowcount = $query->rowcount();

                $this->deleteDetails($datos['codp']);
                $this->saveAction($accion,$datos['codp'],"PEDIDOS",$datos['usr']);

                return true;

            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function insertItemsTable($datos){
            try {
                $data = json_decode($datos);
                for ($i=0; $i < count($data); $i++) {
                   $id   = $data[$i]->indice; 
                   $cant = $data[$i]->cantidad;
                   $esta = 1;
                   $unid = $data[$i]->unidad;
                   $codp = $data[$i]->codped;
                   $veri = $data[$i]->verifica;
                   $aten = 3;
                   $flag = 1;
                   $pedido = $data[$i]->pedido;

                   $query = $this->db->connect()->prepare("INSERT INTO lg_pedidodet (id_regmov,id_cprod,ncodmed,nEstadoReg,ncantpedi,nTipoAten,nFlgCalidad,nflgactivo,pedido) 
                                                            VALUES (:codp,:id,:unid,:esta,:cant,:aten,:veri,:flag,:pedido)");
                   $query->execute(["codp"=>$codp,"cant"=>$cant,"esta"=>$esta,"unid"=>$unid,"aten"=>$aten,"flag"=>$flag,"id"=>$id,"veri"=>$veri,"pedido"=>$pedido]);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertAtachs($datos){
            try {
                $data = json_decode($datos);
                for ($i=0; $i < count($data); $i++) { 
                    $ref = explode("~",$data[$i]->nombre);

                    $nomb = $ref[1];
                    $cref = $ref[0];

                    $codp = $data[$i]->codped;
                    $cmod = "PEDIDO";

                    $query = $this->db->connect()->prepare("INSERT INTO lg_regdocumento (id_regmov,nidrefer,cmodulo,cdocumento) 
                                                             VALUES (:codp,:cref,:cmod,:nomb)");
                    $query->execute(["codp"=>$codp,"cref"=>$cref,"cmod"=>$cmod,"nomb"=>$nomb]);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function deleteDetails($codigo) {
            try {
                $query= $this->db->connect()->prepare("UPDATE lg_pedidodet SET nflgactivo=:flag WHERE id_regmov=:cod");
                $query->execute(["flag"=>0,"cod"=>$codigo]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function sendmails($datos){
            require_once("public/PHPMailer/PHPMailerAutoload.php");

            try {
                $data = json_decode($datos);
                $existe = false;

                if ( $data[0]->tipo == "B"){
                    $this->genOc($data[0]->codped);
                }else {
                    //ver para cambiar segun el formato de orden de servicio
                    $this->genOc($data[0]->codped);
                }
                
                $mensaje = $data[0]->msg;
                $mails = count($data);
                $title = utf8_decode("Aprobación de Pedido");

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

                $filename = "public/pedidos/emitidos/".$data[0]->codped.".pdf";

                if (file_exists( $filename )) {
                    $mail->AddAttachment($filename);
                    $existe = true;	
                }
                
                if (!$mail->send()) {
                    $mensaje = $mail->ErrorInfo;
			    }else {
                    $mensaje = true;
                    $this->changeStatusHeader($data[0]->codped);
                    $this->changeStatusDetails($data[0]->codped);
                }
                
                return $mensaje;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //cambia el estado del pedido
        public function changeStatusHeader($codigo){
            try {
                $cest = 2;
                $query = $this->db->connect()->prepare("UPDATE lg_pedidocab SET nEstadoDoc=:cest WHERE id_regmov=:idx");
                $query->execute(["cest"=>$cest,"idx"=>$codigo]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //cambia el estado de los detalles
        // el estado es 2 para verificar las existencias en almacen
        public function changeStatusDetails($codigo){
            try {
                
                $cest = 2;
                $query = $this->db->connect()->prepare("UPDATE lg_pedidodet SET nEstadoReg=:cest WHERE id_regmov=:idx AND nflgactivo = 1");
                $query->execute(["cest"=>$cest,"idx"=>$codigo]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //genera la nota para el adjunto
        public function genOc($cod){
            require_once("public/libsrepo/repopedidos.php");
            $query = $this->db->connect()->prepare("SELECT  logistica.lg_pedidocab.id_regmov,
                                                            logistica.lg_pedidocab.cnumero,
                                                            logistica.lg_pedidocab.ccoddoc,
                                                            logistica.lg_pedidocab.cserie,
                                                            logistica.lg_pedidocab.ffechadoc,
                                                            logistica.lg_pedidocab.cconcepto,
                                                            logistica.lg_pedidocab.mdetalle,
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
                                    $dti = "";
                                    $mmt = "";
                                    $cla = "NORMAL";
                                    $msj = "EMITIDO";
                                    $reg = "";
                                    $apr = "";
                }
            }

            $filename = "public/pedidos/emitidos/".$cod.".pdf";

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
                                $row['ccodprod'],
                                utf8_decode($row['cdesprod']),
                                $row['cabrevia'],
                                $row['cantidad'],
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
            
        }
    }
?>