<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= ROOT?>/assets/css/signup.css?v=1.1">
  <title>Login</title>
</head>
<body>

  <?php $errors = $data['errors']; ?>

  <div class="loginsignup" >
    <div class="loginsignup-container">
      <div class="logo-container">
        <img class="logo" src="<?= ROOT?>/assets/images/logo.png"/>
        <div class="logo_text">WELL BE</div>
      </div>
      <h1>Log In</h1>
      <form method="post">
        <div class="loginsignup-fields">
          <input name="nic"  type="text" placeholder="Type your NIC number"/>
          <input name="password"  type="password" placeholder="Type your Password"/>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="error-messages">
            <?php foreach ($errors as $error): ?>
              <p class="error"><?= $error ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        
        <button>LOGIN</button>
      </form>
      <p class="loginsignup-login">Create an account <span><a href="<?= ROOT?>/signup">Click here</a></span></p>
      <div class='loginsignup-agree'>
        <input type='checkbox' name='' id='' />
        <p>By continuing, I agree to the terms of use & privacy policy.</p>
      </div>
    </div>
    
  </div>
</body>
</html>