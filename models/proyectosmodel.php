<?php
    class ProyectosModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getTotals($est) {
            try {
                $query = $this->db->connect()->prepare("SELECT ncodpry FROM tb_proyecto1 WHERE nflgactivo =:est ");
                $query->execute(["est"=>$est]);
                $rowcount = $query->rowcount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function checkExist($cod,$des){
            try {
                $salida = false;
                $query = $this->db->connect()->prepare("SELECT ccodpry FROm tb_proyecto1 WHERE ccodpry =:cod OR cdespry =:dep");
                $query->execute(["cod"=>$cod,"dep"=>$des]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    $salida = true;
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insert($datos){
            try {
                $salida = false;
                $query = $this->db->connect()->prepare("INSERT INTO tb_proyecto1 SET ccodpry=:cod,cdespry=:dep,cubica=:ubi,cresponsa=:res,nflgactivo=:est");
                $query->execute(["cod"=>$datos['cod'],
                                 "dep"=>$datos['dep'],
                                 "ubi"=>$datos['ubi'],
                                 "res"=>$datos['res'],
                                 "est"=>$datos['est']]);
                $rowcount = $query->rowcount();

                if ( $rowcount > 0 ) {
                    $salida = true;
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function update($datos){
            try {
                $salida = false;
                $query = $this->db->connect()->prepare("INSERT INTO tb_proyecto1 SET ccodpry=:cod,cdespry=:dep,cubica=:ubi,cresponsa=:res,nflgactivo=:est WHERE ncodpry=:idx LIMIT 1");
                $query->execute(["cod"=>$datos['cod'],
                                 "dep"=>$datos['dep'],
                                 "ubi"=>$datos['ubi'],
                                 "res"=>$datos['res'],
                                 "est"=>$datos['est'],
                                 "idx"=>$datos['idx']]);
                $rowcount = $query->rowcount();

                if ( $rowcount > 0 ) {
                    $salida = true;
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delete($idx){
            try {
                $query=$this->db->connect()->prepare("UPDATE tb_proyecto1 SET nflgactivo=:est WHERE ncodpry=:idx  LIMIT 1");
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
                $salida = false;
                $query = $this->db->connect()->query("SELECT  ccodpry,cdespry,ncodpry FROM tb_proyecto1 WHERE nflgactivo=1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ( $rowcount > 0 ) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr data-idx="'.$row['ncodpry'].'">
                                    <td>'.$row['ccodpry'].'</td>
                                    <td>'.strtoupper($row['cdespry']).'</td>
                                    <td class="centro"><a href="'.$row['ncodpry'].'"><i class="far fa-edit"></i></a></td>
                                </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getItemById($idx)
        {
            try {
                $item = array();

                $query = $this->db->connect()->prepare("SELECT ncodpry,ccodpry,cdespry,nflgactivo,cubica,cresponsa
                                                        FROM
                                                            tb_proyecto1
                                                        WHERE
                                                            ncodpry =:idx");
                $query->execute(["idx"=>$idx]);

                while ($row = $query->fetch()) {
                        $item['ncodpry']    = $row['ncodpry'];
                        $item['ccodpry']    = $row['ccodpry'];
                        $item['cdespry']    = $row['cdespry'];
                        $item['nflgactivo'] = $row['nflgactivo'];
                        $item['cubica']     = $row['cubica'];
                        $item['cresponsa']  = $row['cresponsa'];
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>