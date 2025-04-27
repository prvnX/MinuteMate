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
            $declineReason = $requestData['declineReason'] ?? '';
    
            $userRequestsModel = $this->model("user_requests");

            $userDetails = $userRequestsModel->getRequestById($requestId);
    
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
    
                        $roles = array_map('trim', explode(',', $userDetails->role));
                        
                        foreach ($roles as $role) {
                            $userRolesModel->insert([
                                'username' => $username,
                                'role' => $role
                            ]);
                        }
    
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
                              
                                $secretaryMeetingModel->insertSecretaryMeetingTypes($username, $secretaryMeetingTypes);
                               
                                $userMeetingTypesModel->insertMeetingTypes($username, $secretaryMeetingTypes);
                            }
                        }
    
                        // Remove original request
                        $userRequestsModel->deleteRequestById($requestId);
    
                        $mail = new Mail();
                        $mail->sendAcceptanceEmail($userDetails->email, $userDetails->full_name);
    
                        echo json_encode(['success' => true, 'message' => 'Request accepted and user added!']);
                        return;
    
                    } catch (Exception $e) {
                        $userModel->rollBack();
                        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
                        return;
                    }
                }
            }else{
                // Update the request status to 'declined'
                $result = $userRequestsModel->updateRequestStatusById($requestId, 'declined');
                if ($result !== false) {
                    header('Content-Type: application/json');
                    $mail = new Mail();
                    $mail->sendDeclineEmail($userDetails->email, $userDetails->full_name, $declineReason);
                    echo json_encode(['success' => true, 'message' => 'Request declined.']);
                 return;
                } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to decline the request.']);
                 return;
                }
            }
            
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
                }
    }
    
    
    
    public function viewRequestDetails() {
        $requestId = $_GET['id'] ?? null;
        
        if ($requestId) {
            $userRequestsModel = $this->model("user_requests");

            $userDetails = $userRequestsModel->getRequestById($requestId);

            if ($userDetails) {
                $this->view("admin/viewRequestDetails", [
                    "userDetails" => $userDetails
                ]);
            } else {
                $this->view("admin/404");
            }
        } else {
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
        $meetingType = $_GET['meetingType'] ?? null;
    
        if ($meetingType) {
            $meetingTypesModel = $this->model("meeting_types");
            $userMeetingTypesModel = $this->model("user_meeting_types");
    
            $meetingTypeId = $meetingTypesModel->getTypeIdByMeetingType($meetingType);
    
            if ($meetingTypeId) {

                $usernames = $userMeetingTypesModel->getUsernamesByMeetingTypeId($meetingTypeId);
    
                $this->view("admin/viewMembersList", [
                    "meetingType" => $meetingType,
                    "members" => $usernames
                ]);
            } else {
                $this->view("admin/viewMembersList", [
                    "meetingType" => $meetingType,
                    "members" => []
                ]);
            }
        } else {
            $this->view("admin/viewMembersList", [
                "meetingType" => "Unknown Meeting Type",
                "members" => []
            ]);
        }
    }

public function viewMemberProfile() {
    
    $userId = $_GET['id'] ?? null;

 
    if ($userId) {
        
        $userModel = new User();

        $userData = $userModel->getUserById($userId);

        if ($userData) {
            $this->view('admin/viewMemberProfile', ['userData' => $userData]);
        } else {
            echo "User not found.";
        }
    } else {
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
   
    // public function editMemberProfile() {
    //     // Get the user ID from the URL
    //     $userId = $_GET['id'] ?? null;
    
    //     if (!$userId) {
    //         die("User ID is required.");
    //     }
    
    //     // Fetch user details from the database
    //     $userModel = $this->model("User");
    //     $userData = $userModel->getUserById($userId);
    
    //     if (!$userData) {
    //         die("User not found.");
    //     }
    
    //     // Pass the data to the view
    //     $this->view("admin/editMemberProfile", ['userData' => $userData]);
    // }
    
    // public function updateMember() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $userModel = $this->model("User");
            
    
    //          // Sanitize and validate input
    //         $username = trim($_POST['username'] ?? '');
    //         $full_name = trim($_POST['full_name'] ?? '');
    //         $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    //         $nic = trim($_POST['nic'] ?? '');
    //         $role = trim($_POST['role'] ?? '');
    //         $phone = trim($_POST['phone'] ?? '');
    //         $meetingTypeIds = $_POST['meeting_types'] ?? [];

    //     if (empty($username) || empty($full_name) || !$email || empty($nic) || empty($role)) {
    //         echo "Invalid input. Please fill all fields correctly.";
    //         exit;
    //     }
            
    //     $meetingTypeMap = [
    //         'RHD' => 1,
    //         'IOD' => 2,
    //         'SYN' => 3,
    //         'BOM' => 4,
    //     ];
        
    //     $meetingTypeIds = array_map(fn($type) => $meetingTypeMap[$type], $meetingTypeIds);
        
    
    //         // Update user details
    //         $userData = [
    //             'username' => $username,
    //             'full_name' => $full_name,
    //             'email' => $email,
    //             'nic' => $nic,
    //             'role' => $role
    //         ];

    //         $userModel->updateUserByUsername($username, $userData);
            
    //         if (!empty($phone)) {
    //             $userModel->updateContactInfo($username, $phone);
    //         }

    //         if (!empty($meetingTypeIds)) {
    //             $userModel->updateMeetingTypes($username, $meetingTypeIds);
    //         }

    //         $redirectUrl = ROOT . "/admin/viewMemberProfile?id=" . urlencode($username) . "&status=success&message=" . urlencode("Member updated successfully.");
    //         header("Location: " . $redirectUrl);
    //     exit;
    //     }

    // }
    

    public function PastMembers(): void{
        $this->view(name: "admin/PastMembers");
    }

    public function viewPastMembersByType() {
        $meetingType = $_GET['meetingType'] ?? null;
    
        if ($meetingType) {
            
            $meetingTypesModel = $this->model("meeting_types");
            $userMeetingTypesModel = $this->model("user_meeting_types");
    
            $meetingTypeId = $meetingTypesModel->getTypeIdByMeetingType($meetingType);
    
            if ($meetingTypeId) {

                $removedMembers = $userMeetingTypesModel->getInactiveMembersByMeetingType($meetingTypeId);

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

    $userId = $_GET['id'] ?? null;

    if (!$userId) {
        echo "Invalid user ID.";
        return;
    }

    $userData = $userModel->getUserById($userId);

    $deletedData = $deletedUserModel->getDeletedInfo($userId);

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
        $errors = [];
            $success = false;
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    
                    $users = new User();
                    $username = $_SESSION['userDetails']->username;
                    $currentPassword = $_POST['current_password'];
                    $newPassword = $_POST['new_password'];
                    $confirmPassword = $_POST['confirm_password'];
        
                    $storedPasswordData = $users->getHashedPassword($username);
                    $storedPassword = $storedPasswordData[0] ->password ?? null;
        
                  
                    if(!password_verify($currentPassword,$storedPassword))
                    {
                        $errors[] = 'Current Password is not correct';
                    }
        
                    if($newPassword !== $confirmPassword)
                    {
                        $errors[] = 'New password and confirmation do not match';
                    }
        
                    //checking if the password has the required strength
                    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
                        $errors[] = "New password does not meet the required strength.";
                    }
                    if(empty($errors))
                    {
                        $newHashed = password_hash($newPassword , PASSWORD_DEFAULT);
                        $users->updatePassword($username, $newHashed);
                        $success = true;
                    }
                    echo json_encode([
                        'success' => $success,
                        'errors' => $errors,
                        'state'=> password_verify($currentPassword,$storedPassword)
                    ]);
                    exit;
                }
        $this->view("admin/viewprofile");
    }

    public function updateprofile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'nic' => $_POST['nic'],
            ];

        $nic = $data['nic'];
        if (!preg_match('/^(\d{12}|\d{10}[vV])$/', $nic)) {
            echo "<script>
                alert('Invalid NIC. NIC should be either 12 digits or 10 digits followed by V/v.');
                window.history.back();
            </script>";
            exit;
        }
            
            $userModel = $this->model("user");

            $username = $_SESSION['userDetails']->username;
    
            $userModel->updateUserByUsername($username, $data);
    
            $_SESSION['userDetails']->full_name = $data['full_name'];
            $_SESSION['userDetails']->email = $data['email'];
            $_SESSION['userDetails']->nic = $data['nic'];
    
            echo "<script>alert('Profile updated successfully!'); window.location.href='" . ROOT . "/admin/viewprofile';</script>";
            exit;
        }
    }
    
    
    public function requestchange(){
        $responseStatus = "";
  
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $field = $_POST['field'] ?? [];
            $newValue = $_POST['newValue'] ?? [];
            $message = $_POST['message'] ?? "Message not provided";
            $requestchange = new User_edit_requests();
            $requestchange->addUserRequest($field, $newValue, $message);
            $responseStatus = "success";
            
        }
    
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
        $contact_no = new UserContactNums();
        $data['contact_no'] = $contact_no->getContactByUsername($data[0]->username);
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
  
        $username = $data['username'] ?? null;
        $new_fullname = $data['new_fullname'] ?? null;
        $new_nic = $data['new_nic'] ?? null;
        $new_email = $data['new_email'] ?? null;
        $new_tp_no = $data['new_tp_no'] ?? null;
        $new_department = $data['new_department'] ?? null;
        $id = $data['id'] ?? null;
    
        // Prepare the updated data
        $updatedData = [];

    if (!empty($new_fullname)) $updatedData['full_name'] = $new_fullname;
    if (!empty($new_nic)) $updatedData['nic'] = $new_nic;
    if (!empty($new_email)) $updatedData['email'] = $new_email;

   
    $userUpdate = new User();

   

    // Update contact number in related table
    if(!($new_nic==null && $new_email==null && $new_fullname==null)){
        $userUpdate->update($username, $updatedData, 'username');
        
    }
       
   

    if (!empty($new_tp_no)) {
        $contactModel = new UserContactNums();
        $contactModel->updateContactNumbers($username, $new_tp_no);  // You'll need to implement this
    }

    $updateAndDelete = new User_edit_requests();
    $updateAndDelete->deleteRequestById($id);

    
}

