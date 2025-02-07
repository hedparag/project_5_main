<?php
include("include/config.php");
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $query = "SELECT * FROM employees WHERE employee_id = $employee_id";
    $result = pg_query($con, $query);
    $row = pg_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_name = $_POST['employee_name'];
    $employee_email = $_POST['employee_email'];
    $employee_phone = $_POST['employee_phone'];
    $salary = $_POST['salary'];

    $psql = "UPDATE users SET employee_name = '$employee_name', employee_email = '$employee_email', employee_phone = '$employee_phone', salary = $salary WHERE employee_id = $employee_id;";
    $result1 = pg_query($con, $psql);
    $query = "UPDATE employees SET employee_name = '$employee_name', employee_email = '$employee_email', employee_phone = '$employee_phone', salary = $salary WHERE employee_id = $employee_id;";
    $result2 = pg_query($con, $query);

    if ($result2) {
        $_SESSION['message'] = "Employee updated successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating employee.";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Edit Employee</title>
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

    <form method="post" class="container mt-4 p-4 border rounded shadow bg-light">
        <h2 class="mb-3 text-center">Edit Employee</h2>
        
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="employee_name" class="form-control" value="<?php echo $row['employee_name']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="employee_email" class="form-control" value="<?php echo $row['employee_email']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" name="employee_phone" class="form-control" value="<?php echo $row['employee_phone']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Salary:</label>
            <input type="text" name="salary" class="form-control" value="<?php echo $row['salary']; ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>


    <script src="js/bootstrap.bundle.min.js" async defer></script>
    </body>
</html>
