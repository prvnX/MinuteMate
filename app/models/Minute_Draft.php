<?php 
class Minute_Draft{
    use Model;
    protected $table = "minute_drafts";
    protected $allowedColumns = [
        'username',
        'meeting_id',
        'draft_data'
    ];

    public function addToDraft($username,$meeting_id,$draft_data){
        $data=[
            'username'=>$username,
            'meeting_id'=>$meeting_id,
            'draft_data'=>$draft_data
        ];
        $query ="
            INSERT INTO $this->table (username, meeting_id, draft_data)
            VALUES (:username, :meeting_id, :draft_data)
            ON DUPLICATE KEY UPDATE draft_data = :draft_data";
        return $this->queryExec($query,$data);
    }

    public function isDraftExist($username,$meeting_id){
        $data=[
            'username'=>$username,
            'meeting_id'=>$meeting_id
        ];
        $query = "SELECT EXISTS(
                    SELECT 1 FROM $this->table WHERE username = :username AND  meeting_id = :meeting_id
                    ) AS is_present";
        return $this->query($query,$data);
    }


}