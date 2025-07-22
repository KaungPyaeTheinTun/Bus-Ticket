<?php require_once APPROOT . '/views/inc/sidebar.php' ?>

<style>
    .error-message {
        color: red;
        font-size:14px;
    }
</style>
    <div class="container">
        <main class="main-content">
            <?php require_once APPROOT . '/views/inc/profileHeader.php' ?>
            <section class="add-admin-card"> 
                <?php require APPROOT . '/views/components/auth_message.php'; ?>         
                <form class="admin-form" name="contactForm" method="POST" action="<?php echo URLROOT; ?>/auth/adminRegister">
                    <p class="error-message">
						<?php
							if(isset($data['name-err']))
							echo $data['name-err'];
						?>
					</p>
                    <div class="form-group">
                        <input type="text" id="name" required name="name" class="input-field" placeholder="Name">
                    </div>

                    <p class="error-message">
						<?php
							if(isset($data['phone-err']))
							echo $data['phone-err'];
						?>
				    </p>
                    <div class="form-group">
                        <input type="text" id="phone" required name="phone" class="input-field" placeholder="Phone">
                    </div>

                    <p class="error-message">
						<?php
							if(isset($data['email-err']))
							echo $data['email-err'];
						?>
				    </p>
                    <div class="form-group">
                        <input type="text" id="email" required name="email" class="input-field" placeholder="Email">
                    </div>

                    <p class="error-message">
						<?php
							if(isset($data['password-err']))
							echo $data['password-err'];
						?>
					</p>
                    <div class="form-group">
                        <div class="password-field-container">
                            <input type="password" required id="password" name="password" class="input-field password-input" placeholder="Enter Password">
                        </div><br>
                        <input type="checkbox" onclick="myFunction()"> Show Password
                    </div>                 
                    <button type="submit" class="add-btn">Add</button>
                </form>

                <div class="back-link-container">
                    <a href="<?php echo URLROOT; ?>/user/profile" style="text-decoration: none;"><i class="fas fa-arrow-left"></i> Back Profiles</a>
                </div>
            </section>

    </div>
</body>
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
</html>