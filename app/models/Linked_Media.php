<?php
class Linked_Media{
    use Model;
    protected $table='linked_media';
    protected $allowedColumns=[
        'Name',
        'minute_id',
        'media_location',
        'ext'
    ];

}