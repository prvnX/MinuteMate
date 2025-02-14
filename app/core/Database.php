<?php
trait Database{
    private $conn; // db connection
    private function connect(){
        if(!$this->conn){
            $string="mysql:hostname=".DBHOST.";dbname=".DBNAME;
            $this->conn = new PDO($string,DBUSER,DBPASS,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        return $this->conn;
    }

    public function query($query,$data=[]){ //for any CRUD operation
        $conn= $this->connect();
        $stm = $conn->prepare($query);
        $check= $stm->execute($data);
        if($check){
             $result=$stm->fetchAll(PDO::FETCH_OBJ);
             if(is_array($result) && count($result)){
                return $result;
             }        
        }
        return false;
    }

    public function queryExec($query,$data=[]){ //for insert, update, delete
        $conn= $this->connect();
        $stm= $conn->prepare($query);
        $check= $stm->execute($data);
        return $check ? $stm->rowCount() > 0 : false; 
    }

    /* Transaction Methods*/
    public function beginTransaction(){
        $this->connect()->beginTransaction(); //begin the transaction with calling pdo beginTransaction
    }

    //commit the transaction
    public function commit(){
        $this->connect()->commit(); //commit the transaction with calling pdo commit
    }

    //rollback if any error occurs
    public function rollBack(){
        $this->connect()->rollBack(); //rollback the transaction with calling pdo rollBack
    }

    public function getLastInsertID(){
        return $this->connect()->lastInsertId(); //get the last inserted id
    }

    /*get_row Only returns a one result*/
    public function get_row($query,$data=[]){
        $conn= $this->connect();
        $stm = $conn->prepare($query);
        $check= $stm->execute($data);
        if($check){
             $result=$stm->fetchAll(PDO::FETCH_OBJ);
             if(is_array($result) && count($result)){
                return $result[0];
             }        
        }
        return false;
    }
    
}