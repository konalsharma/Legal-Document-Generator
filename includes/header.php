<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

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

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: math;
    }

    html, body {
      height: 100%;
      background-color: var(--background);
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

    .chat-list {
      flex: 1;
      overflow-y: auto;
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
      margin-left: 260px;
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
label.upload-btn {
    margin: 10px;
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
    /* Format Section (The Tiles) */
    .format-section {
	  height: 100%;
      padding: 20px;
      background-color: #fff;
      border-bottom: 1px solid #e0e0e0;
      text-align: center;
    }
    .format-title {
      font-size: 1.4rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: var(--text-dark);
    }
   .format-boxes {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* 2 per row */
  gap: 20px;
  max-width: 350px;  /* keep it centered and neat */
  margin: 0 auto;justify-items: center;
}

.format-box {
  width: 160px;
  height: 160px;
  border-radius: 18px;
  background: linear-gradient(135deg, #eef2f7, #dce6f9);
  border: 1px solid #d0d7e2;
  text-align: center;
  font-weight: 600;
  color: #2d2d2d;
  box-shadow: 0 6px 12px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  font-size: 1rem;
  text-decoration: none;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}
.format-box:hover {
  transform: translateY(-5px) scale(1.05);
  box-shadow: 0 10px 18px rgba(0,0,0,0.15);
  background: linear-gradient(135deg, #d0e4ff, #e8f0ff);
  border-color: #8cbaff;
}

/* subtle animated highlight */
.format-box::before {
  content: "";
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.2), transparent 70%);
  transform: rotate(25deg);
  transition: opacity 0.3s;
  opacity: 0;
}

.format-box:hover::before {
  opacity: 1;
}

/* Icon style */
.format-box .icon {
  font-size: 2rem;
  margin-bottom: 8px;
}

/* Label text */
.format-box .label {
  font-size: 1rem;
  letter-spacing: 0.5px;
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
	.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 10px;
}
.sidebar-link {
	cursor: pointer;
  display: block;
  background: var(--format-gradient);
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  color: var(--text-dark);
  font-weight: 500;
  transition: background 0.2s ease;
}
.sidebar-link:hover {
  background: var(--format-hover);
  color: var(--text-dark);
}

.sample-question {
      background: linear-gradient(135deg, #e6e9f8, #c8d2f2);
      border: none;
      border-radius: 8px;
      padding: 12px;
      text-align: left;
      font-size: 0.95rem;
      color: var(--text-dark);
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .sample-question:hover {
      background: linear-gradient(135deg, #d8ddf9, #b5c0f7);
    }

    .content {
      padding: 20px;
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

    /* Chat styles */
   #chatMessages {
  min-height: 500px;
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

    .message {
      max-width: 90%;
      border-radius: 16px;
      word-wrap: break-word;
    }

    .message.user {
      align-self: flex-end;
      background-color: var(--user-bubble);
      color: white;
    }

    .message.bot {
      align-self: flex-start;
      background-color: var(--bot-bubble);
      color: #333;
    }

    .typing {
  align-self: flex-start;
  background: none;
  padding: 0;
  margin: 0;
  font-style: italic;
  color: gray;
  border-radius: 0;
}
.content {
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

   .content {
	   display: flex;
      flex-direction: row;
  gap: 20px;
  padding: 20px;
}

.card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.03);
  flex: 1 1 300px;   /* responsive flexible width */
  min-width: 280px;
  width: 100%;
}

@media (max-width: 768px) {
  .content {
    flex-direction: column;
  }

  .card {
    flex: 1 1 100%;
    width: 100%;
  }
}

    .card h3 {
      margin-bottom: 16px;
      color: var(--primary);
    }

    .card label {
      display: block;
      font-size: 20px;
      margin-bottom: 4px;
      font-weight: 500;
	  cursor: pointer;
      color: var(--text-dark);
    }

    .card input,
    .card select,
    .card textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      resize: vertical;
    }
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
    .card button {
      margin-top: 16px;
      background: var(--primary);
      border: none;
      color: #fff;
      padding: 12px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
    }

    .card button:hover {
      background: var(--primary-dark);
    }

    #generatedContent {
      min-height: 200px;
	  max-height: 400px;
      border: 2px dashed #ccc;
      padding: 20px;
      color: #666;
      white-space: pre-wrap;
      overflow-y: auto;
    }

    #actionButtons {
      display: none;
      margin-top: 16px;
      gap: 12px;
    }

    #actionButtons button {
      flex: 1;
      background: var(--primary);
      border: none;
      color: #fff;
      padding: 10px;
      border-radius: 6px;
      cursor: pointer;
    }

    #actionButtons button:hover {
      background: var(--primary-dark);
    }

    #progressBar {
      display: none;
      width: 100%;
      background-color: #e0e0e0;
      border-radius: 6px;
      margin-top: 20px;
      overflow: hidden;
      height: 12px;
    }

    #progressInner {
      height: 100%;
      width: 0%;
      background-color: var(--primary);
      transition: width 0.3s;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main {
        margin-left: 0;
      }
      .sidebar-overlay.active {
        display: block;
      }
    }
	.styled-form {
  background: var(--secondary);
  border-radius: 12px;
  padding: 20px;
  animation: fadeIn 0.4s ease;
  margin-top: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.03);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full {
  grid-column: 1 / -1;
}

