<?php
class Studentrep extends BaseController {
    

    public function index() {
        date_default_timezone_set('Asia/Colombo');
        $meeting = new Meeting();
        $lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));
        $today=date("Y-m-d");
        $meetingsinweek = $meeting->getMeetingsInWeek($today, $lastDayOfWeek, $_SESSION['userDetails']->username);
        if($meetingsinweek){
            $meetingsinweek = count($meetingsinweek);
        }
        else{
            $meetingsinweek = 0;
        }
        $this->view("studentrep/dashboard",["meetingsinweek" => $meetingsinweek]);
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
        $user = $_SESSION['userDetails']->username;
        $minute = new Minute();
        $minuteList = $minute->MinuteListByUser($user);
        $this->view("studentrep/viewminutes", ['minutes' => $minuteList]);
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
    private function isRestrict($username,$contentID){
        $restrictions=new Content_restrictions();
        $res_status=$restrictions->checkRestrictions($username,$contentID);
        if($res_status[0]->is_restricted==1){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function viewminute(){
        $user=$_SESSION['userDetails']->username;
        $minuteID=$_GET['minuteID'];

        // get minute details
        $minute = new Minute();
        $Meeting_attendence=new Meeting_attendence();
        $Agenda= new Agenda();
        $content= new Content();
        $memo_discussed= new Memo_discussed_meetings();
        $linkedMinutes=new Minutes_linked();
        $linkedMedia=new Linked_Media();
        $minuteDetails = $minute->getMinuteDetails($minuteID);
        $contentrestrict=false;
        
        if(!$minuteDetails) {
            $this->view('minutenotfound');           
            return;
        }
        else{
        $user_meetings=$_SESSION['meetingTypes'];
        $minuteType=$minuteDetails[0]->meeting_type;
        for($i=0;$i<count($user_meetings);$i++){
            if($user_meetings[$i]=="IOD"){
                $user_meetings[$i]="IUD";
            }
            $user_meetings[$i]=strtolower($user_meetings[$i]);
            }

        if(!in_array($minuteType,$user_meetings)){

            $this->view('accessdenied');
            return;
        }
        else{
        // show($_SESSION);
        $user_accessible_content=[];
        $linked_minutes=[];
        $isContentRestricted=false;
        $attendence = $Meeting_attendence->getAttendees($minuteDetails[0]->meeting_id);
        $agendaItems=$Agenda->selectandproject('agenda_item',['meeting_id'=>$minuteDetails[0]->meeting_id]);
        $contentDetails=$content->select_all(['minute_id'=>$minuteID]);
        $discussed_memos=$memo_discussed->getMemos($minuteDetails[0]->meeting_id);
        $linkedMinutes=$linkedMinutes->getLinkedMinutes($minuteID);
        $linkedMediaFiles=$linkedMedia->select_all(['minute_id'=>$minuteID]);

        if($linkedMinutes!= null){
            if(count($linkedMinutes)>0){
                foreach($linkedMinutes as $linkedMinute){
                    $linkedMinuteID=$linkedMinute->minutes_linked;
                    $otherMinuteID=$linkedMinute->minute_id;
                    if($linkedMinuteID!=$minuteID){
                        $linked_minutes[]=$linkedMinuteID;
                    }
                    else if($otherMinuteID!=$minuteID){
                        $linked_minutes[]=$otherMinuteID;
                    }
                }
            }

        }
        $linked_minutes=array_unique($linked_minutes);

        foreach ($contentDetails as $contentDetail) {
            $contentID=$contentDetail->content_id;
            if($this->isRestrict($user,$contentID)){
                $isContentRestricted=true;
               continue;
            }else{
                $user_accessible_content[]=$contentDetail;
            }
        }

        // show($contentDetails);
        $minuteDetails[0]->attendence = $attendence;
        $minuteDetails[0]->agendaItems = $agendaItems;
        $minuteDetails[0]->discussed_memos = $discussed_memos;
        $minuteDetails[0]->linked_minutes = $linked_minutes;
        $minuteDetails[0]->linkedMediaFiles = $linkedMediaFiles;
        // show($minuteDetails[0]);
        //  show($minuteDetails);
        $this->view("studentrep/viewminute",['user'=>$user,'minuteID'=>$minuteID,'minuteDetails'=>$minuteDetails,'contents'=>$user_accessible_content,'isContentRestricted'=>$isContentRestricted]);
        }
    }





    }

}