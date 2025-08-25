   <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-about">
                <div class="logo">
                    <span>MYBUSTICKET</span>
                </div>
                <p>MYBUSTICKET, Myanmar's #1 online bus ticketing platform since 2017. Easy, fast, and safe ticketing experience.</p>
            </div>
            <div class="footer-links">
                <h3>Legal</h3>
                <ul>
                    <li><a href="<?php echo URLROOT; ?>/pages/term&condition" style="color:blue;">Terms & Conditions</a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/policy" style="color:blue;">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h3>Contact</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-phone"></i> +959 123 456 789</a></li>
                    <li> <?php if (isset($_SESSION['session_loginuserid'])): ?>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=kkpp42877@gmail.com" target="_blank">
                            <i class="fas fa-envelope"></i> kkpp42877@gmail.com
                        </a>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/users/login">
                            <i class="fas fa-envelope"></i> Login to send mail
                        </a>
                    <?php endif; ?></li>
                </ul>
            </div>
        </div>
     <div class="footer-bottom">
        <p>&copy; 2025 MYBUSTICKET. All rights reserved.</p>
    </div>
    </footer>
</body>
</html>