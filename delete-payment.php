<?php
session_start();

$paymentid = $_POST['paymentid'];

if (empty($_POST['paymentid']) || empty($_COOKIE['email'])) {
	header('location:payments.php');
} else {
	$email = $_COOKIE['email'];

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
	
		$sql = "DELETE FROM payments WHERE id='$paymentid'";

		if (mysqli_query($conn, $sql)) {
		  echo "Payment deleted successfully" . "<br>";
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	
	//Close the connection
	mysqli_close($conn);
	header('location:payments.php');
}
?>
