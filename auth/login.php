<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/home.php');
    exit;
}

$error = '';
$email = '';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pw    = $_POST['password'] ?? '';

    $stmt = getDB()->prepare(
        'SELECT id, full_name, password_hash, role, is_active FROM users WHERE email = ?'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $user['is_active'] && password_verify($pw, $user['password_hash'])) {
        session_regenerate_id(true);   // new session ID after login
        $_SESSION['user_id']   = (int)$user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];
        header('Location: ' . BASE_URL . '/home.php');
        exit;
    }
    // One generic message: do not reveal whether the email exists.
    $error = 'Invalid email or password.';
}

$pageTitle = 'Log In';
require __DIR__ . '/../includes/header.php';
?>
<div class="card narrow">
  <h1>Log in</h1>
  <p class="tagline">Every Change. Clearly Communicated.</p>

  <?php if ($flash): ?><div class="success"><?= e($flash) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="error"><?= e($error) ?></div><?php endif; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    <button type="submit" class="btn">Log in</button>
  </form>
  <p>New student? <a href="register.php">Create an account</a></p>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
