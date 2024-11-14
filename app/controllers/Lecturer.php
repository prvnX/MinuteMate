<?php
class Lecturer extends Controller {
    public function index() {
        $this->view("lecturer/dashboard");
    }

    public function search() {
        $this->view("404");
    }
    public function notifications() {
        $this->view("lecturer/notifications");
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
