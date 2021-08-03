<?php
session_start();

$productid = $_POST['productid'];
$productprice = $_POST['productprice'];
$selleremail = $_POST['selleremail'];
$buyerpaypalusername = $_SESSION['buyerpaypalusername'];

if (empty($_POST['productid']) || empty($_COOKIE['email'])) {
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
	
		$sql = "INSERT INTO payments (payment, useremail, selleremail, productid, buyerpaypalusername)
		VALUES ('$productprice', '$email', '$selleremail', '$productid', '$buyerpaypalusername')";

		if (mysqli_query($conn, $sql)) {
		  echo "New payment created successfully" . "<br>";
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}

	//Close the connection
	mysqli_close($conn);
	header('location:payments.php');
}
?>
