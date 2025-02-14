<?php
class Agenda{
    use Model;
    protected $table='meeting_agenda';

    protected $allowedColumns=[
        'id',
        'meeting_id',
        'agenda_item'
    ];
    

}