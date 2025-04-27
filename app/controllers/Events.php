<?php
class Events extends Controller
{
    private $fetchData;
    public function index()
    {   date_default_timezone_set(timezoneId: 'Asia/Colombo');

        $date=$_GET['date'];
        if($this->isValidRequest()){
            $meeting = new Meeting();
            $memofwd = new Memo_forwards;
            $this->findMeetingforDate();
            $lastDayOfWeek = date('Y-m-d', strtotime('sunday this week'));
            $today=date("Y-m-d");
            $meetingsinweek = $meeting->getMeetingsInWeek($today, $lastDayOfWeek, $_SESSION['userDetails']->username);
            $this->view("viewevents",['date'=>$date,'fetchData'=>$this->fetchData,'meetingsinweek'=>$meetingsinweek]);
        }
        else{
            redirect("login");
        }
    }

    public function isValidRequest(){
        if(isset($_SESSION['userDetails'])){
            return true;
        }
        else{
            return false;
        }
    }

    public function isSecretary(){
        if($_SESSION['userDetails']->role=="secretary"){
            return true;
        }
        else{
            return false;
        }
    }
    private function authMeetingEdit($meeting_id,$uername){
        $meeting = new Meeting();
        $secretaryMeetingTypes = new secretary_meeting_type;
        $row=$meeting->select_one(['meeting_id'=>$meeting_id]);
        $meetingtype=$row[0]->type_id;
        $secRow=$secretaryMeetingTypes->select_all(['username'=>$_SESSION['userDetails']->username]);
        $auth=false;
        foreach ($secRow as $secMeetingType) {
            $secnewRow=$secMeetingType->meeting_type_id;
            if($secnewRow==$meetingtype){
                $auth=true;
                break;
            }
        }
        return $auth;

    }

