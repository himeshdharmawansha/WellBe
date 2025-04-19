<!DOCTYPE html> 
<html lang="en"> 
<head>   
    <meta charset="UTF-8">   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <link rel="stylesheet" href="<?= ROOT?>/assets/css/signup.css?v=1.1">   
    <title>Login</title> 
    <script>
        function validateForm(event) {
            var checkbox = document.getElementById("agreeCheckbox");
            if (!checkbox.checked) {
                alert("You must agree to the terms of use & privacy policy to continue.");
                event.preventDefault(); // Prevent form submission
            }
        }
    </script>
</head> 
<body>    
    <div class="loginsignup">     
        <div class="loginsignup-container">       
            <div class="logo-container">         
                <img class="logo" src="<?= ROOT?>/assets/images/logo.png"/>         
                <div class="logo_text">WELL BE</div>       
            </div>       
            <h1>Log In</h1>       
            <form method="post" onsubmit="validateForm(event)">         
                <div class="loginsignup-fields">           
                    <input name="nic" type="text" placeholder="Type your NIC number" required/>           
                    <input name="password" type="password" placeholder="Type your Password" required/>         
                </div>          

                <?php if (!empty($errors)): ?>           
                <div class="error-messages">             
                    <?php foreach ($errors as $error): ?>               
                        <p class="error"><?= $error ?></p>             
                    <?php endforeach; ?>           
                </div>         
                <?php endif; ?>                               

                <p class="loginsignup-login">Don't have an Account? Create your Profile <span><a href="<?= ROOT?>/signup">Click here</a></span></p> 
                      
                <div class='loginsignup-agree'>         
                    <input type='checkbox' id="agreeCheckbox" required />         
                    <p>By continuing, I agree to the <a href="<?= ROOT?>/privacy-policy">terms of use & privacy policy</a>.</p>       
                </div>       
                <button type="submit">LOGIN</button>       
            </form>     
        </div>        
    </div> 
</body> 
</html>