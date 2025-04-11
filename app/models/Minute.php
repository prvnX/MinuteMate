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

    public function getMinuteDetails($minuteID){
        $data['minute_id'] = $minuteID;
        $query = "SELECT minute.Minute_id, minute.created_date , user.full_name AS created_by,meeting.meeting_id,meeting.date,meeting.start_time,meeting.end_time,meeting.meeting_type,meeting.location
                    FROM $this->table
                    INNER JOIN meeting ON minute.Meetingid = meeting.meeting_id
                    INNER JOIN user ON meeting.created_by = user.username
                    WHERE minute.Minute_id= :minute_id";
        return $this->query($query, $data);
    }
    
}