<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?= base_url('static/images/favicon.ico') ?>" type="image/x-icon">
  <link href="<?= base_url('static/css/theme/bootstrap.min.css') ?>" rel="stylesheet">
  <link rel="stylesheet"
	href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
  <link rel="stylesheet"
	href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/bootstrap-vue.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/bootstrap-vue-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('static/css/vendors/vue-multiselect.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('static/css/theme/styles.css') ?>">
  <title>Bookings | Hello Shuttle</title>
  <script type="text/javascript">
        const baseURL = "<?= base_url('/') ?>";
    </script>
</head>

<body>
  <div class="d-flex wrapper" id="wrapper">
	<!-- Sidebar -->
	<div id="sidebar-wrapper" class="sidebar-wrapper">
	  <div class="sidebar-heading">
		<a href="<?= base_url('/') ?>">
			<img src="<?= base_url('static/images/logo/hello-shuttle-gold.png') ?>" class="mt-4 ps-3 ms-4" alt="hello-shuttle-car-rental" >
		</a>
	  </div>
	  <nav class="sidebar py-2 mb-4">
		<ul class="nav flex-column" id="nav_accordion">
		  <li class="nav-item">
			<a class="nav-link d-flex justify-content-between align-items-center" href="<?= base_url('/') ?>/configurations">
			  <span class="d-flex gap-1 align-items-center">
				<span class="material-symbols-outlined"> home </span> Configurations
			  </span>
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link active d-flex justify-content-between align-items-center" href="<?= base_url('/') ?>/bookings">
			  <span class="d-flex gap-1 align-items-center">
				<span class="material-symbols-rounded"> star </span> Bookings
			  </span>
			</a>
		  </li>
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
			<a class="d-flex gap-2 align-items-center" href="#" id="navbarDropdown4" role="button"
			  data-bs-toggle="dropdown" aria-expanded="false">
			  <img src="<?= base_url('static/images/users/avatar.png') ?>" alt="user">
			  <div class="d-flex flex-column d-none d-xl-block">
				<p class="mb-0">System admin</p>
				<span class="small">Account Settings</span>
			  </div>
			</a>
		  </div>
		</div>
		<!-- For pc -->
	  </nav>

	  <div class="container-fluid main-content px-2 px-lg-4 pt-3 pt-lg-5 mt-5" id="main-app" v-cloak>
		<div class="row">
		  <h3 class="mt-4">Dashboard</h3>
		  <span id="date" class="medium"></span>
		</div>
		<!-- Area chart 1 -->
		<div class="row my-2 g-3 g-lg-4">
			<div class="col-12">
				<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
					<b-table-lite
						caption="Bookings"
						caption-top
						responsive
						striped
						:fields="tableConfig.bookings.fields"
						:items="bookings">
						<template #cell(index)="row">
							{{ row.index + 1 }}
						</template>
						<template #cell(actions)="row">
							<b-button-group>
								<b-button size="sm" variant="outline-primary" title="View booking details" @click="viewBookingDetails(row.item)">
									<b-icon icon="eye"></b-icon>
								</b-button>
								<b-button size="sm" variant="outline-success" title="Edit booking details">
									<b-icon icon="pencil"></b-icon>
								</b-button>
							</b-button-group>
						</template>
					</b-table-lite>
				</div>
			</div>
		</div>

		<div class="row my-2 g-3 g-lg-4 top-border footer">
		  <div class="col-lg-6">
			<span class="text-center text-lg-start d-block w-100">Copyright © <?= date('Y') ?> | Designed by
			  <a href="https://dannythedesigner.com/" target="_blank">DannyTheDesigner</a></span>
		  </div>
		  <div class="col-lg-6">
			<ul
			  class="d-flex gap-2 gap-xl-4 p-0 align-items-center flex-wrap justify-content-center justify-content-lg-end">
			  <li><a href="javascript:void(0)">Support</a></li>
			  <li><a href="javascript:void(0)">Help Center</a></li>
			  <li><a href="javascript:void(0)">Privacy</a></li>
			  <li><a href="javascript:void(0)">Terms of Service</a></li>
			</ul>
		  </div>
		</div>

		<b-modal
			static
			:visible="modalConfig.bookingDetails.show"
			@close="() => { modalConfig.bookingDetails.show = false; modalConfig.bookingDetails.data = {} }"
			title="Booking details"
			hide-footer
			no-close-on-esc
			no-close-on-backdrop
			size="lg"
			body-class="p-md-4">
			<section>
				<h5>Customer</h5>
				<ul class="list-group list-group-flush">
					<li
						v-for="detail in modalConfig.bookingDetails.data.customer"
						class="list-group-item">
						<div class="row">
							<div class="col-12 col-md-6">{{ detail.label }}</div>
							<div class="col-12 col-md-6">{{ detail.value }}</div>
						</div>
					</li>
				</ul>
			</section>

			<section class="mt-4">
				<h5>Trip details</h5>

				<ul class="list-group list-group-flush">
					<li>Picking-up</li>
					<li
						v-for="detail in modalConfig.bookingDetails.data.oneWayTrip"
						class="list-group-item">
						<div class="row">
							<div class="col-12 col-md-6">{{ detail.label }}</div>
							<div class="col-12 col-md-6">{{ detail.value }}</div>
						</div>
					</li>
				</ul>

				<ul class="list-group list-group-flush" v-if="modalConfig.bookingDetails.data.tripType === 'round-trip'">
					<li>Return</li>
					<li
						v-for="detail in modalConfig.bookingDetails.data.roundTrip"
						class="list-group-item">
						<div class="row">
							<div class="col-12 col-md-6">{{ detail.label }}</div>
							<div class="col-12 col-md-6">{{ detail.value }}</div>
						</div>
					</li>
				</ul>
			</section>

			<section class="mt-4">
				<h5>Total: &dollar;{{ modalConfig.bookingDetails.data.totalPrice }}</h5>
			</section>
		</b-modal>
	  </div>
	</div>

	<!-- /#page-content-wrapper -->
  </div>

  <script src="<?= base_url('static/js/vendors/jquery.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/popper.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/axios.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap.min.js') ?>" type="text/javascript"></script>
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
  <script src="<?= base_url('static/js/bookings.js') ?>"></script>
</body>

</html>