<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Please login first.'];
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
  <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
  <form method="POST" action="includes/update.php" class="mt-3">
    <!-- Existing username and email fields -->
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input name="username" type="text" id="username" class="form-control" value="<?= htmlspecialchars($_SESSION['username']) ?>" required />
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input name="email" type="email" id="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required />
    </div>

    <!-- New password fields -->
    <hr />
    <p class="text-muted">Leave password fields blank to keep your current password.</p>
    <div class="mb-3">
      <label for="current_password" class="form-label">Current Password</label>
      <input name="current_password" type="password" id="current_password" class="form-control" />
    </div>
    <div class="mb-3">
      <label for="new_password" class="form-label">New Password</label>
      <input name="new_password" type="password" id="new_password" class="form-control" />
    </div>
    <div class="mb-3">
      <label for="confirm_new_password" class="form-label">Confirm New Password</label>
      <input name="confirm_new_password" type="password" id="confirm_new_password" class="form-control" />
    </div>

    <button type="submit" class="btn btn-success">Update Details</button>
  </form>

  <form method="POST" action="includes/delete.php" onsubmit="return confirm('Are you sure you want to delete your account? This action is irreversible.');" class="mt-3">
    <button type="submit" class="btn btn-danger">Delete Account</button>
  </form>

  <a href="includes/logout.php" class="btn btn-secondary mt-3">Logout</a>
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
