<?php require_once APPROOT . '/views/inc/nav.php' ?>

    <main class="trip-main-content">
        <section class="search-bar-top">
            <div class="search-inputs">
                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <label id="fromLocationTop"></label>
                </div>
                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <label id="toLocationTop"></label>
                </div>
                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" id="date-top" disabled>
                </div>
                <div class="input-group passenger-input">
                    <i class="fas fa-users"></i>
                    <input type="number" min="1" id="passengers-top" disabled>
                </div>
                <button class="search-now-btn" id="reSearchButton"><i class="fas fa-search"></i>Research</button>
            </div>
        </section>
<br><br><br><br>
        <section class="search-results-section">
            <div class="filter-sidebar">
                <h3>Departure Time</h3>
                <div class="filter-options-group">
                    <div class="filter-option">
                        <input type="radio" id="any-time" name="departure-time" value="any" checked>
                        <label for="any-time">Any Time <br> <span>24 Hours</span></label>
                    </div>
                    <div class="filter-option">
                        <input type="radio" id="day-time" name="departure-time" value="day">
                        <label for="day-time">Day <br> <span>(5 AM - 11:59 AM)</span></label>
                    </div>
                    <div class="filter-option">
                        <input type="radio" id="afternoon-time" name="departure-time" value="afternoon">
                        <label for="afternoon-time">Afternoon <br> <span>(12 PM - 4:59 PM)</span></label>
                    </div>
                    <div class="filter-option">
                        <input type="radio" id="night-time" name="departure-time" value="night">
                        <label for="night-time">Night <br> <span>(5 PM - 4:59 AM)</span></label>
                    </div>
                </div>
            </div>
            
            <div id="resultsContainer" class="results-container">
                <!-- Search results will be dynamically inserted here -->
            </div>
        </section>

        <!-- <div class="back-to-search-container">
            <a href="index.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Search</a>
        </div> -->
    </main>
   <?php require_once APPROOT . '/views/inc/footer.php' ?>
    <script>
        let allBusRoutes = []; // This will hold the full data from index.html's mock data
        let currentFilteredRoutes = []; // This will hold the routes after initial From/To/Date search
        let currentSearchParams = null; // To store search parameters including passengers

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

        // Helper function to convert time (e.g., "7:30 AM") to 24-hour format (e.g., "07:30") for comparison
        function convertTo24Hour(time12h) {
            const [time, period] = time12h.split(' ');
            let [hours, minutes] = time.split(':');
            if (period && period.toUpperCase() === 'PM' && hours !== '12') {
                hours = parseInt(hours, 10) + 12;
            } else if (period && period.toUpperCase() === 'AM' && hours === '12') {
                hours = '00';
            }
            return `${String(hours).padStart(2, '0')}:${minutes}`;
        }

        // Function to render routes based on the provided array
        function renderRoutes(routesToDisplay) {
            const resultsContainer = document.getElementById('resultsContainer');
            resultsContainer.innerHTML = ''; // Clear previous results

            if (routesToDisplay.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="no-routes-found">
                        <p>No routes available for your search criteria or selected time filter.</p>
                        <p>Please adjust your filters or try a different search.</p>
                    </div>
                `;
            } else {
                routesToDisplay.forEach(route => {
                    const routeCard = document.createElement('div');
                    routeCard.classList.add('trip-card');
                    routeCard.innerHTML = `
                        <div class="trip-time-info">
                            <p class="time">${route.time}</p>
                            <p class="day">${route.date}</p>
                            <p class="route">${route.from} - ${route.to}</p>
                            <p class="details">${route.date} (Departs At)</p>
                            <p class="details">${route.to}, ${route.arrival} (Arrives At)</p>
                            <p class="details estimate"><i class="fas fa-clock"></i> Estimate duration: 8 HR 00 Min</p>
                            <p class="nrc-message">Please bring your NRC</p>
                        </div>
                        <div class="trip-operator-info">
                            <img src="${route.image}" alt="${route.operator} Logo" class="operator-logo" onerror="this.onerror=null; this.src='https://placehold.co/80x80/cccccc/333333?text=Logo';">
                            <p class="operator-name">${route.operator}</p>
                            <p class="bus-type">Non A/C, 2+2, 40 Seaters</p>
                            <p class="departure-point">${route.from}, Aung Mingalar (Departs Gate)</p>
                        </div>
                        <div class="trip-price-actions">
                            <p class="price">${route.price}</p>
                            <p class="seats-left">Available Seats: ${currentSearchParams ? currentSearchParams.passengers : '??'}</p>
                            <a href="selectseat.html"><button class="select-trip-btn" data-route-id="${route.operator}-${route.time}-${route.date}">Select Trip</button></a>
                        </div>
                        
                    `;
                    resultsContainer.appendChild(routeCard);
                });

                // IMPORTANT FIX: Re-attach event listeners after rendering new buttons
                document.querySelectorAll('.select-trip-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const routeId = this.dataset.routeId;
                        const selectedRoute = routesToDisplay.find(r => `${r.operator}-${r.time}-${r.date}` === routeId);

                        if (selectedRoute && currentSearchParams) {
                            // Store the selected trip details and the search parameters (including passengers)
                            localStorage.setItem('selectedTripDetails', JSON.stringify(selectedRoute));
                            localStorage.setItem('searchParams', JSON.stringify(currentSearchParams)); 
                            console.log('trip.html: Stored selectedTripDetails and searchParams in localStorage:', selectedRoute, currentSearchParams);
                            window.location.href = 'selectseat.html';
                        } else {
                            // Replaced alert with showMessageBox for consistent UX
                            showMessageBox('Error: Could not select trip or retrieve search parameters.');
                            console.error('Error selecting trip or search params:', selectedRoute, currentSearchParams);
                        }
                    });
                });
            }
        }

        // Function to apply time filter to the already search-filtered routes
        function applyTimeFilter(filterType) {
            let filtered = [];
            
            // Re-filter from the 'currentFilteredRoutes' (which are the initial search results)
            if (filterType === 'any') {
                filtered = currentFilteredRoutes;
            } else if (filterType === 'day') {
                filtered = currentFilteredRoutes.filter(route => {
                    const time24h = convertTo24Hour(route.time);
                    const [hours] = time24h.split(':');
                    const hour = parseInt(hours, 10);
                    // Day: 5 AM (05:00) to 11:59 AM (11:59)
                    return hour >= 5 && hour < 12; 
                });
            } else if (filterType === 'afternoon') {
                filtered = currentFilteredRoutes.filter(route => {
                    const time24h = convertTo24Hour(route.time);
                    const [hours] = time24h.split(':');
                    const hour = parseInt(hours, 10);
                    // Afternoon: 12 PM (12:00) to 4:59 PM (16:59)
                    return hour >= 12 && hour < 17; 
                });
            }
            else if (filterType === 'night') {
                filtered = currentFilteredRoutes.filter(route => {
                    const time24h = convertTo24Hour(route.time);
                    const [hours] = time24h.split(':');
                    const hour = parseInt(hours, 10);
                    // Night: 5 PM (17:00) to 4:59 AM (04:59) (spans across midnight)
                    return hour >= 17 || hour < 5; 
                });
            }
            renderRoutes(filtered);
        }

        // --- DOMContentLoaded for initial page setup ---
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle mobile navigation for trip.html
            document.getElementById('mobile-menu').addEventListener('click', function() {
                document.getElementById('main-nav').classList.toggle('active');
            });

            // Get initial search parameters and data from localStorage
            const initialFilteredRoutesJSON = localStorage.getItem('initialFilteredBusRoutes');
            const allBusRoutesJSON = localStorage.getItem('allBusRoutesData'); // Get all routes for full filtering
            const searchParamsJSON = localStorage.getItem('searchParams');

            if (allBusRoutesJSON) {
                allBusRoutes = JSON.parse(allBusRoutesJSON); // Store all data in global variable
            }

            if (searchParamsJSON) {
                currentSearchParams = JSON.parse(searchParamsJSON); // Store search params including passengers
                
                // Populate top search bar with remembered values
                document.getElementById('fromLocationTop').textContent = currentSearchParams.from;
                document.getElementById('toLocationTop').textContent = currentSearchParams.to;
                document.getElementById('date-top').value = currentSearchParams.date;
                document.getElementById('passengers-top').value = currentSearchParams.passengers;

                // Set currentFilteredRoutes based on the initial search results
                if (initialFilteredRoutesJSON) {
                    currentFilteredRoutes = JSON.parse(initialFilteredRoutesJSON);
                } else {
                    // Fallback: If initialFilteredRoutes is somehow missing, try to filter from allBusRoutes
                    currentFilteredRoutes = allBusRoutes.filter(route => {
                        return route.from.toLowerCase() === currentSearchParams.from.toLowerCase() &&
                               route.to.toLowerCase() === currentSearchParams.to.toLowerCase() &&
                               route.date === currentSearchParams.date;
                    });
                }
                
                // Initial render based on the default 'any' filter (or previously selected)
                const selectedFilter = document.querySelector('input[name="departure-time"]:checked').value;
                applyTimeFilter(selectedFilter);

            } else {
                // If direct access or no valid search params, show a default message
                document.getElementById('resultsContainer').innerHTML = '<div class="no-routes-found"><p>Please go back to the home page to search for routes.</p></div>';
            }

            // Add event listeners for radio buttons to apply filter on change
            document.querySelectorAll('input[name="departure-time"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    applyTimeFilter(this.value);
                });
            });

            // Re-search button logic: Redirects to index.html to allow a new search
            document.getElementById('reSearchButton').addEventListener('click', function() {
                window.location.href = '<?php echo URLROOT; ?>/pages/index'; 
            });
            // seat button logic: Redirects to index.html to allow a new search
            document.getElementById('seat').addEventListener('click', function() {
                window.location.href = 'seat.html'; 
            });
        });
    </script>
