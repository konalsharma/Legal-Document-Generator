<?php
 include ('include/connect.php');

session_start();
$errors = $_SESSION['errors'] ?? [];
$old_data = $_SESSION['old_data'] ?? [];
$show_register = isset($_GET['from']) && $_GET['from'] === 'register';
unset($_SESSION['errors']);
unset($_SESSION['old_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login & Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #6f42c1;
      --secondary-color: #764ba2;
      --accent-color: #667eea;
      --light-color: #f8f9fa;
      --dark-color: #343a40;
      --transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    body {
      background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
      padding: 1rem;
    }
    
    .auth-card {
      overflow: hidden;
      border-radius: 1.25rem;
      box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2);
      border: none;
      max-width: 420px;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.95);
    }
    
    .toggle-btns {
      display: flex;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .toggle-btns button {
      border: none;
      flex: 1;
      padding: 1.25rem;
      font-weight: 600;
      background-color: transparent;
      color: var(--dark-color);
      transition: var(--transition);
      position: relative;
      font-size: 1.1rem;
    }
    
    .toggle-btns button::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 3px;
      background-color: var(--primary-color);
      transition: var(--transition);
    }
    
    .toggle-btns button.active {
      color: var(--primary-color);
    }
    
    .toggle-btns button.active::after {
      width: 100%;
    }
    
    .form-slider {
      display: flex;
      width: 200%;
      transition: var(--transition);
    }
    
    .form-container {
      width: 50%;
      padding: 2.5rem 2rem;
    }
    
    .form-title {
      color: var(--primary-color);
      margin-bottom: 2rem;
      font-weight: 700;
      text-align: center;
      position: relative;
    }
    
    .form-title::after {
      content: '';
      position: absolute;
      bottom: -0.75rem;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background-color: var(--primary-color);
      border-radius: 3px;
    }
    
    .form-floating > .form-control {
      border-radius: 0.5rem;
      padding: 1rem 0.75rem;
      border: 1px solid rgba(0, 0, 0, 0.1);
      transition: var(--transition);
    }
    
    .form-floating > .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
      border-color: var(--primary-color);
    }
    
    .form-floating > label {
      padding: 0.75rem;
      color: #6c757d;
      transition: var(--transition);
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
      transform: scale(0.85) translateY(-0.75rem) translateX(0.15rem);
      color: var(--primary-color);
    }
    
    .password-toggle {
      cursor: pointer;
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      transition: var(--transition);
    }
    
    .password-toggle:hover {
      color: var(--primary-color);
    }
    
    .btn-auth {
      border-radius: 0.5rem;
      padding: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      font-size: 0.9rem;
      transition: var(--transition);
      margin-top: 1rem;
    }
    
    .btn-login {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-login:hover {
      background-color: #5a32a8;
      border-color: #5a32a8;
      transform: translateY(-2px);
    }
    
    .btn-register {
      background-color: #28a745;
      border-color: #28a745;
    }
    
    .btn-register:hover {
      background-color: #218838;
      border-color: #218838;
      transform: translateY(-2px);
    }
    
    .form-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: #6c757d;
    }
    
    .form-footer a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }
    
    .form-footer a:hover {
      text-decoration: underline;
    }
    
    /* Animation for form switch */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .form-container {
      animation: fadeIn 0.5s ease-out;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
      .auth-card {
        max-width: 100%;
      }
      
      .form-container {
        padding: 2rem 1.5rem;
      }
      
      .toggle-btns button {
        padding: 1rem;
        font-size: 1rem;
      }
    }
	
	.form-floating>.form-control-plaintext:focus, .form-floating>.form-control-plaintext:not(:placeholder-shown), .form-floating>.form-control:focus, .form-floating>.form-control:not(:placeholder-shown) {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}

.intl-tel-input {
    position: relative;
    display: inline-block;
    width: 100% !important;
    height: 55px !important;
}

input#mobile{
    height: 55px !important;
}
  </style>
  

  
</head>
<body>
  <div class="auth-card">
    <div class="toggle-btns">
      <button id="loginBtn" class="<?php echo $show_register ? '' : 'active' ?>">Sign In</button>
      <button id="registerBtn" class="<?php echo $show_register ? 'active' : '' ?>">Create Account</button>
    </div>
    <div class="form-slider" id="formSlider">
      <!-- Login Form -->
      <div class="form-container">
        <h4 class="form-title">Welcome Back</h4>
		<!-- Login Error Display -->
        <?php if (!empty($errors) && !$show_register): ?>
          <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
              <p class="mb-1"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <form action="login.php" method="POST">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="loginEmail" name="email" placeholder="name@example.com" required >
            <label for="loginEmail">Email address</label>
          </div>
          <div class="form-floating mb-3 position-relative">
            <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password" required>
            <label for="loginPassword">Password</label>
            <i class="bi bi-eye password-toggle" onclick="togglePassword('loginPassword', this)"></i>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="#" class="text-decoration-none">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary w-100 btn-auth btn-login">Sign In</button>
          <div class="form-footer">
            Don't have an account? <a href="#" id="switchToRegister">Sign up</a>
          </div>
        </form>
      </div>

      
      <!-- Register Form -->
      <div class="form-container">
        <h4 class="form-title">Create Account</h4>
		<!-- Registration Error Display -->
        <?php if (!empty($errors) && $show_register): ?>
          <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
              <p class="mb-1"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
		
		<div class="form-floating mb-3">
  <input type="text" class="form-control" id="registerUsername" name="username" placeholder="Your Name" required
         value="<?php echo htmlspecialchars($old_data['username'] ?? ''); ?>">
  <label for="registerUsername">Name</label>
