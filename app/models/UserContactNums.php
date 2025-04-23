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

    public function updateContacts($username, $primary, $additional = null) {
        // Fetch existing contact numbers
        $existingContacts = $this->getContactByUsername($username);
    
        if (!$existingContacts) {
            return ['success' => false, 'message' => 'No contact records found for the user.'];
        }
    
        // Update the primary contact (assumed to be the first one in the list)
        $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username AND contact_no = :old_contact_no";
        $this->query($query, [
            'contact_no' => $primary,
            'username' => $username,
            'old_contact_no' => $existingContacts[0] ?? ''
        ]);
    
        // Handle additional contact number
        if (isset($existingContacts[1])) {
            if (!empty($additional)) {
                // Update existing additional number
                $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username AND contact_no = :old_contact_no";
                $this->query($query, [
                    'contact_no' => $additional,
                    'username' => $username,
                    'old_contact_no' => $existingContacts[1]
                ]);
            } else {
                // Optional: delete the additional number if now empty
                $query = "DELETE FROM $this->table WHERE username = :username AND contact_no = :old_contact_no";
                $this->query($query, [
                    'username' => $username,
                    'old_contact_no' => $existingContacts[1]
                ]);
            }
        } elseif (!empty($additional)) {
            // Add new additional number if it didn't exist
            $query = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :contact_no)";
            $this->query($query, [
                'username' => $username,
                'contact_no' => $additional
            ]);
        }
    
        return ['success' => true, 'message' => 'Contact numbers updated successfully.'];
    }
    
    
    
    

    
    
    
    
}


