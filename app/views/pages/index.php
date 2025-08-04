<?php require_once APPROOT . '/views/inc/nav.php' ?>
<?php 
   $today = date('Y-m-d'); 
//    var_dump($_SESSION['session_loginuserid']);exit;
?>
<style>
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
                <form class="booking-form" id="searchForm" method="get" action="<?php echo URLROOT; ?>/route/searchAndRedirect">
                    <div class="form-group">
                        <input type="text" placeholder="From" id="from-location" name="from" required>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="To" id="to-location" name="to" required>
                    </div>
                    <div class="form-group">
                        <input type="date" id="departure-date" name="date" required min="<?php echo $today; ?>">
                    </div>
                    <div class="form-group passenger-group">
                        <div class="passenger-control">
                            <button type="button" class="minus-btn"><i class="fas fa-minus-circle"></i></button>
                            <input type="number" id="passengers" name="passengers" value="1" min="1" max="2">
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
                <h3>10+ Bus Operators</h3>
                <p>Choose from 10+ major bus operators covering 100+ destinations.</p>
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
                <p>WaveMoney, A+ Kpay, KBZPay and MABPay.</p>
                <br>
                <a href="#payment" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>
        </section>

        <section id="payment" class="payment-section">
            <h2>We Accept</h2>
            <div class="payment-logos">
                <img src="<?php echo URLROOT; ?>/images/kpay.png" alt="Kpay"> 
                <img src="<?php echo URLROOT; ?>/images/wave.png" alt="Wave Money">
                <img src="<?php echo URLROOT; ?>/images/a+.png" alt="A+ Pay">
                <img src="<?php echo URLROOT; ?>/images/mab.png" alt="MAB Pay">
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
            <h2>Over 10+ Bus Operators</h2>
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
    // Auto-hide flash message after 2 seconds
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500); // Remove after fadeOut completes
        },1500); // Show for 2 seconds
    }
</script>