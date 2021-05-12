<?php
     class ProveedoresModel extends Model{

        public function __construct()
        {
            parent::__construct();
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

        public function getMoneda(){
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT ncodmon,cmoneda,dmoneda FROM tb_moneda");
                $query->execute();

                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodmon'].'" data-cmoneda="'.$row['dmoneda'].'">'.$row['dmoneda'].'</a></li>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getBanks(){
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT ncodbco,cdesbco FROM tb_banco");
                $query->execute();

                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodbco'].'">'.$row['cdesbco'].'</a></li>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getParameters($param){
            try {
                $salida = "";
                $query = $this->db->connect()->prepare("SELECT
                            tb_paramete2.ncodprm1,
                            tb_paramete2.ncodprm2,
                            tb_paramete2.ccodprm2,
                            tb_paramete2.cdesprm2,
                            tb_paramete2.nflgactivo 
                        FROM
                            tb_paramete2 
                        WHERE
                            tb_paramete2.ncodprm1 =:par");
                $query->execute(["par"=>$param]);

                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodprm2'].'">'.$row['cdesprm2'].'</a></li>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getCountry(){
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT ncodpais,cdespais FROM tb_pais ORDER BY cdespais");
                $query->execute();
                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodpais'].'">'.$row['cdespais'].'</a></li>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function exist($doc, $raz) {
            try {
                $query = $this->db->connect()->prepare("SELECT id_centi FROM cm_entidad WHERE cnumdoc = :raz OR crazonsoc = :doc");
                $query->execute(["raz"=>$raz,
                                "doc"=>$doc]);
                $rowcount = $query->rowcount();

                if ($rowcount == 0) {
                    return false;
                }else {
                    return true;
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insert($datos)
        {
            try {
                $id = uniqid();
                $query = $this->db->connect()->prepare("INSERT INTO cm_entidad SET nflgactivo=:est,id_centi=:idx,ctipdoc=:cdoc,ctipper=:cper,ctipenti=:cent,
                                                                                    ncodpais=:cpai,ncubigeo=:ubig,cnumdoc=:ndoc,crazonsoc=:razo,cemail=:corr,
                                                                                    capepat=:apat,capemat=:amat,cnombre1=:nomb,cnomcom=:nomc,ctelefono=:tele,
                                                                                    cviadireccion=:dir,cvianro=:nro,cviainterior=:intr,cviazona=:zona,nagenper=:agpe,
                                                                                    nagenret=:agre,nagepor=:porc,nagemin=:mmin,ncondicion=:cond,ndigregis=:nror,
                                                                                    cdigcateg=:cate,cdigsitua=:situ,ndigempadron=:empa,ncalifica=:cali,ncondpag=:pago");
                $query->execute(["est"=>$datos['est'],
                                "idx"=>$id,
                                "cdoc"=>$datos['cdoc'],
                                "cper"=>$datos['cper'],
                                "cpai"=>$datos['cpai'],
                                "cent"=>$datos['cent'],
                                "ubig"=>$datos['ubig'],
                                "ndoc"=>$datos['ndoc'],
                                "razo"=>$datos['razo'],
                                "corr"=>$datos['corr'],
                                "apat"=>$datos['apat'],
                                "amat"=>$datos['amat'],
                                "nomb"=>$datos['nomb'],
                                "nomc"=>$datos['nomc'],
                                "tele"=>$datos['tele'],
                                "dir"=>$datos['dir'],
                                "nro"=>$datos['nro'],
                                "intr"=>$datos['intr'],
                                "zona"=>$datos['zona'],
                                "agpe"=>$datos['agpe'],
                                "agre"=>$datos['agre'],
                                "porc"=>$datos['porc'],
                                "mmin"=>$datos['mmin'],
                                "cond"=>$datos['cond'],
                                "nror"=>$datos['nror'],
                                "cate"=>$datos['cate'],
                                "situ"=>$datos['situ'],
                                "empa"=>$datos['empa'],
                                "cali"=>$datos['cali'],
                                "pago"=>$datos['pago']]);

                $rowcount = $query->rowcount();

                return $id;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertContacts($datos) {
            try {
                $data = json_decode($datos);

                for ( $i=0; $i < count($data); $i++) {
                    $nom = $data[$i]->nom;
                    $cor = $data[$i]->cor;
                    $dir = $data[$i]->dir;
                    $tel = $data[$i]->tel;
                    $cod = $data[$i]->cod;
                    $flg = 1;
    
                    $query = $this->db->connect()->prepare("INSERT INTO cm_entidadcon (id_centi,cnombres,cdireccion,cemail,ctelefono1,nflgactivo) 
                                                            VALUES (:cod,:nom,:dir,:cor,:tel,:flg)");
                    $query->execute(["cod"=>$cod,"nom"=>$nom,"cor"=>$cor,"dir"=>$dir,"tel"=>$tel,"flg"=>$flg]);
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function insertBanks($datos){
            try {
                $data = json_decode($datos);

                for ( $i=0; $i < count($data); $i++) {
                    $cta = $data[$i]->cta;
                    $cba = $data[$i]->cba;
                    $mnn = $data[$i]->mnn;
                    $tic = $data[$i]->tic;
                    $cod = $data[$i]->cod;
                    $flg = 1;
    
                    $query = $this->db->connect()->prepare("INSERT INTO cm_entidadbco (id_centi,cnrocta,ncodbco,cmoneda,ctipcta,nflgactivo) 
                                                            VALUES (:cod,:cta,:cba,:mon,:tic,:flg)");
                    $query->execute(["cod"=>$cod,"cta"=>$cta,"cba"=>$cba,"mon"=>$mnn,"tic"=>$tic,"flg"=>$flg]);
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function update($datos) {
            try {
                $query = $this->db->connect()->prepare("UPDATE cm_entidad SET nflgactivo=:est,ctipdoc=:cdoc,ctipper=:cper,ctipenti=:cent,
                                                                                    ncodpais=:cpai,ncubigeo=:ubig,cnumdoc=:ndoc,crazonsoc=:razo,cemail=:corr,
                                                                                    capepat=:apat,capemat=:amat,cnombre1=:nomb,cnomcom=:nomc,ctelefono=:tele,
                                                                                    cviadireccion=:dir,cvianro=:nro,cviainterior=:intr,cviazona=:zona,nagenper=:agpe,
                                                                                    nagenret=:agre,nagepor=:porc,nagemin=:mmin,ncondicion=:cond,ndigregis=:nror,
                                                                                    cdigcateg=:cate,cdigsitua=:situ,ndigempadron=:empa,ncondpag=:pago 
                                                                        WHERE id_centi=:idx");
                $query->execute(["est"=>$datos['est'],
                                 "idx"=>$datos['idx'],
                                 "cdoc"=>$datos['cdoc'],
                                 "cper"=>$datos['cper'],
                                 "cpai"=>$datos['cpai'],
                                 "cent"=>$datos['cent'],
                                 "ubig"=>$datos['ubig'],
                                 "ndoc"=>$datos['ndoc'],
                                 "razo"=>$datos['razo'],
                                 "corr"=>$datos['corr'],
                                 "apat"=>$datos['apat'],
                                 "amat"=>$datos['amat'],
                                 "nomb"=>$datos['nomb'],
                                 "nomc"=>$datos['nomc'],
                                 "tele"=>$datos['tele'],
                                 "dir"=>$datos['dir'],
                                 "nro"=>$datos['nro'],
                                 "intr"=>$datos['intr'],
                                 "zona"=>$datos['zona'],
                                 "agpe"=>$datos['agpe'],
                                 "agre"=>$datos['agre'],
                                 "porc"=>$datos['porc'],
                                 "mmin"=>$datos['mmin'],
                                 "cond"=>$datos['cond'],
                                 "nror"=>$datos['nror'],
                                 "cate"=>$datos['cate'],
                                 "situ"=>$datos['situ'],
                                 "empa"=>$datos['empa'],
                                 "pago"=>$datos['pago']]);

                //$rowcount = $query->rowcount();

                $this->delBanks($datos['idx']);
                $this->delContacs($datos['idx']);
                
                /*forzar el grabado la actualizacion de los proveedores*/
                return true;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delete($id) {
            try {
                $query = $this->db->connect()->prepare("UPDATE cm_entidad SET nflgactivo=:flag WHERE id_centi=:id");
                $query->execute(["flag"=>0,"id"=>$id]);
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

        public function getAllEnt() {
            try {
                $salida = "";
                $query = $this->db->connect()->query("SELECT cm_entidad.crazonsoc,
                                                            cm_entidad.cnumdoc,
                                                            cm_entidad.cnomcom,
                                                            cm_entidad.id_centi 
                                                        FROM cm_entidad 
                                                        WHERE cm_entidad.nflgactivo = 1");
                $query->execute();

                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .= '<tr class="pointertr" data-id="'.$row['id_centi'].'">
                                        <td>'.strtoupper($row['crazonsoc']).'</td>
                                        <td>'.strtoupper($row['cnomcom']).'</td>
                                        <td>'.strtoupper($row['cnumdoc']).'</td>
                                        <td class="centro"><a href="'.$row['id_centi'].'"><i class="far fa-edit"></i></a></td>
                                    </tr>';
                    }
                }

                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function getEntById($id){
            try {
                $item = array();
                $query = $this->db->connect()->prepare("SELECT
                                                cm_entidad.id_centi,
                                                cm_entidad.ccodenti,
                                                cm_entidad.ctipdoc,
                                                cm_entidad.ctipper,
                                                cm_entidad.ctipenti,
                                                cm_entidad.cnumdoc,
                                                cm_entidad.crazonsoc,
                                                cm_entidad.cemail,
                                                cm_entidad.capepat,
                                                cm_entidad.capemat,
                                                cm_entidad.cnombre1,
                                                cm_entidad.cnombre2,
                                                cm_entidad.cnomcom,
                                                cm_entidad.ncodpais,
                                                cm_entidad.cviadireccion,
                                                cm_entidad.cvianro,
                                                cm_entidad.cviainterior,
                                                cm_entidad.cviazona,
                                                cm_entidad.ncubigeo,
                                                cm_entidad.ctelefono,
                                                cm_entidad.nagenper,
                                                cm_entidad.nagenret,
                                                cm_entidad.nagepor,
                                                cm_entidad.nagemin,
                                                cm_entidad.ncondicion,
                                                cm_entidad.ndigflag,
                                                cm_entidad.ndigregis,
                                                cm_entidad.cdigcateg,
                                                cm_entidad.cdigsitua,
                                                cm_entidad.ndigempadron,
                                                cm_entidad.ncalifica,
                                                cm_entidad.nflgactivo,
                                                cm_entidad.ncondpag,
                                                ( SELECT cdesprm2 FROM tb_paramete2 WHERE ncodprm2 = ncondpag ) AS det_pago,
                                                ( SELECT cdesprm2 FROM tb_paramete2 WHERE ncodprm2 = ctipdoc ) AS det_doc,
                                                ( SELECT cdesprm2 FROM tb_paramete2 WHERE ncodprm2 = ctipper ) AS det_per,
                                                ( SELECT cdesprm2 FROM tb_paramete2 WHERE ncodprm2 = ctipenti ) AS det_enti,
                                                ( SELECT cdespais FROM tb_pais WHERE tb_pais.ncodpais = cm_entidad.ncodpais ) AS det_pais,
                                                ( SELECT cdubigeo FROM tb_ubigeo WHERE tb_ubigeo.ccubigeo = SUBSTR( cm_entidad.ncubigeo, 1, 2 ) ) AS det_dpto,
                                                ( SELECT cdubigeo FROM tb_ubigeo WHERE tb_ubigeo.ccubigeo = SUBSTR( cm_entidad.ncubigeo, 1, 4 ) ) AS det_prov,
                                                ( SELECT cdubigeo FROM tb_ubigeo WHERE tb_ubigeo.ccubigeo = cm_entidad.ncubigeo ) AS det_dist 
                                            FROM
                                                cm_entidad 
                                            WHERE
                                                cm_entidad.id_centi = :cod 
                                                LIMIT 1");
                $query->execute(["cod"=>$id]);

                $rowcount = $query->rowcount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                            $item["id_centi"]       = $row["id_centi"];
                            $item["ccodenti"]       = $row["ccodenti"];
                            $item["ctipdoc"]        = $row["ctipdoc"];
                            $item["ctipper"]        = $row["ctipper"];
                            $item["ctipenti"]       = $row["ctipenti"];
                            $item["cnumdoc"]        = $row["cnumdoc"];
                            $item["crazonsoc"]      = $row["crazonsoc"];
                            $item["cemail"]         = $row["cemail"];
                            $item["capepat"]        = $row["capepat"];
                            $item["capemat"]        = $row["capemat"];
                            $item["cnombre1"]       = $row["cnombre1"];
                            $item["cnombre2"]       = $row["cnombre2"];
                            $item["cnomcom"]        = $row["cnomcom"];
                            $item["ncodpais"]       = $row["ncodpais"];
                            $item["cviadireccion"]  = $row["cviadireccion"];
                            $item["cvianro"]        = $row["cvianro"];
                            $item["cviainterior"]   = $row["cviainterior"];
                            $item["cviazona"]       = $row["cviazona"];
                            $item["ncubigeo"]       = $row["ncubigeo"];
                            $item["ctelefono"]      = $row["ctelefono"];
                            $item["nagenper"]       = $row["nagenper"];
                            $item["nagenret"]       = $row["nagenret"];
                            $item["nagepor"]        = $row["nagepor"];
                            $item["nagemin"]        = $row["nagemin"];
                            $item["ncondicion"]     = $row["ncondicion"];
                            $item["ndigflag"]       = $row["ndigflag"];
                            $item["ndigregis"]      = $row["ndigregis"];
                            $item["cdigcateg"]      = $row["cdigcateg"];
                            $item["cdigsitua"]      = $row["cdigsitua"];
                            $item["ndigempadron"]   = $row["ndigempadron"];
                            $item["ncalifica"]      = $row["ncalifica"];
                            $item["nflgactivo"]     = $row["nflgactivo"];
                            $item["det_doc"]        = $row["det_doc"];
                            $item["det_per"]        = $row["det_per"];
                            $item["det_enti"]       = $row["det_enti"];
                            $item["det_dpto"]       = $row["det_dpto"];
                            $item["det_prov"]       = $row["det_prov"];
                            $item["det_dist"]       = $row["det_dist"];
                            $item["det_pais"]       = $row["det_pais"];
                            $item["det_pago"]       = $row['det_pago'];
                            $item["ncondpag"]       = $row['ncondpag'];
                    }
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function showContacs($id){
            try {
                $salida ="";
                $query = $this->db->connect()->prepare("SELECT
                                                    cnombres,
                                                    cemail,
                                                    cdireccion,
                                                    ctelefono1,
                                                    nitem,
                                                    id_centi 
                                                FROM
                                                    cm_entidadcon 
                                                WHERE
                                                    id_centi = :id");
                $query->execute(["id"=>$id]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr>
                            <td>'.$row['cnombres'].'</td>
                            <td>'.$row['cemail'].'</td>
                            <td>'.$row['cdireccion'].'</td>
                            <td>'.$row['ctelefono1'].'</td>
                            <td class="centro"><a href="'.$row['id_centi'].'" data-accion="modify"><i class="far fa-edit"></i></a></td>
                            <td class="centro"><a href="'.$row['id_centi'].'" data-accion="delete"><i class="far fa-trash-alt"></i></a></td> 
                        </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function showBanks($id){
            try {
                $salida ="";
                $query = $this->db->connect()->prepare("SELECT cm_entidadbco.id_centi,
                                        cm_entidadbco.nitem,
                                        cm_entidadbco.ncodbco,
                                        cm_entidadbco.cnrocta,
                                        cm_entidadbco.ctipcta,
                                        cm_entidadbco.cmoneda,
                                        tb_moneda.dmoneda,
                                        tb_banco.cdesbco 
                                    FROM
                                        cm_entidadbco
                                        INNER JOIN tb_moneda ON cm_entidadbco.cmoneda = tb_moneda.ncodmon
                                        INNER JOIN tb_banco ON cm_entidadbco.ncodbco = tb_banco.ccodbco 
                                    WHERE
                                        cm_entidadbco.id_centi = :id");
                $query->execute(["id"=>$id]);
                $rowcount = $query->rowcount();

                if ($rowcount > 0) {
                    while ($row = $query->fetch()) {
                        $salida.='<tr>
                            <td>'.$row['cnrocta'].'</td>
                            <td>'.$row['cdesbco'].'</td>
                            <td>'.$row['dmoneda'].'</td>
                            <td>'.$row['ctipcta'].'</td>
                            <td><a href="'.$row['id_centi'].'" data-accion="modify"><i class="far fa-edit"></i></a></td>
                            <td><a href="'.$row['id_centi'].'" data-accion="delete"><i class="far fa-trash-alt"></i></a></td> 
                        </tr>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delContacs($id) {
            try {
                $query = $this->db->connect()->prepare("UPDATE cm_entidadcon SET nflgactivo = 0 WHERE id_centi = :id");
                $query->execute(["id"=>$id]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delBanks($id) {
            try {
                $query = $this->db->connect()->prepare("UPDATE cm_entidadbco SET nflgactivo = 0 WHERE id_centi = :id");
                $query->execute(["id"=>$id]);

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
    }
?>