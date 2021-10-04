<?php 
    class Database{
        //atributos
        private $host;
        private $db;
        private $user;
        private $password;
        private $charset;
        private $db2;

        //metodos
        public function __construct()
        {
            $this->host     = constant('HOST');
            $this->db       = constant('DB');
            $this->db2      = constant('DB2');
            $this->user     = constant('USER');
            $this->password = constant('PASSWORD');
            $this->charset  = constant('CHARSET');
        }

        function connect(){
            try{
                $connection = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset; 
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false,];
                $pdo = new PDO($connection,$this->user, $this->password, $options);
                return $pdo;
            }catch(PDOException $e){
                print_r('Error connection:'.$e->getMessage());
            };            
        }

        function connectrrhh(){
            try{
                $connection = "mysql:host=".$this->host.";dbname=".$this->db2.";charset=".$this->charset; 
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false,];
                $pdo = new PDO($connection,$this->user, $this->password, $options);
                return $pdo;
            }catch(PDOException $e){
                print_r('Error connection:'.$e->getMessage());
            };            
        }
    }
?>