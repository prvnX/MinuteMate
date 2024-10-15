<?php
class Lecturer extends Controller {
    public function index() {
        echo "This is lecturer Controller";
        $this->view("lecturer/dashboard");

    }
}
