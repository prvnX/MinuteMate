<?php
class User_edit_requests{
    use Model;
    protected $table = "user_edit_requests";
    protected $allowedColumns=[
        'id',
        'username',
        'new_fullname',
        'new_nic',
        'new_email',
        'new_tp_no',
        'new_additional_tp_no',
        'new_department',
        'message'
    ];
    
    function addUserRequest($field,$value,$message){
        $field[] = 'username';
        $value[] = $_SESSION['userDetails']->username;
        $field[] = 'message';
        $value[] = $message;
        $data = array_combine($field, $value);
        $this->insert($data);
    }

}