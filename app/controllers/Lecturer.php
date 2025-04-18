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
    public function reviewstudentmemo(){
        $this->view("lecturer/reviewstudentmemo");
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

    public function reviewmemos() {
        $this->view("lecturer/reviewmemos");
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
        $memoList = $memo->getMemosByUser($user);
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
     
    

    public function notifications() {
        //these are just placeholders
        $user = "lecturer";
        $memocart = "memocart";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/lecturer",
            $notification => ROOT."/lecturer/notifications",
            "profile" => ROOT."/lecturer/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"notification" => $notification]);
    }
    public function viewprofile() {
        $this->view("lecturer/viewprofile");
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
             $this->view("showsuccessmemo",["user"=>"lecturer"]);
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
    public function selectmemo (){
        $this->view("lecturer/selectmemo");
    }
    public function selectminute (){
        $this->view("lecturer/selectminute");
    }
    public function viewmemoreports() {
        if(!isset($_GET['memo'])) {
            header("Location: ".ROOT."/lecturer/selectmemo");
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
    
        $this->view("lecturer/viewmemoreports", $data);
    }
    public function viewminutereports() {
        if(!isset($_GET['minute'])) {
            header("Location: ".ROOT."/lecturer/selectminute");
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
    $this->view("lecturer/acceptmemo");

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
    $this->view("lecturer/viewminute",['user'=>$user,'minuteID'=>$minuteID,'minuteDetails'=>$minuteDetails,'contents'=>$user_accessible_content,'isContentRestricted'=>$isContentRestricted]);
    }
}





}
}
