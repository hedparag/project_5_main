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
                            <a class="nav-link active" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Admin</a>
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
                        
                $query = "SELECT e.employee_id, e.employee_name, e.employee_email, e.employee_phone, e.salary, e.profile_image, e.employee_details, e.employee_skils, e.dob, e.status, d.department_name, p.position_name, u_t.user_type FROM employees e 
                        JOIN departments d ON e.department_id = d.department_id 
                        JOIN positions p ON e.position_id = p.position_id 
                        JOIN user_types u_t ON e.user_type_id = u_t.user_type_id";
                $result = pg_query($con, $query);
            ?>
        </div>
    </div>
    
    <div class="container mt-4">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <h3>Employee Dashboard</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Salary</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>User Type</th>
                    <th>Skills</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Profile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = pg_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['employee_id']; ?></td>
                        <td><?php echo $row['employee_name']; ?></td>
                        <td><?php echo $row['employee_email']; ?></td>
                        <td><?php echo $row['employee_phone']; ?></td>
                        <td><?php echo $row['salary']; ?></td>
                        <td><?php echo $row['department_name']; ?></td>
                        <td><?php echo $row['position_name']; ?></td>
                        <td><?php echo $row['user_type']; ?></td>
                        <td><?php echo $row['employee_skils']; ?></td>
                        <td><?php echo $row['dob']; ?></td>
                        <td><?php echo ($row['status'] == 'f') ? 'Inactive' : 'Active'; ?></td>
                        <td>
                            <?php if ($row['profile_image']) { ?>
                                <img src="uploads/<?php echo $row['profile_image']; ?>" width="50" height="50">
                            <?php } else { ?>
                                No Image
                            <?php } ?>
                        </td>
                        <td>
                            <a href="edit_employee.php?employee_id=<?php echo $row['employee_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_employee.php?employee_id=<?php echo $row['employee_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                            <a href="status_active.php?employee_id=<?php echo $row['employee_id']; ?>" class="btn btn-info btn-sm" onclick="return confirm('Are you sure you want to change the status?');">Status Active</a>
                            <a href="status_deactive.php?employee_id=<?php echo $row['employee_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to change the status?');">Status Deactive</a>
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