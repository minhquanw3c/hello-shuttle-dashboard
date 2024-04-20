<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?= base_url('static/images/logo/favicons/32x36.png') ?>" type="image/x-icon">
	<link href="<?= base_url('static/css/theme/bootstrap.min.css') ?>" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('static/css/theme/styles.css') ?>">
	<style>
		.text-right {
			text-align: right !important;
		}

		.max-width-logo {
			max-width: 250px;
			width: 100%;
		}
	</style>
	<title>Login | Hello Shuttle</title>
</head>

<body>
	<div class="container-fluid px-0">
		<div class="row">
			<div class="col-lg-5 px-0 bg-login d-none d-lg-block">
				<div class="login-left pb-4">
					<a href="<?= base_url('/') ?>">
						<img
							src="<?= base_url('static/images/logo/hello-shuttle-gold-hand-white-text.png') ?>"
							class="mt-4 ps-3 ms-4 max-width-logo"
							alt="hello-shuttle-car-rental"
						/>
					</a>
					<img class="img-fluid login-img" src="<?= base_url('static/images/login.png') ?>" alt="">
					<img src="<?= base_url('static/images/ball-b.png') ?>" class="ball1 img-fluid d-none d-md-block" alt="">
					<img src="<?= base_url('static/images/ball-b.png') ?>" class="ball2 img-fluid d-none d-md-block" alt="">
					<img src="<?= base_url('static/images/ball-s.png') ?>" class="ball3 img-fluid d-none d-md-block" alt="">
					<img src="<?= base_url('static/images/ball-s.png') ?>" class="ball4 img-fluid d-none d-md-block" alt="">
					<img src="<?= base_url('static/images/ball-s.png') ?>" class="ball5 img-fluid d-none d-md-block" alt="">
					<img class="social1 img-fluid" src="<?= base_url('static/images/fb_login.png') ?>" alt="">
					<img class="social2 img-fluid" src="<?= base_url('static/images/google_login.png') ?>" alt="">
					<img class="social3 img-fluid" src="<?= base_url('static/images/apple_login.png') ?>" alt="">
					<img class="social4 img-fluid" src="<?= base_url('static/images/twitter_login.png') ?>" alt="">
					<div class="text-center pb-5">
						<h4 class="fw-semibold text-white">Welcome to system</h4>
						<span class="pg-large text-white">Start your system journey now!</span>
					</div>
				</div>
			</div>
			<div class="col-lg-7 px-0">
				<div class="login-right">
					<div class="login-form">
						<div class="d-flex justify-content-end mb-3">
							<button class="btn btn-primary" id="adminBtn">Admin</button>
							<button class="btn btn-primary mx-2" id="employeeBtn">Employee</button>
							<button class="btn btn-primary" id="customerBtn">Customer</button>
						</div>
						<?php
							$errors = validation_errors();
						?>
						<?php if (! empty($errors)): ?>
							<div class="alert alert-danger" role="alert">
								<ul class="list-group">
								<?php foreach ($errors as $error): ?>
									<li><?= esc($error) ?></li>
								<?php endforeach ?>
								</ul>
							</div>
						<?php endif ?>

						<?= form_open(base_url('login'), ['name' => 'login-form']); ?>
							<label for="email" class="large mb-2">Email address</label>
							<input type="email" name="email" id="email" placeholder="info@example.com" class="form-control mb-3 border-0 py-2" required value="<?= set_value('email') ?>">
							<div class="inputgroup">
								<label for="txtPassword" class="large mb-2">Password</label>
								<input type="password" name="password" id="txtPassword" placeholder="Password" class="form-control mb-3 border-0 py-2" required value="<?= set_value('password') ?>">
								<button type="button" id="btnToggle" class="toggle">
									<span id="eyeIcon" class="material-symbols-outlined">
										visibility
									</span>
								</button>
							</div>
							<div class="d-flex mt-5 justify-content-end">
								<a class="outline-btn text-center w-25 me-3" href="<?= $bookingFormUrl ?>">
									Back to booking
								</a>
								<button type="submit" class="primary-btn w-25">Sign in</button>
							</div>
						<?= form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		let darkmode = localStorage.getItem("dark");

		if (darkmode) {
			document.body.classList.add("dark");
		}

		let passwordInput = document.getElementById("txtPassword"),
			emailInput = document.getElementById("email"),
			toggle = document.getElementById("btnToggle"),
			icon = document.getElementById("eyeIcon"),
			adminBtn = document.getElementById("adminBtn"),
			employeeBtn = document.getElementById("employeeBtn"),
			customerBtn = document.getElementById("customerBtn");

		function togglePassword() {
			if (passwordInput.type === "password") {
				passwordInput.type = "text";
				icon.innerHTML = "visibility_off";
				//toggle.innerHTML = 'hide';
			} else {
				passwordInput.type = "password";
				icon.innerHTML = "visibility";
				//toggle.innerHTML = 'show';
			}
		}

		function checkInput() {
			if (passwordInput.value === '') {
				toggle.style.display = 'none';
				toggle.innerHTML = 'show';
				passwordInput.type = 'password';
			} else {
				toggle.style.display = 'block';
			}
		}

		function autoFillLoginInfo(role) {
			const pwd = "123";

			if (role === "admin") {
				emailInput.value = "admin@helloshuttle.localhost";
			}

			if (role === "employee") {
				emailInput.value = "teststaff@helloshuttle.localhost";
			}

			if (role === "customer") {
				emailInput.value = "testcustomer001@gmail.com";
			}

			passwordInput.value = pwd;
		}

		toggle.addEventListener("click", togglePassword, false);
		passwordInput.addEventListener("keyup", checkInput, false);

		adminBtn.addEventListener("click", () => autoFillLoginInfo("admin"), false);
		employeeBtn.addEventListener("click", () => autoFillLoginInfo("employee"), false);
		customerBtn.addEventListener("click", () => autoFillLoginInfo("customer"), false);
	</script>
</body>

</html>