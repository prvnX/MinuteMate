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

        if(!$result){
            return null;
        }

        return array_map(fn($r) => $r->contact_no, $result);
    }

    public function updateOrInsertContactNumbers($username, $contactNumbers = []) {
        $query = "SELECT contact_no FROM $this->table WHERE username = :username";
        $existing = $this->query($query, ['username' => $username]);
    
        $existingNumbers = [];
    
        if ($existing && is_array($existing)) {
            // ✅ Fix: use -> instead of [''] if result is stdClass
            $existingNumbers = array_map(fn($row) => $row->contact_no, $existing);
        }
    
        // If there are exactly two existing numbers, update both of them
        if (count($existingNumbers) == 2) {
            foreach ($contactNumbers as $phone) {
                if (!in_array($phone, $existingNumbers)) {
                    // Update the first existing number (or any other existing number if you have a preferred logic)
                    $updateQuery = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username AND contact_no = :old_contact_no";
                    $this->query($updateQuery, [
                        'username' => $username, 
                        'contact_no' => $phone, 
                        'old_contact_no' => $existingNumbers[0] // This can be adjusted if you have logic on which one to update
                    ]);
                }
            }
        } 
        // If only one existing number, update it and insert the new one if needed
        elseif (count($existingNumbers) == 1) {
            // Update the first existing contact number
            $updateQuery = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username AND contact_no = :old_contact_no";
            $this->query($updateQuery, [
                'username' => $username, 
                'contact_no' => $contactNumbers[0], 
                'old_contact_no' => $existingNumbers[0]
            ]);
            
            // Insert the second number if it's different and not already existing
            if (!empty($contactNumbers[1]) && !in_array($contactNumbers[1], $existingNumbers)) {
                $insertQuery = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :contact_no)";
                $this->query($insertQuery, ['username' => $username, 'contact_no' => $contactNumbers[1]]);
            }
        } 
        // If no existing numbers, insert both
        else {
            foreach ($contactNumbers as $phone) {
                $insertQuery = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :contact_no)";
                $this->query($insertQuery, ['username' => $username, 'contact_no' => $phone]);
            }
        }
    }
    

    
    
    
    
}


