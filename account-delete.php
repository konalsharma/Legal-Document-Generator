<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="robots" content="noindex, nofollow">
  <title>Law AI - Delete Account</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="./speedlaw.png">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f4f6fb;
      color: #333;
    }
    .header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 20px;
      background: #fff;
      border-bottom: 1px solid #e0e0e0;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .chat-header-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: #4a67d6;
      cursor: pointer;
    }
    .admin-btn {
      background: none;
      border: none;
	  width: 5%;
      font-size: 1.5rem;
      cursor: pointer;
      color: #4a67d6;
    }
    .card {
      max-width: 420px;
      margin: 40px auto;
      background: #fff;
      padding: 28px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.08);
      animation: fadeIn 0.5s ease;
    }
    .card h2 {
      text-align: center;
      color: #222;
      margin-bottom: 12px;
    }
    .card p {
      font-size: 14px;
      color: #555;
      text-align: center;
      margin-bottom: 24px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-size: 14px;
      font-weight: 500;
      color: #333;
    }
    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: border 0.2s ease;
    }
    input:focus {
      border: 1px solid #4a67d6;
      outline: none;
      box-shadow: 0 0 0 2px rgba(74, 103, 214, 0.1);
    }
    button {
      width: 100%;
      background: #c62828;
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s ease, transform 0.1s ease;
    }
    button:hover {
      background: #b71c1c;
    }
    button:active {
      transform: scale(0.98);
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="header-bar">
    <div class="chat-header-title" onclick="window.location.href='/'">Law AI</div>
    <button class="admin-btn">⚙️</button>
  </div>

  <div class="card">
    <h2>Delete Your Account</h2>
    <p>Enter your email to request account deletion.</p>

    <form id="deleteForm">
      <label>Email Address</label>
      <input type="email" id="email" required />
      <button type="submit">Deletion Account</button>
    </form>
  </div>

 <script>
  const form = document.getElementById("deleteForm");
  form.addEventListener("submit", function(e){
    e.preventDefault();
    
    Swal.fire({
      icon: 'success',
      title: 'Account Delete',
      text: '✅ Your account deletion process will start soon.',
      confirmButtonColor: '#4a67d6'
    }).then(() => {
      // Redirect user to home page
      window.location.href = '/';
    });

    form.reset();
  });
</script>
<br><br><br><br>
<?php
include "include/footer.php"; 
?>

