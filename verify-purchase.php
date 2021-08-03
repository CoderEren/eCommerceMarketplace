<?php
session_start();

$paymentid = $_POST['paymentid'];

if (empty($_POST['paymentid']) || empty($_COOKIE['seller-email'])) {
	header('location:seller-payments.php');
} else {
	$email = $_COOKIE['seller-email'];

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
	
		$sql = "UPDATE payments SET purchaseverified='true' WHERE id = '$paymentid'";

		if (mysqli_query($conn, $sql)) {
		  echo "Record updated successfully" . "<br>";
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
	//Close the connection
	mysqli_close($conn);
	header('location:seller-payments.php');
}
?>
