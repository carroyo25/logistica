<?php
    class AreasModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getTotals($est) {
            try {
                $query = $this->db->connect()->prepare("SELECT ncodarea FROM tb_area WHERE nflgactivo =:est ");
                $query->execute(["est"=>$est]);
                $rowcount = $query->rowcount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function generateCode(){
            try {
                $query= $this->db->connect()->query("SELECT ccodarea FROM tb_area");
                $query->execute();
                $rowcount = $query->rowcount();

                return $rowcount + 1;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insert($datos){
            try {
                $query = $this->db->connect()->prepare("INSERT tb_area SET ccodarea=:cod,cdesarea=:dea,nflgactivo=:est");
                $query->execute(["cod"=>$datos['cod'],
                                 "dea"=>$datos['dea'],
                                 "est"=>$datos['est']]);
                $rowcount = $query->rowcount();

                if ( $rowcount > 0 ) {
                    return true;
                }else {
                    return false;
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function update($datos){
            try {
                $query = $this->db->connect()->prepare("UPDATE tb_area SET ccodarea=:cod,cdesarea=:dea,nflgactivo=:est WHERE ncodarea=:idx");
                $query->execute(["cod"=>$datos['cod'],
                                 "dea"=>$datos['dea'],
                                 "est"=>$datos['est'],
                                 "idx"=>$datos['idx']]);
                $rowcount = $query->rowcount();

                if ( $rowcount > 0 ) {
                    return true;
                }else {
                    return false;
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delete($idx){
            try {
                $query=$this->db->connect()->prepare("UPDATE tb_area SET nflgactivo=:est WHERE ncodarea=:idx  LIMIT 1");
                $query->execute(["idx"=>$idx, "est"=>0]);
                                
                $rowcount = $query->rowcount();

                if ( $rowcount > 0 ) {
                    return true;
                }else {
                    return false;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllItems(){
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT ncodarea,ccodarea,cdesarea FROM tb_area WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr data-idx="'.$row['ncodarea'].'">
                                    <td>'.$row['ccodarea'].'</td>
                                    <td>'.strtoupper($row['cdesarea']).'</td>
                                    <td class="centro"><a href="'.$row['ncodarea'].'"><i class="far fa-edit"></i></a></td>
                                </tr>';
                    }
                }
                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getItemById($idx){
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                                        tb_area.ncodarea,
                                                        tb_area.ccodarea,
                                                        tb_area.cdesarea,
                                                        tb_area.nflgactivo 
                                                    FROM
                                                        tb_area 
                                                    WHERE
                                                        tb_area.ncodarea =:idx");
                $query->execute(["idx"=>$idx]);

                while ($row = $query->fetch()) {
                        $item['ncodarea'] = $row['ncodarea'];
                        $item['ccodarea'] = $row['ccodarea'];
                        $item['cdesarea'] = $row['cdesarea'];
                        $item['nflgactivo'] = $row['nflgactivo'];
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>