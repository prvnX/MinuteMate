<?php
class Studentrep extends BaseController {
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

    public function viewminutes() {
        $this->view("studentrep/viewminutes");
    }
    public function viewsubmittedmemos() {
        $this->view("studentrep/viewsubmittedmemos");
    }
    public function notifications() {
        //these are just placeholders
        $user = "studentrep";
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/studentrep",
            $notification => ROOT."/studentrep/notifications",
            "profile" => ROOT."/studentrep/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"notification" => $notification]);

    }

    public function viewprofile(){
        $this->view("studentrep/viewprofile");
    }
    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"studentrep"]);
    }


    // public function requestchange() {
    //     $this->view("studentrep/requestchange");
    
    // }

    public function requestchange(){
        $responseStatus = "";
    
        // Handle POST request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $field = $_POST['field'] ?? [];
            $newValue = $_POST['newValue'] ?? [];
            $message = $_POST['message'] ?? "Message not provided";
            $requestchange = new User_edit_requests();
            $requestchange->addUserRequest($field, $newValue, $message);
            $responseStatus = "success";
            
        }
    
        // Pass responseStatus to the view
        $this->view("studentrep/requestchange", [
            "user" => "studentrep",
            "responseStatus" => $responseStatus
        ]);
    }



    public function logout() {
        session_start();
        // Destroy all session data
        session_unset();
        session_destroy();
        // Redirect to the login page
        redirect("home");
    }

}