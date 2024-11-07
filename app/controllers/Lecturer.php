<?php
class Lecturer extends Controller {
    public function index() {
        $this->view("lecturer/dashboard");
    }

    public function search() {
        $this->view("404");
    }
}
