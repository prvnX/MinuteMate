<?php
class Lecturer extends BaseController {
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
        $this->view("lecturer/dashboard",["meetingsinweek" => $meetingsinweek]);
    }


    public function search() {
        $searchtxt=$_POST['search'];
        if($searchtxt=="" || !$searchtxt){
            $this->view("lecturer/dashboard");
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
    public function reviewmemos(){
        $this->view("lecturer/reviewmemos");
    }
    public function entermemo() {
        $user=$_SESSION['userDetails']->username;
        $date = date("Y-m-d");
        if($this->isValidRequest()){
            $meetings = ($this->findMeetingsToEnterMemos($date));

            $this->view("lecturer/entermemo", ['meetings' => $meetings]);
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

    public function reviewstudentmemo() {
        $user = $_SESSION['userDetails']->username;
        $reviewMemos = new ReviewMemo();
        $reviewMemoList = $reviewMemos->getReviewMemosForUser($user) ?? [];
        $this->view("lecturer/reviewstudentmemo", ['memos'=>$reviewMemoList]);
    }
    public function viewminutes() {
        $user = $_SESSION['userDetails']->username;
        $minute = new Minute();
        $minuteList = $minute->MinuteListByUser($user);
        $this->view("lecturer/viewminutes", ['minutes' => $minuteList]);
    }
    public function viewsubmittedmemos() {
        $user = $_SESSION['userDetails']->username;
        $memo = new Memo();
        $memoList = $memo->getMemosByUser($user) ?? [];
        $this->view("lecturer/viewsubmittedmemos", ['memos'=>$memoList]);
    }

    public function isValidRequest(){
        if(isset($_SESSION['userDetails'])){
            return true;
        }
        else{
            return false;
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
        redirect("lecturer/viewsubmittedmemos");
        return;
       }

        $memoModel = new Memo;
        $memo = $memoModel->getMemoById($memo_id);

        error_log("Memo Data: " . json_encode($memo)); //debugging

        if($memo)
        {
            $this->view("lecturer/viewmemodetails", ['memo'=>$memo]);
        }
        else
        {
            $_SESSION['flash_error'] = "Memo not found.";

            redirect("lecturer/viewmemos");

          
        }
    }
    else
    {
        redirect("login");
    }
}
     
    

    public function notifications() {
        $notificationModel=new Notification;
        $Readnotifications=$notificationModel->select_all(['reciptient'=>$_SESSION['userDetails']->username, 'is_read'=>1]);
        $Unreadnotifications=$notificationModel->select_all(['reciptient'=>$_SESSION['userDetails']->username, 'is_read'=>0]);
        //these are just placeholders
        $user = "lecturer";
        $memocart = "memocart";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/lecturer",
            $notification => ROOT."/lecturer/notifications",
            "profile" => ROOT."/lecturer/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"notification" => $notification, 'Readnotifications'=>$Readnotifications, "Unreadnotifications"=>$Unreadnotifications]);
    }
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
            

            $this->view("lecturer/viewprofile", ['userDetails' => $userDetails, 'contactNumbers' => $contactNumbers, 'userRole' => $userRole, 'userMeetingTypes' => $userMeetingTypes,'attendenceMeetings'=>$attendenceMeetings]);
         

    }
    public function submitmemo() {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $memoTitle = htmlspecialchars($_POST['memo-subject']);
            $memoContent = htmlspecialchars($_POST['memo-content']);
            $meetingId = htmlspecialchars($_POST['meeting']);
            $submittedBy=$_SESSION['userDetails']->username;

            if (empty($_POST['meeting']) || empty($_POST['memo-subject']) || empty($_POST['memo-content'])) {
                $_SESSION['flash_error'] = "All fields are required.";    
                redirect('lecturer/entermemo'); 
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
            $meeting= new Meeting();
            $memo->insert($memoData);
            $memoId = $memo->getLastInsertID();
            $sec=$meeting->getSecForMeeting($meetingId);
            $secusername=$sec[0]->username;
            $user=$_SESSION['userDetails']->full_name;

            

            $notification = new Notification();
            if($secusername!=$user){
                $notification->insert([
                    'reciptient' => $secusername,
                    'notification_message' => "New memo submitted by $user,Review Now",
                    'notification_type' => 'memo',
                    'Ref_ID' => $memoId,
                    'link'=>"acceptmemo/?memo_id=$memoId"]);
            }
  

             $this->view("showsuccessmemo",["user"=>"lecturer",'memoid'=>$memoId]);
        }
        else{
            $this->view("showunsuccessmememo", ["user"=> "lecturer"]);
        }
    }
    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"lecturer"]);
    }

    public function logout() {
        session_start();
        // Destroy all session data
        session_unset();
        session_destroy();
        // Redirect to the login page
        redirect("home");
    }
  
    public function selectmemo() { //this is the page where the lecturer selects the memo to view details
        $memo = new Memo();
        $memos = $memo->getMemos($_SESSION['userDetails']->username, date("Y-m-d"));
    
        
            $this->view("lecturer/selectmemo", ['memos' => $memos]);
         
    }
    public function selectminute() { //this is the page where the lecturer selects the minute to view details
        $minute = new Minute();
        $minutes = $minute->getMinutes($_SESSION['userDetails']->username, date("Y-m-d"));
        
        $this->view("lecturer/selectminute", ['minutes' => $minutes]);
    }
    public function viewmemoreport() {
        if (!isset($_GET['memo'])) {
            header("Location: " . ROOT . "/lecturer/selectmemo");
            exit;
        }

        $memoId = $_GET['memo'];
        $memo = new Memo();
        $memoDetails = $memo->getMemoDetails($memoId);
      

        if (!$memoDetails) {
            $this->view("memoreportnotfound");
            return;
        }

        $this->view("lecturer/viewmemoreports", [
            'memoDetails' => $memoDetails,
            'user' => $_SESSION['userDetails']->username
        ]);
    }
    public function viewminutereports() {
        if (!isset($_GET['minute'])) {
            header("Location: " . ROOT . "/lecturer/selectminute");
            exit;
        }


        $id = $_GET['minute'];
        $minute = new Minute();
        $minuteDetails = $minute->getMinuteReportDetails($id);

        if (!$minuteDetails) {
            $this->view("minutereportnotfound");
            return;
        }
       
        $this->view("lecturer/viewminutereports", [
            'minuteDetails' => $minuteDetails,
            'user' => $_SESSION['userDetails']->username
        ]);
    }
     
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
        $this->view("lecturer/requestchange", [
            "user" => "lecturer",
            "responseStatus" => $responseStatus
        ]);
    }
    public function acceptmemo()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Handle GET request to display memo details
        $memo_id = $_GET['memo_id'] ?? null;

        if (!$memo_id) {
            $_SESSION['flash_error'] = "Memo ID not provided.";
            redirect("lecturer/reviewstudentmemo");
            return;
        }

        $memo = new ReviewMemo();
        $memos = $memo->getMemoById($memo_id);

        if ($memos) {
            $this->view("lecturer/acceptmemo", ['memo' => $memos]);
        } else {
            $_SESSION['flash_error'] = "Memo not found.";
            redirect("lecturer/reviewstudentmemo");
        }

    } elseif($_SERVER['REQUEST_METHOD']==='POST'){
        $memo_id = $_POST['memo_id'] ?? null;
        $action = $_POST['action'] ?? null;

            if(!$memo_id || !$action)
            {
                $_SESSION['flash_error'] = 'Invalid Request';
                redirect("lecturer/reviewstudentmemo");
                return;
            }

        $reviewmemo = new ReviewMemo();
        $notification = new Notification();
        $memo = $reviewmemo -> getMemoById($memo_id);
        $submitter = $memo->submitted_by;

            if(!$submitter){
                $_SESSION['flash_error'] = 'Submitter Not Found';
                redirect("lecturer/reviewstudentmemos");
                return;
            }

        if($action === 'accept')
        {
            $deleted = $reviewmemo->deleteMemo($memo_id);

            $mainmemo = new Memo();
            $mainmemo -> insert([
                'memo_title' =>$memo->memo_title,
                'memo_content' => $memo->memo_content,
                'status' => 'pending',
                'submitted_by'=> $submitter,
                'meeting_id' => $memo->meeting_id
            ]);
           
            //$memo_id=$mainmemo->getLastInsertID();
            $notification->insert([
                'reciptient'=> $submitter,
                'notification_type ' => 'approved',
                'notification_message' => 'Your memo has been forwarded to be reviewed',
                'Ref_ID' => $memo_id,
                'link' => "viewmemodetails/?memo_id=$memo_id"

            ]);
        }elseif($action === 'decline'){
            $deleted = $reviewmemo->deleteMemo($memo_id);

            $notification->insert([
                'reciptient' => $submitter,
                'notification_message' => "Your memo has been declined by the reviewer.",
                'notification_type' => 'declined',
                'Ref_ID' => $memo_id,
                'link' => "viewmemodetails/?memo_id=$memo_id"
            ]);
        }else {
            $_SESSION['flash_error'] = 'Invalid action.';
            redirect("lecturer/reviewstudentmemo");
            return;
    }
    $_SESSION['flash_message'] = "Memo successfully {$action}ed.";
    redirect("lecturer/reviewstudentmemo");
}
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
    $this->view("lecturer/viewminute",['user'=>$user,'minuteID'=>$minuteID,'minuteDetails'=>$minuteDetails,'contents'=>$user_accessible_content,'isContentRestricted'=>$isContentRestricted,'approvedStatus'=>$approveStatus[0],'approved_recorrect_Meeting'=>$approved_recorrect_Meeting,'linked_content_minutes'=>$linked_content_minutes,'previousMinute'=>$previousMinute]);
    }
}
}


    // public function changePassword()
    // {
    //     $errors = [];
    //     $success = false;

    //     if($_SERVER['REQUEST_METHOD'] === 'POST')
    //     {
    //         $users = new User();
    //         $username = $_SESSION['userDetails']->username;
    //         $currentPassword = $_POST['current_password'];
    //         $newPassword = $_POST['new_password'];
    //         $confirmPassword = $_POST['confirm_password'];


    //         $storedPassword = getHashedPassword($username);

    //         if(!storedPassword || !password_verify($currentPassword,$storedPassword))
    //         {
    //             $errors[] = 'Current Password is not correct';
    //         }

    //         if($newPassword !== $confirmPassword)
    //         {
    //             $errors[] = 'New password and confirmation do not match';
    //         }

    //         //checking if the password has the required strength
    //         if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newPassword)) {
    //             $errors[] = "New password does not meet the required strength.";
    //         }
    //         if(empty($errors))
    //         {
    //             $newHashed = password_hash($newPassword , PASSWORD_DEFAULT);
    //             $users->updatePassword($username, $newHashed);
    //             $success = true;
    //         }
    //     }
    //     echo json_encode([
    //         'success' => $success,
    //         'errors' => $errors
    //     ]);

    //     echo($errors);
    // }

}
