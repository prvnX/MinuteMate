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
        $this->view("secretary/createminute");
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
}
