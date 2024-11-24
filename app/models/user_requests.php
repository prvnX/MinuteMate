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

    // Fetch all pending requests
    public function getPendingRequests() {
        $query = "SELECT id, full_name FROM user_requests WHERE status = 'pending'";
        return $this->query($query);
    }

    public function getRequestById($requestId) {
        // Assuming you're using a prepared statement to prevent SQL injection
        $query = "SELECT * FROM user_requests WHERE id = :id";
        $data = ['id' => $requestId];
        
        // Fetch a single row from the database
        return $this->get_row($query, $data);
    } 

    public function deleteRequestById($requestId) {
        $query = "DELETE FROM user_requests WHERE id = :id";
        $data = ['id' => $requestId];
        return $this->query($query, $data);
    }
    
}
