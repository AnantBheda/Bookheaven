<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['add_to_cart'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart!';
   } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Our Shop</h3>
      <p> <a href="home.php">Home</a> / Shop </p>
   </div>

   <!-- Navigation Links for filtering -->
 

   <section class="products">

      <h1 class="title">Latest Products</h1>
      <div class="filter-nav" style="display: flex !important; justify-content: center !important; align-items: center !important; margin-bottom: 15px !important; width: 100%; text-align: center;">
      <a style="margin-right:15px" href="javascript:void(0);" class="btn" data-filter="all">All Books</a>
      <a style="margin-right:15px" href="javascript:void(0);" class="btn" data-filter="old">Old Books</a>
      <a style="margin-right:15px" href="javascript:void(0);" class="btn" data-filter="new">New Books</a>
   </div>
     
      <div class="box-container">

         <?php
         // Select all products from the database
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <form action="" method="post" class="box" data-oldbookflag="<?php echo $fetch_products['OldBookFlag']; ?>">
                  <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <!-- <div class="oldBookFlag"><?php echo $fetch_products['OldBookFlag'] == 0 ? 'Old Book' : 'New Book'; ?></div> -->
                  <div class="price" style="font-size:13px">₹<?php echo $fetch_products['price']; ?>/-</div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>

   </section>

   <?php include 'footer.php'; ?>

   <!-- custom js file link -->
   <script src="js/script.js"></script>

   <script>
      // Filter products based on OldBookFlag (old or new)
      const filterLinks = document.querySelectorAll('.filter-nav .btn');
      const productBoxes = document.querySelectorAll('.products .box');

      filterLinks.forEach(link => {
         link.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');
            filterProducts(filter);
         });
      });

      function filterProducts(filter) {
         productBoxes.forEach(box => {
            const oldBookFlag = box.getAttribute('data-oldbookflag');

            if (filter === 'all') {
               box.style.display = 'block'; // Show all products
            } else if (filter === 'old' && oldBookFlag == 0) {
               box.style.display = 'block'; // Show old books
            } else if (filter === 'new' && oldBookFlag == 1) {
               box.style.display = 'block'; // Show new books
            } else {
               box.style.display = 'none'; // Hide products that don't match the filter
            }
         });
      }

      // Initialize with "all" filter applied
      filterProducts('all');
   </script>

</body>

</html>
