<?php
include 'include/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    }
    
    if (empty($errors)) {
        // Check user exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['mobile'] = $user['mobile'];
            
            // Remember me functionality
            if (isset($_POST['rememberMe'])) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60); // 30 days
                
                $query = "UPDATE users SET remember_token = ?, token_expiry = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $token, $expiry, $user['id']);
                $stmt->execute();
                
                setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/');
            }
            
            // Redirect to dashboard
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Invalid email or password';
        }
    }
    
    // Store errors in session
    $_SESSION['errors'] = $errors;
   header('Location: gettoken.php?from=login');
    exit();
}
?>