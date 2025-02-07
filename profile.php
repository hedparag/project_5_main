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

$query = "SELECT 
            e.employee_id, 
            e.employee_name, 
            e.employee_email, 
            e.employee_phone, 
            e.salary, 
            e.profile_image, 
            e.employee_details, 
            e.employee_skils, 
            e.dob, 
            e.created_at, 
            e.updated_at,
            e.status, 
            d.department_name, 
            d.department_abbr, 
            p.position_name, 
            p.position_abbr, 
            u_t.user_type, 
            u.last_login_time 
        FROM employees e
        JOIN departments d ON e.department_id = d.department_id 
        JOIN positions p ON e.position_id = p.position_id 
        JOIN user_types u_t ON e.user_type_id = u_t.user_type_id
        LEFT JOIN users u ON e.employee_id = u.employee_id
        WHERE u.username = '" . $_SESSION["username"] . "' or e.employee_name = '" . $_SESSION["username"] . "'";

$result = pg_query($con, $query);
$employee = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
                            <a class="nav-link active" href="profile.php">My Profile</a>
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
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Employee Profile</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Profile Image -->
                    <div class="col-md-4 text-center">
                        <img src="<?= !empty($employee['profile_image']) ? 'uploads/' . $employee['profile_image'] : 'images/default_profile.png' ?>" 
                            class="img-fluid rounded-circle border border-secondary" 
                            alt="Profile Image" width="200" height="200">
                    </div>

                    <!-- Employee Details -->
                    <div class="col-md-8">
                        <h4 class="text-primary">Personal Information</h4>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($employee['employee_name']) ?></li>
                            <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($employee['employee_email']) ?></li>
                            <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($employee['employee_phone']) ?></li>
                            <li class="list-group-item"><strong>Date of Birth:</strong> <?= htmlspecialchars($employee['dob']) ?></li>
                            <li class="list-group-item"><strong>Status:</strong> <?= ($employee['status'] == 'f') ? 'Inactive' : 'Active'; ?></li>
                        </ul>
                    </div>
                </div>

                <hr>

                <!-- Job Details -->
                <h4 class="text-primary mt-3">Job Information</h4>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Department:</strong> <?= htmlspecialchars($employee['department_name']) ?> (<?= htmlspecialchars($employee['department_abbr']) ?>)</li>
                    <li class="list-group-item"><strong>Position:</strong> <?= htmlspecialchars($employee['position_name']) ?> (<?= htmlspecialchars($employee['position_abbr']) ?>)</li>
                    <li class="list-group-item"><strong>User Type:</strong> <?= htmlspecialchars($employee['user_type']) ?></li>
                    <li class="list-group-item"><strong>Salary:</strong> $<?= number_format($employee['salary'], 2) ?></li>
                </ul>

                <hr>

                <!-- Additional Details -->
                <h4 class="text-primary mt-3">Additional Details</h4>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Employee Skills:</strong> <?= htmlspecialchars($employee['employee_skils']) ?></li>
                    <li class="list-group-item"><strong>Profile Details:</strong> <?= htmlspecialchars($employee['employee_details']) ?></li>
                    <li class="list-group-item"><strong>Account Created:</strong> <?= htmlspecialchars($employee['created_at']) ?></li>
                    <li class="list-group-item"><strong>Last Updated:</strong> <?= htmlspecialchars($employee['updated_at']) ?></li>
                    <li class="list-group-item"><strong>Last Login:</strong> <?= htmlspecialchars($employee['last_login_time']) ?></li>
                </ul>

                <div class="text-center mt-4">
                    <a href="edit_profile.php?employee_id=<?php echo $employee['employee_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_profile.php?employee_id=<?php echo $employee['employee_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                    <?php if ($employee['status'] == 't'): ?>
                        <a href="new_password.php?employee_id=<?php echo $employee['employee_id']; ?>" class="btn btn-info">New Password</a>
                        <a href="password_change.php?employee_id=<?php echo $employee['employee_id']; ?>" class="btn btn-secondary">Change Password</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-dark">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <script src="js/bootstrap.bundle.min.js" async defer></script>

    </body>
</html>