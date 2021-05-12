<?php
     class BienesModel extends Model{

        public function __construct()
        {
            parent::__construct();
        }

        //listar los grupos/clases/familias
        public function getAllGroups() {
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
                        $salida .= '<tr>
                                        <td class="grupos">'.strtoupper($row['nombre_grupo']).'</td>
                                        <td class="clases">'.strtoupper($row['nombre_clase']).'</td>
                                        <td class="familias">'.strtoupper($row['nombre_familia']).'</td>
                                        <td><a href="'.$row['idx'].'" 
                                                data-codgrupo="'.$row['codigo_grupo'].'"
                                                data-nomgrupo="'.$row['nombre_grupo'].'"
                                                data-codclase="'.$row['codigo_clase'].'"
                                                data-nomclase="'.$row['nombre_clase'].'"
                                                data-codfamil="'.$row['codigo_familia'].'"
                                                data-nomfamil="'.$row['nombre_familia'].'">
                                                <i class="far fa-edit"></i>
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

        //generar el ultimo codigo 
        public function generateCode($datos){
            try {

                $query = $this->db->connect()->prepare("SELECT cm_producto.ccodprod FROM cm_producto WHERE SUBSTR(cm_producto.ccodprod,1,8) = :cod");
                $query->execute(["cod"=>$datos["cod"]]);
                
                $rowcount = $query->rowCount();

                return $rowcount;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //listar las unidades de medida
        public function listUnids(){
            try {
                $salida = "";

                $query = $this->db->connect()->query("SELECT
                                                    tb_unimed.ncodmed,
                                                    tb_unimed.ccodmed,
                                                    tb_unimed.cdesmed,
                                                    tb_unimed.nunisunat,
                                                    tb_unimed.nfactor
                                                    FROM
                                                    tb_unimed
                                                    ORDER BY
                                                    tb_unimed.cdesmed");
                
                $query->execute();
                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida.='<li><a href="'.$row['ncodmed'].'">'.$row['cdesmed'].'</a></li>';
                    }
                }

                return $salida;

            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //insertar registros
        public function insert($datos) {
            try {
                $cod = substr($datos['codi'],0,8);
                $internal = compact("cod");
                $newcode = $this->generateCode($internal);
                $codi = $cod.str_pad($newcode+1,4,"0",STR_PAD_LEFT);
                $id = uniqid();
                
                $query = $this->db->connect()->prepare("INSERT INTO cm_producto (ccodprod,cdesprod,cdescomer,cdesabrev,ncodmed,ncodcata,ncodsunat,ntipoprod,ntipoorig,ntipodest,
                                                                                nloteprod,nserieprod,ccodbarra,nregesp,nunidsec,ndigemid,ngasrela,nicbper,mdetalle,cmarca,
                                                                                cmodelo,cmedida,ccolor,cnroparte,ccodanexo,npeso,nvolumen,cfoto,nflgactivo,id_cprod)
                                                                        VALUES(:codi,:derc,:ncom,:ncor,:coun,:ccat,:prsu,:tipr,:orig,:dest,
                                                                                :lote,:srun,:codb,:rees,:unse,:cond,:prre,:afic,:deta,:marc,
                                                                                :mode,:medi,:colo,:nopa,:coan,:peso,:volu,:rufo,:esta,:ncod)");
                $query->execute(["codi"=>$codi,
                                 "derc"=>$datos['derc'],
                                 "ncom"=>$datos['ncom'],
                                 "ncor"=>$datos['ncor'],
                                 "coun"=>$datos['coun'],
                                 "ccat"=>$datos['ccat'],
                                 "prsu"=>$datos['prsu'],
                                 "tipr"=>$datos['tipr'],
                                 "orig"=>$datos['orig'],
                                 "dest"=>$datos['dest'],
                                 "lote"=>$datos['lote'],
                                 "srun"=>$datos['srun'],
                                 "codb"=>$datos['codb'],
                                 "rees"=>$datos['rees'],
                                 "unse"=>$datos['unse'],
                                 "cond"=>$datos['cond'],
                                 "prre"=>$datos['prre'],
                                 "afic"=>$datos['afic'],
                                 "deta"=>$datos['deta'],
                                 "marc"=>$datos['marc'],
                                 "mode"=>$datos['mode'],
                                 "medi"=>$datos['medi'],
                                 "colo"=>$datos['colo'],
                                 "nopa"=>$datos['nopa'],
                                 "coan"=>$datos['coan'],
                                 "peso"=>$datos['peso'],
                                 "volu"=>$datos['volu'],
                                 "rufo"=>$datos['rufo'],
                                 "esta"=>1,
                                 "ncod"=>$id]);

                    $rowcount = $query->rowcount();
                    $errorinfo = $query->errorInfo();

                    if ($rowcount > 0) {
                        $insert = $this->insertHistory($id,"insert");
                        return $id;
                    }else {
                        return false;
                    }
                    
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function update($datos) {
            try {

                $query = $this->db->connect()->prepare("UPDATE cm_producto SET cdesprod=:derc,
                                                                            cdescomer=:ncom,
                                                                            cdesabrev=:ncor,
                                                                            ncodmed=:unme,
                                                                            mdetalle=:deta,
                                                                            cmarca=:marc,
                                                                            cmodelo=:mode,
                                                                            cmedida=:medi,
                                                                            ccolor=:colo,
                                                                            npeso=:peso,
                                                                            nvolumen=:volu,
                                                                            cnroparte=:nopa,
                                                                            ccodanexo=:coan,
                                                                            ntipoorig=:orig, 
                                                                            ntipodest=:dest,
                                                                            nunidsec=:unse,
                                                                            nloteprod=:lote,
                                                                            nserieprod=:srun,
                                                                            ndigemid=:cond,
                                                                            ngasrela=:prre,
                                                                            nicbper=:afic,
                                                                            nregesp=:rees,
                                                                            cfoto=:rufo
                                                         WHERE id_cprod =:id  LIMIT 1");

                $query->execute(["derc"=>$datos['derc'],
                                 "ncom"=>$datos['ncom'],
                                 "ncor"=>$datos['ncor'],
                                 "unme"=>$datos['unme'],
                                 "deta"=>$datos['deta'],
                                 "marc"=>$datos['marc'],
                                 "mode"=>$datos['mode'],
                                 "medi"=>$datos['medi'],
                                 "colo"=>$datos['colo'],
                                 "peso"=>$datos['peso'],
                                 "volu"=>$datos['volu'],
                                 "nopa"=>$datos['nopa'],
                                 "coan"=>$datos['coan'],
                                 "orig"=>$datos['orig'],
                                 "dest"=>$datos['dest'],
                                 "unse"=>$datos['unse'],
                                 "lote"=>$datos['lote'],
                                 "srun"=>$datos['srun'],
                                 "cond"=>$datos['cond'],
                                 "prre"=>$datos['prre'],
                                 "afic"=>$datos['afic'],
                                 "rees"=>$datos['rees'],
                                 "rufo"=>$datos['rufo'],
                                 "id"=>$datos['id']]);

                $rowcount = $query->rowcount();

                $errorinfo = $query->errorInfo();  
                var_dump($errorinfo);

                echo $rowcount;
                if ($rowcount > 0) {
                    $insert = $this->insertHistory($datos['id'],"update");
                    return true;
                }else {
                    return false;
                }

            }  catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function delete($cod) {
            try {
                $query= $this->db->connect()->prepare("UPDATE cm_producto SET nflgactivo=:est WHERE id_cprod =:id  LIMIT 1");
                $query->execute(["est"=>0,"id"=>$cod]);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        public function listItems() {
            try {
                $salida ="";
                $query  = $this->db->connect()->query("SELECT
                                    cm_producto.id_cprod,
                                    cm_producto.ccodprod,
                                    cm_producto.cdesprod,
                                    cm_producto.cdescomer,
                                    cm_producto.cdesabrev,
                                    cm_producto.cmarca,
                                    cm_producto.cmodelo,
                                    cm_producto.fregsys,
                                    cm_producto.ccolor,
                                    cm_producto.cnroparte
                                    FROM
                                    cm_producto
                                    WHERE cm_producto.nflgactivo = '1' AND  cm_producto.ntipoprod = '1'
                                    ORDER BY
                                    cm_producto.ccodprod ASC");
                $query->execute();

                while($row = $query->fetch()){
                    $salida.='<tr>
                                <td>'.$row['ccodprod'].'</td>
                                <td>'.strtoupper($row['cdesprod']).'</td>
                                <td>'.strtoupper($row['cdescomer']).'</td>
                                <td>'.strtoupper($row['cdesabrev']).'</td>
                                <td>'.strtoupper($row['cmarca']).'</td>
                                <td>'.strtoupper($row['cmodelo']).'</td>
                                <td>'.strtoupper($row['cnroparte']).'</td>
                                <td><a href="'.$row['id_cprod'].'"><i class="far fa-edit"></i></a></td>
                            </tr>';
                }
                
                return $salida;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //obtener el item de acuerdo al codigo
        public function listItemById($datos){
            try {

                $item = array();

                $query = $this->db->connect()->prepare("SELECT
                                    cm_producto.id_cprod,
                                    cm_producto.ccodprod,
                                    cm_producto.cdesprod,
                                    cm_producto.cdescomer,
                                    cm_producto.cdesabrev,
                                    cm_producto.ncodsunat,
                                    cm_producto.ntipoprod,
                                    cm_producto.ntipocosto,
                                    cm_producto.ntipoorig,
                                    cm_producto.ntipodest,
                                    cm_producto.nloteprod,
                                    cm_producto.nserieprod,
                                    cm_producto.ccodbarra,
                                    cm_producto.nstockmin,
                                    cm_producto.nstockmax,
                                    cm_producto.ncodaranc,
                                    cm_producto.nregesp,
                                    cm_producto.nunidsec,
                                    cm_producto.ndigemid,
                                    cm_producto.ngasrela,
                                    cm_producto.nicbper,
                                    cm_producto.ncodtexis,
                                    cm_producto.mdetalle,
                                    cm_producto.cmarca,
                                    cm_producto.cmodelo,
                                    cm_producto.cmedida,
                                    cm_producto.ccolor,
                                    cm_producto.cnroparte,
                                    cm_producto.ccodanexo,
                                    cm_producto.npeso,
                                    cm_producto.nvolumen,
                                    cm_producto.cfoto,
                                    cm_producto.nflgactivo,
                                    cm_producto.fregsys,
                                    cm_producto.ncodmed,
                                    tb_unimed.cdesmed
                                FROM  cm_producto
                                INNER JOIN tb_unimed ON cm_producto.ncodmed = tb_unimed.ncodmed
                                WHERE cm_producto.id_cprod = :cod
                                LIMIT 1");
                $query->execute(["cod"=>$datos['cod']]);

                while($row = $query->fetch()){
                    $item["id_cprod"]   = $row['id_cprod'];
                    $item["ccodprod"]   = $row['ccodprod'];
                    $item['cdesprod']   = $row['cdesprod'];
                    $item['cdescomer']  = $row['cdescomer'];
                    $item['cdesabrev']  = $row['cdesabrev'];
                    $item['ncodsunat']  = $row['ncodsunat'];
                    $item['ntipoprod']  = $row['ntipoprod'];
                    $item['ntipocosto'] = $row['ntipocosto'];
                    $item['ntipoorig']  = $row['ntipoorig'];
                    $item['ntipodest']  = $row['ntipodest'];
                    $item['nloteprod']  = $row['nloteprod'];
                    $item['nserieprod'] = $row['nserieprod'];
                    $item['ccodbarra']  = $row['ccodbarra'];
                    $item['nstockmin']  = $row['nstockmin'];
                    $item['nstockmax']  = $row['nstockmax'];
                    $item['ncodaranc']  = $row['ncodaranc'];
                    $item['nregesp']    = $row['nregesp'];
                    $item['nunidsec']   = $row['nunidsec'];
                    $item['ndigemid']   = $row['ndigemid'];
                    $item['ngasrela']   = $row['ngasrela'];
                    $item['nicbper']    = $row['nicbper'];
                    $item['ncodtexis']  = $row['ncodtexis'];
                    $item['mdetalle']   = $row['mdetalle'];
                    $item['cmarca']     = $row['cmarca'];
                    $item['cmodelo']    = $row['cmodelo'];
                    $item['cmedida']    = $row['cmedida'];
                    $item['ccolor']     = $row['ccolor'];
                    $item['cnroparte']  = $row['cnroparte'];
                    $item['ccodanexo']  = $row['ccodanexo'];
                    $item['npeso']      = $row['npeso'];
                    $item['nvolumen']   = $row['nvolumen'];
                    $item['cfoto']      = $row['cfoto'];
                    $item['nflgactivo'] = $row['nflgactivo'];
                    $item['fregsys']    = $row['fregsys'];
                    $item['ncodmed']    = $row['ncodmed'];
                    $item['cdesmed']    = $row['cdesmed'];

                    $grupo   = substr($row['ccodprod'],0,2);
                    $clase   = substr($row['ccodprod'],2,2);
                    $familia = substr($row['ccodprod'],4,4);

                    $item['grupo']      = $this->getGroupName($grupo);
                    $item['clase']      = $this->getClassName($grupo,$clase);
                    $item['familia']    = $this->getFamilyName($grupo,$clase,$familia);
                }

                return $item;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        //insertar el seguimiento
        public function insertHistory($codigo,$proceso){

            try {
                session_start();
                $usuario =  $_SESSION['iduser'];
                $modulo = "bienes";
                
                $query = $this->db->connect()->prepare("INSERT INTO cm_seguimiento (cmodulo,ncodref,ncoduser,cproceso) VALUES(:mod,:cod,:usr,:pro)");
                $query->execute(["mod"=>$modulo,"cod"=>$codigo,"usr"=>$usuario,"pro"=>$proceso]);
    
                $rowcount = $query->rowcount();
    
                if ($rowcount > 0) {
                    return true;
                }else {    
                    return false;
                }
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
           
        }

        //insertar los documentos externos
        public function insertDocs($datos) {
            try {
                $data = json_decode($datos);

                for ( $i=0; $i < count($data); $i++) {
                    $des = $data[$i]->des;
                    $nom = $data[$i]->nom;
                    $adj = $data[$i]->adj;
                    $cod = $data[$i]->cod;
    
                    $query = $this->db->connect()->prepare("INSERT INTO cm_proddocum (id_cprod,creferen,cproceso,cdocumen) VALUES (:cod,:dea,:pro,:adj)");
                    $query->execute(["cod"=>$cod,"dea"=>$des,"pro"=>$nom,"adj"=>$adj]);
                }
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        //listar el seguimiento
        public function showHistory($codigo){
            try {
                $salida="";
                $query = $this->db->connect()->prepare("SELECT ncoduser,cproceso,fregsys FROM  cm_seguimiento WHERE ncodref = :cod");
                $query->execute(["cod"=>$codigo]);
                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .= '<tr><td class="pl20">'.strtoupper('USUARIO DEMO').'</td>
                                        <td class="pl20">'.strtoupper($row['cproceso']).'</td>
                                        <td class="centro">'.$row['fregsys'].'</td>
                                    </tr>';
                    }

                    return $salida;
                }
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        //listar los doccumentos
        public function showDocs($codigo){
            try {
                $salida="";
                $query = $this->db->connect()->prepare("SELECT creferen,cproceso,cdocumen FROM cm_proddocum WHERE id_cprod= :cod");
                $query->execute(["cod"=>$codigo]);
                $rowcount = $query->rowCount();
                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $salida .= '<tr><td class="pl20">'.strtoupper($row['creferen']).'</td>
                                        <td class="pl20">'.strtoupper($row['cproceso']).'</td>
                                        <td class="centro"><a href="'.constant('URL').'/public/docs/'.$row['cdocumen'].'"><i class="far fa-eye"></i></a></td>
                                    </tr>';
                    }

                    return $salida;
                }
            } catch (PDOException $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getGroupName($g){
            try {
                $query=$this->db->connect()->prepare("SELECT tb_clasprod.cdesclas FROM tb_clasprod WHERE tb_clasprod.ccodgru = :grupo 
                                                        AND tb_clasprod.nnivclas = '1' LIMIT 1");
                $query->execute(["grupo"=>$g]);
                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $grupo = $row['cdesclas'];
                    }
                }

                return $grupo;

            } catch (PDOException  $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getClassName($g,$c){
            try {
                $query=$this->db->connect()->prepare("SELECT tb_clasprod.cdesclas FROM tb_clasprod WHERE tb_clasprod.ccodgru = :grupo 
                                                     AND  tb_clasprod.ccodcla = :clase AND tb_clasprod.nnivclas = '2' LIMIT 1");
                $query->execute(["grupo"=>$g,"clase"=>$c]);

                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $clase = $row['cdesclas'];
                    }
                }

                return $clase;

            } catch (PDOException  $e) {
                $e->getMessage();
                return false;
            }
        }

        public function getFamilyName($g,$c,$f){
            try {
                $query=$this->db->connect()->prepare("SELECT tb_clasprod.cdesclas FROM tb_clasprod 
                                                        WHERE tb_clasprod.ccodgru = :grupo AND tb_clasprod.ccodcla = :clase AND tb_clasprod.ccodfam = :familia
                                                        AND tb_clasprod.nnivclas = '3' LIMIT 1");
                $query->execute(["grupo"=>$g,"clase"=>$c,"familia"=>$f]);

                $rowcount = $query->rowCount();

                if ($rowcount > 0 ){
                    while ($row = $query->fetch()) {
                        $fam = $row['cdesclas'];
                    }
                }

                return $fam;

            } catch (PDOException  $e) {
                $e->getMessage();
                return false;
            }
        }
    }
?>