<?php
    class TcambioModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function insert($datos){
            try {
                $hoy = date("Y-m-d");
                $query = $this->db->connect()->prepare("INSERT INTO tb_tcambio SET ffechaproc=:fec,ncodmon=:mon,pcompra=:com,pventa=:ven");
                $query->execute(["fec"=>$hoy,
                                 "mon"=>$datos['mon'],
                                 "com"=>$datos['com'],
                                 "ven"=>$datos['ven']]);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getAllRecords(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT tb_tcambio.ffechaproc,
                                                        tb_tcambio.ncodmon,
                                                        tb_tcambio.pcompra,
                                                        tb_tcambio.pventa,
                                                        tb_tcambio.nflgactivo,
                                                        tb_moneda.dmoneda 
                                                    FROM
                                                        tb_tcambio
                                                        INNER JOIN tb_moneda ON tb_tcambio.ncodmon = tb_moneda.ncodmon");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $fecha = date("d/m/Y", strtotime($row['ffechaproc']));
                        $salida .= "<tr>
                                        <td class='centro con_borde'>".$fecha."</td>
                                        <td class='pl20 con_borde'>".$row['dmoneda']."</td>
                                        <td class='centro'>--</td>
                                        <td class='centro con_borde'>".$row['pcompra']."</td>
                                        <td class='centro con_borde'>".$row['pventa']."</td>
                                    </tr>";
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