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
    public function findMeetingforDate(){
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
}
?>