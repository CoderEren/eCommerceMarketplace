<!DOCTYPE html>
<html>
<head>
	<title>FullCashback Verify Email</title>
	<link href="https://getbootstrap.com/docs/5.0/examples/sign-in/signin.css" rel="stylesheet">
	<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	<style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
</head>
<body class="text-center">

<main class="form-signin">
	<?php
	$email = $_GET['email'];
	$conn = mysqli_connect("localhost", "root", "PASSWORD", "fullcashback");
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "UPDATE users SET emailverified='true' WHERE email='$email'";

	if (mysqli_query($conn, $sql)) {
		echo $email . " is successfully verified.";
		echo "<a href='index.php'> Return Home</a>";
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}

	mysqli_close($conn);
?>
</main>

</body>
</html>
