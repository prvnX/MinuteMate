<?php
class Admin extends BaseController {
    public function index() {
        $this->view("admin/dashboard");
    }

    public function viewpendingRequests(): void {
        // Create an instance of the UserRequests model
        $userRequestsModel = $this->model("user_requests");

        // Fetch the list of pending requests
        $pendingRequests = $userRequestsModel->getPendingRequests();

        // Pass the data to the view
        $this->view(name: "admin/viewpendingRequests", data: [
            "pendingRequests" => $pendingRequests
        ]);
    }
    
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestData = json_decode(file_get_contents("php://input"), true);
            $requestId = $requestData['id'] ?? null;
            $action = $requestData['action'] ?? null;
            $meetingTypes = $requestData['meetingTypes'] ?? [];
    
            $userRequestsModel = $this->model("user_requests");
    
            if ($requestId && $action === 'accept') {
                $userModel = $this->model("User");
                $userRolesModel = $this->model("UserRoles");
                $userMeetingTypesModel = $this->model("user_meeting_types");
                $userContactNumsModel = $this->model("UserContactNums");
    
                $userDetails = $userRequestsModel->getRequestById($requestId);
    
                if ($userDetails) {
                    $username = strtolower(str_replace(' ', '', $userDetails->lec_stu_id));
                    $password = password_hash($userDetails->nic, PASSWORD_DEFAULT);
    
                    try {
                        // Start transaction
                        $userModel->beginTransaction();
    
                        // Insert into user table
                        $userInsertResult = $userModel->insert([
                            'username' => $username,
                            'password' => $password,
                            'nic' => $userDetails->nic,
                            'full_name' => $userDetails->full_name,
                            'email' => $userDetails->email,
                            'status' => 'active'
                        ]);
    
                        if (!$userInsertResult['success']) {
                            throw new Exception("Username already exists.");
                        }
    
                        // Insert into user_roles
                        $userRolesModel->insert([
                            'username' => $username,
                            'role' => $userDetails->role
                        ]);
    
                        // Insert contact number(s)
                        $userContactNumsModel->insert([
                            'username' => $username,
                            'contact_no' => $userDetails->tp_no
                        ]);
    
                        if (!empty($userDetails->additional_tp_no)) {
                            $userContactNumsModel->insert([
                                'username' => $username,
                                'contact_no' => $userDetails->additional_tp_no
                            ]);
                        }
    
                        // Insert meeting types
                        if (!empty($meetingTypes)) {
                            foreach ($meetingTypes as $meetingTypeId) {
                                $userMeetingTypesModel->insert([
                                    'accessible_user' => $username,
                                    'meeting_type_id' => $meetingTypeId
                                ]);
                            }
                        }
    
                        // Remove the original request
                        $userRequestsModel->deleteRequestById($requestId);
    
                        // Commit transaction
                        $userModel->commit();
    
                        echo json_encode(['success' => true, 'message' => 'Request accepted and user added!']);
                        return;
    
                    } catch (Exception $e) {
                        $userModel->rollBack();
                        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
                        return;
                    }
                }
            }
    
