<?php
class Minute{
    use Model;
    protected $table = 'minute';
    protected $allowedColumns = [
        'Minute_id',
        'Meetingid',
        'title',
        'created_date',
        'updated_by'
    ];
    public function getMinuteList(){
        $query = "SELECT Minute_id, title, created_date,meeting.meeting_type AS type,meeting.date AS date 
                    FROM $this->table 
                    INNER JOIN meeting ON minute.Meetingid = meeting.Meeting_id 
                    ORDER BY created_date DESC";
        return $this->query($query);
    }
}