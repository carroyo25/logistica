<?php
    class UsuariosModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getAllModules(){
            try {
                $salida = "";
                
                $query=$this->db->connect()->query("SELECT ncodmodu,cdesmodu,ccodopcion,cdesmenu FROM tb_sysmodulo");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        if ($row['ccodopcion'] == '00') {
                            $salida .= '<tr>
                                            <td class="cursor_pointer con_borde pl20" colspan="2">'.$row['cdesmenu'].'</td>
                                        </tr>';
                        }else{
                            $salida .= '<tr data-idx="'.$row['ncodmodu'].'" class="pointer">
                                        <td class="cursor_pointer con_borde drch pr20">'.$row['ncodmodu'].'</td>
                                        <td class="cursor_pointer con_borde pl20">'.$row['cdesmodu'].'</td>
                                    </tr>';
                        }
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getAllProys(){
            try {
                $salida = "";
                $query=$this->db->connect()->query("SELECT ncodpry,ccodpry,cdespry FROM tb_proyecto1 WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida .= '<tr data-idx="'.$row['ncodpry'].'" class="pointer">
                                        <td class="cursor_pointer con_borde drch pr20">'.$row['ccodpry'].'</td>
                                        <td class="cursor_pointer con_borde pl20">'.strtoupper($row['cdespry']).'</td>
                                    </tr>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                $e->getMessage();

                return false;
            }
        }

        public function getAllAlmc(){
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT ncodalm,ccodalm,cdesalm FROM tb_almacen WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr data-idx="'.$row['ncodalm'].'" class="pointer">
                                    <td class="cursor_pointer con_borde drch pr20">'.$row['ccodalm'].'</td>
                                    <td class="cursor_pointer con_borde pl20">'.strtoupper($row['cdesalm']).'</td>
                                </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function createCodeUser(){
            try {
                $query = $this->db->connect()->query("SELECT id_cuser FROM tb_sysusuario");
                $query->execute();
                
                $rowcount = $query->rowcount();

                return str_pad($rowcount + 1,4,0,STR_PAD_LEFT);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllNames() {
            try {
                $salida = "";
                $query = $this->db->connectrrhh()->query("SELECT dni, CONCAT(apellidos,', ',nombres) AS nombres, internal,ccargo,dcargo FROM tabla_aquarius 
                                                        WHERE estado = 'AC' ORDER BY apellidos ASC");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li>
                                    <a href="'.$row['internal'].'" data-ccargo="'.$row['ccargo'].'" data-dcargo="'.$row['dcargo'].'">'.$row['dni'].' '.$row['nombres'].'</a>
                                </li>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getParameters($tipo){
            try {
                $salida = "";

                $query = $this->db->connect()->prepare("SELECT ncodprm2,ccodprm2,cdesprm2 FROM tb_paramete2 WHERE ncodprm1 =:tipo");
                $query->execute(["tipo"=>$tipo]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ccodprm2'].'">'.$row['ccodprm2'].' '.strtoupper($row['cdesprm2']).'</a></li>';
                    }
                }
                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insert($datos) {
            $exist = $this->checkUser($datos['usr']);
            $cod = $this->createCodeUser();
            $pas = $this->encryptPass($datos['cla']);
            $id  = uniqid();
            $error = false;

            if (!$exist) {    
                try {

                    $query = $this->db->connect()->prepare("INSERT INTO tb_sysusuario SET id_cuser=:id,ccodper=:cre,nnivuser=:cni,nestado=:ces,
                                                                                        ccoduser=:cod,cnombres=:nom,cpasword=:cla,fvigdesde=:fde,
                                                                                        fvighasta=:has,cnameuser=:usr,ncodcarg=:cca,nflgactivo=:act");
                    $query->execute(["id"=> $id,
                                    "cre"=>$datos['cre'],
                                    "cni"=>$datos['cni'],
                                    "ces"=>$datos['ces'],
                                    "cod"=>$cod,
                                    "usr"=>$datos['usr'],
                                    "nom"=>$datos['nom'],
                                    "cla"=>$pas,
                                    "fde"=>$datos['des'],
                                    "has"=>$datos['has'],
                                    "act"=>1,
                                    "cca"=>$datos["cca"]]);
                    $rowcount = $query->rowcount();

                    if ($rowcount >= 1) {
                        $mensaje = "Usuario creado";
                        $error = false;
                    }else{
                        $mensaje = "Error al crear el usuario";
                        $error = true;
                    }

                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return false;
                }
            }else{
                $mensaje = "El usuario ya existe";
                $error = true;
            }

            $salida = array("codigo"=>$id,
                        "mensaje"=>$mensaje,
                        "error"=>$error);        
            return json_encode($salida);  
        }

        public function update($datos){
            try {
                $id  = $datos['id'];

                if ( $datos['old'] == $datos['cla']){
                    $pass = $datos['old'];
                }else{
                    $pass = $this->encryptPass($datos['cla']);
                }

                $query = $this->db->connect()->prepare("UPDATE tb_sysusuario 
                                                        SET ccodper=:cre,nnivuser=:cni,nestado=:ces,cnombres=:nom,cpasword=:cla,fvigdesde=:fde,
                                                            fvighasta=:has,cnameuser=:usr,nflgactivo=:act,ncodcarg=:cca
                                                        WHERE id_cuser=:id");

                $query->execute(["id"=> $datos['id'],
                                 "cre"=>$datos['cre'],
                                 "cni"=>$datos['cni'],
                                 "ces"=>$datos['ces'],
                                 "usr"=>$datos['usr'],
                                 "nom"=>$datos['nom'],
                                 "cla"=>$pass,
                                 "fde"=>$datos['des'],
                                 "has"=>$datos['has'],
                                 "act"=>1,
                                 "cca"=>$datos["cca"]]);

                $this->deleteModules($datos['id']);
                $this->deleteProyects($datos['id']);
                $this->deleteWarehouse($datos['id']);
                $this->deleteAutorization($datos['id']);

                $mensaje = "Usuario modificado";
                $error = false;

                /*if ( $rowcount >= 1 ) {
                    $mensaje = "Usuario modificado";
                    $error = false;
                }else{
                    $mensaje = "Error al modificar el usuario";
                    $error = true;
                }*/

                $salida = array("codigo"=>$id,
                        "mensaje"=>$mensaje,
                        "error"=>$error);        
                        return json_encode($salida);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delete($cod){
            try {
                $query=$this->db->connect()->prepare("UPDATE tb_sysusuario SET nflgactivo=:flag WHERE id_cuser=:cod LIMIT 1");
                $query->execute(["cod"=>$cod,"flag"=>0]);

                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    $salida = "Usuario eliminado";
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllUsers(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT
                                                        logistica.tb_sysusuario.id_cuser,
                                                        logistica.tb_sysusuario.ccoduser,
                                                        logistica.tb_sysusuario.cnameuser,
                                                        logistica.tb_sysusuario.cnombres,
                                                        logistica.tb_sysusuario.ccodper,
                                                        logistica.tb_sysusuario.nnivuser,
                                                        logistica.tb_sysusuario.nestado,
                                                        logistica.tb_sysusuario.fvigdesde,
                                                        logistica.tb_sysusuario.fvighasta,
                                                        logistica.tb_sysusuario.nflgactivo,
                                                        rrhh.tabla_aquarius.dni,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        niveles.cdesprm2 AS nivel,
                                                        estados.cdesprm2 AS estado 
                                                    FROM
                                                        logistica.tb_sysusuario
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.tb_sysusuario.ccodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_paramete2 AS niveles ON logistica.tb_sysusuario.nnivuser = niveles.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS estados ON logistica.tb_sysusuario.nestado = estados.ccodprm2 
                                                    WHERE
                                                        niveles.ncodprm1 = 9 
                                                        AND estados.ncodprm1 = 15
                                                        AND tb_sysusuario.nflgactivo = 1
                                                        ORDER BY logistica.tb_sysusuario.ccoduser");
                $query->execute([]);

                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {

                        $estado = $row['nestado'] == 1 ? 'activo' : 'inactivo';

                        $salida.=' <tr data-idx="'.$row['id_cuser'].'" class="cursor_pointer">
                                    <td class="con_borde centro">'.$row['ccoduser'].'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cnameuser']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['cnombres']).'</td>
                                    <td class="con_borde pl20">'.strtoupper($row['nivel']).'</td>
                                    <td class="con_borde pl20 '.$estado.'">'.strtoupper($estado).'</td>
                                    <td class="con_borde centro">--</td>
                                    <td class="con_borde centro">--</td>
                                    <td class="con_borde centro"><a href="'.$row['id_cuser'].'" data-action="s"><i class="fas fa-eye"></i></a></td>
                                    <td class="con_borde centro"><a href="'.$row['id_cuser'].'" data-action="u"><i class="far fa-edit"></i></a></td>
                                    <td class="con_borde centro"><a href="'.$row['id_cuser'].'" data-action="d"><i class="far fa-trash-alt"></i></a></td>
                                </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getUserById($cod){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        logistica.tb_sysusuario.id_cuser,
                                                        logistica.tb_sysusuario.ccoduser,
                                                        logistica.tb_sysusuario.cnameuser,
                                                        logistica.tb_sysusuario.cnombres,
                                                        logistica.tb_sysusuario.ccodper,
                                                        logistica.tb_sysusuario.nnivuser,
                                                        logistica.tb_sysusuario.nestado,
                                                        logistica.tb_sysusuario.fvigdesde,
                                                        logistica.tb_sysusuario.fvighasta,
                                                        logistica.tb_sysusuario.nflgactivo,
                                                        logistica.tb_sysusuario.cpasword,
                                                        rrhh.tabla_aquarius.dni,
                                                        rrhh.tabla_aquarius.apellidos,
                                                        rrhh.tabla_aquarius.nombres,
                                                        niveles.cdesprm2 AS nivel,
                                                        estados.cdesprm2 AS estado 
                                                    FROM
                                                        logistica.tb_sysusuario
                                                        INNER JOIN rrhh.tabla_aquarius ON logistica.tb_sysusuario.ccodper = rrhh.tabla_aquarius.internal
                                                        INNER JOIN logistica.tb_paramete2 AS niveles ON logistica.tb_sysusuario.nnivuser = niveles.ccodprm2
                                                        INNER JOIN logistica.tb_paramete2 AS estados ON logistica.tb_sysusuario.nestado = estados.ccodprm2 
                                                    WHERE
                                                        niveles.ncodprm1 = 9 
                                                        AND estados.ncodprm1 = 15
                                                        AND id_cuser=:cod");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                if ($rowcount>0){
                    while($row = $query->fetch()){
                        $item['id_cuser']       = $row['id_cuser'];
                        $item['ccoduser']       = $row['ccoduser'];
                        $item['cnameuser']      = $row['cnameuser'];
                        $item['cnombres']       = $row['cnombres'];
                        $item['ccodper']        = $row['ccodper'];
                        $item['nnivuser']       = $row['nnivuser'];
                        $item['nestado']        = $row['nestado'];
                        $item['fvigdesde']      = $row['fvigdesde'] == "0000-00-00" ? null : date('Y-m-d',strtotime($row['fvigdesde']));
                        $item['fvighasta']      = $row['fvighasta'] == "0000-00-00" ? null : date('Y-m-d',strtotime($row['fvighasta']));
                        $item['nivel']          = $row['nivel'];
                        $item['estado']         = $row['estado'];
                        $item['responsable']    = $row['dni'].' '.$row['apellidos'].' '.$row['nombres'];
                        $item['cpasword']         = $row['cpasword'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function checkUser($usr){
            try {
                $query = $this->db->connect()->prepare("SELECT id_cuser FROM tb_sysusuario WHERE cnameuser=:usr");
                $query->execute(["usr"=>$usr]);
                $rowcount = $query->rowcount();
                
                if ($rowcount > 0) {
                    return true;
                }else{
                    return false;
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function encryptPass($password){
            $sSalt = '20adeb83e85f03cfc84d0fb7e5f4d290';
            $sSalt = substr(hash('sha256', $sSalt, true), 0, 32);
            $method = 'aes-256-cbc';
        
            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        
            $encrypted = base64_encode(openssl_encrypt($password, $method, $sSalt, OPENSSL_RAW_DATA, $iv));
            return $encrypted;
        }

        public function decryptPass($password){
            $sSalt = '20adeb83e85f03cfc84d0fb7e5f4d290';
            $sSalt = substr(hash('sha256', $sSalt, true), 0, 32);
            $method = 'aes-256-cbc';
        
            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        
            $decrypted = openssl_decrypt(base64_decode($password), $method, $sSalt, OPENSSL_RAW_DATA, $iv);
            return $decrypted;
        }

        public function insertModules($datos){
            try {
                $data = json_decode($datos);

                for ($i=0; $i < count($data); $i++) { 
                   $flag = 1;

                   $query = $this->db->connect()->prepare("INSERT INTO tb_sysacceso (id_cuser,ncodmodu,nflgAdd,nflgMod,nflgDel,nflgVer,nflgPrn,nflgPro,nflgAll,cestado,nflgactivo) 
                                                            VALUES (:user,:modu,:agre,:modi,:elim,:visi,:impr,:pros,:todo,:cest,:flag)");
                   $query->execute(["modu"=>$data[$i]->codm,
                                    "user"=>$data[$i]->user,
                                    "agre"=>$data[$i]->agre,
                                    "modi"=>$data[$i]->modi,
                                    "elim"=>$data[$i]->elim,
                                    "visi"=>$data[$i]->visi,
                                    "impr"=>$data[$i]->impr,
                                    "pros"=>$data[$i]->proc,
                                    "todo"=>$data[$i]->todo,
                                    "cest"=>$data[$i]->esta,
                                    "flag"=>$flag]);
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

    
        public function getPassById($cod) {
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT cpasword FROM tb_sysusuario WHERE id_cuser=:cod LIMIT 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();

                if ($rowcount>=1){
                    while ($row = $query->fetch()) {
                        $salida = $this->decryptPass($row['cpasword']);
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getModulesById($cod){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                tb_sysacceso.id_cuser,
                                                tb_sysacceso.nflgAdd,
                                                tb_sysacceso.nflgMod,
                                                tb_sysacceso.nflgDel,
                                                tb_sysacceso.nflgVer,
                                                tb_sysacceso.nflgPrn,
                                                tb_sysacceso.nflgPro,
                                                tb_sysacceso.nflgAll,
                                                tb_sysacceso.cestado,
                                                tb_sysmodulo.cdesmodu,
                                                tb_sysacceso.ncodmodu 
                                            FROM
                                                tb_sysacceso
                                                INNER JOIN tb_sysmodulo ON tb_sysacceso.ncodmodu = tb_sysmodulo.ncodmodu 
                                            WHERE
                                                tb_sysacceso.id_cuser = :cod AND nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $cont = 1;

                if ($rowcount > 0){
                    while ($row = $query->fetch()) {

                        $add = $row['nflgAdd'] == 1 ? "checked":"";
                        $mod = $row['nflgMod'] == 1 ? "checked":"";
                        $del = $row['nflgDel'] == 1 ? "checked":"";
                        $prn = $row['nflgPrn'] == 1 ? "checked":"";
                        $pro = $row['nflgPro'] == 1 ? "checked":"";
                        $ver = $row['nflgVer'] == 1 ? "checked":"";
                        $est = $row['cestado'] == 1 ? "checked":"";
                        $all = $row['nflgAll'] == 1 ? "checked":"";

                        $salida .='<tr>
                                        <td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>
                                        <td class="con_borde pl20">'.str_pad($cont,2,"0",STR_PAD_LEFT).'</td>
                                        <td class="con_borde pl20" data-codigo="'.$row['ncodmodu'].'">'.$row['cdesmodu'].'</td>
                                        <td class="centro con_borde"><input type="checkbox"'.$add.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$mod.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$del.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$prn.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$pro.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$ver.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$est.'></td>
                                        <td class="centro con_borde"><input type="checkbox"'.$all.' class="total"></td>

                                    </tr>';
                        $cont++;
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        ///aca estan los cambios
        public function getWareHousesById($cod){
            try {
                $salida="";
                $query=$this->db->connect()->prepare("SELECT
                                                        tb_almacen.cdesalm,
                                                        tb_almacen.ccodalm,
                                                        tb_almausu.ncodalm 
                                                    FROM
                                                        tb_almausu
                                                        INNER JOIN tb_almacen ON tb_almausu.ncodalm = tb_almacen.ncodalm 
                                                    WHERE
                                                        tb_almausu.id_cuser =:cod AND tb_almausu.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $cont = 1;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr>
                                    <td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>
                                    <td class="con_borde centro">'.str_pad($cont,2,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde pl20" data-idx="'.$row['ncodalm'].'">'.$row['ccodalm'].'</td>
                                    <td class="con_borde pl20">'.$row['cdesalm'].'</td>
                                 </tr>';
                        
                        $cont++;
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAuthorizationById($cod){
            try {
                $salida="";
                $query=$this->db->connect()->prepare("SELECT
                                                    CONCAT( rrhh.tabla_aquarius.nombres, ' ', rrhh.tabla_aquarius.apellidos ) AS nombres,
                                                    CONCAT( rrhh.tabla_aquarius.corporativo, '@sepcon.net' ) as corporativo,
                                                    logistica.tb_sysruta.ccodper,
                                                    logistica.tb_paramete2.cdesprm2,
                                                    logistica.tb_sysruta.ncodmodu 
                                                FROM
                                                    logistica.tb_sysruta
                                                    INNER JOIN rrhh.tabla_aquarius ON logistica.tb_sysruta.ccodper = rrhh.tabla_aquarius.internal
                                                    INNER JOIN logistica.tb_paramete2 ON logistica.tb_sysruta.ncodmodu = logistica.tb_paramete2.ncodprm2 
                                                WHERE
                                                    logistica.tb_sysruta.id_cuser =:cod 
                                                    AND logistica.tb_sysruta.nflgactivo = 1 
                                                    AND logistica.tb_paramete2.ncodprm1 = 19");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $cont = 1;

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr>
                                    <td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a></td>
                                    <td class="con_borde centro">'.str_pad($cont,2,"0",STR_PAD_LEFT).'</td>
                                    <td class="con_borde centro">'.$row['ncodmodu'].'</td>
                                    <td class="con_borde pl20" data-cmo="'.$row['ncodmodu'].'">'.$row['cdesprm2'].'</td>
                                    <td class="con_borde pl20" data-idx="'.$row['ccodper'].'">'.$row['nombres'].'</td>
                                    <td class="con_borde pl20">'.$row['corporativo'].'</td>
                                </tr>';
                        $cont++;
                    }
                }
                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getProysById($cod){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                                                tb_proyusu.ncodproy,
                                                tb_proyusu.id_cuser,
                                                tb_proyusu.nflgactivo,
                                                tb_proyusu.fregsys,
                                                tb_proyecto1.ccodpry,
                                                tb_proyecto1.cdespry 
                                            FROM
                                                tb_proyusu
                                                INNER JOIN tb_proyecto1 ON tb_proyusu.ncodproy = tb_proyecto1.ncodpry 
                                            WHERE
                                                tb_proyusu.id_cuser = :cod 
                                                AND tb_proyecto1.nflgactivo = 1 
                                                AND tb_proyusu.nflgactivo = 1");
                $query->execute(["cod"=>$cod]);
                $rowcount = $query->rowcount();
                $cont = 1;

                if ($rowcount > 0){
                    while ($row = $query->fetch()) {
                        $salida .='<tr>
                        <td class="centro con_borde"><a href="#"><i class="fas fa-eraser"></i></a> </td>
                        <td class="con_borde centro">'.str_pad($cont,2,"0",STR_PAD_LEFT).'</td>
                        <td class="con_borde pl20" data-idx="'.$row['ncodproy'].'">'.$row['ccodpry'].'</td>
                        <td class="con_borde pl20">'.$row['cdespry'].'</td>
                    </tr>';

                    $cont++;
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function deleteModules($cod){
            try {
                $query= $this->db->connect()->prepare("UPDATE tb_sysacceso SET nflgactivo=:flag WHERE id_cuser=:cod");
                $query->execute(["cod"=>$cod,"flag"=>0]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function deleteProyects($cod){
            try {
                $query= $this->db->connect()->prepare("UPDATE tb_proyusu SET nflgactivo=:flag WHERE id_cuser=:cod");
                $query->execute(["cod"=>$cod,"flag"=>0]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function deleteWarehouse($cod){
            try {
                $query= $this->db->connect()->prepare("UPDATE tb_almausu SET nflgactivo=:flag WHERE id_cuser=:cod");
                $query->execute(["cod"=>$cod,"flag"=>0]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function deleteAutorization($cod){
            try {
                $query= $this->db->connect()->prepare("UPDATE tb_sysruta SET nflgactivo=:flag WHERE id_cuser=:cod");
                $query->execute(["cod"=>$cod,"flag"=>0]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllMails(){
            try {
                $salida = "";
                $query = $this->db->connectrrhh()->query("SELECT corporativo, CONCAT(nombres,' ',apellidos) AS nombres,internal FROM tabla_aquarius 
                                                        WHERE corporativo <> 'NULL' ORDER BY apellidos ASC");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida .= '<tr data-idx="'.$row['internal'].'" class="pointertr">
                                        <td class="con_borde pl20 cursor_pointer">'.$row['nombres'].'</td>
                                        <td class="con_borde pl20 cursor_pointer">'.$row['corporativo'].'@sepcon.net'.'</td>
                                    </tr>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getModAprob(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT
                                                            tb_paramete2.ncodprm1,
                                                            tb_paramete2.ncodprm2,
                                                            tb_paramete2.cdesprm2 
                                                        FROM
                                                            tb_paramete2 
                                                        WHERE
                                                            tb_paramete2.ncodprm1 = 19");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida .= '<tr data-idx="'.$row['ncodprm2'].'" class="pointertr">
                                        <td class="con_borde pl20 cursor_pointer">'.$row['ncodprm2'].'</td>
                                        <td class="con_borde pl20 cursor_pointer">'.$row['cdesprm2'].'</td>
                                    </tr>';
                    }
                }
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertProyects($datos){
            try {
                $data = json_decode($datos);

                for ($i=0; $i < count($data); $i++) { 
                   $flag = 1;

                   $query = $this->db->connect()->prepare("INSERT INTO tb_proyusu (ncodproy,id_cuser,nflgactivo) 
                                                            VALUES (:codp,:user,:flag)");
                   $query->execute(["codp"=>$data[$i]->codp,
                                    "user"=>$data[$i]->user,
                                    "flag"=>$flag]);
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertWarehouses($datos){
            try {
                $data = json_decode($datos);

                for ($i=0; $i < count($data); $i++) {
                    $flag = 1;

                    $query = $this->db->connect()->prepare("INSERT INTO tb_almausu SET ncodalm=:coda,id_cuser=:user,nflgactivo=:flag");
                    $query->execute(["coda"=>$data[$i]->coda,
                                     "user"=>$data[$i]->user,
                                     "flag"=>$flag]);
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertAuthorization($datos){
            try {
                $data = json_decode($datos);

                for ($i=0; $i < count($data); $i++) {
                    $flag = 1;

                    $query = $this->db->connect()->prepare("INSERT INTO tb_sysruta SET id_cuser=:user,ncodmodu=:codm,ccodper=:codp,nflgactivo=:flag");

                    $query->execute(["codp"=>$data[$i]->codp,
                                      "user"=>$data[$i]->user,
                                      "codm"=>$data[$i]->codm,
                                      "flag"=>$flag]);
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>