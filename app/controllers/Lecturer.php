<?php
class Lecturer extends Controller {
    public function index() {
        $this->view("lecturer/dashboard");
    }

    public function search() {
        $this->view("404");
    }

    public function entermemo() {
        $this->view("lecturer/entermemo");
    }
    public function reviewstudentmemos() {
        $this->view("lecturer/reviewstudentmemos");
    }
    public function viewminutes() {
        $this->view("lecturer/viewminutes");
    }
    public function viewsubmittedmemos() {
        $this->view("lecturer/viewsubmittedmemos");
    }
    public function viewmemoreports() {
        $this->view("lecturer/viewmemoreports");
    }
    public function viewminutereports() {
        $this->view("lecturer/viewminutereports");
    }
}
