<?php
class Approved_minutes{
    use Model;
    protected $table = 'Approved_Minutes';
    protected $allowedColumns = [
        'Minute_ID',
        'Approved_Meeting_ID'
    ];

    public function getApprovedMinute($Minute)
    {   
        $data=[
            'Minute_ID' => $Minute
        ];
        $query = "SELECT Approved_Meeting_ID as approvedMeeting, mi.Minute_ID , m.meeting_type, m.date , mi.Minute_ID AS approval FROM $this->table am
                    INNER JOIN meeting m ON am.Approved_Meeting_ID=m.meeting_id
                    INNER JOIN minute mi ON mi.MeetingID=m.meeting_id
                    WHERE am.Minute_ID = :Minute_ID LIMIT 1" ;

        return $this->query($query, $data);
    }
        

}
    
    