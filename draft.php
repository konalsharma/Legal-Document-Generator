<?php include "include/header.php"; ?>
<div class="content">
<div class="card">
<h2>📄 Legal Document Generator</h2>
<p>Create professional legal documents with Speed Law</p>
</div>
</div>
    <div class="content">
      <div class="card">
        <h3>Document Details</h3>
        <label for="docType">Document Type</label>
        <select onchange="handleDocumentTypeChange(this.value)" id="docType">
  <option value="">Select document template</option>
  <option value="rentAgreement">Rent Agreement</option>
  <option value="Power of Attorney">Power of Attorney</option>
  <option value="Sale Deed">Sale Deed</option>
  <option value="NDA (Non-Disclosure Agreement)">NDA (Non-Disclosure Agreement)</option>
  <option value="Employment Agreement">Employment Agreement</option>
  <option value="Notice Letter">Notice Letter</option>
  <option value="Will">Will</option>
  <option value="Loan Agreement">Loan Agreement</option>
  <option value="Cheque Bounce Notice">Cheque Bounce Notice</option>
  <option value="Money Recovery Notice">Money Recovery Notice</option>
  <option value="Bail Application">Bail Application</option>
</select>
<!-- Rent Agreement Type Selection -->
<div id="rentTypeSelector" class="rent-type-selector">
  <label class="rent-type-label">🏢 Select Agreement Type:</label>
  <div class="rent-type-options">
    <label class="option">
      <input type="radio" name="rentType" id="commercialOption" value="commercial">
      <span>Commercial</span>
    </label>
    <label class="option">
      <input type="radio" name="rentType" id="residentialOption" value="residential">
      <span>Residential</span>
    </label>
  </div>
</div>
<div id="rentFields" class="styled-form" style="display: none;min-height: 200px;max-height: 400px;overflow-y: auto;">
  <h4 style="color: var(--primary); margin-bottom: 16px;">📑Commercial Rent Agreement Details</h4>
  <div class="form-grid">
    <div class="form-group">
      <label for="Lessor">Property Owner</label>
      <input type="text" id="Lessor" placeholder="e.g., M/s Ansia Technologies">
    </div>

    <div class="form-group">
      <label for="property">Property Address</label>
      <textarea id="property" rows="2" placeholder="Enter property details"></textarea>
    </div>

    <div class="form-group">
      <label for="term">Lease Duration</label>
      <input type="text" id="term" placeholder="e.g., 36 months (1 July 2025 – 30 June 2028)">
    </div>

    <div class="form-group">
      <label for="rent">Monthly Rent</label>
      <input type="text" id="rent" placeholder="₹40,000 plus GST">
    </div>

    <div class="form-group">
      <label for="deposit">Security Deposit</label>
      <input type="text" id="deposit" placeholder="e.g., ₹1,20,000">
    </div>

   <div class="form-group">
  <label>Rent Escalation</label>
  <select id="escalationSelect" class="form-control">
    <option value="">-- Select escalation --</option>
    <option value="5%">5% per year</option>
    <option value="10%">10% per year</option>
    <option value="custom">Other</option>
  </select>
  
  <!-- Custom input (hidden by default) -->
  <input type="text" id="customEscalation" placeholder="Enter rate" style="display:none; margin-top:5px;">
</div>
    <div class="form-group full">
  <label>Lock-in Period</label>
  <select id="lockinSelect" class="form-control">
    <option value="">-- Select lock-in period --</option>
    <option value="12 months">12 months</option>
    <option value="24 months">24 months</option>
    <option value="custom">Other</option>
  </select>

  <!-- Custom input (hidden by default) -->
  <input type="text" id="customLockin" placeholder="Enter period (e.g., 18 months)" style="display:none; margin-top: 8px;">
</div>
    <div class="form-group full">
  <label>Notice Period</label>
  <select id="noticeSelect" class="form-control">
    <option value="">-- Select notice period --</option>
    <option value="1 month">1 month</option>
    <option value="2 months">2 months</option>
    <option value="custom">Other</option>
  </select>

  <!-- Custom input (hidden by default) -->
  <input type="text" id="customNotice" placeholder="Enter period (e.g., 45 days)" style="display:none; margin-top: 8px;">
