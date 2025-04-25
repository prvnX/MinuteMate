<?php
class Recorrect_Minutes{
    use Model;
    protected $table = 'Recorrect_Minutes';
    protected $allowedColumns = [
        'Minute_ID',
        'recorrected_version'
    ];

    
}