<?php
class Meeting_forward_Transaction{
    use Model;
    //Content_Forward_meeting table
    protected $cfmTable='content_forward_meeting';
    protected $cfmColumns=['content_id','meeting_type','forward_by','forwarded_meeting'];
    //meeting agenda table

    protected $maTable='meeting_agenda';
    protected $maColumns=['meeting_id','agenda_item'];

    public function forwardcontent($meeting_id,$newAgendaItem,$content_id){
        $data['meeting_id']=$meeting_id;
        $data['agenda_item']=$newAgendaItem;
        try{
            $this->beginTransaction();
            $isDataInserted=$this->insertToTable($this->maTable,$this->maColumns,$data);
            if(!$isDataInserted){
                throw new Exception("Error inserting data to meeting_agenda table");
            }
            $datatoUpdate['forwarded_meeting']=$meeting_id;
            $isDataInserted=$this->updateTheTable($this->cfmTable,$content_id,$this->cfmColumns,$datatoUpdate,'content_id');
            if(!$isDataInserted){
                throw new Exception("Error updating data to content_forward_meeting table");
            }
            $this->commit();
            return true; 
        }
    catch(Exception $e){
        $this->rollBack();
        return false;
    }
}
}