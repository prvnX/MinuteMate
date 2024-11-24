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
