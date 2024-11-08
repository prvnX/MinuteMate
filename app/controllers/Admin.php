<?php
class Admin extends Controller {
    public function index() {
        $this->view("admin/dashboard");
    }

    public function search() {
        $this->view("404");
    }

    public function viewpendingRequests() {
        $this->view("admin/viewpendingRequests");
    }
    public function viewMembers() {
        $this->view("admin/viewMembers");
    }
    public function RemoveMembers() {
        $this->view("admin/RemoveMembers");
    }
}
