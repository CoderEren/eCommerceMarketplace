<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>FullCashback Seller Login</title>
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

<?php
		// define variables and set to empty values
		$loginemailErr = $loginpasswordErr = "";
		$loginemail = $loginpassword = "";
		
		if (isset($_COOKIE['seller-email']) && isset($_COOKIE['seller-password'])) {
			$loginemail = $_COOKIE['seller-email'];
			$loginpassword = $_COOKIE['seller-password'];
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
		  if (empty($_POST["loginemail"])) {
			$loginemailErr = "Email is required";
		  } else {
			$loginemail = test_input($_POST["loginemail"]);
			// check if e-mail address is well-formed
			if (!filter_var($loginemail, FILTER_VALIDATE_EMAIL)) {
			  $loginemailErr = "Invalid email format";
			}
		  }
		  
		  if (empty($_POST["loginpassword"])) {
			$loginpasswordErr = "Password is required";
		  } else {
			$loginpassword = test_input($_POST["loginpassword"]);
		  }
		  
		  if ($loginemailErr == "" && $loginpasswordErr == "") {
			$servername = "localhost";
			$username = "root";
			$serverpassword = "PASSWORD";
			$dbname = "fullcashback";

			// Create connection
			$conn = mysqli_connect($servername, $username, $serverpassword, $dbname);

			// Check connection
			if (!$conn) {
			  die("Connection failed: " . mysqli_connect_error());
			}
			
			$s = "SELECT * FROM users WHERE email = '$loginemail' && password = '$loginpassword'";
			$result = mysqli_query($conn, $s);
			
			if (mysqli_num_rows($result) > 0) {
				//$_SESSION['loggedinemail'] = $loginemail;
				setcookie("seller-email", $_POST['loginemail'], time() + (86400 * 30), "/");
				setcookie("seller-password", $_POST['loginpassword'], time() + (86400 * 30), "/");
				header('location:seller-dashboard.php');
				mysqli_close($conn);
			} else {
				$loginemailErr = "Wrong login details!";
				$loginpasswordErr = "Wrong login details!";
			}
		  }
		}

		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	?>



<main class="form-signin">

	<header class="p-3 bg-dark text-white">
		<div class="container">
		  <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
			<a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
			  <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
			</a>
			<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
			  <li><a href="seller-home.php" class="nav-link px-2 text-white">Seller Home</a></li>
			  <li><a href="seller-home.php#how-it-works" class="nav-link px-2 text-white">How It Works</a></li>
			</ul>
		  </div>
		</div>
	</header>

	<h1 class="h3 mb-3 fw-normal">FullCashback</h1>
	<h1 class="h3 mb-3 fw-normal">Seller Log In</h1>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div class="form-floating">
		  <input type="email" name="loginemail" value="<?php echo $loginemail;?>" class="form-control" id="floatingInput" placeholder="name@example.com">
		  <label for="floatingInput">Email address</label>
		  <span style="color:red;"><?php echo $loginemailErr;?></span>
		</div>
		<div class="form-floating">
		  <input type="password" name="loginpassword" value="<?php echo $loginpassword;?>" class="form-control" id="floatingPassword" placeholder="Password">
		  <label for="floatingPassword">Password</label>
		  <span style="color:red;"><?php echo $loginpasswordErr;?></span>
		</div>
		<button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Log in</button>
	</form>
	<br>
	<p>Don't have a seller account? <a href="seller-home.php">Sign up for free!</a></p>
</main>

</body>
</html>
