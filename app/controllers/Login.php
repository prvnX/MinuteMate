<?php
class Login extends Controller {
    public function index() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $user = new User;
            $userRoles = new UserRoles;
            $userMeetings = new User_Meeting_Types;
            $secMeetingTypes = new secretary_meeting_type;


            $username = $_POST['username'];
            $password = $_POST['password'];
            $row = $user->select_all(["username" => $username,"status"=>"Active"]);

            if ($row) {                
                if (password_verify($password, $row[0]->password)) {
                    $roleRow = $userRoles->select_all(["username" => $username]);
                    $userMeetingTypes= $userMeetings->getUserMeetingTypes($username);
                    $meetingTypes =[];
                    $roleArray = [];
                    foreach ($userMeetingTypes as $meetingType) {
                        $meetingTypes[] = $meetingType->meeting_type;
                    }
                    foreach ($roleRow as $role) {
                        $roleArray[] = $role->role;
                    }
                    $_SESSION['userDetails'] = $row[0];
                    $_SESSION['roles'] = $roleArray;
                    $_SESSION['meetingTypes'] = $meetingTypes;

                    if (sizeof($roleArray) > 1) {
                        echo "Multiple roles found";
                        show($roleArray);
                        redirect("selectrole");
                    } else {
                        // If only one role, proceed with login
                        $role = $roleArray[0];
                        if($role == "secretary"){
                            $secretaryMeetingTypes = $secMeetingTypes->getSecMeeting($username);
                            $secMeetingTypeArray = [];
                            foreach ($secretaryMeetingTypes as $secMeetingType) {
                                $secMeetingTypeArray[] = $secMeetingType->meeting_type;
                            }
                            $_SESSION['secMeetingTypes'] = $secMeetingTypeArray;
                        }
                        $this->setUserRoleAndRedirect($role);
                    }
                } else {
                    $user->errors['invalid'] = "Invalid Password";
                    $this->view("login", ["err" => $user->errors]);
                }
            } else {
                if($row = $user->select_all(["username" => $username])){
                    $user->errors['invalid'] = "Account is Not Actived";
                    $this->view("login", ["err" => $user->errors]);
                    
                }
                else{
                    $user->errors['invalid'] = "Username Not Found";
                    $this->view("login", ["err" => $user->errors]);
                }

            }
        } else {
            $this->view("login", ['err' => ['invalid' => '']]);
        }
    }

    private function setUserRoleAndRedirect($role) {
        $rolePaths = [
            "admin" => "admin",
            "lecturer" => "lecturer",
            "studentrep" => "studentrep",
            "secretary" => "secretary"
        ];

        if (array_key_exists($role, $rolePaths)) {
            $_SESSION['userDetails']->role = $role;
            redirect($rolePaths[$role]);
        } else {
            redirect("login");
        }
    }
}