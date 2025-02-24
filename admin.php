<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Dashboard</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <!-- Brand Logo -->
                <a class="navbar-brand" href="home.html">
                    <img src="images/company_logo.png" alt="Logo" width="200" height="40">
                </a>

                <!-- Toggler Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Registration</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="admin.php">Admin</a>
                        </li>  
                    </ul>
                    <!-- Search Bar & Auth Buttons -->
                    <form class="d-flex me-2" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
                    </form>
                    <a href="login.html" class="btn btn-light text-dark me-2">Login</a>
                    <a href="register.html" class="btn btn-primary">Sign-up</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="d-flex justify-content-center">
        <div class="w-50">
            <?php
                include("include/config.php");
                session_start();
                
                if (!isset($_SESSION["username"])) {
                    header("Location: login.php");
                    exit();
                }
                
                // Display success or error messages
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            ' . $_SESSION['message'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    unset($_SESSION['message']); // Remove message after displaying
                }

                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            ' . $_SESSION['error'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    unset($_SESSION['error']); // Remove error after displaying
                }
                        
                $query1 = "SELECT d.department_id, d.department_name, d.department_abbr, d.status FROM departments d;";
                $result1 = pg_query($con, $query1);

                $query2 = "SELECT p.position_id, p.position_name, p.position_abbr, p.status FROM positions p;";
                $result2 = pg_query($con, $query2);
            ?>
        </div>
    </div>
    
    <div class="container mt-4">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <h3>Department Dashboard</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Department ID</th>
                    <th>Department Name</th>
                    <th>Department Abbrebiation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = pg_fetch_assoc($result1)) { ?>
                    <tr>
                        <td><?php echo $row['department_id']; ?></td>
                        <td><?php echo $row['department_name']; ?></td>
                        <td><?php echo $row['department_abbr']; ?></td>
                        <td><?php echo ($row['status'] == 'f') ? 'Inactive' : 'Active'; ?></td>
                        <td>
                            <a href="status_active_dept.php?employee_id=<?php echo $row['department_id']; ?>" class="btn btn-info btn-sm" onclick="return confirm('Are you sure you want to change the status?');">Status Active</a>
                            <a href="status_deactive_dept.php?employee_id=<?php echo $row['department_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to change the status?');">Status Deactive</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Positions Dashboard</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Positions ID</th>
                    <th>Positions Name</th>
                    <th>Positions Abbrebiation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = pg_fetch_assoc($result2)) { ?>
                    <tr>
                        <td><?php echo $row['position_id']; ?></td>
                        <td><?php echo $row['position_name']; ?></td>
                        <td><?php echo $row['position_abbr']; ?></td>
                        <td><?php echo ($row['status'] == 'f') ? 'Inactive' : 'Active'; ?></td>
                        <td>
                            <a href="status_active_dept.php?employee_id=<?php echo $row['position_id']; ?>" class="btn btn-info btn-sm" onclick="return confirm('Are you sure you want to change the status?');">Status Active</a>
                            <a href="status_deactive_dept.php?employee_id=<?php echo $row['position_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to change the status?');">Status Deactive</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><a href="logout.php" class="btn btn-secondary">Logout</a></p>
    </div>

    <script src="js/bootstrap.bundle.min.js" async defer></script>
    </body>
</html>