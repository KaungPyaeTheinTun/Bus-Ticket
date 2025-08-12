<?php require_once APPROOT . '/views/inc/nav.php'; ?>
<?php
    $trip = $data['trip'] ?? [];
    $bookedSeatNumbers = $data['bookedSeatNumbers'];
?>
<?php if (!empty($_SESSION['error'])): ?>
    <div id="flashMessage" class="flash-message error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<style>
    .flash-message {
        position: fixed;
        top: 28px;
        left: 40%;
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
    }

    .error-message {
        background-color: rgb(239, 154, 161);
        color: #721c24;
    }

    @keyframes fadeOut {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.9); }
    }

    .seat-box.selected {
        background-color: #4caf50 !important;
        color: white;
        border-color: #388e3c;
    }

    .seat-box.booked {
        background-color:rgba(80, 79, 79, 0.91);
        color: #999;
        cursor: not-allowed;
        border-color: #ccc;
        pointer-events: none;
    }

    .select-seat-main {
        max-width: 1000px;
        margin: 10px auto 60px;
        padding: 0 20px;
    }

    .seat-grid-container {
        display: grid;
        grid-template-columns: repeat(4, 60px);  /* 4 seats per row */
        gap: 10px 40px; /* spacing between rows and columns */
        justify-content: center;
        margin: 20px 0;
    }

    .seat-box {
        width: 70px;
        /* max-width: 100%; */
        aspect-ratio: 3 / 2;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ddd;
        border: 1px solid #bbb;
        border-radius: 5px;
        font-weight: bold;
        font-size: 1.1em;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    @media (max-width: 768px) {
        .seat-box {
            font-size: 0.9em;
        }
    }
    .seat-map-container{
        max-height:900px;
    }
    .selection-card{
        max-height:900px;
    }
</style>

<main class="select-seat-main">
    <section class="seat-selection-section">
        <div class="selection-card">
            <h2>Seat Selection</h2>
            <div class="card-content">
                <div class="seat-map-container">
                    <h3 id="seatSelectionMessage">
                        Please select <span id="requiredSeatsCount"><?= htmlspecialchars($trip['passengers']) ?></span> seat
                    </h3>
                    <div class="seat-map" id="seatMap">
                        <div class="seat-row driver-row">
                            <div class="seat driver-seat">Driver</div>
                        </div>
                        <div class="seat-grid-container">
                            <?php if (htmlspecialchars($trip['bus_type']) == 'VIP'): ?>
                                <?php for ($i = 1; $i <= (int)$trip['seat_capacity']; $i += 3): ?>
                                    <div class="seat-row">
                                        <?php for ($j = 0; $j < 3; $j++): ?>
                                            <?php
                                                $seatNumber = $i + $j;
                                                if ($seatNumber > (int)$trip['seat_capacity']) break;
                                                if ($j === 2): // Insert spacer before the 3rd seat
                                            ?>
                                                <div class="seat-spacer"></div>
                                            <?php endif; ?>
                                            <?php
                                                $isBooked = in_array($seatNumber, $bookedSeatNumbers);
                                                $seatClass = $isBooked ? 'booked' : 'available';
                                            ?>
                                            <div class="seat-box <?= $seatClass ?>" data-seat-number="<?= $seatNumber ?>">
                                                <?= htmlspecialchars($seatNumber) ?>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                <?php endfor; ?>

                                
                                <?php else: ?>
                                <?php for ($i = 1; $i <= (int)$trip['seat_capacity']; $i++): ?>
                                    <?php
                                        $isBooked = in_array($i, $bookedSeatNumbers);
                                        $seatClass = $isBooked ? 'booked' : 'available';
                                    ?>
                                    <div class="seat-box <?= $seatClass ?>" data-seat-number="<?= $i ?>">
                                        <?= htmlspecialchars($i) ?>
                                    </div>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="trip-summary-container">
                    <h3>Trip Summary</h3>
                    <div class="summary-details">
                        <p>
                            <span id="summaryFromLocation"><?= htmlspecialchars($trip['from']) ?></span>
                            <span class="date" id="summaryDepartureDateTime"><?= formatDate($trip['departure_time']) ?></span>
                        </p>
                        <p>
                            <span id="summaryToLocation"><?= htmlspecialchars($trip['to']) ?></span>
                            <span class="date" id="summaryArrivalDateTime"><?= formatDate($trip['arrival_time']) ?></span>
                        </p>
                        <p class="small-text">*Arrival times are estimates and may be subject to change.</p>
                        <hr>
                        <p>Bus Operator <span id="summaryBusOperator"><?= htmlspecialchars($trip['operator_name']) ?></span></p>
                        <p>Ticket Price <span id="summaryTicketPrice"><?= htmlspecialchars($trip['price']) ?> MMK</span></p>
                        <p>Seat Number <span id="summaryNumberOfSeats">0</span></p>
                        <p class="total-price">Total Ticket Price <span style="color:green" id="summaryTotalPrice">MMK 0</span></p>
                        <div class="notice-box">Notices - Please bring your NRC</div>
                        <form action="<?= URLROOT ?>/seat/store" method="post" id="seatForm">
                            <input type="hidden" name="route_id" value="<?= $trip['route_id'] ?>">
                            <input type="hidden" name="passengers" value="<?= htmlspecialchars($trip['passengers'] ?? 1) ?>">
                            <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="">
                            <button type="submit" class="continue-payment-btn" id="continuePaymentBtn">
                                Continue to payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const maxSelectableSeats = <?= (int)($trip['passengers'] ?? 1) ?>;
        const seatBoxes = document.querySelectorAll('.seat-box');
        const summaryNumberOfSeats = document.getElementById('summaryNumberOfSeats');
        const summaryTotalPrice = document.getElementById('summaryTotalPrice');
        const pricePerSeat = <?= (int)$trip['price'] ?>;
        const selectedSeatsInput = document.getElementById('selectedSeatsInput');

        let selectedSeats = [];

        seatBoxes.forEach(box => {
            box.addEventListener('click', () => {
                if (box.classList.contains('booked')) return;

                const seatNumber = box.textContent.trim();

                if (box.classList.contains('selected')) {
                    box.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(num => num !== seatNumber);
                } else {
                    if (selectedSeats.length < maxSelectableSeats) {
                        box.classList.add('selected');
                        selectedSeats.push(seatNumber);
                    }
                }

                summaryNumberOfSeats.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '0';
                summaryTotalPrice.textContent = `MMK ${selectedSeats.length * pricePerSeat}`;
                selectedSeatsInput.value = selectedSeats.join(',');
            });
        });

        const flashMessage = document.getElementById('flashMessage');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.animation = "fadeOut 0.5s forwards";
                setTimeout(() => flashMessage.remove(), 500);
            }, 1500);
        }
    });
</script>
