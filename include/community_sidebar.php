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
        <div class="text-center text-black
                brand-text ">
            <?php echo $_SESSION['name'] ?>
        </div>

        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library-->

                <li class="nav-item">
                    <a href="comm.php" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Grievances
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="comm.php?status=pending" class="nav-link ">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Pending</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="comm.php?status=in_progress" class="nav-link">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>In Progress</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="comm.php?status=forwards" class="nav-link">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Forwards</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="comm.php?status=forwarded" class="nav-link">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Forwarded</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="comm.php?status=partially_solved" class="nav-link">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Partially Solved</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="comm.php?status=resolved" class="nav-link">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Resolved</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                    <a href="griev.php?status=offline" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Offline Grievance
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="filtersummarycomm.php" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Filter Grievance
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="commsummary.php" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Generate Summary
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="offlinereport.php" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Manual Entry
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
