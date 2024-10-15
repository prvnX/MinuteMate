<?php
class Studentrep extends Controller {
    public function index() {
        echo "This is studentrep Controller";
        $this->view("studentrep/dashboard");

    }
}