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

}