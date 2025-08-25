<?php require_once APPROOT . '/views/inc/nav.php' ?>
<?php 
   $today = date('Y-m-d'); 
?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .booking-form select {
        width: 100%;
        font-family: 'Poppins', sans-serif;
        color:#333;
        padding: 10px 15px;
        border: 1px solid var(--input-border);
        border-radius: 5px;
        font-size: 16px;
        outline: none;
        appearance: none; /* Remove default arrow in some browsers */
        background-color: var(--white);
        transition: border-color 0.3s ease;
    }

    .booking-form select:focus {
        border-color: var(--primary-blue);
    }

    .form-group select {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
    }
    .form-group select:focus {
        border-color: #3f51b5;
        outline: none;
    }
    .payment-note {
        font-size: 14px;
        color: #555;
        margin-bottom: 1px;
        font-style: italic;
        margin-top:-30px;
    }
    .flatpickr-calendar {
        font-size: 12px;
        width: 300px; /* caution: use only if necessary */
        }

       .flash-message {
            position: fixed;
            top: 28px;
            left: 40%;
            /* transform: translateX(0); */
            padding: 16px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            animation: fadeInScale 0.3s ease;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            /* border-left: 5px solid #28a745; */
        }

        .error-message {
            background-color:rgb(239, 154, 161);
            color: #721c24;
            /* border-left: 5px solid #dc3545; */
        }
        @keyframes fadeOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform:  scale(0.9);
            }
        }
</style>
<?php if (!empty($_SESSION['error'])): ?>
    <div id="flashMessage" class="flash-message error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
     </div>
<?php endif; ?>
    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h2>Get Bus Tickets Across Myanmar</h2>
                <p>Fast and easy - book in just 3 minutes!</p>

                <div class="cta-buttons">
                    <button class="cta-button primary"><i class="fas fa-ticket-alt"></i> Get Your Preferred Seat</button>
                    <button class="cta-button secondary"><i class="fas fa-headset"></i> 24/7 Support</button>
                </div>
                <div class="cta-buttons">
                    <button class="cta-button secondary"><i class="fas fa-bus"></i> 10+ Operators</button>
                    <button class="cta-button secondary"><i class="fas fa-check-circle"></i> Instant Confirmation</button>
                </div>
            </div>
            <div class="booking-form-container">
                <form class="booking-form" id="searchForm" method="get" action="<?php echo URLROOT; ?>/home/searchAndRedirect">
                    <div class="form-group">
                        <input type="text" list="from-location" id="from" name="from" placeholder="From" required>
                        <datalist id="from-location">
                            <?php foreach ($data['from'] as $row): ?>
                            <option value="<?php echo htmlspecialchars($row['from']); ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <input type="text" list="to-location" id="to" name="to" placeholder="To" required>
                        <datalist id="to-location">
                            <?php foreach ($data['to'] as $row): ?>
                            <option value="<?php echo htmlspecialchars($row['to']); ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <input type="text" id="departureDateTime" name="departure_time" class="text-input" placeholder="Departure Date & Time" >
                    </div>
                    <div class="form-group passenger-group">
                        <div class="passenger-control">
                            <button type="button" class="minus-btn"><i class="fas fa-minus-circle"></i></button>
                            <input type="number" id="passengers" name="passengers" value="1" min="1" max="3">
                            <button type="button" class="plus-btn"><i class="fas fa-plus-circle"></i></button>
                        </div>
                    </div>
                        <button type="submit" class="search-button" id="searchRoutesButton">
                            <i class="fas fa-search"></i>Search Now
                        </button>
                </form> 

            </div>           
        </section>
