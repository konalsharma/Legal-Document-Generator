<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Speed Law - PDF to Text Converter</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <!-- PDF.js for text extraction -->
  <script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
  <!-- Tesseract.js for OCR (image-based PDFs) -->
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #4a67d6;
      --primary-dark: #3b54b4;
      --text-dark: #333;
      --text-light: #fff;
      --background: #f8f9fc;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: var(--background);
    }

    .sidebar {
      width: 260px;
      background-color: #fff;
      border-right: 1px solid #e0e0e0;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 100;
      display: flex;
      flex-direction: column;
      padding: 20px;
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

    .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 10px;
    }

    .sidebar-link {
      background: #e6e9f8;
      padding: 12px 16px;
      border-radius: 8px;
      text-decoration: none;
      color: var(--text-dark);
      font-weight: 500;
      transition: background 0.2s ease;
    }

    .sidebar-link:hover {
      background: #d8ddf9;
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

    .main {
      margin-left: 260px;
      transition: margin-left 0.3s ease;
    }

    .header-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 20px;
      border-bottom: 1px solid #e0e0e0;
      background-color: #fff;
    }

    .chat-header-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--primary);
    }

    .menu-btn,
    .admin-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--primary);
    }

    .admin-menu {
      display: none;
      position: absolute;
      top: 50px;
      right: 20px;
      background: #444;
      border: 1px solid #222;
      border-radius: 4px;
      padding: 5px 0;
      z-index: 999;
    }

    .admin-menu button {
      display: block;
      width: 100%;
      background: none;
      border: none;
      color: white;
      padding: 8px 16px;
      text-align: left;
      cursor: pointer;
    }

    .admin-menu button:hover {
      background: #555;
    }

    .content {
      display: flex;
      gap: 20px;
      padding: 20px;
      flex-wrap: wrap;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.03);
      flex: 1 1 300px;
      min-width: 280px;
    }

    .dashed-box {
      border: 2px dashed #ccc;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      color: #666;
      cursor: pointer;
      transition: border-color 0.3s;
    }

    .dashed-box:hover {
      border-color: #4a67d6;
    }

    .button {
      width: 100%;
      padding: 12px;
      background-color: var(--primary);
      color: var(--text-light);
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.2s ease;
    }

    .button:hover {
      background-color: var(--primary-dark);
    }

    .message {
      margin-bottom: 15px;
      padding: 12px;
      border-radius: 8px;
      background: #f0f0f8;
      word-wrap: break-word;
    }

    .message.user {
      background: #e6e9f8;
    }

    .message.bot {
      background: #d8ddf9;
    }

    .image-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 8px;
    }

    .image-grid img {
      max-width: 80px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .image-results {
      margin-top: 10px;
    }

.progress-wrapper {
  width: 100%;
  background: #f0f0f0;
  border-radius: 6px;
  height: 14px;
  margin-top: 10px;
  overflow: hidden;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.progress-bar {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #4a67d6, #6e8eff);
  transition: width 0.3s ease;
  border-radius: 6px;
}

    .loading-spinner {
      border: 4px solid rgba(0, 0, 0, 0.1);
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border-left-color: var(--primary);
      animation: spin 1s linear infinite;
      margin: 10px auto;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .sidebar-overlay.active {
        display: block;
      }
      .main {
        margin-left: 0;
      }
    }
	@media (min-width: 768px) {
	.first {
		max-width: 40%;
	}}
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
  max-height: 400px;
  overflow-y: auto;
  font-size: 15px;
  line-height: 1.6;
  white-space: pre-wrap;
}

/* Chat Container */
#chatMessages {
  padding: 20px;
  max-width: 800px;
  margin: auto;
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
	width: 100%;
  background-color: #e7f3ff;
  align-self: flex-start;
  border-left: 5px solid #2196f3;
}

