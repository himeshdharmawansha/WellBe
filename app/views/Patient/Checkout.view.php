<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/Checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php
        $this->renderComponent('navbar', $active);
        ?>
        

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Checkout"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="header">
                    <p>Checkout</p>
                    <hr>
                </div>
                <div class="checkout-container">
                    <form action="">
                        <div class="row">
                            <div class="column">
                                <h3 class="title">Payment Details</h3>
                                <div class="input-box">
                                    <span>Card Accepted :</span>
                                    <img src="../assests/visamasteramex-removebg-preview.png">
                                </div>
                                <div class="input-box"> 
                                    <span>Title:</span>
                                    <select name="title" id="title">
                                        <option value="mr">Mr.</option>
                                        <option value="mrs">Mrs.</option>
                                        <option value="miss">Miss.</option>
                                    </select>
                                </div>
                                <div class="input-box"> 
                                    <span>Name on Card :</span>
                                    <input type="text"
                                    placeholder="Amrah Slamath">
                                </div>
                                <div class="input-box"> 
                                    <span>Credit/Debit Card Number :</span>
                                    <input type="number"
                                    placeholder="1111 2222 3333 4444">
                                </div>
                                <div class="input-box"> 
                                    <span>Expiry Date :</span>
                                    <input type="text"
                                    placeholder="01/25">
                                </div>
                                <div class="input-box"> 
                                    <span>CVV :</span>
                                    <input type="text"
                                    placeholder="123">
                                </div>
                                <div class="input-box"> 
                                    <span>OTP :</span>
                                    <input type="text"
                                    placeholder="123">
                                </div>
                                </div>
                            </div>
                            <button type="submit" class="btn">Submit</button>
                        </div>
                        
                    </form>
                </div>
                    
    </div>
</body>
</html>
