<?php
class Admin extends Controller {
    public function index() {
        echo "This is admin Controller";
              $this->view("admin/dashboard");
    }
}