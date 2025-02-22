<?php
class Content_forward_meeting{
    use Model;
    private $table='content_forward_meeting';
    private $allowedColumns=['content_id','meeting_type','forward_by','forwarded_meeting'];
    public function forwardedContentMeetings($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query="SELECT c.title,c.content_id FROM $this->table cfm
                INNER JOIN content c ON c.content_id=cfm.content_id
                INNER JOIN minute mt ON mt.Minute_ID=c.minute_id
                WHERE mt.MeetingID = :meeting_id && cfm.forwarded_meeting IS NULL";
       return  $this->query($query,$data);
    }

}