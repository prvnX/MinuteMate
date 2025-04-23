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
    
            $generalMeetingTypes = $requestData['meetingTypes'] ?? [];
            $secretaryMeetingTypes = $requestData['secretaryMeetingType'] ?? [];
            $lecturerMeetingTypes = $requestData['lecturerMeetingType'] ?? [];
    
            $userRequestsModel = $this->model("user_requests");
    
            if ($requestId && $action === 'accept') {
                $userModel = $this->model("User");
                $userRolesModel = $this->model("UserRoles");
                $userMeetingTypesModel = $this->model("user_meeting_types");
                $secretaryMeetingModel = $this->model("secretary_meeting_type");
                $userContactNumsModel = $this->model("UserContactNums");
    
                $userDetails = $userRequestsModel->getRequestById($requestId);
    
                if ($userDetails) {
                    $username = strtolower(str_replace(' ', '', $userDetails->lec_stu_id));
                    $password = password_hash($userDetails->nic, PASSWORD_DEFAULT);
    
                    try {
                        // Start transaction
                        //$userModel->beginTransaction();
    
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
    
                        // Insert roles
                        $roles = array_map('trim', explode(',', $userDetails->role));
                        
                        foreach ($roles as $role) {
                            $userRolesModel->insert([
                                'username' => $username,
                                'role' => $role
                            ]);
                        }
    
                        // Insert contact numbers
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
    
                        // Insert general meeting types
                        if (!empty($generalMeetingTypes)) {
                            $userMeetingTypesModel->insertMeetingTypes($username, $generalMeetingTypes);
                        }
    
                        // Insert lecturer meeting types
                        if (!empty($lecturerMeetingTypes)) {
                            $userMeetingTypesModel->insertMeetingTypes($username, $lecturerMeetingTypes);
                        }
    
                        // If user is a secretary, insert secretary meeting types into both tables
                        if (in_array('secretary', array_map('strtolower', $roles))) {
                            if (!empty($secretaryMeetingTypes)) {
                                // Insert into secretary_meeting_type table
                                $secretaryMeetingModel->insertSecretaryMeetingTypes($username, $secretaryMeetingTypes);
                                // Also insert into user_meeting_types table
                                $userMeetingTypesModel->insertMeetingTypes($username, $secretaryMeetingTypes);
                            }
                        }
    
                        // Remove original request
                        $userRequestsModel->deleteRequestById($requestId);
    
                        // Commit transaction
                        //$userModel->commit();
    
                        echo json_encode(['success' => true, 'message' => 'Request accepted and user added!']);
                        return;
    
                    } catch (Exception $e) {
                        $userModel->rollBack();
                        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
                        return;
                    }
                }
            }
    
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
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

    public function viewPastMembersByType() {
        $meetingType = $_GET['meetingType'] ?? null;
    
        if ($meetingType) {
            // Load the required models
            $meetingTypesModel = $this->model("meeting_types");
            $userMeetingTypesModel = $this->model("user_meeting_types");
    
            // Retrieve the type_id for the given meeting type
            $meetingTypeId = $meetingTypesModel->getTypeIdByMeetingType($meetingType);
    
            if ($meetingTypeId) {
                // Fetch the usernames of users who have been removed for this meeting type
                $removedMembers = $userMeetingTypesModel->getInactiveMembersByMeetingType($meetingTypeId);
    
                // Pass the data to the view
                $this->view("admin/PastMembersList", [
                    "meetingType" => $meetingType,
                    "removedMembers" => $removedMembers
                ]);
            }
        }
    }
    
    public function pastMemberProfile(){
    $userModel = new User();
    $deletedUserModel = new DeletedUsers();

    // Get the user ID (username) from the query parameter
    $userId = $_GET['id'] ?? null;

    if (!$userId) {
        echo "Invalid user ID.";
        return;
    }

    // Fetch user details (from 'user', 'user_roles', 'user_contact_nums', 'user_meeting_types')
    $userData = $userModel->getUserById($userId);

    // Fetch deletion details from 'deleted_users'
    $deletedData = $deletedUserModel->getDeletedInfo($userId);

    // Combine both datasets
    $data = [
        'userData' => $userData,
        'deletedData' => $deletedData,
        'memberId' => $userId
    ];

    // Load the view
    $this->view('admin/pastMemberProfile', $data);
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

public function removeMember() {
    // Ensure the session is started
    $removedBy = $_SESSION['userDetails']->username;  // Using session username

    // Check if admin is logged in
    if (!isset($removedBy)) {
        echo json_encode(['error' => 'Admin not logged in.']);
        exit;
    }

    // Admin is logged in, proceed with removal
    $username = $_POST['username'] ?? '';
    $fullName = $_POST['full_name'] ?? '';
    $reason = $_POST['reason'] ?? '';



    // Other logic for removing member
    $userModel = new User();
    if (!$userModel->getUserById($removedBy)) {
        echo json_encode(['error' => 'Invalid admin username.']);
        exit;
    }

    $deletedModel = new DeletedUsers();
    $deletedModel->insert([
        'username' => $username,
        'full_name' => $fullName,
        'reason' => $reason,
        'removed_by' => $removedBy
    ]);

    $userModel->update($username, ['status' => 'inactive'], 'username');
    echo "<script>
    alert('Member successfully removed.');
    window.location.href = '" . ROOT . "/admin/viewMembers';
</script>";
exit;

    exit;
}

public function reactivateMember() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        // Dynamically load models as needed
        $userModel = $this->model("user");
        $userRolesModel = $this->model("UserRoles");
        $meetingTypesModel = $this->model("meeting_types");
        $userMeetingTypesModel = $this->model("user_meeting_types");
        $deletedUsersModel = $this->model("DeletedUsers");
        $usercontactModel = $this->model("UserContactNums");
        $secretaryMeetingTypesModel = $this->model("secretary_meeting_type");

        $username = $_POST['username'];

        // Use models to update data
        $userModel->updateUserByUsername($username, [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'nic' => $_POST['nic']
        ]);
        

        $usercontactModel->updateContacts(
            $username,
            $_POST['contact_no'],
            $_POST['additional_tp_no'] ?? null
        );
        
        // Update roles
        $roles = $_POST['roles'] ?? [];
        $userRolesModel->updateRoles($username, $roles);

        // Update meeting types for lecturer/student rep
        if (in_array('lecturer', $roles) && isset($_POST['lecturerMeetingType'])) {
            $userMeetingTypesModel->updateMeetingTypes($username, $_POST['lecturerMeetingType']);
        } elseif (in_array('student Representative', $roles) && isset($_POST['meetingType'])) {
            $userMeetingTypesModel->updateMeetingTypes($username, $_POST['meetingType']);
        }

        // Update meeting types for secretary
        if (in_array('secretary', $roles) && isset($_POST['secretaryMeetingType'])) {
            $secretaryMeetingTypesModel->updateMeetingTypes($username, $_POST['secretaryMeetingType']);
        }else{
            $secretaryMeetingTypesModel->deleteMeetingTypesByUsername($username);
        }
        
        // Reactivate the user
        $userModel->reactivateStatus($username);

        $deletedUsersModel->deleteByUsername($username);
        
        echo "<script>alert('Member reactivated successfully.'); window.location.href='your_url_here';</script>";

    }
}







}


