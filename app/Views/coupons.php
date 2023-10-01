<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<div class="col-12 text-right">
	<b-button class="btn" variant="primary" @click="() => { showModal.createCoupon = true; }">
		<b-icon icon="plus-circle"></b-icon>
		Coupon
	</b-button>
</div>
<div class="col-12">
	<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
		<b-table-lite caption="Coupons" caption-top responsive striped :fields="tableConfig.coupons.fields" :items="couponsList">
			<template #cell(index)="row">
				{{ row.index + 1 }}
			</template>
			<template #cell(actions)="row">
				<b-button variant="outline-primary" @click="openModal('editCoupon', row.item)" class="btn-sm">
					<b-icon icon="pencil-fill"></b-icon>
				</b-button>
			</template>
		</b-table-lite>
	</div>
</div>

<!-- Modals sections -->

<!-- Edit coupon -->
<b-modal title="Edit coupon" no-close-on-esc no-close-on-backdrop @close="clearModalState('editCoupon', true)" :visible="showModal.editCoupon">
	<b-form-group label="Coupon code" :state="validateInputField($v.modals.editCoupon.couponCode)" :invalid-feedback="errorMessages.required">
		<b-form-input readonly v-model="$v.modals.editCoupon.couponCode.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group :state="validateInputField($v.modals.editCoupon.couponIsPercentage)" :invalid-feedback="errorMessages.required">
		<b-form-checkbox value="yes" unchecked-value="no" v-model="$v.modals.editCoupon.couponIsPercentage.$model">
			Percentage
		</b-form-checkbox>
	</b-form-group>

	<b-form-group label="Discount amount" :state="validateInputField($v.modals.editCoupon.couponDiscountAmount)" :invalid-feedback="errorMessages.required">
		<b-input-group>
			<b-form-input autocomplete="off" v-model="$v.modals.editCoupon.couponDiscountAmount.$model">
			</b-form-input>
			<b-input-group-append>
				<b-button>
					<b-icon :icon="modals.editCoupon.couponIsPercentage === 'yes' ? 'percent' : 'currency-dollar'"></b-icon>
				</b-button>
			</b-input-group-append>
		</b-input-group>
	</b-form-group>

	<b-form-group :state="validateInputField($v.modals.editCoupon.couponStartDate)" :invalid-feedback="errorMessages.required" label="Start date">
		<b-form-datepicker :min="new Date()" v-model="$v.modals.editCoupon.couponStartDate.$model">
		</b-form-datepicker>
	</b-form-group>

	<b-form-group :state="validateInputField($v.modals.editCoupon.couponEndDate)" :invalid-feedback="errorMessages.required" label="End date">
		<b-form-datepicker :min="new Date()" v-model="$v.modals.editCoupon.couponEndDate.$model">
		</b-form-datepicker>
	</b-form-group>

	<template #modal-footer>
		<b-button class="px-4" variant="primary" @click="editCoupon">Save</b-button>
	</template>
</b-modal>

<!-- Create coupon -->
<b-modal title="Create coupon" no-close-on-esc no-close-on-backdrop @close="clearModalState('createCoupon', true)" :visible="showModal.createCoupon">
	<b-form-group label="Coupon code" :state="validateInputField($v.modals.createCoupon.couponCode)" :invalid-feedback="errorMessages.required">
		<b-input-group>
			<b-form-input v-model="$v.modals.createCoupon.couponCode.$model">
			</b-form-input>
			<b-input-group-append>
				<b-button @click="generateRandomString()">
					Generate
				</b-button>
			</b-input-group-append>
		</b-input-group>
	</b-form-group>

	<b-form-group :state="validateInputField($v.modals.createCoupon.couponIsPercentage)" :invalid-feedback="errorMessages.required">
		<b-form-checkbox value="yes" unchecked-value="no" v-model="$v.modals.createCoupon.couponIsPercentage.$model">
			Percentage
		</b-form-checkbox>
	</b-form-group>

	<b-form-group label="Discount amount" :state="validateInputField($v.modals.createCoupon.couponDiscountAmount)" :invalid-feedback="errorMessages.required">
		<b-input-group>
			<b-form-input autocomplete="off" v-model="$v.modals.createCoupon.couponDiscountAmount.$model">
			</b-form-input>
			<b-input-group-append>
				<b-button>
					<b-icon :icon="modals.createCoupon.couponIsPercentage === 'yes' ? 'percent' : 'currency-dollar'"></b-icon>
				</b-button>
			</b-input-group-append>
		</b-input-group>
	</b-form-group>

	<b-form-group :state="validateInputField($v.modals.createCoupon.couponStartDate)" :invalid-feedback="errorMessages.required" label="Start date">
		<b-form-datepicker :min="new Date()" v-model="$v.modals.createCoupon.couponStartDate.$model">
		</b-form-datepicker>
	</b-form-group>

	<b-form-group :state="validateInputField($v.modals.createCoupon.couponEndDate)" :invalid-feedback="errorMessages.required" label="End date">
		<b-form-datepicker :min="new Date()" v-model="$v.modals.createCoupon.couponEndDate.$model">
		</b-form-datepicker>
	</b-form-group>

	<template #modal-footer>
		<b-button class="px-4" variant="primary" @click="createCoupon">Create</b-button>
	</template>
</b-modal>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script src="<?= base_url('static/js/coupons.js?v=' . now()) ?>"></script>
<?= $this->endSection() ?>