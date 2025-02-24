<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <?php include "./templates/meta-info.php";?>
    </head>
    <body class="d-flex flex-column min-vh-100">
    <?php include "./templates/header.php";
        echo $navbarAdminScr;
    ?>
        <div class="d-flex justify-content-center">
            <div class="w-50">
                <?php
                    include "include/config.php";
                    session_start();
                    
                    if (!isset($_SESSION["employee_id"])) {
                        header("Location: logout.php");
                        exit();
                    }
                    
                    // Display success or error messages
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                ' . $_SESSION['message'] . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        unset($_SESSION['message']); // Remove message after displaying
                    }

                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                ' . $_SESSION['error'] . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        unset($_SESSION['error']); // Remove error after displaying
                    }
                            
                    $query = "SELECT e.employee_id, e.employee_name, e.employee_email, e.employee_phone, e.salary, e.profile_image, e.employee_details, e.employee_skils, e.dob, e.status, d.department_name, p.position_name, u_t.user_type, u_t.user_type_id FROM employees e 
                            JOIN departments d ON e.department_id = d.department_id 
                            JOIN positions p ON e.position_id = p.position_id 
                            JOIN user_types u_t ON e.user_type_id = u_t.user_type_id;";
                    
                    $result = pg_query($con, $query);
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
                        $_SESSION['employee_id'] = $_POST['employee_id'];
                    }
                    $employee_id = $_SESSION['employee_id'] ?? null;

                    if (!$employee_id) {
                        header("Location: dashboard.php"); // Redirect if no ID found
                        exit();
                    }
                ?>
            </div>
        </div>

            <!-- Main Content -->
        <div class="container mt-4">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <form method="post" action="add_employee.php">
                            <button type="submit" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fa-solid fa-plus"></i> Add Employee
                            </button>
                        </form>
                        <div>
                            <button id="bulkApproveBtn" class="btn btn-success shadow-sm">
                                <i class="fa-solid fa-check"></i> Approve Selected
                            </button>
                            <button id="bulkRejectBtn" class="btn btn-danger shadow-sm">
                                <i class="fa-solid fa-xmark"></i> Reject Selected
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Skills</th>
                                    <th>Details</th>
                                    <th>Employee Type</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Approve/Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = pg_fetch_assoc($result)) { ?>
                                        <td><input type="checkbox" class="form-check-input userCheckbox" value="<?= $row['employee_id']; ?>"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $row['profile_image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($row['employee_name']); ?>"
                                                    class="rounded-circle border me-2" width="50" height="50">
                                                <span class="fw-bold"> <?= htmlspecialchars($row['employee_name']); ?> </span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($row['employee_email']); ?></td>
                                        <td><?= htmlspecialchars($row['employee_phone']); ?></td>
                                        <td><span class="badge bg-primary"> <?= htmlspecialchars($row['position_name']); ?> </span></td>
                                        <td><span class="badge bg-secondary"> <?= htmlspecialchars($row['department_name']); ?> </span></td>
                                        <td>
                                            <?php
                                            $skillsArray = explode(',', $row['employee_skils']);
                                            foreach ($skillsArray as $skill) {
                                                $skill = trim($skill);
                                                if (!empty($skill)) {
                                                    echo "<span class='badge bg-info text-dark me-1'>" . htmlspecialchars($skill) . "</span>";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-truncate" style="max-width: 150px;"> <?= htmlspecialchars($row['employee_details']); ?> </td>
                                        <td class="text-truncate" style="max-width: 150px;"> <?= htmlspecialchars($row['user_type']); ?></td>
                                        <td>
                                            <span class="badge <?= $row['status'] == 't' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?= $row['status'] == 't' ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="profile.php?id=<?php echo $row['employee_id']; ?>&hash=<?php echo md5($row['employee_id'].'abcd'); ?>"
                                            class="btn btn-sm btn-outline-info">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <a href="delete_employee.php?id=<?php echo $row['employee_id']; ?>&hash=<?php echo md5($row['employee_id'].'abcd'); ?>"
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirmDelete();">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm <?= $row['status'] == 't' ? 'btn-danger' : 'btn-success'; ?> toggle-status-btn"
                                                data-id="<?= $row['employee_id']; ?>" data-status="<?= $row['status']; ?>">
                                                <?= $row['status'] == 't' ? 'Reject' : 'Approve'; ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="./js/dashboard.js"></script>
        <script>
            function confirmDelete() {
                if (confirm("Are you sure you want to delete your profile? This action cannot be undone.")) {
                    location.href = 'delete_employee.php'; // Change to actual delete action
                }
            }
        </script>

        <?php include "./templates/footer.php"; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </body>
</html>
