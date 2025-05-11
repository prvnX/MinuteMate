<?php

require_once "../app/core/Database.php";

class user_requests {
    use Database; // Include your Database trait

    // Define the columns as class properties
    public $id;
    public $full_name;
    public $role;
    public $lec_stu_id;
    public $nic;
    public $email;
    public $tp_no;
    public $additional_tp_no;
    public $department;
    public $status;

    public function insertRequest($fullName, $roleString, $lecStuId, $nic, $email, $tpno, $additionalTpno, $status) {
        $query = "INSERT INTO user_requests (full_name, role, lec_stu_id, nic, email, tp_no, additional_tp_no, status) 
                  VALUES (:full_name, :role, :lec_stu_id, :nic, :email, :tp_no, :additional_tp_no, :status)";
        
        $data = [
            'full_name' => $fullName,
            'role' => $roleString,
            'lec_stu_id' => $lecStuId,
            'nic' => $nic,
            'email' => $email,
            'tp_no' => $tpno,
            'additional_tp_no' => $additionalTpno,
            'status' => $status,
        ];
   
        return $this->queryExec($query, $data);
        return $this->getLastInsertID();
    }
    

    public function getPendingRequests() {
        $query = "SELECT id, full_name FROM user_requests WHERE status = 'pending'";
        return $this->query($query);
    }

    public function getRequestById($requestId) {
      
        $query = "SELECT * FROM user_requests WHERE id = :id";
        $data = ['id' => $requestId];
        
        return $this->get_row($query, $data);
    } 

    public function deleteRequestById($requestId) {
        $query = "DELETE FROM user_requests WHERE id = :id";
        $data = ['id' => $requestId];
        return $this->query($query, $data);
    }

    public function updateRequestStatusById($requestId, $status) {
        $query = "UPDATE user_requests SET status = :status WHERE id = :id";
        $data = [
            'status' => $status,
            'id' => $requestId,
        ];

        return $this->queryExec($query, $data);
    }
    
    
}
