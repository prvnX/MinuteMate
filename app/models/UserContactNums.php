<?php

class UserContactNums {
    use Model; 
    protected $table = "user_contact_nums"; 

    public function insert($data) {
        $query = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :contact_no)";
        $this->query($query, $data);

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
    public function updateContactNumbers($username, $newContact) {
        $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username";
        $data = [
            'contact_no' => $newContact,
            'username' => $username
        ];
        return $this->query($query, $data);
    }
    

    public function updateContacts($username, $primary, $additional = null) {
      
        $existingContacts = $this->getContactByUsername($username);
    
        if (!$existingContacts) {
            return ['success' => false, 'message' => 'No contact records found for the user.'];
        }
    
        $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username AND contact_no = :old_contact_no";
        $this->query($query, [
            'contact_no' => $primary,
            'username' => $username,
            'old_contact_no' => $existingContacts[0] ?? ''
        ]);
    
        if (isset($existingContacts[1])) {
            if (!empty($additional)) {
               
                $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username AND contact_no = :old_contact_no";
                $this->query($query, [
                    'contact_no' => $additional,
                    'username' => $username,
                    'old_contact_no' => $existingContacts[1]
                ]);
            } else {
                $query = "DELETE FROM $this->table WHERE username = :username AND contact_no = :old_contact_no";
                $this->query($query, [
                    'username' => $username,
                    'old_contact_no' => $existingContacts[1]
                ]);
            }
        } elseif (!empty($additional)) {
            $query = "INSERT INTO $this->table (username, contact_no) VALUES (:username, :contact_no)";
            $this->query($query, [
                'username' => $username,
                'contact_no' => $additional
            ]);
        }
    
        return ['success' => true, 'message' => 'Contact numbers updated successfully.'];
    }
    
    
    
    

    
    
    
    
}


