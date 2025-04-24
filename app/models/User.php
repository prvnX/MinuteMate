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
        'status'
    ]; // editable columns

    // Validation function (placeholder for now)
    public function validate($data) {
        $this->errors = [];
        if (empty($this->errors)) {
            return true;
        } else {
            return false;
        }
    }

    public function usernameExists($lecStuId) {
        $query = "SELECT COUNT(*) AS count FROM user WHERE username = :username";
        $result = $this->query($query, ['username' => $lecStuId]);
        return $result[0]->count > 0;
    }
    

    // Insert user and add to user_meeting_types table
    public function insert($data) {
        // Check if the username already exists
        $query = "SELECT COUNT(*) AS count FROM user WHERE username = :username";
        $result = $this->query($query, ['username' => $data['username']]);

        if ($result[0]->count > 0) {
            return ['success' => false, 'message' => 'Username already exists.'];
        }

        // Proceed with the insert if no duplicate username
        $query = "INSERT INTO user (username, password, nic, full_name, email, status) 
                  VALUES (:username, :password, :nic, :full_name, :email, :status)";
        $this->query($query, $data);

        // Get the username of the newly inserted user
        $username = $data['username'];

        return ['success' => true, 'message' => 'User successfully created and added to meeting types.'];
    }

    // Helper function to get meeting type id based on the role
    public function getMeetingTypeIdByRole($role) {
        // Here, map roles to meeting type ids
        switch ($role) {
            case 'secretary':
                return 1; // RHD
            case 'lecturer':
                return 2; // IOD
            case 'other':
                return 3; // SYN
            default:
                return null;
        }
    }

    public function getUserById($userId) {
        $query = "SELECT u.username, u.full_name, u.email, u.nic, u.status,
                         ur.role,
                         c.contact_no, 
                         m.meeting_type_id, mt.meeting_type,
                         s.meeting_type_id 
                  FROM user u
                  LEFT JOIN user_roles ur ON u.username = ur.username
                  LEFT JOIN user_contact_nums c ON u.username = c.username
                  LEFT JOIN user_meeting_types m ON u.username = m.accessible_user
                  LEFT JOIN meeting_types mt ON m.meeting_type_id = mt.type_id
                  LEFT JOIN secretary_meeting_type s ON u.username = s.username
                   LEFT JOIN meeting_types mt2 ON s.meeting_type_id = mt2.type_id
                  WHERE u.username = :username";
    
        $result = $this->query($query, ['username' => $userId]);
    
        if (empty($result)) {
            return null;
        }
    
        // Base user info from the first row
        $userData = $result[0];
    
        // Extract meeting types into an array
        $userData->meetingTypes = array_map(fn($row) => $row->meeting_type, $result);

        // Fetch secretary meeting types if exists
        $secretaryMeetingModel = new secretary_meeting_type();
        $userData-> secMeetings = $secretaryMeetingModel->getsecMeetingTypes($userId);

        $contactModel = new UserContactNums();
        $contacts = $contactModel->getContactByUsername($userId);

        // Ensure contact number and role are present (handle nulls gracefully)
        $userData->contact_no = $contacts[0] ?? null;
        $userData->additional_tp_no = $contacts[1] ?? null;

        $userData->role = array_unique(array_map(fn($row) => $row->role, $result));
    
        return $userData;
    }

    // public function updateContactInfo($username, $contact_no) {
    //     require_once __DIR__ . '/UserContactNums.php'; 
    //     $contactModel = new UserContactNums();
    
    //     // Handle single or multiple numbers
    //     $numbers = is_array($contact_no) ? $contact_no : [$contact_no];
    
    //     $contactModel->updateOrInsertContactNumbers($username, $numbers);
    // }

    public function updateContactNumber($username, $newContact) {
        $query = "UPDATE $this->table SET contact_no = :contact_no WHERE username = :username";
        $data = [
            'contact_no' => $newContact,
            'username' => $username
        ];
        return $this->query($query, $data);
    }

    
    

    public function updateUserByUsername($username, $data) {
        $query = "UPDATE user 
                  SET full_name = :full_name, email = :email, nic = :nic
                  WHERE username = :username";
        $data['username'] = $username;
        return $this->query($query, $data);
    }

    public function updateMeetingTypes($username, $meetingTypeIds) {
        require 'user_meeting_types.php';
        // Instantiate the User_Meeting_Types modelz
        $userMeetingTypesModel = new User_Meeting_Types();
        
        // Call the updateMeetingTypes method from User_Meeting_Types model
        $userMeetingTypesModel->updateMeetingTypes($username, $meetingTypeIds);
    }
    
    public function getUserNameList(){
        $query = "SELECT DISTINCT full_name FROM $this->table 
                  INNER JOIN user_roles ur ON $this->table.username = ur.username
                  WHERE ur.role != 'admin'";
        return $this->query($query);
    }

    public function deactivateUser($username){
    return $this->update($username, ['status' => 'inactive'], 'username');
}
  
public function reactivateStatus($username) {
    $query = "UPDATE user SET status = 'active' WHERE username = :username";
    return $this->query($query, ['username' => $username]);
}



    public function getUsersForMeetingType($meetingTypeId)
        {
            $query = "SELECT DISTINCT full_name , username
                    FROM $this->table 
                    INNER JOIN user_meeting_types umt ON $this->table.username = umt.accessible_user
                    WHERE umt.meeting_type_id = :meetingTypeId";

            return $this->query($query, ['meetingTypeId'=> $meetingTypeId]);

    }
    public function getUserDetails($username) {
        $query = "SELECT username, nic, full_name, email
              FROM  
                $this->table user
                INNER JOIN user_roles ur ON user.username = ur.username
                INNER JOIN user_contact_nums uc ON user.username = uc.username
                WHERE u.username = :username";
        return $this->query($query, ['username' => $username]);
                

                
    }

}
    