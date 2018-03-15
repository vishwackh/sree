<?php
$app->post('/addEvent', function ($request, $response, $args) {
$result =  new stdClass();


$param =  $request->getParsedBody();

$eventname = $param['eventname'];
$description = $param['description'];
$eventdate = $param['eventdate'];

$CurTime = time();

$Database=new Database();

$Database->beginTransaction();

$Insrtqry="INSERT INTO event (eventname,description,eventdate,createdTime,modifiedTime
) VALUES (:eventname, :description, :eventdate,FROM_UNIXTIME(:CreadtedTime),FROM_UNIXTIME(:ModifiedTime))";
	        
$insertParams= array('eventname'=>$eventname,'description'=>$description,'eventdate'=>$eventdate, 
'CreadtedTime'=>$CurTime,'ModifiedTime'=>$CurTime);

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
$Database->endTransaction();

echo json_encode("Event details added successfully");
});

$app->get('/getEventData', function ($request, $response, $args) {
    $result =  new stdClass();
    $Database=new Database();
$Database->query("SELECT * FROM event");
$Database->execute();
$ExeceptionDetails = $Database->getExceptionDetails();
$QryOutput=$Database->resultset();
    echo json_encode($QryOutput);
});

$app->post('/updateEvent', function ($request, $response, $args) {
    $result =  new stdClass();

    $param =  $request->getParsedBody();

$eventname = $param['eventname'];
$description = $param['description'];
$eventdate = $param['eventdate'];
$event_Id = $param['event_Id'];

$CurTime = time();

$Database=new Database();

$Database->beginTransaction();

$Updateqry="UPDATE event set eventname=:eventname, description=:description, eventdate=:eventdate,ModifiedTime=FROM_UNIXTIME(:ModifiedTime)
    where event_Id=:event_Id";
                
    $UpdateParams= array('event_Id'=>$event_Id,'eventname'=>$eventname,'description'=>$description,'eventdate'=>$eventdate, 
    'ModifiedTime'=>$CurTime);
    
    $ExecutionDetails = $Database->executeQuery($Updateqry, $UpdateParams);
    $ExecutionDetails['query']	= $Database->getQRY($Updateqry, $UpdateParams);
    
    
    if($ExecutionDetails['stat'])
    {
        // $Errors["Dialogue"]=2;
        //SSdecho $ExecutionDetails;
        echo "<pre>";
        print_r($ExecutionDetails);echo "</pre>";
         //GOTO ErrorInside;
        echo json_encode("updation failure..");
    }
    $Database->endTransaction();

    echo json_encode("updated successfully..");

});

$app->post('/deleteEvent', function ($request, $response, $args) {
    $result =  new stdClass();
    
    
    $param =  $request->getParsedBody();
    $event_Id=$param['event_Id'];

    $Database=new Database();

    $Database->beginTransaction();

    $DelOS="DELETE FROM event WHERE event_Id=:event_Id";
            
$DelParams= array('event_Id'=>$event_Id);

$ExecutionDetails = $Database->executeQuery($DelOS, $DelParams);
$ExecutionDetails['query']	= $Database->getQRY($DelOS, $DelParams);

if($ExecutionDetails['stat'])
{
    // $Errors["Dialogue"]=2;
    // GOTO ErrorInside;
    echo "<pre>";
    print_r($ExecutionDetails1);echo "</pre>";
    echo json_encode("deletion failure..");
}

$Database->endTransaction();
    echo json_encode("Event details deleted successfully");
});

$app->post('/custFeedback', function ($request, $response, $args) {
    $result =  new stdClass();
    
    
    $param =  $request->getParsedBody();
    
    $customername = $param['customername'];
    $feedback = $param['feedback'];
    $rating = $param['rating'];
    
    $CurTime = time();
    
    $Database=new Database();
    
    $Database->beginTransaction();
    
    $Insrtqry="INSERT INTO eventFeedback (customername,feedback,createdTime
    ) VALUES (:customername,:feedback,FROM_UNIXTIME(:createdTime))"; 
    $insertParams= array('customername'=>$customername,'feedback'=>$feedback,'createdTime'=>$CurTime);
    
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
    $feedback_Id=$Database->lastInsertId();
   foreach($rating as $key=>$value){
        
    $Insrtqry="INSERT INTO categoryFeedback (category,rating,feedback_Id,createdTime
    ) VALUES (:category,:rating,:feedback_Id,FROM_UNIXTIME(:createdTime))"; 
    $insertParams= array('category'=>$value['category'],'rating'=>$value['rating'],'feedback_Id'=>$feedback_Id,'createdTime'=>$CurTime);
    
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

}
    $Database->endTransaction();
    
    echo json_encode("Feedback added successfully");
    });
    
    $app->get('/getCustFeedback', function ($request, $response, $args) {
        $result =  new stdClass();
        $Database=new Database();
    $Database->query("SELECT * FROM eventFeedback");
    $Database->execute();
    $ExeceptionDetails = $Database->getExceptionDetails();
    $QryOutput=$Database->resultset();
        echo json_encode($QryOutput);
    });
    $app->post('/getCustRating', function ($request, $response, $args) {
        $param =  $request->getParsedBody();
    
        $feedback_Id = $param['feedback_Id'];
        
        $result =  new stdClass();
        $Database=new Database();
    $Database->query("SELECT * FROM categoryFeedback where feedback_Id=:feedback_Id");


    $DBParameters["feedback_Id"]=$feedback_Id;
    $Database->bindParameters($DBParameters);
    $Database->execute();
    $ExeceptionDetails = $Database->getExceptionDetails();
    $QryOutput=$Database->resultset();
        echo json_encode($QryOutput);
    });

  
    