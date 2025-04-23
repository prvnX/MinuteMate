<?php 
class NotificationService extends Controller{

    public function getNotifications(){
        $username=$_SESSION['userDetails']->username;
        $notificationModel = new Notification();
        $notificationList=$notificationModel->select_all(['reciptient'=>$username, 'is_read'=>0]);
        $notificationjson= json_encode($notificationList);
        if($notificationjson){
            echo json_encode(["success" => "success", "notifications" => $notificationList]);
        }
        else{
            echo json_encode(["error" => "No notifications found"]);
        }
    }

    public function updateNotificationState(){
        $notification_id=json_decode(file_get_contents('php://input'), true)['notification_id'];
        $notificationModel = new Notification();
        $notificationModel->updateNotificationState($notification_id);
        echo json_encode(["success" => "Notification updated successfully"]);
    }

    public function getNotificationCount(){
        $username=$_SESSION['userDetails']->username;
        $notificationModel = new Notification();
        $count=$notificationModel->selectandproject('Count(notification_id) AS Count',['reciptient'=>$username,'is_read'=>0])[0]->Count;
        echo json_encode(['success'=>true,'count'=>$count]);
    }
    public function deleteNotifications(){
        $state=json_decode(file_get_contents('php://input'), true)['deleteOldNotifications'];
        if($state!=true){
            echo json_encode(['error' => 'Invalid request']);
            return;
        }
        $notificationModel = new Notification();
        $notificationModel->removeOldNotifications();
        echo json_encode(['success' => true]);
    }

}