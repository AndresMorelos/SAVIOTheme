<?php
require_once '../../config.php';
require_once 'lib.php';

$response = array( "success"=>"false","message"=>"","data"=>array(),"time"=>0 );

$ntime = $_REQUEST["ntime"];
if($ntime == 0){$ntime  = null;}//Init request

$notification = saviotheme_get_notifications($ntime);

//Set time
$response["time"] = time();

if(!$notification){
    $response["message"] = "Empty response array";
    return json_encode($response);
}


$response["success"] = true;
$response["message"] = "Loaded notifications";
$response["data"] = $notification;

/*header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');*/
echo json_encode($response);

?>