.styled-form label {
  font-weight: 500;
  margin-bottom: 6px;
  color: var(--text-dark);
}

.styled-form input,
.styled-form textarea {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background: #fff;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
.rent-type-selector {
  display: none;
  padding: 1rem;
  background-color: #f9f9f9;
  border-radius: 8px;
}

.rent-type-label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
  color: #333;
}

.rent-type-options {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.rent-type-options .option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #fff;
  padding: 0.6rem 1rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  cursor: pointer;
  transition: all 0.3s ease;
}

.rent-type-options .option input[type='radio'] {
  accent-color: var(--primary);
  transform: scale(1.2);
}
/* Style radio groups to align with the styled-form aesthetic */
.form-group div {
  display: flex;
  justify-content: space-between;
  gap: 6px;
  padding-left: 2px;
}

/* Individual radio option layout */
.form-group input[type="radio"] {
  accent-color: var(--primary);
  margin-right: 8px;
  transform: scale(1.1);
  cursor: pointer;
}

/* Make label and radio button align cleanly */
.form-group label input[type="radio"] {
  margin-right: 8px;
}

/* Optional hover effect on labels */
.form-group label:hover {
  color: var(--primary-dark);
}

/* Adjust spacing for better mobile experience */
@media (max-width: 600px) {
  .form-group div {
    gap: 4px;
  }
}
 .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  }

  .modal-box h3 {
    margin-bottom: 20px;
    font-size: 1.3rem;
  }

  .modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
  }

  .modal-buttons button {
    padding: 8px 20px;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  .modal-buttons .yes {
    background-color: #4a67d6;
    color: white;
  }

  .modal-buttons .no {
    background-color: #ddd;
  }
#extractedText {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  max-height: 300px;
  overflow-y: auto;
  font-size: 15px;
  line-height: 1.6;
  white-space: pre-wrap;
}

/* Message Boxes */
.message {
  padding: 15px 20px;
  border-radius: 12px;
  margin-bottom: 15px;
  width: fit-content;
  max-width: 100%;
}

.message.bot {
  background-color: #e7f3ff;
  align-self: flex-start;
}

.message.user {
  background-color: #11be65;
  align-self: flex-end;
  border-right: 5px solid #10b981;
}

/* Summary Box */
.message.bot h3 {
  margin-top: 0;
  color: #1e3a8a;
}

.message.bot pre {
  background: #f0f9ff;
  padding: 12px;
  border: 1px solid #dbeafe;
  border-radius: 8px;
  font-size: 14px;
  white-space: pre-wrap;
  overflow-x: auto;
}

/* Buttons */
button {
  background: #3b82f6;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.3s ease;
}

button:hover {
  background: #2563eb;
  color: #fff;
}

button:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

/* Loading Spinner */
.loading-spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 3px solid rgba(0,0,0,0.1);
  border-top-color: #3b82f6;
  border-radius: 50%;
  animation: spin 1s ease-in-out infinite;
  margin-left: 10px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
.spinner-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 20px;
  color: #3b82f6;
  font-weight: 500;
  font-size: 16px;
}

.spinner {
  width: 24px;
  height: 24px;
  border: 4px solid #d1d5db;
  border-top-color: #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
.custom-upload-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  border: 2px solid #4a67d6;
  padding: 12px 20px;
  border-radius: 10px;
  cursor: pointer;
  color: #4a67d6;
  font-weight: 500;
  font-size: 15px;
  transition: all 0.2s ease-in-out;
  background-color: #fff;
}

.custom-upload-btn:hover {
  background-color: #f0f4ff;
}

