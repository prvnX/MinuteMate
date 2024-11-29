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
        $user=$_SESSION['userDetails']->username;
        $date = date("Y-m-d");
        if($this->isValidRequest()){
            $meetings = ($this->findMeetingsToEnterMemos($date));

            

            $this->view("studentrep/entermemo", ['meetings' => $meetings]);
        }
        else{
            redirect("login");
        }
       
    }
    public function findMeetingsToEnterMemos($date){
        $user=$_SESSION['userDetails']->username;
        $meeting = new Meeting();
        $meetinglist = $meeting->getmeetingsforuser($date, $user);
        
        return $meetinglist ?: [];
    }

    public function isValidRequest(){
        if(isset($_SESSION['userDetails'])){
            return true;
        }
        else{
            return false;
        }

    }
    public function submitmemo() {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $memoTitle = htmlspecialchars($_POST['memo-subject']);
            $memoContent = htmlspecialchars($_POST['memo-content']);
            $meetingId = htmlspecialchars($_POST['meeting']);
            $submittedBy=$_SESSION['userDetails']->username;

            if(empty($memoTitle)|| empty($memoContent) || empty($meetingId))
            {
                // $_SESSION['flash_error'] = "All fields are required.";
                // redirect("studentrep/entermemo");
                echo "All fields are required";
                return;
            }

            $memoData = [
                'memo_title' => $memoTitle,
                'memo_content' => $memoContent,
                'status' => 'pending', // Set default status
                'submitted_by' => $submittedBy,
                'meeting_id' => $meetingId,
            ];
            $memo = new Memo();
            $memo->insert($memoData);
             $this->view("showsuccessmemo",["user"=>"studentrep"]);
        }
        
    }

    public function viewminutes() {
        $this->view("studentrep/viewminutes");
    }
    public function viewsubmittedmemos() {
        $user=$_SESSION['userDetails']->username;

        if($this->isValidRequest())
        {
            $memo = new Memo();
            $memos = $memo->getMemosByUser($user);
            $this->view("studentrep/viewsubmittedmemos", ['memos'=> $memos]);
        }
        else
        {
            redirect("login");
        }
    }

    public function viewMemoDetails()
    
        {$memo_id=$_GET['memo_id'];
        error_log("Memo ID: " . $memo_id); //debugging
        if($this->isValidRequest())
        {
           if(!$memo_id)
           {
            $_SESSION['flash_error'] = "Memo ID not provided.";
            redirect("studentrep/viewsubmittedmemos");
            return;
           }

            $memoModel = new Memo;
            $memo = $memoModel->getMemoById($memo_id);

            error_log("Memo Data: " . json_encode($memo)); //debugging

            if($memo)
            {
                $this->view("studentrep/viewmemodetails", ['memo'=>$memo]);
            }
            else
            {
                $_SESSION['flash_error'] = "Memo not found.";
                redirect("studentrep/viewmemos");
            }
        }
        else
        {
            redirect("login");
        }
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
    public function viewmemoreports() {
        if(!isset($_GET['memo'])) {
            header("Location: ".ROOT."/studentrep/selectmemo");
        }
        $memoid = $_GET['memo'];
        
        $data = [
            'id' =>$memoid,
            'date' => '2024-11-16',
            'time' => '2:00 PM',
            'status' => 'Approved',
            'linked_memos' => 'Memo #11, Memo #12',
            'author' => 'John Doe'
        ];
    
        $this->view("studentrep/viewmemoreports", $data);
    }
    public function selectmemo (){
        $this->view("studentrep/selectmemo");
    }

}