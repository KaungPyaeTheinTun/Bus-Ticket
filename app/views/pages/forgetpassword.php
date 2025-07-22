<?php require_once APPROOT . '/views/inc/header.php'; ?>

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
            <h2>Forget Password</h2>
            <p class="subtitle">Reset using phone number</p>
            <form class="forget-password-form" method="POST" action="<?php echo URLROOT; ?>/auth/forgetpassword">
                <div class="form-group">
                    <input type="text" id="email" name="email" placeholder="Email">
                </div>
                <div class="form-actions">
                    <button type="submit" class="submit-button">Submit</button></a>
                    <button type="button" class="cancel-button">Cancel</button></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>