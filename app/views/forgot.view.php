<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/forgot.css">
</head>

<body>

  <div class="main">
    <form method="POST" action="<?= ROOT ?>/forgot/check">
      <h1>Enter Your NIC & Email</h1>
      <div class="field field-row">
        <div class="input-wrapper">
          <input type="text" name="nicID" placeholder="NIC" required>
        </div>
        <div class="select-wrapper">
          <select name="selection" required>
            <option value="patient">Patient</option>
            <option value="doctor">Doctor</option>
            <option value="receptionist">Receptionist</option>
            <option value="administrative_staff">Admin</option>
            <option value="pharmacist">Pharmacist</option>
            <option value="lab_technician">Lab Tech</option>
          </select>
        </div>
      </div>
      <div class="field">
        <input type="email" name="email" placeholder="Email" required>
      </div>

      <?php if (!empty($errorMessage)): ?>
        <p class="forgot-message"><?= htmlspecialchars($errorMessage) ?></p>
      <?php endif; ?>
      <div class="signup_link">
        Back to login? <a href="<?= ROOT ?>/login"> Login </a>
      </div>
      <div class="forgotpass">
        <p>Don't have an Account?<br> Create your Profile <span><a href="<?= ROOT ?>/signup">Click here</a></span></p>
      </div>

      <input type="submit" name="check" value="Check">
    </form>
  </div>

</body>

</html>