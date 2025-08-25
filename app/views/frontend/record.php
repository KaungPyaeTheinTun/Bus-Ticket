<?php require_once APPROOT . '/views/inc/nav.php' ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="flash-message success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<style>
    /* Flash message */
    .flash-message {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        animation: fadeInScale 0.3s ease;
    }
    .success-message { background-color: #d4edda; color: #155724; }
    .error-message   { background-color: #f8d7da; color: #721c24; }
    @keyframes fadeOut {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.9); }
    }

    /* Ticket card */
    .ticket-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: transform 0.2s ease;
    }
    .ticket-card:hover { transform: translateY(-3px); }

    .ticket-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 20px;
    }
    .detail-item .label {
        font-size: 13px;
        font-weight: 600;
        color: #555;
    }
    .detail-item .value {
        font-size: 14px;
        color: #222;
    }

    /* Status labels */
    .status-label {
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
        font-weight: bold;
        font-size: 13px;
    }
    .status-label.pending  { background-color: #f39c12; }
    .status-label.approved { background-color: #27ae60; }
    .status-label.declined { background-color: #c0392b; }

    .status-btn {
        margin-top: 8px;
        background:rgb(27, 51, 184);
        color: #fff;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.2s;
        height:32px;
    }
    .status-btn:hover { background:rgb(63, 82, 191); }

    /* Modal */
    .receipt-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
    }
    .receipt-modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        width: 95%;
        height:90%;
        font-size:14px;
        max-width: 520px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        position: relative;
        animation: slideDown 0.3s ease-out;
    }
    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to   { transform: translateY(0); opacity: 1; }
    }
    .close-modal {
        position: absolute;
        top: 14px;
        right: 18px;
        font-size: 22px;
        color: #555;
        cursor: pointer;
        font-weight: bold;
    }
    .close-modal:hover { color: #000; }

    .receipt-content p {
        margin: 10px 0;
        font-size: 14px;
        color: #444;
    }
    .receipt-content span { font-weight: 600; color: #111; }

    .print-btn {
        margin-top: 18px;
        background: #f1f1f1;
        color: #222;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.2s;
        margin-left:60px;
    }
    .print-btn:hover { background: #e0e0e0; }
    .grid { 
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 20px;
        margin-bottom: 20px; 
    }

</style>

<br><br><br>
<main class="record-main" style="margin-top:-6%;">
    <section class="ticket-card-section">
        <h2 class="section-title">Bought Tickets</h2>
        <hr><br>
        <?php if (empty($data['record'])): ?>
            <p style="text-align:center; color:red;">There is no record yet !</p>
        <?php endif; ?>

        <?php foreach ($data['record'] as $record): ?>
            <div class="ticket-card">
                <div class="ticket-details-grid">
                    <div class="detail-item">
                        <span class="label">Routes</span>
                        <span class="value"><?php echo $record['from_location'] ?> - <?php echo $record['to_location'] ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Departure Date</span>
                        <span class="value"><?php echo formatDate($record['departure_time']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bus Operator</span>
                        <span class="value"><?php echo $record['operator_name'] ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Seat Number</span>
                        <?php
                            $seatNumbers = json_decode($record['seat_number']);
                            if ($seatNumbers) {
                                echo '<span class="value">' . htmlspecialchars(implode(', ', $seatNumbers)) . '</span>';
                            } else {
                                echo '<span class="value">' . htmlspecialchars($record['seat_number']) . '</span>';
                            }
                        ?>
                    </div>
                    <div class="detail-item">
                        <span class="label">Operator Phone</span>
                        <span class="value"><?php echo $record['operator_phone'] ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Total</span>
                        <span class="value" style="color:green;"><?php echo $record['total_price'] ?> MMK</span>
                    </div>
                </div>
                <div class="ticket-actions">
                    <?php if ($record['is_booked'] == '1'): ?>
                        <span class="status-label pending">Pending</span>
                    <?php elseif ($record['is_booked'] == '2'): ?>
                        <span class="status-label approved">Approved</span>
                        <button class="status-btn view-receipt-btn"><i class="fas fa-receipt"></i>  Receipt</button>                          
                    <?php elseif ($record['is_booked'] == '0'): ?>
                        <span class="status-label declined">Declined</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<!-- Modal -->
<div id="receiptModal" class="receipt-modal">
    <div class="receipt-modal-content">
        <span class="close-modal">&times;</span>
        <!-- <h2 style="text-align:center; margin-bottom:20px;">Receipt / Voucher</h2> -->
        <div class="receipt-content">
            <!-- <p>Name : <span><?php echo htmlspecialchars($data['user']['name']); ?></span></p>
            <p>Phone : <span><?php echo htmlspecialchars($data['user']['phone']); ?></span></p>
            <p>Routes : <span id="modalRoute"></span></p>
            <p>Departure Date : <span id="modalDeparture"></span></p>
            <p>Bus Operator : <span id="modalOperator"></span></p>
            <p>Operator Phone : <span id="modalPhone"></span></p>
            <p>Seat Number(s) : <span id="modalSeats"></span></p>
            <p>Total : <span id="modalTotal"></span></p>
            <hr>
            <h4>Terms & Conditions</h4>
            <ul>
                <li>All the tickets bought are not refundable and exchangeable.</li>
                <li>Travellers must reach the bus station 45 minutes before the departure time.</li>
            </ul> -->
       
            <!-- .................... -->
                <h2>Booking Receipt<button onclick="printVoucher()" class="print-btn">🖨 Print Voucher</button></h2>
                <br>
                <div class="grid">
                    <div><span>Routes:</span><br><span id="modalRoute"></span></div>
                    <div><span>Departure Date:</span><br><span id="modalDeparture"></span></div>
                    <div><span>Bus Operator:</span><br><span id="modalOperator"></span></div>
                    <div><span>Operator Phone:</span><br><span id="modalPhone"></span></div>
                    <div><span>Total:</span><br><span id="modalTotal" style="color:green;"></span></div>
                </div>
                <hr>
                <h2>Traveller Information</h2>
                <div class="grid">
                    <div><span>Name:</span> <span id="modalName"><?php echo htmlspecialchars($data['user']['name']); ?></span></div>
                    <div><span>Phone:</span> <span id="modalUserPhone"><?php echo htmlspecialchars($data['user']['phone']); ?></span></div>
                    <div><span>Seat Number:</span> <span id="modalSeats"></span></div>
                </div>
                <hr>
                <h2>Terms & Conditions</h2>
                <ul>
                    <li>All the tickets bought are not refundable and exchangeable.</li>
                    <li>Travellers must reach the bus station 45 minutes before the departure time.</li>
                </ul>
        </div>
    </div>
</div>

<br><br><br>
<?php require_once APPROOT . '/views/inc/footer.php' ?>

<script>
    function printVoucher() {
        const name = document.getElementById('modalName').textContent;
        const phone = document.getElementById('modalUserPhone').textContent;
        const route = document.getElementById('modalRoute').textContent;
        const departure = document.getElementById('modalDeparture').textContent;
        const operator = document.getElementById('modalOperator').textContent;
        const operatorphone = document.getElementById('modalPhone').textContent;
        const seats = document.getElementById('modalSeats').textContent;
        const total = document.getElementById('modalTotal').textContent;

        const printWindow = window.open('', '', 'height=800,width=800');
        printWindow.document.write(`
            <html>
            <head>
                <title>Booking Receipt</title>
                <style>
                    body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
                    .receipt-container {
                        background: #fff;
                        padding: 30px;
                        border-radius: 12px;
                        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
                        max-width: 700px;
                        margin: auto;
                    }
                    h2 { text-align: center; margin-bottom: 20px; font-size: 22px; font-weight: bold; }
                    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 20px; margin-bottom: 20px; }
                    .label { font-weight: bold; color: #333; }
                    .value { color: #000; }
                    .total { font-weight: bold; color: green; }
                    hr { margin: 15px 0; }
                    .section-title { font-weight: bold; margin: 15px 0 10px; font-size: 16px; }
                    li { font-size: 14px; margin-bottom: 6px; }
                    @media print { .print-btn { display: none; } }
                </style>
            </head>
            <body>
                <div class="receipt-container">
                    <h2>Booking Receipt</h2>
                    <div class="grid">
                        <div><span class="label">Routes:</span><br><span class="value">${route}</span></div>
                        <div><span class="label">Departure Date:</span><br><span class="value">${departure}</span></div>
                        <div><span class="label">Bus Operator:</span><br><span class="value">${operator}</span></div>
                        <div><span class="label">Operator Phone:</span><br><span class="value">${operatorphone}</span></div>
                        <div><span class="label">Total:</span><br><span class="total">${total}</span></div>
                    </div>
                    <hr>
                    <div class="section-title">Traveller Information</div>
                    <div class="grid">
                        <div><span class="label">Name:</span> <span class="value">${name}</span></div>
                        <div><span class="label">Phone:</span> <span class="value">${phone}</span></div>
                        <div><span class="label">Seat Number:</span> <span class="value">${seats}</span></div>
                    </div>
                    <hr>
                    <div class="section-title">Terms & Conditions</div>
                    <ul>
                        <li>All the tickets bought are not refundable and exchangeable.</li>
                        <li>Travellers must reach the bus station 45 minutes before the departure time.</li>
                    </ul>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('receiptModal');
        const closeModal = document.querySelector('.close-modal');

        document.querySelectorAll('.view-receipt-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const card = btn.closest('.ticket-card');
                document.getElementById('modalRoute').textContent = card.querySelector('.detail-item:nth-child(1) .value').textContent;
                document.getElementById('modalDeparture').textContent = card.querySelector('.detail-item:nth-child(2) .value').textContent;
                document.getElementById('modalOperator').textContent = card.querySelector('.detail-item:nth-child(3) .value').textContent;
                document.getElementById('modalSeats').textContent = card.querySelector('.detail-item:nth-child(4) .value').textContent;
                document.getElementById('modalPhone').textContent = card.querySelector('.detail-item:nth-child(5) .value').textContent;  // ✅ FIXED
                document.getElementById('modalTotal').textContent = card.querySelector('.detail-item:nth-child(6) .value').textContent; // ✅ FIXED
                modal.style.display = 'flex';
            });
        });

        closeModal.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });
    });

    // Auto-hide flash message
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => flashMessage.remove(), 500);
        }, 1500);
    }
</script>