            // ... rest of your decline/remove logic remains the same ...
        }
    
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
    
    
    
     public function viewRequestDetails() {
        // Retrieve the request ID from the URL
        $requestId = $_GET['id'] ?? null;
        
        if ($requestId) {
            // Create an instance of the UserRequests model
            $userRequestsModel = $this->model("user_requests");

            // Fetch the user details based on the request ID
            $userDetails = $userRequestsModel->getRequestById($requestId);

            // Check if data is found
            if ($userDetails) {
                // Pass the user details to the view
                $this->view("admin/viewRequestDetails", [
                    "userDetails" => $userDetails
                ]);
            } else {
                // Handle if no user found with the given ID
                $this->view("admin/404");
            }
        } else {
            // Handle if no ID is passed
            $this->view("admin/404");
        }
    }
    
    public function viewMembers(): void{
        $this->view(name: "admin/viewMembers");
    }

    public function viewMembersList(): void{
        $this->view(name: "admin/viewMembersList");
    }

    public function viewMembersByMeetingType() {
        // Get the meeting type from the URL
        $meetingType = $_GET['meetingType'] ?? null;
    
        if ($meetingType) {
            // Load the required models
            $meetingTypesModel = $this->model("meeting_types");
            $userMeetingTypesModel = $this->model("user_meeting_types");
    
            // Retrieve the type_id for the given meeting type
            $meetingTypeId = $meetingTypesModel->getTypeIdByMeetingType($meetingType);
    
            if ($meetingTypeId) {
                // Fetch the usernames of users belonging to this meeting type
                $usernames = $userMeetingTypesModel->getUsernamesByMeetingTypeId($meetingTypeId);
    
                // Pass the data to the view
                $this->view("admin/viewMembersList", [
                    "meetingType" => $meetingType,
                    "members" => $usernames
                ]);
            } else {
                // Handle invalid meeting type
                $this->view("admin/viewMembersList", [
                    "meetingType" => $meetingType,
                    "members" => []
                ]);
            }
        } else {
            // Handle missing meeting type
            $this->view("admin/viewMembersList", [
                "meetingType" => "Unknown Meeting Type",
                "members" => []
            ]);
        }
    }

