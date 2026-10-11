<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$role = $_SESSION['role'];
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
?>
<div class="card">
  <h1>Welcome, <?= e($_SESSION['full_name']) ?></h1>
  <p>You are logged in as <span class="badge"><?= e($role) ?></span></p>
</div>

<?php if ($role === 'student'): ?>
  <div class="grid">
    <div class="card"><h2>My Timetable</h2><p class="hint">Coming soon.</p></div>
    <div class="card"><h2>Notifications</h2><p class="hint">Coming soon.</p></div>
  </div>
<?php elseif ($role === 'instructor'): ?>
  <div class="grid">
    <div class="card"><h2>My Lectures</h2><p class="hint">Coming soon.</p></div>
    <div class="card"><h2>Change Requests</h2><p class="hint">Coming soon.</p></div>
  </div>
<?php elseif ($role === 'admin'): ?>
  <div class="grid">
    <div class="card"><h2>Manage Users and Courses</h2><p class="hint">Coming soon.</p></div>
    <div class="card"><h2>Pending Approvals</h2><p class="hint">Coming soon.</p></div>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
