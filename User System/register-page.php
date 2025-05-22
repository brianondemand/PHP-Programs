<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
  <h2>Register</h2>
  <form method="POST" action="includes/register.php" class="mt-3">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input name="username" type="text" id="username" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input name="email" type="email" id="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input name="password" type="password" id="password" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="confirm_password" class="form-label">Confirm Password</label>
      <input name="confirm_password" type="password" id="confirm_password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <a href="login-page.php" class="btn btn-link">Login</a>
  </form>
</div>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="toast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toast-message"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  <?php if (isset($_SESSION['toast'])): ?>
    const toastEl = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    toastMessage.textContent = <?= json_encode($_SESSION['toast']['message']) ?>;
    toastEl.classList.remove('text-bg-primary','text-bg-success','text-bg-danger');
    toastEl.classList.add('text-bg-<?= $_SESSION['toast']['type'] ?>');
    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();
  <?php unset($_SESSION['toast']); endif; ?>
</script>
</body>
</html>
