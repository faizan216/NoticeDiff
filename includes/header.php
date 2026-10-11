<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'NoticeDiff') ?> - NoticeDiff</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="<?= BASE_URL ?>/">NoticeDiff</a>
  <?php if (isLoggedIn()): ?>
    <nav>
      <span class="who"><?= e($_SESSION['full_name']) ?> (<?= e($_SESSION['role']) ?>)</span>
      <form method="post" action="<?= BASE_URL ?>/auth/logout.php" class="inline">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <button type="submit" class="btn btn-small">Log out</button>
      </form>
    </nav>
  <?php endif; ?>
</header>
<main class="container">