.custom-upload-btn .icon {
  width: 20px;
  height: 20px;
  stroke: #4a67d6;
}
.image-grid > div {
  position: relative;
}
/* Account content styles */
    .account-container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
      width: 100%;
    }

    .account-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .account-title {
      font-size: 2rem;
      color: var(--primary);
      font-weight: 600;
    }

    .account-sections {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 30px;
    }

    @media (max-width: 768px) {
      .account-sections {
        grid-template-columns: 1fr;
      }
    }

    /* Profile section */
    .profile-section {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: var(--format-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: var(--primary);
      margin-right: 20px;
    }

    .profile-info h3 {
      font-size: 1.5rem;
      margin-bottom: 5px;
      color: var(--text-dark);
    }

    .profile-info p {
      color: #666;
    }

    .profile-details {
      margin-top: 20px;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      font-weight: 500;
      color: #666;
    }

    .detail-value {
      color: var(--text-dark);
    }

    /* Documents section */
    .documents-section {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .section-title {
      font-size: 1.5rem;
      margin-bottom: 20px;
      color: var(--primary);
      font-weight: 600;
    }
	/* Session list container */
#sessionList {
  list-style: none;
  padding: 0;
  margin: 0;
  border-radius: 10px;
  background: #ffffff;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
  overflow: hidden;
  font-family: 'Inter', sans-serif;
}

/* Each session item */
#sessionList li {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 4px 8px; /* reduced vertical padding */
  border-bottom: 1px solid #f3f4f6;
  transition: all 0.2s ease;
  gap: 4px; /* tighter spacing between buttons */
}

/* Hover effect for list item */
#sessionList li:hover {
  background-color: #f9fafb;
}

/* Main case button */
#sessionList li button:first-child {
  flex: 1;
  background: #f3f4f6;
  border: none;
  border-radius: 6px;
  color: #111827;
  padding: 6px 8px; /* smaller padding */
  font-size: 13px;
  font-weight: 500;
  text-align: left;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background 0.2s ease, color 0.2s ease;
}

#sessionList li button:first-child:hover {
  background: #e0e7ff;
  color: #1e3a8a;
}

/* Rename (✏️) button */
#sessionList li button:nth-child(2),
#sessionList li button:last-child {
  background: none;
  border: none;
  font-size: 15px;
  cursor: pointer;
  padding: 4px;
  transition: transform 0.2s ease, color 0.2s ease;
}

/* Rename button color */
#sessionList li button:nth-child(2) {
  color: #2563eb;
}
#sessionList li button:nth-child(2):hover {
  color: #1d4ed8;
  transform: scale(1.1);
}

/* Delete button color */
#sessionList li button:last-child {
  color: #dc2626;
}
#sessionList li button:last-child:hover {
  color: #b91c1c;
  transform: scale(1.1);
}

/* Empty state */
#sessionList li.empty {
  color: #6b7280;
  text-align: center;
  padding: 10px;
  font-style: italic;
}

