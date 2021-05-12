<?php
     class GrupoModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getAllGroups() {
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT
                        tb_clasprod.ncodcata,
                        tb_clasprod.ccodgru,
                        tb_clasprod.cdesclas
                        FROM
                        tb_clasprod
                        WHERE 
                        NNIVCLAS = 1
                        ORDER BY
                        tb_clasprod.ccodgru ASC");
                $query->execute();

                while($row = $query->fetch()){
                    $salida .= '<tr>
                                    <td class="pl20">'.$row['ccodgru'].'</td>
                                    <td class="pl20">'.strtoupper($row['cdesclas']).'</td>
                                    <td class="centro"><a href="'.$row['ncodcata'].'" data-accion="modify"><i class="far fa-edit"></i></a></td>
                                    <td class="centro"><a href="'.$row['ncodcata'].'" data-accion="delete"><i class="far fa-trash-alt"></i></a></td>
                                </tr>';
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getGroupByLastCode(){
            try {
                $query = $this->db->connect()->query('SELECT COUNT(tb_clasprod.ccodgru) AS codigo FROM tb_clasprod WHERE tb_clasprod.nnivclas = 1');
                $query->execute();

                while($row = $query->fetch()){
                    $total = $row['codigo'];
                }

                return $total;
            }catch(PDOException $e){
                echo $e->getMessage();
                return false;
            }
        }

        public function insertGroup($datos) {
            try {
                $query = $this->db->connect()->prepare('INSERT INTO tb_clasprod (CCODGRU,CDESCLAS,NNIVCLAS) VALUES (:cod,:descrip,:nivel)');
                $query->execute(['cod' => $datos['cod'],
                                'descrip' => $datos['des'],
                                'nivel'=>$datos['niv']]);
                return true;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function updatetGroup($datos) {
            try {
                $query = $this->db->connect()->prepare('UPDATE tb_clasprod (CDESCLAS) VALUES (:descrip) WHERE INDICE =:indice');
                $query->execute(['indice' => $datos['ind'],
                                'descrip' => $datos['des']]);
                return true;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function checkExist($datos) {
            try {
                $query = $this->db->connect()->prepare('SELECT tb_clasprod.cdesclas FROM tb_clasprod WHERE CDESCLAS = :item');
                $query->execute(['item' => $datos['item']]);
                $rowcount = $query->rowCount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getTotalGroups() {
            try {
                $query = $this->db->connect()->prepare('SELECT tb_clasprod.cdesclas FROM tb_clasprod WHERE nnivclas = 1');
                $query->execute();
                $rowcount = $query->rowCount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAccess($opt,$user){
            try {
                $access = "";
                $query = $this->db->connect()->prepare("SELECT nflgAdd,nflgMod,nflgDel,nflgVer,nflgPrn,nflgPro,nflgAll 
                                                        FROM tb_sysacceso 
                                                        WHERE ncodmodu=:opt AND id_cuser=:user");
                $query->execute(["opt"=>$opt,"user"=>$user]);

                $rowcount = $query->rowcount();

                if ($rowcount > 1){
                    while ($row = $query->fetch()) {
                        $access =  $row['nflgAdd'].$row['nflgMod'].$row['nflgDel'].$row['nflgVer'].$row['nflgPrn'].$row['nflgPro'].$row['nflgAll'];
                    }
                }else {
                    $access = "np";
                }
                    

                return $access;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>