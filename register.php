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
        <h1 class="login-title">Employee Registration</h1>
        <input type="text" class="login-input" name="employee_name" id="employee_name" placeholder="Employee Name" required />
        <input type="email" class="login-input" name="employee_email" id="employee_email" placeholder="Email Address" required />
        <input type="text" class="login-input" name="employee_phone" id="employee_phone" placeholder="Phone Number" required />
        <input type="number" class="login-input" name="user_type_id" id="user_type_id" placeholder="User Type ID" required />
        <input type="number" class="login-input" name="department_id" id="department_id" placeholder="Department ID" required />
        <input type="number" class="login-input" name="position_id" id="position_id" placeholder="Position ID" required />
        <input type="number" step="0.01" class="login-input" name="salary" id="salary" placeholder="Salary" required />
        <input type="text" class="login-input" name="profile_image" id="profile_image" placeholder="Profile Image URL" />
        <textarea class="login-input" name="employee_details" id="employee_details" placeholder="Employee Details"></textarea>
        <textarea class="login-input" name="employee_skills" id="employee_skills" placeholder="Employee Skills"></textarea>
        <input type="date" class="login-input" name="dob" id="dob" placeholder="Date of Birth" />
        <select class="login-input" name="status" id="status">
            <option value="true">Active</option>
            <option value="false">Inactive</option>
        </select>
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link">Already Have an Account? <a href="login.php">Login Here</a></p>
    </form>
    </div>


    <?php
    // Database connection
    include("include/config.php");

    // Handling form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $employee_name = pg_escape_string($con, $_POST['employee_name']);
        $employee_email = pg_escape_string($con, $_POST['employee_email']);
        $employee_phone = pg_escape_string($con, $_POST['employee_phone']);
        $user_type_id = intval($_POST['user_type_id']);
        $department_id = intval($_POST['department_id']);
        $position_id = intval($_POST['position_id']);
        $salary = floatval($_POST['salary']);
        $profile_image = isset($_POST['profile_image']) ? pg_escape_string($con, $_POST['profile_image']) : NULL;
        $employee_details = isset($_POST['employee_details']) ? pg_escape_string($con, $_POST['employee_details']) : NULL;
        $employee_skils = isset($_POST['employee_skills']) ? pg_escape_string($con, $_POST['employee_skills']) : NULL;
        $dob = isset($_POST['dob']) ? pg_escape_string($con, $_POST['dob']) : NULL;
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $status = $_POST['status'] == 'true' ? 't' : 'f';        

        // Prepare the SQL query with placeholders
        $query = "INSERT INTO employees (user_type_id, department_id, position_id, employee_name, employee_email, employee_phone, salary, profile_image, employee_details, employee_skils, dob, created_at, updated_at, status) 
              VALUES ('$user_type_id', '$department_id', '$position_id', '$employee_name', '$employee_email', '$employee_phone', '$salary', '$profile_image', '$employee_details', '$employee_skils', '$dob', '$created_at', '$updated_at', '$status') RETURNING employee_id";
    
        $result = pg_query($con, $query);

        if ($result) {
            echo "<div class='container'>
                <div class='form' style='text-align:center; padding:20px; background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;'>
                    <h3>Employee and User Account Registered Successfully!</h3><br>
                    <p><a href='dashboard.php' style='color:#155724; font-weight:bold;'>View Employees</a></p>
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
        $(document).ready(function() {
            $("#registrationForm").submit(function(event) {
                // Clear previous errors
                $(".error").remove();

                // Validate employee name
                var employeeName = $("#employee_name").val();
                if (employeeName.trim() === "") {
                    $("#employee_name").after('<span class="error">Employee name is required.</span>');
                    event.preventDefault();
                }

                // Validate email format
                var employeeEmail = $("#employee_email").val();
                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                if (!emailPattern.test(employeeEmail)) {
                    $("#employee_email").after('<span class="error">Please enter a valid email address.</span>');
                    event.preventDefault();
                }

                // Validate phone number (should contain only numbers and be 10 digits)
                var employeePhone = $("#employee_phone").val();
                var phonePattern = /^[0-9]{10}$/;
                if (!phonePattern.test(employeePhone)) {
                    $("#employee_phone").after('<span class="error">Please enter a valid phone number (10 digits).</span>');
                    event.preventDefault();
                }

                // Validate salary (should be a positive number)
                var salary = $("#salary").val();
                if (salary <= 0) {
                    $("#salary").after('<span class="error">Salary should be a positive number.</span>');
                    event.preventDefault();
                }

                // Validate date of birth (optional check)
                var dob = $("#dob").val();
                if (dob) {
                    var age = new Date().getFullYear() - new Date(dob).getFullYear();
                    if (age < 18) {
                        $("#dob").after('<span class="error">Employee must be at least 18 years old.</span>');
                        event.preventDefault();
                    }
                }

                // Ensure that all required fields are filled
                $("input[required], textarea[required], select[required]").each(function() {
                    if ($(this).val().trim() === "") {
                        $(this).after('<span class="error">This field is required.</span>');
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
    
    <script src="js/bootstrap.bundle.min.js" async defer></script>
    </body>
</html>