</div>
<div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Use of Premises</label>
  <select id="useSelect" class="form-control">
    <option value="">-- Select use of premises --</option>
    <option value="Commercial – office only">Office only</option>
    <option value="Commercial – retail">Retail</option>
    <option value="custom">Other</option>
  </select>

  <!-- Custom input (hidden by default) -->
  <input type="text" id="customUse" placeholder="Enter use (e.g., Restaurant)" style="display:none; margin-top: 8px;">
</div>
<!-- Utilities Responsibility -->
<div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Utilities Responsibility</label>
  <select id="utilitiesSelect" class="form-control">
    <option value="">-- Select responsibility --</option>
    <option value="Paid by Lessee">Paid by Lessee</option>
    <option value="Paid by Lessor">Paid by Lessor</option>
    <option value="custom">Other</option>
  </select>

  <input type="text" id="customUtilities" placeholder="Enter responsibility" style="display:none; margin-top: 8px;">
</div>

<!-- Maintenance -->
<div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Maintenance</label>
  <select id="maintenanceSelect" class="form-control">
    <option value="">-- Select responsibility --</option>
    <option value="Day-to-day by Lessee">By Lessee</option>
    <option value="By Lessor">By Lessor</option>
    <option value="custom">Other</option>
  </select>

  <input type="text" id="customMaintenance" placeholder="Enter responsibility" style="display:none; margin-top: 8px;">
</div>
<div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Restrictions</label>
  <select id="restrictionsSelect" class="form-control">
    <option value="">-- Select restriction --</option>
    <option value="No subletting allowed">No Subletting</option>
    <option value="Subletting allowed">Allowed</option>
    <option value="custom">Other</option>
  </select>

  <!-- Custom input (hidden by default) -->
  <input type="text" id="customRestrictions" placeholder="Enter restriction" style="display:none; margin-top: 8px;">
</div>
    <div class="form-group">
      <label for="termination">Termination</label>
      <input type="text" id="termination" placeholder="e.g., On expiry, or non-payment for 2 months">
    </div>
  </div>
</div>
<!-- Residential Form -->
<div id="residentialFields" class="styled-form" style="display: none; min-height: 200px; max-height: 400px; overflow-y: auto;">
  <h4 style="color: var(--primary); margin-bottom: 16px;">🏠 Residential Rent Agreement Details</h4>
  <div class="form-grid">
    <div class="form-group">
      <label for="landlord">Landlord Name</label>
      <input type="text" id="landlord" placeholder="e.g., Mrs. Kavita Sharma">
    </div>

    <div class="form-group">
      <label for="address">Rental Property Address</label>
      <textarea id="address" rows="2" placeholder="Enter property address"></textarea>
    </div>

 <div class="form-group">
      <label for="residentialRent">Monthly Rent</label>
      <input type="text" id="residentialRent" placeholder="e.g., ₹15,000">
    </div>

    <div class="form-group">
      <label for="residentialDeposit">Security Deposit</label>
      <input type="text" id="residentialDeposit" placeholder="e.g., ₹30,000">
    </div>
    <div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Lease Duration</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialTerm" value="6 months"> 6 Months</label>
    <label><input type="radio" name="residentialTerm" value="12 months"> 12 Months</label>
    <label><input type="radio" name="residentialTerm" value="custom"> Other</label>
  </div>
  <input type="text" id="customLeaseDuration" placeholder="e.g., 11 months from 1 Aug 2025" style="display:none; margin-top: 8px;">
</div>
    <div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Notice Period</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialNotice" value="1 month"> 1 Month</label>
    <label><input type="radio" name="residentialNotice" value="2 months"> 2 Months</label>
    <label><input type="radio" name="residentialNotice" value="custom"> Other</label>
  </div>
  <input type="text" id="customResidentialNotice" placeholder="e.g., 45 days after lock-in" style="display:none; margin-top: 8px;">
