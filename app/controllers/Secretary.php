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
             //these are just placeholders
        $user = "secretary";
        $memocart = "memocart-dot";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/secretary",
            $memocart => ROOT."/secretary/memocart",
            $notification => ROOT."/secretary/notifications",
            "profile" => ROOT."/secretary/viewprofile"
        ];
        $memoResults = [
            [
                "id" => 1,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "Alice",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 2,
                "title" => "Meeting Minutes",
                "date" => "2024-11-05",
                "submitted_by" => "Bob",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 3,
                "title" => "Research Report",
                "date" => "2024-11-10",
                "submitted_by" => "Charlie",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 4,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 5,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 6,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "Alice",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 7,
                "title" => "Meeting Minutes",
                "date" => "2024-11-05",
                "submitted_by" => "Bob",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 8,
                "title" => "Research Report",
                "date" => "2024-11-10",
                "submitted_by" => "Charlie",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 11,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 12,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 13,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "x",
                "meeting_type" => "IUD"
            ]
        ];

        $minuteResults = [
            [
            "content_id" => 1,
            "minute_id"=> "M001",
            "meeting_date" => "2024-11-01",
            "meeting_type" => "IUD",
            "content_title" => "Project proposal of 'පැදුර "
            ],
            [
            "content_id" => 2,
            "minute_id"=> "M002",
            "meeting_date" => "2024-11-05",
            "meeting_type" => "RHD",
            "content_title" => "Increasing credits of SE III"
            ],
            [
            "content_id" => 3,
            "minute_id"=> "M003",
            "meeting_date" => "2024-11-10",
            "meeting_type" => "IUD",
            "content_title" => "Reducing marks for 'A'"
            ],
            [
            "content_id" => 4,
            "minute_id"=> "M004",
            "meeting_date" => "2024-01-15",
            "meeting_type" => "Syndicate",
            "content_title" => "Annual Budget Review"
            ],
            [
            "content_id" => 5,
            "minute_id"=> "M005",
            "meeting_date" => "2024-02-20",
            "meeting_type" => "BOM",
            "content_title" => "New Research Proposals"
            ],
            [
            "content_id" => 6,
            "minute_id"=> "M006",
            "meeting_date" => "2024-03-18",
            "meeting_type" => "RHD",
            "content_title" => "Quarterly Performance Review"
            ],
            [
            "content_id" => 7,
            "minute_id"=> "M007",
            "meeting_date" => "2024-04-22",
            "meeting_type" => "Syndicate",
            "content_title" => "Policy Updates"
            ],
            [
            "content_id" => 8,
            "minute_id"=> "M008",
            "meeting_date" => "2024-05-10",
            "meeting_type" => "IUD",
            "content_title" => "Project Milestone Discussion"
            ],
            [
            "content_id" => 9,
            "minute_id"=> "M009",
            "meeting_date" => "2024-06-14",
            "meeting_type" => "RHD",
            "content_title" => "Client Feedback Analysis"
            ],
            [
            "content_id" => 10,
            "minute_id"=> "M010",
            "meeting_date" => "2024-07-19",
            "meeting_type" => "Syndicate",
            "content_title" => "Mid-Year Review"
            ],
            [
            "content_id" => 11,
            "minute_id"=> "M011",
            "meeting_date" => "2024-08-23",
            "meeting_type" => "IUD",
            "content_title" => "New Initiatives Planning"
            ],
            [
            "content_id" => 12,
            "minute_id"=> "M012",
            "meeting_date" => "2024-09-17",
            "meeting_type" => "RHD",
            "content_title" => "Team Building Activities"
            ],
            [
            "content_id" => 13,
            "minute_id"=> "M013",
            "meeting_date" => "2024-10-21",
            "meeting_type" => "Syndicate",
            "content_title" => "Year-End Strategy"
            ],
            [
            "content_id" => 14,
            "minute_id"=> 15,
            "meeting_date" => "2024-12-05",
            "meeting_type" => "IUD",
            "content_title" => "Final Project Review"
            ]
        ];
        $this->view("search",[ "user" => $user, "menuItems" => $menuItems,"memocart" => $memocart, "notification" => $notification,"searchtxt"=>$searchtxt,"memoResults"=>$memoResults,"minuteResults"=>$minuteResults]);
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
            if($memo->insert($memoData))
            {
                $this->view("showsuccessmemo",["user"=>"secretary"]);
            }
            else
            {
                $this->view("showunsuccessmemo",["user"=>"secretary"]); 
            }
        }
        
    }
    public function createminute() {
        if(!isset($_GET['meeting'])) {
            header("Location: ".ROOT."/secretary/selectmeeting");
        }
        $meetingId = $_GET['meeting'];
        //check the user has the authority to create the minute for the meeting
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
        
        $minutes = $minute->getMinuteList();
        if($auth[0]->auth){
            $this->view("secretary/createminute", ['meetingId' => $meetingId, 'departments' => $deparments, 'participants' => $Participants, 'memos' => $memos, 'minutes' => $minutes, 'meetingType' => $meetingType, 'meetingDetails' => $meetingDetails,'agendaItems'=>$agendaItems,'fwdmemos'=>$fwdmemos]);
        }
        else{
            redirect("secretary/selectmeeting");
        }
    }
    public function notifications() {
        //these are just placeholders
        $user = "secretary";
        $memocart = "memocart-dot";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/secretary",
            $memocart => ROOT."/secretary/memocart",
            $notification => ROOT."/secretary/notifications",
            "profile" => ROOT."/secretary/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"memocart" => $memocart, "notification" => $notification]);
    }
    public function selectmeeting() { //this is the page where the secretary selects the meeting to create a minute 
        $meeting = new Meeting;
        $meetings = $meeting->getNoMinuteMeetings($_SESSION['userDetails']->username, date("Y-m-d"));
        $this->view("secretary/selectmeeting", ['meetings' => $meetings]);
    }
    public function viewminutes() {
        $this->view("secretary/viewminutes");
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

        if ($action === 'accept') {
            $updated = $memo->updateStatus($memo_id, 'accepted');
        } elseif ($action === 'decline') {
            $updated = $memo->deleteMemo($memo_id);
        } else {
            $_SESSION['flash_error'] = 'Invalid action.';
            redirect("secretary/memocart");
            return;
        }

        if ($updated) {
            $_SESSION['flash_message'] = "Memo successfully {$action}ed.";
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
            $this->view("secretary/viewmemos", ['memos'=> $memos]);
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

    public function viewmemoreports() {
        if(!isset($_GET['memo'])) {
            header("Location: ".ROOT."/secretary/selectmemo");
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
    
        $this->view("secretary/viewmemoreports", $data);
    }
    
    public function viewminutereports() {
        if(!isset($_GET['minute'])) {
            header("Location: ".ROOT."/secretary/selectminute");
        }
        $memoid = $_GET['minute'];
        $data = [
            'date' => '2024-11-16',
            'time' => '10:00 AM',
            'meeting_type' => 'Team Meeting',
            'meeting_minute' => 'Discussed project updates and next steps.',
            'linked_minutes' => 'Minute #14, Minute #15',
            'linked_memos' => 'Memo #12',
            'recording' => 'https://example.com/recording.mp4',
            'attendees' => 'Alice, Bob, Charlie'
        ];
        $this->view("secretary/viewminutereports", $data);
    }
    
    public function submitminute() {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $success=true;
            $mailstautus=true;
            $secretary=$_SESSION['userDetails']->username;
            $meetingID = $_POST['meetingID'];
            $attendence = $_POST['attendence'];
            $agendaItems = $_POST['Agenda'];
            $discussedMemos = $_POST['discussed'] ?? [];
            $underDiscussionMemos = $_POST['underdiscussion'] ?? [];
            $parkedMemos= $_POST['parked'] ?? [];
            $LinkedMinutes = json_decode($_POST['Linkedminutes']) ?? [];
            $sections= json_decode($_POST['sections'], true);
            $minuteTitle = $_POST['minuteTitle'];
            $keywords = $_POST['keywordlist'] ?? [];
            $meeting = new Meeting();
            $meetingMinuteStatus=$meeting->selectandproject("is_minute",['meeting_id'=>$meetingID])[0]->is_minute;
            $meetingDate=$meeting->selectandproject("date",['meeting_id'=>$meetingID])[0]->date;
            $keywordList=[];
            if($meetingMinuteStatus==0){ //if the minute is not already created
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
                //$Minute_Transaction->testData(['discussedMemos'=>$discussedMemos,'underDiscussionMemos'=>$underDiscussionMemos,'parkedMemos'=>$parkedMemos]);
    
                $dataInsert=$Minute_Transaction->insertMinute(['MeetingID'=>$meetingID,'title'=>$minuteTitle,'secretary'=>$secretary,'attendence'=>$attendence,'agenda'=>$agendaItems,'sections'=>$sections,'discussedMemos'=>$discussedMemos,'underDiscussionMemos'=>$underDiscussionMemos,'parkedMemos'=>$parkedMemos,'LinkedMinutes'=>$LinkedMinutes,'mediaFiles'=>$mediaArr,'keywords'=>$keywordList]);
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
                            if($mailstautus){
                                $status="Mail sent";
                            
                            }
                            else{
                                $status="Mail not sent";
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
            // $mailstautus=false;
            // $success=false;
            if($success && $dataInsert==1 && $mailstautus==true){
                $minutemodel=new minute;
                $minuteid=$minutemodel->selectandproject('Minute_ID',['MeetingID'=>$meetingID]);
                $minuteid=$minuteid[0]->Minute_ID;
                $this->view("showsuccessminute",["user"=>"secretary","minuteid"=>$minuteid]);
            }
            else if($success && $dataInsert==1 && $mailstautus==false){
                $minutemodel=new minute;
                $minuteid=$minutemodel->selectandproject('Minute_ID',['MeetingID'=>$meetingID]);
                $minuteid=$minuteid[0]->Minute_ID;
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
    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"Secretary"]);
    }

    public function viewprofile() {
        $this->view("secretary/viewprofile");
    }
    public function logout() {
        session_start();
        // Destroy all session data
        session_unset();
        session_destroy();
        // Redirect to the login page
        redirect("home");
            }
    public function selectmemo (){
        $this->view("secretary/selectmemo");
    }
    public function selectminute (){
        $this->view("secretary/selectminute");
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
        $this->view("secretary/viewminute",['user'=>$user,'minuteID'=>$minuteID,'minuteDetails'=>$minuteDetails,'contents'=>$user_accessible_content,'isContentRestricted'=>$isContentRestricted]);
        }
    }





    }
    
}
