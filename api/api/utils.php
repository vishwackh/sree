<?php



/**
 * Function to get user_id
 */
function getUserId() {

//   $user_id  = 40;
//   return $user_id;
//   exit();

  $headers = apache_request_headers();
  $token = '';
  $user_id  = '';
  $err_flag = false;

  if (isset($headers['Auth'])) {
    $token = $headers['Auth'];
  }else if (isset($headers['auth'])) {
    $token = $headers['auth'];
  }

  if (!empty($token)) {

      $db = new DbHandler();
      $query = "select id from login where token ='".$token."'" ;
      $dbresult  = $db->getOneRecord($query);
      $user_id = $dbresult['id'];
      return $user_id;

  }else{
      $result = false;
      return $result;
  }

}


/**
 * Function to get user_id
 */
function getAdminUserId() {

//   $user_id  = 40;
//   return $user_id;
//   exit();

  $headers = apache_request_headers();
  $token = '';
  $user_id  = '';
  $err_flag = false;

  if (isset($headers['Auth'])) {
    $token = $headers['Auth'];
  }else if (isset($headers['auth'])) {
    $token = $headers['auth'];
  }else if (isset($headers['authorization'])) {
    $token = $headers['authorization'];
  }
  if (!empty($token)) {

      $db = new DbHandler();
      $query = "select id from login where token ='".$token."' and type = 1" ;
      $dbresult  = $db->getOneRecord($query);
      $user_id = $dbresult['id'];
      return $user_id;

  }else{
      $result = false;
      return $result;
  }
}


 function sent_mail($to,$subject,$content,$fr)
  {

  	try {

        $to = $to;
        $subject = $subject;
        $from = $fr ;
        
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            "CC: sentmail@freshjoy.in\r\n".
            'X-Mailer: PHP/' . phpversion();
        
        
        // Sending email
        if(mail($to, $subject, $content, $headers)){
            return true;
        } else{
             return false;
        }
      	
    	}catch(Exception $e) {
    		return false;
    	}

    
    return false;

}
