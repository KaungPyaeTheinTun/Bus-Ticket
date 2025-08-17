<?php 
session_start();
if (!isset($_SESSION['post_mail'])) {
    header('Location: ' . URLROOT . '/pages/forgetpassword');
    exit;
}
$user = $_SESSION['post_mail'];

$prefillOtp = isset($_GET['otp']) ? $_GET['otp'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MY_BUS_TICKET</title>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/buscss/login/otp.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/images/icons/icon.png"/>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/vendor/animate/animate.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/vendor/css-hamburgers/hamburgers.min.css">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/vendor/select2/select2.min.css">
</head>
<body>
<div class="otp-container">
    <div class="left-panel">
        <div class="logo"><h1>MYTICKET</h1></div>
        <div class="bus-image"><img src="<?php echo URLROOT; ?>/images/bus.png" alt="Bus Image"></div>
    </div>

    <div class="right-panel">
        <div class="icon-circle"><i class="fas fa-envelope"></i></div>
        <?php require_once APPROOT . '/views/components/auth_message.php'; ?>
        
        <h2>Enter OTP</h2>
        <p class="instruction-text">An OTP has been sent to</p>
        <p class="phone-number"><?php echo $user; ?></p>

        <form class="otp-form" method="POST" action="<?php echo URLROOT; ?>/auth/otp">
            <div class="otp-inputs">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" name="otp[]" maxlength="1" class="otp-input">
                <?php endfor; ?>
            </div>
            <button type="submit" class="submit-button">Submit</button>
        </form>

        <div class="resend-section">
            <span id="resendTimer">Didn't get code?</span>
            <a href="#" id="requestAgainLink" class="disabled">Request again</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const requestAgainLink = document.getElementById('requestAgainLink');
        const resendTimerSpan = document.getElementById('resendTimer');
        let countdown = 5, timer = null;

        // Start resend countdown
        function startResendCountdown() {
            countdown = 5;
            requestAgainLink.classList.add('disabled');
            requestAgainLink.style.pointerEvents = 'none';
            requestAgainLink.style.color = '#ccc';
            updateTimerText();

            if (timer) clearInterval(timer);
            timer = setInterval(() => {
                countdown--;
                updateTimerText();
                if (countdown <= 0) {
                    clearInterval(timer);
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

        requestAgainLink.addEventListener('click', function(e) {
            e.preventDefault();
            if (!this.classList.contains('disabled')) startResendCountdown();
        });

        startResendCountdown();

        // Auto-focus & backspace logic
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                let val = e.target.value;
                if (val.length > 1) val = val.slice(-1);
                e.target.value = val;
                if (val && index < otpInputs.length - 1) otpInputs[index + 1].focus();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !e.target.value && index > 0) otpInputs[index - 1].focus();
            });
        });

        // Paste OTP
        otpInputs[0].addEventListener('paste', function(e) {
            const pasteData = e.clipboardData.getData('text').trim();
            if (/^\d+$/.test(pasteData)) {
                pasteData.split('').forEach((char, i) => { if (otpInputs[i]) otpInputs[i].value = char; });
                otpInputs[Math.min(pasteData.length, otpInputs.length) - 1].focus();
                e.preventDefault();
            }
        });

        // Smooth auto-fill OTP from GET parameter
        const prefillOtp = "<?php echo $prefillOtp; ?>";
        if (prefillOtp.length === otpInputs.length) {
            prefillOtp.split('').forEach((char, i) => {
                setTimeout(() => {
                    otpInputs[i].value = char;
                    if (i < otpInputs.length - 1) otpInputs[i + 1].focus();
                }, i * 200); // 200ms delay between digits
            });
        }
    });
</script>
</body>
</html>
