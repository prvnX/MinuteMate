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


}