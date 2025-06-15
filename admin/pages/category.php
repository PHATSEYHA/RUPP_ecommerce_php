<?php

require './configs/connection.php';
$page_title = "Categories";

if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    Connection::insert('categories', ['name' => $category_name]);
    header("Location: index.php?route=category");
    exit;
}

if (isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    Connection::update('categories', ['name' => $category_name], ['id' => $category_id]);
    header("Location: index.php?route=category");
    exit;
}

if (isset($_GET['delete_id'])) {
    $category_id = $_GET['delete_id'];

    try {
        Connection::delete('categories', ['id' => $category_id]);
        header("Location: index.php?route=category");
        exit;
    } catch (Exception $e) {
        error_log("Delete failed: " . $e->getMessage());
        header("Location: index.php?route=category&error=" . urlencode($e->getMessage()));
        exit;
    }
}

$categories = Connection::getAll('categories');
$index = 1;

include "./layouts/head.php";
?>

<div class="container my-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="text-center fs-3">Manage Categories</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add New Category</button>
    </div>

    <div class="mb-5 w-100 d-flex justify-content-center">
        <input type="text" id="categorySearch" class="form-control w-50" placeholder="Search Categories">
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Name</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td class="text-center"><?php echo $index++; ?></td>
                <td class="text-center"><?php echo htmlspecialchars($category['name']); ?></td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                        data-id="<?php echo $category['id']; ?>" data-name="<?php echo htmlspecialchars($category['name']); ?>">
                        Edit
                    </button>
                    <a href="index.php?route=category&delete_id=<?php echo $category['id']; ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?route=category" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" id="category_name" name="category_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?route=category" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="edit_category_id">
                    <div class="form-group">
                        <label for="edit_category_name">Category Name</label>
                        <input type="text" id="edit_category_name" name="category_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_category" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set values in the edit modal
document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('edit_category_id').value = button.dataset.id;
        document.getElementById('edit_category_name').value = button.dataset.name;
    });
});

// Search functionality for categories (by name only)
document.getElementById('categorySearch').addEventListener('keyup', function() {
    var searchTerm = this.value.toLowerCase();
    var rows = document.querySelectorAll('tbody tr');

    rows.forEach(function(row) {
        var name = row.querySelector('td:nth-child(2)').textContent.toLowerCase(); // Search by Name column

        if (name.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
