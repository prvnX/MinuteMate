<?php
    class UserRoles{
        use Model;
        protected $table = 'user_roles';
        protected $allowedColumns = [
            'username',
            'role'
        ]; 

        public function insert($data) {
            $query = "INSERT INTO user_roles (username, role) VALUES (:username, :role)";
            $this->query($query, $data);
        
            return ['success' => true, 'message' => 'Role successfully assigned to user.'];
        }
        

}