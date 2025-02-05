<?php
class Selectrole extends Controller{
    
    public function index(){
        $secMeetingTypes = new secretary_meeting_type;
        if (!isset($_SESSION['userDetails'])|| sizeof($_SESSION['roles'])< 2) {
            redirect("login");
            exit;
        }
        $secretaryMeetingTypes = $secMeetingTypes->getSecMeeting($_SESSION['userDetails']->username);
        $secMeetingTypeArray = [];
        foreach ($secretaryMeetingTypes as $secMeetingType) {
                $secMeetingTypeArray[] = $secMeetingType->meeting_type;
            }
        $_SESSION['secMeetingTypes'] = $secMeetingTypeArray;
        $this->view("selectrole");
    }
    public function handlelogin(){
        if(isset($_POST['role'])){
            $role = strtolower($_POST['role']);
            if($role == "secretary"){
                $_SESSION['userDetails']->role = "secretary";
                redirect("secretary");
            }
            else if($role == "board member"){
                $_SESSION['userDetails']->role = "lecturer";
                redirect("lecturer");
            }
        }
    }
}
