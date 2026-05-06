<?php
session_start();
include "include/connect.php";

// Get payment details from PayPal (you'll need to implement IPN verification)
$user_id = isset($_GET['custom']) ? $_GET['custom'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0);
$payment_status = $_GET['payment_status'] ?? '';

if ($payment_status === 'Completed' && $user_id) {
    // Update user to premium status
    $stmt = $conn->prepare("UPDATE users SET is_premium = 1, premium_since = NOW() WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $_SESSION['premium_user'] = true;
    
    // Log the payment
    $stmt = $conn->prepare("INSERT INTO premium_payments (user_id, amount, payment_date, status) VALUES (?, ?, NOW(), 'completed')");
    $amount = $_GET['amt'] ?? '0.00';
    $stmt->bind_param("is", $user_id, $amount);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Law AI</title>
    <style>
        .success-container {
            max-width: 500px;
            margin: 100px auto;
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 20px;
        }
    </style>
</head>
<body style="background: var(--background);">
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1 style="color: var(--primary); margin-bottom: 15px;">Payment Successful!</h1>
        <p style="color: var(--text-dark); margin-bottom: 25px; line-height: 1.6;">
            Thank you for upgrading to Law AI Premium! Your account has been upgraded and you now have access to all premium features.
        </p>
        <a href="index.php" class="sidebar-link" style="display: inline-block; padding: 12px 30px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px;">
            Go to Dashboard
        </a>
    </div>
</body>
</html>