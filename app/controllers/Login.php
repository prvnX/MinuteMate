<?php
class Login extends Controller {
    public function index() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $user = new User();            
            $username = $_POST['username'];
            $password = $_POST['password'];
            $row = $user->select_all(["username"=>$username]);
            if($row){                
                if(password_verify($password,$row[0]->password)){
                    $role=$row[0]->role;
                    if($role=="admin"){
                        $path="admin";
                    }
                    elseif($role=="lecturer"){
                        $path="lecturer";
                    }
                    elseif($role=="studentrep"){
                        $path="studentrep";
                    }
                    elseif($role=="secretary"){
                        $path="secretary";
                    }
                    else{
                        redirect("login");
                    }
                    session_start();
                    $_SESSION['userDetails'] = $row[0];
                    redirect($path);
                }
                else{
                    $user->errors['invalid']="Invalid Password";
                    $this->view("login",[ "err" => $user->errors]);
                    
                }
            }
            else{
                $user->errors['invalid']="Username Not Found";
                $this->view("login",[ "err" => $user->errors]);
            }
            
        }else{
            $this->view("login",['err'=>['invalid'=>'']]);
        }

    }
}