<br><br><br>    
        <section class="features-section">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bus"></i></div>
                <h3>5+ Bus Operators</h3>
                <p>Choose from 5+ major bus operators covering 10+ destinations.</p>
                <a href="#operator" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-book-reader"></i></div>
                <h3>Instant Booking</h3>
                <p>Book your trip in less than 3 minutes. Instant confirmation after payment.</p>
                <a href="#" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Secure Payment</h3>
                <p>WaveMoney, A+, KBZPay, CB Bank and MABPay.</p>
                <br>
                <a href="#payment" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </section>

        <section id="payment" class="payment-section">
            <h2>We Accept<br><span class="payment-note">
                    Pay securely using your preferred payment method – fast, safe, and convenient!
            </span></h2>
            <div class="payment-logos">
                <img src="<?php echo URLROOT; ?>/images/kpay.png" alt="Kpay"> 
                <img src="<?php echo URLROOT; ?>/images/wave.png" alt="Wave Money">
                <img src="<?php echo URLROOT; ?>/images/a+.png" alt="A+ Pay">
                <img src="<?php echo URLROOT; ?>/images/mab.png" alt="MAB Pay">
                <img src="<?php echo URLROOT; ?>/images/cb.png" alt="CB Pay">
            </div>
        </section>

        <section class="routes-section">
            <h2>Popular Routes</h2>
            <div class="route-cards">
                <div class="route-card">
                    <img src="<?php echo URLROOT; ?>/images/bagan.jpg" alt="Yangon - Bagan"> <p>Yangon - Bagan</p>
                </div>
                <div class="route-card">
                    <img src="<?php echo URLROOT; ?>/images/mdy.jpg" alt="Yangon - Mandalay">
                    <p>Yangon - Mandalay</p>
                </div>
                <div class="route-card">
                    <img src="<?php echo URLROOT; ?>/images/ygn.jpg" alt="Mandalay - Bagan">
                    <p>Mandalay - Yangon</p>
                </div>
            </div>
        </section>

        <section id="operator" class="operators-section">
            <h2>Over 5+ Bus Operators<br><span class="payment-note">
                   Choose from 5+ major bus operators covering 10+ destinations.
            </span></h2>
            <div class="operator-logos">
                <img src="<?php echo URLROOT; ?>/images/jj.png" alt="JJ Express">
                <img src="<?php echo URLROOT; ?>/images/s.png" alt="S Bus">
                <img src="<?php echo URLROOT; ?>/images/shwemandalar.png" alt="Shwe Mandalar">
                <img src="<?php echo URLROOT; ?>/images/lunimi.png" alt="Lumbini">
                <img src="<?php echo URLROOT; ?>/images/myat.png" alt="Myat Express">
                <img src="<?php echo URLROOT; ?>/images/k.png" alt="K Express">
            </div>
        </section>
    </main>
<?php require_once APPROOT . '/views/inc/footer.php' ?>
 
 <script>

        // Custom message box function (replaces alert for better UX)
        function showMessageBox(message) {
            const messageBox = document.createElement('div');
            messageBox.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 1000;
                text-align: center;
                max-width: 300px;
                font-size: 1.1em;
                color: #333;
            `;
            messageBox.innerHTML = `
                <p>${message}</p>
                <button style="margin-top: 15px; padding: 8px 15px; background-color: #0d47a1; color: white; border: none; border-radius: 5px; cursor: pointer;">OK</button>
            `;
            document.body.appendChild(messageBox);
            messageBox.querySelector('button').addEventListener('click', () => {
                document.body.removeChild(messageBox);
            });
        }
            // Toggle mobile navigation
            document.getElementById('mobile-menu').addEventListener('click', function() {
                document.getElementById('main-nav').classList.toggle('active');
            });

            // Passenger counter logic
            const passengerInput = document.getElementById('passengers');
            const maxPassengers = parseInt(passengerInput.max); // Get max from HTML attribute

            document.querySelector('.plus-btn').addEventListener('click', function() {
                let currentPassengers = parseInt(passengerInput.value);
                if (currentPassengers < maxPassengers) {
                    passengerInput.value = currentPassengers + 1;
                } 
            });

            document.querySelector('.minus-btn').addEventListener('click', function() {
                let currentPassengers = parseInt(passengerInput.value);
                if (currentPassengers > parseInt(passengerInput.min)) { // Ensure it doesn't go below min
                    passengerInput.value = currentPassengers - 1;
                } 
            });
</script>
<script>
    flatpickr("#departureDateTime", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    // Auto-hide flash message after 2 seconds
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500); // Remove after fadeOut completes
        },1500); // Show for 2 seconds
    }
</script>