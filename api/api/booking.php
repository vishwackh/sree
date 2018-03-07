<?php
$app->post('/booking', function ($request, $response, $args) {
$result =  new stdClass();


$param =  $request->getParsedBody();
$customername = $param['customername'];
$bridename = $param['bridename'];
$brideDOB = $param['brideDOB'];
$groomname = $param['groomname'];
$groomDOB = $param['groomDOB'];
$eventdate = $param['eventdate'];
$numberOfDays = $param['numberOfDays'];
$eventenddate = $param['eventenddate'];
$phonenumber = $param['phonenumber'];
$emailid = $param['emailid'];
$eventname = $param['eventname'];
$panadharno = $param['panadharno'];
$totalamount = $param['totalamount'];
$address = $param['address'];
$bookingType = $param['bookingType'];
$addOnServices=$param['addOnServices'];
//bookingType value should be either 1 or 2 i.e 
//if it is enquiry pass 1 
//else it is booking pass 2
$CurTime = time();

$Database=new Database();

$Database->beginTransaction();
$Insrtqry="INSERT INTO booking (customername, bridename, brideDOB, groomname, groomDOB, eventdate,numberOfDays,eventenddate,phonenumber,
emailid,eventname,panadharno,totalamount,address,addOnServices,bookingType,createdTime,modifiedTime
) VALUES (:customername, :bridename, :brideDOB, :groomname, :groomDOB,:eventdate, :numberOfDays,:eventenddate,:phonenumber,
:emailid,:eventname,:panadharno,:totalamount,:address,:addOnServices,:bookingType,FROM_UNIXTIME(:CreadtedTime),FROM_UNIXTIME(:ModifiedTime))";
	        
$insertParams= array('customername'=>$customername,'bridename'=>$bridename,'brideDOB'=>$brideDOB, 
'groomname'=>$groomname,'groomDOB'=>$groomDOB,'eventdate'=>$eventdate, 'numberOfDays'=>$numberOfDays,'eventenddate'=>$eventenddate,
'phonenumber'=>$phonenumber,'emailid'=>$emailid,'eventname'=>$eventname, 'panadharno'=>$panadharno,'totalamount'=>$totalamount,
'address'=>$address,'addOnServices'=>$addOnServices,'bookingType'=>$bookingType,'CreadtedTime'=>$CurTime,'ModifiedTime'=>$CurTime);

$ExecutionDetails = $Database->executeQuery($Insrtqry, $insertParams);
$ExecutionDetails['query']	= $Database->getQRY($Insrtqry, $insertParams);


if($ExecutionDetails['stat'])
{
    // $Errors["Dialogue"]=2;
    //SSdecho $ExecutionDetails;
    echo "<pre>";
    print_r($ExecutionDetails);echo "</pre>";
     //GOTO ErrorInside;
    echo json_encode("Insertion failure..");
}

if($bookingType === 2){
    echo "booking the hall===>";
    $booking_Id=$Database->lastInsertId();
    echo "booking id:".$booking_Id;
    $paidamount = $param['paidamount'];
$paymentType = $param['paymentType'];
$chequeno = $param['chequeno'];
$chequeURL = $param['chequeURL'];
$balanceamount = $param['balanceamount'];


$Insrtqry1="INSERT INTO payment (booking_Id, paidamount, paymentType, chequeno, chequeURL, balanceamount,createdTime,modifiedTime
) VALUES (:booking_Id, :paidamount, :paymentType, :chequeno, :chequeURL,:balanceamount,FROM_UNIXTIME(:CurTime),FROM_UNIXTIME(:ModifiedTime))";
	        
$insertParams1= array('booking_Id'=>$booking_Id,'paidamount'=>$paidamount,'paymentType'=>$paymentType, 
'chequeno'=>$chequeno,'chequeURL'=>$chequeURL,'balanceamount'=>$balanceamount,'CurTime'=>$CurTime,'ModifiedTime'=>$CurTime);

$ExecutionDetails1 = $Database->executeQuery($Insrtqry1, $insertParams1);
$ExecutionDetails1['query']	= $Database->getQRY($Insrtqry1, $insertParams1);


if($ExecutionDetails1['stat'])
{
    // $Errors["Dialogue"]=2;
    // GOTO ErrorInside;
    echo "<pre>";
    print_r($ExecutionDetails1);echo "</pre>";
    echo json_encode("Insertion failure..");
}
}

$Database->endTransaction();

echo json_encode("Payment details Inserted successfully");
});
 
$app->get('/getbookingDetails', function ($request, $response, $args) {
    $result =  new stdClass();
    $Database=new Database();
$Database->query("SELECT * FROM booking WHERE bookingType=:bookingType");
$DBParameters["bookingType"]=2;
$Database->bindParameters($DBParameters);
$Database->execute();
$ExeceptionDetails = $Database->getExceptionDetails();
$QryOutput=$Database->resultset();
    echo json_encode($QryOutput);
});

$app->get('/getenquiryDetails', function ($request, $response, $args) {
    $result =  new stdClass();
    $Database=new Database();
$Database->query("SELECT * FROM booking WHERE bookingType=:bookingType");
$DBParameters["bookingType"]=1;

$Database->bindParameters($DBParameters);
$Database->execute();
$ExeceptionDetails = $Database->getExceptionDetails();
$QryOutput=$Database->resultset();
    echo json_encode($QryOutput);
});

