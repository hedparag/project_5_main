<!DOCTYPE html>
    <head>
        <?php include './templates/meta-info.php'; ?>
        <title>New Password</title>
    </head>

    <body>
    <?php
    include './templates/header.php';
    echo $navbarUserScr;
    include "include/config.php";
    session_start();

    if (!isset($_GET['id'])) {
        $_SESSION['error'] = "Employee ID is required. GET[id] Value: ".print_r($_GET['id']);
        header("Location: dashboard.php");
        exit();
    }
          
    $invalid = 0;
    if(md5($_GET['id'].'abcd') != $_GET['hash']){
        $invalid = 1;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $employee_id = $_GET['id'] ?? null;

        // 🔹 Check if password is already set for this employee_id
        $check_query = "SELECT password FROM users WHERE employee_id = '$employee_id'";
        $check_result = pg_query($con, $check_query);
        if ($check_result) {
            $row = pg_fetch_assoc($check_result);
            if (!empty($row['password'])) {
                echo "<div class='container'>
                    <div class='form' style='text-align:center; padding:20px; background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
                        <h3>Error: Password is already set for this Employee ID. Cannot register again.</h3>
                        <p><a href='logout.php' style='color:#155724; font-weight:bold;'>Login Here</a></p>
                    </div>
                </div>";
                exit;
            }
        }

        $check_query = "SELECT user_type_id FROM employees WHERE employee_id = '$employee_id'";
        $check_result = pg_query($con, $check_query);
        $row = pg_fetch_assoc($check_result);
        
        // Get form data
        $full_name = pg_escape_string($con, $_POST['full_name']);
        $username = pg_escape_string($con, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $user_type_id = $row['user_type_id'];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        // Insert user into the database
        $query = "INSERT INTO users (employee_id, user_type_id, full_name, username, password, created_at, updated_at, status) 
                VALUES ('$employee_id', '$user_type_id', '$full_name', '$username', '$password', '$created_at', '$updated_at', 't')";

        $result = pg_query($con, $query);

        if ($result) {
            echo "<div class='container'>
                <div class='form' style='text-align:center; padding:20px; background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;'>
                    <h3>User Registered Successfully!</h3><br>
                    <p><a href='logout.php' style='color:#155724; font-weight:bold;'>Login Here</a></p>
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

?>
    <div class="container">
    <?php if($invalid == 0) { ?>
        <form class="form" id="registrationForm" action="new_password.php?id=<?php echo $_GET['id']; ?>&hash=<?php echo $_GET['hash']; ?>" method="POST">
            <h1 class="login-title">User Registration</h1>
            <input type="text" class="login-input" name="full_name" id="full_name" placeholder="Full Name" required />
            <input type="text" class="login-input" name="username" id="username" placeholder="Username" required />
            <input type="password" class="login-input" name="password" id="password" placeholder="Password" required />
            <br>
            <input type="submit" name="submit" value="Register" class="login-button">
            <p class="link">Already Have an Account? <a href="logout.php">Login Here</a></p>
        </form>
    <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            INVALID USER ID.
        </div>
    <?php } ?>
    </div>

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