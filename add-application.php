<?php
session_start();

$productid = $_GET['id'];

if (empty($_GET['id']) || empty($_COOKIE['email'])) {
	header('location:home.php');
} else {
	$email = $_COOKIE['email'];
	$selleremail = $_SESSION['add-application-seller-email'];

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
	
	$s = "SELECT * FROM applications WHERE productid = '$productid' && useremail = '$email'";
	$result = mysqli_query($conn, $s);
			
	if (mysqli_num_rows($result) == 0) {
	
		$sql = "INSERT INTO applications (productid, useremail, selleremail)
		VALUES ('$productid', '$email', '$selleremail')";

		if (mysqli_query($conn, $sql)) {
		  echo "New record created successfully" . "<br>";
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	
	}

	//Close the connection
	mysqli_close($conn);
	header('location:applications.php');
}
?>
