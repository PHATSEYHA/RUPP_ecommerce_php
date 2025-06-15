<?php
require "./configs/connection.php";  


if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}


$users = Connection::getAll('users', [], ['select' => 'id, username, email']); 


if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    if ($user_id == $_SESSION['user_id']) {
        die("Error: You cannot delete your own account.");
    }

    Connection::delete('users', ['id' => $user_id]);

    echo "User deleted successfully!";
    header("Location: users.php"); 
    exit();
}


if (isset($_POST['edit'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];


    Connection::update('users', [
        'username' => $username,
        'email' => $email
    ], ['id' => $user_id]);

    echo "User details updated successfully!";
    header("Location: index.php?route=users"); 
    exit();
}

$index = 1; // Index for numbering users
$page_title = "User Management";
include "./layouts/head.php"; 
?>
<div class="container mt-5">
    <h3>User Management</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $index++ ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td> <!-- Changed from 'name' to 'username' -->
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <!-- Edit User Button - Triggers Modal -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editModal-<?= $user['id'] ?>">Edit</button>

                    <!-- Delete User Button -->
                    <a href="users.php?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Template (for editing user details) -->
<?php foreach ($users as $user): ?>
<div class="modal fade" id="editModal-<?= $user['id'] ?>" tabindex="-1"
    aria-labelledby="editModalLabel-<?= $user['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-<?= $user['id'] ?>">Edit User #<?= $user['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit User Form -->
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="<?= htmlspecialchars($user['username']) ?>" required>
                        <!-- Changed from 'name' to 'username' -->
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" name="edit" value="Update User">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include "./layouts/footer.php"; ?>