</div>
    <div class="form-group full">
  <label class="rent-type-label" style="font-weight:700">Lock-in Period</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialLockin" value="6 months"> 6 Months</label>
    <label><input type="radio" name="residentialLockin" value="12 months"> 12 Months</label>
    <label><input type="radio" name="residentialLockin" value="custom"> Other</label>
  </div>
  <input type="text" id="customResidentialLockin" placeholder="e.g., 9 months" style="display:none; margin-top: 8px;">
</div>
   <div class="form-group full">
  <label style="font-weight:700">Maintenance Responsibility</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialMaintenance" value="Tenant"> Tenant</label><br>
    <label><input type="radio" name="residentialMaintenance" value="Landlord"> Landlord</label><br>
    <label><input type="radio" name="residentialMaintenance" value="custom"> Other</label>
  </div>
  <input type="text" id="customResidentialMaintenance" placeholder="e.g., Tenant to handle minor repairs, landlord for major work" style="display:none; margin-top: 8px;">
</div>
<div class="form-group full">
  <label style="font-weight:700">Utilities Responsibility</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialUtilities" value="Tenant"> Tenant</label><br>
    <label><input type="radio" name="residentialUtilities" value="Landlord"> Landlord</label><br>
    <label><input type="radio" name="residentialUtilities" value="custom"> Other</label>
  </div>
  <input type="text" id="customResidentialUtilities" placeholder="e.g., Tenant pays electricity, landlord pays water" style="display:none; margin-top: 8px;">
</div>
<div class="form-group full">
  <label style="font-weight:700">Purpose of Use</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialUse" value="Residential only"> Residential only</label><br>
    <label><input type="radio" name="residentialUse" value="Residential with home office"> Residential + Home Office</label><br>
    <label><input type="radio" name="residentialUse" value="custom"> Other</label>
  </div>
  <input type="text" id="customResidentialUse" placeholder="Specify use (e.g., Residential + Studio work)" style="display:none; margin-top: 8px;">
</div>
<div class="form-group full">
  <label style="font-weight:700">Guest Policy / Subletting</label>
  <div class="radio-group">
    <label><input type="radio" name="residentialGuests" value="No subletting allowed"> No subletting</label><br>
    <label><input type="radio" name="residentialGuests" value="No restrictions"> No restrictions</label><br>
    <label><input type="radio" name="residentialGuests" value="custom"> Other</label>
  </div>
  <input type="text" id="customResidentialGuests" placeholder="Enter specific subletting condition" style="display:none; margin-top: 8px;">
</div>
    <div class="form-group">
      <label for="residentialTermination">Termination Conditions</label>
      <input type="text" id="residentialTermination" placeholder="e.g., Early termination with 1-month notice">
    </div>
  </div>
</div>
<!-- Power of Attorney Form -->
<div id="poaFields" class="styled-form" style="display:none; min-height:400px; max-height:600px; overflow-y:auto;height: 0;">
  <h4 style="color: var(--primary); margin-bottom: 16px;">🖊 Please Fill To Generate POA Draft</h4>
<div id="poaAll" contenteditable="true" style="
      width: 100%; 
      min-height: 500px; 
      padding: 12px; 
      border: 1px solid #ccc; 
      border-radius: 6px; 
      font-family: Arial, sans-serif; 
      white-space: pre-wrap;
      overflow-y: auto;
  ">
