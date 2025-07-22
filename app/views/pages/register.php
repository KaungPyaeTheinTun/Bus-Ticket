
<?php require_once APPROOT . '/views/inc/header.php'; ?>
<?php session_start(); ?>
    <div class="register-container">
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
            <h2>Register</h2>
            <form class="register-form" name="contactForm" method="POST" action="<?php echo URLROOT; ?>/auth/register">
                <p class="text-danger ml-4">
						<?php
							if(isset($data['name-err']))
							echo $data['name-err'];
						?>
					</p>
                <div class="form-group" data-validate = "Valid Name is required:">
                    <!-- <label for="name">Name</label> -->
                    <input type="text" id="name" name="name" placeholder="Name">
                </div>
				<p class="text-danger ml-4">
						<?php
							if(isset($data['phone-err']))
							echo $data['phone-err'];
						?>
				</p>
                <div class="form-group" data-validate = "Valid phone is required:">
                    <!-- <label for="phone">Phone</label> -->
                    <input type="text" id="phone" name="phone" placeholder="Phone">
                </div>
                <p class="text-danger ml-4">
						<?php
							if(isset($data['email-err']))
							echo $data['email-err'];
						?>
				</p>
                <div class="form-group" data-validate = "Valid email is required: ex@abc.xyz">
                    <!-- <label for="phone">Email</label> -->
                    <input type="text" id="email" name="email" placeholder="Email">
                </div>

                <p class="text-danger ml-4">
						<?php
							if(isset($data['password-err']))
							echo $data['password-err'];
						?>
					</p>
                <div class="form-group password-group" data-validate = "Password is required">
                    <!-- <label for="password">Password</label> -->
                    <input type="password" id="myInput" name="password" placeholder="Password">
                </div>
				<input type="checkbox" onclick="myFunction()"> Show Password
				<br><br>
                <button type="submit" class="submit-button">Submit</button>
                <div class="login-link">
                    Already register? <a href="<?php echo URLROOT; ?>/pages/login">Login</a>
                </div>
            </form>
        </div>
    </div>
<script>
	document.addEventListener("DOMContentLoaded", function() {
    	var checkbox = document.querySelector('input[type="checkbox"]');
    	var passwordInput = document.getElementById("myInput");

    	checkbox.addEventListener('change', function() {
        	if (this.checked) {
            	passwordInput.type = "text";
        	} else {
            	passwordInput.type = "password";
        	}
    	});
	});
</script>

<script>

$(function () {
    var str = $('#name').val();
    if(/^[a-zA-Z- ]*$/.test(str) == false) {
        alert('Your search string contains illegal characters.');
    }

    $("form[name='contactForm']").validate({
        rules: {
            name: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            email: {
                required: true,
                minlength: 6,
                maxlength: 50,
                email: true
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 30
            }
        },
        messages: {
            name: {
                required: "Please enter your name",
                minlength: "Name must be min 6 characters long"
            },
            email: {
                required: "Please enter your email",
                minlength: "Please enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password length must be min 8 characters long",
                maxlength: "Password length must not be more than 30 characters long"
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
    });

	// Email Validation
	$(document).ready(function() {

		// form autocomplete off
		// call input tag, set attribute [attr(attribute,value)]	
		$(":input").attr('autocomplete', 'off');

		// remove box shadow from all text input
		// call input tag
		$(":input").css('box-shadow', 'none');



		// save button click function
		// $("#savebtn").click(function() {
		
		// 	// calling validate function
		// 	var response =  validateForm();
		
		// 	// // if form validation fails			
		// 	if(response == 0) {
		// 		return;
		// 	}
		
		
		// 	// getting all form data
		// 	var name      =   $("#name").val();

		// 	var email     =   $("#email").val();

		// 	var password  =   $("#password").val();
		// 	// alert(name);
		// 	// alert(email);
		// 	// alert(password);
		// 	// exit();
		// 	var form_url = '/auth/register';
		// 	// sending ajax request
		// 	$.ajax({
		
		// 		url: form_url,
		// 		type: 'post',
		// 		data: {
		// 				 'name' : name, 
		// 				 'email': email,
		// 				 'password' : password,
		// 				//  'save' : 1
		// 			},
		
		// 		// before ajax request
		// 		beforeSend: function() {
		// 			$("#result").html("<p class='text-success'> Please wait.. </p>");
		// 		},	
		
		// 		// on success response
		// 		success:function(response) {
		// 			$("#result").html(response);
		
		// 			// reset form fields
		// 			$("#regForm")[0].reset();
		// 		},
		
		// 		// error response
		// 		error:function(e) {
		// 			$("#result").html("Some error encountered.");
		// 		}
		
		// 	});
		// });
		
		
		
		
		// ------------- form validation -----------------
		
		// function validateForm() {
		
		// 	// removing span text before message
		// 	$("#error").remove();
		
		
		// 	// validating input if empty
		// 	if($("#name").val() == "") {
		// 		$("#name").after("<span id='error' class='text-danger'> Enter your name </span>");
		// 		return 0;
		// 	}
		
		// 	if($("#email").val() == "") {
		// 		$("#email").after("<span id='error' class='text-danger'> Enter your email </span>");
		// 		return 0;
		// 	}
		
		// 	if($("#password").val() == "") {
		// 		$("#password").after("<span id='error' class='text-danger'> Enter your password </span>");
		// 		return 0;
		// 	}
		

		// 	return 1;
		
		// }

		// remove focus from the text field, remove cursor
		$("#name").blur(function() {
		
		var name  		= 		$('#name').val();//to get the values of form elements(input field)
		
		
		// if name is empty then return
		if(name == "") {
			return;
		}
		// call formRegister method from controllers>auth>formRegister()
		var form_url = '<?php echo URLROOT; ?>/auth/formRegister';

		// send ajax request if name is not empty
		$.ajax({
				url:form_url,
				type: 'post',
				data: {
					'name':name,

			},
		
			success:function(response) {	
			
				// clear span before error message
				$("#name_error").remove();
			
				// adding span after name textbox with error message
				$("#name").after("<span id='name_error' class='text-danger'>"+response+"</span>");
			},
		
			error:function(e) {
				$("#result").html("Something went wrong");
			}
		
		});
	});
</script>
