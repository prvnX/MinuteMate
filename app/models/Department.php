<?php
class Department{
    use Model;
    protected $table = 'department';
    protected $allowedColumns = [
        'id',
        'dep_name',
        'dep_head',
        'dep_email'
    ];
}