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
            'meeting_id',
            'is_forwarded'
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

            public function getPendingMemoCount($type) {
                $data = []; // Initialize an empty array for binding parameters
                
                $query = "SELECT COUNT(*) as count
                          FROM $this->table
                          INNER JOIN meeting ON memo.meeting_id = meeting.meeting_id
                          INNER JOIN meeting_types ON meeting.type_id = meeting_types.type_id
                          WHERE status = 'pending'";
            
                if (!empty($type)) {
                    $placeholders = [];
                    foreach ($type as $key => $value) {
                        $param = ":type" . $key;
                        $placeholders[] = $param;
                        $data[$param] = $value;
                    }
                    $query .= " AND meeting_types.meeting_type IN (" . implode(",", $placeholders) . ")";
                }            
                return $this->query($query, $data);
            }

            public function getTobeForwardedMemos($meeting_id){
                $data['meeting_id']=$meeting_id;
                $query="SELECT * FROM $this->table WHERE meeting_id=:meeting_id AND is_forwarded=0 AND (status='parked' OR status='under_discussion')";
                return $this->query($query,$data);
            }

            public function getMemosByMeetingType($meeting_type){
                $data['meeting_type']=$meeting_type;
                $query="SELECT me.memo_id FROM `memo` me 
                        INNER JOIN meeting m ON me.meeting_id=m.meeting_id 
                        WHERE m.meeting_type=:meeting_type AND me.is_forwarded=0 AND (me.status='parked' OR me.status='under_discussion')";
                return $this->query($query,$data);
            }

    }

?>