.message.user {
  background-color: #d1fae5;
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
.chat-box {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    #chatMessages {
      flex: 1;
      overflow-y: auto;
      margin-bottom: 10px;
    }

    textarea {
      width: 100%;
      height: 60px;
      padding: 10px;
    }

    button {
      margin-top: 10px;
    }
div#summaryText {
    max-height: 440px;
    overflow-y: auto;
}
.chat-box {
	max-height: 460px;
    overflow-y: auto;
}

  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <h2>Chats</h2>
    <button onclick="startNewChat()">➕ New Chat</button>
    <nav class="sidebar-nav">
      <a href="index.php" class="sidebar-link">🏠 Dashboard</a>
      <a href="draft.php" class="sidebar-link">📄 Drafting</a>
      <a href="qa.php" class="sidebar-link">💬 Legal Q&A</a>
      <a href="case-analysis.php" class="sidebar-link">🗂️ Case Analysis</a>
      <a href="account.php" class="sidebar-link">👤 My Account</a>
    </nav>
  </div>
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
  <div class="main">
    <div class="header-bar">
      <button class="menu-btn" onclick="toggleSidebar()">☰</button>
      <div class="chat-header-title">Speed Law</div>
      <button class="admin-btn" onclick="toggleAdminMenu(event)">⚙️</button>
      <div id="adminMenu" class="admin-menu">
        <button onclick="openSettings()">Settings</button>
        <button onclick="logout()">Logout</button>
      </div>
    </div>
	 <div class="content">
      <div class="card">
        <h2>📄 AI Summary</h2>
		<br>
         <div class="summary-box" id="summaryText">
          Loading summary...
          </div>
      </div>

      <div class="card">
        <h2>💬 Chat</h2>
        <div class="chat-box">
        <div id="chatMessages">
        <div class="message bot">Hi! You can ask follow-up questions here.</div>
        </div>
        <textarea id="chatInput" placeholder="Type a message..."></textarea>
        <button onclick="sendMessage()">Send</button>
       </div>
      </div>
    </div>
  </div>
<script>
  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js';

  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('sidebarOverlay').classList.toggle('active');
  }

  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('active');
    document.getElementById('sidebarOverlay').classList.remove('active');
  }

  function toggleAdminMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('adminMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    document.addEventListener('click', function hideMenu(e) {
      if (!menu.contains(e.target)) {
        menu.style.display = 'none';
        document.removeEventListener('click', hideMenu);
      }
    });
  }

  // ---------- SAVE & LOAD ----------
  function saveChatHistory() {
    sessionStorage.setItem("chatHistory", document.getElementById('chatMessages').innerHTML);
    sessionStorage.setItem("summaryText", document.getElementById('summaryText').innerText);
  }

  function loadChatHistory() {
    const savedSummary = sessionStorage.getItem('summaryText');
    if (savedSummary) {
      document.getElementById('summaryText').innerText = savedSummary;
    }

    const savedChat = sessionStorage.getItem('chatHistory');
    if (savedChat) {
      document.getElementById('chatMessages').innerHTML = savedChat;
    }
  }

  // ---------- SEND MESSAGE ----------
  async function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    if (!message) return;

    const chatBox = document.getElementById('chatMessages');

    // Show user's message
    const userMsg = document.createElement('div');
    userMsg.className = 'message user';
    userMsg.textContent = message;
    chatBox.appendChild(userMsg);
    input.value = '';
    saveChatHistory();

    // Show loading indicator
    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'message bot';
    loadingMsg.textContent = 'Assistant is typing...';
    chatBox.appendChild(loadingMsg);
    chatBox.scrollTop = chatBox.scrollHeight;

    try {
      const summaryText = document.getElementById('summaryText').innerText.trim();

      const response = await fetch('chat-api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          summary: summaryText,
          question: message
        })
      });

      const data = await response.json();
      const aiReply = data.reply || '⚠️ AI did not respond.';

      // Replace loading with actual response
      loadingMsg.textContent = aiReply;
      chatBox.scrollTop = chatBox.scrollHeight;

      saveChatHistory(); // ✅ save after AI responds
    } catch (error) {
      console.error(error);
      loadingMsg.textContent = '⚠️ Error contacting AI.';
      saveChatHistory();
    }
  }

  // Load saved chats + summary on page load
  document.addEventListener("DOMContentLoaded", loadChatHistory);
</script>
</body>
</html>