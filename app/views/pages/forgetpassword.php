<?php require_once APPROOT . '/views/inc/header.php'; ?>
<?php session_start(); ?>

    <div class="forget-password-container">
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
                    <button type="submit" class="submit-button">Submit</button></a>
                    <a href="<?php echo URLROOT; ?>/pages/login"><button type="button" class="cancel-button">Cancel</button></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>