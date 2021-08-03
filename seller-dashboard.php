<?php
session_start(); 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FullCashback - Seller Dashboard</title>
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
			header('location:seller-home.php');
		}
	?>
	
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			$emailErr = $passwordErr = $paypalErr = $websiteErr = "";
			$email = $password = $paypalusername = "";
			
			$website = $_POST['website'];
			$website = filter_var($website, FILTER_SANITIZE_URL);
			
			if (filter_var($website, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
				echo("$website is a valid URL");
			} else {
				$websiteErr = "Invalid URL";
			}
			
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
			
			if ($emailErr == "" && $passwordErr == "" && $websiteErr == "") {
			
				$currentemail = $_COOKIE['seller-email'];
				//$email = $_POST['email'];
				//$password = $_POST['password'];
				//$paypalusername = $_POST['paypalusername'];
				
				// Create connection
				$conn = mysqli_connect("localhost", "root", "PASSWORD", "fullcashback");
				// Check connection
				if (!$conn) {
				  die("Connection failed: " . mysqli_connect_error());
				}

				$sql = "UPDATE sellers SET email='$email', password='$password', website='$website', paypalusername='$paypalusername' WHERE email='$currentemail'";

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
		if (isset($_COOKIE['seller-email'])) {
			$email = $_COOKIE['seller-email'];
			
			$db = new PDO("mysql:host=localhost;dbname=fullcashback","root","PASSWORD");

			$articlesor = $db->prepare("SELECT * FROM sellers WHERE email=:email");
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
				<input type="text" name="website" value="<?php echo $articlecek['website'];?>" class="form-control" id="floatingInput" placeholder="Website">
				<label for="floatingInput">Website</label>
				<span style="color:red;"><?php echo $websiteErr;?></span>
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
				<button class="btn btn-outline-secondary">Verify Email</button><br>
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
          <li><a href="seller-dashboard.php" class="nav-link px-2 link-secondary">Seller Dashboard</a></li>
        </ul>
		
		<?php
			if (isset($_COOKIE['seller-email'])) {
		?>

        <div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
			<?php echo $_COOKIE['seller-email']; ?>
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			<li><a class="dropdown-item" onclick="deleteCookie();">Log Out</a></li>
			<li><a class="dropdown-item" id="showModal">Edit Account Details</a></li>
			<li><a class="dropdown-item" href="seller-applications.php">Product Applications</a></li>
			<li><a class="dropdown-item" href="#">Payments</a></li>
		  </ul>
		</div>

		<?php } ?>

      </div>
    </div>
  </header>
	
<!-- 
row py-lg-5
col-lg-6 col-md-8 mx-auto
p-4 p-md-5 border rounded-3 bg-light
 -->
	
	<main>
	  <section class="py-5 text-center container">
		<div class="">
		  <div class="">
		  
			<h1 class="fw-light">Add A New Product</h1>
			<br>
			
			<form method="post" action="add-a-new-product.php" class="">
			
			  <div class="form-floating mb-3">
				<input type="text" name="product-name" value="" class="form-control" id="floatingInput" placeholder="Product Name">
				<label for="floatingInput">Product Name</label>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input type="url" name="image-url" value="" class="form-control" id="floatingInput" placeholder="Image URL">
				<label for="floatingPInput">Image URL</label>
				<span style="color:red;"><?php echo $imageURLErr;?></span>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input type="text" name="product-description" value="" class="form-control" id="floatingInput" placeholder="Short Description">
				<label for="floatingInput">Short Description</label>
			  </div>
			  
			  <div class="form-floating mb-3">
				<!--<input type="text" name="product-details" value="" class="form-control" id="floatingInput" placeholder="Long Description (Include Details)">-->
				<textarea rows="5" name="product-details" class="form-control" id="floatingInput" placeholder="Long Description (Include Details)"></textarea>
				<label for="floatingInput">Long Description (Include Details)</label>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input type="url" name="product-url" value="" class="form-control" id="floatingInput" placeholder="Product URL">
				<label for="floatingPInput">Product URL</label>
				<span style="color:red;"><?php echo $productURLErr;?></span>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input step="0.01" type="number" name="product-price" value="" class="form-control" id="floatingInput" placeholder="Product Normal Price">
				<label for="floatingInput">Product Normal Price</label>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input step="0.01" type="number" name="product-discounted-price" value="" class="form-control" id="floatingInput" placeholder="Product Discounted Price">
				<label for="floatingInput">Product Discounted Price</label>
			  </div>
			  
			  <select name="product-category" class="form-select" aria-label="Choose a category">
				  <option value="all categories" selected>All Categories</option>
				  <option value="electronics">Electronics</option>
				  <option value="toys">Toys</option>
				  <option value="clothing">Clothing</option>
				  <option value="beauty">Makeup & Beauty</option>
				</select><br>
			  
			  <button class="w-100 btn btn-lg btn-primary" type="submit">Add Product</button>
			</form>
			
		  </div>
		</div>
	  </section>

	  <div class="album py-5 bg-light">
		<div class="container">
		<h2 style="text-align:center;">My Products</h2>
		  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
		  
		  <?php
			$db = new PDO("mysql:host=localhost;dbname=fullcashback","root","PASSWORD");
			
			$selleremail = $_COOKIE['seller-email'];

			$articlesor = $db->prepare("SELECT * FROM products WHERE selleremail=:selleremail");
			$articlesor->execute(array(
				'selleremail' => $selleremail
			));
				  
			while($articlecek=$articlesor->fetch(PDO::FETCH_ASSOC)) {
		  ?>
		  
			<div class="col">
			  <div class="card shadow-sm">
				<img style="max-height:300px;" src="<?php echo $articlecek['imageurl'] ?>">
				<div class="card-body">
				  <h3><?php echo $articlecek['name'] ?></h3>
				  <p class="card-text"><?php echo $articlecek['description'] ?></p>
				  <p style="color:green;">£<?php echo $articlecek['discountedprice'] ?></p>
				  <strike style="color:red;">£<?php echo $articlecek['price'] ?></strike>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <form method="POST" action="edit-product.php">
						<input type="hidden" name="productid" value="<?php echo $articlecek['id'] ?>">
						<button type="submit" class="btn btn-primary">Edit Product Details</button>
					  </form>
					  <form method="POST" action="delete-product.php">
					    <input type="hidden" name="productid" value="<?php echo $articlecek['id'] ?>">
						<button type="submit" class="btn btn-danger">Delete Product</button>
					  </form>
					</div>
				  </div>
				</div>
			  </div>
			</div>
			
			<?php } ?>
			
		  </div>
		</div>
	  </div>
	</main>
	
	<script>
		function deleteCookie() {
			document.cookie = "seller-email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			document.cookie = "seller-password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
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
