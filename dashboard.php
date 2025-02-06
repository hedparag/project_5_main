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
                            <a class="nav-link" href="register.php">Registration</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">My Profile</a>
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

    <?php
        include("include/config.php");
        session_start();
        if (!isset($_SESSION["username"])) {
            header("Location: login.php");
            exit();
        }

        $query = "SELECT e.employee_id, e.employee_name, e.employee_email, e.employee_phone, e.salary, e.profile_image, e.employee_details, e.employee_skils, e.dob, d.department_name, p.position_name, u_t.user_type FROM employees e 
                JOIN departments d ON e.department_id = d.department_id 
                JOIN positions p ON e.position_id = p.position_id 
                JOIN user_types u_t ON e.user_type_id = u_t.user_type_id";
        $result = pg_query($con, $query);
    ?>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <h3>Employee Dashboard</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Salary</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>User Type</th>
                    <th>Skills</th>
                    <th>DOB</th>
                    <th>Profile</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = pg_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['employee_id']; ?></td>
                        <td><?php echo $row['employee_name']; ?></td>
                        <td><?php echo $row['employee_email']; ?></td>
                        <td><?php echo $row['employee_phone']; ?></td>
                        <td><?php echo $row['salary']; ?></td>
                        <td><?php echo $row['department_name']; ?></td>
                        <td><?php echo $row['position_name']; ?></td>
                        <td><?php echo $row['user_type']; ?></td>
                        <td><?php echo $row['employee_skils']; ?></td>
                        <td><?php echo $row['dob']; ?></td>
                        <td>
                            <?php if ($row['profile_image']) { ?>
                                <img src="uploads/<?php echo $row['profile_image']; ?>" width="50" height="50">
                            <?php } else { ?>
                                No Image
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><a href="logout.php">Logout</a></p>
    </div>

    <script src="js/bootstrap.bundle.min.js" async defer></script>
    </body>
</html>