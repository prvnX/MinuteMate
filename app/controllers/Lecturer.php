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
             //these are just placeholders
        $user = "lecturer";
       
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/lecturer",
            $notification => ROOT."/lecturer/notifications",
            "profile" => ROOT."/lecturer/viewprofile"
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
        $this->view("search",[ "user" => $user, "menuItems" => $menuItems, "notification" => $notification,"searchtxt"=>$searchtxt,"memoResults"=>$memoResults,"minuteResults"=>$minuteResults]);
        }
    }
    public function reviewstudentmemo(){
        $this->view("lecturer/reviewstudentmemo");
    }
    public function entermemo() {
        $this->view("lecturer/entermemo");
    }
    public function reviewmemos() {
        $this->view("lecturer/reviewmemos");
    }
    public function viewminutes() {
        $this->view("lecturer/viewminutes");
    }
    public function viewsubmittedmemos() {
        $this->view("lecturer/viewsubmittedmemos");
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
        $memosuccess = false;
        $memoid = 1;
        if($memosuccess) {
            $this->view("showsuccessmemo",["user"=>"lecturer","memoid"=>$memoid]);
        }
        else {
            $this->view("showunsuccessmemo",["user"=>"lecturer"]);
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
