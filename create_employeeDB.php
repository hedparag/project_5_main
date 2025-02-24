<?php
// Database configuration
$host = "localhost";
$port = "5432";
$user = "postgres"; // Replace with your PostgreSQL username
$password = "admin"; // Replace with your PostgreSQL password
$newDbName = "EmployeeDB";

// Connect to PostgreSQL server
$conn = pg_connect("host=$host port=$port user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Check if the database exists
$dbExistsQuery = "SELECT 1 FROM pg_database WHERE datname = '$newDbName'";
$result = pg_query($conn, $dbExistsQuery);

if (!$result) {
    die("Error checking database existence: " . pg_last_error());
}

if (pg_num_rows($result) == 0) {
    // Create the database if it doesn't exist
    $createDbQuery = "CREATE DATABASE \"$newDbName\"";
    if (pg_query($conn, $createDbQuery)) {
        echo "Database '$newDbName' created successfully!<br>";
    } else {
        die("Error creating database: " . pg_last_error());
    }
} else {
    echo "Database '$newDbName' already exists!<br>";
}

// Close the initial connection
pg_close($conn);

// Connect to the new database
$conn = pg_connect("host=$host port=$port dbname=$newDbName user=$user password=$password");

if (!$conn) {
    die("Connection to the new database failed: " . pg_last_error());
}

// SQL to create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS user_types (
        user_type_id SERIAL PRIMARY KEY,
        user_type VARCHAR(50) NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    )",
    "CREATE TABLE IF NOT EXISTS departments (
        department_id SERIAL PRIMARY KEY,
        department_name VARCHAR(100) NOT NULL,
        department_abbr VARCHAR(10) NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    )",
    "CREATE TABLE IF NOT EXISTS positions (
        position_id SERIAL PRIMARY KEY,
        position_name VARCHAR(100) NOT NULL,
        position_abbr VARCHAR(10) NOT NULL,
        status BOOLEAN NOT NULL DEFAULT TRUE
    )",
    "CREATE TABLE IF NOT EXISTS employees (
        employee_id SERIAL PRIMARY KEY,
        user_type_id INT NOT NULL,
        department_id INT NOT NULL,
        position_id INT NOT NULL,
        employee_name VARCHAR(100) NOT NULL,
        employee_email VARCHAR(100) NOT NULL UNIQUE,
        employee_phone VARCHAR(15) NOT NULL UNIQUE,
        salary NUMERIC(10, 2) NOT NULL,
        profile_image VARCHAR(255),
        employee_details TEXT,
        employee_skils TEXT,
        dob DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status BOOLEAN NOT NULL DEFAULT TRUE,
        FOREIGN KEY (user_type_id) REFERENCES user_types(user_type_id),
        FOREIGN KEY (department_id) REFERENCES departments(department_id),
        FOREIGN KEY (position_id) REFERENCES positions(position_id)
    )",
    "CREATE TABLE IF NOT EXISTS users (
        user_id SERIAL PRIMARY KEY,
        employee_id INT NOT NULL,
        user_type_id INT NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login_time TIMESTAMP,
        status BOOLEAN NOT NULL DEFAULT TRUE,
        FOREIGN KEY (employee_id) REFERENCES employees(employee_id),
        FOREIGN KEY (user_type_id) REFERENCES user_types(user_type_id)
    )"
];

// Execute each table creation query
foreach ($tables as $table) {
    $result = pg_query($conn, $table);
    if ($result) {
        echo "Table created successfully!<br>";
    } else {
        echo "Error creating table: " . pg_last_error() . "<br>";
    }
}

// Close the connection
pg_close($conn);
?>