<p>Q1. Full name & address of Principal / Grantor (the person giving authority)</p>Ans. 
<br>
<p>Q2. Full name & address of Attorney / Agent (the person receiving authority)</p>Ans. 
<br>
<p>Q3. Type of Power of Attorney (General or Special/Limited)</p>Ans. 
<br>
<p>Q4. Purpose of this Power of Attorney</p>Ans. 
<br>
<p>Q5. Date & place of execution</p>Ans. 
<br>
<p>Q6. Duration / Validity</p>Ans. 
<br>
<p>Q7. Powers granted to Attorney</p>Ans. 
<br>
<p>Q8. Restrictions / Limitations (if any)</p>Ans. 
<br>
<p>Q9. Governing Law & Jurisdiction</p>Ans. 
<br>
<p>Q10. Revocable or Irrevocable</p>Ans. 
<br>
<p>Q11. Witness details (names & addresses, if available)</p>Ans. 
<br>
<p>Q12. Any additional instructions / clauses</p>Ans. 
<br>
</div>
</div>

<!-- Generate Draft Button - Only shown for Rent Agreement and Power of Attorney -->
<div id="generateDraftButtonContainer" style="display: none;">
  <button onclick="generateDraft()">Generate Draft</button>
</div>
</div>
<div id="loginPopup" style="display:none; position:fixed; top:20px; left:50%; transform:translateX(-50%);
 background:#f0f6ff; border-radius:8px; padding:12px 16px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
  <span>Sign in to Generate Draft</span>
  <button onclick="window.location.href='gettoken.php'" 
          style="margin-left:10px; background:#1a73e8; color:white; border:none; padding:6px 12px;
                 border-radius:4px; cursor:pointer;">Sign in</button>
</div></div>
      <div class="card" id="generatedContainer">
	  <div id="progressBar">
          <div id="progressInner"></div>
        </div><br>
        <div id="generatedContent">Generated document will appear here</div>
        <div id="actionButtons">
          <button onclick="downloadDraft()">⬇ Download</button>
        </div>
		<div id="followUpContainer" style="display: none; margin-top: 16px;">
  <label for="followUpInput" style="display: block; margin-bottom: 4px; font-weight: 500;">Need Any Changes Please Mention.</label>
  <textarea id="followUpInput" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; resize: vertical;"></textarea>
  <button style="margin-top: 8px; background: var(--primary); color: #fff; border: none; padding: 10px; border-radius: 6px; cursor: pointer;" onclick="sendFollowUp()">Send</button>
</div>
      </div>
    
  
  <?php
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
// Show rent type selector when Rent Agreement is selected
function handleDocumentTypeChange(type) {
  // Reset / hide all
  document.getElementById('rentTypeSelector').style.display = 'none';
  document.getElementById('rentFields').style.display = 'none';
  document.getElementById('residentialFields').style.display = 'none';
  document.getElementById('poaFields').style.display = 'none';
  document.getElementById('generateDraftButtonContainer').style.display = 'none';

  if (type === 'rentAgreement') {
    document.getElementById('rentTypeSelector').style.display = 'block';
    document.getElementById('generateDraftButtonContainer').style.display = 'block';
  } else if (type === 'Power of Attorney') {
    document.getElementById('poaFields').style.display = 'block';
    document.getElementById('generateDraftButtonContainer').style.display = 'block';
  } else if (type) {
    // For other document types (Sale Deed, NDA, Employment, Notice, Will, etc.)
    // Auto-generate draft for other document types
    setTimeout(() => {
      generateDraftForOtherTypes(type);
    }, 500);
  }
}

