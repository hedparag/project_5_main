<!DOCTYPE html>
<html>
    <head>
        <title>Password Change</title>
        <?php include './templates/meta-info.php'; ?>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST['submit'])) {
        $employee_id = isset($_GET['id']) ? $_GET['id'] : null; // Retrieve employee_id from session
        // Get form data
        $old_password = $_POST['old_password']; 
        $new_password = $_POST['new_password']; 
        // 🔹 Check if password is already set for this employee_id
        $check_query = "SELECT password FROM users WHERE employee_id = '$employee_id'";
        $check_result = pg_query($con, $check_query);
        if ($check_result) {
            $row = pg_fetch_assoc($check_result);
            if ($row && password_verify($old_password, $row['password'])){
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update the password in the database
                $update_query = "UPDATE users SET password = '$hashed_password', updated_at = NOW() WHERE employee_id = '$employee_id'";
                $update_result = pg_query($con, $update_query);

                if ($update_result) {
                    echo "<div class='container'>
                        <div class='form' style='text-align:center; padding:20px; background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;'>
                            <h3>Password Updated Successfully!</h3>
                            <p><a href='profile.php' style='color:#155724; font-weight:bold;'>Go to Profile</a></p>
                        </div>
                    </div>";
                } else {
                    echo "<div class='container'>
                        <div class='form' style='text-align:center; padding:20px; background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
                            <h3>Error Updating Password. Please Try Again.</h3>
                            <p>Error: " . pg_last_error($con) . "</p>
                        </div>
                    </div>";
                }
                exit;
            }
            else{
                echo "<div class='container'>
                    <div class='form' style='text-align:center; padding:20px; background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px;'>
                        <h3>Error: Given Password is Wrong Employee ID. Cannot Change Password.</h3>
                        <p><a href='logout.php' style='color:#155724; font-weight:bold;'>Login Here</a></p>
                    </div>
                </div>";
            }
        }   
    }
    ?>

    <div class="container">
    <?php if($invalid == 0) { ?>
        <form class="form" id="registrationForm" action="password_change.php?id=<?php echo $_GET['id']; ?>&hash=<?php echo $_GET['hash']; ?>" method="POST">
            <h1 class="login-title">User Registration</h1>
            <input type="password" class="login-input" name="old_password" id="old_password" placeholder="Old Password" required />
            <input type="password" class="login-input" name="new_password" id="new_password" placeholder="New Password" required />
            <input type="submit" name="submit" value="Register" class="login-button">
            <p class="link">Already Have an Account? <a href="login.php">Login Here</a></p>
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