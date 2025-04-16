<?php
class Department{
    use Model;
    protected $table = 'department';
    protected $allowedColumns = [
        'id',
        'dep_name',
        'department_head',
        'dep_email'
    ];
}