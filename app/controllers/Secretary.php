<?php
class Secretary extends Controller {
    public function index() {
        echo "This is Secretary Controller";
        $this->view("secretary/dashboard");

    }
}
echo "This is Secretary Controller";