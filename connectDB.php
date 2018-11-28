<?php

$dbhost = "localhost";
$dbuser = "dbadmin";
$dbpw = "password1";
$db = "example";

$conn  = mysqli_connect($dbhost, $dbuser, $dbpw, $db);

//$sqlQuery = "SELECT temp, date FROM weather";
//$result = mysqli_query($conn,$sqlQuery);

if($dbconnect->connect_error){
	die("Error with connection: " . $dbconnect->connect_error);

}
?>