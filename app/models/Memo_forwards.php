<?php
class Memo_forwards{
    use Model;
    protected $table='Memo_forwards';
    protected $allowedColumns=['Forwarded_Memo_id','Forwarded_to','Forwarded_Date'];

    public function getmemoList($meeting_id){
        $data['Forwarded_to']=$meeting_id;
        $query="SELECT closed_at,is_forwarded,mm.meeting_id AS meeting_id,memo_title,memo_content,memo_id,status,submitted_by FROM $this->table m
                INNER JOIN memo mm ON m.Forwarded_Memo_id=mm.memo_id
                WHERE m.Forwarded_to = :Forwarded_to";
        return $this->query($query,$data);
    }
}