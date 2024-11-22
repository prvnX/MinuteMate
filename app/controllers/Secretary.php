<?php
class Secretary extends BaseController {

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
                "id" => 11,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 12,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 13,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "x",
                "meeting_type" => "IUD"
            ]
        ];

        $minuteResults = [
            [
            "content_id" => 1,
            "minute_id"=> "M001",
            "meeting_date" => "2024-11-01",
            "meeting_type" => "IUD",
            "content_title" => "Project proposal of 'පැදුර "
            ],
            [
            "content_id" => 2,
            "minute_id"=> "M002",
            "meeting_date" => "2024-11-05",
            "meeting_type" => "RHD",
            "content_title" => "Increasing credits of SE III"
            ],
            [
            "content_id" => 3,
            "minute_id"=> "M003",
            "meeting_date" => "2024-11-10",
            "meeting_type" => "IUD",
            "content_title" => "Reducing marks for 'A'"
            ],
            [
            "content_id" => 4,
            "minute_id"=> "M004",
            "meeting_date" => "2024-01-15",
            "meeting_type" => "Syndicate",
            "content_title" => "Annual Budget Review"
            ],
            [
            "content_id" => 5,
            "minute_id"=> "M005",
            "meeting_date" => "2024-02-20",
            "meeting_type" => "BOM",
            "content_title" => "New Research Proposals"
            ],
            [
            "content_id" => 6,
            "minute_id"=> "M006",
            "meeting_date" => "2024-03-18",
            "meeting_type" => "RHD",
            "content_title" => "Quarterly Performance Review"
            ],
            [
            "content_id" => 7,
            "minute_id"=> "M007",
            "meeting_date" => "2024-04-22",
            "meeting_type" => "Syndicate",
            "content_title" => "Policy Updates"
            ],
            [
            "content_id" => 8,
            "minute_id"=> "M008",
            "meeting_date" => "2024-05-10",
            "meeting_type" => "IUD",
            "content_title" => "Project Milestone Discussion"
            ],
            [
            "content_id" => 9,
            "minute_id"=> "M009",
            "meeting_date" => "2024-06-14",
            "meeting_type" => "RHD",
            "content_title" => "Client Feedback Analysis"
            ],
            [
            "content_id" => 10,
            "minute_id"=> "M010",
            "meeting_date" => "2024-07-19",
            "meeting_type" => "Syndicate",
            "content_title" => "Mid-Year Review"
            ],
            [
            "content_id" => 11,
            "minute_id"=> "M011",
            "meeting_date" => "2024-08-23",
            "meeting_type" => "IUD",
            "content_title" => "New Initiatives Planning"
            ],
            [
            "content_id" => 12,
            "minute_id"=> "M012",
            "meeting_date" => "2024-09-17",
            "meeting_type" => "RHD",
            "content_title" => "Team Building Activities"
            ],
            [
            "content_id" => 13,
            "minute_id"=> "M013",
            "meeting_date" => "2024-10-21",
            "meeting_type" => "Syndicate",
            "content_title" => "Year-End Strategy"
            ],
            [
            "content_id" => 14,
            "minute_id"=> 15,
            "meeting_date" => "2024-12-05",
            "meeting_type" => "IUD",
            "content_title" => "Final Project Review"
            ]
        ];
        $this->view("search",[ "user" => $user, "menuItems" => $menuItems,"memocart" => $memocart, "notification" => $notification,"searchtxt"=>$searchtxt,"memoResults"=>$memoResults,"minuteResults"=>$minuteResults]);
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
        session_start();
        // Destroy all session data
        session_unset();
        session_destroy();
        // Redirect to the login page
        redirect("home");
            }
    public function selectmemo (){
        $this->view("secretary/selectmemo");
    }
    public function selectminute (){
        $this->view("secretary/selectminute");
    }
}
