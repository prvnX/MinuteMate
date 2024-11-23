<?php
/* User Class */

class User {
    use Model;
    protected $table = 'user';
    
    protected $allowedColumns = [
        'username',
        'password',
        'nic',
        'full_name',
        'email',
        'role',
        'status'
    ]; //editable columns 

    //should add extra functions to  work with advanced concepts
    public function validate($data) {
        $this->errors = [];
        if (empty($this->errors)) {
            return true;
        }
        else{
            return false;
        }
    }

}