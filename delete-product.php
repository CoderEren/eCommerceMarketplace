<?php
session_start();

$productid = $_POST['productid'];

if (empty($_POST['productid']) || empty($_COOKIE['seller-email'])) {
	header('location:seller-dashboard.php');
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
	
	$s = "SELECT * FROM products WHERE id = '$productid' && selleremail = '$email'";
	$result = mysqli_query($conn, $s);
			
	if (mysqli_num_rows($result) > 0) {
	
		$sql = "DELETE FROM products WHERE id='$productid' && selleremail = '$email'";

		if (mysqli_query($conn, $sql)) {
		  echo "Product deleted successfully" . "<br>";
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
		$sql2 = "DELETE * FROM applications WHERE productid='$productid'";
		
		if (mysqli_query($conn, $sql2)) {
		  echo "Product applications deleted successfully" . "<br>";
		} else {
		  echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
		}
	
	}

	//Close the connection
	mysqli_close($conn);
	header('location:seller-dashboard.php');
}
?>
