<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/forgot.css">
</head>

<body>

  <div class=main>
    <form method="POST" action="<?= ROOT ?>/forgot/check">
      <h1> Enter Your Nic & Email</h1>
      <div class="field">
        <input type="text " name="nicID" placeholder="Nic ID" required>
      </div>
      <div class="field">
        <input type="email " name="email" placeholder="Email" required>
      </div>

      <?php if (!empty($errorMessage)): ?>
        <p class="forgot-message"><?= htmlspecialchars($errorMessage) ?></p>
      <?php endif; ?>

      <input type="submit" name="check" value="Check">

      <div class="signup_link">
        Back to login? <a href="<?= ROOT ?>/login"> Login </a>
      </div>
      <div class="forgotpass">
        <p>Don't have an Account?<br> Create your Profile <span><a href="<?= ROOT ?>/signup">Click here</a></span></p>
      </div>
    </form>

  </div>

</body>

</html>