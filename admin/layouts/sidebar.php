<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" style="background-color: teal;"
    id="sidenav-main">
    <div class="sidenav-header">
        <a class="navbar-brand m-0" href="index.php?route=dashboard">
            <h3 class="text-center text-white">Dashboard!</h3>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php echo $current_route === 'dashboard' ? 'active' : ''; ?>"
                    href="index.php?route=dashboard">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-home text-dark text-lg"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-white fs-6">Dashboard</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 text-white fs-6">Product Management</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_route === 'product' ? 'active' : ''; ?>"
                    href="index.php?route=product">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-box text-dark text-lg"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-white fs-6">Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_route === 'category' ? 'active' : ''; ?>"
                    href="index.php?route=category">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-list text-dark text-lg"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-white fs-6">Category</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 text-white fs-6">Oders Managements</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_route === 'orders' ? 'active' : ''; ?>"
                    href="index.php?route=orders">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-tools text-dark text-lg"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-white fs-6">Orders</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 text-white fs-6">Site Management</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_route === 'users' ? 'active' : ''; ?>"
                    href="index.php?route=users">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-cog text-dark text-lg"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-white fs-6">Admin/ User</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo $current_route === 'setting' ? 'active' : ''; ?>"
                    href="index.php?route=setting">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa fa-cog text-dark text-lg"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-white fs-6">Settings</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="sidenav-footer mx-3 position-absolute bottom-0 w-75" style="right: 15px;">
        <a type="button" class="btn btn-primary bg-gradient-danger mt-3 w-100" href="index.php?route=logout"><i class="fa fa-sign-out-alt me-sm-1"></i> Log out</a>
    </div>
</aside>
