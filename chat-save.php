<?php include "include/header.php"; ?>
<style>
  #chatContainer {
    display: flex;
    flex-direction: column;
  }

  .message {
	background: #e5e7eb;
	color: #111827;
    max-width: 70%;
    margin: 6px 0;
    padding: 10px;
    border-radius: 10px;
    white-space: pre-wrap;
    word-wrap: break-word;
  }

  .message.user {
    align-self: flex-end;
    background-color: #4a67d6;
    color: white;
    border-bottom-right-radius: 0;
  }

  .message.bot {
    align-self: flex-start;
    background-color: #e5e7eb;
    color: black;
    border-bottom-left-radius: 0;
  }
.chat-image { max-width:120px; max-height:90px; object-fit:cover; border-radius:8px; margin-bottom:4px; }
</style>
<div class="content">
  <div class="card">
    <h2 id="chatTitle">💬 Case Discussion</h2>

    <!-- Chat messages container -->
    <div id="chatContainer" 
         style="height: 530px; overflow-y: auto; border: 1px solid #ccc; 
                padding: 12px; border-radius: 8px; background: #f9f9f9; margin-bottom: 10px;">
    </div>
    <div id='previewContainer' style='display:flex;gap:10px;padding:8px 12px;flex-wrap:wrap;'></div>
    <!-- User input -->
    <div style="display: flex; gap: 8px;height:45px;">
	<label for='fileInput' class='upload-btn'>+
                <input type='file' id='fileInput' style='display:none;' accept='image/*' multiple>
            </label>
      <textarea id="userInput" placeholder="Query Here..."
        style="flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #ccc; resize: none;"></textarea>
      <button onclick="sendMessage()" 
        style="background: #4a67d6; color: white; font-weight: bold; 
               padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer;margin: 0;">
        Send
      </button>
    </div>
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4"></script>
<script>
let currentSessionId = null;
let chatHistory = [];
let attachedFile = null;
let isNewSession = false;
let defaultMessageSent = false; // Track if default message has been sent

function getSessionIdFromUrl() {
  let params = new URLSearchParams(window.location.search);
  return params.get("session_id");
}

//  On page load
window.onload = async function () {
  let sessionFromUrl = getSessionIdFromUrl();

  if (sessionFromUrl) {
    currentSessionId = sessionFromUrl;
    
    // Check if this is a new session by checking if there's any existing history
    let res = await fetch("load-sessions.php");
    let sessions = await res.json();
    let thisSession = sessions.find(s => s.id == currentSessionId);
    
    // If session exists but has no messages yet, it's a new session
    if (thisSession) {
      let historyRes = await fetch('load-history.php?session_id=' + currentSessionId);
      let historyData = await historyRes.json();
      isNewSession = historyData.length === 0;
    } else {
      isNewSession = true;
    }

    updateChatTitle(currentSessionId, thisSession ? thisSession.title : null);

    await loadHistory();
    await loadSessionsList();
    
    // If this is a new session and we have a document, send the default message
    if (isNewSession && !defaultMessageSent) {
      const chatDoc = sessionStorage.getItem("chatDoc");
      if (chatDoc && chatDoc.trim() !== "") {
        // Add a small delay to ensure the chat is fully loaded
        setTimeout(() => {
          sendDefaultMessage();
        }, 1000);
      }
    }
  } else {
    await startNewChat();
  }
};

//  Send default message for new sessions with documents
async function sendDefaultMessage() {
  if (defaultMessageSent) return; // Prevent sending multiple times
  
  // First, show the summary as a bot message
  const chatDoc = sessionStorage.getItem("chatDoc");
  const originalDoc = sessionStorage.getItem("originalDoc");
  
  if (chatDoc && chatDoc.trim() !== "") {
    // Show the summary first
    addMessage("bot", "✨ **Case Summary:**\n\n" + chatDoc);
  }
  
  // Mark as sent immediately to prevent duplicates
  defaultMessageSent = true;
}

document.getElementById('fileInput').addEventListener('change', function(event){
    let files = Array.from(event.target.files);
    if(!files.length) return;
    attachedFile = files;

    let previewContainer = document.getElementById('previewContainer');
    previewContainer.innerHTML = '';

    files.forEach(file => {
        if(file.type.startsWith('image/')){
            let img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'chat-image';
            previewContainer.appendChild(img);
        }
    });
});

//  Update chat title
function updateChatTitle(sessionId, title = null) {
  let chatTitle = document.getElementById("chatTitle");
  if (chatTitle) {
    if (title && title.trim() !== "") {
      chatTitle.innerText = "💬 " + title;
    } else {
      chatTitle.innerText = "💬 Case " + sessionId + " Discussion";
    }
  }
}

//  Load history for current session
async function loadHistory() {
  if (!currentSessionId) return;
  let response = await fetch('load-history.php?session_id=' + currentSessionId);
  let data = await response.json();

  document.getElementById("userInput").value = "";
  chatHistory = [];

  // Clear existing messages
  const container = document.getElementById('chatContainer');
  container.innerHTML = '';

  data.forEach(msg => {
    addMessage(msg.role, msg.content);
    chatHistory.push({ role: msg.role, content: msg.content });
  });
}

async function startNewChat() {
  let res = await fetch("start-session.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({})
  });

  let data = await res.json();
  currentSessionId = data.session_id;

  //  Show case number in title
  updateChatTitle(currentSessionId);

  // Send default message for new sessions (which now shows the summary)
  if (!defaultMessageSent) {
    setTimeout(() => {
      sendDefaultMessage();
    }, 1000);
  }
}

//  Add message to chat
function addMessage(sender, text) {
  const container = document.getElementById('chatContainer');
  const message = document.createElement('div');
  message.className = `message ${sender}`;
  message.innerText = text;
  container.appendChild(message);
  container.scrollTop = container.scrollHeight;
  return message;
}

//  Send message
async function sendMessage() {
  const input = document.getElementById('userInput');
  let text = input.value.trim();
  if (!text && (!attachedFile || attachedFile.length === 0)) return;

  // User text message
  if (text) addMessage("user", text);
  input.value = "";

  let extractedText = "";
  if (attachedFile && attachedFile.length > 0) {
    // Show previews as messages
    attachedFile.forEach(file => {
      if(file.type.startsWith("image/")){
        addMessage("user", "🖼️ Image: " + file.name);
      }
    });
	document.getElementById("previewContainer").innerHTML = "";
    let ocrResults = [];

    for (let file of attachedFile) {
      if (file.type.startsWith("image/")) {
        const { data: { text: ocrText } } = await Tesseract.recognize(file, 'eng');
        if (ocrText.trim()) ocrResults.push(ocrText.trim());
      }
    }

    extractedText = ocrResults.join("\n\n---\n\n");
    attachedFile = null;
  }

  // Send to backend
  const typingMsg = addMessage("bot", "Typing...");

  try {
    const response = await fetch("extractchat-api.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        session_id: currentSessionId,
        message: text || ("[OCR Extracted Text]\n" + extractedText),
        document: sessionStorage.getItem('chatDoc') || ""
      })
    });

    const data = await response.json();
    typingMsg.innerText = data.reply || "⚠️ No reply received.";
  } catch (e) {
    typingMsg.innerText = "⚠️ Error getting response.";
    console.error(e);
  }
}

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
</script>
</body>
</html>