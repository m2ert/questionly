<?php 
include 'shared/header.php';

ob_start();
session_start();
if ($_SESSION['user_id']) {
  header('Location: /spaces');
  exit;
}

?>
<div class="form">
	<div class="form-title">
		<h1>Questionly</h1>
		<p class="description visible">
			A place to share knowledge and better understand the world
		</p>
	</div>
	<div class="form-group">
		<div class="signup-section">
			<div class="title">Sign Up</div>
			<form id="signup-form">
				<div class="form-control">
					<label for="signup_name">Name</label>
					<input type="text" id="signup_name" name="signup_name" placeholder="What would you like to be called?" />
				</div>
				<div class="form-control">
					<label for="signup_email">Email</label>
					<input type="email" id="signup_email" name="signup_email" placeholder="Your email" />
				</div>
				<div class="form-control">
					<label for="signup_password">Password</label>
					<input type="password" id="signup_password" name="signup_password" placeholder="Your password" maxlength="15" />
				</div>
				<div class="form-control">
					<button type="submit" class="btn btn-active" id="sign_submit">Sign Up</button>
				</div>
			</form>
		</div>
		<div class="login-section">
			<div class="title">Login</div>
			<form id="login-form">
				<div class="form-control">
					<label for="login_email">Email</label>
					<input type="email" id="login_email" name="login_email" placeholder="Your email" />
				</div>
				<div class="form-control">
					<label for="login_password">Password</label>
					<input type="password" id="login_password" name="login_password" placeholder="Your password" />
				</div>
				<div class="form-control">
					<button type="submit" class="btn btn-active" id="login_submit">Login</button>
					<!--<small>Forgot password ?</small>-->
				</div>
			</form>
		</div>
	</div>
</div>
<?php include 'shared/footer.php'; ?>

