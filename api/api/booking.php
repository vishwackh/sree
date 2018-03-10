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


//Check whether booking has been done already for given event date

if($bookingType == 2){
$QryOutput1 =array();
    $Database->query("SELECT * FROM booking WHERE eventdate=:eventdate ");
$DBParameters["eventdate"]=$eventdate;
$Database->bindParameters($DBParameters);
$Database->execute();
$QryOutput1=$Database->resultset();
if(!empty($QryOutput1)){

    $isBookingCancel=0;
    $book_id=0;
    foreach($QryOutput1 as $key => $value){
        $isBookingCancel = $value['isBookingCancel'];
        $book_id =$value['booking_Id'];
    }

      //check whether booking has been cancelled or not for a particular given event date
        if($isBookingCancel ==0){
        echo json_encode("Already booking done for this date..! Please select other dates");
        return;
        }else{
            //if booking cancelled for particular event date then refund the paid advance amount
            $refundStatus=1;
            $Insrtqry2="UPDATE bookingCancelation SET refundStatus=:refundStatus,ModifiedTime=FROM_UNIXTIME(:ModifiedTime)
    where booking_Id=:booking_Id";
                
    $insertParams2= array('refundStatus'=>$refundStatus,'booking_Id'=>$book_id, 'ModifiedTime'=>$CurTime);
    
    $ExecutionDetails2 = $Database->executeQuery($Insrtqry2, $insertParams2);
    $ExecutionDetails2['query']	= $Database->getQRY($Insrtqry2, $insertParams2);
    
    
    if($ExecutionDetails2['stat'])
    {
        // $Errors["Dialogue"]=2;
        // GOTO ErrorInside;
        echo "<pre>";
        print_r($ExecutionDetails2);echo "</pre>";
        echo json_encode("Insertion failure..");
    }
        }
    }
}

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

