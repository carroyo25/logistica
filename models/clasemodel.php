<?php
     class ClaseModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getAllGroups(){
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT
                            tb_clasprod.ccodgru,
                            tb_clasprod.cdesclas
                            FROM
                                tb_clasprod
                            WHERE 
                                tb_clasprod.nnivclas = 1
                            ORDER BY
                                tb_clasprod.cdesclas ASC");
                $query->execute();
                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ccodgru'].'">'.strtoupper($row['cdesclas']).'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //Generar nuevo codigo
        public function genClassCode($datos) {
            try {
                $query = $this->db->connect()->prepare("SELECT tb_clasprod.ccodcla FROM tb_clasprod WHERE ccodgru = :cod AND tb_clasprod.nnivclas = 2");
                $query->execute(['cod' => $datos['cod']]);
                $rowcount = $query->rowCount();

                return $rowcount;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //Verificar si existe el codigo
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

        //insertar nueva clase
        public function insert($datos) {
            try {
                $query = $this->db->connect()->prepare('INSERT INTO tb_clasprod (CCODGRU,CCODCLA,CDESCLAS,NNIVCLAS) VALUES (:gcod,:ccod,:descrip,:nivel)');
                $query->execute(['gcod' => $datos['gcod'],
                                'ccod' => $datos['ccod'],
                                'descrip' => $datos['des'],
                                'nivel'=>$datos['niv']]);
                return true;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function listAllClasses(){
            $salida = "";
            $grupos = [];
            $clases = [];
            $familias = [];

            try {
                $query = $this->db->connect()->query("SELECT
                    tb_clasprod.ccodgru,
                    tb_clasprod.cdesclas
                    FROM
                        tb_clasprod
                    WHERE 
                        tb_clasprod.nnivclas = 1
                    ORDER BY
                        tb_clasprod.cdesclas ASC");
                $query->execute();
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $item = array("cod"=>$row['ccodgru'],"des"=>$row['cdesclas']);
                        array_push( $grupos,$item);
                    }
                }

                $query = $this->db->connect()->query("SELECT
                                    tb_clasprod.ncodcata,
                                    tb_clasprod.ccodcla,
                                    tb_clasprod.ccodgru,
                                    tb_clasprod.cdesclas
                                    FROM
                                    tb_clasprod
                                    WHERE
                                    tb_clasprod.nnivclas = 2
                                    ORDER BY
                                    tb_clasprod.cdesclas ASC");
                $query->execute();
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $item = array("cod"=>$row['ccodcla'],"des"=>$row['cdesclas'],"grp"=>$row['ccodgru'],"idx"=>$row['ncodcata']);

                        array_push( $clases,$item );
                    }
                }

                foreach ($grupos as $grupo) {
                    $salida .= '<tr class="grupos"><td>'.strtoupper($grupo['des']).'</td><td></td><td></td><td></td></tr>';
                    foreach ($clases as $clase) {
                        if ( $clase['grp'] == $grupo['cod'] ) {
                            $salida .= '<tr class="clases"><td>'.strtoupper($grupo['des']).'</td>
                                                            <td>'.strtoupper($clase['des']).'</td>
                                                            <td class="centro">
                                                                <a href="'.$clase['idx'].'" 
                                                                    data-accion="modify" 
                                                                    data-grp="'.$clase['grp'].'"
                                                                    data-cls="'.$clase['cod'].'">
                                                                    <i class="far fa-edit"></i></a>
                                                            </td>
                                                            <td class="centro">
                                                                <a href="'.$clase['idx'].'" 
                                                                data-accion="delete" 
                                                                data-grp="'.$clase['grp'].'
                                                                data-cls="'.$clase['cod'].'">
                                                                <i class="far fa-trash-alt"></i></a>
                                                            </td>
                                        </tr>';
                        }
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getTotalClases() {
            try {
                $query = $this->db->connect()->prepare('SELECT tb_clasprod.cdesclas FROM tb_clasprod WHERE nnivclas = 2');
                $query->execute();
                $rowcount = $query->rowCount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function updateClass($datos) {
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
    }
?>