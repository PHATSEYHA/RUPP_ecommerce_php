<?php
require "./configs/connection.php";

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

$user = Connection::getOne('users', ['id' => $user_id]);

if (!$user) {
    die("Error: User not found.");
}

if (isset($_POST['update_profile'])) {
    $username = $_POST['name'];
    $email = $_POST['email'];

    if (empty($username) || empty($email)) {
        $error = "Username and Email cannot be empty!";
    } else {
        Connection::update('users', [
            'username' => $username,
            'email' => $email
        ], ['id' => $user_id]);
        $_SESSION['name'] = $username;

        $success = "Profile updated successfully!";
    }
}

if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All password fields are required!";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirmation do not match!";
    } else {
        if (!password_verify($current_password, $user['password'])) {
            $error = "Current password is incorrect!";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            Connection::update('users', [
                'password' => $hashed_password
            ], ['id' => $user_id]);

            $success = "Password updated successfully!";
        }
    }
}

$page_title = "Settings";
include "./layouts/head.php";
?>

<div class="container mt-5">
    <h1>Settings</h1>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?>
    </div>
    <div class="mb-3">
        <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
    </div>

    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
        Edit Profile
    </button>

    <button type="button" class="btn btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
        Change Password
    </button> -->

    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="name" name="name" <!-- Keep 'name' for form
                                field name but use 'username' in DB -->
                            value="<?= htmlspecialchars($user['username']) ?>" required>

                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Password Change Form inside the modal -->
                    <form method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                required>
                        </div>

                        <button type="submit" class="btn btn-warning" name="update_password">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <a href="index.php?route=logout" class="btn btn-danger mt-3">Logout</a> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php include "./layouts/footer.php"; ?>