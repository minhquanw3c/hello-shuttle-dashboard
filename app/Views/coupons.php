<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="<?= base_url('static/images/favicon.ico') ?>" type="image/x-icon">
  <link href="<?= base_url('static/css/vendors/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('static/css/theme/bootstrap.min.css') ?>" rel="stylesheet">
  <link rel="stylesheet"
	href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
  <link rel="stylesheet"
	href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/bootstrap-vue.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('static/css/vendors/bootstrap-vue-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('static/css/vendors/vue-multiselect.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('static/css/theme/styles.css') ?>">
  <title>Coupons | Hello Shuttle</title>
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
			<a class="nav-link d-flex justify-content-between align-items-center" href="<?= base_url('/') ?>/bookings">
			  <span class="d-flex gap-1 align-items-center">
				<span class="material-symbols-rounded"> star </span> Bookings
			  </span>
			</a>
		  </li>
          <li class="nav-item">
			<a class="active nav-link d-flex justify-content-between align-items-center" href="<?= base_url('/') ?>/coupons">
			  <span class="d-flex gap-1 align-items-center">
				<span class="material-symbols-rounded"> star </span> Coupons
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
            <div class="col-12 text-right">
                <b-button
                    class="btn"
                    variant="primary"
                    @click="() => { showModal.createCoupon = true; }">
                    <b-icon icon="plus-circle"></b-icon>
                    Coupon
                </b-button>
            </div>
			<div class="col-12">
				<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
                    <b-table-lite
						caption="Coupons"
						caption-top
						responsive
						striped
						:fields="tableConfig.coupons.fields"
						:items="couponsList">
						<template #cell(index)="row">
							{{ row.index + 1 }}
						</template>
						<template #cell(actions)="row">
							<b-button
                                variant="outline-primary"
                                @click="openModal('editCoupon', row.item)"
                                class="btn-sm">
								<b-icon icon="pencil-fill"></b-icon>
							</b-button>
						</template>
					</b-table-lite>
				</div>
			</div>

            <!-- Modals sections -->

			<!-- Edit coupon -->
			<b-modal
                title="Edit coupon"
                no-close-on-esc
                no-close-on-backdrop
				@close="clearModalState('editCoupon', true)"
				:visible="showModal.editCoupon">
				<b-form-group
					label="Coupon code"
                    :state="validateInputField($v.modals.editCoupon.couponCode)"
					:invalid-feedback="errorMessages.required">
					<b-form-input
                        readonly
                        v-model="$v.modals.editCoupon.couponCode.$model">
                    </b-form-input>
				</b-form-group>

                <b-form-group
                    :state="validateInputField($v.modals.editCoupon.couponIsPercentage)"
					:invalid-feedback="errorMessages.required">
                    <b-form-checkbox
                        value="yes"
                        unchecked-value="no"
                        v-model="$v.modals.editCoupon.couponIsPercentage.$model">
                        Percentage
                    </b-form-checkbox>
				</b-form-group>

                <b-form-group
					label="Discount amount"
                    :state="validateInputField($v.modals.editCoupon.couponDiscountAmount)"
					:invalid-feedback="errorMessages.required">
                    <b-input-group>
                        <b-form-input
                            autocomplete="off"
                            v-model="$v.modals.editCoupon.couponDiscountAmount.$model">
                        </b-form-input>
						<b-input-group-append>
							<b-button>
                                <b-icon :icon="modals.editCoupon.couponIsPercentage === 'yes' ? 'percent' : 'currency-dollar'"></b-icon>
                            </b-button>
						</b-input-group-append>
					</b-input-group>
				</b-form-group>

				<b-form-group
					:state="validateInputField($v.modals.editCoupon.couponStartDate)"
					:invalid-feedback="errorMessages.required"
					label="Start date">
					<b-form-datepicker
                        :min="new Date()"
                        v-model="$v.modals.editCoupon.couponStartDate.$model">
                    </b-form-datepicker>
				</b-form-group>

                <b-form-group
					:state="validateInputField($v.modals.editCoupon.couponEndDate)"
					:invalid-feedback="errorMessages.required"
					label="End date">
					<b-form-datepicker
                        :min="new Date()"
                        v-model="$v.modals.editCoupon.couponEndDate.$model">
                    </b-form-datepicker>
				</b-form-group>

				<template #modal-footer>
					<b-button class="px-4" variant="primary" @click="editCoupon">Save</b-button>
				</template>
			</b-modal>

            <!-- Create coupon -->
            <b-modal
                title="Create coupon"
                no-close-on-esc
                no-close-on-backdrop
				@close="clearModalState('createCoupon', true)"
				:visible="showModal.createCoupon">
				<b-form-group
					label="Coupon code"
                    :state="validateInputField($v.modals.createCoupon.couponCode)"
					:invalid-feedback="errorMessages.required">
					<b-input-group>
						<b-form-input
                            readonly
							v-model="$v.modals.createCoupon.couponCode.$model">
						</b-form-input>
						<b-input-group-append>
							<b-button @click="generateRandomString()">
                                Generate
                            </b-button>
						</b-input-group-append>
					</b-input-group>
				</b-form-group>

                <b-form-group
                    :state="validateInputField($v.modals.createCoupon.couponIsPercentage)"
					:invalid-feedback="errorMessages.required">
                    <b-form-checkbox
                        value="yes"
                        unchecked-value="no"
                        v-model="$v.modals.createCoupon.couponIsPercentage.$model">
                        Percentage
                    </b-form-checkbox>
				</b-form-group>

                <b-form-group
					label="Discount amount"
                    :state="validateInputField($v.modals.createCoupon.couponDiscountAmount)"
					:invalid-feedback="errorMessages.required">
                    <b-input-group>
                        <b-form-input
                            autocomplete="off"
                            v-model="$v.modals.createCoupon.couponDiscountAmount.$model">
                        </b-form-input>
						<b-input-group-append>
							<b-button>
                                <b-icon :icon="modals.createCoupon.couponIsPercentage === 'yes' ? 'percent' : 'currency-dollar'"></b-icon>
                            </b-button>
						</b-input-group-append>
					</b-input-group>
				</b-form-group>

				<b-form-group
					:state="validateInputField($v.modals.createCoupon.couponStartDate)"
					:invalid-feedback="errorMessages.required"
					label="Start date">
					<b-form-datepicker
                        :min="new Date()"
                        v-model="$v.modals.createCoupon.couponStartDate.$model">
                    </b-form-datepicker>
				</b-form-group>

                <b-form-group
					:state="validateInputField($v.modals.createCoupon.couponEndDate)"
					:invalid-feedback="errorMessages.required"
					label="End date">
					<b-form-datepicker
                        :min="new Date()"
                        v-model="$v.modals.createCoupon.couponEndDate.$model">
                    </b-form-datepicker>
				</b-form-group>

				<template #modal-footer>
					<b-button class="px-4" variant="primary" @click="createCoupon">Create</b-button>
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
  <script src="<?= base_url('static/js/coupons.js') ?>"></script>
</body>

</html>