    private function findMeetingforDate(){
        $date=$_GET['date'];
        $user=$_SESSION['userDetails']->username;
        if($date!=""){
            $meeting =  new Meeting();
            $meetingDetails=$meeting->getMeetingByDateUser($date,$user);
            if($meetingDetails){
                $this->fetchData=json_encode($meetingDetails);
                
            }
            else{
    
                $meetingDetails=$meeting->getMeetingByDate($date);
                if($meetingDetails){
                    $this->fetchData=json_encode(["error"=>"You do not have access to this meetings"]);
                }
                else{
                $this->fetchData = json_encode(["error"=>"No meeting found for the given date"]);
                }
            }
        }
}
public function deleteMeeting() {
    if ($this->isSecretary()) {
        $meeting = new Meeting();
        // Get the raw POST data and decode JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $meeting_id = $input['meeting_id'] ?? null;
        if ($meeting_id) {
            $auth=$this->authMeetingEdit($meeting_id,$_SESSION['userDetails']->username);
            if($auth){
                $meetingParticipants = $meeting->getParticipants($meeting_id);
                $meeting->delete($meeting_id,'meeting_id');
                $notificationModel = new Notification();
                foreach ($meetingParticipants as $participant) {
                    $notificationModel->insert([
                        'reciptient' => $participant->username,
                        'notification_message' => "The meeting with ID - $meeting_id has been deleted",
                        'notification_type' => 'deleted',
                        'Ref_ID' => $meeting_id,
                        'link'=>"dashboard"]);
                } 
                echo json_encode(["success" => "Meeting with ID - $meeting_id Deleted Successfully"]);
            }
            else{
                echo json_encode(["error" => "You do not have access to delete this meeting"]);
            }
        } else {
            echo json_encode(["error" => "Meeting ID is missing"]);
        }
    } else {
        echo json_encode(["error" => "Invalid request"]);
    }
}


public function rescheduleMeeting() {
    if ($this->isSecretary()) {
        $meeting = new Meeting();
        $input = json_decode( file_get_contents('php://input'), true);
        $meeting_id = $input['meeting_id'] ?? null;
        $date = $input['newDate'];
        $startTime=$input['startTime'];
        $endTime=$input['endTime'];
        if ($meeting_id) {
            $auth=$this->authMeetingEdit($meeting_id,$_SESSION['userDetails']->username);
            if(!$auth){
                echo json_encode(["error" => "You do not have access to edit this meeting"]);
                return;
            }
            $meeting->update($meeting_id,['date'=>$date,'start_time'=>$startTime,'end_time'=>$endTime],'meeting_id');
            $notificationModel = new Notification();
            $meetingParticipants = $meeting->getParticipants($meeting_id);
            foreach ($meetingParticipants as $participant) {
                $notificationModel->insert([
                    'reciptient' => $participant->username,
                    'notification_message' => "The meeting with ID - $meeting_id has been rescheduled to $date",
                    'notification_type' => 'rescheduled',
                    'Ref_ID' => "$meeting_id",
                    'link'=>"events?date=$date"]);
            } 
            echo json_encode(["success" => "Meeting with ID - $meeting_id Rescheduled Successfully"]);
        } else {
            echo json_encode(["error" => "Meeting ID is missing"]);
        }
    } else {
        echo json_encode(["error" => "Invalid request"]);
    }
}

   
public function addMeeting() {
    if ($this->isSecretary()) {
        $meeting = new Meeting();
        $input = json_decode(file_get_contents('php://input'), true);
        $date = $input['date'];
        $meetingType = $input['meeting_type'];
        $startTime = $input['start_time'];
        $endTime = $input['end_time'];
        $location = $input['location'];
        $additionalNote = $input['additional_note']." ";
        $createdBy = $_SESSION['userDetails']->username;
        $agendas=$input['agenda'];
        if($meetingType=="rhd"){
            $type_id=1;
        }
        else if($meetingType=="iud"){
            $type_id=2;
        }
        else if($meetingType=="syn"){
            $type_id=3;
        }
        else if($meetingType=="bom"){
            $type_id=4;
        }
        if($additionalNote==" "){
            $additionalNote="No additional note";
        }
        if($input){
            $meetingAgenda=new Agenda;
            $meeting->insert(['date'=>$date,'meeting_type'=>$meetingType,'start_time'=>$startTime,'end_time'=>$endTime,'location'=>$location,'additional_note'=>$additionalNote,'created_by'=>$createdBy,'type_id'=>$type_id]);
            $meetingid=$meeting->getLastInsertID();
            foreach ($agendas as $agenda) {
                $meetingAgenda->insert(['meeting_id'=>$meetingid,'agenda_item'=>$agenda]);
            }
            $cfm=new Content_forward_meeting;
            $meeting_fwd_trans=new Meeting_forward_Transaction;
            $cfdata=$cfm->getforwardedListByType($meetingType);
            if(isset($cfdata) && $cfdata!=null){
                foreach ($cfdata as $content) {
                        $contentID= $content->content_id;
                        $agendaTitle=$content->title;
                        $meeting_type=$content->meeting_type;
                        $meetingDate=$content->meeting_date;
                        $agendaTitle.=" (From ". strtoupper($meeting_type)." Meeting On : ".$meetingDate.")";
                        $meeting_fwd_trans->forwardcontent($meetingid,$agendaTitle,$contentID);

            }
        }
        $memos=new memo;
        $memofwd=new Memo_forwards;
        $memotofwd=$memos->getMemosByMeetingType($meetingType);

        if(isset($memotofwd) && $memotofwd!=null){
             foreach ($memotofwd as $memo) {
                $memoID=$memo->memo_id;
               $memofwd->insert(['Forwarded_Memo_id'=>$memoID,'Forwarded_to'=>$meetingid,'Forwarded_Date'=>date("Y-m-d")]);
               $memos->update($memoID,['is_forwarded'=>1],'memo_id');
               
           }
                
        }
        $notificationModel = new Notification();
        $meetingParticipants = $meeting->getParticipants($meetingid);
        foreach ($meetingParticipants as $participant) {
            $notificationModel->insert([
                'reciptient' => $participant->username,
                'notification_message' => "New meeting added with ID - $meetingid",
                'notification_type' => 'meeting',
                'Ref_ID' => $meetingid,
                'link'=>"events?date=$date"]);
        } 
            echo json_encode(["success" => "Meeting Added Successfully with ID - ".$meetingid]);
        }
        else{
            echo json_encode(["error" => "No Data Provided"]);
        }
    } else {
        echo json_encode(["error" => "Invalid request"]);
    }
}


public function  getMemoList(){
    $memo = new Memo();
    $memofwd= new Memo_forwards;
    $input = json_decode( file_get_contents('php://input'), true);
    $meeting_id = $input['meeting_id'] ?? null;
    if ($meeting_id) {
        $memosList = $memo->select_all(['meeting_id' => $meeting_id,'status'=>'accepted']);
        $forwardedMemos=$memofwd->getmemoList($meeting_id);
        // if($forwardedMemos){
        //     // $newMemoList=array_merge(arrays: $memosList, $forwardedMemos);
        //     echo json_encode(["success" => true, "memos" => $memosList]);
        // }
        echo json_encode(["success" => true, "memos" => $memosList,"forwardedMemos"=>$forwardedMemos]);
        // if($memosList){
        //     echo json_encode(["success" => true, "memos" => $memosList]);
        // }
        // else{
        //     echo json_encode(["error" => "No memos found for the given meeting ID"]);
        // }
        // if($memosList){
        //     echo json_encode(["success" => true, "memos" => $memosList]);
        // }
        // else{
        //     echo json_encode(["error" => "No memos found for the given meeting ID"]);
        // }

    } else {
        echo json_encode(["error" => "Meeting ID is missing"]);
    }
    }
    public function getAgendaList(){
        $input= json_decode(file_get_contents('php://input'),true);
        $meeting_id=$input['meeting_id'] ?? null;
        $agenda= new Agenda;
        if($meeting_id){
            $agendaList=$agenda->getAgendaItems($meeting_id);
            $meetingAgenda=$agenda->selectandproject('agenda_item,id',['meeting_id'=>$meeting_id]);
            
            echo json_encode(["success" => true, "agendas" => $agendaList,'meetingAgenda'=>$meetingAgenda]);
        }
    }
    public function getAttendanceList(){
        $input = json_decode( file_get_contents('php://input'), true);
        $meeting_id = $input['meeting_id'] ?? null;
        if($meeting_id){
            $meeting = new Meeting();
            $attendanceList = $meeting->getParticipants($meeting_id);
            echo json_encode(["success" => true, "attendances" => $attendanceList]);
            
        }
    }
    public function markAttendence(){
        $meetingAttendence= new Meeting_attendence;
        $meeting=new Meeting;
        if(isset($_POST['attendence'])){
        $attendenceList = $_POST['attendence'];
        $meetingID=$_POST['meetingID'];

        if($meetingID && $attendenceList!=null){
            foreach($attendenceList as $attendee){
                $meetingAttendence->insert(['meeting_id'=>$meetingID,'attendee'=>$attendee]);
            }
            $meeting->update($meetingID,['attendence_mark'=>1],'meeting_id');

            $this->view("success");
            
        }
        }
        else{
            $this->view("notsuccess");
        }
        
    // }
    }

