<?php
class Studentrep extends Controller {
    public function index() {
         
        $this->view("studentrep/dashboard");

    }
    public function search() {
        echo "search";
        $this->view("404");
    }
    public function entermemo() {
        $this->view("studentrep/entermemo");
    }
    public function submitmemo() {
        $memosuccess = true;
        $memoid = 1;
        if($memosuccess) {
            $this->view("showsuccessmemo",["user"=>"studentrep","memoid"=>$memoid]);
        }
        else {
            $this->view("showunsuccessmemo",["user"=>"studentrep"]);
        }
    }

    public function viewminutes() {
        $this->view("studentrep/viewminutes");
    }
    public function viewsubmittedmemos() {
        $this->view("studentrep/viewsubmittedmemos");
    }
    public function notifications() {
        //these are just placeholders
        $user = "studentrep";
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/studentrep",
            $notification => ROOT."/studentrep/notifications",
            "profile" => ROOT."/studentrep/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"notification" => $notification]);
    }
    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"studentrep"]);
    }

    public function viewprofile(){
        $this->view("studentrep/viewprofile");
    }
}