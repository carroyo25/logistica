<?php
    class OrdenesModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function obtenerOrdenesUser($user){
            $salida = "";
            try {
                try {
                    $sql = $this->db->connect()->prepare("SELECT
                                                        lg_regabastec.ctipmov,
                                                        lg_regabastec.cnumero,
                                                        lg_regabastec.cper,
                                                        lg_regabastec.ffechadoc,
                                                        lg_regabastec.nNivAten,
                                                        lg_regabastec.id_regmov,
                                                        lg_regabastec.nflgactivo,
                                                        tb_area.cdesarea,
                                                        tb_proyecto1.ccodpry,
                                                        tb_proyecto1.cdespry,
                                                        tb_ccostos.ccodcos,
                                                        tb_ccostos.cdescos,
                                                        tb_moneda.dmoneda,
                                                        tb_paramete2.cdesprm2,
                                                        lg_pedidocab.cconcepto,
                                                        lg_pedidocab.mobserva,
                                                        lg_regabastec.nfirmaLog,
                                                        lg_regabastec.nfirmaFin,
                                                        lg_regabastec.nfirmaOpe,
                                                        lg_pedidocab.cnumero AS pedido,
                                                        lg_regabastec.id_refpedi
                                                    FROM
                                                        lg_regabastec
                                                        INNER JOIN tb_area ON lg_regabastec.ncodarea = tb_area.ncodarea
                                                        INNER JOIN tb_proyecto1 ON lg_regabastec.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN tb_ccostos ON lg_regabastec.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN tb_moneda ON lg_regabastec.ncodmon = tb_moneda.ncodmon
                                                        INNER JOIN tb_paramete2 ON lg_regabastec.nNivAten = tb_paramete2.ccodprm2
                                                        INNER JOIN lg_pedidocab ON lg_regabastec.id_refpedi = lg_pedidocab.id_regmov 
                                                    WHERE
                                                        tb_paramete2.ncodprm1 = 13 
                                                        AND lg_regabastec.nflgactivo = 1
                                                        AND lg_regabastec.id_cuser=:user");
                    $sql->execute(["user"=>$user]);
                    $rowcount = $sql->rowcount();

                    if ($rowcount > 0) {
                         while ($row = $sql->fetch()) {
                            $num = str_pad($row['cnumero'],6,"0",STR_PAD_LEFT).'-'.$row['cper'];
                            $log = is_null($row['nfirmaLog']) ? '<i class="far fa-square"></i>' : '<i class="far fa-check-square"></i>';
                            $ope = is_null($row['nfirmaOpe']) ? '<i class="far fa-square"></i>' : '<i class="far fa-check-square"></i>';
                            $fin = is_null($row['nfirmaFin']) ? '<i class="far fa-square"></i>' : '<i class="far fa-check-square"></i>';

                            $salida .='<tr>
                                        <td class="con_borde pl20">'.$num.'</td>
                                        <td class="con_borde centro">'.date("d/m/Y", strtotime($row['ffechadoc'])).'</td>
                                        <td class="con_borde pl20">'.strtoupper($row['cconcepto']).'</td>
                                        <td class="con_borde pl20">'.strtoupper($row['cdesarea']).'</td>
                                        <td class="con_borde pl20">'.strtoupper($row['ccodpry']).' '.$row['cdespry'].'</td>
                                        <td class="con_borde centro w5p">'.$log.'</td>
                                        <td class="con_borde centro w5p">'.$ope.'</td>
                                        <td class="con_borde centro w5p">'.$fin.'</td>
                                        <td class="con_borde centro"><a href="'.$row['id_regmov'].'"
                                                                     data-idpedido="'.$row['id_refpedi'].'"
                                                                     data-numerord="'.$num.'"
                                                                     data-nropedido="'.$row['pedido'].'"><i class="far fa-edit"></i></a></td>
                                    </tr>';
                         }   
                    }else {
                        $salida = '<tr><td colspan="11" class="centro">No hay ordenes procesadas</td></tr>';
                    }

                    return $salida;
                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
        
        public function obtenerOrdenesId($cod){
            try {
                $query = $this->db->connect()->prepare("SELECT
                orden.id_regmov,
                orden.id_refpedi,
                orden.ctipmov,
                orden.cnumero AS orden,
                orden.ffechadoc,
                orden.ffechaent,
                orden.id_centi,
                orden.ncodmon,
                orden.ntcambio,
                orden.nigv,
                orden.nexonera,
                orden.ntotal,
                orden.ncodalm,
                orden.ncodpry,
                orden.ncodcos,
                orden.ncodarea,
                orden.ncodper,
                orden.nEstadoDoc,
                orden.id_cuser,
                orden.ncodpago,
                orden.nplazo,
                orden.nfirmaLog,
                orden.nfirmaFin,
                orden.nfirmaOpe,
                orden.cnumcot,
                orden.cdocPDF,
                orden.nflgactivo,
                pagos.cdesprm2 AS pago,
                entrega.cdesprm2 AS entrega,
                logistica.cm_entidad.cnumdoc,
                logistica.cm_entidad.crazonsoc,
                logistica.tb_proyecto1.ccodpry,
                logistica.tb_proyecto1.cdespry,
                logistica.tb_area.cdesarea,
                logistica.tb_area.ccodarea,
                logistica.tb_ccostos.ccodcos,
                logistica.tb_ccostos.cdescos,
                pedido.cnumero AS pedido,
                pedido.cconcepto,
                pedido.mdetalle,
                transporte.cdesprm2,
                rrhh.tabla_aquarius.apellidos,
                rrhh.tabla_aquarius.nombres,
                rrhh.tabla_aquarius.internal 
            FROM
                logistica.lg_regabastec AS orden
                INNER JOIN logistica.tb_paramete2 AS pagos ON orden.ncodpago = pagos.ccodprm2
                INNER JOIN logistica.tb_paramete2 AS entrega ON orden.nplazo = entrega.ccodprm2
                INNER JOIN logistica.cm_entidad ON orden.id_centi = cm_entidad.id_centi
                INNER JOIN logistica.tb_proyecto1 ON orden.ncodpry = tb_proyecto1.ncodpry
                INNER JOIN logistica.tb_area ON orden.ncodarea = tb_area.ncodarea
                INNER JOIN logistica.tb_ccostos ON orden.ncodcos = tb_ccostos.ncodcos
                INNER JOIN logistica.lg_pedidocab AS pedido ON orden.id_refpedi = pedido.id_regmov
                INNER JOIN logistica.tb_paramete2 AS transporte ON orden.ctiptransp = transporte.ccodprm2
                INNER JOIN rrhh.tabla_aquarius ON pedido.ncodper = rrhh.tabla_aquarius.internal 
            WHERE
                orden.id_regmov = :cod 
                AND pagos.ncodprm1 = 11 
                AND entrega.ncodprm1 = 12 
                AND transporte.ncodprm1 = 7");
                $query->execute(["cod"=>$cod]);
                $rowCount = $query->rowCount();
                
                if ($rowCount > 0) {
                    
                    $result = $query->fetchAll();
                    $salida = array("id_regmov"=>$result[0]["id_regmov"],
                                    "pedido"=>$result[0]["pedido"],
                                    "id_refpedi"=>$result[0]["id_refpedi"],
                                    "id_centi"=>$result[0]["id_centi"]);
                     
                    
                    return $salida;
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerDetallesOrdenes(){
            
        }

        public function generarNumeroOrden(){
            try {
                $query = $this->db->connect()->query("SELECT COUNT(lg_regabastec.id_regmov) AS numero FROM lg_regabastec");
                $query->execute();
                $result= $query->fetchAll();

                $numero = str_pad($result[0]['numero']+1,5,0,STR_PAD_LEFT);
                $id = uniqid(); 

                $salida = array("numero"=>$numero,
                                "orden"=>$id) ;

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function verPedidoActivados($user){
            try {
                $salida="";
                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.tb_proyusu.ncodproy,
                                                        logistica.tb_proyecto1.ccodpry,
                                                        logistica.tb_proyecto1.cdespry,
                                                        logistica.tb_ccostos.ccodcos,
                                                        logistica.tb_ccostos.cdescos,
                                                        logistica.tb_area.ccodarea,
                                                        logistica.tb_area.cdesarea,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        rrhh.tabla_aquarius.dni,
                                                        logistica.lg_pedidocab.cnumero,
                                                        logistica.lg_pedidocab.ffechadoc,
                                                        logistica.lg_pedidocab.ffechaven,
                                                        logistica.lg_pedidocab.cconcepto,
                                                        logistica.lg_pedidocab.id_regmov 
                                                    FROM
                                                        logistica.tb_proyusu
                                                        INNER JOIN logistica.lg_pedidocab ON tb_proyusu.ncodproy = lg_pedidocab.ncodpry
                                                        INNER JOIN logistica.tb_proyecto1 ON lg_pedidocab.ncodpry = tb_proyecto1.ncodpry
                                                        INNER JOIN logistica.tb_ccostos ON lg_pedidocab.ncodcos = tb_ccostos.ncodcos
                                                        INNER JOIN logistica.tb_area ON lg_pedidocab.ncodarea = tb_area.ncodarea
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.lg_pedidocab.ncodper = rrhh.tabla_aquarius.internal 
                                                    WHERE
                                                        tb_proyusu.nflgactivo = 1 
                                                        AND tb_proyusu.id_cuser = :user
                                                        AND lg_pedidocab.nEstadoDoc = 7 
                                                    ORDER BY
	                                                    logistica.lg_pedidocab.cnumero ASC");
                $query->execute(["user"=>$user]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0){
                    while ($rs = $query->fetch()) {
                        $salida .='<tr class="pointertr">
                                    <td class="con_borde centro">'.$rs['cnumero'].'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['ffechadoc'])).'</td>
                                    <td class="con_borde centro">'.date("d/m/Y", strtotime($rs['ffechaven'])).'</td>>
                                    <td class="con_borde pl10">'.strtoupper($rs['cconcepto']).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cdesarea']).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['cdespry']).'</td>
                                    <td class="con_borde pl10">'.strtoupper($rs['nombres']." ".$rs['apellidos']).'</td>
                                    <td class="con_borde centro"><a href="'.$rs['id_regmov'].'"><i class="fas fa-exchange-alt"></i></a></td>
                                </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerPedidoPorId($cod){
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

        public function obtenerDetallesPorId($cod) {
            try {
                $salida="";
                $query = $this->db->connect()->prepare("SELECT
                                            lg_pedidodet.id_regmov,
                                            lg_pedidodet.nidpedi,
                                            lg_pedidodet.id_centi,
                                            lg_pedidodet.id_cprod,
                                            lg_pedidodet.ncodmed,
                                            lg_pedidodet.nfactor,
                                            lg_pedidodet.ncantapro,
                                            lg_pedidodet.nprecioref,
                                            lg_pedidodet.nEstadoPed,
                                            lg_pedidodet.nflgaprueba,
                                            lg_pedidodet.nflgactivo,
                                            cm_producto.ccodprod,
                                            cm_producto.cdesprod,
                                            tb_unimed.ncodmed,
                                            tb_unimed.cabrevia,
                                            tb_unimed.nfactor,
                                            cm_entidad.cnumdoc,
                                            cm_entidad.crazonsoc 
                                        FROM
                                            lg_pedidodet
                                            INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod
                                            INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed
                                            INNER JOIN cm_entidad ON lg_pedidodet.id_centi = cm_entidad.id_centi 
                                        WHERE
                                            lg_pedidodet.id_regmov = :cod 
                                            AND lg_pedidodet.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $x=1;

                if ($rowcount> 0) {
                    while ($rs = $query->fetch()) {
                        $salida .='<tr class="h25px">
                                        <td class="con_borde centro" 
                                            data-unitario="'.$rs['nprecioref'].'"
                                            data-iddet="'.$rs['nidpedi'].'"><input type="checkbox"></td>
                                        <td class="con_borde centro">'.str_pad($x,3,0,STR_PAD_LEFT).'</td>
                                        <td class="con_borde centro" data-idprod="'.$rs['id_cprod'].'">'.$rs['ccodprod'].'</td>
                                        <td class="con_borde pl10">'.$rs['cdesprod'].'</td>
                                        <td class="con_borde centro" data-idunid="'.$rs['ncodmed'].'" data-factor="'.$rs['nfactor'].'">'.$rs['cabrevia'].'</td>
                                        <td class="con_borde drch pr10">'.number_format($rs['ncantapro'], 2, '.', ',').'</td>
                                        <td class="con_borde pl10"></td>
                                        <td class="con_borde pl20" 
                                            data-entidadid="'.$rs['id_centi'].'"
                                            data-ruc="'.$rs['cnumdoc'].'">'.$rs['crazonsoc'].'</td>
                                    </tr>';
                        $x++;
                    }
                }

                return $salida;

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function consultarProformas($entidad,$pedido){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                        tb_moneda.ncodmon,
                                                        tb_moneda.cmoneda,
                                                        tb_moneda.cabrevia,
                                                        tb_moneda.dmoneda AS moneda,
                                                        pagos.cdesprm2 AS pago,
                                                        entregas.cdesprm2 AS entrega,
                                                        lg_proformacab.cnumero,
                                                        lg_proformacab.ccondpago,
                                                        lg_proformacab.ccondentrega 
                                                    FROM
                                                        lg_proformacab
                                                        INNER JOIN tb_moneda ON lg_proformacab.ncodmon = tb_moneda.ncodmon
                                                        INNER JOIN tb_paramete2 AS pagos ON lg_proformacab.ccondpago = pagos.ccodprm2
                                                        INNER JOIN tb_paramete2 AS entregas ON lg_proformacab.ccondentrega = entregas.ccodprm2 
                                                    WHERE
                                                        lg_proformacab.id_regmov = :ped 
                                                        AND lg_proformacab.id_centi = :enti 
                                                        AND pagos.ncodprm1 = 11 
                                                        AND entregas.ncodprm1 = 12 
                                                    LIMIT 1");
                $query->execute(["enti"=>$entidad,"ped"=>$pedido]);
                
                $resultado = $query->fetchAll();
                $salida = array("moneda"=>$resultado[0]['moneda'],
                                "pago"=>$resultado[0]['pago'],
                                "entrega"=>$resultado[0]['entrega'],
                                "abrevia"=>$resultado[0]['cabrevia'],
                                'idmoneda'=>$resultado[0]['ncodmon'],
                                'idpago'=>$resultado[0]['ccondpago'],
                                'identrega'=>$resultado[0]['ccondentrega'],
                                "cotizacion"=>$resultado[0]['cnumero']);
               
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

        public function pasarDatosOrden($cabecera,$detalles,$condicion){
            require_once("public/libsrepo/repooc.php");
            
            try {
                
                if  ( $cabecera['ordenpdf'] !== ""){
                    return $cabecera['ordenpdf'];    
                } 
                else {
                    $entidad = $this->obtenerEntidad($cabecera['id_entidad']);
                    $banco = $this->obtenerEntidadBanco($cabecera['id_entidad'],$cabecera['idmoneda']);
                    $datos = json_decode($detalles);
                    $contacto = $this->obtenerEntidadContacto($cabecera['id_entidad']);
    
                    $nombre_contacto    = isset($contacto['nombre']) ? $contacto['nombre'] : "";
                    $telefono_contacto  =  isset($contacto['telefono']) ? $contacto['telefono'] : "";
                    $mail_contacto      = isset($contacto['mail']) ? $contacto['mail'] : "";
    
                    $banco_entidad  = isset($banco['banco']) ? $banco['banco'] : "";
                    $modena_entidad = isset($banco['cmoneda']) ? $banco['cmoneda'] : "";
                    $cuenta_entidad = isset($banco['cta']) ? $banco['cta'] : "";
    
                    if ($cabecera['tipoPedido'] == "B") {
                        $titulo = "ORDEN DE COMPRA" ;
                        $prefix = "OC";
                    }else{
                        $titulo = "ORDEN DE SERVICIO";
                        $prefix = "OS";
                    }
    
                    $nOrd = $this->generarNumeroOrden();
    
                    $titulo = $titulo . " " . $nOrd['numero'];
                    
                    $anio = explode("-",$cabecera['fechaOrd']);
                
    
                    $file = $prefix.$cabecera['orden'].".pdf";
                    $filename = "public/ordenes/emitidas/".$file;
    
                    $pdf = new PDF($titulo,$condicion,$cabecera['fechaOrd'],$cabecera['monedaOrd'],$cabecera['condentrega'],$cabecera['lugarEntrega'],$cabecera['cotizacion'],
                                $cabecera['entrega'],$cabecera['condpago'],$cabecera['precioOrd'],$cabecera['proyectoOrd'],$cabecera['detalleOrd'],$cabecera['elaborado'],
                                $cabecera['entidad'],$cabecera['ruc'],$entidad['direccion'],$entidad['telefono'],$entidad['correo'],$entidad['retencion'],
                                $nombre_contacto,$telefono_contacto,$mail_contacto );
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetWidths(array(10,15,15,10,95,17,15,15));
                    $pdf->SetFont('Arial','',5);
                    $lc = 0;
                    $rc = 0;
    
                    $nreg = count($datos);
    
                    for ($i=0; $i < $nreg; $i++) { 
                        $pdf->SetAligns(array("C","C","R","C","L","C","R","R"));
                        $pdf->Row(array($datos[$i]->item,
                                        $datos[$i]->codigo,
                                        $datos[$i]->cantidad,
                                        $datos[$i]->unidad,
                                        $datos[$i]->descripcion,
                                        $datos[$i]->pedido,
                                        $datos[$i]->punit,
                                        $datos[$i]->total));
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
                    $pdf->Cell(140,6,$this->convertir($cabecera['precioOrd']),"TBR",0,"L",true); 
                    $pdf->SetFont('Arial','B',10);
                    $pdf->Cell(30,6,number_format($cabecera['precioOrd'], 2, '.', ','),"1",1,"R",true);
                
                    $pdf->Ln(1);
                    $pdf->SetFont('Arial',"","7");
                    $pdf->Cell(40,6,"Pedidos Asociados",1,0,"C",true);
                    $pdf->Cell(5,6,"",0,0);
                    $pdf->Cell(80,6,utf8_decode("Información Bancaria del Proveedor"),1,0,"C",true);
                    $pdf->Cell(10,6,"",0,0);
                    $pdf->Cell(40,6,"Valor Venta",0,0);
                    $pdf->Cell(20,6,number_format($cabecera['precioOrd'], 2, '.', ','),0,1);
                                    
                    $pdf->Cell(10,4,utf8_decode("Año"),1,0);
                
                    $pdf->Cell(10,4,"Tipo",1,0);
                    $pdf->Cell(10,4,"Pedido",1,0);
                    $pdf->Cell(10,4,"Mantto",1,0);
                    $pdf->Cell(5,6,"",0,0);
                    $pdf->Cell(35,4,"Detalle del Banco",1,0);
                    $pdf->Cell(15,4,"Moneda",1,0);
                    $pdf->Cell(30,4,"Nro. Cuenta Bancaria",1,1);
                
                    $pdf->Cell(10,4,$anio[0],1,0);
                    $pdf->Cell(10,4,$cabecera['tipoPedido'],1,0);
                    $pdf->Cell(10,4,$cabecera['nropedido'],1,0);
                    $pdf->Cell(10,4,"",1,0);
                    $pdf->Cell(5,6,"",0,0);
                    $pdf->Cell(35,4,utf8_decode($banco_entidad),1,0);
                    $pdf->Cell(15,4,$modena_entidad,1,0);
                    $pdf->Cell(30,4,$cuenta_entidad,1,0);
                    $pdf->Cell(10,4,"",0,0);
                    $pdf->SetFont('Arial',"B","8");
                    $pdf->Cell(20,4,"TOTAL",1,0,"L",true);
                    $pdf->Cell(15,4,$cabecera['mon_abrevia'],1,0,"C",true);
                    $pdf->Cell(20,4,number_format($cabecera['precioOrd'], 2, '.', ','),1,1,"R",true);

                    $pdf->Output($filename,'F');
    
                    return $filename;
                }
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function obtenerEntidad($cod){
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

        public function obtenerEntidadBanco($cod,$mon){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        cm_entidadbco.ncodbco,
                                                        cm_entidadbco.cnrocta,
                                                        cm_entidadbco.ctipcta,
                                                        tb_banco.cdesbco,
                                                        tb_moneda.dmoneda,
                                                        tb_moneda.cmoneda 
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
                        $item['cmoneda'] = $row['cmoneda'];
                    }
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();

                return false;
            }
        }

        public function obtenerEntidadContacto($cod){
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


        public function grabarDatosOrden($cabecera,$detalles){
            try {
                $ret = false;

                $periodo = explode('-',$cabecera['fechaOrd']);

                $query = $this->db->connect()->prepare("INSERT INTO lg_regabastec SET id_regmov=:id,id_refpedi=:pedi,cper=:anio,cmes=:mes,ctipmov=:tipo,
                                                                    cnumero=:orden,ffechadoc=:fecha,ffechaent=:entrega,id_centi=:entidad,ncodmon=:moneda,
                                                                    ntcambio=:tcambio,nigv=:igv,ntotal=:total,ncodpry=:proyecto,ncodcos=:ccostos,
                                                                    ncodarea=:area,ctiptransp=:transporte,id_cuser=:elabora,ncodpago=:pago,nplazo=:pentrega,
                                                                    cnumcot=:cotizacion,cdocPDF=:adjunto,nEstadoDoc=:est,ncodalm=:almacen,nflgactivo=:flag,
                                                                    nNivAten=:atencion");
                $query->execute(["id"=>$cabecera['orden'],
                                "pedi"=>$cabecera['pedido'],
                                "anio"=>$periodo[0],
                                "mes"=>$periodo[1],
                                "tipo"=>$cabecera['tipoPedido'],
                                "orden"=>$cabecera['numOrd'],
                                "fecha"=>$cabecera['fechaOrd'],
                                "entrega"=>$cabecera['entrega'],
                                "entidad"=>$cabecera['id_entidad'],
                                "moneda"=>$cabecera['idmoneda'],
                                "tcambio"=>3.18,
                                "igv"=>0,
                                "total"=>$cabecera['precioOrd'],
                                "proyecto"=>$cabecera['cod_proyecto'],
                                "ccostos"=>$cabecera['cod_costos'],
                                "area"=>$cabecera['cod_area'],
                                "transporte"=>$cabecera['cod_transporte'],
                                "elabora"=>$cabecera['elaborado'],
                                "pago"=>$cabecera['idpago'],
                                "pentrega"=>$cabecera['identrega'],
                                "cotizacion"=>$cabecera['cotizacion'],
                                "adjunto"=>$cabecera['ordenpdf'],
                                "est"=>1,
                                "almacen"=>$cabecera['cod_almacen'],
                                "flag"=>1,
                                "atencion"=>1]);
                $rowCount = $query->rowCount();

                if ($rowCount > 0) {
                    $this->grabarDetalles($detalles,$cabecera['orden']);
                    $ret = true;
                }

                return $ret;
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function grabarDetalles($detalles,$orden){
            try {
                $datos = json_decode($detalles);
                $nreg= count($datos);

                for ($i=0; $i < $nreg; $i++) { 
                    $query = $this->db->connect()->prepare("INSERT INTO lg_detaabastec SET id_regmov=:id,nidpedi=:nid,id_cprod=:cprod,ncanti=:cant,
                                                                                            ncodmed=:unid,ntotal=:total,npventa=:punit,nfactor=:factor,
                                                                                            nestado=1");
                    $query->execute(["id"=>$orden,
                                    "nid"=>$datos[$i]->iddet,
                                    "cprod"=>$datos[$i]->idprod,
                                    "cant"=>$datos[$i]->cantidad,
                                    "unid"=>$datos[$i]->idunid,
                                    "total"=>$datos[$i]->total,
                                    "punit"=>$datos[$i]->punit,
                                    "factor"=>$datos[$i]->factor]);
                }

            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }

        public function actualizarCabeceraPedido($cod){

        }

        public function actualizarDetallesPedido($detalles){

        }
    }
?>

