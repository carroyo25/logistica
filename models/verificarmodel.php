<?php
    class VerificarModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function verPedidosUser($user) {
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
                                                AND estados.ncodprm1 = 4
                                                AND lg_pedidocab.nEstadoDoc = 6");
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
                            </tr>';
                    }
                }
               

                return $salida;

            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function llamarCabeceraPedido($cod){
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
                                                        AND atenciones.ncodprm1 = 13 
                                                        AND estados.ncodprm1 = 4 
                                                        AND transportes.ncodprm1 = 7");
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
                                        $item['ffechaven']      = $row['ffechaven'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function llamarDetallePedido($cod,$tipo){
            try {
                $salida = '';
                $t = $tipo == "B" ? "BIEN" : "SERVICIO";
                
                $query = $this->db->connect()->prepare("SELECT
                                                        lg_pedidodet.nidpedi,
                                                        lg_pedidodet.id_cprod,
                                                        ROUND( lg_pedidodet.ncantapro, 2 ) AS cantidad,
                                                        ROUND( lg_pedidodet.ncantaten, 2 ) AS almacen,
                                                        lg_pedidodet.nEstadoPed,
                                                        lg_pedidodet.nFlgCalidad,
                                                        tb_unimed.nfactor,
                                                        tb_unimed.cabrevia,
                                                        cm_producto.ccodprod,
                                                        cm_producto.cdesprod,
                                                        lg_pedidodet.id_centi,
                                                        lg_pedidodet.id_regmov,
                                                        CONCAT(lg_pedidodet.id_regmov,'_',lg_pedidodet.id_centi,'_',lg_pedidodet.nidpedi,'.pdf')  AS archivo
                                                    FROM 
                                                        lg_pedidodet
                                                        INNER JOIN tb_unimed ON lg_pedidodet.ncodmed = tb_unimed.ncodmed
                                                        INNER JOIN cm_producto ON lg_pedidodet.id_cprod = cm_producto.id_cprod 
                                                    WHERE
                                                        lg_pedidodet.id_regmov =:cod
                                                        AND lg_pedidodet.nflgactivo = 1
                                                        AND lg_pedidodet.nflgadjudicado = 1
                                                        AND ISNULL(lg_pedidodet.nflgaprueba)");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $line = 0;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $line++;
                        $adjunto =  constant("URL")."/public/manuales/".$row['archivo'];;
                        $salida.='<tr class="lh1_2rem">
                            <td class="con_borde drch pr20">'. str_pad($line,3,"0",STR_PAD_LEFT) .'</td>
                            <td class="con_borde centro" 
                                        data-indice="'.$row['nidpedi'].'" data-entidad="'.$row['id_centi'].'" data-pedido="'.$row['id_regmov'].'">'. $row['ccodprod'] .'</td>
                            <td class="con_borde pl10">'. $row['cdesprod'] .'</td>
                            <td class="con_borde centro">'. $row['cabrevia'] .'</td>
                            <td class="con_borde drch pr10">'.$row['cantidad'] .'</td>
                            <td class="con_borde drch pr10">'.$row['almacen'] .'</td>
                            <td class="con_borde"></td>
                            <td class="con_borde centro"><input type="checkbox"></td>
                            <td class="con_borde centro">'. $t .'</td>
                            <td class="con_borde centro"><a href="'.$adjunto.'"><i class="fas fa-paperclip"></i></a></td>
                            <td class="con_borde"><input type="textbox" class="sin_borde pl10 no_outline transparente"></td>
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


        public function actualizarPedido($cod,$detalles){
            $datos = json_decode($detalles);
            $nreg = count($datos);
            $registros = 0;

            for ($i=0; $i < $nreg; $i++) { 
                try {
                    $query = $this->db->connect()->prepare("UPDATE lg_pedidodet SET nflgaprueba = 1,
                                                                                    nEstadoReg = 7,
                                                                                    comentaUsu = :coment 
                                                                WHERE lg_pedidodet.nidpedi=:iddet 
                                                                    AND lg_pedidodet.id_regmov=:idped");
                    $query->execute(["iddet" =>$datos[$i]->iddet,
                                    "idped"  =>$datos[$i]->pedido,
                                    "coment" =>$datos[$i]->comentarios]);
                    $rowcount = $query->rowcount();

                    $registros += $rowcount;

                } catch (PDOException $th) {
                    echo $th->getMessage();
                    return false;
                }
            }

            //if ($registros>0) {
                $this->actualizarCabecera($cod);            
            //}

            return $registros;
        }

        public function actualizarCabecera($cod){
            try {
                $query = $this->db->connect()->prepare("UPDATE lg_pedidocab SET nEstadoDoc = 7 WHERE id_regmov=:cod");
                $query->execute(["cod"=>$cod]);
            } catch (PDOException $th) {
                echo $th->getMessage();
                return false;
            }
        }
    }
?>