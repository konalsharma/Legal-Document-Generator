<?php 
include 'include/header.php'; 
include 'include/connect.php';
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || $user_id == "") {
    header("Location: gettoken.php");
    exit;
}

$userQuery = mysqli_query($conn, "
SELECT 
u.username, u.email, u.mobile, u.created_at,
c.country_name AS country,
s.state_name AS state,
ci.city_name AS city
FROM users u
LEFT JOIN countries c ON u.country = c.country_id
LEFT JOIN states s ON u.state = s.state_id
LEFT JOIN cities ci ON u.city = ci.city_id
WHERE u.id = " . (int)$user_id
);
$user = mysqli_fetch_assoc($userQuery);

$caseCountQuery = mysqli_query($conn, "SELECT COUNT(DISTINCT session_id) AS total FROM chats WHERE user_id = " . (int)$user_id);
$caseCount = mysqli_fetch_assoc($caseCountQuery);
$casesAnalyzed = $caseCount['total'];

$sessionsQuery = mysqli_query($conn, "
    SELECT session_id, MAX(created_at) AS last_update
    FROM chats
    WHERE user_id = " . (int)$user_id . "
    GROUP BY session_id
    ORDER BY last_update DESC
    LIMIT 5
");

$sessions = [];
while ($row = mysqli_fetch_assoc($sessionsQuery)) {
    $summaryQuery = mysqli_query($conn, "
        SELECT content 
        FROM chats 
        WHERE session_id = " . (int)$row['session_id'] . " 
          AND role = 'assistant' 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $summaryRow = mysqli_fetch_assoc($summaryQuery);
    $row['summary'] = $summaryRow['content'] ?? "(no assistant response yet)";
    $sessions[] = $row;
}
?>

  <div class="account-container">
  <div class="account-header">
    <h1 class="account-title">My Account</h1>
  </div>

  <div class="account-sections">
    <div class="profile-section" style="background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; padding: 2%; margin-bottom:25px; box-shadow:0 4px 8px rgba(0,0,0,0.05);">

  <div class="profile-header" style="display:flex; align-items:center; margin-bottom:20px;">
    <div class="profile-avatar" style="width:60px; height:60px; background:#e5e7eb; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:28px; margin-right:15px;">
      👤
    </div>
    <div class="profile-info">
      <h3 style="margin:0; font-size:1.3rem; font-weight:700; color:#111827;">
        <?= htmlspecialchars($user['username']) ?>
      </h3>
      <p style="margin:4px 0 0; font-size:0.95rem; color:#6b7280;">
        ⭐ Member
      </p>
    </div>
  </div>

  <div class="profile-details" style="display:flex; flex-direction:column; gap:12px;">
    
    <div class="detail-row" style="display:flex; justify-content:space-between; font-size:0.95rem; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
      <span class="detail-label" style="font-weight:600; color:#374151;">📧 Email:</span>
      <span class="detail-value" style="color:#2563eb;"><?= htmlspecialchars($user['email']) ?></span>
    </div>
	    <div class="detail-row" style="display:flex; justify-content:space-between; font-size:0.95rem; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
      <span class="detail-label" style="font-weight:600; color:#374151;">📞 Contact No:</span>
      <span class="detail-value" style="color:#2563eb;"><?= htmlspecialchars($user['mobile']) ?></span>
    </div>
		    <div class="detail-row" style="display:flex; justify-content:space-between; font-size:0.95rem; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
      <span class="detail-label" style="font-weight:600; color:#374151;">📍 Location:</span>
      <span class="detail-value" style="color:#2563eb;"><?= htmlspecialchars($user['country']) ?> 
	   | <?= htmlspecialchars($user['state']) ?> | <?= htmlspecialchars($user['city']) ?></span>
    </div>

    <div class="detail-row" style="display:flex; justify-content:space-between; font-size:0.95rem; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
      <span class="detail-label" style="font-weight:600; color:#374151;">📅 Member Since:</span>
      <span class="detail-value" style="color:#059669;"><?= date("F j, Y", strtotime($user['created_at'])) ?></span>
    </div>

    <div class="detail-row" style="display:flex; justify-content:space-between; font-size:0.95rem;">
      <span class="detail-label" style="font-weight:600; color:#374151;">📊 Cases Analyzed:</span>
      <span class="detail-value" style="color:#dc2626;"><?= $casesAnalyzed ?></span>
    </div>
    
  </div>
</div>


    <!-- Cases Section -->
    <div class="documents-section" style="margin-top:30px;">
  <h2 class="section-title" style="font-size:1.5rem; font-weight:700; color:#2563eb; margin-bottom:15px; border-bottom:2px solid #e5e7eb; padding-bottom:6px;">
    📑 Recent Case Summaries
  </h2>
  
  <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:15px;">
    <?php if (!empty($sessions)): ?>
      <?php foreach ($sessions as $sess): ?>
        <li style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:15px; box-shadow:0 2px 5px rgba(0,0,0,0.05); transition:all 0.3s ease;">
          
          <a href="chat-AI.php?session_id=<?= $sess['session_id'] ?>" 
             style="font-size:1.1rem; font-weight:600; color:#059669; text-decoration:none; display:block; margin-bottom:8px;">
             💼 Case <?= $sess['session_id'] ?>
          </a>
          
          <p style="font-size:0.95rem; color:#374151; margin:0 0 8px 0; line-height:1.5;">
            <?= nl2br(htmlspecialchars(substr($sess['summary'], 0, 120))) ?>...
          </p>
          
          <small style="color:#6b7280; font-size:0.8rem;">
            ⏱ Last update: <?= date("M d, Y", strtotime($sess['last_update'])) ?>
          </small>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li style="padding:12px; color:#6b7280; text-align:center; background:#f3f4f6; border-radius:8px;">
        No cases yet.
      </li>
    <?php endif; ?>
  </ul>
</div>

  </div>
</div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
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
  </script>
<?php
include "include/footer.php"; 
?>