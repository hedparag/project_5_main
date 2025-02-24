<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee Management System - Add Employee</title>
    <?php include "./templates/meta-info.php";?>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php
    include "./templates/header.php";
    include "include/config.php";
    session_start();

    if (!isset($_SESSION["employee_id"])) {
        header("Location: logout.php");
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
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

        $profile_image = null;
        if (!empty($_FILES["profile_img"]["name"])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["profile_img"]["name"]);
            move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file);
        } 

        // Prepare the SQL query with placeholders
        $query = "INSERT INTO employees (user_type_id, department_id, position_id, employee_name, employee_email, employee_phone, salary, profile_image, employee_details, employee_skils, dob, created_at, updated_at, status) 
              VALUES ('$user_type_id', '$department_id', '$position_id', '$employee_name', '$employee_email', '$employee_phone', '$salary', '$profile_image', '$employee_details', '$employee_skils', '$dob', '$created_at', '$updated_at', 'f') RETURNING employee_id";
    
        $result = pg_query($con, $query);

        if ($result) {
            echo "<div class='container'>
                <div class='form' style='text-align:center; padding:20px; background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;'>
                    <h3>Employee Account Registered Successfully!</h3><br>
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
    

    <div class="container flex-grow-1 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3 bg-primary text-white">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">Add Employee</h2>
                    <form action="add_employee.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input type="text" class="form-control rounded-3" id="employee_name" name="employee_name" placeholder="Enter your full name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-Mail</label>
                                    <input type="email" class="form-control rounded-3" id="employee_email" name="employee_email" placeholder="Enter your email address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control rounded-3" id="employee_phone" name="employee_phone" placeholder="Enter your mobile number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control rounded-3" id="dob" name="dob" required>
                                </div>
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salary</label>
                                    <input type="number" class="form-control rounded-3" id="salary" name="salary" placeholder="Enter your salary in INR" required>
                                </div>
                                <div class="mb-3">
                                    <label for="profile_img" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control rounded-3" id="profile_image" name="profile_image" accept="image/*" required>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <select class="form-select rounded-3" id="position_id" name="position_id" required>
                                        <option value="">-- Select Position --</option>
                                        <?php
                                        $query1 = "SELECT * FROM positions WHERE status = 't'";
                                        $result = pg_query($con, $query1);
                                        while ($data = pg_fetch_assoc($result)) {
                                            echo "<option value='{$data['position_id']}'>{$data['position_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select rounded-3" id="department_id" name="department_id" required>
                                        <option value="">-- Select Department --</option>
                                        <?php
                                        $query1 = "SELECT * FROM departments WHERE status = 't'";
                                        $result = pg_query($con, $query1);
                                        while ($data = pg_fetch_assoc($result)) {
                                            echo "<option value='{$data['department_id']}'>{$data['department_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">User Type</label>
                                    <select class="form-select rounded-3" id="user_type_id" name="user_type_id" required>
                                        <option value="">-- Select User Type --</option>
                                        <?php
                                        $query1 = "SELECT * FROM user_types WHERE status = 't'";
                                        $result = pg_query($con, $query1);
                                        while ($data = pg_fetch_assoc($result)) {
                                            echo "<option value='{$data['user_type_id']}'>{$data['user_type']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="emp_details" class="form-label">Employee Details</label>
                                    <textarea class="form-control rounded-3" id="employee_details" name="employee_details" rows="3" placeholder="Write a short bio about yourself"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="skills" class="form-label">Skills</label>
                                    <textarea class="form-control rounded-3" id="employee_skills" name="employee_skills" rows="3" placeholder="Enter skills separated by commas"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" name="submit" id="submit" class="btn btn-light text-primary fw-bold rounded-3"><i class="fas fa-user-plus me-1"></i> Register</button>
                                </div>
                                <p class="text-center mt-3 mb-0">
                                    Already have an account? <a href="logout.php" class="text-white text-decoration-none fw-bold">Login here</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
    <?php pg_close($con);?>
    <script src="js/bootstrap.bundle.min.js" async defer></script>
    <script src="./js/dashboard.js"></script>

    <?php include "./templates/footer.php"; ?>
    </body>
</html>