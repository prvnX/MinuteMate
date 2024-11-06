<?php
class Secretary extends Controller {
    public function index() {
        $this->view("secretary/dashboard");

    }
    public function search() {
        echo "search";
        $this->view("404");
    }
}
