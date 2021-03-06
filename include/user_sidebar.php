<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-2">
    <!-- Brand Logo -->
    <a href="./include/index3.html" class="brand-link">
        <!--        <img src="./include/dist/img/logo.png"-->
        <!--             alt="Logo"-->
        <!--             class="brand-image img-circle elevation-3"-->
        <!--             style="opacity: .8">-->
        <!--        -->
        <div class="text-center
                brand-text font-weight-light ">
            Grievance Portal
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="./include/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION['name'] ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./include/index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./include/index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./include/index3.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>
                    </ul>
                </li>
                 -->
                <li class="nav-item">
                    <a href="home.php" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="griev.php" class="nav-link">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            View Grievance
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="griev.php?status=offline" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Offline Grievance
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="griev.php?status=cancelled" class="nav-link">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>
                            Cancelled Grievance
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="nav-icon fas fa-arrow-left"></i>
                        <p>
                            Log Out
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
