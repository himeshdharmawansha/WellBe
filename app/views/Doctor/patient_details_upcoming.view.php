
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #E0EBFF;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 24px;
            font-weight: bold;
            color: #2C3E50;
        }

        .header .divider {
            width: 80px;
            height: 2px;
            background-color: #3498DB;
            margin-top: 5px;
        }

        .patient-info p {
            margin: 8px 0;
            color: #34495E;
        }

        .patient-info p strong {
            color: #2C3E50;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .buttons a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-blue {
            background-color: #3498DB;
        }

        .btn-blue:hover {
            background-color: #2980B9;
        }

        .btn-green {
            background-color: #2ECC71;
        }

        .btn-green:hover {
            background-color: #27AE60;
        }
    </style>
    </style>
</head>
<body>
    <div class="flex h-full">
        <!-- Navbar Component -->
        <?php $this->renderComponent('navbar', $active); ?>

        <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>Doctorr Portal</h2>
            <div class="divider"></div>
        </div>

        <!-- Patient Information -->
        <div class="patient-info">
            <h2 style="color: #3498DB; font-size: 20px;">Patient Information</h2>
            <p><strong>Name:</strong> <?php echo $data[0]->first_name; ?> <?php echo $data[0]->last_name; ?></p>
            <p><strong>Age:</strong> <?php echo $data[0]->age; ?></p>
            <p><strong>Gender:</strong> <?php echo ucfirst($data[0]->gender); ?></p>
            <p><strong>Allergies:</strong> <?php echo $data[0]->allergies ?: 'None'; ?></p>
            <p><strong>Medical History:</strong> <?php echo $data[0]->medical_history ?: 'No history available'; ?></p>
            <p><strong>Contact:</strong> <?php echo $data[0]->contact; ?></p>
        </div>

        <!-- Action Buttons -->
        <div class="buttons">
            <a href="<?= ROOT ?>/doctor/medical_record/<?= $data[0]->id; ?>/<?= $_SESSION['appointment_id']; ?>" class="btn-blue">
                View Medical History
            </a>
            <a href="<?= ROOT ?>/doctor/medication_Details/<?= $data[0]->id; ?>/<?= $_SESSION['appointment_id']; ?>" class="btn-green">
                Create New Record
            </a>
        </div>
    </div>
    </div>
</body>
</html>