function department(){

    $department = new Department();
    $data['department'] = $department->find_all();

    $this->view('admin/department', $data);
}

function saveDepartment() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $department = new Department();

        $data = [
            'dep_name' => $_POST['dep_name'],
            'department_head' => $_POST['department_head'],
            'dep_email' => $_POST['dep_email']
        ];


        $userModel = $this->model("user");

        
        if (!filter_var($_POST['dep_email'], FILTER_VALIDATE_EMAIL)) {
            $this->view("admin/department", [
                "user" => "admin",
                "errorMessage" => "Invalid email format for department email!",
                "formData" => $_POST,
                "department" => $department->findAll()
            ]);
            return;
        }

        
        if (!$userModel->usernameExists($_POST['department_head'])) {
            $this->view("admin/department", [
                "user" => "admin",
                "errorMessage" => "Department head \"{$_POST['department_head']}\" does not exist!",
                "formData" => $_POST,
                "department" => $department->findAll()
            ]);
            return;
        }
        
        if (!empty($_POST['id'])) {
            $department->update($_POST['id'], $data);
        } else {
            $department->insert($data);
        }

        redirect('admin/department');
    }
}


public function removeMember() {
  
    $removedBy = $_SESSION['userDetails']->username;  

    if (!isset($removedBy)) {
        echo json_encode(['error' => 'Admin not logged in.']);
        exit;
    }

    $username = $_POST['username'] ?? '';
    $fullName = $_POST['full_name'] ?? '';
    $reason = $_POST['reason'] ?? '';


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
}

