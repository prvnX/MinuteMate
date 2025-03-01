<?php
class Agenda{
    use Model;
    protected $table='meeting_agenda';

    protected $allowedColumns=[
        'id',
        'meeting_id',
        'agenda_item'
    ];
    public function getAgendaItems($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query="SELECT m.date AS date , m.meeting_type AS type , c.title,c.content FROM content_forward_meeting cfm
        INNER JOIN content c ON cfm.content_id=c.content_id
        INNER JOIN minute mt ON mt.Minute_ID=c.minute_id
        INNER JOIN meeting m ON m.meeting_id=mt.MeetingID
        WHERE cfm.forwarded_meeting=:meeting_id
        ";
        
        return $this->query($query,$data);
    }

}