<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Verify Code and Reset Password</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/forgot.css">
</head>

<body>
   <div class="main">
      <form method="POST" action="<?= ROOT ?>/forgot/verify">
         <h1><?= isset($data['codeVerified']) && $data['codeVerified'] ? 'Reset Your Password' : 'Verify Your Code' ?></h1>

         <?php if (!isset($data['codeVerified']) || !$data['codeVerified']): ?>
            <!-- Show verification code input -->
            <div class="field">
               <input type="text" name="code" placeholder="Verification Code" required>
            </div>
            <?php if (!empty($data['errorMessage'])): ?>
               <div class="forgot-message"><?= htmlspecialchars($data['errorMessage']) ?></div>
            <?php endif; ?>
            <input type="submit" name="verify_code" value="Verify Code">
         <?php else: ?>
            <!-- Show password fields after code verification -->
            <div class="field">
               <input type="password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="field">
               <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <?php if (!empty($data['errorMessage'])): ?>
               <div class="forgot-message"><?= htmlspecialchars($data['errorMessage']) ?></div>
            <?php endif; ?>
            <input type="submit" name="update_password" value="Update Password">
         <?php endif; ?>



         <div class="signup_link">
            Back to login? <a href="<?= ROOT ?>/login">Login</a>
         </div>
      </form>
   </div>
</body>

</html>