</div>

          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="registerEmail" name="email" placeholder="name@example.com" required
                   value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>">
            <label for="registerEmail">Email address</label>
          </div>
          <div class="form-floating mb-3 position-relative">
           <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Password" required>
           <label for="registerPassword">Password</label>
           <i class="bi bi-eye password-toggle" onclick="togglePassword('registerPassword', this)"></i>
          </div>
          <div class="form-floating mb-3 position-relative">
           <input type="password" class="form-control" id="registerConfirmPassword" name="confirm_password" placeholder="Confirm Password" required>
           <label for="registerConfirmPassword">Confirm Password</label>
           <i class="bi bi-eye password-toggle" onclick="togglePassword('registerConfirmPassword', this)"></i>
          </div>
			<div class="form-floating mb-3">
			<input id="mobile" type="tel" name="mobile" class="form-control" 
				placeholder="Your Mobile Number"
				value="<?php echo htmlspecialchars($old_data['mobile'] ?? ''); ?>">
			<span class="error" id="mobile_err"> </span>
			</div>
	

		  
		  	  <?php

			 
	      //Get all country data
    $query = "SELECT * FROM countries  ORDER BY country_name ASC";
    $run_query = mysqli_query($conn, $query);
    //Count total number of rows
	$count = mysqli_num_rows($run_query);
	?>
		  
	<div class="form-floating mb-3 position-relative">
			<select class="form-control" name="country" id="country">
			<option class="form-control" value="">Select Country</option>
			
			
			
			        <?php
        if($count > 0){
            while($row = mysqli_fetch_array($run_query)){
				$country_id=$row['country_id'];
				$country_name=$row['country_name'];
                echo "<option value='$country_id'>$country_name</option>";
            }
        }else{
            echo '<option value="">Country not available</option>';
        }
        ?>
			</select>
			</div>
		  
	<div class="form-floating mb-3 position-relative">
			<select class="form-control" name="state" id="state">
			<option class="form-control" value="">Select country first</option>
			</select>
			</div>
			
				<div class="form-floating mb-3 position-relative">
			<select class="form-control" name="city" id="city">
        <option class="form-control" value="">Select state first</option>
			</select>
			</div>
		  
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
            <label class="form-check-label" for="agreeTerms">I agree to the <a href="#">Terms & Conditions</a></label>
          </div>
          <button type="submit" class="btn btn-success w-100 btn-auth btn-register">Create Account</button>
          <div class="form-footer">
            Already have an account? <a href="#" id="switchToLogin">Sign in</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
    <script src="jquery-country.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#country').on('change',function(){
        var countryID = $(this).val();
        if(countryID){
            $.ajax({
                type:'POST',
                url:'ajaxFile-country.php',
                data:'country_id='+countryID,
                success:function(html){
                    $('#state').html(html);
                    $('#city').html('<option value="">Select state first</option>'); 
                }
            }); 
        }else{
            $('#state').html('<option value="">Select country first</option>');
            $('#city').html('<option value="">Select state first</option>'); 
        }
    });
    
    $('#state').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'ajaxFile-country.php',
                data:'state_id='+stateID,
                success:function(html){
                    $('#city').html(html);
                }
            }); 
        }else{
            $('#city').html('<option value="">Select state first</option>'); 
        }
    });
});
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
<script>
  $(function() {
    $.get("https://ipapi.co/json/", function(response) {
      var initialCountryCode = response.country;
      initializeTelInput(initialCountryCode);
    });

    function initializeTelInput(initialCountryCode) {
      $('#mobile').intlTelInput({
        autoHideDialCode: true,
        autoPlaceholder: "ON",
        dropdownContainer: document.body,
        formatOnDisplay: true,
        initialCountry: initialCountryCode,
        placeholderNumberType: "MOBILE",
        preferredCountries: ['us', 'gb', 'in'],
        separateDialCode: true
      });

      $("form").on("submit", function(event) {
    var dialCode = $("#mobile").intlTelInput("getSelectedCountryData").dialCode;
    $("<input />").attr("type", "hidden").attr("name", "dialCode").attr("value", dialCode).appendTo("form");
});
    }
  });
  
  
</script>



  

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loginBtn = document.getElementById('loginBtn');
      const registerBtn = document.getElementById('registerBtn');
      const switchToRegister = document.getElementById('switchToRegister');
      const switchToLogin = document.getElementById('switchToLogin');
      const formSlider = document.getElementById('formSlider');
      
      // Function to switch to login form
      function showLoginForm() {
        formSlider.style.transform = 'translateX(0%)';
        loginBtn.classList.add('active');
        registerBtn.classList.remove('active');
      }
      
      // Function to switch to register form
      function showRegisterForm() {
        formSlider.style.transform = 'translateX(-50%)';
        registerBtn.classList.add('active');
        loginBtn.classList.remove('active');
      }
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('from') === 'register') {
         showRegisterForm();
      } else {
       showLoginForm();
      }
	  
      // Event listeners for buttons
      loginBtn.addEventListener('click', showLoginForm);
      registerBtn.addEventListener('click', showRegisterForm);
      switchToRegister.addEventListener('click', function(e) {
        e.preventDefault();
        showRegisterForm();
      });
      switchToLogin.addEventListener('click', function(e) {
        e.preventDefault();
        showLoginForm();
      });
      
      // Password toggle function
      window.togglePassword = function(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('bi-eye');
          icon.classList.add('bi-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('bi-eye-slash');
          icon.classList.add('bi-eye');
        }
      }
      
      // Form validation would go here in a real implementation
    });
  </script>
</body>
</html>