<?php
class Memo_discussed_meetings{
    use Model;
    protected $table='memo_discussed_meetings';
    protected $allowedColumns=[
        'memo_id',
        'meeting_id'
    ];
    public function getMemos($meeting_id){
        $data['meeting_id']=$meeting_id;
        $query="SELECT memo.memo_id AS memo_id, memo.memo_title AS memo_title
                FROM $this->table
                INNER JOIN memo ON memo_discussed_meetings.memo_id = memo.memo_id
                WHERE memo_discussed_meetings.meeting_id = :meeting_id";
        return $this->query($query, $data);
    }
                
}