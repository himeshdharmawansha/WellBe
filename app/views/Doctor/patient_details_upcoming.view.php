

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= ROOT?>/assets/css/patient_details.css?v=1.1">
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
    
            <div class="relative" style="background-color: rgb(255, 255, 255);width: 100%; margin-top: 6%;margin-bottom: 2%;overflow-y: auto;overflow-x: hidden;">
            <div class="font-['Poppins'] text-2xl" style="margin-left: 15px;margin-top: 15px;font-weight: bold;">
                <p>Doctor Portal</p>
                <div class="w-52 border-blue-500" style="border-width: 1px;margin-top: 5px;"></div>
            </div>

            
                <div class="container">
                    <div class="patient-info">
                    <h2>Patient Information</h2>
                    <p><strong>Name :</strong> <?php echo $data[0]->first_name; ?> <?php echo $data[0]->last_name; ?></p>
                    <p><strong>Age :</strong> <?php echo $data[0]->age; ?></p>
                    <p><strong>Gender :</strong> <?php echo $data[0]->gender; ?></p>
                    <p><strong>Allergies :</strong> <?php echo $data[0]->allergies; ?></p>
                    <p><strong>Medical History :</strong> <?php echo $data[0]->medical_history; ?></p>
                    <p><strong>Contact :</strong> <?php echo $data[0]->contact; ?></p>
                    </div>
                    
                    <div class="buttons">
                    <a href="<?= ROOT ?>/doctor/medical_record/<?= $data[0]->id; ?>/<?= $_SESSION['appointment_id']; ?>" class="btn btn-blue">View Medical History</a>
                        <a href="<?= ROOT ?>/doctor/medication_Details/<?= $data[0]->id; ?>/<?= $_SESSION['appointment_id']; ?>" class="btn btn-green">Create New Record</a>
                    </div>
                </div>

        </div>
    </div>
    
</body>
</html>