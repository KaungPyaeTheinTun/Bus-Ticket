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
                <div class="resend-section">
                    <span id="resendTimer">Didn't get code?</span>
                    <a href="#" id="requestAgainLink" class="disabled">Request again</a>
                </div>
                <button type="submit" class="submit-button">Submit</button></a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpErrorMessage = document.getElementById('otpErrorMessage');
            const requestAgainLink = document.getElementById('requestAgainLink');
            const resendTimerSpan = document.getElementById('resendTimer');
            let countdown = 60; // 60 seconds for resend timer

            // Function to start the resend countdown
            function startResendCountdown() {
                countdown = 60; // Reset countdown
                requestAgainLink.classList.add('disabled');
                requestAgainLink.style.pointerEvents = 'none'; // Disable clicking
                requestAgainLink.style.color = '#ccc'; // Gray out link

                resendTimerSpan.textContent = `Didn't get code? ${countdown}s`;

                const timer = setInterval(() => {
                    countdown--;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        resendTimerSpan.textContent = `Didn't get code?`;
                        requestAgainLink.classList.remove('disabled');
                        requestAgainLink.style.pointerEvents = 'auto'; // Re-enable clicking
                        requestAgainLink.style.color = 'var(--primary-blue)'; // Restore color
                    } else {
                        resendTimerSpan.textContent = `Didn't get code? ${countdown}s`;
                    }
                }, 1000);
            }

            // OTP input auto-focus and backspace logic
                otpInputs.forEach((input, index) => {
                // Handle normal typing
                input.addEventListener('input', function(e) {
                    let value = e.target.value;

                    // Keep only the last digit if user pastes or types more than one char
                    if (value.length > 1) {
                        value = value.slice(-1);
                        e.target.value = value;
                    }

                    // Move to next input if filled
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }

                    // If user pastes the whole OTP into the first field
                    if (value.length > 1 && e.inputType !== 'deleteContentBackward') {
                        const chars = value.split('');
                        otpInputs.forEach((inputBox, i) => {
                            inputBox.value = chars[i] || '';
                        });
                        otpInputs[Math.min(chars.length, otpInputs.length) - 1].focus();
                    }
                });

                // Handle backspace: go back to previous field
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // Allow pasting directly to the first box
            otpInputs[0].addEventListener('paste', function(e) {
                const pasteData = e.clipboardData.getData('text').trim();
                if (/^\d+$/.test(pasteData)) { // only numbers
                    const chars = pasteData.split('');
                    otpInputs.forEach((inputBox, i) => {
                        inputBox.value = chars[i] || '';
                    });
                    otpInputs[Math.min(chars.length, otpInputs.length) - 1].focus();
                    e.preventDefault();
                }
            });

            // Initially show the error message as per the image
            otpErrorMessage.style.display = 'block';

            // Start countdown on page load (assuming OTP just sent)
            startResendCountdown();

            // Event listener for "Request again"
            requestAgainLink.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                if (!this.classList.contains('disabled')) {
                    alert('OTP requested again!'); // In a real app, send new OTP
                    startResendCountdown(); // Restart the countdown
                }
            });

            // Example: Hide error message on form submission (simulate a retry)
            const otpForm = document.querySelector('.otp-form');
            otpForm.addEventListener('submit', (event) => {
                event.preventDefault(); // Prevent default form submission
                otpErrorMessage.style.display = 'none'; // Hide error when submitting

                // Simulate OTP validation
                const enteredOtp = Array.from(otpInputs).map(input => input.value).join('');
                if (enteredOtp === '123456') { // Example correct OTP
                    alert('OTP Verified Successfully!');
                } else {
                    setTimeout(() => {
                        otpErrorMessage.style.display = 'block'; // Show error after a short delay
                    }, 500);
                }
            });
        });
    </script>
</body>
</html>