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
        $this->view("secretary/viewmemoreports");
    }
    public function viewminutereports() {
        $this->view("secretary/viewminutereports");
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

}
