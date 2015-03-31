
<?php 


//$dbHost = 'mysql://$OPENSHIFT_MYSQL_DB_HOST:$OPENSHIFT_MYSQL_DB_PORT/';
$dbHost = 'localhost';
$dbName = 'sentAirtime';

$dbUser = 'root';
$dbPassword = 'password';

$subScriptionTable = 'subscription';
$unsubscriptionTable = 'unsubscribers';
$outboxTable = 'outbox';
$inboxTable = 'inbox';
$sendTable = 'sent';
$contacts ='contacts';


$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);


if ($dbConnection->connect_errno) 
{
	
	error_log($dbConnection->connect_error,3,"./error.log");
	exit();
}



 ?>