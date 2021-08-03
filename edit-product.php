<?php
session_start(); 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>FullCashback - Edit Product Details</title>
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
	
	<header class="p-3 mb-3 border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="seller-dashboard.php" class="nav-link px-2 link-secondary">Seller Dashboard</a></li>
        </ul>
		
      </div>
    </div>
  </header>

	<main>
	  <section class="py-5 text-center container">
		<div class="">
		  <div class="">
		  
		  <?php
		if (isset($_COOKIE['seller-email'])) {
			$email = $_COOKIE['seller-email'];
			$productid = $_POST['productid'];
			
			$db = new PDO("mysql:host=localhost;dbname=fullcashback","root","PASSWORD");

			$articlesor = $db->prepare("SELECT * FROM products WHERE selleremail=:email && id=:id");
			$articlesor->execute(array(
				'email' => $email,
				'id' => $productid
			));
				  
			while($articlecek=$articlesor->fetch(PDO::FETCH_ASSOC)) {
		
	?>
		  
			<h1 class="fw-light">Edit Product Details</h1>
			<br>
			
			<form method="post" action="save-product-edits.php">
			<input type="hidden" name="product-id" value="<?php echo $articlecek['id'] ?>">
			  <div class="form-floating mb-3">
				<input required type="text" name="product-name" value="<?php echo $articlecek['name']; ?>" class="form-control" id="floatingInput" placeholder="Product Name">
				<label for="floatingInput">Product Name</label>
				<span style="color:red;"><?php echo $nameErr;?></span>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input type="url" name="image-url" value="<?php echo $articlecek['imageurl']; ?>" class="form-control" id="floatingInput" placeholder="Image URL">
				<label for="floatingPInput">Image URL</label>
				<span style="color:red;"><?php echo $imageURLErr;?></span>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input required type="text" name="product-description" value="<?php echo $articlecek['description']; ?>" class="form-control" id="floatingInput" placeholder="Short Description">
				<label for="floatingInput">Short Description</label>
				<span style="color:red;"><?php echo $descriptionErr;?></span>
			  </div>
			  
			  <div class="form-floating mb-3">
				<!--<input type="text" name="product-details" value="" class="form-control" id="floatingInput" placeholder="Long Description (Include Details)">-->
				<textarea required rows="5" name="product-details" class="form-control" id="floatingInput" placeholder="Long Description (Include Details)"><?php echo $articlecek['name']; ?></textarea>
				<label for="floatingInput">Long Description (Include Details)</label>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input required type="url" name="product-url" value="<?php echo $articlecek['link']; ?>" class="form-control" id="floatingInput" placeholder="Product URL">
				<label for="floatingPInput">Product URL</label>
				<span style="color:red;"><?php echo $productURLErr;?></span>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input required step="0.01" type="number" name="product-price" value="<?php echo $articlecek['price']; ?>" class="form-control" id="floatingInput" placeholder="Product Normal Price">
				<label for="floatingInput">Product Normal Price</label>
			  </div>
			  
			  <div class="form-floating mb-3">
				<input required step="0.01" type="number" name="product-discounted-price" value="<?php echo $articlecek['discountedprice']; ?>" class="form-control" id="floatingInput" placeholder="Product Discounted Price">
				<label for="floatingInput">Product Discounted Price</label>
			  </div>
			  
			  <select name="product-category" class="form-select" aria-label="Choose a category">
				  <option value="all categories" selected>All Categories</option>
				  <option value="electronics">Electronics</option>
				  <option value="toys">Toys</option>
				  <option value="clothing">Clothing</option>
				  <option value="beauty">Makeup & Beauty</option>
				</select><br>
				
				<?php
			}
		}
				?>
			  
			  <button class="w-100 btn btn-lg btn-primary" type="submit">Save Changes</button>
			</form>
			
		  </div>
		</div>
	  </section>
	</main>
	
	</body>
</html>
