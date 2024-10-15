<?php
/* User Class */

class User {
    use Model;
    protected $table = 'users';
    
    protected $allowedColumns = [
        'name',
        'email',
        'password',
        'role',
        'status'
    ]; //editable columns 

    //should add extra functions to  work with advanced concepts


}