public function reactivateMember() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $errors = [];

        $userModel = $this->model("user");
        $userRolesModel = $this->model("UserRoles");
        $meetingTypesModel = $this->model("meeting_types");
        $userMeetingTypesModel = $this->model("user_meeting_types");
        $deletedUsersModel = $this->model("DeletedUsers");
        $usercontactModel = $this->model("UserContactNums");
        $secretaryMeetingTypesModel = $this->model("secretary_meeting_type");

        $username = $_POST['username'];

         $email = $_POST['email'];
         $nic = $_POST['nic'];
         $contact_no = $_POST['contact_no'];
         $additional_tp_no = $_POST['additional_tp_no'] ?? null;

         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view("adminwarning",['title'=>"Invalid Email",'message'=>"Ensure that valid email is entered."]);
             return; 
         }
 
         if (!preg_match('/^\d{12}$|^\d{10}[Vv]$/', $nic)) {
            $this->view("adminwarning",['title'=>"Invalid NIC",'message'=>"Ensure that valid NIC with 12 digits or 10 digits+v is entered."]);
             return;
         }
 
         if (strlen($contact_no) != 10) {
            $this->view("adminwarning",['title'=>"Invalid Contact number",'message'=>"Ensure that contact number is exactly 10 digits is entered."]);
            return;
         }

         if ($additional_tp_no && strlen($additional_tp_no) != 10) {
            $this->view("adminwarning",['title'=>"Invalid Contact number",'message'=>"Ensure that the additional contact number is exactly 10 digits is entered."]);
             return;
         }

         if (!empty($errors)) {

            $this->view('admin/pastMemberProfile', ['errors' => $errors, 'userData' => $_POST]);
            return;
        }
        
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

        $roles = $_POST['roles'] ?? [];
        $userRolesModel->updateRoles($username, $roles);

 
        if (in_array('lecturer', $roles) && isset($_POST['lecturerMeetingType'])) {
            $userMeetingTypesModel->updateMeetingTypes($username, $_POST['lecturerMeetingType']);
        } elseif (in_array('student', $roles) && isset($_POST['meetingType'])) {
            $userMeetingTypesModel->updateMeetingTypes($username, $_POST['meetingType']);
        }

        if (in_array('secretary', $roles) && isset($_POST['secretaryMeetingType'])) {
            $secretaryMeetingTypesModel->updateMeetingTypes($username, $_POST['secretaryMeetingType']);
        }else{
            $secretaryMeetingTypesModel->deleteMeetingTypesByUsername($username);
        }
        

        $userModel->reactivateStatus($username);

        $deletedUsersModel->deleteByUsername($username);
        
        echo "<script>alert('Member reactivated successfully.'); window.location.href='your_url_here';</script>";

    }
}







}


