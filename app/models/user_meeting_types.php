<?php

class User_Meeting_Types
{
    use Database;
    use Model;
    

    protected $table = 'user_meeting_types';
    protected $allowedColumns = [
        'meeting_type_id',
        'accessible_user'
    ];


    public function insertMeetingTypes($username, $meetingTypes) {
        foreach ($meetingTypes as $type) {
            $queryx = "SELECT type_id FROM meeting_types WHERE meeting_type = :type";
            $result = $this->query($queryx, ['type' => $type]);
    
            if (!empty($result)) {
                $typeID = $result[0]->type_id;
    
                $query = "INSERT INTO user_meeting_types (accessible_user, meeting_type_id) 
                          VALUES (:accessible_user, :meeting_type_id)";
                $this->query($query, [
                    'accessible_user' => $username,
                    'meeting_type_id' => $typeID
                ]);
            } else {
                error_log("Meeting type '$type' not found in meeting_types table.");
            }
        }
    }
    

    public function getUsernamesByMeetingTypeId(int $meetingTypeId): array
{
    $query = "SELECT u.username
              FROM user_meeting_types umt
              JOIN user u ON umt.accessible_user = u.username
              WHERE umt.meeting_type_id = :meeting_type_id
              AND u.status = 'active'";

    $data = [':meeting_type_id' => $meetingTypeId];
    $result = $this->query($query, $data);
    return $result ? $result : [];
}


    public function updateMeetingTypes($username, $meetingTypes) {
        
        $query = "DELETE FROM user_meeting_types WHERE accessible_user = :username";
        $this->query($query, ['username' => $username]);

        foreach ($meetingTypes as $type) {
            $queryx="SELECT type_id FROM meeting_types WHERE meeting_type=:type";
            $typeID=($this->query($queryx,['type'=>$type]))[0]->type_id;
            

            $query = "INSERT INTO user_meeting_types (accessible_user, meeting_type_id) 
                      VALUES (:accessible_user, :meeting_type_id)";
            $this->query($query, [
                'accessible_user' => $username,
                'meeting_type_id' => $typeID
            ]);
        }
    }

    public function getUserMeetingTypes($username) {
        $data['username'] = $username;
        $query = "SELECT meeting_type FROM user_meeting_types um INNER JOIN meeting_types mt ON um.meeting_type_id = mt.type_id WHERE accessible_user = :username";
        return $this->query($query, $data);
    }

    public function getInactiveMembersByMeetingType($meetingTypeId)
    {
        $query = "SELECT u.username, u.full_name
              FROM user_meeting_types umt
              JOIN user u ON umt.accessible_user = u.username
              WHERE umt.meeting_type_id = :meeting_type_id
              AND u.status = 'inactive'";

    $data = [':meeting_type_id' => $meetingTypeId];
    $result = $this->query($query, $data);
    return $result ? $result : [];
    }
}
