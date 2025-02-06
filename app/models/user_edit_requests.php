<?php
class User_edit_requests{
    use Model;
    protected $table = "user_edit_requests";
    protected $allowedColumns=[
        'id',
        'username',
        'new_fullname',
        'new_nic',
        'new_email',
        'new_tp_no',
        'new_additional_tp_no',
        'new_department',
        'message'
    ];
    
    function addUserRequest($field,$value,$message){
        $field[] = 'username';
        $value[] = $_SESSION['userDetails']->username;
        $field[] = 'message';
        $value[] = $message;
        $data = array_combine($field, $value);
        $this->insert($data);
    }

    public function find_requests() {
        $query = "SELECT req.*, u.full_name 
                  FROM $this->table req 
                  INNER JOIN user u ON req.username = u.username 
                  ORDER BY req.created_at DESC";
        
        return $this->query($query);
    
    
    }
    public function find_request_by_id($id) {
        $data['id']=$id;
        $query= "SELECT * 
                  FROM $this->table req 
                  INNER JOIN user u ON req.username = u.username 
                  where req.id = :id";
        $changes=$this->query($query,$data);
        return $changes;
    }

    public function deleteRequestById($id) {

         $this->delete($id);
         return ['success' => true];          
                    

}
}