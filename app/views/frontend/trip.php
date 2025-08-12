<?php require_once APPROOT . '/views/inc/nav.php'; ?>
<?php
    $searchResults = $_SESSION['search_result'] ?? [];
    $searchParams = $_SESSION['search_params'] ?? null;
?>
<style>
    .badge {
        background-color: #eee;
        color: #777;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 0.8em;
        margin-left: 5px;
    }
    /* Your existing CSS for .trip-card and .search-bar-top */
    .trip-card {
        background-color: var(--white);
        border-radius: 10px;
        box-shadow: var(--shadow-light);
        padding: 20px;
        display: flex;
        /* Adjusted gap to distribute space more evenly */
        gap: 30px; /* Reduced gap for better distribution */
        flex-wrap: wrap;
        margin-bottom: 20px;
        align-items: flex-start; /* Align items to the top within the card */
    }
    .search-bar-top {
        background-color: var(--white);
        padding: 20px;
        box-shadow: var(--shadow-light);
        margin-bottom: 30px;
        top: 65px;
        position: fixed;
        z-index: 1;
        max-width: 100%;
        width: 100%;
    }

    /* New and Adjusted styles for detailed layout */

    .trip-time-info {
        flex: 2; /* Allows it to take more space */
        min-width: 250px; /* Ensure it doesn't get too small */
        text-align: left; /* Aligns text left as per image */
    }

    .trip-time-info .time {
        font-size: 28px; /* Larger font for time */
        font-weight: 700;
        color: var(--dark-blue); /* Stronger color for time */
        margin-bottom: 0px; /* No gap between time and date */
    }

    .trip-time-info .date {
        font-size: 16px; /* Date font size */
        color: var(--text-color-dark); /* Darker color for date */
        margin-bottom: 10px; /* Space after date */
    }

    .trip-time-info .route {
        font-size: 18px; /* Route font size */
        font-weight: 600;
        color: var(--text-color-dark);
        margin-bottom: 5px;
    }

    .trip-time-info .arrival-details { /* New class for arrival time line */
        font-size: 15px;
        color: var(--text-color-medium);
        margin-bottom: 3px;
    }

    .trip-time-info .duration { /* New class for estimated duration */
        font-size: 15px;
        color: var(--text-color-medium);
        margin-bottom: 15px; /* Space before NRC message */
    }

    .trip-time-info .nrc-note { /* New class for NRC message */
        font-size: 14px;
        color: #cc0000; /* Red color for warning/note, as seen in image */
        font-weight: 500;
    }

    .trip-operator-info {
        flex: 1; /* Takes less space but can grow */
        min-width: 180px; /* Ensure it has enough space */
        display: flex; /* Use flexbox for vertical alignment */
        flex-direction: column;
        align-items: center; /* Center items horizontally */
        text-align: center;
    }

    .trip-operator-info .operator-logo {
        height: 60px; /* Larger logo */
        width: auto;
        margin-bottom: 10px;
        filter: none; /* Remove grayscale if you want original colors */
    }

    .trip-operator-info .operator-name {
        font-size: 18px; /* Larger operator name */
        font-weight: 700;
        color: var(--text-color-dark);
        margin-bottom: 5px;
    }

    .trip-operator-info .bus-spec { /* New class for bus details */
        font-size: 14px;
        color: var(--text-color-medium);
        margin-bottom: 3px;
    }

    .trip-operator-info .depart-point { /* New class for departure gate */
        font-size: 14px;
        color: var(--text-color-medium);
    }

    .trip-price-actions {
        flex: 0 0 auto; /* No flex grow, fixed width */
        text-align: right; /* Align contents to the right */
        min-width: 180px; /* Ensure space for price and button */
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* Align items to the right */
    }

    .trip-price-actions .price {
        font-size: 26px; /* Larger price font */
        font-weight: 700;
        color: var(--primary-blue); /* Primary color for price */
        margin-bottom: 5px;
    }

    .trip-price-actions .available-seats { /* New class for available seats */
        font-size: 14px;
        color: var(--text-color-medium);
        margin-bottom: 15px;
    }

    .select-trip-btn {
        background-color: var(--primary-blue);
        color: var(--white);
        border: none;
        border-radius: 5px;
        padding: 10px 20px; /* Slightly larger padding */
        font-size: 16px; /* Larger font */
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%; /* Make button full width of its container */
        max-width: 150px; /* Limit max width for button */
    }

    .select-trip-btn:hover {
        background-color: #30419a;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .trip-card {
            flex-direction: column; /* Stack vertically */
            align-items: flex-start; /* Align contents to the left */
            gap: 20px; /* Adjust gap for stacked layout */
        }
        .trip-time-info,
        .trip-operator-info,
        .trip-price-actions {
            width: 100%; /* Take full width when stacked */
            min-width: unset; /* Remove min-width constraints */
            text-align: left; /* Align all text left */
            align-items: flex-start; /* Align flex items to start */
        }
        .trip-operator-info .operator-logo {
            margin-left: 0; /* Remove horizontal centering margin */
        }
        .trip-price-actions {
            align-items: flex-start; /* Align price and button left */
        }
        .select-trip-btn {
            max-width: none; /* Allow button to take full available width */
        }
    }

    /* Inherit existing @media (max-width: 768px) and @media (max-width: 480px) */
    /* Ensure the .trip-time-info .time, .price etc. font sizes scale down as needed */

