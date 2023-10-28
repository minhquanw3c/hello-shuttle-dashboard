<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="<?= base_url('static/images/logo/favicons/32x36.png') ?>" type="image/x-icon">
	<link href="<?= base_url('static/css/theme/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= base_url('static/css/vendors/bootstrap.min.css') ?>" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/bootstrap-vue.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/bootstrap-vue-icons.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/vue-multiselect.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('static/css/theme/styles.css') ?>">
	<title><?= $pageTitle ?> | Hello Shuttle</title>
	<script type="text/javascript">
		const baseURL = "<?= base_url('/') ?>";
	</script>
	<style>
		.max-width-logo {
			max-width: 180px;
			width: 100%;
		}
	</style>
</head>

<body>
	<div class="d-flex wrapper" id="wrapper">
		<!-- Sidebar -->
		<div id="sidebar-wrapper" class="sidebar-wrapper">
			<div class="sidebar-heading">
				<a href="<?= base_url('/') ?>">
					<img
						src="<?= base_url('static/images/logo/hello-shuttle-gold-hand-black-text.png') ?>"
						class="mt-4 ps-3 ms-4 max-width-logo"
						alt="hello-shuttle-car-rental"
					/>
				</a>
			</div>
			<nav class="sidebar py-2 mb-4">
				<ul class="nav flex-column" id="nav_accordion">
					<?php
						$nav_items = session()->get('logged_in.nav_items_data');
					?>
					<?php foreach($nav_items as $nav): ?>
						<li class="nav-item">
							<a class="nav-link d-flex justify-content-between align-items-center" href="<?= base_url('/' . $nav['navRoute']) ?>">
								<span class="d-flex gap-1 align-items-center">
									<span class="material-symbols-rounded"> <?= $nav['navIcon'] ?> </span> <?= $nav['navTitle'] ?>
								</span>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
			</nav>
		</div>
		<!-- /#sidebar-wrapper -->

		<!-- Page Content -->
		<div id="page-content-wrapper" class="page-content-wrapper">
			<nav class="navbar navbar-expand-lg py-lg-3 px-2 px-lg-4 d-flex fixed-top justify-content-between">
				<div class="d-flex align-items-center">
					<div class="d-flex align-items-center">
						<span class="material-symbols-outlined menu-toggle" id="menu-toggle">
							menu
						</span>
					</div>
				</div>

				<div class="d-flex gap-2 p-lg-2 p-lg-0 align-items-center justify-content-end">

					<div class="nav-item dropdown">
						<a
							class="d-flex gap-2 align-items-center"
							href="javascript:void(0)"
							id="navbarDropdown4"
							role="button"
							data-bs-toggle="dropdown"
							aria-expanded="false">
							<img src="<?= base_url('static/images/users/avatar.png') ?>" alt="user">
							<div class="d-flex flex-column d-none d-xl-block">
								<p class="mb-0"><?= session()->get('logged_in.user_full_name') ?></p>
								<span class="small">Account Settings</span>
							</div>
							<span class="material-symbols-outlined d-none d-lg-block">
								expand_more
							</span>
						</a>
						<ul
							class="dropdown-menu dropdown-menu-end user shadow border-0"
							aria-labelledby="navbarDropdown4"
              				onclick="event.stopPropagation()">
							  <li><span class="px-3 d-inline-block">Welcome <?= session()->get('logged_in.userFullName') ?></span></li>
							  <li>
								<a
									class="dropdown-item d-flex align-items-center gap-1"
									href="<?= base_url('logout') ?>">
									<span class="material-symbols-outlined"> logout </span>
									Log Out
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- For pc -->
			</nav>

			<div class="container-fluid main-content px-2 px-lg-4 pt-3 pt-lg-5 mt-5">
				<div class="row">
					<h3 class="mt-4"><?= $pageTitle ?></h3>
				</div>
				<!-- Main content -->
				<div class="row my-2 g-3 g-lg-4">
					<div class="col-12">
						<div id="main-app" v-cloak>
							<?= $this->renderSection("main") ?>
						</div>
					</div>
				</div>

				<div class="row my-2 g-3 g-lg-4 top-border footer">
					<div class="col-lg-6">
						<span class="text-center text-lg-start d-block w-100">Copyright © <?= date('Y') ?> | Designed by
							<a href="https://dannythedesigner.com/" target="_blank">DannyTheDesigner</a></span>
					</div>
					<div class="col-lg-6">
						<ul class="d-flex gap-2 gap-xl-4 p-0 align-items-center flex-wrap justify-content-center justify-content-lg-end">
							<li><a href="javascript:void(0)">Support</a></li>
							<li><a href="javascript:void(0)">Help Center</a></li>
							<li><a href="javascript:void(0)">Privacy</a></li>
							<li><a href="javascript:void(0)">Terms of Service</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- /#page-content-wrapper -->
	</div>

	<script src="<?= base_url('static/js/vendors/jquery.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/popper.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/axios.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap-v5.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/lodash.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/moment-with-locales.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/vue.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/vue-multiselect.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/portal-vue.umd.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap-vue.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap-vue-icons.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/validators.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/vuelidate.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/theme/main.js') ?>"></script>
	
    <?= $this->renderSection('page-scripts') ?>
</body>

</html>