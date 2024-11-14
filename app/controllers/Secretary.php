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
        $this->view("secretary/notifications");
    }
    public function selectmeeting() { //this is the page where the secretary selects the meeting to create a minute for
        $this->view("secretary/selectmeeting");
    }
    public function viewminutes() {
        $this->view("secretary/viewminutes");
    }
    public function viewsubmittedmemos() {
        $this->view("secretary/viewsubmittedmemos");
    }
    public function viewmemoreports() {
        $this->view("secretary/viewmemoreports");
    }
    public function viewminutereports() {
        $this->view("secretary/viewminutereports");
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
}
