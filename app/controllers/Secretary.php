<?php
class Secretary extends Controller {
    public function index() {
        $this->view("secretary/dashboard");

    }
    public function search() {
        $searchtxt=$_POST['search'];
        if($searchtxt=="" || !$searchtxt){
            $this->view("secretary/dashboard");
        }
        else{
             //these are just placeholders
        $user = "secretary";
        $memocart = "memocart-dot";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/secretary",
            $memocart => ROOT."/secretary/memocart",
            $notification => ROOT."/secretary/notifications",
            "profile" => ROOT."/secretary/viewprofile"
        ];
        $memoResults = [
            [
                "id" => 1,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "Alice",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 2,
                "title" => "Meeting Minutes",
                "date" => "2024-11-05",
                "submitted_by" => "Bob",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 3,
                "title" => "Research Report",
                "date" => "2024-11-10",
                "submitted_by" => "Charlie",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 4,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 5,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 6,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "Alice",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 7,
                "title" => "Meeting Minutes",
                "date" => "2024-11-05",
                "submitted_by" => "Bob",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 8,
                "title" => "Research Report",
                "date" => "2024-11-10",
                "submitted_by" => "Charlie",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 9,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 10,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ]
        ];
        $this->view("search",[ "user" => $user, "menuItems" => $menuItems,"memocart" => $memocart, "notification" => $notification,"searchtxt"=>$searchtxt,"memoResults"=>$memoResults]);
        }
    }
    public function entermemo() {
        $this->view("secretary/entermemo");
    }
    public function createminute() {
        if(!isset($_GET['meeting'])) {
            header("Location: ".ROOT."/secretary/selectmeeting");
        }
        $meetingId = $_GET['meeting'];
        $this->view("secretary/createminute", ['meetingId' => $meetingId]);
    }
    public function notifications() {
        //these are just placeholders
        $user = "secretary";
        $memocart = "memocart-dot";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/secretary",
            $memocart => ROOT."/secretary/memocart",
            $notification => ROOT."/secretary/notifications",
            "profile" => ROOT."/secretary/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"memocart" => $memocart, "notification" => $notification]);
    }
    public function selectmeeting() { //this is the page where the secretary selects the meeting to create a minute for
        $this->view("secretary/selectmeeting");
    }
    public function viewminutes() {
        $this->view("secretary/viewminutes");
    }
    public function viewmemos() {
        $this->view("secretary/viewmemos");
    }
    public function viewmemoreports() {
        if(!isset($_GET['memo'])) {
            header("Location: ".ROOT."/secretary/selectmemo");
        }
        $memoid = $_GET['memo'];
        
        $data = [
            'id' =>$memoid,
            'date' => '2024-11-16',
            'time' => '2:00 PM',
            'status' => 'Approved',
            'linked_memos' => 'Memo #11, Memo #12',
            'author' => 'John Doe'
        ];
    
        $this->view("secretary/viewmemoreports", $data);
    }
    
    public function viewminutereports() {
        if(!isset($_GET['minute'])) {
            header("Location: ".ROOT."/secretary/selectminute");
        }
        $memoid = $_GET['minute'];
        $data = [
            'date' => '2024-11-16',
            'time' => '10:00 AM',
            'meeting_type' => 'Team Meeting',
            'meeting_minute' => 'Discussed project updates and next steps.',
            'linked_minutes' => 'Minute #14, Minute #15',
            'linked_memos' => 'Memo #12',
            'recording' => 'https://example.com/recording.mp4',
            'attendees' => 'Alice, Bob, Charlie'
        ];
        $this->view("secretary/viewminutereports", $data);
    }
    

    public function submitmemo() {
        // $content=$_POST['memo-content'];
        $memosuccess = false;
        $memoid = 1;
        if($memosuccess) {
            $this->view("showsuccessmemo",["user"=>"secretary","memoid"=>$memoid]);
    }
    else {
        $this->view("showunsuccessmemo",["user"=>"secretary"]);
    }
    }
    public function submitminute() {
        $memosuccess = false;
        $minuteid = 1;
        if($memosuccess) {
            $this->view("showsuccessminute",["user"=>"secretary","minuteid"=>$minuteid]);
    }
    else {
        $this->view("showunsuccessminute",["user"=>"secretary"]);
    }
    }
    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"Secretary"]);
    }

    public function viewprofile() {
        $this->view("secretary/viewprofile");
    }
    public function logout() {
        $this->view("logout",[ "user" =>"Secretary"]);
    }
    public function selectmemo (){
        $this->view("secretary/selectmemo");
    }
    public function selectminute (){
        $this->view("secretary/selectminute");
    }
}
