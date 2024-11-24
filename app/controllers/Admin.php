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
            // Get JSON data from the AJAX request
            $requestData = json_decode(file_get_contents("php://input"), true);
    
            // Extract the data
            $requestId = $requestData['id'] ?? null;
            $action = $requestData['action'] ?? null;
            $meetingTypes = $requestData['meetingTypes'] ?? [];
    
            if ($requestId && $action === 'accept') {
                // Create model instances
                $userRequestsModel = $this->model("user_requests");
                $userModel = $this->model("User");
                $userMeetingTypesModel = $this->model("user_meeting_types");
                $meetingTypesModel = $this->model("meeting_types");
    
                // Fetch the user details from user_requests
                $userDetails = $userRequestsModel->getRequestById($requestId);
    
                if ($userDetails) {
                    // Generate username and default password
                    $username = strtolower(str_replace(' ', '', $userDetails->lec_stu_id));
                    $password = password_hash('defaultpassword', PASSWORD_DEFAULT);
    
                    // Insert user into the `user` table
                    $userInsertResult = $userModel->insert([
                        'username' => $username,
                        'password' => $password,
                        'nic' => $userDetails->nic,
                        'full_name' => $userDetails->full_name,
                        'email' => $userDetails->email,
                        'role' => $userDetails->role,
                        'status' => 'active'
                    ]);
    
                    if ($userInsertResult['success'] === false) {
                        // Handle the case where the username already exists
                        echo json_encode(['success' => false, 'message' => 'Username already exists.']);
                        return;
                    }
    
                    // Insert selected meeting types into `user_meeting_types` table
                    if (!empty($meetingTypes)) {
                        foreach ($meetingTypes as $meetingTypeId) {
                            // Add each meeting type to the user_meeting_types table
                            $userMeetingTypesModel->insertMeetingTypes($username, [$meetingTypeId]);
                        }
                    }
    
                    // Remove the user from the `user_requests` table
                    $userRequestsModel->deleteRequestById($requestId);
    
                    // Return success response
                    echo json_encode(['success' => true]);
                    return;
                }
            } elseif ($requestId && $action === 'decline') {
                $userRequestsModel = $this->model("user_requests"); // Comment: I added this line because in here it showed an error as undefined variable -prvn
                // Handle decline action
                $userRequestsModel->deleteRequestById($requestId);
    
                echo json_encode(['success' => true]);
                return;
            }
        }
    
        // Return error response if request data is invalid
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
        $meetingType = $_GET['meetingType'] ?? 'Unknown Meeting Type';

        // Pass the meeting type to the view
        $this->view("admin/viewMembersList", [
            "meetingType" => $meetingType
        ]);
    }
    
    public function viewMemberProfile(): void{
        $this->view(name: "admin/viewMemberProfile");
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
   
    public function editMemberProfile(): void{
        $this->view(name: "admin/editMemberProfile");
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


    public function vieweditrequests(){
        $this->view("admin/vieweditrequests");
    }
    public function viewsinglerequest(){
         

        $_REQUEST = [
                
            ['userid' => '001',
              'name' => 'Nuwan chanu',
              'newname' => 'Nuwan Chanuka',
              'nic' => '19981234567V',
              'newnic' => '199812345567',
              'additionalnote' => 'I would like to request a change of my user profile name to [New Name]. Please ensure that this change is reflected across all associated platforms and services. If any further information is required to process this request, do not hesitate to contact me.',
  
            ],
          [
              'userid' => '002',
              'name' => 'John Doe',
              'newname' => 'John Dohn',
              'nic' => '19981234567V',
              'newnic' => '19981234565',
              'additionalnote' => 'I would like to request a change of my user profile name to [New Name]. Please ensure that this change is reflected across all associated platforms and services. If any further information is required to process this request, do not hesitate to contact me.',
            ],
          [
              'userid' => '003',
              'name' => 'keneth sil',
              'newname' => 'keneth Doe',
              'nic' => '19981234567V',
              'newnic' => '19981234567',
              'additionalnote' => 'I would like to request a change of my user profile name to [New Name]. Please ensure that this change is reflected across all associated platforms and services. If any further information is required to process this request, do not hesitate to contact me.',
            ],
          ];
              
       
          
              
        $userid = $_REQUEST['1'];
        $currentuser = $_REQUEST['1'];
        

        $data = [
            'id' => $userid['userid'],
            'name' => $currentuser['name'],
            'nic' => $currentuser['nic'],
            'newname' => $currentuser['newname'],
            'newnic' => $currentuser['newnic'],
            'additionalnote' => $currentuser['additionalnote'],
        ];




        $this->view("admin/viewsinglerequest", $data);
    }
    
    
}
