<?php
class Events extends Controller
{
    private $fetchData;
    public function index()
    {
        $date=$_GET['date'];
        if($this->isValidRequest()){
            $this->findMeetingforDate();


            $this->view("viewevents",['date'=>$date,'fetchData'=>$this->fetchData]);
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
                $meeting->delete($meeting_id,'meeting_id');
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
            $meeting->insert(['date'=>$date,'meeting_type'=>$meetingType,'start_time'=>$startTime,'end_time'=>$endTime,'location'=>$location,'additional_note'=>$additionalNote,'created_by'=>$createdBy,'type_id'=>$type_id]);
            echo json_encode(["success" => "Meeting Added Successfully"]);
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
    $input = json_decode( file_get_contents('php://input'), true);
    $meeting_id = $input['meeting_id'] ?? null;
    if ($meeting_id) {


        $memosList = $memo->select_all(['meeting_id' => $meeting_id]);
        if($memosList){
            echo json_encode(["success" => true, "memos" => $memosList]);
        }
        else{
            echo json_encode(["error" => "No memos found for the given meeting ID"]);
        }
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
}

        
?>