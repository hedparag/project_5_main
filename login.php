<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './templates/meta-info.php'; ?>
    <title>Login | EMS</title>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <?php include "./templates/header.php";
    include "./include/config.php";
    session_start();
    if (isset($_SESSION['employee_id'])) {
        header("Location: profile.php"); // Redirect to dashboard or another page
        exit();
    }
    if (isset($_POST["submit"])) {     
        $email = "";
        $employee_email = "";
        global $row;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Prepare and execute query securely
            $query = "SELECT e.employee_name, e.employee_id, e.employee_email, u.user_type FROM employees e JOIN user_types u ON e.user_type_id = u.user_type_id WHERE e.employee_name = '$email';";
            $result1 = pg_query($con, $query);

            if ($result1 && pg_num_rows($result1) == 1) {
                $row = pg_fetch_assoc($result1); 
                var_dump($row);  // Check if data is retrieved correctly           
                $employee_email = $row['employee_email'];

                // Verify password
                if ($password === $employee_email) {
                    $_SESSION['employee_email'] = $employee_email;
                    $_SESSION['employee_id'] = $row['employee_id'];
                    $_SESSION['employee_name'] = $row['employee_name'];
                    
                    // Update last login time
                    $update_query = "UPDATE employees SET updated_at = NOW() WHERE employee_id = $employee_id;";
                    $result1 = pg_query($con, $update_query);
                    
                    if($row['user_type']==="Admin"){
                        header("Location: dashboard.php");
                        exit();
                    }
                    else{
                        header("Location: profile.php");
                        exit();
                    }
                } else {
                    $error_msg = "Incorrect Password.";
                }
            } else {
                $error_msg = "Incorrect Username/Password.";
            }


            $query = "SELECT u.*, e.employee_email, e.employee_name, e.department_id, e.position_id, ut.user_type
            FROM users u
            JOIN employees e ON u.employee_id = e.employee_id
            JOIN user_types ut ON u.user_type_id = ut.user_type_id
            WHERE e.employee_email = '$email';";

            $result = pg_query($con, $query);

            if ($result && pg_num_rows($result) > 0) {
                $user = pg_fetch_assoc($result);
                if (password_verify($password, $user["password"])) {
                    $equery = "SELECT * FROM employees WHERE employee_email = $1";
                    $eresult = pg_query_params($con, $equery, [$email]);

                    if ($eresult && pg_num_rows($eresult) > 0) {
                        $employee = pg_fetch_assoc($eresult);
                        $_SESSION["employee_name"] = $employee["employee_name"];
                        $_SESSION["employee_id"] = $employee["employee_id"];
                        $_SESSION["user_type_id"] = $employee["user_type_id"];
                        $_SESSION["employee_email"] = $employee["employee_email"];
                        $_SESSION["profile_image"] = $employee["profile_image"];
                        

                        $dquery = "SELECT department_name FROM departments WHERE department_id = " . $employee['department_id'];
                        $dresult = pg_query($con, $dquery);
                        $department = pg_fetch_assoc($dresult);
                        if ($dresult && pg_num_rows($dresult) > 0) {
                            $department = pg_fetch_assoc($dresult);
                            $_SESSION["department_name"] = $department["department_name"];
                        }

                        $pquery = "SELECT position_name FROM positions WHERE position_id = " . $employee['position_id'];
                        $presult = pg_query($con, $pquery);
                        if ($presult && pg_num_rows($presult) > 0) {
                            $position = pg_fetch_assoc($presult);
                            $_SESSION["position_name"] = $position["position_name"];
                        }

                        if($user['user_type']==="Admin"){
                            header("Location: dashboard.php");
                            exit();
                        }
                        else{
                            header("Location: profile.php");
                            exit();
                        }
                    } else {
                        $login_error = '❌ Employee details not found!';
                    }
                } else {
                    $login_error = '❌ Incorrect password!';
                }
            } else {
                $login_error = '❌ User not found!';
            }

            pg_close($con);
        }
    }
    ?>

    <div class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h1 class="text-center mb-4">Welcome Back</h1>
                    <?php if (!empty($error_msg)) { echo "<p class='error'>$error_msg</p>"; } ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="text" class="form-label">Email address</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <?php if (isset($login_error)) { ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $login_error; ?>
                            </div>
                        <?php } ?>
                        <button type="submit" name="submit" id="submit" value="Login" class="btn btn-primary w-100">Sign In</button>
                    </form>
                    <p class="text-center mt-3">
                        Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include "./templates/footer.php"; ?>
    <script src="js/bootstrap.bundle.min.js" async defer></script>
</body>
</html>
