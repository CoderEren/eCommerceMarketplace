<?php
session_start();

if (empty($_SESSION['email'])) {
	header('location:index.php');
} else {
	$email = $_SESSION['email'];
	$userpassword = $_SESSION['password'];

	$servername = "localhost";
	$username = "root";
	$password = "PASSWORD";
	$dbname = "fullcashback";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	// Check connection
	if (!$conn) {
	  die("Connection failed: " . mysqli_connect_error());
	}
	echo "Connected successfully" . "<br>";
	
	$sql = "INSERT INTO users (email, password)
	VALUES ('$email', '$userpassword')";

	if (mysqli_query($conn, $sql)) {
	  echo "New record created successfully" . "<br>";
	} else {
	  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	//Close the connection
	mysqli_close($conn);
	
	setcookie("email", $_SESSION['email'], time() + (86400 * 30), "/");
	setcookie("password", $_SESSION['password'], time() + (86400 * 30), "/");
	
	//session_destroy();
	
	//session_start();
	//$_SESSION['loggedinemail'] = $email;
	//echo $_SESSION['loggedinemail'];
	header('location:home.php');
}
?>
