<?php
    class ReviewMemo
    {
        use Model;
        protected $table = 'review_memos';

        protected $allowedColumns=[
            'memo_id',
            'memo_title',
            'memo_content',
            'submitted_by',
            'reviewed_by',
            'meeting_id'
        ];
        public function insertx($data)
        {
            $columns = array_intersect_key($data, array_flip($this->allowedColumns));
            $keys = implode(',', array_keys($columns));
            $values = ':' . implode(',:', array_keys($columns));

            $query = "INSERT INTO $this->table ($keys) VALUES ($values)";
            return $this->query($query, $columns); // Assuming `query` is defined in Model
        }

        public function getReviewMemosForUser($user)
        {
            $query = "SELECT *
                      FROM $this->table rm 
                      WHERE reviewed_by= :username
                      ORDER BY memo_id DESC";
            return $this->query($query, ['username' => $user]);
        }

        public function getMemoById($memo_id)
        {
            $query = "SELECT * 
                      FROM $this->table rm
                      WHERE memo_id = :memo_id";

            return $this->query($query, ['memo_id'=>$memo_id])[0] ?? null;
        }

        public function deleteMemo($memoId)
        {
            $query = "DELETE FROM $this->table 
                    WHERE memo_id = :memo_id";

            $conn = $this->connect();
            $stm = $conn->prepare($query);
            $check = $stm->execute([
                'memo_id' => $memoId
            ]);

            return $check; 
        }

    }