if($bookingType == 2){
    $booking_Id=$Database->lastInsertId();
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

echo json_encode("Booking hall successfully");
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
$app->post('/updateBooking', function ($request, $response, $args) {
    $result =  new stdClass();
    
    
    $param =  $request->getParsedBody();
    $booking_Id=$param['booking_Id'];
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
    $Updateqry="UPDATE booking set customername=:customername, bridename=:bridename, brideDOB=:brideDOB, groomname=:groomname, groomDOB=:groomDOB,
    eventdate=:eventdate, numberOfDays=:numberOfDays,eventenddate=:eventenddate,phonenumber=:phonenumber,emailid=:emailid,eventname=:eventname,panadharno=:panadharno,
    totalamount=:totalamount,address=:address,addOnServices=:addOnServices,bookingType=:bookingType,ModifiedTime=FROM_UNIXTIME(:ModifiedTime)
    where booking_Id=:booking_Id";
                
    $UpdateParams= array('booking_Id'=>$booking_Id,'customername'=>$customername,'bridename'=>$bridename,'brideDOB'=>$brideDOB, 
    'groomname'=>$groomname,'groomDOB'=>$groomDOB,'eventdate'=>$eventdate, 'numberOfDays'=>$numberOfDays,'eventenddate'=>$eventenddate,
    'phonenumber'=>$phonenumber,'emailid'=>$emailid,'eventname'=>$eventname, 'panadharno'=>$panadharno,'totalamount'=>$totalamount,
    'address'=>$address,'addOnServices'=>$addOnServices,'bookingType'=>$bookingType,'ModifiedTime'=>$CurTime);
    
    $ExecutionDetails = $Database->executeQuery($Updateqry, $UpdateParams);
    $ExecutionDetails['query']	= $Database->getQRY($Updateqry, $UpdateParams);
    
    
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
        $paidamount = $param['paidamount'];
    $paymentType = $param['paymentType'];
    $chequeno = $param['chequeno'];
    $chequeURL = $param['chequeURL'];
    $balanceamount = $param['balanceamount'];
    $payment_Id = $param['payment_Id'];
    
    
    $Insrtqry1="UPDATE payment SET paidamount=:paidamount, paymentType=:paymentType, chequeno=:chequeno, chequeURL=:chequeURL,
    balanceamount=:balanceamount,ModifiedTime=FROM_UNIXTIME(:ModifiedTime)
    where payment_Id=:payment_Id";
                
    $insertParams1= array('payment_Id'=>$payment_Id,'paidamount'=>$paidamount,'paymentType'=>$paymentType, 
    'chequeno'=>$chequeno,'chequeURL'=>$chequeURL,'balanceamount'=>$balanceamount,'ModifiedTime'=>$CurTime);
    
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
    
    echo json_encode("Customer Payment details updated successfully");
    });
     
    $app->post('/deleteEnquiry', function ($request, $response, $args) {
        $result =  new stdClass();
        
        
        $param =  $request->getParsedBody();
        $booking_Id=$param['booking_Id'];

        $Database=new Database();
    
        $Database->beginTransaction();

        $DelOS="DELETE FROM booking WHERE booking_Id=:booking_Id";
		        
	$DelParams= array('booking_Id'=>$booking_Id);

	$ExecutionDetails = $Database->executeQuery($DelOS, $DelParams);
	$ExecutionDetails['query']	= $Database->getQRY($DelOS, $DelParams);

	if($ExecutionDetails['stat'])
	{
		// $Errors["Dialogue"]=2;
        // GOTO ErrorInside;
        echo "<pre>";
        print_r($ExecutionDetails1);echo "</pre>";
        echo json_encode("Insertion failure..");
	}

    $Database->endTransaction();
        echo json_encode("Customer enquiry deleted successfully");
    });

    $app->get('/getDashboardData', function ($request, $response, $args) {
        $result =  new stdClass();
        $Database=new Database();
    $Database->query("SELECT * FROM booking WHERE eventdate >=now() and isBookingCancel=0");
    
    $Database->execute();
    $ExeceptionDetails = $Database->getExceptionDetails();
    $QryOutput=$Database->resultset();
        echo json_encode($QryOutput);
    });

    $app->post('/bookingCanel', function ($request, $response, $args) {
        $result =  new stdClass();
        
        
        $param =  $request->getParsedBody();
        $booking_Id=$param['booking_Id'];
        $eventdate=$param['eventdate'];
        
        //bookingType value should be either 1 or 2 i.e 
        //if it is enquiry pass 1 
        //else it is booking pass 2
        $CurTime = time();
        $Database=new Database();
        
        $Database->beginTransaction();
        $Updateqry="UPDATE booking set isBookingCancel=:isBookingCancel,ModifiedTime=FROM_UNIXTIME(:ModifiedTime) where booking_Id=:booking_Id";
                    
        $UpdateParams= array('booking_Id'=>$booking_Id,'isBookingCancel'=>1,'ModifiedTime'=>$CurTime);
        
        $ExecutionDetails = $Database->executeQuery($Updateqry, $UpdateParams);
        $ExecutionDetails['query']	= $Database->getQRY($Updateqry, $UpdateParams);
        
        
        if($ExecutionDetails['stat'])
        {
            // $Errors["Dialogue"]=2;
            //SSdecho $ExecutionDetails;
            echo "<pre>";
            print_r($ExecutionDetails);echo "</pre>";
             //GOTO ErrorInside;
            echo json_encode("Insertion failure..");
        }else{

            $Database->query("SELECT sum(paidamount) as paidamount  FROM payment WHERE booking_Id=:booking_Id");
            $DBParameters["booking_Id"]=$booking_Id;
            $Database->bindParameters($DBParameters);
            $Database->execute();
            $ExeceptionDetails = $Database->getExceptionDetails();
            $QryOutput=$Database->resultset();

            $paidamount=0;
            foreach($QryOutput as $key => $value){
                $paidamount = $value['paidamount'];
                echo "paidamount==>".$paidamount;
            }
            


            $Insrtqry1="INSERT INTO bookingCancelation (booking_Id, paidamount, refundAmount,eventdate,createdTime,modifiedTime
) VALUES (:booking_Id, :paidamount, :refundAmount,:eventdate,FROM_UNIXTIME(:CurTime),FROM_UNIXTIME(:ModifiedTime))";
	        
$insertParams1= array('booking_Id'=>$booking_Id,'paidamount'=>$paidamount,'refundAmount'=>$paidamount, 
'eventdate'=>$eventdate,'CurTime'=>$CurTime,'ModifiedTime'=>$CurTime);

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
        
        echo json_encode("Booking cancelation successfully");
        });
$app->get('/getBookingCancelation', function ($request, $response, $args) {
    $result =  new stdClass();
    $Database=new Database();
$Database->query("SELECT * FROM bookingCancelation ");
$Database->execute();
$ExeceptionDetails = $Database->getExceptionDetails();
$QryOutput=$Database->resultset();
    echo json_encode($QryOutput);
});        

$app->post('/getPaymentDetails', function ($request, $response, $args) {
    $result =  new stdClass();
    
    
    $param =  $request->getParsedBody();
    $booking_Id=$param['booking_Id'];
    $Database=new Database();
    $Database->query("SELECT * FROM payment WHERE booking_Id=:booking_Id");
    $DBParameters["booking_Id"]=$booking_Id;
    
    $Database->bindParameters($DBParameters);
    $Database->execute();
    $ExeceptionDetails = $Database->getExceptionDetails();
    $QryOutput=$Database->resultset();
        echo json_encode($QryOutput);
});
$app->post('/addPaymentDetails', function ($request, $response, $args) {
    $result =  new stdClass();
    $CurTime = time();
    
    $param =  $request->getParsedBody();
    $Database=new Database();
    $booking_Id=$param['booking_Id'];
    $paidamount = $param['paidamount'];
    $paymentType = $param['paymentType'];
    $chequeno = $param['chequeno'];
    $chequeURL = $param['chequeURL'];
    $balanceamount = $param['balanceamount'];
    $Database->beginTransaction();
    
    $Insrtqry1="INSERT INTO payment (booking_Id, paidamount, paymentType, chequeno, chequeURL, balanceamount,createdTime,modifiedTime
    ) VALUES (:booking_Id, :paidamount, :paymentType, :chequeno, :chequeURL,:balanceamount,FROM_UNIXTIME(:CurTime),FROM_UNIXTIME(:ModifiedTime))";
                
    $insertParams1= array('booking_Id'=>$booking_Id,'paidamount'=>$paidamount,'paymentType'=>$paymentType, 
    'chequeno'=>$chequeno,'chequeURL'=>$chequeURL,'balanceamount'=>$balanceamount,'CurTime'=>$CurTime,'ModifiedTime'=>$CurTime);
    
    $ExecutionDetails1 = $Database->executeQuery($Insrtqry1, $insertParams1);
    $ExecutionDetails1['query'] = $Database->getQRY($Insrtqry1, $insertParams1);
    
    
    if($ExecutionDetails1['stat'])
    {
        // $Errors["Dialogue"]=2;
        // GOTO ErrorInside;
        echo "<pre>";
        print_r($ExecutionDetails1);echo "</pre>";
        echo json_encode("Insertion failure..");
    }
    
    
    $Database->endTransaction();
    
    echo json_encode("Payment details added successfully");
});