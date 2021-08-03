<?php
session_start(); 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FullCashback Product Applications - Get Full Cashback!</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
		  
		  .modal {
			  display: none; /* Hidden by default */
			  position: fixed; /* Stay in place */
			  z-index: 1; /* Sit on top */
			  left: 0;
			  top: 0;
			  width: 100%; /* Full width */
			  height: 100%; /* Full height */
			  overflow: auto; /* Enable scroll if needed */
			  background-color: rgb(0,0,0); /* Fallback color */
			  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
			}

			/* Modal Content/Box */
			.modal-content {
			  background-color: #fefefe;
			  margin: 15% auto; /* 15% from the top and centered */
			  padding: 20px;
			  border: 1px solid #888;
			  width: 80%; /* Could be more or less, depending on screen size */
			}

			/* The Close Button */
			.close {
			  color: #aaa;
			  float: right;
			  font-size: 28px;
			  font-weight: bold;
			}

			.close:hover,
			.close:focus {
			  color: black;
			  text-decoration: none;
			  cursor: pointer;
			}
		</style>
	</head>
	<body>
	
	<?php
		if (!isset($_COOKIE['email'])) {
			header('location:login.php');
		}
		
		$buyerpaypalusername = "";
	
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			$emailErr = $passwordErr = $paypalErr = "";
			$email = $password = $paypalusername = "";
			
			if (empty($_POST["email"])) {
				$emailErr = "Email is required";
			  } else {
				$email = test_input($_POST["email"]);
				// check if e-mail address is well-formed
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  $emailErr = "Invalid email format";
				}
			  }
			
			if (empty($_POST["password"])) {
				$passwordErr = "Password is required";
			} else {
				$password = test_input($_POST["password"]);
			}
			
			$paypalusername = test_input($_POST["paypalusername"]);
			
			if ($emailErr == "" && $passwordErr == "") {
			
				$currentemail = $_COOKIE['email'];
				//$email = $_POST['email'];
				//$password = $_POST['password'];
				//$paypalusername = $_POST['paypalusername'];
				
				// Create connection
				$conn = mysqli_connect("localhost", "root", "2005eren07", "fullcashback");
				// Check connection
				if (!$conn) {
				  die("Connection failed: " . mysqli_connect_error());
				}

				$sql = "UPDATE users SET email='$email', password='$password', paypalusername='$paypalusername' WHERE email='$currentemail'";

				if (mysqli_query($conn, $sql)) {
				  echo "Account details updated successfully";
				} else {
				  echo "Error updating record: " . mysqli_error($conn);
				}

				mysqli_close($conn);
			}
		}
		
		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	?>
	
	<?php
		if (isset($_COOKIE['email'])) {
			$email = $_COOKIE['email'];
			
			$db = new PDO("mysql:host=localhost;dbname=fullcashback","root","PASSWORD");

			$articlesor = $db->prepare("SELECT * FROM users WHERE email=:email");
			$articlesor->execute(array(
				'email' => $email
			));
				  
			while($articlecek=$articlesor->fetch(PDO::FETCH_ASSOC)) {
				$buyerpaypalusername = $articlecek['paypalusername'];
				$_SESSION['buyerpaypalusername'] = $buyerpaypalusername;
		
	?>
	
	<!-- The Modal -->
	<div id="myModal" class="modal">

	  <!-- Modal content -->
	  <div class="modal-content">
		<span class="close">&times;</span>
		
		  <div class="card-body">
			<h1>Edit Your Account Details</h1>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="p-4 p-md-5 border rounded-3 bg-light">
			  <div class="form-floating mb-3">
				<input type="email" name="email" value="<?php echo $articlecek['email'];?>" class="form-control" id="floatingInput" placeholder="name@example.com">
				<label for="floatingInput">Email address</label>
				<span style="color:red;"><?php echo $emailErr;?></span>
			  </div>
			  <div class="form-floating mb-3">
				<input type="password" name="password" value="<?php echo $articlecek['password'];?>" class="form-control" id="floatingPassword" placeholder="Password">
				<label for="floatingPassword">Password</label>
				<span style="color:red;"><?php echo $passwordErr;?></span>
			  </div>
			  <div class="form-floating mb-3">
				<input type="text" name="paypalusername" value="<?php echo $articlecek['paypalusername'];?>" class="form-control" id="floatingInput" placeholder="PayPal Username">
				<label for="floatingPassword">PayPal Username</label>
				<span style="color:red;"><?php echo $paypalErr;?></span>
			  </div>
			  <div class="form-floating mb-3 input-group">
				<input type="text" disabled name="emailverified" value="<?php echo $articlecek['emailverified'];?>" class="form-control" id="floatingInput" placeholder="Email Verified">
				<label for="floatingPassword">Email Verified</label>
				<?php
					if ($articlecek['emailverified'] == "false") {
				?>
				<a href="send-email.php?email=<?php echo $articlecek['email'] ?>"><button class="btn btn-outline-secondary">Verify Email</button></a><br>
				<?php } ?>
			  </div>
			  <span style="color:red;"><?php echo $emailVerifiedErr;?></span>
			  <button class="w-100 btn btn-lg btn-primary" type="submit">Save Changes</button>
			</form>
			
		  </div>
		
	  </div>
	</div>
	
	<?php
			}
		}
	?>
	
	<header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="index.php" class="nav-link px-2 link-secondary">Home</a></li>
          <li><a href="home.php" class="nav-link px-2 link-dark">Products</a></li>
        </ul>

		<form method="GET" action="search.php" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
          <input name="name" type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>
		
		<?php
			if (isset($_COOKIE['email'])) {
		?>

        <div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
			<?php echo $_COOKIE['email']; ?>
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			<li><a class="dropdown-item" onclick="deleteCookie();">Log Out</a></li>
			<li><a class="dropdown-item" id="showModal">Edit Account Details</a></li>
			<li><a class="dropdown-item" href="home.php">Products</a></li>
			<li><a class="dropdown-item" href="payments.php">Payments</a></li>
		  </ul>
		</div>
		
		<!-- Make a modal where the user can edit their account details (PayPal username and Email Verifying button) when they click on Edit Details -->

		<?php } ?>

      </div>
    </div>
  </header>

	<main>
	
	<section class="py-5 text-center container">
		<div class="row py-lg-5">
		  <div class="col-lg-6 col-md-8 mx-auto">
			<h1 class="fw-light">My Product Applications</h1>
			<h4>If your application to buy a product is approved, the 'Buy Now' button appears. When you have successfully purchased the product and wrote a review, click on 'Submit Purchase' and you will get paid when your purchase is verified.</h4>
			<br>
		  </div>
		</div>
	  </section>
	
	  <div class="album py-5 bg-light">
		<div class="container">
		  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
		  
		  <?php
			$useremail = $_COOKIE['email'];
			$productids = array();
			$purchaseallowed = array();
				
			$servername = "localhost";
			$username = "root";
			$password = "PASSWORD";
			$dbname = "fullcashback";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT * FROM applications WHERE useremail='$useremail'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
				  array_push($productids, $row['productid']);
				  $purchaseallowed[$row['productid']] = $row['purchaseallowed'];
			  }
			} else {
			  echo "No product applications. Go to the products page to apply to buy a product.";
			}
			
			// Create connection
			$conn_ = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn_->connect_error) {
			  die("Connection failed: " . $conn_->connect_error);
			}
				  
			$arrayLength = count($productids);
			$i = 0;
			
			while ($i < $arrayLength) {
				$sql_ = "SELECT * FROM products WHERE id='$productids[$i]'";
				$result_ = $conn_->query($sql_);
				if ($result_->num_rows > 0) {
					while($row_ = $result_->fetch_assoc()) {
			?>
					  
					  <div class="col">
						  <div class="card shadow-sm">
							<img style="max-height:300px;" src="<?php echo $row_['imageurl'] ?>">
							<div class="card-body">
							  <h3><?php echo $row_['name'] ?></h3>
							  <p class="card-text"><?php echo $row_['description'] ?></p>
							  <p style="color:green;">£<?php echo $row_['discountedprice'] ?></p>
							  <strike style="color:red;">£<?php echo $row_['price'] ?></strike>
							  <div class="d-flex justify-content-between align-items-center">
								<div class="btn-group">
								<?php
									if ($purchaseallowed[$row_['id']] == "true") {
								?>
								  <a href="<?php echo $row_['link'] ?>"><button type="button" class="btn btn-primary">Buy Now</button></a>
								  <form method="POST" action="add-payment.php">
									<input type="hidden" name="productid" value="<?php echo $row_['id'] ?>">
									<input type="hidden" name="productprice" value="<?php echo $row_['price'] ?>">
									<input type="hidden" name="selleremail" value="<?php echo $row_['selleremail'] ?>">
								    <button type="submit" class="btn btn-success">Submit Purchase</button>
								  </form>
									<?php } else { ?>
									<p>Application pending</p>
									<?php } ?>
									<form method="POST" action="delete-application.php">
									<input type="hidden" name="productid" value="<?php echo $row_['id'] ?>">
								  <button type="submit" class="btn btn-danger">Delete Application</button>
								  </form>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					  
					  <?php
					  
					}
				}
					$i++;
			}
			
			$conn->close();
			
			$conn_->close();
			
			?>
			
		  </div>
		</div>
	  </div>
	</main>
	
	<script>
		function deleteCookie() {
			document.cookie = "email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			location.reload();
		}
		
		var modal = document.getElementById("myModal");

		// Get the button that opens the modal
		var btn = document.getElementById("showModal");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks on the button, open the modal
		btn.onclick = function() {
		  modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == modal) {
			modal.style.display = "none";
		  }
		}
	</script>
	
	</body>
</html>
