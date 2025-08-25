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
<style>
        .left-panel .logo h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        position: relative;
    }
    .success-tick {
        color: green;
        font-size: 18px;
        margin-left: 6px;
        display: none;
        vertical-align: middle;
    }
    .loader {
        border: 3px solid #f3f3f3; /* Light grey */
        border-top: 3px solid var(--primary-blue); /* Blue */
        border-radius: 50%;
        width: 18px;
        height: 18px;
        animation: spin 1s linear infinite;
        display: inline-block;
        margin-left: 8px;
        vertical-align: middle;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
<body>
<div class="otp-container">
    <div class="left-panel">
        <div class="logo"><h1>MYBUSTICKET</h1></div>
        <div class="bus-image"><img src="<?php echo URLROOT; ?>/images/bus.png" alt="Bus Image"></div>
    </div>

    <div class="right-panel">
        <div class="icon-circle"><i class="fas fa-envelope"></i></div>
        <?php require_once APPROOT . '/views/components/auth_message.php'; ?>
        <p id="resendMessage" style="color:green; margin-top:5px;font-size:17px;"></p>       
        
        <h2>Enter OTP</h2>
        <p class="instruction-text">An OTP has been sent to</p>
        <p class="phone-number"><?php echo $user; ?></p>

        <form class="otp-form" method="POST" action="<?php echo URLROOT; ?>/auth/otp">
            <div class="otp-inputs">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
                <?php endfor; ?>
            </div>
            <button type="submit" class="submit-button">Submit</button>
        </form>
        <br>
        <div class="resend-section">
            <span id="resendTimer">Didn't get code?</span>
            <a href="javascript:void(0)" id="requestAgainLink" class="disabled">Request again</a>
            <span id="loadingSpinner" class="loader" style="display:none;"></span>
            <i id="successTick" class="fas fa-check-circle success-tick"></i>
        </div>
    </div>
</div>

<script>
    //opt
    document.addEventListener('DOMContentLoaded', function() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const requestAgainLink = document.getElementById('requestAgainLink');
        const resendTimerSpan = document.getElementById('resendTimer');
        let countdown = 10, timer = null;

        // Start resend countdown
        function startResendCountdown() {
            countdown = 10;
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

        requestAgainLink.addEventListener('click', function(e) 
        {
            e.preventDefault();
            if (this.classList.contains('disabled')) return;

            const spinner = document.getElementById('loadingSpinner');
            const tick = document.getElementById('successTick');
            tick.style.display = "none";          // hide tick before request
            spinner.style.display = "inline-block"; // show spinner

            fetch("<?php echo URLROOT; ?>/auth/resendOtp", {
                method: "POST",
                headers: { "Content-Type": "application/json" }
            })
            .then(res => res.json())
            .then(data => {
                spinner.style.display = "none"; // hide spinner
                const msgBox = document.getElementById('resendMessage');

                if (data.success) {
                    msgBox.style.color = "green";
                    msgBox.textContent = data.message;
                    tick.style.display = "inline"; // show tick ✅
                    startResendCountdown();
                } else {
                    msgBox.style.color = "red";
                    msgBox.textContent = data.message;
                }
            })
            .catch(() => {
                spinner.style.display = "none"; // hide spinner
                const msgBox = document.getElementById('resendMessage');
                msgBox.style.color = "red";
                msgBox.textContent = "⚠️ Error contacting server.";
            });
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
