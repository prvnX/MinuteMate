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

    // Insert user and add to user_meeting_types table
    public function insert($data) {
        // Check if the username already exists
        $query = "SELECT COUNT(*) AS count FROM user WHERE username = :username";
        $result = $this->query($query, ['username' => $data['username']]);

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
            default:
                return null;
        }
    }

    public function getUserById($userId) {
        $query = "SELECT u.username, u.full_name, u.email, u.nic, u.role, u.status,
                         m.meeting_type_id, mt.meeting_type 
                  FROM user u
                  LEFT JOIN user_meeting_types m ON u.username = m.accessible_user
                  LEFT JOIN meeting_types mt ON m.meeting_type_id = mt.type_id
                  WHERE u.username = :username";

        $result = $this->query($query, ['username' => $userId]);

        if (empty($result)) {
            return null; // Return null if no user found
        }

        $userData = $result[0]; // Assuming one user
        $userData->meetingTypes = array_map(fn($row) => $row->meeting_type, $result);

        // Fetch the phone number from the user_contact_nums table
        require 'UserContactNums.php'; // Explicitly include the model
        $contactNums = new UserContactNums(); // Instantiate the `UserContactNums` model
        $userData->contact_no = $contactNums->getContactByUsername($userId);

        return $userData;
    }

    // Fetch the user ID by username
    public function getUserIdByUsername($username) {
        $query = "SELECT username FROM user WHERE username = :username";
        $result = $this->query($query, ['username' => $username]);

        return $result[0]['username'] ?? null; // Return the username if found, or null
    }

    public function updateContactInfo($username, $newPhone) {
        
        require 'UserContactNums.php';
        $contactNums = new UserContactNums();
        $contactNums->updateContactByUsername($username, $newPhone);
    }

    public function updateUserByUsername($username, $data) {
        $query = "UPDATE user 
                  SET full_name = :full_name, email = :email, nic = :nic, role = :role 
                  WHERE username = :username";
        $data['username'] = $username;
        return $this->query($query, $data);
    }

    public function updateMeetingTypes($username, $meetingTypeIds) {
        require 'user_meeting_types.php';
        // Instantiate the User_Meeting_Types model
        $userMeetingTypesModel = new User_Meeting_Types();
        
        // Call the updateMeetingTypes method from User_Meeting_Types model
        $userMeetingTypesModel->updateMeetingTypes($username, $meetingTypeIds);
    }

    // public function updateUserStatus($username, $status) {
    //     // Prepare the query to update the user's status by username
    //     $query = "UPDATE user SET status = :status WHERE username = :username";
        
    //     // Prepare and execute the query
    //     // $stmt = $this->db->prepare($query);
    //     $stmt->bindParam(':status', $status);
    //     $stmt->bindParam(':username', $username);
    
    //     // Execute the query and return whether it was successful
    //     return $stmt->execute();
    // }
    


}
