<?php
session_start(); 

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="robots" content="noindex, nofollow">
  <title>Law AI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="./speedlaw.png">
  <!-- PDF.js for text extraction -->
  <script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
  <!-- Tesseract.js for OCR (image-based PDFs) -->
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #4a67d6;
      --primary-dark: #3b54b4;
      --secondary: #f0f4ff;
      --text-dark: #333;
      --text-light: #fff;
      --background: #f8f9fc;
      --bubble-user: linear-gradient(135deg, #dbe8ff, #a8c8ff);
      --bubble-bot: #ffffff;
      --format-gradient: linear-gradient(135deg, #e6e9f8, #c8d2f2);
      --format-hover: linear-gradient(135deg, #d8ddf9, #b5c0f7);
      --format-active: linear-gradient(135deg, #c1caf5, #8da4f0);
    }

    /* Sidebar styles */
    .sidebar {
      width: 260px;
      background-color: #fff;
      border-right: 1px solid #e0e0e0;
      display: flex;
      flex-direction: column;
      padding: 20px;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 100;
      transition: transform 0.3s ease;
    }

    .sidebar h2 {
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 1.4rem;
    }

    .sidebar button {
      padding: 12px;
      background: var(--primary);
      color: var(--text-light);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      margin-bottom: 20px;
      transition: background 0.2s ease;
    }

    .sidebar button:hover {
      background: var(--primary-dark);
    }
    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.4);
      z-index: 99;
    }

    /* Main area */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 20px;
      border-bottom: 1px solid #e0e0e0;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }

    .chat-header-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary);
	  cursor: pointer;
    }

    .menu-btn,
    .admin-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--primary);
    }

   /* Dropdown menu */
.admin-menu {
  display: none;
  position: absolute;
  top: 65px; /* adjust based on placement */
  right: 15px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  overflow: hidden;
  animation: fadeIn 0.2s ease-in-out;
  z-index: 1000;
}

.admin-menu button {
  display: flex;
  align-items: center;
  gap: 8px;
  width: 100%;
  padding: 10px 16px;
  border: none;
  background: none;
  font-size: 14px;
  color: #374151;
  cursor: pointer;
  transition: background 0.2s ease, color 0.2s ease;
}

.admin-menu button:hover {
  background: #f3f4f6;
  color: #111827;
}
.menu-btn {
        display: none;
      }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .menu-btn {
        display: block;
      }
      .main {
        margin-left: 0;
      }
      .sidebar-overlay.active {
        display: block;
      }
    }



  </style>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.6;margin: 0; background:#f9f9f9; color:#333;">
 <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
  <div class="main">
    <div class="header-bar">
      <button class="menu-btn" onclick="toggleSidebar()">☰</button>
      <div class="chat-header-title" onclick="window.location.href='/'">Law AI</div>
      <button class="admin-btn" onclick="toggleAdminMenu(event)">⚙️</button>
      <div id="adminMenu" class="admin-menu">
  <?php if(isset($_SESSION['user_id'])): ?>
      <button onclick="openSettings()">👤 My Account</button>
      <button onclick="logout()">🚪 Logout</button>
  <?php else: ?>
      <button onclick="login()">🔑 Login</button>
  <?php endif; ?>
</div>
    </div>
  <div style="max-width:1100px; margin:auto;width: 100%; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <h1 style="text-align:center; color:#222;">Privacy Policy</h1>

    <h2 style="color:#222;">1. Information We Collect</h2>
    <p>We may collect the following information when you use our services:</p>
    <ul style="margin-left:20px;">
      <li>User-provided details: case documents, questions, personal info (name, email).</li>
      <li>Automatically collected data: IP address, browser type, device info, cookies.</li>
    </ul>

    <h2 style="color:#222;">2. How We Use Your Information</h2>
    <p>We use the information to:</p>
    <ul style="margin-left:20px;">
      <li>Provide case summaries and draft agreements.</li>
      <li>Respond to legal-related questions.</li>
      <li>Improve and secure our services.</li>
      <li>Comply with legal obligations.</li>
    </ul>

    <h2 style="color:#222;">3. Data Sharing</h2>
    <p>We do not sell your data. We may share information with:</p>
    <ul style="margin-left:20px;">
      <li>Service providers (like AI platforms such as OpenAI) for processing.</li>
      <li>Authorities if required by law.</li>
      <li>To protect rights, safety, or prevent misuse.</li>
    </ul>

    <h2 style="color:#222;">4. Data Security</h2>
    <p>We use reasonable security measures to protect data, but no system is 100% secure.</p>

    <h2 style="color:#222;">5. Data Retention</h2>
    <p>We retain uploaded data only as long as needed for processing, unless you choose to save drafts in your account.</p>

    <h2 style="color:#222;">6. Your Rights</h2>
    <p>You may have rights to access, correct, or delete your data. Contact us to exercise these rights.</p>

    <h2 style="color:#222;">7. Third-Party Services</h2>
    <p>We use third-party providers (such as OpenAI) to process your inputs. Please check their privacy policies.</p>

    <h2 style="color:#222;">8. Cookies</h2>
    <p>We use cookies to improve site performance and user experience. You can disable cookies in your browser.</p>

    <h2 style="color:#222;">9. Children’s Privacy</h2>
    <p>Our services are not for individuals under 18. We do not knowingly collect data from minors.</p>

    <h2 style="color:#222;">10. Updates</h2>
    <p>We may update this Privacy Policy. Changes will be posted here with a new effective date.</p>

    <h2 style="color:#222;">11. Contact Us</h2>
    <p>If you have any questions, please contact us:</p>
    <p>
      📧 Email: <a href="mailto:pay2speed@gmail.com" style="color:#0066cc;">pay2speed@gmail.com
</a><br />
    </p>
  </div>
   </div>
<?php
include "include/footer.php"; 
?>
