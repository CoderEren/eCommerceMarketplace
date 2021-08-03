<?php
session_start();

if (empty($_SESSION['email'])) {
	header('location:seller-home.php');
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
	
	$sql = "INSERT INTO sellers (email, password)
	VALUES ('$email', '$userpassword')";

	if (mysqli_query($conn, $sql)) {
	  echo "New record created successfully" . "<br>";
	} else {
	  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	//Close the connection
	mysqli_close($conn);
	
	setcookie("seller-email", $_SESSION['email'], time() + (86400 * 30), "/");
	setcookie("seller-password", $_SESSION['password'], time() + (86400 * 30), "/");
	
	header('location:seller-dashboard.php');
}
?>
