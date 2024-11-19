<?php
class Admin extends Controller {
    public function index() {
        $this->view("admin/dashboard");
    }

    public function viewpendingRequests(): void{
        $this->view(name: "admin/viewpendingRequests");
    }

    public function viewRequestDetails() {
        // Retrieve the request ID from the URL
        $requestId = $_GET['id'] ?? null;
    
        // Add logic to fetch request details based on $requestId
        // For example:
        // $requestDetails = $this->model('RequestModel')->getRequestById($requestId);
    
        // Pass the request details to the view
        $this->view("admin/viewRequestDetails", compact("requestId"));
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
    
}
