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
  <title>Configurations | Hello Shuttle</title>
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
			<a class="active nav-link d-flex justify-content-between align-items-center" href="<?= base_url('/') ?>/configurations">
			  <span class="d-flex gap-1 align-items-center">
				<span class="material-symbols-outlined"> home </span> Configurations
			  </span>
			</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link d-flex justify-content-between align-items-center" href="<?= base_url('/') ?>/bookings">
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
			<a class="d-flex gap-2 align-items-center" href="javascript:void(0)" id="navbarDropdown4" role="button"
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
						caption="Cars"
						caption-top
						responsive
						striped
						:fields="tableConfig.cars.fields"
						:items="carsList">
						<template #cell(index)="row">
							{{ row.index + 1 }}
						</template>
						<template #cell(actions)="row">
							<b-button variant="outline-primary" @click="openCarModal(row.item)" v-if="row.item.carEditable === '1'">
								<b-icon icon="pencil-fill"></b-icon>
							</b-button>
						</template>
					</b-table-lite>
				</div>
			</div>

            <!-- Modals sections -->
			<!-- Configurations -->
			<b-modal
				@close="clearConfigModalState"
				:visible="showEditConfigModal">
				<b-form-group
					label="Config name">
					<b-form-input
						disabled
						v-model="modals.editConfig.configName">
					</b-form-input>
				</b-form-group>

				<b-form-group
					:state="validateInputField($v.modals.editConfig.configValue)"
					:invalid-feedback="errorMessages.required"
					label="Config value">
					<b-input-group>
						<b-form-input
							v-model="$v.modals.editConfig.configValue.$model">
						</b-form-input>
						<b-input-group-append>
							<b-icon icon="currency-dollar"></b-icon>
						</b-input-group-append>
					</b-input-group>
				</b-form-group>

				<b-form-group>
					<b-form-checkbox
						value="1"
						unchecked-value="0"
						v-model="modals.editConfig.configActive">
						Active?
					</b-form-checkbox>
				</b-form-group>

				<template #modal-footer>
					<b-button class="px-4" variant="primary" @click="editConfig">Save</b-button>
				</template>
			</b-modal>

			<!-- Cars -->
			<b-modal
				@close="clearCarModalState"
				:visible="showEditCarModal">
				<b-form-group
					label="Car name">
					<b-form-input
						disabled
						v-model="modals.editCar.carName">
					</b-form-input>
				</b-form-group>

				<b-form-group
					label="Car seats">
					<b-form-input
						disabled
						v-model="modals.editCar.carSeatsCapacity">
					</b-form-input>
				</b-form-group>

				<b-form-group
					:state="validateInputField($v.modals.editCar.carStartPrice)"
					:invalid-feedback="errorMessages.required"
					label="Start price">
					<b-form-input
						v-model="$v.modals.editCar.carStartPrice.$model">
					</b-form-input>
				</b-form-group>

				<b-form-group
					:state="validateInputField($v.modals.editCar.carQuantity)"
					:invalid-feedback="errorMessages.required"
					label="Car quantity">
					<b-form-input
						v-model="$v.modals.editCar.carQuantity.$model">
					</b-form-input>
				</b-form-group>

				<b-form-group>
					<b-form-checkbox
						value="1"
						unchecked-value="0"
						v-model="modals.editCar.carActive">
						Active?
					</b-form-checkbox>
				</b-form-group>

				<template #modal-footer>
					<b-button class="px-4" variant="primary" @click="editCar">Save</b-button>
				</template>
			</b-modal>
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
	  </div>
	</div>

	<!-- /#page-content-wrapper -->
  </div>

  <script src="<?= base_url('static/js/vendors/jquery.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/popper.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/axios.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/lodash.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/vue.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/vue-multiselect.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/portal-vue.umd.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap-vue.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/bootstrap-vue-icons.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/validators.min.js') ?>" type="text/javascript"></script>
	<script src="<?= base_url('static/js/vendors/vuelidate.min.js') ?>" type="text/javascript"></script>
  <script src="<?= base_url('static/js/theme/main.js') ?>"></script>
  <script src="<?= base_url('static/js/configurations.js') ?>"></script>
</body>

</html>