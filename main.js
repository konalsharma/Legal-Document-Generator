let isProcessing = false;
let chatHistory = [];
let chatSaved = false;
let attachedFiles = [];
let currentChatId = 0; // ✅ only declare once

const chatBox = document.getElementById('chatMessages');
const input = document.getElementById('userInput');
const sendBtn = document.getElementById('sendBtn');
const micBtn = document.getElementById('micBtn');
const fileInput = document.getElementById('fileInput');
const previewContainer = document.getElementById('previewContainer');

// --- File Upload & Preview ---
fileInput?.addEventListener('change', (event) => {
  let files = Array.from(event.target.files);
  if (!files.length) return;

  attachedFiles = files;
  previewContainer.innerHTML = '';

  files.forEach(file => {
    if (file.type.startsWith('image/')) {
      let img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.style.width = "60px";
      img.style.height = "60px";
      img.style.objectFit = "cover";
      img.style.borderRadius = "6px";
      previewContainer.appendChild(img);
    }
  });
});

// --- Send Message ---
async function sendMessage(message) {
  if ((!message.trim() && attachedFiles.length === 0) || isProcessing) return;
  input.value = "";
  previewContainer.innerHTML = "";
  isProcessing = true;
  input.disabled = sendBtn.disabled = micBtn.disabled = true;

  if (message.trim()) {
    addMessage('user', message);
    chatHistory.push({ role: 'user', content: message });
  }

  // --- OCR Image Handling ---
  let extractedText = '';
  if (attachedFiles.length > 0) {
    addMessage('user', `[📷 ${attachedFiles.length} image(s)]`);
    const processingMsg = addMessage('bot', 'Processing images...');

    let ocrResults = [];
    for (let file of attachedFiles) {
      if (file.type.startsWith('image/')) {
        const { data: { text } } = await Tesseract.recognize(file, 'eng');
        if (text.trim()) ocrResults.push(text.trim());
      }
    }

    processingMsg.remove();
    extractedText = ocrResults.join("\n\n---\n\n");
    if (extractedText) {
      chatHistory.push({ role: 'user', content: `[OCR Extracted Text]\n${extractedText}` });
    }

    previewContainer.innerHTML = '';
    attachedFiles = [];
  }

  addTyping();

  try {
    const res = await fetch('api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        message: message || ("[OCR Extracted Text]\n" + extractedText),
        chat_id: currentChatId,
        history: chatHistory
      })
    });

    const data = await res.json();
    removeTyping();
    addMessage('bot', data.reply);

    if (data.chat_id) currentChatId = data.chat_id;
    chatHistory.push({ role: 'assistant', content: data.reply });
  } catch (err) {
    removeTyping();
    addMessage('bot', '❌ Error getting response.');
    console.error(err);
  }

  isProcessing = false;
  input.disabled = sendBtn.disabled = micBtn.disabled = false;
  input.focus();
}

// --- Start New Chat ---
function startNewChat() {
  currentChatId = 0;
  chatHistory = [];
  chatBox.innerHTML = "";
}

// --- UI Helpers ---
function addMessage(sender, text) {
  if (sender === "assistant") sender = "bot";

  const div = document.createElement('div');
  div.classList.add('message', sender);

  if (sender === 'bot') div.innerHTML = marked.parse(text);
  else div.textContent = text;

  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;
  return div;
}

function addTyping() {
  const div = document.createElement('div');
  div.id = 'typing';
  div.className = 'message bot typing';
  div.textContent = 'Assistant is typing...';
  chatBox.appendChild(div);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function removeTyping() {
  const typingDiv = document.getElementById('typing');
  if (typingDiv) typingDiv.remove();
}

// --- Sample Questions ---
document.querySelectorAll('.sample-question').forEach(btn => {
  btn.addEventListener('click', () => {
    if (!isProcessing) sendMessage(btn.textContent);
  });
});

// --- Enter Key ---
input.addEventListener('keypress', e => {
  if (e.key === 'Enter' && !isProcessing) sendMessage(input.value);
});

let recognition;
let isListening = false; // track mic state

// --- Mic Button ---
micBtn.addEventListener('click', () => {
  if (window.AndroidMic) {
    // --- AndroidMic case ---
    if (!isListening) {
      micBtn.textContent = 'STOP⚪';
      micBtn.style.background = '#ef4444';
      window.AndroidMic.startListening();
      isListening = true;
    } else {
      micBtn.textContent = 'START🎤';
      micBtn.style.background = '';
      window.AndroidMic.stopListening?.(); // stopListening if Android side provides it
      isListening = false;
    }

  } else if (window.SpeechRecognition || window.webkitSpeechRecognition) {
    // --- Web Speech API case ---
    if (!recognition) {
      recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
      recognition.lang = 'en-US';
      recognition.continuous = true;  
      recognition.interimResults = false; // only final text

      recognition.onresult = (event) => {
        let transcript = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
          transcript += event.results[i][0].transcript;
        }

        // ✅ Append instead of overwrite
        if (input.value.trim() !== '') {
          input.value = input.value + ' ' + transcript;
        } else {
          input.value = transcript;
        }
      };

      recognition.onerror = (event) => {
        console.error('Mic error:', event.error);
        alert('Mic error: ' + event.error);
        micBtn.textContent = 'START🎤';
        micBtn.style.background = '';
        isListening = false;
      };

      recognition.onend = () => {
        if (isListening) {
          recognition.start(); // auto-restart if still active
        } else {
          micBtn.textContent = 'START🎤';
          micBtn.style.background = '';
        }
      };
    }

    if (!isListening) {
      recognition.start();
      micBtn.textContent = 'STOP⚪';
      micBtn.style.background = '#ef4444';
      isListening = true;
    } else {
      recognition.stop();
      micBtn.textContent = 'START🎤';
      micBtn.style.background = '';
      isListening = false;
    }

  } else {
    // --- Not supported case ---
    micBtn.disabled = true;
    micBtn.title = 'Speech Recognition not supported';
    micBtn.textContent = 'START🎤';
    micBtn.style.background = '#9ca3af';
  }
});

// --- Android callback functions ---
function onSpeechResult(text) {
  micBtn.textContent = 'START🎤';
  micBtn.style.background = '';
  if (input.value.trim() !== '') {
    input.value = input.value + ' ' + text;
  } else {
    input.value = text;
  }
  sendMessage?.(text);
  isListening = false;
}

function onSpeechError(err) {
  micBtn.textContent = 'START🎤';
  micBtn.style.background = '';
  alert("Mic error: " + err);
  isListening = false;
}

// --- Save Chat ---
function saveChatToLocalStorage() {
  if (chatHistory.length === 0) return;

  const existingChats = JSON.parse(localStorage.getItem('chatHistoryList')) || [];
  const title = chatHistory.find(msg => msg.role === 'user')?.content || 'New Chat';
  const newChat = {
    title: title.replace(/[^a-zA-Z0-9 ?!]/g, '').slice(0, 40),
    messages: [...chatHistory],
    timestamp: Date.now()
  };

  if (existingChats.length > 0 && JSON.stringify(existingChats[0].messages) === JSON.stringify(chatHistory)) return;

  if (existingChats.length > 0 && !chatSaved) existingChats.shift();

  existingChats.unshift(newChat);
  localStorage.setItem('chatHistoryList', JSON.stringify(existingChats.slice(0, 3)));
  chatSaved = true;
}

window.addEventListener('beforeunload', saveChatToLocalStorage);