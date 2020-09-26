<?php


if (empty($_GET["selected"]) || empty($_GET["uid"]) || empty($_GET["year"]) || empty($_GET["day"]) || empty($_GET["month"]) || empty($_GET["attended"])) {
	header('Location: index.php');
}


$selected=$_GET["selected"];
$uid=$_GET["uid"];
$year=$_GET["year"];
$month=$_GET["month"];
$day=$_GET["day"];
$attended=$_GET["attended"];
$date=date("Y-m-d", mktime(0,0,0, $_GET["month"], $_GET["day"], $_GET["year"]));

//MYSQL CON
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "evidencija";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

if($selected==1) {
if($attended==1) {
	
	$sql="DELETE FROM activity1 WHERE uid=$uid AND class_id=$selected AND checkin_date='$date'";
	if($conn->query($sql)===TRUE){
		echo "Super, uspjelo je!";
	}else {
		echo "Nije uspjelo...".$conn->error;
	}
}

else{
	$sql="INSERT INTO activity1 (uid, class_id, checkin_date) VALUES ('$uid', '$selected', '$date')";
	if($conn->query($sql)===TRUE){
		echo "Super, uspjelo je!";
	}else {
		echo "Nije uspjelo...".$conn->error;
	}
}
}else {
	if($attended==1) {
	
	$sql="DELETE FROM activity2 WHERE uid=$uid AND class_id=$selected AND checkin_date='$date'";
	if($conn->query($sql)===TRUE){
		echo "Super, uspjelo je!";
	}else {
		echo "Nije uspjelo...".$conn->error;
	}
}

else{
	$sql="INSERT INTO activity2 (uid, class_id, checkin_date) VALUES ('$uid', '$selected', '$date')";
	if($conn->query($sql)===TRUE){
		echo "Super, uspjelo je!";
	}else {
		echo "Nije uspjelo...".$conn->error;
	}
}
}
$conn->close();
header('Location: overview.php?selected='.$selected.'&uid='.$uid.'&year='.$year.'&month='.$month.'&day='.$day.'');
?>