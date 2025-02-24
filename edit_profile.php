<?php
include "include/config.php";
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Employee ID is required. GET[id] Value: ".print_r($_GET['id']);
    header("Location: dashboard.php");
    exit();
}
      
$invalid = 0;
if(md5($_GET['id'].'abcd') != $_GET['hash']){
    $invalid = 1;
}

$employee_id = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $employee_name = pg_escape_string($con, $_POST['employee_name']);
    $employee_email = pg_escape_string($con, $_POST['employee_email']);
    $employee_phone = intval($_POST['employee_phone']);
    $salary = intval($_POST['salary']);
    $employee_details = pg_escape_string($con, $_POST['employee_details']);
    $employee_skils = pg_escape_string($con, $_POST['employee_skils']);

    $psql = "UPDATE users SET full_name = '$employee_name' WHERE employee_id = $employee_id;";
    $result1 = pg_query($con, $psql);
    $query = "UPDATE employees SET employee_name = '$employee_name', employee_email = '$employee_email', employee_phone = $employee_phone, salary = $salary, employee_details = '$employee_details', employee_skils = '$employee_skils' WHERE employee_id = $employee_id;";
    $result2 = pg_query($con, $query);

    if ($result1 && $result2) {
        $_SESSION['message'] = "Employee updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating employee: " . pg_last_error($con);
    }
} else {
    //$_SESSION['error'] = "Error updating employee: " .print_r($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']));
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Employee</title>
        <?php include './templates/meta-info.php'; ?>
    </head>
    <body>
    <?php
    include './templates/header.php';
    echo $navbarUserScr;
    
    $employee_id = $_GET['id'] ?? null;

    $query = "SELECT * FROM employees WHERE employee_id = $1;";
    $params = [$employee_id];
    $res = pg_query_params($con, $query, $params);

    if (!$res || pg_num_rows($res) === 0) {
        die("<h2>Not Found</h2>");
    }
    $row = pg_fetch_assoc($res);
    ?>

    <div class="container flex-grow-1 py-5">
    <?php if($invalid == 0) { ?>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3 bg-primary text-white">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">Edit Employee</h2>
                    <form method="POST" action="edit_profile.php?id=<?php echo $_GET['id']; ?>&hash=<?php echo $_GET['hash']; ?>">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control rounded-3" name="employee_name" value="<?php echo $row['employee_name']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">E-Mail</label>
                                    <input type="email" class="form-control rounded-3" name="employee_email" value="<?php echo $row['employee_email']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="tel" class="form-control rounded-3" name="employee_phone" value="<?php echo $row['employee_phone']; ?>" required>
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Salary</label>
                                    <input type="number" class="form-control rounded-3" name="salary" value="<?php echo $row['salary']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Employee Details</label>
                                    <textarea class="form-control rounded-3" name="employee_details" rows="3"><?php echo $row['employee_details']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Skills</label>
                                    <textarea class="form-control rounded-3" name="employee_skils" rows="3"><?php echo $row['employee_skils']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" name="submit" id="submit" class="btn btn-light text-primary fw-bold rounded-3"><i class="fas fa-save me-1"></i> Update</button>
                                </div>
                                <p class="text-center mt-3 mb-0">
                                    <a href="dashboard.php" class="text-white text-decoration-none fw-bold">Cancel</a>
                                </p>
                            </div>
                        </div>
                    </form>
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



    <script src="js/bootstrap.bundle.min.js" async defer></script>
    </body>
</html>
