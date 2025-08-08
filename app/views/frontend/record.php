<?php require_once APPROOT . '/views/inc/nav.php' ?>
<?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="flash-message success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>
<style>
    .flash-message {
            position: fixed;
            top: 80px;
            left: 30%;
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
    .print-btn {
        margin-top: 16px;
        color: black;
        background-color: silver;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
    }


    .receipt-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
    }

    .receipt-modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        width: 95%;
        max-width: 500px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        position: relative;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .close-modal {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 24px;
        color: #777;
        cursor: pointer;
        font-weight: bold;
    }

    .close-modal:hover {
        color: #000;
    }

    .receipt-content p {
        margin: 10px 0;
        font-size: 15px;
        color: #333;
    }

    .receipt-content span {
        font-weight: bold;
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
<br><br><br>
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
<div id="receiptModal" class="receipt-modal">
    <div class="receipt-modal-content">
        <span class="close-modal">&times;</span>
        <h2 style="text-align:center; margin-bottom:20px;">Receipt / Voucher</h2>
        <div class="receipt-content">
            <p>Name : <span style="margin-left:80px;"><?php echo htmlspecialchars($data['user']['name']); ?></span></p>
            <p>Phone : <span style="margin-left:78px;"><?php echo htmlspecialchars($data['user']['phone']); ?></span></p>
            <p>Routes : <span id="modalRoute"  style="margin-left:74px;"></span></p>
            <p>Departure Date : <span id="modalDeparture"  style="margin-left:10px;"></span></p>
            <p>Bus Operator : <span id="modalOperator"  style="margin-left:26px;"></span></p>
            <p>Seat Number(s) : <span id="modalSeats"  style="margin-left:6px;"></span></p>
            <p>Total : <span id="modalTotal"  style="margin-left:87px;"></span></p>
        </div>
        <hr>
        <h4>Terms & Conditions</h4>
        <li>All the tickets bought are not refundable and exchangeable.</li>
        <li>Travellers must reach the bus station 45 minutes before the departure time.</li>
        <button onclick="printVoucher()" class="print-btn">🖨 Print Voucher</button>
    </div>
</div>

<br><br><br>
<?php require_once APPROOT . '/views/inc/footer.php' ?>
<script>
    function printVoucher() {
        const content = document.querySelector('.receipt-content').innerHTML;

        const printWindow = window.open('', '', 'height=700,width=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Voucher</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            padding: 40px;
                            line-height: 1.6;
                            color: #333;
                        }
                        h2 {
                            text-align: center;
                            margin-bottom: 30px;
                        }
                        p {
                            margin: 10px 0;
                            font-size: 16px;
                        }
                        span {
                            font-weight: bold;
                            color: #000;
                        }
                    </style>
                </head>
                <body>
                    <h2>Receipt / Voucher</h2>
                    ${content}
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
                document.getElementById('modalTotal').textContent = card.querySelector('.detail-item:nth-child(5) .value').textContent;

                modal.style.display = 'flex';
            });
        });

        closeModal.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
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

