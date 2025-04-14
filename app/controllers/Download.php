<?php
use Dompdf\Dompdf;
require __DIR__ . '/../../vendor/autoload.php';
class Download extends Controller{
    
public function index(){
    if($this->isValidRequest()){
        $minuteID=$_GET['minuteID'];
        $user=$_SESSION['userDetails']->username;

        $minuteDetails=$this->getthisMinuteDetails($user,$minuteID);

        $minuteID=$minuteDetails[0]->Minute_id;
        $minuteType=strtoupper($minuteDetails[0]->meeting_type);
        $date=new DateTime($minuteDetails[0]->date);
        $meetngDate=$date->format('d, F Y');
        $createdDate=new DateTime($minuteDetails[0]->created_date);
        $createdDate=$createdDate->format('d, F Y');
        $createdBy=$minuteDetails[0]->created_by;
        $meeting_id=$minuteDetails[0]->meeting_id;
        $meeting_type=strtoupper($minuteDetails[0]->meeting_type);
        $location=$minuteDetails[0]->location;
        $startTime=substr($minuteDetails[0]->start_time,0,-3);
        $endTime=substr($minuteDetails[0]->end_time,0,-3);
        $attendees=$minuteDetails[0]->attendence;
        $contents=$minuteDetails[0]->contents;

        
        $dompdf = new Dompdf();

        $html = <<<HTML
        <style>
            @page {
                margin: 20px 20px 40px 40px;
    
            }
        
            body {
                
                position: relative;
            }
            .footer {
                position: fixed;
                bottom: -60px;
                left: 0;
                right: 0;
                height: 50px;
                text-align: center;
                font-size: 12px;
                color: #666;
            }
        
            .footer .page:after {
                content: counter(page) ;
                font-size: 10px;
            }
            .minute-heading{
                text-align: center;
                font-size: 20px;
                color: #10256a;
                margin-bottom: 20px;
                text-decoration: underline;
                font-family: Arial, sans-serif;
            }
            .minute-details{
                font-size: 12px;
                color: black;
                margin-bottom: 15px;
                font-family: Arial, sans-serif;
            }
            .minute-details .sub-title{
                font-size: 16px;
                color: #1e40af;
                margin-bottom: 10px;
            }       
            .detail-item{
                margin-left: 10px;
            }
            h1,p{
                margin: 5px;
            }
            span{
                font-weight: bold;
            }

            .attendees {
             margin-left: 10px;
            }
            .attendees p {
             display: inline-block;
             width: 30%; 
             margin-bottom: 0;
            }
            .content-item{
                margin-bottom: 40px;
                margin-left: 10px;
            }
            .discussed-title{
                font-family: Arial,sans-serif;
                font-weight: bold ;
                text-decoration: underline;
                font-size: 0.9rem;
                margin-left:-10px;
                margin-bottom: 0;
                margin:0px
                
            }
            .discussed-title p{
                font-family: Helvetica,sans-serif;
                

            }
            .discussed-content{
                font-family: Helvetica, sans-serif;
                font-size: 0.8rem;
                margin: 10px;
            }
            ul{
                list-style-type:circle;
                margin: 0 0 0 -20px;
            }
            .hidden-content-note{
                font-family: Helvetica, sans-serif;
                font-size: 11px;
                margin: 8px;
                color: gray;
            }
            a{
                text-decoration: underline;
                color:rgb(0, 0, 0);
                font-family: Helvetica, sans-serif;
                font-size: 12px;
            }
   
        </style>
        
        
        <!-- Footer -->
        <div class="footer">
            <span class="page"></span>
        </div>

        <h1 class="minute-heading">Minute of $minuteType Meeting on $meetngDate </h1>
        <div class="minute-details">
            <h1 class="sub-title">Minute Details</h1>
            <div class="detail-item">
            <p><span>Minute ID:</span> $minuteID</p>
        </div>
        <div class="detail-item">
        <p><span>Created On:</span> $createdDate </p>
        </div>
        <div class="detail-item">
        <p><span>Created By:</span> $createdBy </p>
        </div>
        </div>

        <div class="minute-details">
            <h1 class="sub-title">Meeting Details</h1> 
            <div class="detail-item">
             <p>   <span>Meeting ID:</span> $meeting_id  </p>
            </div>
        <div class="detail-item">
           <p> <span>Meeting Type:</span> $meeting_type Meeting </p>
        </div>

        <div class="detail-item">
            <p> <span>Date:</span>$meetngDate</p>
        </div>
        <div class="detail-item">
            <p> <span>Time:</span> $startTime  - $endTime   </p>
        </div>
        <div class="detail-item">
            <p> <span>Location:</span> $location </p>
        </div>
        </div>
        <div class="minute-details ">
        <h1 class="sub-title">Participants</h1> 
        <div class="attendees">

        HTML;
            
        // Optional: Footer only on last page
        foreach ($attendees as $attendee) {
            $html .= "<p>" .$attendee->attendee . "</p>";
        }
        $html .= <<<HTML
        </div>
        </div>

        <div class="minute-details">
        <h1 class="sub-title">Agenda Items</h1>
        <div class="detail-item">
        <ul>
        HTML;
        $AgendaItems=$minuteDetails[0]->agendaItems;
        foreach($AgendaItems as $agenda){
            $html.="<li><p>".$agenda->agenda_item."</p></li>";
            
        }
        $html .= "</ul></div></div>";
        $html .= <<<HTML
                <div class="minute-details">
        <h1 class="sub-title">Contents</h1>
        HTML;
        // $contents=$data['contents'];
        if(count($contents)>0){
            foreach($contents as $content){
                $html.= "<div class='content-item'>";
                $html.= "<div class='discussed-title' >";
                $html.= "<p'>".$content->title."</p>";
                $html.= "</div>";
                $html.= "<div class='discussed-content'>";
                $html.= $content->content;
                $html.= "</div>";
                $html.= "</div>";
            }
        }
        
        if($minuteDetails[0]->isContentRestricted){
                $html.= "<div class='hidden-content-note'> <ul><li> Some contents are hidden for this user.</li></ul></div>";
            
        }
        $html .= "</div>";
        $html .= <<<HTML
            <div class="minute-details">
            <h1 class="sub-title">Discussed Memos</h1>
            <div class="detail-item">
        HTML;
       $memos=$minuteDetails[0]->discussed_memos;
       if($memos!=null){
            if(count($memos)>0){
                foreach($memos as $memo){
                    $html.= "<a href='".ROOT."/secretary/viewmemodetails/?memo_id=".htmlspecialchars($memo->memo_id)."' target='_blank'><p>".htmlspecialchars($memo->memo_title)."</p></a>";
                }
            }
        } else{
            $html.= "<div class='hidden-content-note'> <ul><li>No Memos were discussed in this meeting.</li></ul></div>";
        }
        $html.= "</div></div>";
        $html .= <<<HTML
        <div class="minute-details">
            <h1 class="sub-title">Linked Minutes</h1>
            <div class="detail-item">
        HTML;
        $linked_minutes=$minuteDetails[0]->linked_minutes;
        if($linked_minutes!=null){
            if(count($linked_minutes)>0){
                foreach($linked_minutes as $linkedMinute){
                    $html.= "<a href='".ROOT."/secretary/viewminute/?minuteID=".htmlspecialchars($linkedMinute)."' target='_blank'><p> Minute ID : ".htmlspecialchars($linkedMinute)."</p></a>";
                }
            }
        } else{
            $html.= "<div class='hidden-content-note'> <ul><li>No Linked Minutes.</li></ul></div>";
        }
        $html.= "</div></div>";

        $html .= <<<HTML
        <div class="minute-details">
            <h1 class="sub-title">Linked Media</h1>
            <div class="detail-item">
        HTML;
        $linkedMediaFiles=$minuteDetails[0]->linkedMediaFiles;
        if($linkedMediaFiles!=null){
            if(count($linkedMediaFiles)>0){
                foreach($linkedMediaFiles as $media){
                    $html.= "<a href=".$media->media_location." target='_blank'><p>".$media->Name." - ".$media->ext."</p></a>"; 
                }
            }
        } else{
            $html.= "<div class='hidden-content-note'> <ul><li>No Linked Media.</li></ul></div>";
        }
        $html.= "</div></div>";

        $html .= '
        <div style="page-break-before: always; padding-top:500px;text-align: center; color: gray;">
            <hr>
<p>This is an original document verified and signed by MinuteMate. You can confirm its authenticity using the attached digital signature.</p>        </div>
        
        ';
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
         $dompdf->stream("Minute_$minuteID.pdf", ["Attachment" => true]); // false = open in browser
        // show(data: $minuteDetails);
    
    }
    else{
        redirect("login");
    }
}

public function isValidRequest(){
    if(isset($_SESSION['userDetails'])){
        return true;
    }
    else{
        return false;
    }
}
private function isRestrict($username,$contentID){
    $restrictions=new Content_restrictions();
    $res_status=$restrictions->checkRestrictions($username,$contentID);
    if($res_status[0]->is_restricted==1){
        return true;
    }
    else{
        return false;
    }
}
private function getthisMinuteDetails($user,$minuteID){
        $minute = new Minute();
        $Meeting_attendence=new Meeting_attendence();
        $Agenda= new Agenda();
        $content= new Content();
        $memo_discussed= new Memo_discussed_meetings();
        $linkedMinutes=new Minutes_linked();
        $linkedMedia=new Linked_Media();
        $minuteDetails = $minute->getMinuteDetails($minuteID);
        $contentrestrict=false;
        
        if(!$minuteDetails) {
            $this->view('minutenotfound');           
            die;
        }
        else{
        $user_meetings=$_SESSION['meetingTypes'];
        $minuteType=$minuteDetails[0]->meeting_type;
        for($i=0;$i<count($user_meetings);$i++){
            if($user_meetings[$i]=="IOD"){
                $user_meetings[$i]="IUD";
            }
            $user_meetings[$i]=strtolower($user_meetings[$i]);
            }

        if(!in_array($minuteType,$user_meetings)){

            $this->view('accessdenied');
            die;
        }
        else{
        $user_accessible_content=[];
        $linked_minutes=[];
        $isContentRestricted=false;
        $attendence = $Meeting_attendence->getAttendees($minuteDetails[0]->meeting_id);
        $agendaItems=$Agenda->selectandproject('agenda_item',['meeting_id'=>$minuteDetails[0]->meeting_id]);
        $contentDetails=$content->select_all(['minute_id'=>$minuteID]);
        $discussed_memos=$memo_discussed->getMemos($minuteDetails[0]->meeting_id);
        $linkedMinutes=$linkedMinutes->getLinkedMinutes($minuteID);
        $linkedMediaFiles=$linkedMedia->select_all(['minute_id'=>$minuteID]);

        if($linkedMinutes!= null){
            if(count($linkedMinutes)>0){
                foreach($linkedMinutes as $linkedMinute){
                    $linkedMinuteID=$linkedMinute->minutes_linked;
                    $otherMinuteID=$linkedMinute->minute_id;
                    if($linkedMinuteID!=$minuteID){
                        $linked_minutes[]=$linkedMinuteID;
                    }
                    else if($otherMinuteID!=$minuteID){
                        $linked_minutes[]=$otherMinuteID;
                    }
                }
            }

        }
        $linked_minutes=array_unique($linked_minutes);

        foreach ($contentDetails as $contentDetail) {
            $contentID=$contentDetail->content_id;
            if($this->isRestrict($user,$contentID)){
                $isContentRestricted=true;
               continue;
            }else{
                $user_accessible_content[]=$contentDetail;
            }
        }

        $minuteDetails[0]->attendence = $attendence;
        $minuteDetails[0]->agendaItems = $agendaItems;
        $minuteDetails[0]->discussed_memos = $discussed_memos;
        $minuteDetails[0]->linked_minutes = $linked_minutes;
        $minuteDetails[0]->linkedMediaFiles = $linkedMediaFiles;
        $minuteDetails[0]->contents=$user_accessible_content;
        $minuteDetails[0]->isContentRestricted=$isContentRestricted;

        return $minuteDetails;
        }
    }
}




}