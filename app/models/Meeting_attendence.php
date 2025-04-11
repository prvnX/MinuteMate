<?php
class Meeting_attendence{
    use Model;
    protected $table = 'meeting_attendence';
    protected $allowedColumns = [
        'meeting_id',
        'attendee'
    ];
    public function getAttendees($meeting_id){
        $data['meeting_id'] = $meeting_id;
        $query = "SELECT user.full_name AS attendee
                    FROM $this->table
                    INNER JOIN user ON meeting_attendence.attendee = user.username
                    WHERE meeting_attendence.meeting_id = :meeting_id";
        return $this->query($query, $data);
    }
}