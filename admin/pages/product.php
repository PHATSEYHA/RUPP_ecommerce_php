<?php
require './configs/connection.php';
$page_title = "Products";

$products = Connection::getAll('products');
$categories = Connection::getAll('categories');
$index = 1;

function handleFileUpload($file, $targetDir = "uploads/storages/images/")
{
    if (!empty($file['name'])) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $imageName = time() . "_" . basename($file['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $imageName;
        } else {
            throw new Exception("Error uploading file.");
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $image = $_FILES['image_url'] ?? null;

    try {
        Connection::connect()->beginTransaction();

        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";

        $image_url = handleFileUpload($image);
        echo "Image URL: " . $image_url;

        if (isset($_POST['add'])) {
            $data = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image_url' => $image_url
            ];
            Connection::insert('products', $data);
        } elseif (!empty($_POST['id'])) {
            $id = intval($_POST['id']);

            if (!$image_url) {
                $product = Connection::getOne('products', ['id' => $id], ['select' => 'image_url']);
                $image_url = $product['image_url'] ?? '';
            }

            $data = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image_url' => $image_url
            ];
            Connection::update('products', $data, ['id' => $id]);
        } else {
            throw new Exception("Invalid request.");
        }

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        Connection::connect()->commit();
        header("Location: index.php?route=product&message=Operation successful");
        exit();
    } catch (Exception $e) {
        Connection::connect()->rollBack();
        die("Error: " . $e->getMessage());
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        Connection::delete('products', ['id = ?'], [$id]);
        header("Location: index.php?route=product&message=Product deleted successfully");
        exit();
    } catch (Exception $e) {
        die("Error deleting product: " . $e->getMessage());
    }
}

include "./layouts/head.php";
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Product List</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus-circle"></i> Add New Product
        </button>
    </div>
    <div class="mb-5 w-100 d-flex justify-content-center">
        <input type="text" id="productSearch" class="form-control w-50" placeholder="Search Products">
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="text-center alert">
                            No products available.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $index++; ?></td>
                            <td>
                                <img src="uploads/storages/images/<?php echo $product['image_url']; ?>" class="img-thumbnail" style="max-height: 50px;">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</td>
                            <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['stock']); ?></td>
                            <td>
                                <button class="btn btn-info btn-sm view-product" data-bs-toggle="modal" data-bs-target="#viewProductModal" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editProductModal" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="index.php?route=product&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" name="name" id="productName" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productPrice" class="form-label">Price</label>
                                <input type="number" name="price" id="productPrice" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productDescription" class="form-label">Description</label>
                                <textarea name="description" id="productDescription" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productStock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="productStock" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productCategory" class="form-label">Category</label>
                                <select name="category" id="productCategory" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productImage" class="form-label">Product Image</label>
                                <input type="file" name="image_url" id="productImage" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="edit">
                        <input type="hidden" name="id" id="editProductId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editProductName" class="form-label">Product Name</label>
                                <input type="text" name="name" id="editProductName" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProductPrice" class="form-label">Price</label>
                                <input type="number" name="price" id="editProductPrice" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProductDescription" class="form-label">Description</label>
                                <textarea name="description" id="editProductDescription" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProductStock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="editProductStock" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProductCategory" class="form-label">Category</label>
                                <select name="category" id="editProductCategory" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProductImage" class="form-label">Product Image</label>
                                <input type="file" name="image_url" id="editProductImage" class="form-control" accept="image/*">
                                <img src="" alt="Product Image" class="img-thumbnail mt-2" id="editProductImagePreview" style="max-width: 100%; max-height: 150px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="productDetailsContent">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const product = <?php echo json_encode($products); ?>.find(p => p.id == id);

                document.getElementById("editProductId").value = id;
                document.getElementById("editProductName").value = product.name;
                document.getElementById("editProductDescription").value = product.description;
                document.getElementById("editProductPrice").value = product.price;
                document.getElementById("editProductStock").value = product.stock;
                document.getElementById("editProductImagePreview").src = "uploads/storages/images/" + product.image_url;
            });
        });

        document.querySelectorAll(".view-product").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const product = <?php echo json_encode($products); ?>.find(p => p.id == id);

                const content = `
                    <h4>${product.name}</h4>
                    <p><strong>Price:</strong> $${product.price}</p>
                    <p><strong>Stock:</strong> ${product.stock}</p>
                    <p><strong>Description:</strong> ${product.description}</p>
                    <img src="uploads/storages/images/${product.image_url}" alt="${product.name}" class="img-fluid">
                `;
                document.getElementById("productDetailsContent").innerHTML = content;
            });
        });

        document.getElementById("productSearch").addEventListener("keyup", function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const name = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                const description = row.querySelector("td:nth-child(4)").textContent.toLowerCase();

                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>

<?php include "./layouts/footer.php"; ?>