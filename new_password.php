<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Registration</title>
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
                            <a class="nav-link active" href="register.php">Registration</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
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
        <form class="form" id="registrationForm" action="" method="post">
            <h1 class="login-title">User Registration</h1>
            <input type="text" class="login-input" name="full_name" id="full_name" placeholder="Full Name" required />
            <input type="text" class="login-input" name="username" id="username" placeholder="Username" required />
            <input type="password" class="login-input" name="password" id="password" placeholder="Password" required />
            <input type="number" class="login-input" name="user_type_id" id="user_type_id" placeholder="User Type ID" required />
            <select class="login-input" name="status" id="status">
                <option value="true">Active</option>
                <option value="false">Inactive</option>
            </select>
            <input type="submit" name="submit" value="Register" class="login-button">
            <p class="link">Already Have an Account? <a href="login.php">Login Here</a></p>
        </form>
    </div>


    <?php
        include("include/config.php");
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Get employee_id from session
            if (!isset($_GET['employee_id'])) {
                echo "<div class='container'>
                    <div class='form' style='text-align:center; padding:20px; background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
                        <h3>Session expired. Please log in again. ".($_GET['employee_id'])."</h3>
                    </div>
                </div>";
                exit;
            }
            $employee_id = $_GET['employee_id'];

            // Get form data
            $full_name = pg_escape_string($con, $_POST['full_name']);
            $username = pg_escape_string($con, $_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
            $user_type_id = intval($_POST['user_type_id']);
            $status = $_POST['status'] == 'true' ? 't' : 'f';
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            // Insert user into the database
            $query = "INSERT INTO users (employee_id, user_type_id, full_name, username, password, created_at, updated_at, status) 
                    VALUES ('$employee_id', '$user_type_id', '$full_name', '$username', '$password', '$created_at', '$updated_at', '$status')";

            $result = pg_query($con, $query);

            if ($result) {
                echo "<div class='container'>
                    <div class='form' style='text-align:center; padding:20px; background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;'>
                        <h3>User Registered Successfully!</h3><br>
                        <p><a href='login.php' style='color:#155724; font-weight:bold;'>Login Here</a></p>
                    </div>
                </div>";
            } else {
                echo "<div class='container'>
                    <div class='form' style='text-align:center; padding:20px; background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
                        <h3>Error in Registration. Please Try Again.</h3>
                    </div>
                </div>";
            }
        }

        pg_close($con);
    ?>

    <script>
        $(document).ready(function () {
            $("#registrationForm").submit(function (event) {
                // Clear previous errors
                $(".error").remove();

                let isValid = true;

                // Validate full name (non-empty)
                var fullName = $("#full_name").val();
                if (fullName.trim() === "") {
                    $("#full_name").after('<span class="error">Full name is required.</span>');
                    isValid = false;
                }

                // Validate username (non-empty and alphanumeric, minimum 3 characters)
                var username = $("#username").val();
                var usernamePattern = /^[a-zA-Z0-9._-]{3,50}$/;
                if (username.trim() === "" || !usernamePattern.test(username)) {
                    $("#username").after('<span class="error">Username must be 3-50 characters and can contain letters, numbers, dots, underscores, or hyphens.</span>');
                    isValid = false;
                }

                // Validate password (minimum 8 characters with at least one uppercase, one lowercase, one number, and one special character)
                var password = $("#password").val();
                var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
                if (!passwordPattern.test(password)) {
                    $("#password").after('<span class="error">Password must be at least 8 characters, include one uppercase letter, one lowercase letter, one number, and one special character.</span>');
                    isValid = false;
                }

                // Validate user type ID (must be a positive integer)
                var userTypeID = $("#user_type_id").val();
                if (isNaN(userTypeID) || userTypeID <= 0) {
                    $("#user_type_id").after('<span class="error">User Type ID must be a positive number.</span>');
                    isValid = false;
                }

                // Validate status (should not be empty and must be either true or false)
                var status = $("#status").val();
                if (status !== "true" && status !== "false") {
                    $("#status").after('<span class="error">Status must be selected as either Active or Inactive.</span>');
                    isValid = false;
                }

                // If any validation fails, prevent form submission
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>    
    
    <script src="js/bootstrap.bundle.min.js" async defer></script>
    </body>
</html>