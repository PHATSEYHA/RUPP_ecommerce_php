<?php
require './configs/connection.php';
$page_title = "Dashboard";

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php?route=login");
    exit();
}

$categories = Connection::getAll('categories');


try {
    $totalBooks = Connection::getCount('products');
    $books = Connection::getAll('products');
    $totalCategories = Connection::getCount('categories');
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
$index = 1;


include "./layouts/head.php";
?>

<div class="container my-2">
    <h2 class="mb-3">Dashboard</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-white mb-3" style="background-color: teal;">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text fs-3"><?= $totalBooks ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white mb-3" style="background-color: teal;">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text fs-3"><?= $totalCategories ?></p>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4 shadow p-3 rounded-3" style="border: 1px solid teal;">
        <h4 class="">Latest Product</h4>
        <table class="table table-hover">
            <thead style="background-color: #ddd; " class="">
                <tr class="table-head-row">
                    <th class="">#</th>
                    <th>Name</th>
                    <th>Price ($)</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td class="text-center"><?php echo $index++; ?></td>
                            <td><?= htmlspecialchars($book['name']) ?></td>
                            <td>$<?= number_format($book['price'], 2) ?></td>
                            <td><?= $book['stock'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4 shadow p-3 rounded-3" style="border: 1px solid teal;">
        <h4 class="">Latest Category</h4>
        <table class="table table-hover">
            <thead style="background-color: #ddd; " class="">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td class="text-center"><?php echo $index++; ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($category['name']); ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "./layouts/footer.php"; ?>