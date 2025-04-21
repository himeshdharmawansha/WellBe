
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= ROOT?>/assets/css/doc_dashboard.css?v=1.1">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #E0EBFF;
        }
    </style>
</head>
<body>
    <div class=" h-full" style="display: flex;">
       <?php 
        $this -> renderComponent('navbar',$active);
        ?>
        
    
        <div class="main-content">

            <?php
            $pageTitle = "Doctor Portal"; // Set the text you want to display
            //include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>


            <div class="items dashboard-content">
                <div style="margin-top: 25px;">
                    <div style="margin-left: 25px;">
                        <div class="flex" style="gap: 20px; align-items: center;margin-bottom: 20px;">
                            <div>
                                <img src="<?= ROOT ?>/assets/images/user.png" alt="" style="width: 100px ;min-width: 80px;">
                            </div>
                            <div>
                                <p class="font-['Poppins'] " style="font-weight: bold; font-size: 28px;margin-bottom: 10px;">Welcome</p>
                                <p class="font-['Poppins'] " style="font-weight: bold; font-size: 40px;margin-top: -15px;max-width: 350px;line-height: 1.0;">Dr.<?php echo htmlspecialchars($_SESSION['USER']->first_name); ?> <?php echo htmlspecialchars($_SESSION['USER']->last_name); ?></p>
                            </div>
                        </div>
    
                        <div class="blue-bar">
                            <div class="my-element" style="margin-right: 10px;"></div>
                            <div class=" font-['Poppins']" style="width: 200px;font-size: 27px; font-weight: 500;height: 9.5rem;color:black"> Dr. <?php echo htmlspecialchars($_SESSION['USER']->first_name); ?>, you have  8 appointments today</div>
                        </div>

                        <div class=" font-['Poppins']" style="margin-top:30px;margin-left:0px;font-size: 27px; font-weight: 500;color:black">
                                <p>New Patients : 03</p>
                                <p>Returning Patients : 05</p>
                        </div>
                    </div>
                </div>

                <div class="container2">
                    <div class="graph">
                        <?php 
                            $this -> renderChart('chart');
                        ?>
                        <?php 
                            $this -> renderCalender('calender');
                        ?>
                    </div>
                    
                    <div class="font-['Poppins']" style="display: flex;margin-left:100px;margin-top: 40px;gap: 20px;">
                           <p class="checkUp" style="font-size: 36px;">Start Patient Check-up</p>
                            <button style="width: 100px;background-color: rgb(44, 34, 119);font-size: 34px;border-radius: 10px;margin-left: 30px"><a style="color: #E0EBFF;" href="<?= ROOT?>/doctor/today_checkups">Start</a></button>
                        </div>
                    
                </div>
            </div>

        </div>
    </div>
    
</body>
</html>

<?php
ob_end_flush(); // Flush the buffer and send output
?>
