<?php
// user_contact_nums.php

class UserContactNums {
    use Model; // Assuming Model trait is included
    protected $table = "user_contact_nums"; // Define table name

    public function insert($data) {
        $fields = array_keys($data);
        $placeholders = array_map(fn($field) => ":$field", $fields);
    
        $query = "INSERT INTO $this->table (" . implode(", ", $fields) . ") 
                  VALUES (" . implode(", ", $placeholders) . ")";
        $this->query($query, $data);
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


