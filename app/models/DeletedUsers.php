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

        public function removeUser($username, $fullName, $reason, $removedBy)
{
    return $this->insert([
        'username' => $username,
        'full_name' => $fullName,
        'reason' => $reason,
        'removed_by' => $removedBy
    ]);
}


}