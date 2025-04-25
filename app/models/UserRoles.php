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

        public function updateRoles($username, $roles) {
            $this->query("DELETE FROM user_roles WHERE username = :username", ['username' => $username]);
            foreach ($roles as $role) {
                $this->query("INSERT INTO user_roles (username, role) VALUES (:username, :role)", ['username' => $username, 'role' => $role]);
            }
        }

        public function getAdminUsername() {
            $query = "SELECT username FROM user_roles WHERE role = 'admin' LIMIT 1";
            $result = $this->get_row($query);
        
            return $result ? $result->username : null;
        }
        
        
        

}