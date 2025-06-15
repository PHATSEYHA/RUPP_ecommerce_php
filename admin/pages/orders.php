<?php
require "./configs/connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id']; // Get user ID from session

// Handle Create Order
if (isset($_POST['create'])) {
    if (!isset($_POST['books_id']) || empty($_POST['books_id'])) {
        die("Error: No books selected.");
    }
    if (!isset($_POST['quantities']) || empty($_POST['quantities'])) {
        die("Error: Quantities are missing.");
    }

    $books_id = $_POST['books_id'];
    $quantities = $_POST['quantities'];
    $total_price = 0;
    $order_items = [];

    foreach ($books_id as $index => $book_id) {
        $quantity = $quantities[$index];
        // Use getOne from Connection class to fetch book details
        $book = Connection::getOne('books', ['id' => $book_id]);

        if (!$book) {
            die("Error: Book with ID $book_id not found.");
        }

        $book_price = $book['price'];
        $total_price += $book_price * $quantity;

        $order_items[] = [
            'book_id' => $book_id,
            'quantity' => $quantity,
            'price' => $book_price
        ];
    }

    // Insert order into the database using Connection::insert
    $order_data = [
        'user_id' => $user_id,
        'total_price' => $total_price
    ];
    Connection::insert('orders', $order_data);
    $order_id = Connection::connect()->lastInsertId();

    // Insert order items into the database using Connection::insert
    foreach ($order_items as $item) {
        $item_data = [
            'order_id' => $order_id,
            'book_id' => $item['book_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ];
        Connection::insert('order_items', $item_data);
    }

    // Redirect to payment page
    header("Location: payment.php?order_id=" . $order_id);
    exit();
}

// Fetch orders for display using Connection::getAll with a JOIN
$orders = Connection::getAll('orders', [], [
    'join' => [
        'table' => 'users',
        'on' => 'orders.user_id = users.id'
    ],
    'select' => 'orders.*, users.username AS user_name, users.email'
]);

foreach ($orders as &$order) {
    // Fetch order items for each order using Connection::getAll with a JOIN
    $order['items'] = Connection::getAll('order_items', ['order_id' => $order['id']], [
        'join' => [
            'table' => 'books',
            'on' => 'order_items.book_id = books.id'
        ],
        'select' => 'order_items.*, books.title, books.price, books.image_url'
    ]);
}

$page_title = "Orders";
$index = 1;
include "./layouts/head.php";
?>

<h2 class="mb-4 text-center">📦 Order Management</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Status</th>
            <th>Total Price ($)</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $index++ ?></td>
            <td><?= htmlspecialchars($order['user_name']) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td><?= number_format($order['total_price'], 2) ?></td>
            <td><?= $order['created_at'] ?></td>
            <td>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#orderDetailsModal-<?= $order['id'] ?>">View Items</button>
                <a href="index.php?route=orders&delete=<?= $order['id'] ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete Order #<?= $order['id'] ?>?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php foreach ($orders as $order): ?>
<div class="modal fade" id="orderDetailsModal-<?= $order['id'] ?>" tabindex="-1" aria-labelledby="orderDetailsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order #<?= $order['id'] ?> - Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <?php foreach ($order['items'] as $item): ?>
                    <li class="list-group-item">
                        <img src="uploads/storages/images/<?= htmlspecialchars($item['image_url']) ?>" width="50"
                            alt="<?= htmlspecialchars($item['title']) ?>">
                        <?= htmlspecialchars($item['title']) ?> (x<?= $item['quantity'] ?>) -
                        $<?= number_format($item['price'], 2) ?> each
                        <strong>Total: $<?= number_format($item['quantity'] * $item['price'], 2) ?></strong>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include "./layouts/footer.php"; ?>