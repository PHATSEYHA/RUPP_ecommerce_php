<?php
session_start();
include('./layouts/header.php');
require_once './admin/configs/connection.php';

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  if ($_POST['action'] === 'add') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $quantity = 1;

    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
      $_SESSION['cart'][$product_id] = [
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'image_url' => $product_image,
        'quantity' => $quantity,
      ];
    }

    header("Location: cart.php");
    exit();
  }

  if ($_POST['action'] === 'update' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity']));

    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }

    header("Location: cart.php");
    exit();
  }

  if ($_POST['action'] === 'remove' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);

    header("Location: cart.php");
    exit();
  }
}

$cart = $_SESSION['cart'];
$subtotal = 0;
foreach ($cart as $item) {
  $subtotal += $item['price'] * $item['quantity'];
}

$index = 1;
?>

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

<div class="cart-section mt-150 mb-150">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-12">
        <div class="cart-table-wrap">
          <table class="cart-table">
            <thead class="cart-table-head">
              <tr class="table-head-row">
                <th>#</th>
                <th class="product-image">Product Image</th>
                <th class="product-name">Name</th>
                <th class="product-price">Price</th>
                <th class="product-quantity">Quantity</th>
                <th class="product-total">Total</th>
                <th class="product-remove"></th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($cart)) : ?>
                <?php foreach ($cart as $id => $product) : ?>
                  <tr class="table-body-row">
                  <td><?php echo $index++; ?></td>
                    <td class="product-image">
                      <img src="admin/uploads/storages/images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                    </td>
                    <td class="product-name"><?php echo $product['name']; ?></td>
                    <td class="product-price">$<?php echo number_format($product['price'], 2); ?></td>
                    <td class="product-quantity">
                      <form method="POST" action="">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number"  name="quantity" value="<?php echo $product['quantity']; ?>" min="1" class="quantity-input w-25 me-3">
                        <button type="submit" class="update-btn ms-3 bg-transparent border-3 rounded border-warning">Update</button>
                      </form>
                    </td>
                    <td class="product-total">$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
                    <td class="product-remove">
                      <form method="POST" action="">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="remove-btn border-1 border-0 fs-1 p-3 rounded-circle bg-transparent" style="width: 50px; font-size: 20px ;"  >X</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="6" class="text-center">Your cart is empty.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="total-section">
          <table class="total-table">
            <thead class="total-table-head">
              <tr class="table-total-row">
                <th>Total</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody>
              <tr class="total-data">
                <td><strong>Subtotal: </strong></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
              </tr>
            </tbody>
          </table>
          <div class="cart-buttons">
            <a href="cart.php" class="boxed-btn">Update Cart</a>
            <a href="checkout.php" class="boxed-btn black">Check Out</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('./layouts/footer.php'); ?>