public function viewMemberProfile() {
    // Get the user ID from the URL
    $userId = $_GET['id'] ?? null;

    // Check if the user ID is provided
    if ($userId) {
        // Instantiate the User model
        $userModel = new User();

        // Fetch the user data by username or ID
        $userData = $userModel->getUserById($userId);

        if ($userData) {
            // Pass the user data to the view
            $this->view('admin/viewMemberProfile', ['userData' => $userData]);
        } else {
            // Handle the case where no user is found
            echo "User not found.";
        }
    } else {
        // Handle the case where no user ID is provided
        echo "Invalid user ID.";
    }
}


    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"admin"]);
    }

    public function logout() {
        session_start();
        // Destroy all session data
        session_unset();
        session_destroy();
        // Redirect to the login page
        redirect("home");
    }
   
    public function editMemberProfile() {
        // Get the user ID from the URL
        $userId = $_GET['id'] ?? null;
    
        if (!$userId) {
            die("User ID is required.");
        }
    
        // Fetch user details from the database
        $userModel = $this->model("User");
        $userData = $userModel->getUserById($userId);
    
        if (!$userData) {
            die("User not found.");
        }
    
        // Pass the data to the view
        $this->view("admin/editMemberProfile", ['userData' => $userData]);
    }
    
    public function updateMember() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model("User");
            
    
             // Sanitize and validate input
            $username = trim($_POST['username'] ?? '');
            $full_name = trim($_POST['full_name'] ?? '');
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $nic = trim($_POST['nic'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $meetingTypeIds = $_POST['meeting_types'] ?? [];

        if (empty($username) || empty($full_name) || !$email || empty($nic) || empty($role)) {
            echo "Invalid input. Please fill all fields correctly.";
            exit;
        }
            
        $meetingTypeMap = [
            'RHD' => 1,
            'IOD' => 2,
            'SYN' => 3,
            'BOM' => 4,
        ];
        
        $meetingTypeIds = array_map(fn($type) => $meetingTypeMap[$type], $meetingTypeIds);
        
    
            // Update user details
            $userData = [
                'username' => $username,
                'full_name' => $full_name,
                'email' => $email,
                'nic' => $nic,
                'role' => $role
            ];

            $userModel->updateUserByUsername($username, $userData);
            
            if (!empty($phone)) {
                $userModel->updateContactInfo($username, $phone);
            }

            if (!empty($meetingTypeIds)) {
                $userModel->updateMeetingTypes($username, $meetingTypeIds);
            }

            $redirectUrl = ROOT . "/admin/viewMemberProfile?id=" . urlencode($username) . "&status=success&message=" . urlencode("Member updated successfully.");
            header("Location: " . $redirectUrl);
        exit;
        }

    }
    

    public function PastMembers(): void{
        $this->view(name: "admin/PastMembers");
    }

    public function PastMembersList(): void{
        $this->view(name: "admin/PastMembersList");
    }

    public function viewPastMembersByType() {
        // Retrieve the meeting type from the URL
        $meetingType = $_GET['meetingType'] ?? 'Unknown Meeting Type';
    
        // Pass the meeting type to the view
        $this->view("admin/PastMembersList", [
            "meetingType" => $meetingType
        ]);
    }
    
    public function pastMemberProfile(): void{
        $this->view(name: "admin/pastMemberProfile");
    }

    public function addPastMember(): void{
        $this->view(name: "admin/addPastMember");
    }
    public function viewprofile() {
        $this->view("admin/viewprofile");
    }
    
    public function requestchange(){
        $responseStatus = "";
    
        // Handle POST request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $field = $_POST['field'] ?? [];
            $newValue = $_POST['newValue'] ?? [];
            $message = $_POST['message'] ?? "Message not provided";
            $requestchange = new User_edit_requests();
            $requestchange->addUserRequest($field, $newValue, $message);
            $responseStatus = "success";
            
        }
    
        // Pass responseStatus to the view
        $this->view("admin/requestchange", [
            "user" => "admin",
            "responseStatus" => $responseStatus
        ]);
    }

    
    public function vieweditrequests() {
        //changes done by nc
        $requestChangeModel = $this->model("User_edit_requests");
        $requests = $requestChangeModel->find_requests();  // Fetch edit requests

        // Pass the data to the view
        $this->view("admin/vieweditrequests", [
            "requests" => $requests
        ]);
    }


    public function viewsinglerequest(){
        //changes done by nc
        $id=$_GET['id'];
        $requestChangeModel=new User_edit_requests;
        $data=$requestChangeModel->find_request_by_id($id);
        $this->view("admin/viewsinglerequest", $data);
    }

    public function declineRequest() {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
        echo json_encode(['success' => $id]);
        if ($id) {
            $userEditRequests = new User_edit_requests();
            $userEditRequests->deleteRequestById($id);
            echo json_encode(['success' => "Request with ID - $id is Declined"]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid request ID']);
        }
    }

    public function acceptRequest() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        // Extract data from the request
        $username = $data['username'] ?? null;
        $new_fullname = $data['new_fullname'] ?? null;
        $new_nic = $data['new_nic'] ?? null;
        $new_email = $data['new_email'] ?? null;
        $new_tp_no = $data['new_tp_no'] ?? null;
        $new_department = $data['new_department'] ?? null;
        $id = $data['id'] ?? null;
    
        // Prepare the updated data
        $updatedData = [];
    
        if (!empty($new_fullname)) {
            $updatedData['full_name'] = $new_fullname;
        }
    
        if (!empty($new_nic)) {
            $updatedData['nic'] = $new_nic;
        }
    
        if (!empty($new_email)) {
            $updatedData['email'] = $new_email;
        }
    
        // Update the user data
        $userUpdate = new User();
        $updateSuccess = $userUpdate->update($username, $updatedData, 'username');
        $updateAndDelete = new User_edit_requests();
        $updateAndDelete->deleteRequestById($id);
    
    
}
function department(){

    $department = new Department();
    $data['department'] = $department->find_all();

    $this->view('admin/department', $data);
}

function saveDepartment(){

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $department = new Department();

        $data = [
            'dep_name' => $_POST['dep_name'],
            'department_head' => $_POST['department_head'],
            'dep_email' => $_POST['dep_email']
        ];

        if (!empty($_POST['id'])) {
            $department->update($_POST['id'], $data);
        } else {
            $department->insert($data);
        }

        redirect('admin/department');
    }
}

}
