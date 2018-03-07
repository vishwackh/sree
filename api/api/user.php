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
 
