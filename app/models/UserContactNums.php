<?php
// user_contact_nums.php

class UserContactNums {
    use Model; // Assuming Model trait is included
    protected $table = "user_contact_nums"; // Define table name

    public function insert($data) {
        // Insert primary contact number
        $query = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :contact_no)";
        $this->query($query, $data);

        // If there's an additional contact number, insert it
        if (isset($data['add_tp'])) {
            $query = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :add_tp)";
            $this->query($query, $data);
        }

        return ['success' => true, 'message' => 'Contact numbers successfully added.'];
    }
    

    public function getContactByUsername($username) {
        $result = $this->select_all(['username' => $username]);
        return $result ? $result[0]->contact_no : null; // Return the contact number if found
    }

    public function updateContactByUsername($username, $newPhone) {
        $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username";
        $this->query($query, ['username' => $username, 'contact_no' => $newPhone]);
    }
}


