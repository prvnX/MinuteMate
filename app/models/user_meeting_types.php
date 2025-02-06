<?php

class User_Meeting_Types
{
    use Database;
    use Model;
    

    protected $table = 'user_meeting_types';

    /**
     * Inserts meeting types for a given user into the database.
     *
     * @param string $username - The username of the accepted user
     * @param array $meetingTypeIds - An array of selected meeting type IDs
     */
    public function insertMeetingTypes($username, $meetingTypeIds)
    {
        foreach ($meetingTypeIds as $typeId) {
            $query = "INSERT INTO $this->table (meeting_type_id, accessible_user) VALUES (:meeting_type_id, :accessible_user)";
            $data = [
                ':meeting_type_id' => $typeId,
                ':accessible_user' => $username,
            ];
            $this->query($query, $data);
        }
    }

    /**
     * Get usernames by meeting type ID.
     *
     * @param int $meetingTypeId - The ID of the meeting type
     * @return array - An array of usernames
     */
    public function getUsernamesByMeetingTypeId(int $meetingTypeId): array
    {
        $query = "SELECT accessible_user AS username FROM $this->table WHERE meeting_type_id = :meeting_type_id";
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
    

}
