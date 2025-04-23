<?php 
class Notification{
    use Model;
    protected $table = 'notifications';
    
    protected $allowedColumns = [
        'reciptient',
        'notification_type',
        'notification_message',
        'Ref_ID',
        'is_read',
        'link',
        'created_at'
    ];

    public function removeOldNotifications(){
        $user=$_SESSION['userDetails']->username;
        $query = "DELETE FROM $this->table WHERE is_read = 1 AND reciptient = :reciptient";
        $this->query($query, ['reciptient' => $user]);
    }

    public function updateNotificationState($notification_id){
        $data=[
            'notification_id' => $notification_id,
            'reciptient' => $_SESSION['userDetails']->username
        ];
        $query = "UPDATE $this->table SET is_read = 1 WHERE notification_id = :notification_id AND reciptient = :reciptient";
        $this->query($query, $data);
    }

        

    


}