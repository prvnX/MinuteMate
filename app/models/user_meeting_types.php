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

    /**
     * Inserts meeting types for a given user into the database.
     *
     * @param string $username - The username of the accepted user
     * @param array $meetingTypeIds - An array of selected meeting type IDs
     */
    public function insertMeetingTypes($username, $meetingTypeIds) {
        foreach ($meetingTypeIds as $typeId) {
            $query = "INSERT INTO user_meeting_types (accessible_user, meeting_type_id) 
                      VALUES (:accessible_user, :meeting_type_id)";
            $this->query($query, [
                'accessible_user' => $username,
                'meeting_type_id' => $typeId
            ]);
        }
        return ['success' => true, 'message' => 'Meeting types successfully added.'];
    }
    

    /**
     * Get usernames by meeting type ID.
     *
     * @param int $meetingTypeId - The ID of the meeting type
     * @return array - An array of usernames
     */
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


    public function updateMeetingTypes($username, $meetingTypeIds) {
        // Delete existing records for the user
        $query = "DELETE FROM user_meeting_types WHERE accessible_user = :username";
        $this->query($query, ['username' => $username]);

        // Insert new meeting types
        foreach ($meetingTypeIds as $typeId) {
            $query = "INSERT INTO user_meeting_types (accessible_user, meeting_type_id) 
                      VALUES (:accessible_user, :meeting_type_id)";
            $this->query($query, [
                'accessible_user' => $username,
                'meeting_type_id' => $typeId,
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
