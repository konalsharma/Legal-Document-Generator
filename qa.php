<?php include "include/header.php"; ?>
<style>
.message {background: #e5e7eb;color: #111827;}
.delete-btn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  color: #d11a2a;
}

.delete-btn:hover {
  color: #ff0000;
}
.message.user {
	max-width: 50%;
}
.message.assistant {
    max-width: 60%;
}
.micBtn { 
  background:#10b981; 
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-size: 0.8rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
}
.micBtn:hover { 
  background:#17a676; 
}
.micBtn.listening {
  background:#ef4444;
  animation: pulse 1.5s infinite;
}
.micBtn.listening:hover {
  background:#dc2626;
}
#sendBtn { 
  background:#10b981 !important;
  color: white;  
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.2rem;
  padding: 0 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: background 0.2s;
  }
#sendBtn:hover { 
  background:#17a676; 
  }
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}
</style>
  <div style="display: flex; flex: 1; flex-wrap: wrap; justify-content: space-around;">
  <div style="flex: 1; display: flex; flex-direction: column; background: #f0f2f5;border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);height: calc(100vh - 120px);">
    <!-- Chat messages area -->
    <div id="chatMessages" style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; padding-right: 10px;"></div>
   <div id="previewContainer" style="display:flex; gap:10px; padding:4px 12px; flex-wrap:wrap;"></div>
    <!-- Input and buttons -->
    <div style="display: flex; gap: 6px;height: 40px;">
	<label for="fileInput" style="cursor: pointer; font-size: 1.4rem;margin: 5px;">+</label>
    <input type="file" id="fileInput" accept="image/*" multiple style="display:none;" />
      <input id="userInput" type="text" placeholder="Ask your legal question..."
             style="flex: 1; padding: 14px 16px; border: 1px solid #ccc; border-radius: 8px 0 0 8px; font-size: 1rem; outline: none; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);" />

      <button id="micBtn" class="micBtn" title="Click to Speak">
        START🎤
      </button>

      <button id="sendBtn" onclick="sendMessage(document.getElementById('userInput').value)">
        ➤
      </button>
    </div>
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4"></script>
<script src="main.js"></script>
<script>
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
    }

    window.onclick = function(event) {
      if (!event.target.matches('.admin-btn')) {
        document.getElementById('adminMenu').style.display = 'none';
      }
    }

    function openSettings() {
  // Redirect to account.php page
  window.location.href = 'account.php';
}

    function login() {
      window.location.href = 'gettoken.php';
    }
	
	   function logout() {
      // In a real implementation, this would redirect to logout endpoint
      Swal.fire({
        title: 'Logout',
        text: 'Are you sure you want to logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4a67d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, logout'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect to logout page
          window.location.href = 'logout.php';
        }
      });
    }
	document.addEventListener("DOMContentLoaded", loadSessionChat);
	// --- Load Old Chat from Server ---
// --- Load Old Chat from Server ---
async function loadSessionChat() {
  try {
    const res = await fetch("get_messages.php");
    const messages = await res.json();

    chatBox.innerHTML = "";
    chatHistory = [];

    if (!messages || messages.length === 0) {
      addMessage("bot", "Hello! How can I help you?");
    } else {
      messages.forEach(msg => {
        addMessage(msg.role, msg.content);
        chatHistory.push({ role: msg.role, content: msg.content });
      });
    }
  } catch (err) {
    console.error("❌ Error loading session chat:", err);
    addMessage("bot", "Hello! How can I help you?");
  }
}
</script>
</body>
</html>