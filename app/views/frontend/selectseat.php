<?php require_once APPROOT . '/views/inc/nav.php'; ?>
<?php
    $trip = $data['trip'] ?? [];
    $route_id = $trip['route_id'] ?? '';
    $from = $trip['from'] ?? '';
    $to = $trip['to'] ?? '';
    $departure_time = $trip['departure_time'] ?? '';
    $arrival_time = $trip['arrival_time'] ?? '';
    $operator_name = $trip['operator_name'] ?? '';
    $price = $trip['price'] ?? 0;
    $passengers = $trip['passengers'] ?? 1;
?>

<?php
    $departureFormatted = $departure_time ? date('F j g:i A', strtotime($departure_time)) : '';
    $arrivalFormatted = $arrival_time ? date('F j g:i A', strtotime($arrival_time)) : '';
?>

<style>
    .seat-box.selected {
    background-color: #4caf50 !important; /* Green when selected */
    color: white;
    border-color: #388e3c;
    }

    .select-seat-main {
    max-width: 1000px;
    margin: 10px auto 60px;
    margin-top:-50px;
    padding: 0 20px;
    }
    .seat-grid-container 
    {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 columns for seats */
        gap: 15px;
        padding: 10px;
        background-color: #f8f8f8; /* Light background for seat area */
        border-radius: 8px;
        flex-grow: 1; /* Allow it to take available space */
    }

    .seat-box {
        width: 75px; /* Fixed width for seats */
        height: 50px; /* Fixed height for seats */
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ddd; /* Default available seat color (lighter grey) */
        border: 1px solid #bbb;
        border-radius: 5px;
        font-weight: bold;
        font-size: 1.1em;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
</style>

    <main class="select-seat-main">
        <section class="seat-selection-section">
            <div class="selection-card">
                <h2>Seat Selection</h2>
                <div class="card-content">
                    <div class="seat-map-container">
                        <h3 id="seatSelectionMessage">Please select <span id="requiredSeatsCount"><?= htmlspecialchars($passengers) ?></span> seat</h3>
                        <div class="seat-map" id="seatMap">
                            <!-- Driver seat is static in HTML -->
                            <div class="seat-row driver-row">
                                <div class="seat driver-seat">Driver</div>
                            </div>
                            <div class="seat-grid-container">
                                <div class="seat-box available">1</div>
                                <div class="seat-box available">2</div> 
                                <div class="seat-box available">3</div>
                                <div class="seat-box available">4</div>
                                <div class="seat-box available">5</div>
                                <div class="seat-box available">6</div>
                                <div class="seat-box available">7</div>
                                <div class="seat-box available">8</div>
                                <div class="seat-box available">9</div>
                                <div class="seat-box available">10</div>
                                <div class="seat-box available">11</div>
                                <div class="seat-box available">12</div>
                                <div class="seat-box available">13</div>
                                <div class="seat-box available">14</div>
                                <div class="seat-box available">15</div>
                                <div class="seat-box available">16</div>
                                <div class="seat-box available">17</div>
                                <div class="seat-box available">18</div>
                                <div class="seat-box available">19</div>
                                <div class="seat-box available">20</div>
                                <div class="seat-box available">21</div>
                                <div class="seat-box available">22</div>
                                <div class="seat-box available">23</div>
                                <div class="seat-box available">24</div>
                                <div class="seat-box available">25</div>
                                <div class="seat-box available">26</div>
                                <div class="seat-box available">27</div>
                                <div class="seat-box available">28</div>
                                <div class="seat-box available">29</div>
                                <div class="seat-box available">30</div>
                                <div class="seat-box available">31</div>
                                <div class="seat-box available">32</div>
                            </div>
                        </div>
                    </div>

                    <div class="trip-summary-container">
                        <h3>Trip Summary</h3>
                        <div class="summary-details">
                        <p>
                            <span id="summaryFromLocation"><?= htmlspecialchars($from) ?></span>
                            <span class="date" id="summaryDepartureDateTime"><?= htmlspecialchars($departureFormatted) ?></span>
                        </p>
                        <p>
                            <span id="summaryToLocation"><?= htmlspecialchars($to) ?></span>
                            <span class="date" id="summaryArrivalDateTime"><?= htmlspecialchars($arrivalFormatted) ?></span>
                        </p>                            
                        <p class="small-text">*Arrival times are estimates and may be subject to change.</p>
                        <hr>
                        <p>Bus Operator <span id="summaryBusOperator"><?= htmlspecialchars($operator_name) ?></span></p>
                        <p>Ticket Price <span id="summaryTicketPrice"><?= htmlspecialchars($price) ?> MMK</span></p>
                        <p>Seat Number <span id="summaryNumberOfSeats">0</span></p>
                        <p class="total-price">Total Ticket Price <span style="color:green" id="summaryTotalPrice">MMK 0</span></p>
                        <div class="notice-box">
                            Notices - Please bring your NRC
                        </div>
                        <form action="<?= URLROOT ?>/seat/store" method="post" id="seatForm">
                            <input type="hidden" name="route_id" value="<?= $route_id ?>">
                            <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="">
                            <button type="submit" class="continue-payment-btn" id="continuePaymentBtn" disabled>Continue to payment</button>
                        </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

<script>
    
document.addEventListener('DOMContentLoaded', function() {
    const maxSelectableSeats = <?= (int)$passengers ?>;
    const seatBoxes = document.querySelectorAll('.seat-box.available');
    const summaryNumberOfSeats = document.getElementById('summaryNumberOfSeats');
    const summaryTotalPrice = document.getElementById('summaryTotalPrice');
    const continueBtn = document.getElementById('continuePaymentBtn');
    const pricePerSeat = <?= (int)$price ?>;
    const seatForm = document.getElementById('seatForm');
    const selectedSeatsInput = document.getElementById('selectedSeatsInput');

    let selectedSeats = [];

    seatBoxes.forEach(box => {
        box.addEventListener('click', () => {
            const seatNumber = box.textContent.trim();

            if (box.classList.contains('selected')) {
                // Deselect
                box.classList.remove('selected');
                selectedSeats = selectedSeats.filter(num => num !== seatNumber);
            } else {
                if (selectedSeats.length < maxSelectableSeats) {
                    box.classList.add('selected');
                    selectedSeats.push(seatNumber);
                }
            }

            // Update summary
            summaryNumberOfSeats.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '0';
            summaryTotalPrice.textContent = `MMK ${selectedSeats.length * pricePerSeat}`;

            // Enable or disable continue button
            continueBtn.disabled = (selectedSeats.length !== maxSelectableSeats);
        });
    });

    // Submit form when continue is clicked
    continueBtn.addEventListener('click', function(e) {
    e.preventDefault();
    if (selectedSeats.length === maxSelectableSeats) {
        selectedSeatsInput.value = selectedSeats.join(',');
        seatForm.submit();
    }
});
});
</script>