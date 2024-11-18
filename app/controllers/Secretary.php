<?php
class Secretary extends Controller {
    public function index() {
        $this->view("secretary/dashboard");

    }
    public function search() {
        echo "search";
        $this->view("404");
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
    public function selectmemo (){
        $this->view("secretary/selectmemo");
    }
    public function selectminute (){
        $this->view("secretary/selectminute");
    }
}