</style>

<main class="trip-main-content">
    <section class="search-bar-top">
        <div class="search-inputs">
            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <label><?php echo htmlspecialchars($searchParams['from'] ?? ''); ?></label>
            </div>
            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <label><?php echo htmlspecialchars($searchParams['to'] ?? ''); ?></label>
            </div>
            <div class="input-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" value="<?php echo htmlspecialchars($searchParams['date'] ?? ''); ?>" disabled>
            </div>
            <div class="input-group passenger-input">
                <i class="fas fa-users"></i>
                <input type="number" min="1" value="<?php echo htmlspecialchars($searchParams['passengers'] ?? 1); ?>" disabled>
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
            <p id="noRouteMsg" style="color:red; display:none;">No routes found for selected time.</p>
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $route): ?>
                    <?php
                        // Assuming route['departure_time'] and route['arrival_time'] are valid date/time strings
                        $departureTimeFormatted = $route['departure_time'] ? date('h:i A', strtotime($route['departure_time'])) : '';
                        // $departureDateFormatted = $route['departure_time'] ? date('F j', strtotime($route['departure_time'])) : '';
                        // $arrivalTimeFormatted = $route['arrival_time'] ? date('h:i A', strtotime($route['arrival_time'])) : '';
                        // $arrivalDateFormatted = $route['arrival_time'] ? date('F j', strtotime($route['arrival_time'])) : '';
                        $depHour = $route['departure_time'] ? date('G', strtotime($route['departure_time'])) : null;
                    ?>
                    <input type="hidden" name="route_id" value="<?= htmlspecialchars($route['id'] ?? '') ?>">

                    <div class="trip-card" data-departure-hour="<?php echo htmlspecialchars($depHour); ?>">
                        <div class="trip-time-info">
                            <p class="time"><?php echo $departureTimeFormatted; ?> <span class="badge"><?php echo $route['bus_type']; ?></span></p>
                            <p class="route"><?php echo htmlspecialchars($route['from'] . ' - ' . $route['to']); ?></p>
                            <p class="arrival-details"><?php echo htmlspecialchars($route['from'] . ', ' . formatDate($route['departure_time']) .' '); ?> (Depart At)</p>
                            <p class="arrival-details"><?php echo htmlspecialchars($route['to'] . ', '.formatDate($route['arrival_time']) .' ' ); ?> (Arrives At)</p>
                            <p class="nrc-note">Please bring your NRC</p>
                        </div>
                        <div class="trip-operator-info">
                            <?php if (!empty($route['image'])): ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/routes_images/<?php echo htmlspecialchars($route['image']); ?>" class="operator-logo" alt="<?php echo htmlspecialchars($route['operator_name'] ?? 'Operator Logo'); ?>">
                            <?php endif; ?>
                            <p class="operator-name"><?php echo htmlspecialchars($route['operator_name'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="trip-price-actions">
                            <p class="price"><?php echo htmlspecialchars($route['price'] ?? '0'); ?> MMK</p>
                            <p class="available-seats">Available Seats: <?php echo htmlspecialchars($searchParams['passengers'] ?? 1); ?></p>
                            <a href="<?php echo URLROOT; ?>/home/selectseat?route_id=<?php echo urlencode($route['id'] ?? ''); ?>&passengers=<?php echo urlencode($searchParams['passengers'] ?? 1); ?>">
                                <button class="select-trip-btn">Select Trip</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:red;">No routes found for your search.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

<script>
    document.getElementById('reSearchButton').addEventListener('click', () => {
        window.location.href = '<?php echo URLROOT; ?>/pages/index';
    });

    // Filter cards by departure hour
    function applyTimeFilter(filter) {
        const cards = document.querySelectorAll('.trip-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const hour = parseInt(card.dataset.departureHour, 10);
            let show = false;

            if (isNaN(hour)) {
                show = false; // Hide cards with invalid hours
            } else if (filter === 'any') {
                show = true;
            } else if (filter === 'day') {
                show = hour >= 5 && hour < 12;
            } else if (filter === 'afternoon') {
                show = hour >= 12 && hour < 17;
            } else if (filter === 'night') {
                show = hour >= 17 || hour < 5;
            }

            card.style.display = show ? 'flex' : 'none';
            if (show) visibleCount++;
        });

        // Show or hide "No routes found"
        const noRouteMsg = document.getElementById('noRouteMsg');
        if (noRouteMsg) {
            if (visibleCount === 0) {
                noRouteMsg.style.display = 'block';
            } else {
                noRouteMsg.style.display = 'none';
            }
        }
    }

    document.querySelectorAll('input[name="departure-time"]').forEach(radio => {
        radio.addEventListener('change', () => applyTimeFilter(radio.value));
    });

    // Default show all on page load
    applyTimeFilter('any');
</script>