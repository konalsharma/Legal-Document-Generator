<?php include "include/header.php"; ?>
<style>
/* Spinner animation */
.spinner {
  width: 22px;
  height: 22px;
  border: 3px solid #e5e7eb;
  border-top: 3px solid #4a67d6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

/* Smooth rotation */
@keyframes spin {
  to { transform: rotate(360deg); }
}
/* Add this to your CSS */
.progress-bar {
  background: linear-gradient(90deg, #4a67d6, #6d83f2);
  background-size: 200% 100%;
  animation: progress-stripes 2s linear infinite;
}

@keyframes progress-stripes {
  from { background-position: 200% 0; }
  to { background-position: -200% 0; }
}

/* Premium popup styling */
.premium-popup {
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.premium-popup .swal2-title {
  color: #1f2937;
  font-size: 1.5rem;
}
</style>
    <div class="content">
      <div class="card" id="firstcase" 
     style="max-width: 650px; margin: 20px auto; padding: 24px; background: #fff; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
  <!-- Upload Box -->
  <div id="dashedBox" style="text-align: center; cursor: pointer; transition: all 0.3s;">
   <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
    📂 Upload Legal Document
  </h3>
    <label for="fileInput" class="custom-upload-btn"
       style="display: flex; flex-direction: column; align-items: center; 
              cursor: pointer; border: 2px dashed #4a67d6; 
              border-radius: 10px; transition: all 0.3s ease; margin: auto;">
  <svg xmlns="http://www.w3.org/2000/svg" class="icon" id="uploadIcon" viewBox="0 0 24 24" fill="none" 
       style="width: 30px; height: 30px; margin-bottom: 6px; stroke: #4a67d6;">
    <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5 5 5M12 15V5" 
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <span style="font-size: 0.9rem; font-weight: 600; color: #4a67d6;">Click To Upload File</span>
  <span style="font-size: 0.75rem; color: #6b7280; margin-top: 3px;">PDF, DOCX, JPG, PNG</span>
  <input type="file" id="fileInput" style="display: none;" multiple accept=".pdf,.docx,image/*">
</label>
<!-- Preview -->
  <div id="imagePreviewContainer" class="image-grid" style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px;"></div>
  </div>

  <!-- Examples -->
  <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px;">
    <h4 style="font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 10px;">
      ✅ Examples of supported documents:
    </h4>
    <ul style="font-size: 0.9rem; color: #4b5563; line-height: 1.6; padding-left: 20px;">
      <li>⚖️ Court Summons</li>
      <li>📜 Legal Notices</li>
      <li>📝 Contracts & Agreements</li>
      <li>📑 Property Documents</li>
    </ul>
  </div>

  <!-- Extract Button -->
  <div id="extractContainer" style="margin-top: 24px;">
    <button id="extractBtn" 
        style="width: 100%; background: #4a67d6; color: #fff; font-weight: 600; font-size: 1rem; padding: 12px; border: none; border-radius: 10px; cursor: pointer; transition: background 0.3s;">
  🚀 Extract Text
</button>
  </div><br>
  <h3 style="color: #1f2937;text-align: center;">OR</h3>
  <div style="max-width: 650px; margin: 20px auto;">
<div style="margin-top: 20px;">
  <form id="chatForm" action="chat-AI.php" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">
  <!-- Textarea -->
  <textarea name="query" id="queryTextarea" placeholder="Type Your Query Here?"
            style="width:100%; min-height:100px; padding:12px; border:1px solid #d1d5db; border-radius:10px; font-size:0.95rem; resize:vertical;"></textarea>

  <!-- Submit Button -->
  <button type="submit"
          style="width:100%; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-weight:700; font-size:1rem; padding:14px 18px; border:none; border-radius:12px; cursor:pointer; transition:all 0.3s ease; box-shadow:0 4px 10px rgba(16,185,129,0.25);"
          onmouseover="this.style.background='linear-gradient(135deg,#34d399,#059669)'; this.style.transform='translateY(-2px)'"
          onmouseout="this.style.background='linear-gradient(135deg,#10b981,#059669)'; this.style.transform='translateY(0)'">
    🚀 Start Chat with AI Agent
  </button>
</form>
</div>
</div>
<div id="loginPopup" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#f0f6ff; border-radius:8px; padding:12px 16px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
  <span>Sign in to Chat with AI Agent</span>
  <button onclick="window.location.href='gettoken.php'" 
          style="margin-left:10px; background:#1a73e8; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer;">Sign in</button>
</div>
</div>

      <div class="card" id="first" 
     style="display:none; max-width: 750px; margin: 30px auto; padding: 24px; 
            background: #ffffff; border-radius: 18px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);">

  <!-- Header -->
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
    <h3 style="font-size:1.3rem; font-weight:700; color:#1f2937; margin:0; display:flex; align-items:center; gap:8px;">
      📄 Extracted Text
    </h3>
  </div>

  <!-- Spinner -->
  <div id="loadingSpinner" class="spinner-container" 
       style="display:none; text-align:center; margin: 20px 0;">
    <div class="spinner" style="width:28px;height:28px;border:3px solid #ddd;border-top:3px solid #4a67d6; border-radius:50%;margin:auto;animation:spin 1s linear infinite;"></div>
    <p style="margin-top:10px; color:#6b7280; font-size:0.9rem;">Summarizing...</p>
  </div>

  <!-- Messages Container -->
  <div id="chatMessages" 
       style="border: 1px solid #e5e7eb; 
              border-radius: 12px; padding: 16px; background: #f9fafb;
              font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 0.95rem; line-height:1.5;">
    <div class="message bot" 
         style="padding:12px; background:#eef2ff; border-radius:12px; color:#374151; font-weight:500;">
      Upload documents to generate a summarized overview.
    </div>
  </div>
</div>
</div>
</div>
  <?php
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<script>
  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js';

const chatForm = document.getElementById('chatForm');
const queryTextarea = document.getElementById('queryTextarea');

chatForm.addEventListener('submit', async function (e) {
  e.preventDefault(); // stop default submit first

 if (queryTextarea.value.trim() === "") {
  Swal.fire({
    toast: true,
    icon: "warning",
    title: "Please type your query first!",
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
  });
  queryTextarea.focus();
  return;
}

  try {
    // Check session limit first
    const res = await fetch("get-new-session.php");
    const data = await res.json();

    if (data.error === "session_limit_reached") {
      showPremiumPopup(data.current_sessions, data.session_limit);
      return;
    }

    if (data.next_session_id) {
      chatForm.action = "chat-AI.php?session_id=" + data.next_session_id;
      chatForm.submit(); // ✅ finally submit
    } else {
      document.getElementById("loginPopup").style.display = "block";
      setTimeout(() => {
        document.getElementById("loginPopup").style.display = "none";
      }, 3000);
    }
  } catch (err) {
    console.error("Error getting session ID:", err);
    alert("Error creating session.");
  }
});

// Premium Popup Function
function showPremiumPopup(currentSessions, sessionLimit) {
    Swal.fire({
        title: '🚀 Upgrade to Premium',
        html: `You've used <strong>${currentSessions}</strong> out of <strong>${sessionLimit}</strong> free sessions.<br><br>
               Upgrade to premium for unlimited sessions and advanced features!`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Upgrade Now',
        cancelButtonText: 'Maybe Later',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        showDenyButton: false,
        customClass: {
            popup: 'premium-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to premium page
            window.location.href = 'premium.php';
        } else if (result.isDenied) {
            // Redirect to sessions page
            window.location.href = 'case-analysis.php';
        }
    });
}

// Session Limit Check Function
async function checkSessionLimit() {
  try {
    const res = await fetch("get-new-session.php");
    const data = await res.json();
    
    if (data.error === "session_limit_reached") {
      showPremiumPopup(data.current_sessions, data.session_limit);
      return false;
    }
    return true;
  } catch (error) {
    console.error("Error checking session limit:", error);
    return true; // Allow proceeding if there's an error
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
  document.getElementById('first').style.display = 'none'; 
  const previewContainer = document.getElementById('imagePreviewContainer');
  let uploadedImages = [];
  let selectedFiles = [];
  
  const extractBtn = document.getElementById('extractBtn');

extractBtn.addEventListener('click', function() {
  if (selectedFiles.length === 0) {
	  Swal.fire({
    toast: true,
    icon: "warning",
    title: "Please upload a legal document to extract!",
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
  });
    return; // Stop extraction if no files
  }

  // Check session limit before proceeding
  checkSessionLimit().then(canProceed => {
    if (canProceed) {
      extractText();
    }
  });
});

 document.getElementById('fileInput').addEventListener('change', function (event) {
  const files = Array.from(event.target.files);
  const isPDF = files.some(file => file.type === 'application/pdf');
  const isImage = files.some(file => file.type.startsWith('image/'));

  document.getElementById('extractContainer').style.display = 'block';
  
fetch("get-new-session.php")
  .then(res => res.json())
  .then(data => {
    if (data.next_session_id) {
      let caseHeader = document.getElementById("caseHeader");
      if (!caseHeader) {
        caseHeader = document.createElement("h3");
        caseHeader.id = "caseHeader";
        caseHeader.style.fontSize = "1.25rem";
        caseHeader.style.fontWeight = "700";
        caseHeader.style.color = "#2563eb"; // blue
        caseHeader.style.marginBottom = "12px";
        document.getElementById("dashedBox").prepend(caseHeader);
      }
      caseHeader.textContent = "📑 CASE " + data.next_session_id;
    }
  });

  if (isPDF) {
    // Remove all images if any
    const hasImage = selectedFiles.some(f => f.type.startsWith('image/'));
    if (hasImage) {
      uploadedImages = [];
      selectedFiles = selectedFiles.filter(f => !f.type.startsWith('image/'));
      previewContainer.innerHTML = '';
    }

    files.forEach(pdfFile => {
      if (pdfFile.type === 'application/pdf') {
        const alreadySelected = selectedFiles.some(f => f.name === pdfFile.name && f.type === pdfFile.type);
        if (alreadySelected) {
          alert(`File "${pdfFile.name}" is already selected`);
          return;
        }

        selectedFiles.push(pdfFile);

        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.display = 'inline-block';

        const icon = document.createElement('img');
        icon.src = 'https://cdn-icons-png.flaticon.com/512/337/337946.png';
        icon.alt = 'PDF';
        icon.style.width = '60px';
        icon.style.height = '60px';
        icon.style.borderRadius = '6px';
        icon.style.marginRight = '6px';

        const removeBtn = document.createElement('span');
        removeBtn.textContent = '✕';
        removeBtn.style.cssText = `
          position: absolute;
          top: -6px;
          background: #dc2626;
          color: white;
          font-size: 12px;
          border-radius: 50%;
          width: 18px;
          height: 18px;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
        `;

        removeBtn.addEventListener('click', () => {
          wrapper.remove();
          selectedFiles = selectedFiles.filter(f => f.name !== pdfFile.name);
          if (selectedFiles.length === 0) {
            document.getElementById('extractContainer').style.display = 'none';
          }
        });

        wrapper.appendChild(icon);
        wrapper.appendChild(removeBtn);
        previewContainer.appendChild(wrapper);
      }
    });

  } else if (isImage) {
    // Remove all PDFs if any
    const hasPDF = selectedFiles.some(f => f.type === 'application/pdf');
    if (hasPDF) {
      selectedFiles = selectedFiles.filter(f => f.type !== 'application/pdf');
      uploadedImages = [];
      previewContainer.innerHTML = '';
    }

    files.forEach(file => {
      if (file.type.startsWith('image/')) {
        const alreadySelected = selectedFiles.some(f => f.name === file.name && f.type === file.type);
        if (alreadySelected) {
          alert(`Image "${file.name}" is already selected`);
          return;
        }

        selectedFiles.push(file);

        const reader = new FileReader();
        reader.onload = function (e) {
          const wrapper = document.createElement('div');
          wrapper.style.position = 'relative';
          wrapper.style.display = 'inline-block';

          const img = document.createElement('img');
          img.src = e.target.result;
          img.style.maxWidth = '80px';
          img.style.borderRadius = '6px';
          img.style.cursor = 'pointer';
          img.style.marginRight = '6px';
          img.addEventListener('click', () => showFullscreenImage(e.target.result));

          const removeBtn = document.createElement('span');
          removeBtn.textContent = '✕';
          removeBtn.style.cssText = `
            position: absolute;
            top: -6px;
            background: #dc2626;
            color: white;
            font-size: 12px;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
          `;

          removeBtn.addEventListener('click', () => {
            wrapper.remove();
            uploadedImages = uploadedImages.filter(i => i !== e.target.result);
            selectedFiles = selectedFiles.filter(f => f.name !== file.name);
            if (selectedFiles.length === 0) {
              document.getElementById('extractContainer').style.display = 'none';
            }
          });

          wrapper.appendChild(img);
          wrapper.appendChild(removeBtn);
          previewContainer.appendChild(wrapper);

          uploadedImages.push(e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  event.target.value = '';
});

  function showFullscreenImage(src) {
    const modal = document.getElementById('fullscreenImageModal');
    const image = document.getElementById('fullscreenImage');
    image.src = src;
    modal.style.display = 'flex';
  }

  function closeFullscreenImage() {
    document.getElementById('fullscreenImageModal').style.display = 'none';
  }
 function extractText() {
  // Check session user_id from PHP
  let userId = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";

  if (!userId) {
    // No session → redirect
    window.location.href = "gettoken.php";
    return; // stop further execution
  }

  // Session exists → continue
  handleFileUpload();
  document.getElementById('firstcase').style.display = 'none';
  document.getElementById('first').style.display = 'block';  
}
const fileInput = document.getElementById('fileInput');
const extractContainer = document.getElementById('extractContainer');
const chatMessages = document.getElementById('chatMessages');

async function handleFileUpload() {
  if (!selectedFiles.length) return;
  const files = selectedFiles;

  chatMessages.innerHTML = '';

  const loadingMsg = document.createElement('div');
  loadingMsg.className = 'message bot';
  loadingMsg.innerHTML =
    '<div style="padding: 20px; text-align: center;">' +
      '<div style="font-weight: 600; font-size: 1rem; margin-bottom: 10px; color: #374151;">' +
        'Processing ' + files.length + ' file(s)...' +
      '</div>' +

      // Progress container
      '<div class="progress-container" style="width: 100%; margin-top: 12px;">' +
        '<div class="progress-wrapper" ' +
             'style="width: 100%; background: #f3f4f6; height: 24px; border-radius: 20px; overflow: hidden; position: relative; box-shadow: inset 0 2px 6px rgba(0,0,0,0.1);">' +
          '<div class="progress-bar" id="progressBar" ' +
               'style="width: 0%; height: 100%; background: linear-gradient(90deg, #4a67d6, #6d83f2);margin:0px; ' +
                      'transition: width 0.6s ease-in-out; border-radius: 20px;"></div>' +
          '<span id="progressLabel" ' +
                'style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); font-size:0.85rem; font-weight:700; color:#fff; text-shadow:0 1px 2px rgba(0,0,0,0.3);">0%</span>' +
        '</div>' +
      '</div>' +

      // File label
      '<div id="progressFileLabel" style="margin-top: 10px; font-size:0.85rem; color:#4a67d6; font-weight:500;">Waiting...</div>' +

      // Spinner + Text
      '<div style="margin-top: 14px; display:flex; justify-content:center; align-items:center; gap:10px; color:#4a67d6;">' +
        '<div class="spinner"></div>' +
        '<span style="font-size:0.9rem; font-weight:500;">Extracting text, please wait...</span>' +
      '</div>' +
    '</div>';

  chatMessages.appendChild(loadingMsg);
  chatMessages.scrollTop = chatMessages.scrollHeight;

  let allExtractedText = '';
  let imageCount = 0;
  let pdfCount = 0;

  try {
    for (let i = 0; i < files.length; i++) {
      const file = files[i];

      // Update file label
      const fileLabel = document.getElementById("progressFileLabel");
      if (fileLabel) {
        fileLabel.textContent = `Processing: ${file.name} (${i + 1}/${files.length})`;
      }

      // Stage 1 progress (start)
      updateProgress(i, files.length, "processing");

      let extractedText = '';
      if (file.type === 'application/pdf') {
        extractedText = await processPDF(file);
      } else if (file.type.startsWith('image/')) {
        extractedText = await processImage(file);
      } else if (
        file.name.endsWith('.docx') ||
        file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
      ) {
        extractedText = await processDOCX(file);
      } else {
        extractedText = 'Unsupported file type: ' + file.name;
      }

      // Stage 2 progress (done)
      updateProgress(i, files.length, "done");

      let label = '';
      if (file.type.startsWith('image/')) {
        imageCount++;
        label = 'Image ' + imageCount;
      } else if (file.type === 'application/pdf') {
        pdfCount++;
        label = 'PDF ' + pdfCount;
      } else {
        label = file.name;
      }

      allExtractedText += `${label} \n${extractedText.trim()}\n\n`;
    }

    loadingMsg.remove();

    const aiMsg = document.createElement('div');
    aiMsg.className = 'message bot';

    const header = document.createElement('div');
    header.style.display = 'flex';
    header.style.justifyContent = 'space-between';
    header.style.alignItems = 'center';
    header.style.marginBottom = '8px';

    header.innerHTML =
      '<h3 style="margin: 0;">📄 Extracted Text</h3>' +
      '<div style="display: flex; gap: 10px;">' +
        '<button class="button" onclick="copyText()" title="Copy">' +
          '<i class="fas fa-copy"></i>' +
        '</button>' +
        '<button class="button" onclick="downloadText()" title="Download">' +
          '<i class="fas fa-download"></i>' +
        '</button>' +
      '</div>';

    const pre = document.createElement('pre');
    pre.id = 'extractedText';
    pre.textContent = allExtractedText;
    pre.style.whiteSpace = 'pre-wrap';
    pre.style.border = '1px solid #ccc';
    pre.style.padding = '12px';
    pre.style.borderRadius = '8px';
    pre.style.background = '#f9f9f9';
    pre.style.fontSize = '14px';
    pre.style.cursor = 'pointer';

    pre.addEventListener('click', function () {
      makeEditable(pre);
    });

    // Chat Button - Now includes summarization
    const chatBtn = document.createElement('button');
    chatBtn.className = 'button';
    chatBtn.onclick = goToChatWithSummary;
    chatBtn.title = 'Chat with Speed AI Assistant (includes summarization)';
    chatBtn.textContent = 'Get Summary and Chat with Speed AI';
    chatBtn.style.marginTop = '16px';
    chatBtn.style.background = '#16a34a';
    chatBtn.style.color = 'white';
    chatBtn.style.fontWeight = 'bold';
    chatBtn.style.padding = '8px 16px';
    chatBtn.style.borderRadius = '6px';

    const btnWrapper = document.createElement('div');
    btnWrapper.style.display = 'flex';
    btnWrapper.style.gap = '10px';
    btnWrapper.style.flexWrap = 'wrap';
    btnWrapper.style.justifyContent = 'center';

    // Only add chat button, no summarize button
    btnWrapper.appendChild(chatBtn);

    const summaryContainer = document.createElement('div');
    summaryContainer.id = 'summaryContainer';
    summaryContainer.style.marginTop = '20px';

    aiMsg.appendChild(header);
    aiMsg.appendChild(pre);
    aiMsg.appendChild(btnWrapper);
    aiMsg.appendChild(summaryContainer);
    chatMessages.appendChild(aiMsg);

  } catch (error) {
    console.error('Error processing files:', error);
    loadingMsg.innerHTML =
      '<div class="message bot error">Error processing files. Please try again.</div>';
  }

  fileInput.value = '';
}

// ✅ Improved Progress Function
function updateProgress(index, total, stage = "processing") {
  const bar = document.getElementById("progressBar");
  const label = document.getElementById("progressLabel");

  if (!bar || !label) return;

  let targetPercent;

  if (total === 1) {
    targetPercent = stage === "processing" ? 50 : 100;
  } else {
    targetPercent = Math.round(((index + (stage === "done" ? 1 : 0)) / total) * 100);
  }

  // Smooth animation instead of instant jump
  let current = parseInt(bar.style.width) || 0;

  const step = () => {
    if (current < targetPercent) {
      current++;
      bar.style.width = current + "%";
      label.textContent = `${current}% (${Math.min(index + 1, total)} of ${total})`;
      requestAnimationFrame(step);
    } else {
      // Final state
      if (current === 100) {
        bar.style.background = "linear-gradient(90deg, #16a34a, #22c55e)";
        bar.style.animation = "none";
        bar.style.boxShadow = "0 2px 6px rgba(22,163,74,0.6)";
      }
    }
  };
  step();
}

let autoSaveTimeout;

function makeEditable(pre) {
  const currentText = pre.textContent;

  const textarea = document.createElement('textarea');
  textarea.value = currentText;
  textarea.style.width = '100%';
  textarea.style.height = '300px';
  textarea.style.fontSize = '14px';
  textarea.style.padding = '10px';
  textarea.style.borderRadius = '8px';
  textarea.style.border = '1px solid #ccc';
  textarea.id = 'extractedTextArea';

  pre.replaceWith(textarea);
  textarea.focus();

  textarea.addEventListener('input', function () {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(function () {
      autoSaveText();
    }, 1500);
  });

  textarea.addEventListener('blur', function () {
    autoSaveText();
  });
}

function autoSaveText() {
  const textarea = document.getElementById('extractedTextArea');
  if (!textarea) return;

  const newText = textarea.value;

  const pre = document.createElement('pre');
  pre.id = 'extractedText';
  pre.textContent = newText;
  pre.style.whiteSpace = 'pre-wrap';
  pre.style.border = '1px solid #ccc';
  pre.style.padding = '12px';
  pre.style.borderRadius = '8px';
  pre.style.background = '#f9f9f9';
  pre.style.fontSize = '14px';
  pre.style.cursor = 'pointer';

  pre.addEventListener('click', function () {
    makeEditable(pre);
  });

  textarea.replaceWith(pre);
}

function copyText() {
  const pre = document.getElementById('extractedText');
  const text = pre ? pre.innerText : '';
  if (!text) return;

  navigator.clipboard.writeText(text).catch((err) => {
    console.error('Failed to copy text:', err);
  });
}

async function processImage(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    const progressBar = document.getElementById('progressBar');

    reader.onload = async () => {
      try {
        progressBar.style.display = 'block';
        progressBar.style.width = '0%';

        // Simulate progress
        const simulateProgress = async (to, delay) => {
          return new Promise(res => {
            setTimeout(() => {
              progressBar.style.width = to + '%';
              res();
            }, delay);
          });
        };

        await simulateProgress(10, 100); // Reading file
        const base64 = reader.result.split(',')[1];

        await simulateProgress(30, 100); // Preparing request

        const response = await fetch('ocr.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ images: [base64] })
        });

        await simulateProgress(70, 200); // Waiting for response

        const result = await response.json();
        await simulateProgress(100, 200); // Done

        setTimeout(() => {
          progressBar.style.display = 'none';
          progressBar.style.width = '0%';
        }, 500);

        if (result.extracted) {
          resolve(result.extracted);
        } else {
          reject('❌ Extraction failed.');
        }
      } catch (err) {
        reject(err);
        progressBar.style.display = 'none';
      }
    };

    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

async function processPDF(file) {
  return new Promise(async (resolve, reject) => {
    try {
      const fileReader = new FileReader();
      fileReader.onload = async function () {
        const typedArray = new Uint8Array(this.result);
        try {
          const pdf = await pdfjsLib.getDocument(typedArray).promise;
          let fullText = '';

          // ✅ Progress bar elements
          const progressBar = document.getElementById("progressBar");
          const progressLabel = document.getElementById("progressLabel");

          try {
            for (let i = 1; i <= pdf.numPages; i++) {
              const page = await pdf.getPage(i);
              const textContent = await page.getTextContent();
              if (textContent.items.length === 0) {
                throw new Error("No text found");
              }
              fullText += textContent.items.map(item => item.str).join(" ") + "\n\n";

              // ✅ Update progress per page
              if (progressBar && progressLabel) {
                let percent = Math.round((i / pdf.numPages) * 100);
                progressBar.style.display = "block";
                progressBar.style.width = percent + "%";
                progressLabel.textContent = `${percent}% (page ${i} of ${pdf.numPages})`;
              }
            }
          } catch (e) {
            // fallback OCR
            fullText = await extractTextWithOCR(pdf);
          }

          // ✅ Hide progress after done
          setTimeout(() => {
            if (progressBar) {
              progressBar.style.display = "none";
              progressBar.style.width = "0%";
            }
          }, 500);

          resolve(fullText);
        } catch (error) {
          reject("Error processing PDF");
        }
      };
      fileReader.onerror = reject;
      fileReader.readAsArrayBuffer(file);
    } catch (error) {
      reject("Error loading PDF");
    }
  });
}

async function extractTextWithOCR(pdf) {
    let fullText = '';
    for (let i = 1; i <= pdf.numPages; i++) {
      const page = await pdf.getPage(i);
      const viewport = page.getViewport({ scale: 2.0 });
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');
      canvas.height = viewport.height;
      canvas.width = viewport.width;
      await page.render({ canvasContext: context, viewport: viewport }).promise;
      const { data } = await Tesseract.recognize(canvas, 'eng', {
        logger: m => {
          if (m.status === 'recognizing text') {
            const progress = Math.round(m.progress * 100);
            document.getElementById('progressBar').style.width = progress + '%';
          }
        }
      });
      fullText += data.text + '\n\n';
    }
    return fullText;
  }
  
async function processDOCX(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = async (event) => {
      try {
        const arrayBuffer = event.target.result;
        const result = await mammoth.extractRawText({ arrayBuffer });
        resolve(result.value);
      } catch (err) {
        reject('Error processing DOCX');
      }
    };
    reader.onerror = reject;
    reader.readAsArrayBuffer(file);
  });
}

async function sendToOpenAI(text) {
  const response = await fetch('case-api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ text })
  });

  if (!response.ok) {
    const errorText = await response.text();
    console.error('Backend error:', response.status, errorText);
    return 'Error contacting backend: ' + errorText;
  }

  const data = await response.json();
  return data.choices?.[0]?.message?.content || 'No response from backend.';
}

function downloadText() {
  const pre = document.getElementById('extractedText');
  const text = pre ? pre.innerText : '';
  if (!text) {
    alert('No text to download!');
    return;
  }

  if (window.AndroidDownload) {
    // Call Android's DownloadManager
    AndroidDownload.downloadTextFile("extracted_text.txt", text);
  } else {
    // Fallback for normal browsers
    const blob = new Blob([text], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'extracted_text.txt';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }
}

// New function: Chat with AI Assistant including summarization
async function goToChatWithSummary() {
  const pre = document.getElementById('extractedText');
  const text = pre ? pre.innerText.trim() : '';

  if (!text) {
    alert('No text to summarize and chat with!');
    return;
  }

  // Check session limit first
  try {
    const res = await fetch("get-new-session.php");
    const data = await res.json();

    if (data.error === "session_limit_reached") {
      showPremiumPopup(data.current_sessions, data.session_limit);
      return;
    }
  } catch (err) {
    console.error("Error checking session limit:", err);
  }

  // Save original text to sessionStorage
  sessionStorage.setItem('originalDoc', text);

  try {
    // Show loading for summarization
    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'message bot';
    loadingMsg.innerHTML = '<div style="padding: 12px; text-align: center;">' +
      '<div class="spinner" style="margin: auto;"></div>' +
      '<p style="margin-top: 10px; color: #6b7280; font-size: 0.9rem;">Generating summary...</p>' +
      '</div>';
    
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.appendChild(loadingMsg);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Generate summary first
    const summary = await sendToOpenAI(text);
    
    // Remove loading message
    loadingMsg.remove();

    // Save both original text and summary
    sessionStorage.setItem('originalDoc', text);
    sessionStorage.setItem('chatDoc', summary); // Use summary for chat

    // Get session ID and redirect
    const res = await fetch("get-new-session.php");
    const data = await res.json();

    if (data.next_session_id) {
      // Redirect to chat-save.php with session_id
      window.location.href = "chat-save.php?session_id=" + data.next_session_id;
    } else {
      alert("Please Login First Then Try again.");
    }
  } catch (err) {
    console.error("Error:", err);
    alert("Something went wrong. Please try again.");
  }
}

</script>
<script>
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
<div id="fullscreenImageModal" class="modal-overlay" style="display: none;" onclick="closeFullscreenImage()">
  <img id="fullscreenImage" src="" style="max-width: 100vw; max-height: 100vh; border-radius: 12px; box-shadow: 0 0 30px rgba(0,0,0,0.3);" />
</div>
<br>
<?php
include "include/footer.php"; 
?>