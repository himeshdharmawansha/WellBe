<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FInal Scheduling</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/Schedule.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
    <?php
        $this->renderComponent('navbar', $active);
        ?>
        
        <!-- Main Content -->
        <div class="main-content">
        <?php
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->

                <div class="container">
                    <!-- Doctor Information Section -->
                    <div class="doctor-info">
                        <div class="profile">
                            <h2>Dr.C.A.B MAKULOLUWA</h2>
                            <h3>(Eye Surgeon)</h3>
                        </div>
                        <div class="details">
                            <h4>PROFESSIONAL QUALIFICATIONS & MEMBERSHIPS</h4>
                            <p>MS, Consultant Ophthalmic Surgeon</p>
                            <br/>
                            <h4>AREAS OF SPECIALISATIONS</h4>
                            <p>Ophthalmology</p>
                            <br/>
                            <h4>LANGUAGES</h4>
                            <p>English/Sinhala</p>
                            <br/>
                            <h4>AVAILABILITY</h4>
                            <p>Part Time</p>
                            <br/>
                            <h4>CONTACT</h4>
                            <p>0773 131 955</p>
                            <br/>
                            <h4>EMAIL</h4>
                            <p>cabmakuloluwa@yahoo.com</p>
                        </div>
                    </div>
            
                    <!-- Booking Information Section -->
                    <div class="booking-info">
                        <h2 class="booking-title">Booking Information</h2>
                        <div class="slots">
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">20</span> <!-- Green circle, no button -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">00</span> <!-- Red circle, button visible -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">14</span> <!-- Red circle, button visible -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">20</span>
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">20</span> <!-- Green circle, no button -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">00</span> <!-- Red circle, button visible -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">14</span> <!-- Red circle, button visible -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">20</span>
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">20</span> <!-- Green circle, no button -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">00</span> <!-- Red circle, button visible -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">14</span> <!-- Red circle, button visible -->
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            <div class="slot">
                                <p>2024 Aug 23<br>08:00</p>
                                <div class="slot-details">
                                    <span class="available-slots">20</span>
                                    <button class="book-now">Book Now</button>
                                </div>
                                <p class="available-text">Available Slots</p>
                            </div>
                            
                            
                            <!-- More booking slots can be added here -->
                        </div>
                    </div>
                </div>
                <script>
                    document.querySelectorAll('.slot').forEach(slot => {
                        const availableSlots = parseInt(slot.querySelector('.available-slots').textContent);
                        const availableSlotElement = slot.querySelector('.available-slots');
                        const bookNowButton = slot.querySelector('.book-now');
                        const availableText = slot.querySelector('.available-text');
                
                        // If available slots are 20, make the circle green and display "Booking Full"
                        if (availableSlots === 20) {
                            availableSlotElement.classList.add('red-circle');
                            bookNowButton.classList.add('hidden');
                            availableText.textContent = "Booking Full";  // Replace the text
                        } else {
                            availableSlotElement.classList.add('green-circle');
                            bookNowButton.classList.remove('hidden');
                            availableText.textContent = "Available Slots"; // Reset the text when slots are less than 20
                        }
                    });
                </script>
                
                
</body>
</html>
