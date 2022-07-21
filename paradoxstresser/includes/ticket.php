<?php
include 'configuration.php'; 
include 'extra.php';
$hdejnedi = $userInf['message'];
?>

                                                                <?php
      $SQLGetMessages = $odb -> prepare("SELECT * FROM `messages` WHERE `ticketid` = :ticketid ORDER BY `messageid` ASC");
      $SQLGetMessages -> execute(array(':ticketid' => $userInf['message']));
      while ($getInfo = $SQLGetMessages -> fetch(PDO::FETCH_ASSOC)){
      {
        $sender = $getInfo['sender'];
        $date = $getInfo['date'];
        $view = $getInfo['view'];
        $content = openssl_decrypt($getInfo['content'], "AES-128-ECB" ,$keykey);
         if ($sender == 'Client'){
$senderz = $_SESSION['username'];}
else{
$senderz = $sender;
   }        

     if ($sender == 'Client'){
$li = '<li class="right">';}
else{
$li = '<li>';
   }
        if ($userInf['avatar'] == '0'){
$usert = 'favicon.ico';}
else{
$usert = $userInf['avatar'];
   }    

    if ($getInfo['view'] == ''){
$viewe = '';}
else{
$viewe = '<p>Seen by '.$senderz.' '.htmlspecialchars($user -> _ago($getInfo['view'])).' ago </p>';
   }

        if ($sender == 'Client'){
$avatar = '/assets/images/'.$usert.'';}
else{
$avatar = '/assets/images/Admin.png';
   } }
    echo '

     '.$li.'
                                                                    <div class="conversation-list"><div class="ctext-wrap"><img class="img-thumbnail rounded-circle avatar-sm" src="'.$avatar.'" alt="Header Avatar"><img/>
                                                                       <br>
                                                                            <div class="conversation-name">'.$senderz.'</div>
                                                                            <p>'.htmlspecialchars($content).'</p>
                                                                            <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> '.strftime("%d %B", $getInfo['date']).' <span>'. strftime("%H:%M", $getInfo['date']).'</p>
                                                                       </div>
                                                                     '.$viewe.'
                                                                     </div>  
                                                               </li>
  
                                            ';

  
     }

  

?>