<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>Voice Chat with ChatGPT</title>
  <style>
    body { font-family: Arial; padding: 20px; background: #f9fafb; }
    #micBtn {
      border: none;
      background: #6b7280;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    #micBtn:hover {
      background: #4b5563;
    }
    #micBtn.listening {
      background: #ef4444;
      animation: pulse 1.5s infinite;
    }
    #micBtn.listening:hover {
      background: #dc2626;
    }
    #chatBox {
      width: 100%;
      height: 300px;
      margin-top: 20px;
      font-size: 16px;
      padding: 12px;
      resize: vertical;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      background: white;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 style="color: #1f2937; margin-bottom: 20px;">🎤 Voice Chat with ChatGPT</h2>
    <button id='micBtn'>
      <span>START🎤</span>
      <span>Click to Speak</span>
    </button>
    <textarea id='chatBox' readonly placeholder="Your conversation will appear here..."></textarea>
  </div>

  <script>
    const micBtn = document.getElementById('micBtn');
    const chatBox = document.getElementById('chatBox');

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      alert('Your browser does not support Speech Recognition. Use Chrome.');
      micBtn.disabled = true;
      micBtn.style.background = '#9ca3af';
    }

    const recognition = new SpeechRecognition();
    recognition.lang = 'en-US';
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    micBtn.addEventListener('click', () => {
      recognition.start();
      micBtn.innerHTML = '<span>STOP⚪</span><span>Listening...</span>';
      micBtn.classList.add('listening');
    });

    recognition.onresult = async (event) => {
      const transcript = event.results[0][0].transcript;
      chatBox.value += '🧑 You: ' + transcript + '\n';
      micBtn.innerHTML = '<span>START🎤</span><span>Click to Speak</span>';
      micBtn.classList.remove('listening');

      try {
        const res = await fetch('micchat.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: transcript })
        });

        const data = await res.json();
        const reply = data.reply || 'No response from backend';

        chatBox.value += '🤖 ChatGPT: ' + reply + '\n\n';
        chatBox.scrollTop = chatBox.scrollHeight;
      } catch (err) {
        chatBox.value += '⚠️ Error contacting backend: ' + err.message + '\n';
        console.error(err);
      }
    };

    recognition.onerror = (event) => {
      console.error('Mic error:', event.error);
      micBtn.innerHTML = '<span>START🎤</span><span>Click to Speak</span>';
      micBtn.classList.remove('listening');
      chatBox.value += '⚠️ Microphone error: ' + event.error + '\n';
    };

    recognition.onend = () => {
      micBtn.innerHTML = '<span>START🎤</span><span>Click to Speak</span>';
      micBtn.classList.remove('listening');
    };
  </script>
</body>
</html>