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
            'reviewed_by'
        ];
        public function insertx($data)
        {
            $columns = array_intersect_key($data, array_flip($this->allowedColumns));
            $keys = implode(',', array_keys($columns));
            $values = ':' . implode(',:', array_keys($columns));

            $query = "INSERT INTO $this->table ($keys) VALUES ($values)";
            return $this->query($query, $columns); // Assuming `query` is defined in Model
        }

    }

