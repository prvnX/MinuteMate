<?php
class Meeting{
    use Model;
    protected $table = 'meeting';
    
    protected $allowedColumns = [
        'meeting_id',
        'date',
        'meeting_type',
        'start_time',
        'end_time',
        'location',
        'created_by',
        'is_minute',
        'type_id',
        'additional_note'
    ]; //editable columns
    public function showMeeting(){ //for calendar
        $query="select meeting_id,date,meeting_type from $this->table";
        return $this->query($query);
    }
    public function getMeetingByDateUser($date,$user=''){
        $data['date']=$date;
        $data['username']=$user;
        $query = "SELECT 
                    meeting.*, 
                    IFNULL(COUNT(memo.memo_id), 0) AS memos,
                    creator.full_name AS created_by_name
                FROM 
                    $this->table AS meeting
                INNER JOIN 
                    user_meeting_types ON user_meeting_types.meeting_type_id = meeting.type_id
                INNER JOIN 
                    user ON user_meeting_types.accessible_user = user.username
                LEFT JOIN 
                    memo ON memo.meeting_id = meeting.meeting_id
                INNER JOIN 
                    user AS creator ON meeting.created_by = creator.username
                WHERE 
                    user.username = :username
                    AND meeting.date = :date
                GROUP BY 
                    meeting.meeting_id";


        return $this->query($query,$data);
    }

    public function getMeetingByDate($date){
        $data['date']=$date;
        $query = "
                SELECT count(*) as count
                FROM 
                    $this->table
                WHERE 
                    date = :date;";
        return $this->query($query,$data);
    }

}