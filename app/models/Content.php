<?php 
class Content{
    use Model;
    protected $table='content';
    protected $allowedColumns=[
        'title',
        'content',
        'minute_id'
    ];




}
