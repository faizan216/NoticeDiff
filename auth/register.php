<?php
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/home.php');
    exit;
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $name  = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pw    = $_POST['password'] ?? '';
    $pw2   = $_POST['confirm_password'] ?? '';

    if (strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = 'Enter your full name (2 to 100 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($pw) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($pw !== $pw2) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        try {
            // The role is fixed to 'student' here, never taken from the form.
            $stmt = getDB()->prepare(
                "INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, 'student')"
            );
            $stmt->execute([$name, $email, password_hash($pw, PASSWORD_DEFAULT)]);
            $_SESSION['flash'] = 'Account created. Please log in.';
            header('Location: ' . BASE_URL . '/auth/login.php');
            exit;
        } catch (PDOException $ex) {
            if ($ex->getCode() === '23000') {      // duplicate email
                $errors[] = 'That email is already registered.';
            } else {
                error_log($ex->getMessage());
                $errors[] = 'Something went wrong. Please try again.';
            }
        }
    }
}

$pageTitle = 'Sign Up';
require __DIR__ . '/../includes/header.php';
?>
<div class="card narrow">
  <h1>Create account</h1>
  <p class="tagline">Student registration</p>

  <div id="js-errors" class="error" style="display:none"></div>
  <?php if ($errors): ?>
    <div class="error"><ul>
      <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
    </ul></div>
  <?php endif; ?>

  <form method="post" data-validate="register" novalidate>
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <label for="full_name">Full name</label>
    <input type="text" id="full_name" name="full_name" value="<?= e($name) ?>" required>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= e($email) ?>" required>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    <p class="hint">At least 8 characters.</p>
    <label for="confirm_password">Confirm password</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <button type="submit" class="btn">Sign up</button>
  </form>
  <p>Already registered? <a href="login.php">Log in</a></p>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
