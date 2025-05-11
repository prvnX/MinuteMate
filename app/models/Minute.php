<?php
class Minute{
    use Model;
    protected $table = 'minute';
    protected $allowedColumns = [
        'Minute_id',
        'Meetingid',
        'title',
        'created_date',
        'updated_by',
        'is_approved',
        'is_recorrect'
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
    public function MinuteListByUser($username){
        $data['username']=$username;
        $query="SELECT mi.Minute_ID,mi.title,m.meeting_id,m.start_time,m.date,m.meeting_type 
                FROM $this->table mi
                INNER JOIN `meeting` m ON mi.MeetingID = m.meeting_id
                INNER JOIN `meeting_types` mty ON m.type_id = mty.type_id
                INNER JOIN `user_meeting_types` u ON mty.type_id = u.meeting_type_id
                WHERE u.accessible_user = :username
                ORDER BY m.date DESC";
        return $this->query($query, $data);
    }
    public function getMinutes()
    {
         
           $query = "SELECT minute_id, title, created_date, meeting.meeting_type 
                      FROM $this->table
                      INNER JOIN meeting ON minute.Meetingid = meeting.Meeting_id";
            return $this->query(query: $query);
        
    }
   public function getMinuteReportDetails($id)
    {
        $query = "SELECT 
                u.full_name AS user,
                m.Minute_ID, 
                m.MeetingID, 
                m.title, 
                m.created_date, 
                m.created_by,
                meeting.meeting_type,
                GROUP_CONCAT(DISTINCT ml.minutes_linked ORDER BY ml.id SEPARATOR ',') AS linked_minutes,
                GROUP_CONCAT(DISTINCT mk.Keyword ORDER BY mk.Keyword SEPARATOR ',') AS keywords
            FROM 
                minute AS m
            INNER JOIN 
                meeting ON m.MeetingID = meeting.Meeting_id
            INNER JOIN 
                user u ON m.created_by = u.username
            LEFT JOIN 
                minutes_linked ml ON m.Minute_ID = ml.minute_id
            LEFT JOIN 
                minute_Keywords mk ON m.Minute_ID = mk.Minute_ID
            WHERE 
                m.Minute_ID = :Minute_ID
                AND meeting.meeting_type IS NOT NULL
            GROUP BY 
                m.Minute_ID, m.MeetingID, m.title, m.created_date, m.created_by, meeting.meeting_type, u.full_name;

            ";
         
        return $this->query($query, ['Minute_ID' => $id])[0] ?? null;
    }

    public function getPreviousMinute($meeting_time,$meeting_date,$meeting_type){
        $data['meeting_date'] = $meeting_date;
        $data['meeting_time'] = $meeting_time;
        $data['meeting_type'] = $meeting_type;
        $query="SELECT m.Minute_ID, m.title, mt.meeting_id  
                FROM $this->table m
                INNER JOIN meeting mt ON m.MeetingID = mt.meeting_id
                WHERE 
                (mt.meeting_type = :meeting_type)
                AND
                (
                mt.date < :meeting_date 
                OR (mt.date = :meeting_date AND mt.end_time < :meeting_time)
                )
                AND (
                mt.is_minute = 1 
                AND (
                (m.is_approved = 1 AND m.is_recorrect = 0)
                OR 
                (m.is_approved = 0 AND m.is_recorrect = 1)
                )
                ) ORDER BY mt.date DESC LIMIT 1";
                return $this->query($query, $data)[0] ?? null;
    }
    
    
    
}