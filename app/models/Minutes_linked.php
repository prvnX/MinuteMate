<?php
class Minutes_linked{
    use Model;
    protected $table='minutes_linked';
    protected $allowedColumns=[
        'minute_id',
        'minutes_linked'
    ];
    public function getLinkedMinutes($minute_id){
        $data['minute_id']=$minute_id;
        $query="SELECT minute_id, minutes_linked
                FROM $this->table
                WHERE minute_id = :minute_id OR minutes_linked = :minute_id";
        return $this->query($query, $data);
    }
}