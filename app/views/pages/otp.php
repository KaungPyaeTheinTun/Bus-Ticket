<?php session_start();
$user = $_SESSION['post_mail'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyTicket - Invalid OTP</title>
    <link rel="stylesheet" href="<?php echo URLROOT ;?>/public/css/buscss/login/otp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/select2/select2.min.css">
</head>
<body>
    <div class="otp-container">
        <div class="left-panel">
            <div class="logo">
             <h1>MYTICKET</h1>
            </div>
            <div class="bus-image">
                <img src=" <?php echo URLROOT; ?>/images/bus.png" alt="IMG">
            </div>
        </div>
        <div class="right-panel">
            <div class="icon-circle">
                <i class="fas fa-envelope"></i>
            </div>
            <?php require_once APPROOT . '/views/components/auth_message.php';?>
            <h2>Enter OTP</h2>
            <p class="instruction-text">An OTP has been sent to</p>
            <p class="phone-number"><?php echo $user?></p>

            <!-- <p class="error-message" id="otpErrorMessage">
                Invalid OTP
            </p> -->

            <form class="otp-form" method = 'POST' action='<?php echo URLROOT ?>/auth/otp'>
                <div class="otp-inputs">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                </div>
                <button type="submit" class="submit-button">Submit</button></a>
            </form><br>
            <form method="POST" action="<?php echo URLROOT; ?>/auth/forgetpassword">
                <div class="resend-section">
                    <span id="resendTimer">Didn't get code?</span>
                    <a href="#" id="requestAgainLink" class="disabled">Request again</a>
                </div>
            </form>
        </div>
    </div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpErrorMessage = document.getElementById('otpErrorMessage');
        const requestAgainLink = document.getElementById('requestAgainLink');
        const resendTimerSpan = document.getElementById('resendTimer');
        let countdown = 5;
        let timer = null;

        function startResendCountdown() {
            countdown = 5;
            requestAgainLink.classList.add('disabled');
            requestAgainLink.style.pointerEvents = 'none';
            requestAgainLink.style.color = '#ccc';

            updateTimerText();

            // Clear any existing timer to avoid multiple intervals running
            if (timer) clearInterval(timer);

            timer = setInterval(() => {
                countdown--;
                updateTimerText();

                if (countdown <= 0) {
                    clearInterval(timer);
                    timer = null;
                    requestAgainLink.classList.remove('disabled');
                    requestAgainLink.style.pointerEvents = 'auto';
                    requestAgainLink.style.color = 'var(--primary-blue)';
                    resendTimerSpan.textContent = `Didn't get code?`;
                }
            }, 1000);
        }

        function updateTimerText() {
            resendTimerSpan.textContent = `Didn't get code? ${countdown}s`;
        }

        // Event listener for "Request again"
        requestAgainLink.addEventListener('click', function(event) {
            event.preventDefault();
            if (!this.classList.contains('disabled')) {
                startResendCountdown();
            }
        });

        // Start countdown on page load
        startResendCountdown();

        // OTP auto-focus and backspace logic
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                let value = e.target.value;
                if (value.length > 1) value = value.slice(-1);
                e.target.value = value;

                if (value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // Allow pasting into first input
        otpInputs[0].addEventListener('paste', function(e) {
            const pasteData = e.clipboardData.getData('text').trim();
            if (/^\d+$/.test(pasteData)) {
                const chars = pasteData.split('');
                otpInputs.forEach((inputBox, i) => {
                    inputBox.value = chars[i] || '';
                });
                otpInputs[Math.min(chars.length, otpInputs.length) - 1].focus();
                e.preventDefault();
            }
        });
    });

</script>

</body>
</html>