
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="flex h-full">
       <?php 
        $this -> renderComponent('navbar',$active);
        ?>
    
        <div class="main-content" style="background-color: rgb(255, 255, 255);width: 100%;margin-bottom: 2%;overflow-y: auto;overflow-x: hidden;">
            <?php
            $pageTitle = "Today Check-Ups"; // Set the text you want to display
            //include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <div class="items" style="display:flex;justify-content: space-between;">
                <div style="margin-top: 25px;margin-right:50px">
                    <div class="guide">
                        <div class="status">
                            <p>Available for Check-Up      :  </p>
                            <div style="width: 20px;height: 20px;background-color:#559bf6;border-radius:20%;"></div>
                        </div>
                        <div class="status">
                            <p>Not Available for Check-Up  :  </p>
                            <div style="width: 20px;height: 20px;background-color: #a4afc3f3;border-radius:20%;"></div>
                        </div>
                    </div>
                </div>

                <div class="container2">
                    
                    <div class="statQueue">
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $appointment): ?>
                            <a href="<?= ($appointment->state === 'PRESENT') ? ROOT . '/doctor/patient_details/' . $appointment->appointment_id . '/' . $appointment->patient_id : '#'; ?>" style="text-decoration: none;">
                                <div class="boxQueue" style="<?php echo ($appointment->state === 'PRESENT') ? 'background-color: #559bf6;' : ''; ?>">
                                    <div class="box-itemQueue">
                                        <div>
                                            <img src="<?= ROOT?>/assets/images/examination.png">
                                        </div>
                                        <div class="test">
                                            <p>
                                                <span style="color: black;">Name</span> : <?php echo htmlspecialchars($appointment->first_name . ' ' . $appointment->last_name); ?>
                                            </p>
                                            <p><span style="color: black;">App.ID</span> : <?php echo $appointment->appointment_id; ?></p>
                                            <!-- <p><span style="color: black;">Date of Birth</span> : <?php echo $appointment->dob; ?></p> -->
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="error-message">No appointments available.</p>
                    <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</body>
</html>