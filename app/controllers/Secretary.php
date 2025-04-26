<?php
class Secretary extends BaseController {


    public function index() {
        date_default_timezone_set('Asia/Colombo');
        $meeting = new Meeting();
        $memo = new Memo();
        $memoCount = $memo->getPendingMemoCount($_SESSION['secMeetingTypes']);
        $memoCount = $memoCount[0]->count;
        $Minutes=$meeting->getNoMinuteMeetings($_SESSION['userDetails']->username, date("Y-m-d"));
        if($Minutes){
            $MinutesCnt = count($Minutes);
        }
        else{
            $MinutesCnt = 0;
        }
        $lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));
        $today=date("Y-m-d");
        $meetingsinweek = $meeting->getMeetingsInWeek($today, $lastDayOfWeek, $_SESSION['userDetails']->username);
        if($meetingsinweek){
            $meetingsinweek = count($meetingsinweek);
        }
        else{
            $meetingsinweek = 0;
        }
        $this->view("secretary/dashboard",[ "MinutesCnt" => $MinutesCnt, "memoCount" => $memoCount, "meetingsinweek" => $meetingsinweek]);
    }

    public function search() {
        $searchtxt=$_POST['search'];
        if($searchtxt=="" || !$searchtxt){
            $this->view("secretary/dashboard");
        }
        else{
            $searchModel=new Search();
            $users= new User();
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
        if($this->isValidRequest()){
            $meetings = ($this->findMeetingsToEnterMemos($date));

            

            $this->view("secretary/entermemo", ['meetings' => $meetings]);
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
            $memoId = $memo->getLastInsertID();
            $meeting= new Meeting();
            $sec=$meeting->getSecForMeeting($meetingId);
            $secusername=$sec[0]->username;
            $user=$_SESSION['userDetails']->full_name;
            $username=$_SESSION['userDetails']->username;

            $notification = new Notification();
            if($secusername!=$username){
                $notification->insert([
                    'reciptient' => $secusername,
                    'notification_message' => "New memo submitted by $user,Review Now",
                    'notification_type' => 'memo',
                    'Ref_ID' => $memoId,
                    'link'=>"acceptmemo/?memo_id=$memoId"]);
            }
            $this->view("showsuccessmemo",["user"=>"secretary",'memoid'=>$memoId]);
        }
            else
            {
                $this->view("showunsuccessmemo",["user"=>"secretary"]); 
            }       
        
    }
    public function recorrectminute(){
        if(!isset($_GET['meeting']) && !isset($_GET['prevMin'])) {
            header("Location: ".ROOT."/secretary/selectmeeting");
        }
        $meetingId = $_GET['meeting'];
        $prevMinID= $_GET['prevMin'];
        //check the user has the authority to create the minute for the meeting
        $user=$_SESSION['userDetails']->username;
        $drafts= new Minute_Draft();
        $meeting = new Meeting();
        $department = new Department(); 
        $memo = new Memo();
        $minute=new Minute();
        $agenda=new Agenda();
        $memofwd=new Memo_forwards;
        $fwdmemos=$memofwd->getmemoList($meetingId);
        $agendaItems=$agenda->select_all(['meeting_id'=>$meetingId]);
        $meetingType = $meeting->selectandproject("meeting_type",['meeting_id'=>$meetingId])[0]->meeting_type;
        $deparments = $department->find_all();
        $Participants = $meeting->getParticipants($meetingId);
        $auth=$meeting->authUserforMinute($meetingId,$_SESSION['userDetails']->username);
        $meetingDetails=$meeting->select_one(['meeting_id'=>$meetingId]);
        $memos = $memo->select_all(['meeting_id'=>$meetingId,'status'=>'accepted']);
        $draftStatus=$drafts->isDraftExist($user,$meetingId);
        $recentMinute=$minute->getPreviousMinute($meetingDetails[0]->end_time,$meetingDetails[0]->date,$meetingType);
        $content_forward_meeting=new Content_forward_meeting;
        $linkedMinutes=$content_forward_meeting->getLinkMinuteIds($meetingId);
        $recentMinuteState=[];
        if($recentMinute!=null){
            $recentMinuteState=$minute->selectandproject('is_approved,is_recorrect',['Minute_ID'=>$recentMinute->Minute_ID]);
        }
       
        

        
        $minutes = $minute->getMinuteList();
        if($auth[0]->auth){
            $this->view("secretary/recreateminute", ['meetingId' => $meetingId, 'departments' => $deparments, 'participants' => $Participants, 'memos' => $memos, 'minutes' => $minutes, 'meetingType' => $meetingType, 'meetingDetails' => $meetingDetails,'agendaItems'=>$agendaItems,'fwdmemos'=>$fwdmemos,'minuteDraft'=>$draftStatus,'recentMinute'=>$recentMinute,'recentMinuteState'=>$recentMinuteState,'linkedMinutes'=>$linkedMinutes,'prevMin'=>$prevMinID]);
        }
        else{
            redirect("secretary/selectmeeting");
        }

    }


    public function createminute() {
        if(!isset($_GET['meeting'])) {
            header("Location: ".ROOT."/secretary/selectmeeting");
        }
        $meetingId = $_GET['meeting'];
        //check the user has the authority to create the minute for the meeting
        $user=$_SESSION['userDetails']->username;
        $drafts= new Minute_Draft();
        $meeting = new Meeting();
        $department = new Department(); 
        $memo = new Memo();
        $minute=new Minute();
        $agenda=new Agenda();
        $memofwd=new Memo_forwards;
        $content_forward_meeting=new Content_forward_meeting;
        $linkedMinutes=$content_forward_meeting->getLinkMinuteIds($meetingId);
        $fwdmemos=$memofwd->getmemoList($meetingId);
        $agendaItems=$agenda->select_all(['meeting_id'=>$meetingId]);
        $meetingType = $meeting->selectandproject("meeting_type",['meeting_id'=>$meetingId])[0]->meeting_type;
        $deparments = $department->find_all();
        $Participants = $meeting->getParticipants($meetingId);
        $auth=$meeting->authUserforMinute($meetingId,$_SESSION['userDetails']->username);
        $meetingDetails=$meeting->select_one(['meeting_id'=>$meetingId]);
        $memos = $memo->select_all(['meeting_id'=>$meetingId,'status'=>'accepted']);
        $draftStatus=$drafts->isDraftExist($user,$meetingId);
        $recentMinute=$meeting->getMostRecentMinutePending($meetingType,$meetingDetails[0]->date) ?? null;
       
        
        // show($recentMinute);

        
        $minutes = $minute->getMinuteList();
        if($auth[0]->auth){
            $this->view("secretary/createminute", ['meetingId' => $meetingId, 'departments' => $deparments, 'participants' => $Participants, 'memos' => $memos, 'minutes' => $minutes, 'meetingType' => $meetingType, 'meetingDetails' => $meetingDetails,'agendaItems'=>$agendaItems,'fwdmemos'=>$fwdmemos,'minuteDraft'=>$draftStatus,'recentMinute'=>$recentMinute,'linkedMinutes'=>$linkedMinutes]);
        }
        else{
            redirect("secretary/selectmeeting");
        }
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
            

            $this->view("secretary/viewprofile", ['userDetails' => $userDetails, 'contactNumbers' => $contactNumbers, 'userRole' => $userRole, 'userMeetingTypes' => $userMeetingTypes,'attendenceMeetings'=>$attendenceMeetings]);
         

    }

    public function viewmemoreport() {
        if (!isset($_GET['memo'])) {
            header("Location: " . ROOT . "/secretary/selectmemo");
            exit;
        }

        $memoId = $_GET['memo'];
        $memo = new Memo();
        $memoDetails = $memo->getMemoDetails($memoId);
      

        if (!$memoDetails) {
            $this->view("memoreportnotfound");
            return;
        }

        $this->view("secretary/viewmemoreports", [
            'memoDetails' => $memoDetails,
            'user' => $_SESSION['userDetails']->username
        ]);
    }



    public function viewminutereports() {
        if (!isset($_GET['minute'])) {
            header("Location: " . ROOT . "/secretary/selectminute");
            exit;
        }


        $id = $_GET['minute'];
        $minute = new Minute();
        $minuteDetails = $minute->getMinuteReportDetails($id);

        if (!$minuteDetails) {
            $this->view("minutereportnotfound");
            return;
        }
       
        $this->view("secretary/viewminutereports", [
            'minuteDetails' => $minuteDetails,
            'user' => $_SESSION['userDetails']->username
        ]);
    }
     

    public function notifications() {
        $notificationModel=new Notification;
        $Readnotifications=$notificationModel->select_all(['reciptient'=>$_SESSION['userDetails']->username, 'is_read'=>1]);
        $Unreadnotifications=$notificationModel->select_all(['reciptient'=>$_SESSION['userDetails']->username, 'is_read'=>0]);

        //these are just placeholders
        $user = "secretary";
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/secretary",
            $notification => ROOT."/secretary/notifications",
            "profile" => ROOT."/secretary/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems, "notification" => $notification,'Readnotifications'=>$Readnotifications,'Unreadnotifications'=>$Unreadnotifications]);
    }
    public function selectmeeting() { //this is the page where the secretary selects the meeting to create a minute 
        $meeting = new Meeting;
        $meetings = $meeting->getNoMinuteMeetings($_SESSION['userDetails']->username, date("Y-m-d"));
        $this->view("secretary/selectmeeting", ['meetings' => $meetings]);
    }
    public function selectmemo() { //this is the page where the secretary selects the memo to view details
    $memo = new Memo();
    $memos = $memo->getMemos();

    
        $this->view("secretary/selectmemo", ['memos' => $memos]);
     
}
public function selectminute() { //this is the page where the secretary selects the minute to view details
    $minute = new Minute();
    $minutes = $minute->getMinutes();
    
    $this->view("secretary/selectminute", ['minutes' => $minutes]);
}
    public function viewminutes() {
        $user = $_SESSION['userDetails']->username;
        $minute = new Minute();
        $minuteList = $minute->MinuteListByUser($user);
        $this->view("secretary/viewminutes", ['minutes' => $minuteList]);
    }

    public function memocart() {
        $user = $_SESSION['userDetails']->username;
        $memo = new Memo();

        $memos = $memo->getMemosForMemocart($user);
        $this->view('secretary/memocart', ['memos'=>$memos]);
    }

    public function acceptmemo()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Handle GET request to display memo details
        $memo_id = $_GET['memo_id'] ?? null;

        if (!$memo_id) {
            $_SESSION['flash_error'] = "Memo ID not provided.";
            redirect("secretary/memocart");
            return;
        }

        $memo = new Memo();
        $memos = $memo->getMemoById($memo_id);

        if ($memos) {
            $this->view("secretary/acceptmemo", ['memo' => $memos]);
        } else {
            $_SESSION['flash_error'] = "Memo not found.";
            redirect("secretary/memocart");
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle POST request for accepting or declining the memo
        $memo_id = $_POST['memo_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if (!$memo_id || !$action) {
            $_SESSION['flash_error'] = 'Invalid request. Please provide all required data.';
            redirect("secretary/memocart");
            return;
        }

        $memo = new Memo();
        $notification = new Notification();
        $memoSubmitter= $memo->selectandproject('submitted_by',['memo_id'=>$memo_id])[0]->submitted_by;
   

        if ($action === 'accept') {
            $updated = $memo->updateStatus($memo_id, 'accepted');
            $notification->insert([
                'reciptient' => $memoSubmitter,
                'notification_message' => "Your memo with ID $memo_id has been approved.",
                'notification_type' => 'approved',
                'Ref_ID' => $memo_id,
                'link'=>"viewmemodetails/?memo_id=$memo_id"]);
        } elseif ($action === 'decline') {
            $updated = $memo->deleteMemo($memo_id);
            $notification->insert([
                'reciptient' => $memoSubmitter,
                'notification_message' => "Your memo with ID $memo_id has been declined.",
                'notification_type' => 'declined',
                'Ref_ID' => $memo_id,
                'link'=>"viewmemodetails/?memo_id=$memo_id"]);
        } else {
            $_SESSION['flash_error'] = 'Invalid action.';
            redirect("secretary/memocart");
            return;
        }

        if ($updated) {
            $_SESSION['flash_message'] = `Memo successfully {$action}ed.`;
        } else {
            $_SESSION['flash_error'] = "Failed to {$action} memo.";
        }

        // Redirect to prevent form resubmission
        redirect("secretary/memocart");
    }
}


    public function declinememo($memoId)
    {
        $memo = new Memo();
        $memo->deleteMemo($memoId);
        header('Location: ' . ROOT . '/secretary/memocart');   
    }

    public function viewmemos() {
        $user=$_SESSION['userDetails']->username;

        if($this->isValidRequest())
        {
            $memo = new Memo();
            $memos = $memo->getAllAcceptedMemos();

            $userModel = new User();
            $submittedMembers = $userModel->query("SELECT DISTINCT full_name FROM user");

            $this->view("secretary/viewmemos", ['memos'=> $memos, 'submittedMembers'=>$submittedMembers]);
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
            redirect("secretary/viewsubmittedmemos");
            return;
           }

            $memoModel = new Memo;
            $memo = $memoModel->getMemoById($memo_id);

            error_log("Memo Data: " . json_encode($memo)); //debugging

            if($memo)
            {
                $this->view("secretary/viewmemodetails", ['memo'=>$memo]);
            }
            else
            {
                $_SESSION['flash_error'] = "Memo not found.";
                redirect("secretary/viewmemos");
            }
        }
        else
        {
            redirect("login");
        }
    }

 
   
    
    public function submitminute() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $minute = new minute;
            $success=true;
            $mailstautus=true;
            $secretary=$_SESSION['userDetails']->username;
            $meetingID = $_POST['meetingID'];
            $discussedMemos = $_POST['discussed'] ?? [];
            $underDiscussionMemos = $_POST['underdiscussion'] ?? [];
            $parkedMemos= $_POST['parked'] ?? [];
            $LinkedMinutes = json_decode($_POST['Linkedminutes']) ?? [];
            $sections= json_decode($_POST['sections'], true);
            $minuteTitle = $_POST['minuteTitle'];
            $keywords = $_POST['keywordlist'] ?? [];
            $prevMinuteState=$_POST['previousMinuteStatus'];
            $prevMinute=$_POST['previousMinute'] ?? null;
            $prevMinuteRecorrect=$minute->selectandproject('is_approved,is_recorrect',['Minute_ID'=>$prevMinute])[0]; 

            $meeting = new Meeting();
            $meetingMinuteStatus=$meeting->selectandproject("is_minute",['meeting_id'=>$meetingID])[0]->is_minute;
            $meetingDate=$meeting->selectandproject("date",['meeting_id'=>$meetingID])[0]->date;
            $keywordList=[];
        if($meetingMinuteStatus==0){ //if the minute is not already created
            if($prevMinuteState=='accept' || ($prevMinuteState=='reject' && $prevMinuteRecorrect->is_recorrect==1)){
                
            $mediaArr=[];
            if(isset($_FILES['media']) && !empty($_FILES['media']['name'][0])){
                $cloudinaryUpload = new CloudinaryUpload();
                $mediaArr = $cloudinaryUpload->uploadFiles($_FILES['media']);
                //show($mediaArr);
                if($mediaArr==null){
                    $success=false;
                }
            }
            if(isset($keywords) && $keywords[0]!=null){
                foreach($keywords as $keyword){
                    if($keyword!=""){
                        $keywordList[]=$keyword;
                    }
                
                }
            }


            if($success){
                $Minute_Transaction=new Minute_Transaction();
                // show($_POST);
                //$Minute_Transaction->testData(['discussedMemos'=>$discussedMemos,'underDiscussionMemos'=>$underDiscussionMemos,'parkedMemos'=>$parkedMemos]);
                if($prevMinuteState=='accept'){
                    $dataInsert=$Minute_Transaction->insertMinute(['MeetingID'=>$meetingID,'title'=>$minuteTitle,'secretary'=>$secretary,'sections'=>$sections,'discussedMemos'=>$discussedMemos,'underDiscussionMemos'=>$underDiscussionMemos,'parkedMemos'=>$parkedMemos,'LinkedMinutes'=>$LinkedMinutes,'mediaFiles'=>$mediaArr,'keywords'=>$keywordList,'prevMinuteState'=>$prevMinuteState,'prevMinute'=>$prevMinute]);
                }
                else{
                    $$prevMinuteState=null;
                    $dataInsert=$Minute_Transaction->insertMinute(['MeetingID'=>$meetingID,'title'=>$minuteTitle,'secretary'=>$secretary,'sections'=>$sections,'discussedMemos'=>$discussedMemos,'underDiscussionMemos'=>$underDiscussionMemos,'parkedMemos'=>$parkedMemos,'LinkedMinutes'=>$LinkedMinutes,'mediaFiles'=>$mediaArr,'keywords'=>$keywordList,'prevMinuteState'=>$prevMinuteState,'prevMinute'=>$prevMinute]);
                }

                if($dataInsert==1 || $dataInsert==true){
                    $approveMinute= new Approved_minutes();
                    if($prevMinute!= null){
                        $approveMinute->insert(['Minute_ID'=>$prevMinute,'Approved_Meeting_ID'=>$meetingID]);
                    }
                }
                // $dataInsert=false;
            }
            else{
                $dataInsert=false;
            }




           
            //  $dataInsert=1;
              if($dataInsert==1 || $dataInsert==true){
                  //echo $dataInsert;
                 $cfd=new Content_forward_dep;
                 $forwardDepContents=$cfd->get_dep_forwarded_content($meetingID);
                 

                 //mmail sending for the releavent deps
                 if(isset($forwardDepContents) && $forwardDepContents!=null){
                        foreach($forwardDepContents as $forwardcontent){
                            //show($forwardcontent);
                            $contentTitle=$forwardcontent->title;
                            $minuteID=$forwardcontent->Minute_ID;
                            $meetingDate=$forwardcontent->date;
                            $secname=$forwardcontent->full_name;
                            $meetingType=$forwardcontent->meeting_type;
                            $depEmail=$forwardcontent->dep_email;
                            $contentinD=$forwardcontent->content;
                            $dheadmail=$forwardcontent->dheadmail;
                            $dhead=$forwardcontent->dhead;
                            $depname=$forwardcontent->dep_name;
                        //     //send the releavent content as a mail
                            $mail = new Mail();
                            $mailstautus=$mail->forwardMinuteContent($depEmail,$depname,$contentTitle,$contentinD,$minuteID,$meetingDate,$secname,$meetingType,$dheadmail,$dhead);
                            if(!$mailstautus){
                                $mailstautus=false;
                            
                            }
                 }   

            }
            
            // after mail sending - meeting content forward for future 
            $cfm=new Content_forward_meeting;
            $agendafwd=new Meeting_forward_Transaction;
            $MtForwardedContent=$cfm->forwardedContentMeetings($meetingID);
           
            if(isset($MtForwardedContent)&& $MtForwardedContent!=null){
                
                    //show($MtForwardedContent);
                    foreach($MtForwardedContent as $content){ //if theres already scheduled meeting on the forwarded meeting type
                        $contentID= $content->content_id;
                        $agendaTitle=$content->title;
                        $meeting_type=$content->meeting_type;
                        $meetingDate=$content->meeting_date;
                        $forwardMeeting=$meeting->getLatestMeeting($meeting_type);
                        if(isset($forwardMeeting)&& $forwardMeeting!=null){
                        $forwardMeetingID=$forwardMeeting[0]->meeting_id;
                        $agendaTitle.=" (From ". strtoupper($meeting_type)." Meeting On : ".$meetingDate.")";
                        // $status=1;
                        $status=$agendafwd->forwardcontent($forwardMeetingID,$agendaTitle,$contentID);
                        // if($status){
                        //     echo "Agenda forwarded";
                        // }
                        // else{
                        //     echo "Agenda not forwarded";
                        // }
                        }
                        
                    }

            

            }

            //after content forwarding for meetings - memo linking for the meetings
            $memo=new memo;
            $memofwd=new Memo_forwards;
            $meetingType=$meeting->selectandproject("meeting_type",['meeting_id'=>$meetingID])[0]->meeting_type;
            $memotolink=$memo->getTobeForwardedMemos($meetingID);
            //find the next meeting of the same type
            $latestMeeting=$meeting->getLatestMeeting($meetingType);
            if(isset($memotolink)&& $memotolink!=null){
                if(isset($latestMeeting)&& $latestMeeting!=null){
                $nextMeetingID=$latestMeeting[0]->meeting_id;    
                foreach($memotolink as $memos){
                    $memoID=$memos->memo_id;
                    $memofwd->insert(['Forwarded_Memo_id'=>$memoID,'Forwarded_to'=>$nextMeetingID,'Forwarded_Date'=>date("Y-m-d")]);
                    $memo->update($memoID,['is_forwarded'=>1],'memo_id');
                    
                }
                    
            }
        }
        







            }
            $notification = new Notification();
            // $mailstautus=false;
            // $success=false;
            if($success && $dataInsert==1 && $mailstautus==true){
                $minutemodel=new minute;
                $minuteid=$minutemodel->selectandproject('Minute_ID',['MeetingID'=>$meetingID]);
                $minuteid=$minuteid[0]->Minute_ID;
                $message="Meeting minute (ID ".$meetingID." , ".$meetingDate." ) is created. View now.";
                $recivers=$meeting->getParticipants($meetingID);
                
                foreach($recivers as $reciever){
                    $reciever=$reciever->username;
                    $notification->insert(['reciptient'=>$reciever,'notification_type'=>'minute','notification_message'=>$message,'Ref_ID'=>$minuteid,'link'=>'viewminute?minuteID='.$minuteid]);
                
                }
                $this->view("showsuccessminute",["user"=>"secretary","minuteid"=>$minuteid]);
            }
            else if($success && $dataInsert==1 && $mailstautus==false){
                $minutemodel=new minute;
                $minuteid=$minutemodel->selectandproject('Minute_ID',['MeetingID'=>$meetingID]);
                $minuteid=$minuteid[0]->Minute_ID;
                $message="Meeting minute (ID ".$meetingID." , ".$meetingDate." ) is created. View now.";
                $recivers=$meeting->getParticipants($meetingID);
                
                foreach($recivers as $reciever){
                    $reciever=$reciever->username;
                    $notification->insert(['reciptient'=>$reciever,'notification_type'=>'minute','notification_message'=>$message,'Ref_ID'=>$minuteid,'link'=>'viewminute?minuteID='.$minuteid]);
                
                }
                $this->view("showwarningminute",["user"=>"secretary","minuteid"=>$minuteid]);
            }
            else{
                $this->view("showunsuccessminute",['meetingid'=>$meetingID]);
            }


            
            //show($_POST);
            
            // show($mediaArr);
            // foreach($LinkedMinutes as $Minute){
            //     show($Minute);
            // }
            //show($sections);
            
        
        
        
            }
            else{
                $this->view("minutenotrecorrect",["user"=>"secretary"]);
            }
        }
            else{
                $this->view("minutecreatedmsg",["user"=>"secretary"]);
            }
        }
            
        else{
            echo "Invalid request";
        }
    //     $memosuccess = false;
    //     $minuteid = 1;
    //     if($memosuccess) {
    //         $this->view("showsuccessminute",["user"=>"secretary","minuteid"=>$minuteid]);
    // }
    // else {
    //     $this->view("showunsuccessminute",["user"=>"secretary"]);
    // }
    }

    public function submitrecorrectminute(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $success=true;
            $mailstautus=true;
            $secretary=$_SESSION['userDetails']->username;
            $meetingID = $_POST['meetingID'];
            $discussedMemos = $_POST['discussed'] ?? [];
            $underDiscussionMemos = $_POST['underdiscussion'] ?? [];
            $parkedMemos= $_POST['parked'] ?? [];
            $LinkedMinutes = json_decode($_POST['Linkedminutes']) ?? [];
            $sections= json_decode($_POST['sections'], true);
            $minuteTitle = $_POST['minuteTitle'];
            $keywords = $_POST['keywordlist'] ?? [];
            $prevMinuteID=$_POST['prevMinuteID'];

            $meeting = new Meeting();
            $meetingDate=$meeting->selectandproject("date",['meeting_id'=>$meetingID])[0]->date;
            $keywordList=[];
            $mediaArr=[];
            if(isset($_FILES['media']) && !empty($_FILES['media']['name'][0])){
                $cloudinaryUpload = new CloudinaryUpload();
                $mediaArr = $cloudinaryUpload->uploadFiles($_FILES['media']);
                //show($mediaArr);
                if($mediaArr==null){
                    $success=false;
                }
            }
            if(isset($keywords) && $keywords[0]!=null){
                foreach($keywords as $keyword){
                    if($keyword!=""){
                        $keywordList[]=$keyword;
                    }
                
                }
            }
            if($success){
                $RecorrectTrans=new minute_Recorrect_Transaction();
                $Draft= new Minute_Draft();
                $newminuteID=$RecorrectTrans->insertMinute(['MeetingID'=>$meetingID,'title'=>$minuteTitle,'secretary'=>$secretary,'sections'=>$sections,'LinkedMinutes'=>$LinkedMinutes,'mediaFiles'=>$mediaArr,'keywords'=>$keywordList,'prevMinuteID'=>$prevMinuteID]);
                if($newminuteID && $newminuteID!=0){
                    $recorrectMinute= new Recorrect_Minutes();
                    $recorrectMinute->insert(['Minute_ID'=>$prevMinuteID,'recorrected_version'=>$newminuteID]);
                    $Draft->delete($meetingID,'meeting_id');
                    $recivers=$meeting->getParticipants($meetingID);
                    $notification=new Notification;
                    foreach($recivers as $reciever){
                        $reciever=$reciever->username;
                        $notification->insert(['reciptient'=>$reciever,'notification_type'=>'minute','notification_message'=>"The Minute with ID:".$prevMinuteID." has Recorrected",'Ref_ID'=>$newminuteID,'link'=>'viewminute?minuteID='.$newminuteID]); 
                    }
                    $this->view("successrecreate",['minuteid'=>$newminuteID]);
                }
            }
            else{
                $this->view("showunsuccessminute");

            }
            }
            else{
                echo "Invalid request";
            }
        


            
            //show($_POST);
            
            // show($mediaArr);
            // foreach($LinkedMinutes as $Minute){
            //     show($Minute);
            // }
            //show($sections);  
    }



    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"Secretary"]);
    }
 
                    
    public function logout() {
        session_start();
        // Destroy all session data
        session_unset();
        session_destroy();
        // Redirect to the login page
        redirect("home");
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
        $this->view("secretary/requestchange", [
            "user" => "secretary",
            "responseStatus" => $responseStatus
        ]);
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
        $this->view("secretary/viewminute",['user'=>$user,'minuteID'=>$minuteID,'minuteDetails'=>$minuteDetails,'contents'=>$user_accessible_content,'isContentRestricted'=>$isContentRestricted,'approvedStatus'=>$approveStatus[0],'approved_recorrect_Meeting'=>$approved_recorrect_Meeting,'linked_content_minutes'=>$linked_content_minutes,'previousMinute'=>$previousMinute]);
        }
    }





    }
    
    //handling the ajax requests for drafting the minutes
    private function isSecretary(){
        if($_SESSION['userDetails']->role=="secretary"){
            return true;
        }
        else{
            return false;
        }
    }
    public function inputDraftData(){
        if($this->isSecretary()){
  
            $input = json_decode(file_get_contents("php://input"), true);
            $meetingID = $input['draftMeeting'] ?? null;
            $username = $input['draftAddedUser'] ?? null;
            $draftData = $input['draftData'] ?? null;
            $draftData = json_encode($draftData);
            $draft = new Minute_Draft();
            $state=$draft->addToDraft($username, $meetingID, $draftData);
  

            echo json_encode([ 'success' => true,'response' => 'data recieved'  ]);
        }
        else{
            echo json_encode([  'success' => false,'response' => 'authorization error'  ]);
        }
    }

    public function getDraftData(){
        if($this->isSecretary()){
            $input = json_decode(file_get_contents("php://input"), true);
            $meetingID = $input['loadDraft'] ?? null;
            $username = $input['loadAddedUser'] ?? null;
            $draft = new Minute_Draft();
            $draftData=$draft->selectandproject('draft_data',['meeting_id'=>$meetingID,'username'=>$username]);
            if($draftData){
                $draftData = json_decode($draftData[0]->draft_data, true);
                echo json_encode([ 'success' => true,'response' => $draftData  ]);
            }
            else{
                echo json_encode([  'success' => false,'response' => 'no data found'  ]);
            }
        }
        else{
            echo json_encode([  'success' => false,'response' => 'authorization error'  ]);
        }
    }        
    
}


