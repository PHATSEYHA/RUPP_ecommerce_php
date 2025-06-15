<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl mt-3" style="background-color: #ddd;" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <button class="navbar-toggler d-md-none text-dark" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-dark"></span>
        </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="">Pages</a>
                </li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                    <?php echo isset($page_title) ? htmlspecialchars($page_title) : "Dashboard"; ?>
                </li>
            </ol>
            <h6 class="font-weight-bolder mb-0">
                <?php echo isset($page_title) ? htmlspecialchars($page_title) : "Dashboard"; ?>
            </h6>
        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <!-- Right Side Links -->
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li class="nav-item d-flex align-items-center">
                        <a href="index.php?route=profile" class="nav-link text-body font-weight-bold px-0">
                            <i class="fa fa-user p-2 text-white rounded-circle" style="background-color: teal;"></i>
                            <span class="d-sm-inline d-none fs-6 fw-bold"> <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center ms-4">
                        <a href="index.php?route=logout" class="nav-link text-body font-weight-bold bg-gradient-danger p-3 rounded">
                            <i class="fa fa-sign-out-alt me-sm-1 text-white"></i>
                            <span class="d-sm-inline d-none text-white">Logout</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbarToggler = document.querySelector(".navbar-toggler");
        const navbarCollapse = document.getElementById("navbar");

        navbarToggler.addEventListener("click", function() {
            navbarCollapse.classList.toggle("show");
        });
    });
</script>