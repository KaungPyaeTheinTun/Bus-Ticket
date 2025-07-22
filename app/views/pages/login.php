
<?php require_once APPROOT . '/views/inc/header.php'; ?>
<?php
    session_start();
?>
    <div class="login-container">
        <div class="left-panel">
            <div class="logo">
           <h1>MYTICKET</h1>
            </div>
            <div class="bus-image">
                <img src="<?php echo URLROOT; ?>/images/bus.png" alt="IMG">
            </div>
        </div>
        <div class="right-panel">
            <?php require_once APPROOT . '/views/components/auth_message.php'; ?>
            <h2>Login</h2>
            <form class="login-form" method="POST" action="<?php echo URLROOT; ?>/auth/login">
                <div class="form-group">
                    <label for="email" data-validate = "Valid email is required: ex@abc.xyz"></label>
                    <input type="text" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group password-group">
                    <label for="password" data-validate = "Password is required"></label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <input type="checkbox" onclick="myFunction()"> Show Password
                <div class="forgot-password">
                    <a href="<?php echo URLROOT; ?>/pages/forgetpassword">Forget password?</a>
                </div>
                <button type="submit" class="submit-button">Login</button>
                <div class="register-link">
                    Don't have an account? <a href="<?php echo URLROOT; ?>/pages/register">Register</a>
                </div>
            </form>
        </div>
    </div>
<script>
	document.addEventListener("DOMContentLoaded", function() {
    	var checkbox = document.querySelector('input[type="checkbox"]');
    	var passwordInput = document.getElementById("password");

    	checkbox.addEventListener('change', function() {
        	if (this.checked) {
            	passwordInput.type = "text";
        	} else {
            	passwordInput.type = "password";
        	}
    	});
	});
</script>

