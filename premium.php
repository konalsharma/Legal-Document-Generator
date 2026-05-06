<?php
session_start();
// ✅ Only allow access if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: gettoken.php");
    exit();
}

include "include/header.php";

$name = $_SESSION['username'] ?? '';
$mobile = $_SESSION['mobile'] ?? '';
$email = $_SESSION['email'] ?? '';
?>

<div class="account-container">
  <div class="account-header">
    <h1 class="account-title">Premium Subscription</h1>
  </div>

  <div class="account-sections">
    <!-- Current Plan -->
    <div class="profile-section">
      <h3 class="section-title">Current Plan</h3>
      <div class="detail-row">
        <span class="detail-label">Status:</span>
        <span class="detail-value">
          <?php 
          if (isset($_SESSION['premium_user']) && $_SESSION['premium_user']) {
            echo '<span style="color: #10b981;">⭐ Premium Member</span>';
          } else {
            echo '<span style="color: #6b7280;">Free User</span>';
          }
          ?>
        </span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Features:</span>
        <span class="detail-value">
          <?php if (isset($_SESSION['premium_user']) && $_SESSION['premium_user']): ?>
            Unlimited documents, Priority support, Advanced AI
          <?php else: ?>
            Limited documents, Basic features
          <?php endif; ?>
        </span>
      </div>
    </div>

    <!-- Contact Developer Section -->
    <div class="documents-section">
      <h3 class="section-title">Contact Developer</h3>

      <form action="send_contact.php" method="POST" style="margin-top: 20px; max-width: 600px;">
        <div style="margin-bottom: 15px;">
          <label for="name" style="display: block; margin-bottom: 5px;">Your Name</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required
                 style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
          <label for="email" style="display: block; margin-bottom: 5px;">Your Email</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required
                 style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
          <label for="number" style="display: block; margin-bottom: 5px;">Your Contact number</label>
          <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" required
                 style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
          <label for="message" style="display: block; margin-bottom: 5px;">Message</label>
          <textarea id="message" name="message" rows="5" required
                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;"></textarea>
        </div>

        <button type="submit" name="send" class="sidebar-link"
                style="width: 100%; text-align: center; background: var(--primary); color: white; border: none;">
          Send Message
        </button>
      </form>
    </div>
  </div>
</div>

<?php
include "include/footer.php";
?>