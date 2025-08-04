<?php require_once APPROOT . '/views/inc/nav.php' ?>
<style>
    .print-btn {
        margin-top:10px;
        background-color:#4caf50;
        color:white;
        padding:8px 16px;
        border:none;
        border-radius:4px;
        cursor:pointer;
    }
    .print-btn:hover {
        background-color:#45a049;
    }

        .receipt-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 60px;
    left: 0; top: 0;
    width: 100%; height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
    }

    .receipt-modal-content {
    background-color: #fff;
    margin: auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .close-modal {
    color: #aaa;
    float: right;
    font-size: 24px;
    cursor: pointer;
    }

    .close-modal:hover {
    color: #000;
    }

    .status-label {
        padding: 6px 12px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
    }
    .status-label.pending {
        background-color: #f39c12; /* orange */
    }
    .status-label.approved {
        background-color: #27ae60; /* green */
    }
    .status-label.declined {
        background-color: #c0392b; /* red */
    }

</style>
    <main class="record-main" style="margin-top:-6%;">
        <section class="ticket-card-section">
            <h2 class="section-title">Bought Tickets</h2>
            <hr><br>
            <?php if (empty($data['record'])): ?>
                <p style="text-align:center; color:red;">There is no record of your trip !</p>
            <?php endif; ?>
            <?php foreach ($data['record'] as $record): ?>
                <?php  
                    $departureFormatted = $record['departure_time'] ?  date('F j g:i A', strtotime($record['departure_time'])) : '';
                ?>
                <div class="ticket-card">
                    <div class="ticket-details-grid">
                        <div class="detail-item">
                            <span class="label">Routes</span>
                            <span class="value"><?php echo $record['from_location'] ?> - <?php echo $record['to_location'] ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Departure Date</span>
                            <span class="value"><?php echo $departureFormatted ?></span>
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
                                    $formattedSeats = implode(', ', $seatNumbers);
                                    echo '<span class="value">' . htmlspecialchars($formattedSeats) . '</span>';
                                } else {
                                    echo '<span class="value">' . htmlspecialchars($record['seat_number']) . '</span>';
                                }
                            ?>
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
<button class="status-btn view-receipt-btn">View Receipt</button>                            
                        <?php elseif ($record['is_booked'] == '0'): ?>
                            <span class="status-label declined">Declined</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <div id="receiptModal" class="receipt-modal" style="display:none;">
        <div class="receipt-modal-content">
            <span class="close-modal">&times;</span>
            <h3>Receipt / Voucher</h3>
            <button onclick="printVoucher()" class="print-btn">Print Voucher</button>
            <div class="receipt-content">
                <p><strong>Routes:</strong> <span id="modalRoute"></span></p>
                <p><strong>Departure Date:</strong> <span id="modalDeparture"></span></p>
                <p><strong>Bus Operator:</strong> <span id="modalOperator"></span></p>
                <p><strong>Seat Number(s):</strong> <span id="modalSeats"></span></p>
                <p><strong>Total:</strong> <span id="modalTotal"></span></p>
            </div>
        </div>
    </div>
<br><br><br>
<?php require_once APPROOT . '/views/inc/footer.php' ?>
<script>
function printVoucher() {
    const modalContent = document.querySelector('.receipt-modal-content').innerHTML;

    // Open new window
    const printWindow = window.open('', '', 'height=600,width=400');
    printWindow.document.write('<html><head><title>Voucher</title>');
    printWindow.document.write('<style> body{font-family:Arial; padding:20px;} </style>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(modalContent);
    printWindow.document.write('</body></html>');

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}


document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('receiptModal');
    const closeModal = document.querySelector('.close-modal');

    document.querySelectorAll('.view-receipt-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            const card = btn.closest('.ticket-card');
            // Fetch data from the card
            document.getElementById('modalRoute').textContent = card.querySelector('.detail-item:nth-child(1) .value').textContent;
            document.getElementById('modalDeparture').textContent = card.querySelector('.detail-item:nth-child(2) .value').textContent;
            document.getElementById('modalOperator').textContent = card.querySelector('.detail-item:nth-child(3) .value').textContent;
            document.getElementById('modalSeats').textContent = card.querySelector('.detail-item:nth-child(4) .value').textContent;
            document.getElementById('modalTotal').textContent = card.querySelector('.detail-item:nth-child(5) .value').textContent;

            modal.style.display = 'block';
        });
    });

    closeModal.onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if (e.target == modal) modal.style.display = 'none'; }
});
</script>
