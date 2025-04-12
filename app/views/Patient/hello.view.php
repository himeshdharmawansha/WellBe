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

        .submit-btn:disabled {
        background-color: #ccc;
        color: #666;
        cursor: not-allowed;
        opacity: 0.6;
        }

        .low-fund {
            color: red;
            font-weight: bold;
        }

    </style>

</head>

<body>

    <?php
        $amount = $data['walletAmount'][0]->e_wallet; //e_wallet is patient e_wallet column
        $disableWalletBtn = ($amount <= 1500) ? 'disabled' : '';
        //print_r($amount);
        //echo gettype($amount);
    ?>

    <div class="dashboard-container">
        <!-- Sidebar -->
        
        <?php
        $this->renderComponent('navbar', $active);
        ?>


        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Appointment Details"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Patient/header.php';
            ?>


            <section class="container">
                <!-- Patient Details Section -->
                <div class="patient-details">
                    <h2 class="title">Appointment Details</h2>
                    <div class="cha-container">

                        <p id="doctor-info">Doctor: Loading...</p>
                        <p id="specialization-info">Specialization: Loading...</p>
                        <p id="day-info">Appointment Date: </p>
                        <p id="start-time-info">Appointment Time: Loading...</p>
                        <p id="appointment-id-info">Appointment Number: Loading...</p>
                        <p id="appointment-fee"><strong>Appointment Fees:</strong></p>
                    </div>
                    <div class="cha-container">
                        <p><strong>Patient Name: </strong><?= $_SESSION['USER']->first_name; ?> <?= $_SESSION['USER']->last_name; ?></p>
                        <p><strong>Contact Number: </strong><?= $_SESSION['USER']->contact; ?></p>
                        <p><strong>Emergency Contact Number: </strong><?= $_SESSION['USER']->emergency_contact_no; ?></p>


                    </div>
                    <button id="confirmBtn" name="save_patient" class="submit-btn ">Confirm Appointment</button>
                    <div class="button-container">
                        <button id="payHereBtn" onclick="paymentGateWay();" name="save_patient"
                            class="submit-btn hidden">Pay
                            Now</button>
                        <button id="payLaterBtn" name="save_patient" class="submit-btn hidden">Pay
                            Over the Counter</button>
                        </div>
                        <button id="payByWalletBtn" name="save_patient" class="submit-btn hidden" <?= $disableWalletBtn ?>>Pay
                            By E-Wallet 
                            <span class="wallet-amount <?= ($amount < 1500) ? 'low-fund' : '' ?>">
                                (Amount: Rs.<?= $amount ?>.00)
                            </span>
                        </button>
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
                    <button id="closeModal" class="submit-btn"
                        onclick="window.location.href='patient_dashboard'">OK</button>
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
        <script src="<?= ROOT ?>/assets/js/Patient/checkout.js"></script>
        <script>

            document.addEventListener('DOMContentLoaded', function () {
                const confirmBtn = document.getElementById('confirmBtn');
                const payHereBtn = document.getElementById('payHereBtn');
                const payLaterBtn = document.getElementById('payLaterBtn');
                const payByWalletBtn = document.getElementById('payByWalletBtn');


                confirmBtn.addEventListener('click', function () {

                    confirmBtn.classList.add('hidden'); // Hide Pay Here button
                    payHereBtn.classList.remove('hidden'); // Show Confirm Appointment button
                    payLaterBtn.classList.remove('hidden');
                    payByWalletBtn.classList.remove('hidden');
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                const confirmBtn = document.getElementById('confirmBtn');
                const payHereBtn = document.getElementById('payHereBtn');
                const payLaterBtn = document.getElementById('payLaterBtn');
                const payByWalletBtn = document.getElementById('payByWalletBtn')
                const popupModal = document.getElementById('popupModal');
                const closeModal = document.getElementById('closeModal');

                const showModal = () => {
                    popupModal.classList.remove('hidden');
                };

                const hideModal = () => {
                    popupModal.classList.add('hidden');
                };


                payLaterBtn.addEventListener('click', showModal);
                payByWalletBtn.addEventListener('click', showModal);
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

        <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const doctor = sessionStorage.getItem('doctor');
                    const specialization = sessionStorage.getItem('specialization');
                    const appointment_id = sessionStorage.getItem('appointment_id');
                    const start_time = sessionStorage.getItem('start_time');
                    const day = sessionStorage.getItem('day');
                    const appointment_fee = sessionStorage.getItem('doctor_fee');

                    document.getElementById('doctor-info').innerHTML = '<strong>Doctor: </strong>' + (doctor ? doctor : 'N/A');
                    document.getElementById('specialization-info').innerHTML = '<strong>Specialization: </strong>' + (specialization ? specialization : 'N/A');
                    document.getElementById('appointment-id-info').innerHTML = '<strong>Appointment Number: </strong>' + (appointment_id ? appointment_id : 'N/A');
                    document.getElementById('start-time-info').innerHTML = '<strong>Start Time: </strong>' + (start_time ? start_time : 'N/A');
                    document.getElementById('day-info').innerHTML = '<strong>Appointment Date: </strong>' + (day ? day : 'N/A');
                    document.getElementById('appointment-fee').innerHTML = '<strong>Appointment Fees: </strong>' + (appointment_fee ? appointment_fee : 'N/A');
                });


                async function sendAppointmentData(paymentMethod) {
                    const data = {
                        doctor: document.getElementById('doctor-info').innerText.replace("Doctor: ", "").trim(),
                        specialization: document.getElementById('specialization-info').innerText.replace("Specialization: ", "").trim(),
                        appointment_date: document.getElementById('day-info').innerText.replace("Appointment Date: ", "").trim(),
                        appointment_time: document.getElementById('start-time-info').innerText.replace("Appointment Time: ", "").trim(),
                        appointment_number: document.getElementById('appointment-id-info').innerText.replace("Appointment Number: ", "").trim(),
                        appointment_fee: document.getElementById('appointment-fee').innerText.replace("Appointment Fees: ", "").trim(),
                        payment_method : paymentMethod,
                        patient_name: "<?= $_SESSION['USER']->first_name; ?> <?= $_SESSION['USER']->last_name; ?>",
                        contact_number: "<?= $_SESSION['USER']->contact; ?>",
                        emergency_contact: "<?= $_SESSION['USER']->emergency_contact_no; ?>"
                    };

                    try {
                        // Send data to the controller
                        const response = await fetch('http://localhost/WellBe/public/patient/getAppointmentdata', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        const text = await response.text();
                        console.log("Raw response:", text);
                        const result = JSON.parse(text);
                        console.log("result",result);
                        if (result.success) {
                            alert("Payment initiated successfully!");
                //  window.location.href = `http://localhost/wellbe/public/patient/patient_dashboard.php`
                        } else {
                            alert("Payment failed. Please try again.");
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        alert("An error occurred while processing the payment.");
                    }
                };

                document.getElementById('payHereBtn').addEventListener('click', () => sendAppointmentData("online"));
                document.getElementById('payLaterBtn').addEventListener('click', () => sendAppointmentData("counter"));
                document.getElementById('payByWalletBtn').addEventListener('click', () => sendAppointmentData("wallet"));

        </script>

</body>

</html>