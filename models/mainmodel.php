<?php
    require_once 'models/usuario.php';

    class MainModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getByUserPass($user, $clave){
            try {
                $item = array();
                $cla = $this->encryptPass($clave);

                $query = $this->db->connect()->prepare("SELECT id_cuser,nflgactivo,cnameuser,cnombres,ccodper,ncodcarg 
                                                            FROM tb_sysusuario 
                                                            WHERE cnameuser=:user AND cpasword=:clave 
                                                            LIMIT 1");
                $query->execute(["user"=>$user,"clave"=>$cla]);
                
                $rowcount=$query->rowcount();

                if($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $item['codigo'] = $row['id_cuser'];
                        $item['estado'] = $row['nflgactivo'];

                        if ($item['estado'] == 1 ){
                            $_SESSION['iduser']     = $item['codigo'];
                            $_SESSION['user']       = $row['cnameuser'];
                            $_SESSION['nombres']    = $row['cnombres'];
                            $_SESSION['codper']     = $row['ccodper'];
                            $_SESSION['ncargo']     = $row['ncodcarg'];
                            $_SESSION['password']   = "aK8izG1WEQwwB1X"; //codigo de usuario para los correos
                        }
                        else{
                            $item['mensaje'] = "Usuario no habilitado";
                        }
                    }
                }else{
                    $item['estado'] = -1;
                    $item['mensaje'] = "Usuario o clave incorrecta";
                }

                return $item;

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
    }
?>