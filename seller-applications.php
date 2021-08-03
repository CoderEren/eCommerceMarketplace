<?php
session_start(); 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FullCashback Seller Product Applications - Get Full Cashback!</title>
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
		if (!isset($_COOKIE['seller-email'])) {
			header('location:seller-login.php');
		}
		
		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	?>
	
	<header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="seller-dashboard.php" class="nav-link px-2 link-secondary">Seller Dashboard</a></li>
        </ul>

		<form method="GET" action="search.php" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
          <input name="name" type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>
		
		<?php
			if (isset($_COOKIE['seller-email'])) {
		?>

        <div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
			<?php echo $_COOKIE['seller-email']; ?>
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			<li><a class="dropdown-item" onclick="deleteCookie();">Log Out</a></li>
			<li><a class="dropdown-item" href="seller-dashboard.php">Edit Account Details</a></li>
			<li><a class="dropdown-item" href="seller-dashboard.php">Seller Dashboard</a></li>
			<li><a class="dropdown-item" href="#">Payments</a></li>
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
			<h1 class="fw-light">Approve Product Applications</h1>
			<h4>Approve your product applications so people can access the purchase link of your product.</h4>
			<br>
		  </div>
		</div>
	  </section>
	
	  <div class="album py-5 bg-light">
		<div class="container">
		  <table class="table table-hover table-striped">
		  <thead>
			<tr>
			  <th scope="col">Buyer Email</th>
			  <th scope="col">Product Name</th>
			  <th scope="col">Purchase Allowed</th>
			  <th scope="col">Approve</th>
			  <th scope="col">Unapprove</th>
			</tr>
		  </thead>
		  <tbody>

		  
		  <?php
			$selleremail = $_COOKIE['seller-email'];
			$productids = array();
			$purchaseallowed = array();
			$buyeremails = array();
				
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

			$sql = "SELECT * FROM applications WHERE selleremail='$selleremail'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
				  array_push($productids, $row['productid']);
				  $purchaseallowed[$row['productid']] = $row['purchaseallowed'];
				  $buyeremails[$row['productid']] = $row['useremail'];
			  }
			} else {
			  echo "No product applications to approve.";
			}
			
			//$conn->close();
			
			// Create connection
			$conn_ = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn_->connect_error) {
			  die("Connection failed: " . $conn_->connect_error);
			}
			
			//foreach ($productids as $productid) {
				//$sql_ = "SELECT * FROM products WHERE id='$productid'";
				//$result_ = $conn_->query($sql_);

				//if ($result_->num_rows > 0) {
				  // output data of each row
				  //while($row_ = $result_->fetch_assoc()) {
					  
			$arrayLength = count($productids);
			$i = 0;
			
			while ($i < $arrayLength) {
				$sql_ = "SELECT * FROM products WHERE id='$productids[$i]'";
				$result_ = $conn_->query($sql_);
				if ($result_->num_rows > 0) {
					while($row_ = $result_->fetch_assoc()) {
			?>
			


    <tr>
      <td><?php echo substr($buyeremails[$row_['id']], 0, 5)."*****"; ?></td>
	  <td><?php echo $row_['name']; ?></td>
      <td><?php echo $purchaseallowed[$row_['id']] ?></td>
	  <?php $_SESSION["buyer-email"] = $buyeremails[$row_['id']]; ?>
	  <form method="POST" action="approve-application.php">
		<input type="hidden" name="productid" value="<?php echo $row_['id'] ?>">
		<td><button type="submit" class="btn btn-primary">Approve</button></td>
	  </form>
	  <form method="POST" action="unapprove-application.php">
		<input type="hidden" name="productid" value="<?php echo $row_['id'] ?>">
		<td><button type="submit" class="btn btn-danger">Unapprove</button></td>
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
			document.cookie = "seller-email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			document.cookie = "seller-password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			location.reload();
		}
	</script>
	
	</body>
</html>
