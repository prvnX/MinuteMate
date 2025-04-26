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
        
        $searchtxt=$_POST['search'];
        if($searchtxt=="" || !$searchtxt){
            $this->view("studentrep/dashboard");
        }
        else{
            $searchModel=new Search();
            $users=new User();
            $minuteResults=$searchModel->minute_search($searchtxt);
            $memoResults=$searchModel->memo_search($searchtxt);
            $names=$users->getUserNameList();
            $memosSubmitters=[];
            foreach($names as $name){
                $memosSubmitters[]=$name->full_name;
            }
                
            $this->view("search",["searchtxt"=>$searchtxt,"minuteResults"=>$minuteResults,"memoResults"=>$memoResults,'memosSubmitters'=>$memosSubmitters]);
        }
    }
    public function entermemo() {
        $user=$_SESSION['userDetails']->username;
        $date = date("Y-m-d");
        $meeting = new Meeting();
        $users = new User();
        if($this->isValidRequest()){
            $meetings = ($this->findMeetingsToEnterMemos($date));
            
            if (isset($_POST['meeting_id'])) {
                $meetingId = $_POST['meeting_id'];
                // Get the meeting type from the selected meeting ID (or assume you have it in your $meetings array)
                $meetingType = $meeting->getMeetingTypeById($meetingId);
                
                // Fetch the users for the selected meeting type
                $users = $users->getUsersForMeetingType($meetingType[0]->id);
    
                // Pass the meeting users to the view
                $this->view("studentrep/entermemo", [
                    'meetings' => $meetings,
                    'selectedMeetingUsers' => $users
                ]);
            } else {
                // If no meeting is selected, just show the form with the meetings
                $this->view("studentrep/entermemo", ['meetings' => $meetings]);
            }
        }
        else{
            redirect("login");
        }
       
    }

    public function fetchUsersByMeeting()
{
    if ($this->isValidRequest() && isset($_POST['meeting_id'])) {
        $meeting = new Meeting();
        $user = new User();

        $meetingId = $_POST['meeting_id'];
        $meetingType = $meeting->getMeetingTypeById($meetingId);

        if (!empty($meetingType)) {
            $users = $user->getUsersForMeetingType($meetingType[0]->type_id);
            echo json_encode($users);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
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
            $toBeReviewedBy = htmlspecialchars($_POST['Reviewedby']);
            $submittedBy=$_SESSION['userDetails']->username;
            $meetingId = htmlspecialchars($_POST['meeting']);
            if(empty($memoTitle)|| empty($memoContent))
            {
                // $_SESSION['flash_error'] = "All fields are required.";
                // redirect("studentrep/entermemo");
                echo "All fields are required";
                return;
            }

            $memoData = [
                'memo_title' => $memoTitle,
                'memo_content' => $memoContent,
                'submitted_by' => $submittedBy,
               'reviewed_by' => $toBeReviewedBy,
               'meeting_id' => $meetingId
            ];
          //  print_r($memoData);
            $reviewMemo = new ReviewMemo();
            $reviewMemo->insertx($memoData);

            $this->view("showsuccessmemo",["user"=>"studentrep"]);
        }
        else{
            $this->view("showunsuccessmememo", ["user"=> "studentrep"]);
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
            $memos = $memo->getMemosByUser($user) ?? [];
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
    // my part
    public function viewprofile(){
        $userModel = new User();
        $username = $_SESSION['userDetails']->username;
        $userDetails = $userModel-> select_one(['username' => $username]);
        $contact_no = new UserContactNums();
        $contactNumbers = $contact_no->select_all(['username' => $username]);
        $role = new UserRoles();
        $userRole = $role->select_one(['username' => $username]);
        $userMeeting = new user_meeting_types();
        $userMeetingTypes = $userMeeting->getUserMeetingTypes($username);
        $MeetingAtt = new Meeting_attendence();
        $attendenceMeetings = $MeetingAtt->selectandproject('Count(*) as attendence_count',['attendee'=>$username]);
            $errors = [];
        $success = false;
            if($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                
                $users = new User();
                $username = $_SESSION['userDetails']->username;
                $currentPassword = $_POST['current_password'];
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];
    
                $storedPasswordData = $users->getHashedPassword($username);
                $storedPassword = $storedPasswordData[0] ->password ?? null;
    
              
                if(!password_verify($currentPassword,$storedPassword))
                {
                    $errors[] = 'Current Password is not correct';
                }
    
                if($newPassword !== $confirmPassword)
                {
                    $errors[] = 'New password and confirmation do not match';
                }
    
                //checking if the password has the required strength
                if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
                    $errors[] = "New password does not meet the required strength.";
                }
                if(empty($errors))
                {
                    $newHashed = password_hash($newPassword , PASSWORD_DEFAULT);
                    $users->updatePassword($username, $newHashed);
                    $success = true;
                }
                echo json_encode([
                    'success' => $success,
                    'errors' => $errors,
                    'state'=> password_verify($currentPassword,$storedPassword)
                ]);
                exit;
            }
            

            $this->view("studentrep/viewprofile", ['userDetails' => $userDetails, 'contactNumbers' => $contactNumbers, 'userRole' => $userRole, 'userMeetingTypes' => $userMeetingTypes,'attendenceMeetings'=>$attendenceMeetings]);
         

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
    

    public function viewmemoreport() {
        if (!isset($_GET['memo'])) {
            header("Location: " . ROOT . "/studentrep/selectmemo");
            exit;
        }

        $memoId = $_GET['memo'];
        $memo = new Memo();
        $memoDetails = $memo->getMemoDetails($memoId);
      

        if (!$memoDetails) {
            $this->view("memoreportnotfound");
            return;
        }

        $this->view("studentrep/viewmemoreports", [
            'memoDetails' => $memoDetails,
            'user' => $_SESSION['userDetails']->username
        ]);
    }


    public function selectmemo() { //this is the page where the studentrep selects the memo to view details
        $memo = new Memo();
        $memos = $memo->getMemos($_SESSION['userDetails']->username, date("Y-m-d"));
    
        
            $this->view("studentrep/selectmemo", ['memos' => $memos]);
         
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
        $content= new Content();
        $memo_discussed= new Memo_discussed_meetings();
        $linkedMinutes=new Minutes_linked();
        $linkedMedia=new Linked_Media();
        $approved_minutes=new Approved_minutes();
        $recorrect_minute=new Recorrect_Minutes();
        $cfm=new Content_forward_meeting();


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
        $approved_recorrect_Meeting=[];
        $user_accessible_content=[];
        $linked_minutes=[];
        $linked_content_minutes=[];
        $isContentRestricted=false;
        $contentDetails=$content->select_all(['minute_id'=>$minuteID]);
        $discussed_memos=$memo_discussed->getMemos($minuteDetails[0]->meeting_id);
        $linkedMinutes=$linkedMinutes->getLinkedMinutes($minuteID);
        $linkedMediaFiles=$linkedMedia->select_all(['minute_id'=>$minuteID]);
        $approveStatus=$minute->selectandproject('is_approved,is_recorrect',['Minute_ID'=>$minuteID]);
        $previousMinute=$minute->getPreviousMinute($minuteDetails[0]->end_time,$minuteDetails[0]->date,$minuteDetails[0]->meeting_type);



        if($approveStatus[0]->is_approved==1 && $approveStatus[0]->is_recorrect==0){
            $approved_recorrect_Meeting=$approved_minutes->getApprovedMinute($minuteID);
        }
        else if($approveStatus[0]->is_recorrect==1 && $approveStatus[0]->is_approved==0){
            $approved_recorrect_Meeting=$recorrect_minute->selectandproject('recorrected_version',['Minute_ID'=>$minuteID]);
        }
        else if($approveStatus[0]->is_recorrect==1 && $approveStatus[0]->is_approved==1){
            $approved_recorrect_Meeting=$recorrect_minute->selectandproject('minute_id',['recorrected_version'=>$minuteID]);
        }
        $linked_content_minutes=$cfm->getLinkMinuteIds($minuteDetails[0]->meeting_id);

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


        $minuteDetails[0]->discussed_memos = $discussed_memos;
        $minuteDetails[0]->linked_minutes = $linked_minutes;
        $minuteDetails[0]->linkedMediaFiles = $linkedMediaFiles;
        // show($minuteDetails[0]);
        //  show($minuteDetails);

        // show($previousMinute);
        $this->view("studentrep/viewminute",['user'=>$user,'minuteID'=>$minuteID,'minuteDetails'=>$minuteDetails,'contents'=>$user_accessible_content,'isContentRestricted'=>$isContentRestricted,'approvedStatus'=>$approveStatus[0],'approved_recorrect_Meeting'=>$approved_recorrect_Meeting,'linked_content_minutes'=>$linked_content_minutes,'previousMinute'=>$previousMinute]);
        }
    }

}

}