<?php


$selected = htmlspecialchars($_GET["selected"]);

$displayText = "Error 104";
if($selected == "1"){
	$displayText = "Programsko inženjerstvo";
	$class01 = 1;
}
else if($selected == "2"){
	$displayText = "Dinamičke web aplikacije";
	$class02 = 1;
}


$un = "";
$currentYear = htmlspecialchars($_GET["year"]);
$currentMonth = htmlspecialchars($_GET["month"]);
$currentDay = htmlspecialchars($_GET["day"]);
$message = "";
$messageColor = "#ff0000";
$todayDate = date("Y-m-d");
$uid = htmlspecialchars($_GET["uid"]);
$accExists = false;
$attended = array();

	//MYSQL CON
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "evidencija";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
	
	
$sql = "SELECT id, un, pw, class01, class02, reg_date FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		if($row["id"] == $uid){
			$un = $row["un"];
			$accExists = true;
		}
		else{
			$message = "Korisnički račun nije registriran!";
			$messageColor = "#ff0000";
			$accExists = false;
		}
	}
} 
else {
	$accExists = false;
}
	
if($accExists == true){
	$sql = "SELECT login_id, uid, class_id, checkin_date FROM activity";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if($row["uid"] == $uid){
				$month = explode("-", $row["checkin_date"]);
				if($month[1] == $currentMonth){
					array_push($attended, $month[2]);
				}
			}
			
		}
	}
}
$conn->close();

$monthOverview = array(array('0','0','0','0','0','0','0'),
						array('0','0','0','0','0','0','0'),
						array('0','0','0','0','0','0','0'),
						array('0','0','0','0','0','0','0'),
						array('0','0','0','0','0','0','0'),
						array('0','0','0','0','0','0','0'));
$monthNoDays = NumberOfDays($currentYear, $currentMonth);

for($i = 0, $d = 0, $w = 0; $i<$monthNoDays; $i++){
	$d = GetDayOfTheWeek($currentYear, $currentMonth, $i+1);
	$monthOverview[$w][$d-1] = $i+1;
	if($d == 7){
		$w++;
	}
}


function GetDayOfTheWeek($y, $m, $d){
	$day = date("l", mktime(0,0,0,$m,$d,$y));
	if($day == "Monday"){
		return 1;
	}
	else if($day == "Tuesday"){
		return 2;
	}
	else if($day == "Wednesday"){
		return 3;
	}
	else if($day == "Thursday"){
		return 4;
	}
	else if($day == "Friday"){
		return 5;
	}
	else if($day == "Saturday"){
		return 6;
	}
	else if($day == "Sunday"){
		return 7;
	}
	else{
		return 999;
	}
}

function NumberOfDays($y, $m){
	if($m == 2){
		if($y % 4 == 0){
			return 29;
		}
		else{
			return 28;
		}
	}
	else if($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12){
		return 31;
	}
	else if($m == 4 || $m == 6 || $m == 9 || $m == 11){
		return 30;
	}
	else{
		return 0;
	}
}

function CroatianGetNameOfMonth($m){
	if($m==1){return "Siječanj";}
	else if($m==2){return "Veljača";}
	else if($m==3){return "Ožujak";}
	else if($m==4){return "Travanj";}
	else if($m==5){return "Svibanj";}
	else if($m==6){return "Lipanj";}
	else if($m==7){return "Srpanj";}
	else if($m==8){return "Kolovoz";}
	else if($m==9){return "Rujan";}
	else if($m==10){return "Listopad";}
	else if($m==11){return "Studeni";}
	else if($m==12){return "Prosinac";}
	else {return "Greška";}
}
function CroatianGetNameOfDay($d){
	if($d==1){return "Ponedjeljak";}
	else if($d==2){return "Utorak";}
	else if($d==3){return "Srijeda";}
	else if($d==4){return "Četvrtak";}
	else if($d==5){return "Petak";}
	else if($d==6){return "Subota";}
	else if($d==7){return "Nedjelja";}
	else {return "Greška";}
}

function CheckAttendanceOnDay($array, $d){
	foreach ($array as $value) {
		if($value == $d){
			return true;
		}
	}
	return false;
}


?>

<html>
	<head>
	<title>Pregled evidencije!</title>
		 <link rel="stylesheet" href="css/layout.css">
	</head>
	<body>
		<h1 style="font-family: 'Century Gothic';color: #ff6a00;">Evidencija dolazaka za: <?php echo $un; ?></h1>

		<div class="month">      
		  <ul>
		  
		  <?php 
			$previousYear = $currentYear;
			$nextYear = $currentYear;
			$previousMonth = $currentMonth-1;
			$nextMonth = $currentMonth+1;
			if($previousMonth<=0){
				$previousMonth = 12;
				$previousYear = $currentYear-1;
			}
			if($nextMonth>12){
				$nextMonth = 1;
				$nextYear = $currentYear+1;
			}
			?>
			<a href="overview.php?selected=<?php echo $selected; ?>&uid=<?php echo $uid; ?>&year=<?php echo $previousYear; ?>&month=<?php echo $previousMonth; ?>&day=<?php echo '01'; ?>"><li class="prev">&#10094;</li></a>
			<a href="overview.php?selected=<?php echo $selected; ?>&uid=<?php echo $uid; ?>&year=<?php echo $nextYear; ?>&month=<?php echo $nextMonth; ?>&day=<?php echo '01'; ?>"><li class="next">&#10095;</li></a>
			<li>
			  <?php echo CroatianGetNameOfMonth($currentMonth); ?><br>
			  <span style="font-size:18px"> <?php echo $currentYear; ?></span>
			</li>
		  </ul>
		</div>

		<ul class="weekdays">
		  <li> <?php echo CroatianGetNameOfDay(1); ?></li>
		  <li> <?php echo CroatianGetNameOfDay(2); ?></li>
		  <li> <?php echo CroatianGetNameOfDay(3); ?></li>
		  <li> <?php echo CroatianGetNameOfDay(4); ?></li>
		  <li> <?php echo CroatianGetNameOfDay(5); ?></li>
		  <li> <?php echo CroatianGetNameOfDay(6); ?></li>
		  <li> <?php echo CroatianGetNameOfDay(7); ?></li>
		</ul>

		<ul class="days">  
		<?php
			for($i = 0; $i<6; $i++){
				for($j = 0; $j<7; $j++){
					echo "<li>";
					if($monthOverview[$i][$j] != 0){
						$wasOnClass = CheckAttendanceOnDay($attended, $monthOverview[$i][$j]);
						if($wasOnClass == true){
							echo "<span class='active'>";
						}
						else{
							if($j == 5 || $j == 6){
								
							}
							else{
								echo "<span class='notactive'>";
							}
						}
						echo $monthOverview[$i][$j];
						if($wasOnClass == true){
							echo "</span>";
						}
						else{
							if($j == 5 || $j == 6){
								
							}
							else{
								echo "</span>";
							}
						}
					}
					echo "</li>";
				}
			}
		?>
		
		</ul>
		<b>Student je imao ukupno <?php echo count($attended); ?> dolazaka ovaj mjesec.</b>
		<br><br>
		<div style="background-color:'#777777'"><a href="index.php">Povratak na izbornik</a></div>
	</body>
</html>










