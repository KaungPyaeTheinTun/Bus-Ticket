<?php require_once APPROOT . '/views/inc/sidebar.php' ?>
<style>
    /* Style for inputs with icons */
        .input-with-icon {
            position: relative; /* Needed for absolute positioning of the icon */
            display: flex; /* Helps align icon and input horizontally */
            align-items: center; /* Vertically centers the icon with the input */
            width: 100%; /* Ensure it takes full width of its parent form-group */
        }

        .input-with-icon .text-input {
            padding-left: 35px; /* Make space for the icon on the left */
            width: 100%; /* Ensure input fills the wrapper */
        }

        .input-with-icon .icon {
            position: absolute; /* Position the icon freely within its parent */
            left: 10px; /* Adjust as needed to position the icon */
            color: #555; /* Icon color */
            font-size: 16px; /* Icon size */
            pointer-events: none; /* Allows clicks to pass through to the input */
        }

        /* Specific styling for route inputs to align them horizontally */
        .route-inputs {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between "From" and "To" fields */
        }

        .route-inputs .input-with-icon {
            flex: 1; /* Allows inputs to grow and share space */
        }

        .route-separator {
            font-size: 20px; /* Adjust arrow size */
            color: #888;
        }
</style>
<div class="container">
        
        <main class="main-content">
            <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
           <section class="add-operator-card">
                <form class="operator-form" method="POST" action="<?php echo URLROOT; ?>/operator/store">
                    <div class="form-group">
                        <!-- <label for="operatorName">Name</label> -->
                        <input type="text" id="operatorName" name="name" class="text-input" placeholder="Enter operator name">
                    </div>

                    <div class="form-group">
                        <!-- <label for="phoneNumber">Phone Number</label> -->
                        <input type="text" id="phoneNumber" name="phone" class="text-input" placeholder="Enter phone number">
                    </div>

                    <div class="form-group">
                        <!-- <label for="seatCapacity">Seat Capacity</label> -->
                        <div class="input-with-icon">
                            <i class="fas fa-chair icon"></i> <input type="number" id="seatCapacity" name="seat_capacity" class="text-input number-input" placeholder="Enter seat capacity" min="1">
                        </div>
                    </div>
                    <button type="submit" class="confirm-button">Confirm</button>
                    <br>
                    <a href="<?php echo URLROOT; ?>/operator"><i class="fas fa-arrow-left"></i> Back</a>
                </form>
            </section>

    </div>
</body>
<script>
function togglePasswordVisibility(id) {
            const passwordInput = document.getElementById(id);
            const toggleIcon = passwordInput.nextElementSibling.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        // Seat Selection Logic
        const seatBoxes = document.querySelectorAll('.seat-box.available'); // Only clickable if available
        seatBoxes.forEach(seat => {
            seat.addEventListener('click', function() {
                // Toggle 'selected' class only if it's not a 'sold' seat
                if (!this.classList.contains('sold')) {
                    this.classList.toggle('selected');
                }
            });
        });

        // Reset Seats Functionality
        document.getElementById('resetSeatsButton').addEventListener('click', function() {
            const selectedSeats = document.querySelectorAll('.seat-box.selected');
            selectedSeats.forEach(seat => {
                seat.classList.remove('selected');
            });
            // Optional: You might want to also reset 'sold' seats to 'available' if that's the intention of "reset all seats"
            // For now, it only clears 'selected' seats. If you want to reset ALL to available:
            // const allSeats = document.querySelectorAll('.seat-box');
            // allSeats.forEach(seat => {
            //     seat.classList.remove('sold', 'selected');
            //     seat.classList.add('available');
            // });
            alert('All selected seats have been reset!'); // Optional: provide user feedback
        });

        // Tabs functionality (simple example: highlight active tab)
        const tabs = document.querySelectorAll('.tabs-container .tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                // In a real application, you'd load/filter seat data here based on the selected tab (Morning/Evening)
            });
        });
    </script>
</html>