// Auto-generate draft for other document types (not Rent Agreement or Power of Attorney)
function generateDraftForOtherTypes(type) {
  const isLoggedIn = <?php echo $isLoggedIn; ?>;
  if (!isLoggedIn) {
    document.getElementById("loginPopup").style.display = "block";
    setTimeout(() => {
      document.getElementById("loginPopup").style.display = "none";
    }, 3000);
    return;
  }

  document.getElementById('actionButtons').style.display = 'none';
  document.getElementById('followUpContainer').style.display = 'none';
  document.getElementById('generatedContent').innerText = '';
  startProgress();

  const prompt = `You are LawAI, a professional Indian lawyer with strong drafting experience.
Your role is to prepare legal drafts based on the ${type} of document requested by the user.

IMPORTANT:
1. The document type (${type}) is already provided. Do NOT ask the user to select the document type. Proceed directly to Step 2.
2. Before generating any draft, If sufficient details are not provided, ask the user the necessary questions in a **clear Q&A format. Do not generate the draft until all answers are provided. Present the questions clearly in a numbered Q&A format with example answers.

Supported document types:  
- Rent Agreement  
- Sale Deed  
- Power of Attorney  
- Will  
- NDA (Non-Disclosure Agreement)  
- Employment Agreement  
- Loan Agreement  
- Cheque Bounce Notice  
- Money Recovery Notice  
- Bail Application  

Process:

Step 2: Start with the line:
"To assist you better, please share answers to the questions below. This will help me create an accurate ${type} draft for your needs."
If sufficient details are not provided, ask the user the necessary questions in a **clear Q&A format.
- Number the questions (Q1, Q2, …).
- Provide example answers in brackets.
- Example:
  Q1. Name & address of Landlord  
  Ans. (Mr. A, 123, Sector 10, Delhi)

Step 3: If the user skips any question, follow up one by one until all answers are received.

Step 4: Once all answers are collected, generate the draft in proper Indian legal style:
- Use clear headings and numbered clauses.
- Insert placeholders [NEEDED: …] for any non-essential missing info.
- Draft must be at least 1000 words (~2 pages).

Step 5: End the draft with this disclaimer:
"Disclaimer: This is an auto-generated draft. Please review with a qualified lawyer before use."

Step 6: Finally, output *ONLY the final ${type} text*. Do not include any explanations, instructions, or Q&A in the final output.`;

  generateDraftWithPrompt(prompt);
}

// Listen to rent type radio buttons
document.getElementById('commercialOption').addEventListener('change', () => {
  document.getElementById('rentFields').style.display = 'block';
  document.getElementById('residentialFields').style.display = 'none';
});

document.getElementById('residentialOption').addEventListener('change', () => {
  document.getElementById('rentFields').style.display = 'none';
  document.getElementById('residentialFields').style.display = 'block';
});
document.getElementById('escalationSelect').addEventListener('change', function() {
  document.getElementById('customEscalation').style.display = 
    this.value === 'custom' ? 'block' : 'none';
});
  document.getElementById('lockinSelect').addEventListener('change', function () {
    const customLockin = document.getElementById('customLockin');
    customLockin.style.display = this.value === 'custom' ? 'block' : 'none';
  });
  document.getElementById('noticeSelect').addEventListener('change', function () {
    const customNotice = document.getElementById('customNotice');
    customNotice.style.display = this.value === 'custom' ? 'block' : 'none';
  });
  document.getElementById('useSelect').addEventListener('change', function () {
    const customUse = document.getElementById('customUse');
    customUse.style.display = this.value === 'custom' ? 'block' : 'none';
  });
  // Utilities
  document.getElementById('utilitiesSelect').addEventListener('change', function () {
    const customUtilities = document.getElementById('customUtilities');
    customUtilities.style.display = this.value === 'custom' ? 'block' : 'none';
  });

  // Maintenance
  document.getElementById('maintenanceSelect').addEventListener('change', function () {
    const customMaintenance = document.getElementById('customMaintenance');
    customMaintenance.style.display = this.value === 'custom' ? 'block' : 'none';
  });
  document.getElementById('restrictionsSelect').addEventListener('change', function () {
    const customInput = document.getElementById('customRestrictions');
    customInput.style.display = this.value === 'custom' ? 'block' : 'none';
  });
  document.querySelectorAll('input[name="residentialTerm"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customLeaseDuration');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
  document.querySelectorAll('input[name="residentialNotice"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customResidentialNotice');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
  document.querySelectorAll('input[name="residentialLockin"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customResidentialLockin');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
  document.querySelectorAll('input[name="residentialMaintenance"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customResidentialMaintenance');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
  document.querySelectorAll('input[name="residentialUtilities"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customResidentialUtilities');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
  document.querySelectorAll('input[name="residentialUse"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customResidentialUse');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
  document.querySelectorAll('input[name="residentialGuests"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const customInput = document.getElementById('customResidentialGuests');
      customInput.style.display = this.value === 'custom' ? 'block' : 'none';
    });
  });
