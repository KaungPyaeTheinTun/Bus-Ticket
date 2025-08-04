<?php require_once APPROOT . '/views/inc/nav.php'; ?>
<?php
    function formatPhone($phone) 
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (strpos($digits, '95') === 0) {
            $formatted = '+' . $digits;
        } else {
            $formatted = '+95' . ltrim($digits, '0'); 
        if (preg_match('/^(\+\d{3})(\d{3})(\d{3})(\d{3})$/', $formatted, $matches)) {
            return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3] . ' ' . $matches[4];
        }
        return $formatted; 
        }
    }
?>

<main class="payment-method-main main-content-padding" style="margin-top:-4%;">
    <section class="payment-card-section">
        <div class="payment-card">
            <div class="payment-content">

                <form action="<?= URLROOT ?>/seat/finalStore" method="post" enctype="multipart/form-data">
                    <!-- Booking ID hidden input -->
                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($_SESSION['booking_data']['id'] ?? '') ?>">

                    <!-- Payment method select -->
                    <div class="select-payment-method-dropdown">
                        <select id="paymentMethodSelect" name="payment_method" class="styled-select" required>
                            <option value="">Select Payment Method</option>
                            <?php foreach ($data['payments'] as $payment): ?>
                                <option value="<?= $payment['id'] ?>">
                                    <?= htmlspecialchars($payment['method']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- QR code & phone display (will update dynamically with JS) -->
                    <div class="qr-code-section">
                        <img id="paymentQrCode" src="<?= URLROOT ?>/public/images/default_qr.jpg" alt="QR Code" class="qr-code">
                        <p class="phone-number"><i class="fas fa-phone-alt"></i> <span id="paymentPhone">Please select a payment method</span></p>
                    </div>
                    <br>
                    <!-- Upload slip -->
                    <div class="file-upload">
                        <label for="payment-slip" class="custom-file-upload">
                            <i class="fas fa-upload"></i> Upload Slip
                        </label>
                        <input type="file" id="payment-slip" name="payment_slip" accept="image/*,application/pdf" required>
                        <span class="file-name">No file chosen</span>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="confirm-btn">Confirm</button>
                </form>

            </div>
        </div>
    </section>
</main>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>

<script>
// Data for payment methods (id => {phone, qr_image})
const paymentData = {
    <?php foreach ($data['payments'] as $payment): ?>
        "<?= $payment['id'] ?>": {
            phone: "<?= htmlspecialchars(formatPhone($payment['phone'])) ?>",
            qr_image: "<?= URLROOT . '/public/uploads/scan_image/' . htmlspecialchars($payment['scan_image']) ?>"
        },
    <?php endforeach; ?>
};

// Elements
const paymentSelect = document.getElementById('paymentMethodSelect');
const qrCodeImg = document.getElementById('paymentQrCode');
const phoneSpan = document.getElementById('paymentPhone');
const fileInput = document.getElementById('payment-slip');
const fileNameSpan = document.querySelector('.file-name');

// Update QR code and phone when payment method changes
paymentSelect.addEventListener('change', () => {
    const selectedId = paymentSelect.value;
    if (paymentData[selectedId]) {
        qrCodeImg.src = paymentData[selectedId].qr_image;
        phoneSpan.textContent = paymentData[selectedId].phone;
    } else {
        qrCodeImg.src = "<?= URLROOT ?>/public/images/default_qr.jpg";
        phoneSpan.textContent = "Please select a payment method";
    }
});

// Show selected file name
fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        fileNameSpan.textContent = fileInput.files[0].name;
    } else {
        fileNameSpan.textContent = 'No file chosen';
    }
});
</script>
