

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details Collection</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/hello.css?v=<?= time() ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('ms');
            if (message) {
                alert(message);
            }
        });
    </script>

    <style>
        .hidden {
            display: none;
        }
    </style>

</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
         <!-- Sidebar -->
         <?php
        $this->renderComponent('navbar', $active);
        ?>
      

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WellBe/app/views/Components/Patient/header.php';
            ?>


            <section class="container">
                <!-- Channel Details Section -->

                <script>
                    // Retrieve data from localStorage
                    document.addEventListener('DOMContentLoaded', function () {
                        const doctor = localStorage.getItem('doctor');
                        const specialization = localStorage.getItem('specialization');
                        const date = localStorage.getItem('date');

                        // Display the data
                        document.getElementById('doctor').innerText = doctor ? doctor : 'Not specified';
                        document.getElementById('specialization').innerText = specialization ? specialization : 'Not specified';
                        document.getElementById('date').innerText = date ? date : 'Not specified';

                    });

                </script>

                <!-- Patient Details Section -->
                <div class="patient-details">
                    <h2 class="title">Appointment Details</h2>
                    <div class="cha-container">

                        <p><strong>Doctor:</strong></p>
                        <p><strong>Specialization:</strong></p>
                        <p><strong>Appointment Date & Time:</strong></p>
                        <p><strong>Appointment Number:</strong></p>
                        <p><strong>Appointment Fees:</strong></p>
                    </div>
                    <div class="cha-container">
                        <p><strong>Patient Name:</strong></p>
                        <p><strong>Contact No.:</strong></p>
                        <p><strong>Emergency Contact No.:</strong></p>
                        

                    </div>
                    <button id="confirmBtn" name="save_patient" class="submit-btn ">Confirm Appointment</button>
                    <div class="button-container">
                    <button id="payHereBtn" onclick="paymentGateWay();" name="save_patient" class="submit-btn hidden">Pay
                        Now</button>
                        <button id="payLaterBtn"  name="save_patient" class="submit-btn hidden">Pay
                        Over the Counter</button>
                        </div>

                </div>
            </section>
        </main>
    </div>
    <div id="popupModal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Thank You!</h2>
            </div>
            <div class="modal-body">
                <p>Your details have been successfully submitted. Thanks!</p>
            </div>
            <div class="modal-footer">
                <button id="closeModal" class="submit-btn" onclick="window.location.href='patient_dashboard'">OK</button>
            </div>
        </div>
    </div>

    <div id="popupModal2" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Thank You!</h2>
            </div>
            <div class="modal-body">
                <p>Your Payment has been made successfully. Thanks!</p>
            </div>
            <div class="modal-footer">
            <button id="closeModal2" class="submit-btn">OK</button>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script src="checkout.js"></script>
    <script>

document.addEventListener('DOMContentLoaded', function () {
            const confirmBtn = document.getElementById('confirmBtn');   
            const payHereBtn = document.getElementById('payHereBtn');
            const payLaterBtn = document.getElementById('payLaterBtn');
    

            confirmBtn.addEventListener('click', function () {
       
                confirmBtn.classList.add('hidden'); // Hide Pay Here button
                payHereBtn.classList.remove('hidden'); // Show Confirm Appointment button
                payLaterBtn.classList.remove('hidden');
    });
});

        document.addEventListener('DOMContentLoaded', function () {
            const confirmBtn = document.getElementById('confirmBtn');   
            const payHereBtn = document.getElementById('payHereBtn');
            const payLaterBtn = document.getElementById('payLaterBtn');
            const popupModal = document.getElementById('popupModal');
            const closeModal = document.getElementById('closeModal');

            const showModal = () => {
                popupModal.classList.remove('hidden');
            };

            const hideModal = () => {
                popupModal.classList.add('hidden');
            };

            
            payLaterBtn.addEventListener('click', showModal);
            closeModal.addEventListener('click', hideModal);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const confirmBtn = document.getElementById('confirmBtn');   
            const payHereBtn = document.getElementById('payHereBtn');
            const payLaterBtn = document.getElementById('payLaterBtn');
            const popupModal2 = document.getElementById('popupModal2');
            const closeModal2 = document.getElementById('closeModal2');

            const showModal = () => {
                popupModal2.classList.remove('hidden');
            };

            const hideModal = () => {
                popupModal2.classList.add('hidden');
            };

        
            
        });
    </script>

    

</body>

</html>