<?php
include 'include/connect.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $mobile = trim($_POST['mobile']);
    $dialCode = trim($_POST['dialCode']);
    $full_mobile = '+' . $dialCode . $mobile;


    $country = trim($_POST['country']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);

    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter and one number';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($mobile)) {
        $errors[] = 'Mobile number is required';
    }

    if (empty($country)) {
        $errors[] = 'Country is required';
    }

    if (empty($state)) {
        $errors[] = 'State is required';
    }

    if (empty($city)) {
        $errors[] = 'City is required';
    }

    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Email already exists';
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password, mobile, country, state, city) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            $errors[] = 'Database error: ' . $conn->error;
        } else {
            $stmt->bind_param("sssssss", $username, $email, $hashed_password, $full_mobile, $country, $state, $city);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['mobile'] = $mobile;
                $_SESSION['registration_success'] = true;

                header('Location: index.php');
                exit();
            } else {
                $errors[] = 'Registration failed: ' . $stmt->error;
            }
        }
    }

    // Store errors and old form data
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = [
        'username' => $username,
        'email' => $email,
        'mobile' => $full_mobile,
        'country' => $country,
        'state' => $state,
        'city' => $city
    ];

    header('Location: gettoken.php?from=register');
    exit();
}
?>
