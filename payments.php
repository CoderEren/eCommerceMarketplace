<?php
session_start(); 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FullCashback Payments - Get Full Cashback!</title>
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
				$conn = mysqli_connect("localhost", "root", "PASSWORD", "fullcashback");
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
			<li><a class="dropdown-item" href="applications.php">Product Applications</a></li>
		  </ul>
		</div>

		<?php } ?>

      </div>
    </div>
  </header>

	<main>
	
	<section class="py-5 text-center container">
		<div class="row py-lg-5">
		  <div class="col-lg-6 col-md-8 mx-auto">
			<h1 class="fw-light">My Pending Payments</h1>
			<br>
		  </div>
		</div>
	  </section>
	
	  <div class="album py-5 bg-light">
		<div class="container">
		<table class="table table-hover table-striped">
		  <thead>
			<tr>
			  <th scope="col">Product Name</th>
			  <th scope="col">Payment</th>
			  <th scope="col">Purchase Verified</th>
			  <th scope="col">Payment Received</th>
			  <th scope="col">Delete Payment Request</th>
			</tr>
		  </thead>
		  <tbody>
		  
		  <?php
			$useremail = $_COOKIE['email'];
			$productids = array();
			$purchaseverified = array();
			$paymentids = array();
			$paymentreceived = array();
				
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

			$sql = "SELECT * FROM payments WHERE useremail='$useremail'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
				  array_push($productids, $row['productid']);
				  $purchaseverified[$row['productid']] = $row['purchaseverified'];
				  $paymentids[$row['productid']] = $row['id'];
				  $paymentreceived[$row['productid']] = $row['paymentreceived'];
			  }
			} else {
			  echo "No pending payments. Go to the product applications page to request cashback for a product.";
			}
			
			//$conn->close();
			
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
					  
					  <tr>
						  <td><?php echo $row_['name']; ?></td>
						  <td>??<?php echo $row_['price']; ?></td>
						  <td><?php echo $purchaseverified[$row_['id']] ?></td>
						  <td><?php echo $paymentreceived[$row_['id']] ?></td>
						  <form method="POST" action="delete-payment.php">
							<input type="hidden" name="paymentid" value="<?php echo $paymentids[$row_['id']] ?>">
							<td><button type="submit" class="btn btn-danger">Delete Payment Request</button></td>
						  </form>
						</tr>
					  
					  <?php
					  
					}
				}
					$i++;
			}
			
			$conn->close();
			
			$conn_->close();
			
			?>
			
		  </tbody>
		</table>
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
