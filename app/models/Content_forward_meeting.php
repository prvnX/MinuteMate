<?php
class Content_forward_meeting{
    use Model;
    private $table='content_forward_meeting';
    private $allowedColumns=['content_id','meeting_type','forward_by','forwarded_meeting'];
    public function forwardedContentMeetings($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query="SELECT c.title,c.content_id,cfm.meeting_type,m.date as meeting_date FROM $this->table cfm
                INNER JOIN content c ON c.content_id=cfm.content_id
                INNER JOIN minute mt ON mt.Minute_ID=c.minute_id
                INNER JOIN meeting m ON m.meeting_id=mt.MeetingID
                WHERE mt.MeetingID = :meeting_id && cfm.forwarded_meeting IS NULL";
       return  $this->query($query,$data);
    }
    public function getforwardedListByType($type){
        $data['meeting_type']=$type;
        $query="SELECT c.title,c.content_id,cfm.meeting_type,m.date as meeting_date FROM $this->table cfm
                INNER JOIN content c ON c.content_id=cfm.content_id
                INNER JOIN minute mt ON mt.Minute_ID=c.minute_id
                INNER JOIN meeting m ON m.meeting_id=mt.MeetingID
                WHERE cfm.meeting_type = :meeting_type && cfm.forwarded_meeting IS NULL";
        return  $this->query($query,$data);
    }
    public function getLinkMinuteIds($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query="SELECT DISTINCT c.Minute_ID FROM $this->table cfm
                INNER JOIN content c ON c.content_id=cfm.content_id
                WHERE cfm.forwarded_meeting = :meeting_id";
       return  $this->query($query,$data);
    }

}