    public function viewMeetingReport(){
        if($this->isValidRequest()){
        $meeting = new Meeting;
        $meetingAtd= new Meeting_attendence;
        $agenda= new Agenda;
        $memoDis=new Memo_discussed_meetings;
        $minute=new Minute;
        $meetingID=$_GET['meeting_id'];
        $meetingtypes=[];
        $meetingDetails[0]=$meeting->select_all(['meeting_id'=>$meetingID]);
        $meetingDetails[1]=$meetingAtd->getAttendees($meetingID);
        $meetingDetails[2]=$agenda->selectandproject('agenda_item',['meeting_id'=>$meetingID])??null;
        $meetingDetails[3]=$memoDis->selectandproject('memo_id',['meeting_id'=>$meetingID])??null;
        $meetingDetails[4]=$minute->selectandproject('Minute_ID',['MeetingID'=>$meetingID])??null;
        if($meetingDetails[0][0]!=null){
            $meetingType=$meetingDetails[0][0]->meeting_type;
            if($meetingType=='iud'){
                $meetingType='iod';
            }
            else if($meetingType=='rhd'){
                $meetingType='rhd';
            }
            else if($meetingType=='syn'){
                $meetingType='syn';
            }
            else if($meetingType=='bom'){
                $meetingType='bom';
            }
            if(in_array(strtoupper($meetingType),$_SESSION['meetingTypes'])){
                $this->view('viewmeetingreport',['meetingDetails'=>$meetingDetails,]);
            }
            else{
                $this->view('meetingreportNotaccess');
            }
                

        }
    
       
        else{
            redirect("login");
        }
    }
}


}

        
?>