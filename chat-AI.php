<?php include 'include/header.php'; ?>
<?php
$userQuery = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $userQuery = trim($_POST['query']);
}
?>
<style>
.chat-container { display:flex; flex-direction:column; height:calc(100vh - 120px); border:1px solid #e5e7eb; border-radius:12px; background:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.05); overflow:hidden; }
.chat-box { flex:1; padding:16px; overflow-y:auto; background:#f9fafb; }
.message { max-width:75%; margin-bottom:14px; padding:12px 16px; border-radius:12px; line-height:1.4; font-size:15px; background: #e5e7eb;color: #111827;}
.message.user { background:#10b981; color:#fff; margin-left:auto; border-bottom-right-radius:4px; }
.message.bot { background:#e5e7eb; color:#111827; text-align:left; width:fit-content; margin-right:auto; border-bottom-left-radius:4px; }
.chat-input { display:flex; align-items:center; padding:12px; border-top:1px solid #e5e7eb; background:#fff; gap:10px; }
.chat-input input { flex:1; padding:12px; border:1px solid #d1d5db; border-radius:8px; outline:none; font-size:14px; }
.chat-input button { padding:10px 14px; border:none; border-radius:8px; background:#10b981; color:#fff; font-weight:600; cursor:pointer; transition:background .3s; }
.chat-input button:hover { background:#059669; }
.upload-btn { display:flex; align-items:center; justify-content:center; font-size:20px; cursor:pointer; padding:10px; border-radius:8px; background:#f3f4f6; transition:background .3s; }
.upload-btn:hover { background:#e5e7eb; }
.mic-btn { background:#10b981; display:flex; align-items:center; justify-content:center; padding:10px; transition:all 0.3s ease; }
.mic-btn:hover { background:#4b5563; }
.mic-btn.listening { background:#ef4444; animation:pulse 1.5s infinite; }
.mic-btn.listening:hover { background:#dc2626; }
.chat-image { max-width:120px; max-height:90px; object-fit:cover; border-radius:8px; margin-bottom:4px; }
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}
</style>
<div class='chat-container'>
<h2 id="chatTitle" style="margin-bottom:12px; font-weight:bold; color:#1f2937;">
    </h2>
    <div id='chatBox' class='chat-box'></div>
    <div class='chat-input-wrapper' style='display:flex;flex-direction:column;width:100%;'>
        <div id='previewContainer' style='display:flex;gap:10px;padding:8px 12px;flex-wrap:wrap;'></div>
        <div class='chat-input'>
            <label for='fileInput' class='upload-btn'>📎
                <input type='file' id='fileInput' style='display:none;' accept='image/*' multiple>
            </label>
            <input type='text' id='userInput' placeholder='Ask anything...' 
       value="<?php echo htmlspecialchars($userQuery); ?>" 
       onkeydown='if(event.key==="Enter"){sendMessage();}'>
            <button id="micBtn" class='mic-btn' onclick='startListening()' title="Click to speak">START🎤</button>
            <button onclick='sendMessage()'>➤</button>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/tesseract.js@4'></script>
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
	
let attachedFile = null;
let chatHistory = [];
let currentSessionId = null;  // <-- session id

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

function addMessage(content, sender, type) {
    let chatBox = document.getElementById('chatBox');
    let msg = document.createElement('div');
    msg.classList.add('message', sender);

    if (type === 'image') {
        let img = document.createElement('img');
        img.src = content;
        img.className = 'chat-image';
        msg.appendChild(img);
    } else {
        msg.innerText = content;
    }

    const isAtBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 50;

    chatBox.appendChild(msg);

    if (isAtBottom) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    return msg;
}

// 🚀 Start a new chat session
async function startNewChat(){
    let res = await fetch("start-session.php");
    let data = await res.json();
    if(data.session_id){
        currentSessionId = data.session_id;
        chatHistory = [];
        document.getElementById("chatBox").innerHTML = "";
    } else {
         window.location.href = "gettoken.php";
    }
}

// 🚀 Load history for current session
async function loadHistory(){
    if(!currentSessionId) return;
    let response = await fetch('load-history.php?session_id='+currentSessionId);
    let data = await response.json();

    document.getElementById("chatBox").innerHTML = "";
    chatHistory = [];

    data.forEach(msg => {
        addMessage(msg.content, msg.role);
        chatHistory.push({role: msg.role, content: msg.content});
    });
}

// 🚀 Send message (text or image)
async function sendMessage(){
    let input = document.getElementById('userInput');
    let userText = input.value.trim();
    if(!userText && (!attachedFile || attachedFile.length === 0)) return;
    if(!currentSessionId){  window.location.href = "gettoken.php"; }

    input.value = '';
    if(userText){
        addMessage(userText,'user');
        chatHistory.push({role:'user', content:userText});
    }

    let extractedText = '';
    if(attachedFile && attachedFile.length > 0){
        for(let file of attachedFile){
            if(file.type.startsWith('image/')){
                addMessage(URL.createObjectURL(file), 'user', 'image');
            }
        }
        document.getElementById('previewContainer').innerHTML='';
        const processingMsg = addMessage("Processing images...", 'bot');
        let ocrResults = [];
        for(let file of attachedFile){
            if(file.type.startsWith('image/')){
                const { data: { text } } = await Tesseract.recognize(file, 'eng');
                if(text.trim()) ocrResults.push(text.trim());
            }
        }
        extractedText = ocrResults.join("\n\n---\n\n");
        processingMsg.remove();
        chatHistory.push({role:'user', content:`[OCR Extracted Text]\n${extractedText}`});
        var loader = addMessage("wait...", 'bot');
    } else {
        var loader = addMessage("Typing...", 'bot');
    }

    try{
        let response = await fetch('chat-backend.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({ session_id: currentSessionId, messages: chatHistory })
        });
        let data = await response.json();
        loader.innerText = data.reply || "No reply returned.";
        chatHistory.push({role:'assistant', content: loader.innerText});
    }catch(e){
        loader.innerText = "Error connecting to server.";
        console.error(e);
    }
    attachedFile = null;
    input.value = '';
}

let recognition;
let isListening = false;

function startListening() {
  const input = document.getElementById('userInput');
  const micBtn = document.getElementById('micBtn');

  if (!('webkitSpeechRecognition' in window || 'SpeechRecognition' in window)) {
    alert('Your browser does not support speech recognition.');
    return;
  }

  if (!recognition) {
    recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = 'en-US';
    recognition.continuous = true;   // keep listening until manually stopped
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onresult = function (e) {
      let transcript = '';
      for (let i = e.resultIndex; i < e.results.length; i++) {
        transcript += e.results[i][0].transcript;
      }

      // ✅ Append text instead of overwrite
      if (input.value.trim() !== '') {
        input.value += ' ' + transcript;
      } else {
        input.value = transcript;
      }
    };

    recognition.onerror = function (e) {
      console.error('Speech error:', e.error);
      micBtn.textContent = 'START🎤';
      micBtn.classList.remove('listening');
      isListening = false;
    };

    recognition.onend = function () {
      if (isListening) {
        recognition.start(); // 🔁 auto-restart if still listening
      } else {
        micBtn.textContent = 'START🎤';
        micBtn.classList.remove('listening');
      }
    };
  }

  if (!isListening) {
    recognition.start();
    micBtn.textContent = 'STOP⚪';
    micBtn.classList.add('listening');
    isListening = true;
  } else {
    recognition.stop();
    micBtn.textContent = 'START🎤';
    micBtn.classList.remove('listening');
    isListening = false;
  }
}

// 🚀 Get session_id from URL if available
function getSessionIdFromUrl(){
    let params = new URLSearchParams(window.location.search);
    return params.get("session_id");
}

// 🚀 On load: start new chat
window.onload = async function () {
    let sessionFromUrl = getSessionIdFromUrl();

    if (sessionFromUrl) {
        currentSessionId = sessionFromUrl;

        // Fetch this session's title
        let res = await fetch("load-sessions.php");
        let sessions = await res.json();
        let thisSession = sessions.find(s => s.id == currentSessionId);

        // Update title with custom session title (if any)
        updateChatTitle(currentSessionId, thisSession ? thisSession.title : null);

        await loadHistory();
        await loadSessionsList();
    } else {
        await startNewChat();

        // New chat -> show default title
        updateChatTitle(currentSessionId, null);

        await loadSessionsList();
    }

    // 🚀 Send the query from the previous page immediately
    <?php if (!empty($userQuery)): ?>
        document.getElementById('userInput').value = <?php echo json_encode($userQuery); ?>;
        sendMessage(); // automatically sends it
    <?php endif; ?>
};

function updateChatTitle(sessionId, sessionTitle = null) {
    let chatTitle = document.getElementById("chatTitle");
    if (chatTitle) {
        if (sessionTitle && sessionTitle.trim() !== "") {
            chatTitle.innerText = "💬 " + sessionTitle;
        } else {
            chatTitle.innerText = "💬 Case " + sessionId + " Discussion";
        }
    }
}

// 🚀 Open existing session
async function openSession(sessionId){
    currentSessionId = sessionId;
    chatHistory = [];
    document.getElementById('chatBox').innerHTML = '';
    await loadHistory();
    await loadSessionsList(); // refresh highlight
}
</script>
</body>
</html>