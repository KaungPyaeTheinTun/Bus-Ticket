<?php require_once APPROOT . '/views/inc/header.php'; ?>
<?php session_start(); ?>
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
    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary-blue);
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 0.8s linear infinite;
        display: inline-block;
        margin-left: 8px;
        vertical-align: middle;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
    <div class="forget-password-container">
        <div class="left-panel">
            <div class="logo">
                <h1>MYBUSTICKET</h1>
            </div>
            <div class="bus-image">
                <img src=" <?php echo URLROOT; ?>/images/bus.png" alt="IMG">
             </div>
        </div>
        <div class="right-panel">
            <?php require_once APPROOT . '/views/components/auth_message.php';?>
            <h2>Forget Password</h2>
            <p class="subtitle">Reset using email</p>
            <form class="forget-password-form" method="POST" action="<?php echo URLROOT; ?>/auth/forgetpassword">
			<?php echo SessionHelper::csrfInput(); ?>    
            <p class="text-danger ml-4">
                            <?php
                                if(isset($data['email-err']))
                                echo $data['email-err'];
                            ?>
                </p>    
                <div class="form-group">
                    <input type="text" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="submit-button">Submit<span id="submitSpinner" class="spinner" style="display:none;"></span></button></a>
                    <a href="<?php echo URLROOT; ?>/pages/login"><button type="button" class="cancel-button">Cancel</button></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.forget-password-form');
        const submitButton = form.querySelector('.submit-button');
        const spinner = document.getElementById('submitSpinner');

        form.addEventListener('submit', function() {
            // Show spinner
            spinner.style.display = 'inline-block';
            // Disable button to prevent multiple clicks
            submitButton.disabled = true;
        });
    });
</script>
