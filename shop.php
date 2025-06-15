<?php
include('./layouts/header.php');
require_once './admin/configs/connection.php';
session_start();  
$products = Connection::getAll('products');
?>


<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2 text-center">
        <div class="breadcrumb-text">
          <p>High Performance and Adventure</p>
          <h1>Big Bike Shop</h1>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end breadcrumb section -->

<!-- products -->
<div class="product-section mt-150 mb-150">
  <div class="container">
    <div class="row product-lists">
      <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
          <div class="col-lg-4 col-md-6 text-center">
            <div class="single-product-item">
              <div class="product-image">
                <a href="single-product.php?id=<?php echo $product['id']; ?>">
                  <img style="height: 200px; width: 200px;" src="admin/uploads/storages/images/<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                </a>
              </div>
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <p class="product-price"><span>Price:</span> $<?php echo number_format($product['price'], 2); ?></p>
              <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>

              <!-- Add to cart form -->
              <form method="POST" action="cart.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $product['image_url']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button role="button" type="submit" name="submit" class="btn-style cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
              </form>

            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p>No products available at the moment.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- end products -->

<?php include('./layouts/footer.php'); ?>
