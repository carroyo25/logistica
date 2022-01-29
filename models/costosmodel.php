<?php
    class CostosModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function checkExist($datos) {
            try {
                $query= $this->db->connect()->prepare("SELECT ncodcos FROM tb_ccostos WHERE ccodcos=:cod AND cdescos=:dsc");
                $query->execute(["cod"=>$datos['cod'],"dsc"=>$datos['des']]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    return true;
                }else {
                    return false;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insert($datos){
            try {
                $query= $this->db->connect()->prepare("INSERT INTO tb_ccostos SET ccodcos=:cod,cdescos=:dsc,nflgactivo=:est,ncodpry=:proy,nflgVeryAlm=:alm");
                $query->execute(["cod"=>$datos['cod'],
                                 "dsc"=>$datos['des'],
                                 "est"=>$datos['est'],
                                 "proy"=>$datos['proy'],
                                 "alm"=>$datos['alm']]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
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
                $query= $this->db->connect()->prepare("UPDATE tb_ccostos SET ccodcos=:cod,cdescos=:dsc,nflgactivo=:est,nflgVeryAlm=:alm WHERE ncodcos=:idx LIMIT 1");
                $query->execute(["idx"=>$datos['idx'],
                                 "cod"=>$datos['cod'],
                                 "dsc"=>$datos['des'],
                                 "est"=>$datos['est'],
                                 "alm"=>$datos['alm']]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    return true;
                }else {
                    return false;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delete($id){
            try {
                $query= $this->db->connect()->prepare("UPDATE tb_ccostos SET nflgactivo=:est WHERE ncodcos=:idx LIMIT 1");
                $query->execute(["idx"=>$id,"est"=>0]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    return true;
                }else {
                    return false;
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getallItem(){
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT ncodcos,ccodcos,cdescos FROM tb_ccostos WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr data-idx="'.$row['ncodcos'].'">
                                    <td>'.$row['ccodcos'].'</td>
                                    <td>'.strtoupper($row['cdescos']).'</td>
                                    <td class="centro"><a href="'.$row['ncodcos'].'"><i class="far fa-edit"></i></a></td>
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
                                                tb_ccostos.ccodcos, 
                                                tb_ccostos.cdescos, 
                                                tb_ccostos.nflgactivo, 
                                                tb_ccostos.ncodcos, 
                                                tb_ccostos.ncodpry, 
                                                tb_ccostos.nflgVeryAlm, 
                                                tb_proyecto1.cdespry,
                                                tb_proyecto1.ccodpry
                                            FROM
                                                tb_ccostos
                                                INNER JOIN
                                                tb_proyecto1
                                                ON 
                                                    tb_ccostos.ncodpry = tb_proyecto1.ncodpry
                                            WHERE
                                                tb_ccostos.ncodcos = :idx");
                $query->execute(["idx"=>$idx]);

                while ($row = $query->fetch()) {
                        $item['ncodcos']     = $row['ncodcos'];
                        $item['ccodcos']     = $row['ccodcos'];
                        $item['cdescos']     = $row['cdescos'];
                        $item['nflgactivo']  = $row['nflgactivo'];
                        $item['ncodpry']     =  $row['ncodpry'];
                        $item['cdespry']     = $row['ccodpry']." ".$row['cdespry'];
                        $item['nflgVeryAlm'] = $row['nflgVeryAlm'];
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getTotals($est) {
            try {
                $query = $this->db->connect()->prepare("SELECT ncodcos FROM tb_ccostos WHERE nflgactivo =:est ");
                $query->execute(["est"=>$est]);
                $rowcount = $query->rowcount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function listarProyectos(){
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
    }
?>