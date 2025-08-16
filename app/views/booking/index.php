<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

<?php require_once APPROOT . '/helpers/SessionHelper.php'; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="flash-message success-message">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div id="flashMessage" class="flash-message error-message">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['pending'])): ?>
    <div id="flashMessage" class="flash-message pending-message">
        <?php echo $_SESSION['pending']; unset($_SESSION['pending']); ?>
    </div>
<?php endif; ?>

<style>
    .flash-message {
            position: fixed;
            top: 28px;
            left: 50%;
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
        .pending-message {
            background-color:#f1c40f;
            color:rgb(255, 255, 255);
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
    .status-buttons button {
        font-size: 20px;
    }

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
    .modal-content h3 { margin-top: 0; margin-bottom: 20px; color: #333; font-size: 1.5em; }
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
    .btn-yes { background-color: #e74c3c; color: white; }
    .btn-yes:hover { background-color: #c0392b; }
    .btn-no { background-color: #bdc3c7; color: #333; }
    .btn-no:hover { background-color: #95a5a6; }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .booking-table button.view-booking-button {
        background-color: #0d47a1;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9em;
        transition: background-color 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        text-decoration: none;
    }
    .booking-table button.view-booking-button:hover {
        background-color: #0a3a80;
    }
    .booking-table th, .booking-table td {
        padding: 12px 8px;
    }
    .payment_close{
        position: absolute;
        top: 1px;
        right: -10px;
        background: transparent;
        border: none;
        font-weight: bold;
        cursor: pointer;
        color: #555;
        line-height: 1;
    }

    /* New Status Control Styles */
    .status-control-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .status-dropdown {
        padding: 0px 12px;
        border: 1px solid #ddd;
        border-radius: 19px;
        font-size: 14px;
        max-width: 128px;
        height:30px;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: white;
    }



    .status-dropdown:hover {
        border-color: #007bff;
    }

    .status-dropdown option {
        padding: 8px;
    }

    /* Style the select based on selected value */
    .status-dropdown.status-pending {
        border-color:rgb(255, 191, 0);
        background-color: #fff9e6;
    }

    .status-dropdown.status-approved {
        border-color:rgb(22, 201, 64);
        background-color: #e8f5e8;
    }

    .status-dropdown.status-declined {
        border-color:rgb(226, 5, 28);
        background-color: #fdeaea;
    }

    /* Current status badge */
    .current-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .current-status.status-pending {
        background-color:rgb(255, 196, 0);
        color: #856404;
    }

    .current-status.status-approved {
        background-color:rgb(0, 203, 129);
        color: #0c5460;
    }

    .current-status.status-declined {
        background-color:rgb(254, 0, 21);
        color: #721c24;
    }
</style>

<div class="container">
    <main class="main-content">
        <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
        <section class="booking-list-card">
            <div class="card-header">Booking List</div>
            <div class="booking-table-container">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Bus Operators</th>
                            <th>Amount</th>
                            <th>Payment_slip</th>
                            <th>Seat_No</th>
                            <th style="text-align:center;">State</th>
                            <th style="text-align: center;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($data['booking']) && is_array($data['booking'])): ?>
                        <?php foreach ($data['booking'] as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['operator_name']); ?></td>
                                <td style="color:green;">MMK <?php echo htmlspecialchars(number_format($booking['total_price'])); ?></td>
                                <td style="text-align:center;">
                                    <?php if (!empty($booking['payment_slip'])): ?>
                                        <button type="button" class="view-payment-slip-btn" 
                                            data-img-src="<?php echo URLROOT; ?>/public/uploads/payment_slip/<?php echo htmlspecialchars($booking['payment_slip']); ?>"
                                            style="border:none; background:none; padding:0; cursor:pointer;">
                                            <img src="<?php echo URLROOT; ?>/public/uploads/payment_slip/<?php echo htmlspecialchars($booking['payment_slip']); ?>" width="50" alt="Payment Slip Thumbnail">
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center;">
                                    <?php
                                        $seatNumbers = json_decode($booking['seat_number']);
                                        if ($seatNumbers) {
                                            $formattedSeats = implode(', ', $seatNumbers);
                                            echo '<span class="value">' . htmlspecialchars($formattedSeats) . '</span>';
                                        } else {
                                            echo '<span class="value">' . htmlspecialchars($booking['seat_number']) . '</span>';
                                        }
                                    ?>
                                </td>                          
                                
                                <td style="text-align: center;">
                                    <div class="status-control-container">
                                        <!-- Current Status Badge -->
                                        <!-- <div class="current-status status-<?php echo ($booking['is_booked']=='1' ? 'pending' : ($booking['is_booked']=='2' ? 'approved' : 'declined')); ?>">
                                            <?php 
                                                if ($booking['is_booked'] == '1') echo '⏳ Pending';
                                                elseif ($booking['is_booked'] == '2') echo '✅ Approved'; 
                                                else echo '❌ Canceled';
                                            ?>
                                        </div> -->
                                        <!-- Status Control Form -->
                                        <form class="status-form" action="<?php echo URLROOT; ?>/booking/updateStatus/<?php echo $booking['booking_id']; ?>" method="POST">
				                            <?php echo SessionHelper::csrfInput(); ?>
                                            <select class="status-dropdown status-<?php echo ($booking['is_booked']=='1' ? 'pending' : ($booking['is_booked']=='2' ? 'approved' : 'declined')); ?>" 
                                                    name="status" 
                                                    data-booking-id="<?php echo $booking['booking_id']; ?>" 
                                                    data-current-status="<?php echo $booking['is_booked']; ?>"
                                                    onchange="this.form.submit()">
                                                <option value="<?php echo $booking['is_booked']; ?>" selected>
                                                    <?php 
                                                        if ($booking['is_booked'] == '1') echo '⏳Pending';
                                                        elseif ($booking['is_booked'] == '2') echo '✅Confirmed'; 
                                                        else echo '❌Canceled';
                                                    ?>
                                                </option>
                                                <?php if ($booking['is_booked'] != '1'): ?>
                                                    <option value="1">⏳Pending</option>
                                                <?php endif; ?>
                                                <?php if ($booking['is_booked'] != '2'): ?>
                                                    <option value="2">✅Confirmed</option>
                                                <?php endif; ?>
                                                <?php if ($booking['is_booked'] != '0'): ?>
                                                    <option value="0">❌Declined</option>
                                                <?php endif; ?>
                                            </select>
                                        </form>
                                    </div>
                                </td>
                                
                                <td style="text-align: center;">
                                   <a href="<?php echo URLROOT; ?>/booking/deleteseat/<?php echo base64_encode($booking['seat_number']); ?>" 
                                        class="delete-admin-btn" 
                                        data-name="Seat <?php echo htmlspecialchars($booking['seat_number']); ?>">
                                            <i class="fas fa-trash-alt action-icon delete-icon"></i>
                                    </a>
                                </td>
                            </tr>                                
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">No Booking !</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<!-- Delete confirmation modal -->
<div id="deleteConfirmationModal" class="modal-overlay">
    <div class="modal-content">                         
        <form id="deleteForm" method="POST">
            <h3>Are you sure you want to delete <span id="adminNameToDelete"></span>?</h3>
            <p>This action cannot be undone.</p>
            <div class="modal-buttons">
                <button type="submit" class="btn-yes">Yes, Delete</button>
                <button type="button" class="btn-no" id="confirmDeleteNo">No, Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Payment slip modal -->
<div id="paymentSlipModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 390px;">
        <img id="paymentSlipImage" src="" alt="Payment Slip" style="max-width: 100%; border-radius: 3px;">
        <div class="modal-buttons">
            <button id="closePaymentSlipModal" aria-label="Close modal" class="payment_close" style="font-size:20px;">
                &times;
            </button>
        </div>
    </div>
</div>

<script>
    // Original delete functionality
    const deleteButtons = document.querySelectorAll('.delete-admin-btn');
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const adminNameToDeleteSpan = document.getElementById('adminNameToDelete');
    const confirmDeleteNoBtn = document.getElementById('confirmDeleteNo');
    const deleteForm = document.getElementById('deleteForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.getAttribute('href');
            const adminName = this.dataset.name;
            deleteForm.action = deleteUrl;
            adminNameToDeleteSpan.textContent = adminName;
            deleteModal.style.display = 'flex';
        });
    });

    confirmDeleteNoBtn.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });

    deleteModal.addEventListener('click', function(event) {
        if (event.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    });

    // Original payment slip modal logic
    const paymentSlipModal = document.getElementById('paymentSlipModal');
    const paymentSlipImage = document.getElementById('paymentSlipImage');
    const closePaymentSlipModalBtn = document.getElementById('closePaymentSlipModal');

    document.querySelectorAll('.view-payment-slip-btn').forEach(button => {
        button.addEventListener('click', () => {
            const imgSrc = button.getAttribute('data-img-src');
            paymentSlipImage.src = imgSrc;
            paymentSlipModal.style.display = 'flex';
        });
    });

    closePaymentSlipModalBtn.addEventListener('click', () => {
        paymentSlipModal.style.display = 'none';
        paymentSlipImage.src = '';
    });

    paymentSlipModal.addEventListener('click', (e) => {
        if (e.target === paymentSlipModal) {
            paymentSlipModal.style.display = 'none';
            paymentSlipImage.src = '';
        }
    });

    // New Status Control Logic
    document.addEventListener('DOMContentLoaded', function() {
        const statusDropdowns = document.querySelectorAll('.status-dropdown');

        // Handle dropdown changes with visual feedback
        statusDropdowns.forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                const selectedValue = this.value;
                
                // Update dropdown styling based on selection
                this.classList.remove('status-pending', 'status-approved', 'status-declined');
                
                if (selectedValue === '1') {
                    this.classList.add('status-pending');
                } else if (selectedValue === '2') {
                    this.classList.add('status-approved');
                } else if (selectedValue === '0') {
                    this.classList.add('status-declined');
                }
            });
        });
    });
        // Flash message auto hide
    const flash = document.getElementById('flashMessage');
    if(flash){
        setTimeout(()=>{flash.style.animation="fadeOut 0.5s forwards"; setTimeout(()=>flash.remove(),500);},2000);
    }
</script>
