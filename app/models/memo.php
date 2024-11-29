<?php
    class Memo{
        use Model;
        protected $table = 'memo';

        protected $allowedColumns=[
            'memo_id',
            'memo_title',
            'memo_content',
            'status',
            'submitted_by',
            'meeting_id'
        ];
        public function insert($data)
        {
            $columns = array_intersect_key($data, array_flip($this->allowedColumns));
            $keys = implode(',', array_keys($columns));
            $values = ':' . implode(',:', array_keys($columns));

            $query = "INSERT INTO $this->table ($keys) VALUES ($values)";
            return $this->query($query, $columns); // Assuming `query` is defined in Model
        }

        public function getAllMemos(){
            $query = "SELECT * 
                      FROM $this->table
                      ORDER BY memo_id 
                      DESC;";
            return $this->query($query);
        }

        public function getAllAcceptedMemos(){
            $query = "SELECT * 
                      FROM $this->table
                      WHERE status='accepted'
                      ORDER BY memo_id 
                      DESC;";
            return $this->query($query);
        }

        public function getMemoById($memo_id)
        {
            $query = "SELECT *
                       FROM $this->table 
                       WHERE memo_id = :memo_id;";
            return $this->query($query, ['memo_id'=> $memo_id])[0] ?? null; 
        }

        public function getMemosByUser($user)
        {
            $query = "SELECT *
                      FROM $this->table 
                      WHERE submitted_by=:user;";

            return $this->query($query, ['user'=> $user]);
        }

        public function getMemosForMemocart($user)
        {
            $query = "SELECT *
                      FROM $this->table 
                       WHERE submitted_by != :user && status='pending';";

            return $this->query($query,['user'=>$user] );
        }

        public function updateStatus($memoId, $status)
        {
            $query = "UPDATE $this->table 
                    SET status = :status 
                    WHERE memo_id = :memo_id";

            return $this->query($query, [
                'status' => $status,
                'memo_id' => $memoId
            ]);
        }

        public function deleteMemo($memoId)
        {
            $query = "DELETE FROM $this->table 
                    WHERE memo_id = :memo_id";

            return $this->query($query, [
                'memo_id' => $memoId
            ]);
        }




    }

?>