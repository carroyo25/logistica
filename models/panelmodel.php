<?php
    class Usuario{
        public $cnombres;
        public $cnameuser;
        public $iniciales;
        public $id_user;
    }
    class PanelModel extends Model{
        public function __construct()
        {   
            parent::__construct();
        }

        public function getUser(){
            try {
                $item = new Usuario();

                $user = $_SESSION['iduser'];

                $query = $this->db->connect()->prepare("SELECT id_cuser,cnombres,cnameuser FROM tb_sysusuario WHERE id_cuser =:user");
                $query->execute(['user'=>$user]);

                while($row = $query->fetch()){
                   $_SESSION['cnombres']  = $row['cnombres'];
                   $_SESSION['cnameuser'] = $row['cnameuser'];
                   $_SESSION['iniciales'] = substr( $row['cnombres'],0,1);
                   $_SESSION['id_user'] = $row['id_cuser'];

                }

               return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getVisibleActions($user){
            try {
                $permited = array();
                $query = $this->db->connect()->prepare("SELECT
                                                tb_sysacceso.ncodusu,
                                                tb_sysacceso.ncodmodu,
                                                tb_sysacceso.id_cuser 
                                            FROM
                                                tb_sysacceso 
                                            WHERE
                                                tb_sysacceso.id_cuser =:user 
                                                AND tb_sysacceso.nflgactivo = 1");
                $query->execute(["user"=>$user]);
                $rowcount=$query->rowcount();

                if ($rowcount > 0 ) {
                    while ($row = $query->fetch()) {
                        array_push($permited,$row['ncodmodu']);
                    }
                }

                return $permited;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>