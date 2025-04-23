<?php
    class DeletedUsers{
        use Model;
        protected $table = 'deleted_users';
        protected $allowedColumns = [
            'username',
            'full_name',
            'reason',
            'removed_by'
        ]; 

        public function removeUser($username, $fullName, $reason, $removedBy){
            return $this->insert([
                'username' => $username,
                'full_name' => $fullName,
                'reason' => $reason,
                'removed_by' => $removedBy
                ]);
        }

        public function getDeletedInfo($username){
             $query = "SELECT * FROM deleted_users WHERE username = :username";
             $result = $this->query($query, ['username' => $username]);

            return $result ? $result[0] : null;
        }

        public function deleteByUsername($username) {
            $query = "DELETE FROM deleted_users WHERE username = :username";
            $this->query($query, ['username' => $username]);
        }
        



}