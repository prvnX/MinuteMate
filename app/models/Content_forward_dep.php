<?php
class Content_forward_dep{
    use Model;
    protected $table = 'content_forward_dep';
    protected $allowedColumns=[
        'content_id',
        'forward_dep_id',
        'forward_by',
        
    ];
    public function get_dep_forwarded_content($meeting_id){
        $data=[
            'meeting_id'=>$meeting_id
        ];
        $query = "SELECT c.content,c.title,d.dep_email,mt.Minute_ID,m.date,u.full_name,m.meeting_type,d.dep_name,hd.full_name as dhead,hd.email as dheadmail FROM $this->table cfd
                    INNER JOIN content c ON cfd.content_id = c.content_id
                    INNER JOIN minute mt ON mt.Minute_ID = c.minute_id
                    INNER JOIN meeting m ON m.meeting_id = mt.MeetingID
                    INNER JOIN department d ON cfd.forward_dep_id=d.id
                    INNER JOIN user u ON u.username=cfd.forward_by
                    INNER JOIN user hd ON hd.username=d.department_head
                    WHERE m.meeting_id=:meeting_id
        ";
        return $this->query($query,$data);
    }
}