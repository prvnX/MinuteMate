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

    public function insertSecretaryMeetingTypes($username, $meetingTypes) {
        foreach ($meetingTypes as $type) {
            $result = $this->query("SELECT type_id FROM meeting_types WHERE meeting_type = :type", [
                'type' => $type
            ]);
    
            if (!empty($result)) {
                $typeID = $result[0]->type_id;
    
                $this->query("INSERT INTO secretary_meeting_type (username, meeting_type_id) 
                              VALUES (:username, :meeting_type_id)", [
                    'username' => $username,
                    'meeting_type_id' => $typeID
                ]);
            }
        }
    }

    public function updateMeetingTypes($username, $meetingTypes) {
        // Step 1: Delete existing secretary meeting types for the user
        $this->query("DELETE FROM secretary_meeting_type WHERE username = :username", [
            'username' => $username
        ]);
    
        // Step 2: Re-insert the new selected types
        foreach ($meetingTypes as $type) {
            $result = $this->query("SELECT type_id FROM meeting_types WHERE meeting_type = :type", [
                'type' => $type
            ]);
    
            if (!empty($result)) {
                $typeID = $result[0]->type_id;
    
                $this->query("INSERT INTO secretary_meeting_type (username, meeting_type_id) 
                              VALUES (:username, :meeting_type_id)", [
                    'username' => $username,
                    'meeting_type_id' => $typeID
                ]);
            }
        }
    }
    
    public function getsecMeetingTypes($username) {
        $data['username'] = $username;
        $query = "SELECT meeting_type FROM secretary_meeting_type s INNER JOIN meeting_types mt ON s.meeting_type_id = mt.type_id WHERE username = :username";
        return $this->query($query, $data);
    }
    
    public function deleteMeetingTypesByUsername($username) {
        $this->query("DELETE FROM secretary_meeting_type WHERE username = :username", [
            'username' => $username
        ]);
    }
    
    
}