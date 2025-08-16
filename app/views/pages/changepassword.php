<?php require_once APPROOT . '/views/inc/header.php'; ?>
<?php
    session_start();
?>
<style>
    .password-input {
        /* your styles here, for example: */
        border: 1px solid #ccc;
        padding: 10px 12px;
        width: 100%;
        border-radius: 4px;
            font-size: 15px;
    }
</style>
    <div class="change-password-container">
        <div class="left-panel">
            <div class="logo">
             <h1>MYTICKET</h1>
            </div>
            <div class="bus-image">
                <img src=" <?php echo URLROOT; ?>/images/bus.png" alt="IMG"> 
            </div>
        </div>
        <div class="right-panel">
            <?php require_once APPROOT . '/views/components/auth_message.php';?>
            <h2>Change Password</h2>
            <form class="change-password-form" method = 'POST' action='<?php echo URLROOT; ?>/auth/changepassword'>
			<?php echo SessionHelper::csrfInput(); ?>    
            <p class="text-danger ml-4">
					<?php
						if(isset($data['password-err']))
						echo $data['password-err'];
					?>
				</p>
                <div class="form-group">
                    <input type="password" id="new-password" name="password" placeholder="New Password" class="password-input" value="<?php echo htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm New Password" class="password-input" value="<?php echo htmlspecialchars($_POST['confirm_password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <input type="checkbox"> Show Password
                <br><br>
                <button type="submit" class="submit-button">Confirm</button></a>
            </form>
        </div>
    </div>

<script>
        document.addEventListener("DOMContentLoaded", function() {
        var checkbox = document.querySelector('input[type="checkbox"]');
        var newPasswordInput = document.getElementById("new-password");
        var confirmPasswordInput = document.getElementById("confirm-password");

        checkbox.addEventListener('change', function() {
            var type = this.checked ? "text" : "password";
            newPasswordInput.type = type;
            confirmPasswordInput.type = type;
        });
    });
</script>
</body>
</html>