@media screen and (min-width: 1100px) {
.format-title {
    color: var(--text-dark);
    margin-top: 1%;
    margin-bottom: 5%;
    font-family: Sora, sans-serif;
    font-size: 42px;
    font-weight: 700;
    line-height: 120%;
}

.format-box {
    width: 200px;
    height: 125px;
    border-radius: 18px;
    background: linear-gradient(135deg, #eef2f7, #00a56e);
    border: 1px solid #828c9d;
    text-align: center;
    font-weight: 800;
    color: #000000;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
	margin: 1%;
}

.format-boxes {
display: ruby-text;}
}
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
  <a href="index.php" class="logo"> 
  <img src="./uploads/speedlaw.png" alt="speedlaw logo">
      </a>
  <nav class="sidebar-nav">
  <a href="index.php" class="sidebar-link">🏠 Dashboard</a>
    <a href="qa.php" class="sidebar-link">💬 Ask Questions</a>
  <a href="draft.php" class="sidebar-link">📄 Drafting</a>
    <a href="premium.php" class="sidebar-link">⭐ Go Premium</a>
    <div id="caseSection" style="margin-top:8px;">
    <a href="case-analysis.php">  <button onclick="startNewCase()" 
              style="width:100%; padding:8px; margin-bottom:10px; border:none; 
                     border-radius:6px; background:#10b981; color:#fff; font-weight:600; cursor:pointer;">
        ➕ Create New Case
      </button></a>

      <h4 style="font-size:14px; color:#374151;">📑 Case History</h4>
      <ul id="sessionList" 
          style="list-style:none; padding:0px 8px; margin:0; max-height:40vh; overflow-y:auto; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px;">
      </ul>
    </div>
 <?php
if (isset($_SESSION['user_id'])) {
} else {
    // If user is not logged in
    echo '<a href="gettoken.php" class="sidebar-link">🔑 Login</a>';
}
?>

</nav>
<br>
  </div>

  <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

  <div class="main">
    <div class="header-bar">
      <button class="menu-btn" onclick="toggleSidebar()">☰</button>
      <div class="chat-header-title" onclick="window.location.href='index.php'">Law AI</div>
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
document.addEventListener("DOMContentLoaded", () => {
  loadSessionsList();

  // 🔁 Auto-refresh every second (always active now)
  setInterval(loadSessionsList, 1000);
});

// 🚀 Start new case
async function startNewCase() {
  let res = await fetch("start-session.php");
  let data = await res.json();
  if (data.session_id){
    window.location.href = "case-analysis.php?session_id=" + data.session_id;
  } else {
    window.location.href = "gettoken.php"; // if no session
  }
}

// 🚀 Load old cases (with delete button)
async function loadSessionsList() {
  let res = await fetch("load-sessions.php");
  let sessions = await res.json();

  let sessionList = document.getElementById("sessionList");
  sessionList.innerHTML = "";

  if (sessions.length === 0) {
    sessionList.innerHTML = '<li style="padding:8px; color:#6b7280;">No cases yet</li>';
    return;
  }
  sessions.forEach(sess => {
    let li = document.createElement("li");
    li.style.display = "flex";
    li.style.justifyContent = "space-between";
    li.style.alignItems = "center";

    // Case button
    let btn = document.createElement("button");
    btn.textContent = "💼 " + (sess.title ? sess.title : "Case " + sess.id);
    btn.style = "flex:1; text-align:left; border:none; border-radius:6px; cursor:pointer; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin:0; padding:6px 8px;";
    btn.onclick = () => {
      window.location.href = "chat-AI.php?session_id=" + sess.id;
    };

    // Delete button
    let delBtn = document.createElement("button");
    delBtn.textContent = "🗑️";
    delBtn.className = "delete-btn";
    delBtn.style = "border:none; background:none; cursor:pointer; color:#d11a2a; font-size:15px;";
    delBtn.onclick = async (e) => {
      e.stopPropagation();
      if (confirm("Delete this case?")) {
        const res = await fetch(`delete-session.php?id=${sess.id}`, { method: "POST" });
        const data = await res.json();
        if (data.success) {
          li.remove();
          window.location.href = "case-analysis.php";
        } else {
          alert("❌ Failed to delete case");
        }
      }
    };
	// Rename button
let renameBtn = document.createElement("button");
renameBtn.textContent = "✏️";
renameBtn.style = "border:none; background:none; cursor:pointer; color:#2563eb; font-size:15px;";
renameBtn.onclick = async (e) => {
  e.stopPropagation();
  const newName = prompt("Enter new case name:", sess.title || ("Case " + sess.id));
  if (newName && newName.trim() !== "") {
    const res = await fetch("rename-session.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${sess.id}&title=${encodeURIComponent(newName)}`
    });
    const data = await res.json();
    if (data.success) {
      btn.textContent = "💼 " + newName;
    } else {
      alert("❌ Failed to rename case");
    }
  }
};


    li.appendChild(btn);
	li.appendChild(renameBtn);
    li.appendChild(delBtn);
    sessionList.appendChild(li);
  });
}
// Admin menu toggle function
function toggleAdminMenu(event) {
    event.stopPropagation();
    const adminMenu = document.getElementById('adminMenu');
    const isVisible = adminMenu.style.display === 'block';
    
    // Close all other menus first
    document.querySelectorAll('.admin-menu').forEach(menu => {
        menu.style.display = 'none';
    });
    
    // Toggle current menu
    adminMenu.style.display = isVisible ? 'none' : 'block';
}

// Close admin menu when clicking elsewhere
document.addEventListener('click', function() {
    document.querySelectorAll('.admin-menu').forEach(menu => {
        menu.style.display = 'none';
    });
});

// Prevent menu from closing when clicking inside it
document.querySelectorAll('.admin-menu').forEach(menu => {
    menu.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});

// Sidebar functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
}

// Admin menu functions
function openSettings() {
    window.location.href = 'account.php'; // Change to your account/settings page
}

function logout() {
    // You can use SweetAlert for a better confirmation
    Swal.fire({
        title: 'Logout?',
        text: 'Are you sure you want to logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4a67d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php'; // Change to your logout script
        }
    });
}

function login() {
    window.location.href = 'gettoken.php';
}

// Close sidebar when clicking on a link (for mobile)
document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            closeSidebar();
        }
    });
});
</script>
