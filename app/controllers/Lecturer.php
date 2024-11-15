<?php
class Lecturer extends Controller {
    public function index() {
        $this->view("lecturer/dashboard");
    }

    public function search() {
        $this->view("404");
    }
    public function notifications() {
        //these are just placeholders
        $user = "lecturer";
        $memocart = "memocart";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/lecturer",
            $notification => ROOT."/lecturer/notifications",
            "profile" => ROOT."/lecturer/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"notification" => $notification]);
    }
    public function viewprofile() {
        $this->view("lecturer/viewprofile");
    }
    public function entermemo() {
        $this->view("lecturer/entermemo");
    }
    public function submitmemo() {
        $memosuccess = false;
        $memoid = 1;
        if($memosuccess) {
            $this->view("showsuccessmemo",["user"=>"lecturer","memoid"=>$memoid]);
        }
        else {
            $this->view("showunsuccessmemo",["user"=>"lecturer"]);
        }
    }

}
