<?php
// session_start();

require './configs/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: index.php?route=register');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email format.';
        header('Location: index.php?route=register');
        exit();
    }

    $existingUser = Connection::getOne('users', ['email = :email'], ['email' => $email]);
    if ($existingUser) {
        $_SESSION['error'] = 'Email already registered.';
        header('Location: index.php?route=register');
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $userData = [
        'username' => $name,
        'email' => $email,
        'password' => $hashedPassword,
    ];

    $result = Connection::insert('users', $userData);

    if ($result) {
        $_SESSION['success'] = 'Registration successful! You can now login.';
        header('Location: index.php?route=login');
        exit();
    } else {
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: register.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Admin</title>

    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link id="pagestyle" href="./assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Register</h3>

                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                                            unset($_SESSION['error']); ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success'];
                                unset($_SESSION['success']); ?>
                            </div>
                        <?php } ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn w-100 text-white" style="background-color: teal;">Register</button>
                            <p class="text-center mt-3">Already have an account? <a
                                    href="index.php?route=login" class="text-success">Login</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>