<?php
class Home extends Controller {
    public function index($param1="",$param2="",$param3="") {
        // $user=new User;
        // $arr['name']='new';
        // $arr['age']=15;
        // $result=$user->insert($arr);
        // $result=$user->find_all();
        // show($result);
        //calling home view
        //show($param1.$param2);
        $this->view("home");

    }
    /*
    public function about($param1="",$param2="",$param3="") {
        //calling home view
        echo "Edit";
        show($param1.$param2);
        $this->view("edit");
    }*/
    


}
