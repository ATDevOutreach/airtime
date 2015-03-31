<?php
// Save this code in sendAirtime.php. Configure the callback URL for your phone number
// to point to the location of this script on the web
// e.g http://yourWebsite.rhcloud.com/sendAirtime.php

$table = "";
 if(isset($_POST['number'])) {
 require_once "AfricasTalkingGateway.php";
 require_once('DBConn.php');
 require_once('apiConfig.php');

 // Specify your login credentials
 $api = $apiKey;
 $user = $apiUsername ;

 try{
 $gateway = new AfricasTalkingGateway($user, $api);
 $phone = $_POST['number'];
 $amount = $_POST['amount'];
 $phoneArr = explode(',', $phone);
 $recipients = array();

 foreach($phoneArr as $phone) {
  $recipients[] = array("phoneNumber"=>$phone, "amount"=>'KES ' . $amount);
 }
 $rec = json_encode($recipients);
 $results = $gateway->sendAirtime($rec);

 //Create insert values by looping the individual 
 //elements of the results array, allowing for persisting of many phone numbers
 $insert = "";
 foreach($results as $result) {

 	$insert .= ",('".$result->status."','".$result->amount."','".$result->phoneNumber."','".$result->requestId."')";
 }
 $insertFinal = substr($insert, 1);
 //$insert should hold string like "('Success','KES XX', '+25477XXXYYY', '22222XX'),('Success','KES XX', '+25477ZZZYYY', '22222XX')"

 //Persist $results =============
 //Check if user posted a number to be topped up
 if($insert != '') {
$subScriberSql = "INSERT INTO `".$sendTable."` (`status`,`amount`,`phoneNumber`,`requestId`)";
$subScriberSql.= " VALUES " . $insertFinal;
//persist in db
if($dbConnection->multi_query($subScriberSql))
	{
		error_log(print_r($POST,true));
	}
//===================


//Assemble the Table
 $table = "<table width='40%' border='1'><tr><th>Phone number</th><th>Amount</th><th>ErrorMessage</th></tr>";
 foreach($results as $res) {
  $table .= "<tr><td>" . $res->phoneNumber . "</td><td>" . $res->amount . "</td><td>" . $res->errorMessage . "</td></tr>";
 }
 $table .= "</table>";
}
}
 catch (AfricasTalkingGatewayException $e){
  echo $e->getMessage();
 }
}
?>


<html>
<div>
<form name="form" method="post" action="#">
 <table>
  <tr><td>Phone number:</td><td> <input type="text" name="number"/></td></tr>
  <tr><td>Amount:</td><td><input type="number" name="amount"/></td></tr>
  <tr><td/><td><input type="submit" value="submit"/></td></tr>
  </table>
</form>

</div>
<div>
<?php echo $table;?>
</div>
</html>