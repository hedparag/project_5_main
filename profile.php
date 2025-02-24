<!DOCTYPE html>
<html lang="en">

<head>
    <?php include './templates/meta-info.php'; ?>
    <title>Profile | EMS</title>
</head>

<?php
    include "include/config.php";
    session_start();
    $invalid = 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
        $_SESSION['employee_id'] = $_POST['employee_id'];
    }
    if (!isset($_SESSION['employee_id'])) {
        $_SESSION['error'] = "Employee ID is required.";
        header("Location: logout.php");
        exit();
    } 
    if (isset($_GET['id'])) {
        $employee_id = isset($_GET['id']) ? $_GET['id'] : null;
        if(md5($_GET['id'].'abcd') != $_GET['hash']){
            $invalid = 1;
        }
    } else{
        $employee_id = $_SESSION['employee_id'];
    }
    $query = "SELECT * FROM employees WHERE employee_id=$1";
    $result = pg_query_params($con, $query, [$employee_id]);

    if (!$result || pg_num_rows($result) == 0) {
        die("Employee not found.");
    }

    $employee = pg_fetch_assoc($result);
?>


<body class="d-flex flex-column min-vh-100" style="background-image: url('https://img.freepik.com/free-vector/blue-pink-halftone-background_53876-99004.jpg'); background-size: cover; background-position: center; font-family: poppins;">

    <?php
    include './templates/header.php';
    echo $navbarUserScr;
    
    $query_pos = "SELECT * FROM positions WHERE position_id= $1;";
    $params_pos=[$employee['position_id']];
    $res_pos=pg_query_params($con,$query_pos,$params_pos);
    $result_pos=pg_fetch_assoc($res_pos);
    $position=$result_pos['position_name'];
    
    $query_dept = "SELECT * FROM departments WHERE department_id=$1;";
    $params_dept=[$employee['department_id']];
    $res_dept=pg_query_params($con,$query_dept,$params_dept);
    $result_dept=pg_fetch_assoc($res_dept);
    $department=$result_dept['department_name'];
    
    $query_type = "SELECT * FROM user_types WHERE user_type_id=$1;";
    $params_type=[$employee['user_type_id']];
    $res_type=pg_query_params($con,$query_type,$params_type);
    $result_type=pg_fetch_assoc($res_type);
    $type=$result_type['user_type'];
    ?>

    <!-- Main Content -->
    <div class="container flex-grow-1 py-5">
    <?php if($invalid == 0) { ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Profile Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="position-relative">
                                    <img src="<?= $employee['profile_image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($employee["employee_name"]); ?>" class="rounded-circle me-2" width="128" height="128">
                                    <label for="profile_img" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm" style="cursor: pointer;">
                                        <i class="fas fa-camera text-primary"></i>
                                        <input type="file" id="profile_img" class="d-none">
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="mb-1"><?php echo $employee['employee_name'] ?></h2>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary">
                                        <?php
                                        $query1 = "SELECT * FROM positions WHERE position_id=$1;";
                                        $presult = pg_query_params($con, $query1, [$employee['position_id']]);
                                        $pdata = pg_fetch_assoc($presult);
                                        echo $pdata["position_name"];
                                        ?>

                                    </span>
                                    <?php if ($employee['status']=='t') { ?>
                                        <span class="badge bg-success">Active </span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-user-circle text-primary"></i>
                                </span>
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title mb-0">Personal Information</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-lg rounded-3">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">Employee Profile</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <h6 class="text-muted">Personal Details</h6>
                                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($employee['employee_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['employee_email'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($employee['employee_phone'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($employee['dob'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="card p-3">
                                        <h6 class="text-muted">Work Information</h6>
                                        <p><strong>Position:</strong> <?php echo htmlspecialchars($position, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Department:</strong> <?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Employee Type:</strong> <?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Salary:</strong> <?php echo htmlspecialchars($employee['salary'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                </div>

                                <!-- Full Width Details -->
                                <div class="col-12">
                                    <div class="card p-3">
                                        <h6 class="text-muted">Additional Information</h6>
                                        <p><strong>About Me:</strong> <?php echo htmlspecialchars($employee['employee_details'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p><strong>Skills:</strong> <?php echo htmlspecialchars($employee['employee_skils'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Show buttons only if status is active
                            if ($employee['status'] == 't') :
                            ?>
                                <!-- Action Buttons -->
                                <hr class="my-4">
                                <div class="d-flex justify-content-center gap-3">
                                    <!-- New Password -->
                                    <a href="new_password.php?id=<?php echo $employee['employee_id']; ?>&hash=<?php echo md5($employee['employee_id'].'abcd'); ?>"
                                    class="btn btn-warning">
                                        <i class="fas fa-key me-1"></i> New Password
                                    </a>

                                    <!-- Change Password -->
                                    <a href="password_change.php?id=<?php echo $employee['employee_id']; ?>&hash=<?php echo md5($employee['employee_id'] . 'abcd'); ?>" 
                                    class="btn btn-danger">
                                        <i class="fas fa-lock me-1"></i> Change Password
                                    </a>

                                    <!-- Update Details -->
                                    <a href="edit_profile.php?id=<?php echo $employee['employee_id']; ?>&hash=<?php echo md5($employee['employee_id'] . 'abcd'); ?>" 
                                    class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i> Update Details
                                    </a>

                                    <!-- Delete Profile -->
                                    <a href="delete_employee.php?id=<?php echo $employee['employee_id']; ?>&hash=<?php echo md5($employee['employee_id'] . 'abcd'); ?>" 
                                    class="btn btn-outline-danger" 
                                    onclick="return confirmDelete();">
                                        <i class="fas fa-trash me-1"></i> Delete Profile
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="card text-center mt-3">
                                    <div class="card-body">
                                        <p class="text-danger mb-0">Wait till Admin activates your status.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="alert alert-danger" role="alert">
                INVALID USER ID.
            </div>
        <?php } ?>
    </div>

    <?php
    include './templates/footer.php';
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your profile? This action cannot be undone.")) {
                location.href = 'delete_employee.php'; // Change to actual delete action
            }
        }
    </script>
</body>

</html>