<?php
    class PersonalModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        public function getAll(){
            try {
                $salida ="";
                $query = $this->db->connectrrhh()->query("SELECT 
                                                            dni, 
                                                            CONCAT(apellidos,', ',nombres) AS nombres,
                                                            internal 
                                                        FROM tabla_aquarius 
                                                        WHERE estado = 'AC'
                                                        ORDER BY apellidos ASC");
                $query->execute();
                $rowcount = $query->rowcount();

                if($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr data-idx="'.$row['internal'].'">
                                    <td>'.$row['dni'].'</td>
                                    <td>'.strtoupper($row['nombres']).'</td>
                                    <td class="centro"><a href="'.$row['internal'].'"><i class="far fa-edit"></i></a></td>
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

                $query = $this->db->connectrrhh()->prepare("SELECT
                                                            tabla_aquarius.internal,
                                                            tabla_aquarius.dni,
                                                            tabla_aquarius.dcostos,
                                                            tabla_aquarius.dsede,
                                                            tabla_aquarius.apellidos,
                                                            tabla_aquarius.nombres,
                                                            tabla_aquarius.estado,
                                                            tabla_aquarius.correo 
                                                        FROM
                                                            tabla_aquarius 
                                                        WHERE
                                                            tabla_aquarius.internal =:idx 
                                                        ORDER BY
                                                            tabla_aquarius.apellidos ASC,
                                                            tabla_aquarius.nombres ASC 
                                                            LIMIT 1");
                $query->execute(["idx"=>$idx]);

                while ($row = $query->fetch()) {
                        $item['dni'] = $row['dni'];
                        $item['dcostos'] = $row['dcostos'];
                        $item['dsede'] = $row['dsede'];
                        $item['apellidos'] = $row['apellidos'];
                        $item['nombres'] = $row['nombres'];
                        $item['estado'] = $row['estado'];
                        $item['correo'] = $row['correo'];
                }

                return $item;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getTotals($est) {
            try {
                $query = $this->db->connectrrhh()->prepare("SELECT internal FROM tabla_aquarius WHERE estado =:est");
                $query->execute(["est"=>$est]);
                $rowcount = $query->rowcount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>