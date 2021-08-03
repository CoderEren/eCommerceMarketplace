<?php
session_start();

$productid = $_POST['productid'];

if (empty($_POST['productid']) || empty($_COOKIE['seller-email'])) {
	header('location:seller-applications.php');
} else {
	$email = $_COOKIE['seller-email'];
	$buyeremail = $_SESSION['buyer-email'];

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
	
	$s = "SELECT * FROM applications WHERE productid = '$productid' && useremail = '$buyeremail' && selleremail = '$email'";
	$result = mysqli_query($conn, $s);
			
	if (mysqli_num_rows($result) > 0) {
	
		$sql = "UPDATE applications SET purchaseallowed='false' WHERE productid = '$productid' && useremail = '$buyeremail' && selleremail = '$email'";

		if (mysqli_query($conn, $sql)) {
		  echo "Record updated successfully" . "<br>";
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	
	}

	//Close the connection
	mysqli_close($conn);
	header('location:seller-applications.php');
}
?>
