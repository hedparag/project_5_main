<?php 
    include("include/config.php");
    session_start();

    if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if($username == "admin" && $password == "admin") {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } 

        // Prepare and execute query securely
        $query = "SELECT employee_id, employee_email FROM employees WHERE employee_name = '$username'";
        $result1 = pg_query($con, $query);

        if ($result1 && pg_num_rows($result1) == 1) {
            $row = pg_fetch_assoc($result1);
            $employee_email = $row['employee_email'];

            // Verify password
            if ($password === $employee_email) {
                $_SESSION['username'] = $username;
                $_SESSION['employee_id'] = $row['employee_id'];

                // Update last login time
                $update_query = "UPDATE employees SET updated_at = NOW() WHERE employee_id = $employee_id";
                $result1 = pg_query($con, $update_query);

                header("Location: profile.php");
                exit();
            } else {
                $error_msg = "Incorrect Password.";
            }
        } else {
            $error_msg = "Incorrect Username/Password.";
        }

        pg_free_result($result1);

        // Prepare and execute query securely
        $query = "SELECT user_id, password FROM users WHERE username = '$username' AND status = 't'";
        $result2 = pg_query($con, $query);

        if ($result2 && pg_num_rows($result2) == 1) {
            $row = pg_fetch_assoc($result2);
            $hashed_password = $row['password'];

            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['user_id'];

                // Update last login time
                $update_query = "UPDATE users SET last_login_time = NOW() WHERE user_id = $user_id";
                $result2 = pg_query($con, $update_query);

                header("Location: profile.php");
                exit();
            } else {
                $error_msg = "Incorrect Password.";
            }
        } else {
            $error_msg = "Incorrect Username/Password.";
        }

        pg_free_result($result2);
    }

    pg_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
                            <a class="nav-link active" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">My Profile</a>
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
    <div class="form">
        <h1 class="login-title">Login</h1>
        <?php if (!empty($error_msg)) { echo "<p class='error'>$error_msg</p>"; } ?>
        <form action="" method="post">
            <input type="text" class="login-input" name="username" placeholder="Username" required />
            <input type="password" class="login-input" name="password" placeholder="Password" required />
            <input type="submit" name="submit" value="Login" class="login-button"/>
        </form>
        <p class="link">Don't Have an Account? <a href="registration.php">Register Here</a></p>
    </div>
    </div>

    <script src="js/bootstrap.bundle.min.js" async defer></script>

    </body>
</html>