<?php
    class UserRoles{
        use Model;
        protected $table = 'user_roles';
        protected $allowedColumns = [
            'username',
            'role'
        ]; 
    }