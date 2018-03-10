<?php
$app->post('/adminlogin', function ($request, $response, $args) {
$result =  new stdClass();
$param =  $request->getParsedBody();
$userName = $param['userName'];
$password = $param['password'];


$Database=new Database();
$Database->query("SELECT count(id) as count,token,id FROM login WHERE userName=:userName AND password=:password AND type=:type");

$DBParameters["userName"]=$userName;
$DBParameters["password"]=$password;
$DBParameters["type"]=1;

$Database->bindParameters($DBParameters);
$Database->execute();
$ExeceptionDetails = $Database->getExceptionDetails();
$QryOutput=$Database->single();

$token = $QryOutput['token'];
$id = $QryOutput['id'];

if( $QryOutput['count']  == 1 ){
    $token = openssl_random_pseudo_bytes(16);
    $token = bin2hex($token);

    $Updateqry="UPDATE login SET token=:token WHERE id=:id";        
	$UpdateParams= array('token'=>$token,'id'=>$QryOutput['id']);

	$ExecutionDetails = $Database->executeQuery($Updateqry, $UpdateParams);
	$ExecutionDetails['query']	= $Database->getQRY($Updateqry, $UpdateParams);

    $result->status = true ;
    $result->token =  $token;
}else {
        $result->status = false ;
}

echo json_encode($result);
});
 
$app->post('/resetPassword', function ($request, $response, $args) {
    $result =  new stdClass();
    $param =  $request->getParsedBody();
    $username = $param['username'];
   $currentpassword = $param['currentpassword'];
   $newpassword = $param['newpassword'];
   
    $Database=new Database();
    $Database->beginTransaction();
    $CurTime = time();
   $Database->query("SELECT * FROM login where userName=:userName and password=:password");
   
   $DBParameters["userName"]=$username;
   $DBParameters["password"]=$currentpassword;
   
   $Database->bindParameters($DBParameters);
   $Database->execute();
   $ExeceptionDetails = $Database->getExceptionDetails();
   $QryOutput=$Database->resultset();
   if(!empty($QryOutput)){
   $Updateqry="UPDATE login set password=:password,updatedDate=FROM_UNIXTIME(:ModifiedTime)
   where userName=:userName";
    $UpdateParams= array('password'=>$newpassword,'userName'=>$username,'ModifiedTime'=>$CurTime);
    
    $ExecutionDetails = $Database->executeQuery($Updateqry, $UpdateParams);
    $ExecutionDetails['query']    = $Database->getQRY($Updateqry, $UpdateParams);
    
    if($ExecutionDetails['stat']){
    // $Errors["Dialogue"]=2;
    //SSdecho $ExecutionDetails;
    echo "<pre>";
    print_r($ExecutionDetails);echo "</pre>";
    //GOTO ErrorInside;
    echo json_encode("updation failure..");
    }
    $Database->endTransaction();
   
    echo json_encode("password updated successfully..");

   }
});