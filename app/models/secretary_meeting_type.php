<?php
class secretary_meeting_type{
    use Model;
    protected $table = 'secretary_meeting_type';
    protected $allowedColumns = [
        'username',
        'meeting_type_id'
    ];
    public function getSecMeeting($username){
        $data['username'] = $username;
        $query = "SELECT meeting_type FROM $this->table INNER JOIN meeting_types ON secretary_meeting_type.meeting_type_id = meeting_types.type_id WHERE username = :username";
        return $this->query($query, $data);
    }
}