<?php require_once APPROOT . '/views/inc/nav.php' ?>
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
                <form class="booking-form" id="searchForm">
                    <div class="form-group">
                        <input type="text" placeholder="From" id="from-location" name="from-location">
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="To" id="to-location" name="to-location">
                    </div>
                    <div class="form-group">
                        <input type="date" id="departure-date" name="departure-date">
                    </div>
                    <div class="form-group passenger-group">
                        <div class="passenger-control">
                            <button type="button" class="minus-btn"><i class="fas fa-minus-circle"></i></button>
                            <input type="number" id="passengers" name="passengers" value="1" min="1" max="2">
                            <button type="button" class="plus-btn"><i class="fas fa-plus-circle"></i></button>
                        </div>
                    </div>
                    <a href="<?php echo URLROOT; ?>/home/trip">
                        <button type="button" class="search-button" id="searchRoutesButton">
                            <i class="fas fa-search"></i>Search Now
                        </button>
                    </a>
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
                <img src="payment/kpay.png" alt="Kpay"> 
                <img src="payment/wave.png" alt="Wave Money">
                <img src="payment/a+.png" alt="A+ Pay">
                <img src="payment/mab.png" alt="MAB Pay">
            </div>
        </section>

        <section class="routes-section">
            <h2>Popular Routes</h2>
            <div class="route-cards">
                <div class="route-card">
                    <img src="route/bagan.jpg" alt="Yangon - Bagan"> <p>Yangon - Bagan</p>
                </div>
                <div class="route-card">
                    <img src="route/mdy.jpg" alt="Yangon - Mandalay">
                    <p>Yangon - Mandalay</p>
                </div>
                <div class="route-card">
                    <img src="route/ygn.jpg" alt="Mandalay - Bagan">
                    <p>Mandalay - Yangon</p>
                </div>
            </div>
        </section>

        <section id="operator" class="operators-section">
            <h2>Over 10+ Bus Operators</h2>
            <div class="operator-logos">
                <img src="operator/jj.png" alt="JJ Express">
                <img src="operator/s.png" alt="S Bus">
                <img src="operator/shwemandalar.png" alt="Shwe Mandalar">
                <img src="operator/lunimi.png" alt="Lumbini">
                <img src="operator/myat.png" alt="Myat Express">
                <img src="operator/k.png" alt="K Express">
            </div>
        </section>
    </main>
<?php require_once APPROOT . '/views/inc/footer.php' ?>
 
 <script>
        // Mock Route Data - IMPORTANT: Ensure image paths are correct relative to CSS folder
        const allRoutesData = [
            { from: 'Yangon', to: 'Mandalay', date: '2025-06-15', operator: 'ShweMandalar', price: '46,000 MMK', time: '7:30 AM', image: 'operator/shwemandalar.png', arrival: '4:00 PM' },
            { from: 'Yangon', to: 'Mandalay', date: '2025-06-16', operator: 'EliteExpress', price: '50,000 MMK', time: '8:00 AM', image: 'operator/s.png', arrival: '5:00 PM' },
            { from: 'Mandalay', to: 'Yangon', date: '2025-06-15', operator: 'Lumbini', price: '45,000 MMK', time: '9:00 PM', image: 'operator/lunimi.png', arrival: '6:00 AM' },
            { from: 'Yangon', to: 'Bagan', date: '2025-06-20', operator: 'JJ Express', price: '30,000 MMK', time: '6:00 AM', image: 'operator/jj.png', arrival: '1:00 PM' },
            { from: 'Bagan', to: 'Yangon', date: '2025-06-20', operator: 'Myat Express', price: '32,000 MMK', time: '7:00 PM', image: 'operator/myat.png', arrival: '2:00 AM' },
            { from: 'Yangon', to: 'Nay Pyi Taw', date: '2025-06-17', operator: 'K Express', price: '25,000 MMK', time: '10:00 PM', image: 'operator/k.png', arrival: '5:00 AM' },
            { from: 'Yangon', to: 'Mandalay', date: '2025-06-15', operator: 'S Bus', price: '48,000 MMK', time: '1:00 PM', image: 'operator/s.png', arrival: '8:00 PM' }
        ];

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

        // Function to get today's date in YYYY-MM-DD format
        function getTodayDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Set the minimum date for the departure date input
        document.addEventListener('DOMContentLoaded', function() {
            const departureDateInput = document.getElementById('departure-date');
            if (departureDateInput) {
                departureDateInput.min = getTodayDate();
                // Optionally set default value to today for convenience if not already set
                if (!departureDateInput.value) {
                    departureDateInput.value = getTodayDate();
                }
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

            // Search functionality
            document.getElementById('searchRoutesButton').addEventListener('click', function() {
                const fromLocation = document.getElementById('from-location').value.trim();
                const toLocation = document.getElementById('to-location').value.trim();
                const departureDate = document.getElementById('departure-date').value; 
                const passengers = document.getElementById('passengers').value;

                // Basic validation for empty fields
                if (!fromLocation || !toLocation || !departureDate) {
                    showMessageBox('Please fill in all search fields (From, To, Date).');
                    return;
                }

                // Date validation: Cannot search past days
                const todayDate = getTodayDate();
                if (departureDate < todayDate) {
                    showMessageBox('You cannot search for routes on a past date. Please choose today or a future date.');
                    // Clear filtered results and set searchPerformed to true so trip.html shows 'no routes available'
                    localStorage.setItem('initialFilteredBusRoutes', JSON.stringify([])); 
                    localStorage.setItem('searchPerformed', 'true');
                    localStorage.setItem('searchParams', JSON.stringify({
                        from: fromLocation,
                        to: toLocation,
                        date: departureDate, // Still send the chosen (past) date for display
                        passengers: passengers
                    }));
                    window.location.href = 'trip.html'; // Redirect to trip.html to show "no routes"
                    return; // Stop further execution
                }

                const filteredRoutes = allRoutesData.filter(route => {
                    const routeDate = route.date;
                    return route.from.toLowerCase() === fromLocation.toLowerCase() &&
                           route.to.toLowerCase() === toLocation.toLowerCase() &&
                           routeDate === departureDate;
                });

                // Store *all* routes for filtering on trip.html, and the initial filtered results
                localStorage.setItem('allBusRoutesData', JSON.stringify(allRoutesData)); // Store full data
                localStorage.setItem('initialFilteredBusRoutes', JSON.stringify(filteredRoutes)); // Store initial search result
                localStorage.setItem('searchPerformed', 'true');
                localStorage.setItem('searchParams', JSON.stringify({
                    from: fromLocation,
                    to: toLocation,
                    date: departureDate,
                    passengers: passengers
                }));

                // Redirect to trip.html
                window.location.href = 'trip.html';
            });
        });
</script>