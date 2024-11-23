<?php
class Admin extends Controller {
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
        $this->view("logout",[ "user" =>"admin"]);
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


}
