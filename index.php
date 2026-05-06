<?php
include "include/header.php"; 
include "include/connect.php";
// Fetch news (latest first)
$sql = "SELECT * FROM news ORDER BY published_at DESC, id DESC LIMIT 20";
$result = $conn->query($sql);

$articles = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$account_link = $userId ? "account.php" : "gettoken.php";
?>
<style>
.news-header {
  background: linear-gradient(135deg, #0077cc, #005fa3);
  color: white;
  padding: 18px;
  text-align: center;
  font-size: 20px;
  font-weight: bold;
  border-radius: 10px;
  margin-bottom: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.news-list { 
  display: flex; 
  flex-direction: column; 
  gap: 15px; 
  padding: 10px; 
}
.news-item { 
  display: flex; 
  background: #fff; 
  border-radius: 12px; 
  overflow: hidden; 
  box-shadow: 0 3px 6px rgba(0,0,0,0.1); 
  cursor: pointer; 
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.news-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}
.news-thumb {
  flex: 0 0 100px;
  width: 100px;
  height: 100px;
  object-fit: cover;
  background: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  color: #666;
}
.news-content { 
  padding: 12px; 
  flex: 1; 
}
.news-title { 
  font-size: 16px; 
  font-weight: bold; 
  margin: 0 0 6px 0; 
  color: #222; 
}
.news-desc {
  font-size: 13px;
  color: #444;
  margin-bottom: 8px;
}
.news-meta { 
  font-size: 12px; 
  color: #777; 
}
@media (max-width: 768px) {
.news-header {
display:block !important;
}
.news-list { 
display:block !important;
}	
}
</style>
<div class="format-section">
  <div class="format-title">Please Select Your Service</div>
  <div class="format-boxes">
    <a class="format-box" href="qa.php">💬<br>Legal Q&A</a>
    <a class="format-box" href="draft.php">📄<br>Drafting</a>
    <a class="format-box" href="case-analysis.php">⬆️<br>Case Analysis</a>
    <a class="format-box" href="<?= $account_link ?>">👤<br>My Account</a>
  </div>
  <br>
</div>
<br><br>
<header class="news-header" style="display:none;">📰 Latest News</header>
<div class="news-list" style="display:none;">
  <?php if (count($articles) > 0): ?>
    <?php foreach ($articles as $article): ?>
      <?php 
        $desc = htmlspecialchars($article['description']);
        $words = explode(" ", $desc);
        if (count($words) > 50) {
            $desc = implode(" ", array_slice($words, 0, 20)) . " ...";
        }
      ?>
      <div class="news-item" onclick="window.open('https://lawai.guru/frame.php?id=<?php echo urlencode($article['id']); ?>', '_blank')">
        <?php if (!empty($article['image_url'])): ?>
          <img class="news-thumb" src="https://lawai.guru/lawadminxyz/<?php echo htmlspecialchars($article['image_url']); ?>" alt="News image">
        <?php else: ?>
          <div class="news-thumb no-img">📰</div>
        <?php endif; ?>
        <div class="news-content">
          <h3 class="news-title"><?php echo htmlspecialchars($article['title']); ?></h3>
          <div class="news-meta">
            <?php echo htmlspecialchars($article['source'] ?? ''); ?>
            • <?php echo !empty($article['published_at']) ? date("M d, Y g:i A", strtotime($article['published_at'])) : ''; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="padding:15px;">No news found.</p>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4"></script>
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
  window.location.href = 'account.php';
}

function login() {
  window.location.href = 'gettoken.php';
}

function logout() {
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
      window.location.href = 'logout.php';
    }
  });
}
</script>
<br>
<?php
include "include/footer.php"; 
?>
