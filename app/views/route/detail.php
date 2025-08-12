<?php require_once APPROOT . '/views/inc/sidebar.php'; ?>

<?php
    $routeId = $data['route']['id'] ?? 0;
    $db = new Database();
    $allSeats = $db->readAll('seats');
    $operatorId = $data['route']['operator_id'] ?? 0;
    $operator = $db->getById('operator', $operatorId);
    $seatCapacity = (int)$operator['seat_capacity'];
    $busType = $operator['bus_type_id'] ?? null;
    $busTypeRow = $db->getById('bus_type', $busType);
    $isVip = strtolower($busTypeRow['type_name']) === 'vip';
    $bookedSeatNumbers = [];
    foreach ($allSeats as $seat) {
        if (((int)$seat['is_booked'] === 1 || (int)$seat['is_booked'] === 2) && (int)$seat['route_id'] === (int)$routeId) {
            $seatNumbersArray = json_decode($seat['seat_number'], true);
            if (is_array($seatNumbersArray)) {
                foreach ($seatNumbersArray as $number) {
                    $bookedSeatNumbers[] = (int)$number;
                }
            }
        }
    }
?>

<?php if (!empty($_SESSION['error'])): ?>
    <div id="flashMessage" class="flash-message error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="flash-message success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<style>
    /* Layout header: side by side */
    .seat-layout-header {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 15px;
    }

    /* Common button styles */
    .seat-layout-header .tab,
    .reset-seats-button {
        padding: 10px 16px;
        font-size: 1em;
        border: none;
        border-radius: 4px;
        cursor: default; /* disable hover cursor on Sold/Available */
    }

    /* Sold & Available fixed styles, no hover */
    .sold-tab { background: #8a8585; color: white; cursor: default }
    .available-tab { background: #d8e6f1; color: #333;cursor: default }

    /* Reset button with hover */
    .reset-seats-button {
        cursor: pointer;
        background: #e74c3c;
        color: #fff;
        transition: background 0.2s;
    }
    .reset-seats-button:hover { background: #c0392b; }

    /* Flash messages */
    .flash-message {
        position: fixed;
        top: 28px;
        left: 50%;
        padding: 16px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        animation: fadeInScale 0.3s ease;
    }
    .success-message { background-color: #d4edda; color: #155724; }
    .error-message { background-color: rgb(239, 154, 161); color: #721c24; }

    @keyframes fadeOut {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.9); }
    }

        /* Default layout */
    .seat-grid-container {
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        gap: 10px;
        margin: 15px 0;
        padding: 10px;
        background: #f4f4f4;
        border-radius: 8px;
    }

    .seat-grid-container.vip-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        background: #f4f4f4;
        padding: 10px;
        border-radius: 8px;
        width: fit-content;
    }

    .vip-row {
        display: flex;
        gap: 25px; /* gap between 2-left and 1-right (aisle) */
        margin-bottom: 10px;
        width: 100%;
    }

    .vip-left {
        display: flex;
        gap: 30px;
    }

    .vip-right {
        display: flex;
        margin-left:60px;
    }

    /* All seat styles */
    .seat-box {
        padding: 12px 0;
        background: #ddd;
        text-align: center;
        font-weight: bold;
        border-radius: 5px;
    }

    .driver-box {
        grid-column: span 4;
        background-color: #ddd;          /* same as seat-box */
        text-align: center;
        font-weight: bold;
        padding: 12px 0;                 /* same padding as seat-box */
        border-radius: 5px;              /* same rounded corners */
        border: 1px solid #bbb;          /* same border */
        margin-bottom: 8px;
        font-size: 1.1em;               /* same font size */
        width:115px;
        height:50px;
    }
  
    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-align: center;
        max-width: 400px;
        width: 90%;
        animation: fadeInScale 0.3s ease-out;
    }
    .modal-buttons {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    .modal-buttons button {
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.2s ease;
    }
    .btn-yes {
        background-color: #e74c3c;
        color: white;
    }
    .btn-yes:hover { background-color: #c0392b; }
    .btn-no {
        background-color: #bdc3c7;
        color: #333;
    }
    .btn-no:hover { background-color: #95a5a6; }
    .seat-box.sold {
        background-color: #8a8585;
        color: white;
        cursor: not-allowed;
    }

    .seat-box.available {
        background-color: #d8e6f1;
        color: #333;
        cursor: default;
    }
    @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
</style>

<main class="main-content">
<?php require_once APPROOT . '/views/inc/profileHeader.php'; ?>

<div class="main-content-grid">
<section class="bus-info-card">
    <div class="card-header">Bus Information</div>
    <div class="info-grid">
        <div class="info-item"><span class="label">Operator :</span><span class="value"><?= htmlspecialchars($data['operator']['name']) ?></span></div>
        <div class="info-item"><span class="label">Phone :</span><span class="value"><?= htmlspecialchars($data['operator']['phone']) ?></span></div>
        <div class="info-item"><span class="label">From :</span><span class="value"><?= htmlspecialchars($data['route']['from']) ?></span></div>
        <div class="info-item"><span class="label">To :</span><span class="value"><?= htmlspecialchars($data['route']['to']) ?></span></div>
        <div class="info-item"><span class="label">Price :</span><span class="value" style="color:green;">MMK <?= htmlspecialchars($data['route']['price']) ?></span></div>
        <div class="info-item"><span class="label">Departure :</span><span class="value">
            <?php
                echo formatDate($data['route']['departure_time']);
            ?>
        </span></div>
        <div class="info-item"><span class="label">Arrival :</span><span class="value">
            <?php
                echo formatDate($data['route']['arrival_time']);            
            ?>
        </span></div>
    </div>
    <div class="card-actions">
        <a href="<?= URLROOT; ?>/route"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</section>

<section class="seat-layout-card">
    <div class="seat-layout-header">
        <button class="tab sold-tab">Sold</button>
        <button class="tab available-tab">Available</button>
        <button type="button" class="reset-seats-button" id="openModalBtn">Reset seats</button>
    </div>

      <div class="seat-grid-container <?= $isVip ? 'vip-layout' : '' ?>">
        <div class="driver-box">Driver</div>
        <?php if ($isVip): ?>
            <?php
            for ($i = 1; $i <= $seatCapacity; $i += 3):
                $leftSeat1 = $i;
                $leftSeat2 = $i + 1;
                $rightSeat = $i + 2;
            ?>
            <div class="vip-row">
                <div class="vip-left">
                    <?php foreach ([$leftSeat1, $leftSeat2] as $seat): ?>
                        <?php if ($seat <= $seatCapacity): ?>
                            <?php $seatClass = in_array($seat, $bookedSeatNumbers) ? 'seat-box sold' : 'seat-box available'; ?>
                            <div class="<?= $seatClass ?>"><?= $seat ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="vip-right">
                    <?php if ($rightSeat <= $seatCapacity): ?>
                        <?php $seatClass = in_array($rightSeat, $bookedSeatNumbers) ? 'seat-box sold' : 'seat-box available'; ?>
                        <div class="<?= $seatClass ?>"><?= $rightSeat ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endfor; ?>
        <?php else: ?>
        <?php for ($i = 1; $i <= $seatCapacity; $i++): ?>
            <?php $seatClass = in_array($i, $bookedSeatNumbers) ? 'seat-box sold' : 'seat-box available'; ?>
            <div class="<?= $seatClass ?>"><?= $i ?></div>
        <?php endfor; ?>
    <?php endif; ?>
    </div>
</section>
</div>
</main>

<!-- Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <p>Are you sure you want to reset all seats?</p>
        <form method="post" action="<?= URLROOT ?>/route/resetSeats">
            <input type="hidden" name="route_id" value="<?= (int)$routeId ?>">
            <div class="modal-buttons">
                <button type="submit" class="btn-yes">Yes, Reset</button>
                <button type="button" class="btn-no" id="closeModalBtn">No, Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500);
        }, 1500);
    }

    document.getElementById('openModalBtn').addEventListener('click', () => {
        document.getElementById('confirmModal').style.display='flex';
    });
    document.getElementById('closeModalBtn').addEventListener('click', () => {
        document.getElementById('confirmModal').style.display='none';
    });
</script>
