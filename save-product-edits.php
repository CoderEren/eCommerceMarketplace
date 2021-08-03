<?php
			if (empty($_POST['product-name'] || $_POST['product-description'] || $_POST['product-details'] || $_POST['product-url'] || $_POST['product-price'] || $_POST['product-discounted-price'])) {
				header('location:edit-product.php');
			} else {
			
			$nameErr = $descriptionErr = $imageURLErr = $productURLErr = "";
			$productname = $productdescription = $productdetails = $productprice = $productdiscountedprice = "";
			
			$imageURL = $_POST['image-url'];
			$productURL = $_POST['product-url'];
			$productcategory = $_POST['product-category'];
			$productid = $_POST['product-id'];
			
			if (!filter_var($imageURL, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
				$imageURLErr = "Invalid URL";
			}
			
			if (!filter_var($productURL, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
				$productURLErr = "Invalid URL";
			}
			
			$productname = test_input($_POST["product-name"]);
			$productdescription = test_input($_POST["product-description"]);
			$productdetails = test_input($_POST["product-details"]);
			$productprice = test_input($_POST["product-price"]);
			$productdiscountedprice = test_input($_POST["product-discounted-price"]);
			
			if ($imageURLErr == "" && $productURLErr == "") {
			
				$currentemail = $_COOKIE['seller-email'];
				
				// Create connection
				$conn = mysqli_connect("localhost", "root", "PASSWORD", "fullcashback");
				// Check connection
				if (!$conn) {
				  die("Connection failed: " . mysqli_connect_error());
				}

				$sql = "UPDATE products SET name='$productname', imageurl='$imageURL', description='$productdescription', details='$productdetails', link='$productURL', price='$productprice', discountedprice='$productdiscountedprice', category='$productcategory' WHERE id='$productid'";

				if (mysqli_query($conn, $sql)) {
				  echo "Product details updated successfully";
				} else {
				  echo "Error updating record: " . mysqli_error($conn);
				}

				mysqli_close($conn);
				header('location:seller-dashboard.php');
			}
		}
		
		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
?>
