<?php

$hasParameters = true;
$success = false;

if (empty($_GET["selected"]) || empty($_GET["username"]) || empty($_GET["password"])) {
	$hasParameters = false;
}
if (empty($_GET["selected"])){
	header('Location: index.php');
}
$selected = htmlspecialchars($_GET["selected"]);
$displayText = "Error 104";

$class01 = 0;
$class02 = 0;

if($selected == "1"){
	$displayText = "Programsko inženjerstvo";
	$class01 = 1;
}
else if($selected == "2"){
	$displayText = "Dinamičke web aplikacije";
	$class02 = 1;
}

$message = "";
$messageColor = "#ff0000";
$todayDate = date("Y-m-d");
if($hasParameters == true){




$un = htmlspecialchars($_GET["username"]);
$pw = md5(htmlspecialchars($_GET["password"]));
$orgPass = htmlspecialchars($_GET["password"]);
$accExists = false;
$uid = 0;

if(strlen($un) > 3 && strlen($pw) > 3 ){
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
		if($row["un"] == $un){
			
			if($row["pw"] == $pw){
				$uid = $row["id"];
				if($selected == 1){
					if($row["class01"] == 1){
						$message = "Već ste se registrirali na ovaj kolegij!";
						$success = true;
						$messageColor = "#ff0000";
					}
					else{
						$sql = "UPDATE users SET class01='1' WHERE id='$uid'";

						if ($conn->query($sql) === TRUE) {
						  //echo "Record updated successfully";
						} else {
						 // echo "Error updating record: " . $conn->error;
						}
						$message = "Uspješno ste se registrirali na ovaj kolegij!";
						$success = true;
						$messageColor = "#00ff00";
					}
				}
				if($selected == 2){
					if($row["class02"] == 1){
						$message = "Već ste se registrirali na ovaj kolegij!";
						$success = true;
						$messageColor = "#ff0000";
					}
					else{
						$sql = "UPDATE users SET class02='1' WHERE id='$uid'";

						if ($conn->query($sql) === TRUE) {
						  //echo "Record updated successfully";
						} else {
						  //echo "Error updating record: " . $conn->error;
						}
						$message = "Uspješno ste se registrirali na ovaj kolegij!";
						$success = true;
						$messageColor = "#00ff00";
					}
				}
			}
			else{
				$message = "Pogrešna lozinka!";
				$messageColor = "#ff0000";
			}
			$accExists = true;
		}
		else{
			$accExists = false;
			$message = "Ovaj račun ne postoji!";
			$messageColor = "#ff0000";
		}
	  }
	} else {
	  $accExists = false;
	}
	
	if($accExists == false){
		if ($selected==1) {
		$sql = "INSERT INTO users (un, pw, class01 reg_date)
		VALUES ('$un', '$pw', '$class01', '$class02', $todayDate)";

		if ($conn->query($sql) === TRUE) {
			$message = "Uspješno ste se registrirali na ovaj kolegij!";
					$success = true;
			$messageColor = "#00ff00";
		} else {
			$message = "Došlo je do greške!";
			$messageColor = "#ff0000";
		}
	}else {
		$sql = "INSERT INTO users (un, pw, class02, reg_date)
		VALUES ('$un', '$pw', '$class01', '$class02', $todayDate)";

		if ($conn->query($sql) === TRUE) {
			$message = "Uspješno ste se registrirali na ovaj kolegij!";
					$success = true;
			$messageColor = "#00ff00";
		} else {
			$message = "Došlo je do greške!";
			$messageColor = "#ff0000";
		}
	}
	}
	$conn->close();
}
}
?>

<html>
	<head>
	<title>Registriraj se!</title>
		<link rel="stylesheet" href="css/layout.css">
	</head>
	<body>
		<h3 class="titleText">
			Registracija na kolegij: <?php echo $displayText; ?>
		</h3>
		<div class="registerInput">
			<form action="register.php">
			  <input type="hidden" name="selected" id="selected" value="<?php echo $selected; ?>"><br><br>
			  <label class="inputLabel" for="username">Korisničko ime:</label>
			  <input type="text" placeholder="ovdje upišite svoje korisničko ime..." id="username" name="username"><br><br>
			  <label class="inputLabel" for="password">Lozinka:</label>
			  <input type="password" placeholder="ovdje upišite svoju lozinku..." id="password" name="password"><br><br>
			  <input type="submit" value="Registriraj se">
			</form>
			<b style="color:<?php echo $messageColor; ?>"><?php echo $message; ?></b>
			<?php if($success == true){?>
			<a href="login.php?selected=<?php echo $selected; ?>"<b style="color:#000000">Sada se prijavi!</b></a>
			<?php } ?>
		</div>
		<div class="footer">
			<b>Prijavi se na kolegij, <a href="login.php?selected=<?php echo $selected; ?>">Klikni ovdje</a>!</b>
			<p><b>Za povratak na izbor kolegija <a href="index.php?selected=<?php echo $selected; ?>">klikni ovdje!</a></b> </p>
		</div>
	</body>
</html>