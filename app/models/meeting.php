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

    public function getmeetingsforuser($date , $user){
        $data['date'] = $date;
        $data['accessible_user'] = $user;

        $query = "
                    SELECT m.*
                    FROM
                         meeting m
                    JOIN 
                        user_meeting_types umt ON m.type_id = umt.meeting_type_id
                    WHERE 
                        umt.accessible_user = :accessible_user
                    AND 
                        m.date > :date
                    ORDER BY m.date ASC;
                    ";

            return $this->query($query, $data);
    }

    public function getNoMinuteMeetings($user,$date){
        $data['username']=$user;
        $data['date']=$date;
        $query = "SELECT 
                    meeting.meeting_id AS id,meeting.meeting_type AS name,meeting.date AS date 
                FROM
                    $this->table
                WHERE 
                    meeting.date<=:date
                    AND meeting.is_minute=0
                    AND meeting.created_by=:username";
        return $this->query($query,$data);
    }
    public function authUserforMinute($meeting_id,$user){
        $data['meeting_id']=$meeting_id;
        $data['username']=$user;
        $query = "SELECT EXISTS(
                    SELECT 1 FROM $this->table
                    WHERE 
                        meeting_id=:meeting_id
                        AND created_by=:username
                    ) AS auth";
        return $this->query($query,$data);
    }

    public function getParticipants($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query = "SELECT 
                    user_meeting_types.accessible_user AS username,
                    user.full_name AS name
                FROM 
                    $this->table
                INNER JOIN 
                    user_meeting_types ON meeting.type_id = user_meeting_types.meeting_type_id
                INNER JOIN
                    user ON user_meeting_types.accessible_user = user.username
                WHERE 
                    meeting.meeting_id = :meeting_id";
        return $this->query($query,$data);
    }

    public function getMeetingsInWeek($today,$lastDate,$username){
        $data['today']=$today;
        $data['lastDate']=$lastDate;
        $data['username']=$username;
        $query = "SELECT 
                    meeting.meeting_id AS id,
                    meeting.date AS date,
                    meeting.meeting_type AS name
                FROM 
                    $this->table
                INNER JOIN 
                    user_meeting_types ON meeting.type_id = user_meeting_types.meeting_type_id
                WHERE 
                    meeting.date BETWEEN :today AND :lastDate
                    AND user_meeting_types.accessible_user = :username ";
        return $this->query($query,$data);

    }

    public function getLatestMeeting($type){
        $data['type']=strtolower($type);
        $data['date']=date("Y-m-d");
        $query="SELECT m.meeting_id,m.meeting_type
                FROM $this->table m
                WHERE m.meeting_type=:type AND date > :date ORDER BY date ASC LIMIT 1";
        return $this->query($query,$data);
    }


    public function getSecForMeeting($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query="SELECT username
                FROM meeting m
                INNER JOIN secretary_meeting_type smt ON m.type_id = smt.meeting_type_id
                WHERE m.meeting_id = :meeting_id";
        return $this->query($query,$data);
    }

    public function getMeetingTypeByID($meetingId)
    {
        $data['meetingId'] = $meetingId;
        $query = "SELECT type_id FROM $this->table
                WHERE  meeting_id=:meetingId";

        return $this->query($query, $data);
    }

    public function getMostRecentMinutePending($type,$date){
        $data['type']=strtolower($type);
        $data['date']=$date;
        $query="SELECT *
                FROM meeting m
                INNER JOIN minute mi ON m.meeting_id = mi.MeetingID
                WHERE m.meeting_type=:type AND date <=:date AND is_minute=1 AND is_approved=0 AND is_recorrect=0 ORDER BY date DESC LIMIT 1;";
        return $this->query($query,$data);

    }

    public function isFinishedMeeting($user,$date,$time){
        $data['username']=$user;
        $data['date']=$date;
        $data['time']=$time;
        $query="SELECT EXISTS(
                    SELECT 1 FROM $this->table
                    WHERE 
                        date<=:date
                        AND end_time<=:time
                        AND created_by=:username
                    ) AS auth";
        return $this->query($query,$data);
        
    }


}