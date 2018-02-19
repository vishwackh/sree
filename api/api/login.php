<?php
$app->post('/adminlogin', function ($request, $response, $args) {
$result =  new stdClass();
$param =  $request->getParsedBody();
$userName = $param['userName'];
$password = $param['password'];
$db = new DbHandler();
$query = "select count(id) as count,token,id from login where type= 1 and userName ='".$userName."' and password ='".$password."'" ;
$dbresult  = $db->getOneRecord($query);

$token = $dbresult['token'];
$id = $dbresult['id'];

if( $dbresult['count']  == 1 ){
    $token = openssl_random_pseudo_bytes(16);
    $token = bin2hex($token);

    $query = "update login set token='".$token."' where id=". $dbresult['id'];
    $dbresult = $db->insert($query);

    $result->status = true ;
    $result->token =  $token;
}else {
        $result->status = false ;
}

echo json_encode($result);
});
 