</script>
<script>
  let lastDraft = '';

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

  function toggleAdminMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('adminMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    document.addEventListener('click', function hideMenu(e) {
      if (!menu.contains(e.target) && e.target !== event.target) {
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


  async function generateDraft() {
	  // Scroll to the top of the generatedContainer div
document.getElementById('generatedContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });

	const isLoggedIn = <?php echo $isLoggedIn; ?>;
	if (!isLoggedIn) {
      document.getElementById("loginPopup").style.display = "block";
      setTimeout(() => {
        document.getElementById("loginPopup").style.display = "none";
      }, 3000); // auto hide after 3 sec
    } else {	
    const type = document.getElementById('docType').value;

    if (!type) {
      alert('Please fill in all fields!');
      return;
    }
	

    document.getElementById('actionButtons').style.display = 'none';
    document.getElementById('followUpContainer').style.display = 'none';
    document.getElementById('generatedContent').innerText = '';
    startProgress();

    let prompt = '';

if (type === 'rentAgreement') {
  const rentType = document.querySelector('input[name="rentType"]:checked')?.value;

  if (rentType === 'commercial') {
    const Lessor = document.getElementById('Lessor').value.trim();
    const property = document.getElementById('property').value.trim();
    const term = document.getElementById('term').value.trim();
    const rent = document.getElementById('rent').value.trim();
    const deposit = document.getElementById('deposit').value.trim();
    let escalation = document.querySelector('input[name="escalation"]:checked')?.value || '';
    if (escalation === 'custom') {
    escalation = document.getElementById('customEscalation').value.trim();
    }
    let lockin = document.querySelector('input[name="lockin"]:checked')?.value || '';
    if (lockin === 'custom') {
    lockin = document.getElementById('customLockin').value.trim();
    }
    let notice = document.querySelector('input[name="notice"]:checked')?.value || '';
    if (notice === 'custom') {
    notice = document.getElementById('customNotice').value.trim();
    }
    let useOfPremises = document.querySelector('input[name="use"]:checked')?.value || '';
    if (useOfPremises === 'custom') {
    useOfPremises = document.getElementById('customUse').value.trim();
    }
    let utilitiesResponsibility = document.querySelector('input[name="utilities"]:checked')?.value || '';
    if (utilitiesResponsibility === 'custom') {
    utilitiesResponsibility = document.getElementById('customUtilities').value.trim();
    }
    let maintenanceResponsibility = document.querySelector('input[name="maintenance"]:checked')?.value || '';
    if (maintenanceResponsibility === 'custom') {
    maintenanceResponsibility = document.getElementById('customMaintenance').value.trim();
    }
    let restrictionValue = document.querySelector('input[name="restrictions"]:checked')?.value || '';
    if (restrictionValue === 'custom') {
    restrictionValue = document.getElementById('customRestrictions').value.trim();
    }
    const termination = document.getElementById('termination').value.trim();
    if (!Lessor || !property || !term || !rent || !deposit) {
      alert("Please fill in all required Commercial Rent Agreement fields.");
      return;
    }

    prompt = `
You are a legal expert drafting a *Commercial Lease Agreement* under Indian law. Create a rent deed using the details below. Use proper formatting, include standard clauses like indemnity, governing law, and stamp paper statement.

- Lessor: ${Lessor}
- Property: ${property}
- Lease Term: ${term}
- Monthly Rent: ${rent}
- Security Deposit: ${deposit}
- Rent Escalation: ${escalation}
- Lock-in Period: ${lockin}
- Notice Period: ${notice}
- Use of Premises: ${useOfPremises}
- Utilities: ${utilitiesResponsibility}
- Maintenance: ${maintenanceResponsibility}
- Restrictions: ${restrictionValue}
- Termination: ${termination}

Only output the final legal text. Do not include any disclaimers or introductory comments.
    `.trim();

  } else if (rentType === 'residential') {
  const landlord = document.getElementById('landlord').value.trim();
  const address = document.getElementById('address').value.trim();
  let residentialTerm = document.querySelector('input[name="residentialTerm"]:checked')?.value || '';
  if (residentialTerm === 'custom') {
  residentialTerm = document.getElementById('customLeaseDuration').value.trim();
  }
  const residentialRent = document.getElementById('residentialRent').value.trim();
  const residentialDeposit = document.getElementById('residentialDeposit').value.trim();
  let residentialNotice = document.querySelector('input[name="residentialNotice"]:checked')?.value || '';
  if (residentialNotice === 'custom') {
  residentialNotice = document.getElementById('customResidentialNotice').value.trim();
  }
  let residentialLockin = document.querySelector('input[name="residentialLockin"]:checked')?.value || '';
  if (residentialLockin === 'custom') {
  residentialLockin = document.getElementById('customResidentialLockin').value.trim();
  }
  let residentialMaintenance = document.querySelector('input[name="residentialMaintenance"]:checked')?.value || '';
  if (residentialMaintenance === 'custom') {
  residentialMaintenance = document.getElementById('customResidentialMaintenance').value.trim();
  }
  let residentialUtilities = document.querySelector('input[name="residentialUtilities"]:checked')?.value || '';
  if (residentialUtilities === 'custom') {
  residentialUtilities = document.getElementById('customResidentialUtilities').value.trim();
  }
  let residentialUse = document.querySelector('input[name="residentialUse"]:checked')?.value || '';
  if (residentialUse === 'custom') {
  residentialUse = document.getElementById('customResidentialUse').value.trim();
  }
  let residentialGuests = document.querySelector('input[name="residentialGuests"]:checked')?.value || '';
  if (residentialGuests === 'custom') {
  residentialGuests = document.getElementById('customResidentialGuests').value.trim();
  }
  const residentialTermination = document.getElementById('residentialTermination').value.trim();
  // Basic required fields check
  if (!landlord || !address || !residentialTerm || !residentialRent || !residentialDeposit) {
    alert("Please fill in all required Residential Rent Agreement fields.");
    return;
  }

  prompt = `
You are a legal expert drafting a *Residential Rent Agreement* under Indian law. Create a professionally formatted rent deed using the details below. Include standard clauses (e.g., tenant obligations, lock-in period, maintenance, and termination conditions), and ensure it is legally sound.

- Landlord: ${landlord}
- Rental Property Address: ${address}
- Lease Duration: ${residentialTerm}
- Monthly Rent: ${residentialRent}
- Security Deposit: ${residentialDeposit}
- Notice Period: ${residentialNotice}
- Lock-in Period: ${residentialLockin}
- Maintenance Responsibility: ${residentialMaintenance}
- Utilities Responsibility: ${residentialUtilities}
- Purpose of Use: ${residentialUse}
- Guest/Subletting Policy: ${residentialGuests}
- Termination Conditions: ${residentialTermination}

Only output the final legal agreement text. Do not include any explanations, notes, or disclaimers.
  `.trim();
} else {
    alert("Please select a rent agreement type: Commercial or Residential.");
    return;
  }
} else if (type === 'Power of Attorney') {
    const poaContentDiv = document.getElementById('poaAll');
    const qaText = poaContentDiv.textContent.trim(); // preserve line breaks

    prompt = 'You are a professional Indian contract lawyer.\n' +
            'Draft a formal Power of Attorney (POA) based on the Q&A provided by the user.\n' +
            'Follow standard structure:\n' +
            '1. Title\n' +
            '2. Parties (Principal/Grantor and Attorney/Agent)\n' +
            '3. Recitals/Purpose\n' +
            '4. Powers Granted\n' +
            '5. Restrictions/Limitations\n' +
            '6. Duration / Revocation\n' +
            '7. Governing Law & Jurisdiction\n' +
            '8. Witness/Attestation Clause\n' +
            '9. Signature Blocks for Principal, Attorney, and Witnesses.\n\n' +
            'Where any answer is missing, insert [NEEDED: …].\n' +
            'Use clear, formal legal English, suitable for Indian law.\n' +
            'End with: "Disclaimer: Auto-generated draft. Please review and register/stamp with a qualified lawyer/notary before use."\n\n' +
            'Here are the details provided by the user in Q&A format:\n\n' +
            qaText + '\n\n' +
            'Please generate a legally valid Power of Attorney document using these details.';
}

    generateDraftWithPrompt(prompt);
  }
 }

// Common function to generate draft with given prompt
async function generateDraftWithPrompt(prompt) {
  try {
    const response = await fetch('generate.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ prompt })
    });
    const data = await response.json();
    const draft = data.choices?.[0]?.message?.content?.trim() || "No draft generated.";
    document.getElementById('generatedContent').innerHTML = draft.replace(/\n/g, '<br>');
    document.getElementById('actionButtons').style.display = 'flex';
    document.getElementById('followUpContainer').style.display = 'block';
    lastDraft = draft;
  } catch (error) {
    console.error(error);
    document.getElementById('generatedContent').innerText = "Error generating draft.";
  } finally {
    stopProgress();
  }
}

  async function sendFollowUp() {
    const userInstruction = document.getElementById('followUpInput').value.trim();
    if (!userInstruction) {
      alert('Please enter your refinement or question!');
      return;
    }

    startProgress();
    document.getElementById('generatedContent').innerText = '';
    document.getElementById('actionButtons').style.display = 'none';

    const messages = [
      { role: "system", content: "You are a legal assistant. You must output only the final legal text" },
      { role: "user", content: `Here is the current draft:\n\n${lastDraft}\n\nThe user wants to make the following changes or ask the following question:\n\n${userInstruction}\n\nPlease revise accordingly. ONLY output the revised final legal text` }
    ];

    try {
      const response = await fetch('chat.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({ messages })  // sending only necessary data
});

      const data = await response.json();
      const updatedDraft = data.choices?.[0]?.message?.content?.trim() || "No updated draft generated.";
      document.getElementById('generatedContent').innerText = updatedDraft;
      document.getElementById('actionButtons').style.display = 'flex';
      document.getElementById('followUpContainer').style.display = 'block';
      lastDraft = updatedDraft;
      document.getElementById('followUpInput').value = '';
    } catch (error) {
      console.error(error);
      document.getElementById('generatedContent').innerText = "Error generating updated draft.";
    } finally {
      stopProgress();
    }
  }

  function startProgress() {
    const bar = document.getElementById('progressBar');
    const inner = document.getElementById('progressInner');
    bar.style.display = 'block';
    inner.style.width = '0%';
    let width = 0;
    window.progressInterval = setInterval(() => {
      if (width < 90) {
        width += 5;
        inner.style.width = width + '%';
      }
    }, 200);
  }

  function stopProgress() {
    clearInterval(window.progressInterval);
    const inner = document.getElementById('progressInner');
    inner.style.width = '100%';
    setTimeout(() => {
      document.getElementById('progressBar').style.display = 'none';
      inner.style.width = '0%';
    }, 500);
  }

 

 function downloadDraft() {
  const content = document.getElementById('generatedContent').innerHTML;

  // Word document boilerplate
  const header =
    '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
    'xmlns:w="urn:schemas-microsoft-com:office:word" ' +
    'xmlns="http://www.w3.org/TR/REC-html40">' +
    '<head><meta charset="utf-8"></head><body>';

  const footer = "</body></html>";
  const fullDocument = header + content + footer;

  if (window.AndroidDownload) {
    // ✅ Use Android's download method (saves to Downloads folder)
    AndroidDownload.downloadTextFile("Draft.doc", fullDocument);
  } else {
    // ✅ Browser fallback (creates a Word file via Blob)
    const blob = new Blob(['\ufeff', fullDocument], {
      type: "application/msword"
    });

    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = "Draft.doc";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  }
}

</script>
<br>
<?php
include "include/footer.php"; 
?>