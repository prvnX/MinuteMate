<?php
/* User Class */

class User {
    use Model;
    protected $table = 'user';
    
    protected $allowedColumns = [
        'username',
        'password',
        'nic',
        'full_name',
        'email',
        'role',
        'status'
    ]; //editable columns 

    //should add extra functions to  work with advanced concepts
    public function validate($data) {
        $this->errors = [];
        if (empty($this->errors)) {
            return true;
        }
        else{
            return false;
        }
    }

    public function insert($data) {
        // Check if the username already exists
        $query = "SELECT COUNT(*) AS count FROM user WHERE username = :username";
        $result = $this->query($query, ['username' => $data['username']]);
        
        // Check if result is an object (assuming query returns an object)
        if ($result[0]->count > 0) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }
    
        // Proceed with the insert if no duplicate username
        $query = "INSERT INTO user (username, password, nic, full_name, email, role, status) 
                  VALUES (:username, :password, :nic, :full_name, :email, :role, :status)";
        $this->query($query, $data);
    
        // Get the username of the newly inserted user
        $username = $data['username'];
        
        // Get the meeting type ID based on the user's role
        $meetingTypeId = $this->getMeetingTypeIdByRole($data['role']);
        
        // Insert the user into the user_meeting_types table if meetingTypeId is found
        if ($username && $meetingTypeId) {
            $query = "INSERT INTO user_meeting_types (accessible_user, meeting_type_id) 
                      VALUES (:accessible_user, :meeting_type_id)";
            $this->query($query, [
                'accessible_user' => $username,
                'meeting_type_id' => $meetingTypeId
            ]);
        }
    
        return ['success' => true, 'message' => 'User successfully created and added to meeting types.'];
    }
    
    
    // Helper function to get meeting type id based on the role
    public function getMeetingTypeIdByRole($role) {
        // Here, map roles to meeting type ids
        switch ($role) {
            case 'admin':
                return 1; // RHD
            case 'user':
                return 2; // IOD
            case 'manager':
                return 3; // SYN
            // Add more roles and meeting type mappings as needed
            default:
                return null;
        }
    }
    
    
    
    
    public function getUserIdByUsername($username) {
        $query = "SELECT username FROM user WHERE username = :username";
        $result = $this->query($query, ['username' => $username]);
    
        return $result[0]['username'] ?? null; // Return the username if found, or null
    }
    
    



}