<?php
     class FamiliaModel extends Model{

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

        public function getAllClasesByCodGroup($datos){
            try {
                $salida = "";

                $query = $this->db->connect()->prepare("SELECT tb_clasprod.ccodcla,tb_clasprod.cdesclas FROM tb_clasprod WHERE ccodgru = :cod AND tb_clasprod.nnivclas = 2");
                $query->execute(['cod' => $datos['cod']]);
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ccodcla'].'">'.$row['cdesclas'].'</a></li>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function genFamilyCode($datos){
            try {
                $query = $this->db->connect()->prepare("SELECT tb_clasprod.ccodfam FROM tb_clasprod WHERE ccodgru = :group AND ccodcla = :class AND tb_clasprod.nnivclas = 3");
                $query->execute(['group' => $datos['group'],'class' => $datos['class']]);
                $rowcount = $query->rowCount();

                return $rowcount;

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

        //insertar nueva clase
        public function insert($datos) {
            try {
                $query = $this->db->connect()->prepare('INSERT INTO tb_clasprod (CCODGRU,CCODCLA,CCODFAM,CDESCLAS,NNIVCLAS) VALUES (:gcod,:ccod,:cfam,:descrip,:nivel)');
                $query->execute(['gcod' => $datos['gcod'],
                                'ccod' => $datos['ccod'],
                                'cfam' => $datos['cfam'],
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

            try {
                $query = $this->db->connect()->query("SELECT
                                    fam.ncodcata AS idx,
                                    gru.cdesgru AS nombre_grupo,
                                    cla.cdescla AS nombre_clase,
                                    fam.cdesclas AS nombre_familia,
                                    fam.ccodgru AS codigo_grupo,
                                    fam.ccodcla AS codigo_clase,
                                    fam.ccodfam AS codigo_familia 
                                FROM
                                    tb_clasprod AS fam
                                    INNER JOIN ( SELECT ccodgru, cdesclas AS cdesgru FROM tb_clasprod WHERE nnivclas = 1 ) gru ON fam.ccodgru = gru.ccodgru
                                    INNER JOIN ( SELECT ccodgru, ccodcla, cdesclas AS cdescla FROM tb_clasprod WHERE nnivclas = 2 ) cla ON fam.ccodgru = cla.ccodgru 
                                    AND fam.ccodcla = cla.ccodcla 
                                WHERE
                                    NOT ISNULL( fam.ccodfam ) 
                                    AND fam.cdesclas <> '' 
                                ORDER BY
                                    gru.cdesgru");
                $query->execute();
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .= '<tr><td class="grupos">'.strtoupper($row['nombre_grupo']).'</td>
                                        <td class="clases">'.strtoupper($row['nombre_clase']).'</td>
                                        <td class="familias">'.strtoupper($row['nombre_familia']).'</td>
                                        <td><a href="'.$row['idx'].'"
                                                data-accion="modify" 
                                                data-codgrupo="'.$row['codigo_grupo'].'"
                                                data-codclase="'.$row['codigo_clase'].'"
                                                data-codfamil="'.$row['codigo_familia'].'">
                                                <i class="far fa-edit"></i>
                                            </a></td>
                                        <td>
                                            <a href="'.$row['idx'].'" 
                                                data-accion="delete">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function update($datos) {
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

        public function getTotalFamily() {
            try {
                $query = $this->db->connect()->prepare('SELECT tb_clasprod.cdesclas FROM tb_clasprod WHERE nnivclas = 3');
                $query->execute();
                $rowcount = $query->rowCount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>