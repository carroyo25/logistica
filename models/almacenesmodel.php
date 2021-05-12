<?php
    class AlmacenesModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getTotals($est) {
            try {
                $query = $this->db->connect()->prepare("SELECT ncodalm FROM tb_almacen WHERE nflgactivo =:est ");
                $query->execute(["est"=>$est]);
                $rowcount = $query->rowcount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
        
        public function getUbigeo($nivel){
            try {
                $salida = "";
                $query= $this->db->connect()->query("SELECT
                    tb_ubigeo.ccubigeo,
                    tb_ubigeo.cdubigeo
                FROM
                    tb_ubigeo
                WHERE
                    tb_ubigeo.nnivel = '$nivel'");
                $query->execute();

                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ccubigeo'].'">'.$row['cdubigeo'].'</a></li>';
                    }
                }

                return $salida;
                
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function generateCode(){
            try {

                $query= $this->db->connect()->query("SELECT ccodalm FROM tb_almacen");
                $query->execute();
                $rowcount = $query->rowcount();

                return $rowcount + 1;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insert($datos)
        {
            try {
                $query=$this->db->connect()->prepare("INSERT INTO tb_almacen SET ncubigeo=:ubi,ccodalm=:coa,cdesalm=:dea,
                                                                                ctipovia=:vti,cdesvia=:vin,cnrovia=:num,czonavia=:zon,
                                                                                nflgactivo=:est,cdesdpto=:dep,cdesprov=:pro,cdesdist=:dis");
                $query->execute(["ubi"=>$datos['ubi'],
                                "coa"=>$datos['coa'],
                                "dea"=>$datos['dea'],
                                "vti"=>$datos['vti'],
                                "vin"=>$datos['vin'],
                                "num"=>$datos['num'],
                                "zon"=>$datos['zon'],
                                "est"=>$datos['est'],
                                "dep"=>$datos['dep'],
                                "pro"=>$datos['pro'],
                                "dis"=>$datos['dis']]);
                                
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
                $query=$this->db->connect()->prepare("INSERT INTO tb_almacen SET ncubigeo=:ubi,ccodalm=:coa,cdesalm=:dea,
                                                                                ctipovia=:vti,cdesvia=:vin,cnrovia=:num,czonavia=:zon,
                                                                                nflgactivo=:est,cdesdpto=:dep,cdesprov=:pro,cdesdist=:dis
                                                                WHERE ncodalm=:idx
                                                                LIMIT 1");
                $query->execute(["ubi"=>$datos['ubi'],
                                "coa"=>$datos['coa'],
                                "dea"=>$datos['dea'],
                                "vti"=>$datos['vti'],
                                "vin"=>$datos['vin'],
                                "num"=>$datos['num'],
                                "zon"=>$datos['zon'],
                                "est"=>$datos['est'],
                                "dep"=>$datos['dep'],
                                "pro"=>$datos['pro'],
                                "dis"=>$datos['dis'],
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
                $query=$this->db->connect()->prepare("UPDATE tb_almacen SET nflgactivo=:est WHERE ncodalm=:idx  LIMIT 1");
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

        public function getallItem() {
            try {
                $salida="";
                $query = $this->db->connect()->query("SELECT ncodalm,ccodalm,cdesalm FROM tb_almacen WHERE nflgactivo = 1");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr data-idx="'.$row['ncodalm'].'">
                                    <td>'.$row['ccodalm'].'</td>
                                    <td>'.strtoupper($row['cdesalm']).'</td>
                                    <td class="centro"><a href="'.$row['ncodalm'].'"><i class="far fa-edit"></i></a></td>
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

                $query = $this->db->connect()->prepare("SELECT  tb_almacen.ccodalm,
                                                                tb_almacen.cdesalm,
                                                                tb_almacen.ncubigeo,
                                                                tb_almacen.cdesdpto,
                                                                tb_almacen.cdesprov,
                                                                tb_almacen.cdesdist,
                                                                tb_almacen.ctipovia,
                                                                tb_almacen.cdesvia,
                                                                tb_almacen.cnrovia,
                                                                tb_almacen.czonavia,
                                                                tb_almacen.nflgactivo,
                                                                tb_almacen.ncodalm
                                                        FROM
                                                            tb_almacen 
                                                        WHERE
                                                            tb_almacen.ncodalm =:idx");
                $query->execute(["idx"=>$idx]);

                while ($row = $query->fetch()) {
                        $item['ncodalm'] = $row['ncodalm'];
                        $item['ccodalm'] = $row['ccodalm'];
                        $item['cdesalm'] = $row['cdesalm'];
                        $item['ncubigeo'] = $row['ncubigeo'];
                        $item['cdesdpto'] = $row['cdesdpto'];
                        $item['cdesprov'] = $row['cdesprov'];
                        $item['cdesdist'] = $row['cdesdist'];
                        $item['ctipovia'] = $row['ctipovia'];
                        $item['cdesvia'] = $row['cdesvia'];
                        $item['cnrovia'] = $row['cnrovia'];
                        $item['czonavia'] = $row['czonavia'];
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