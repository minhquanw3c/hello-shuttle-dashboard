<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<div class="col-12 text-right px-0">
	<!-- <b-button variant="danger" @click="clearTestData">
		<b-icon icon="trash"></b-icon>
		Bookings
	</b-button> -->

	<b-button variant="outline-primary" @click="fetchBookingsList">
		<b-icon icon="arrow-repeat"></b-icon>
		Bookings
	</b-button>
</div>

<b-table-lite caption="Bookings" caption-top responsive striped :fields="tableConfig.bookings.fields" :items="bookings">
	<template #cell(index)="row">
		{{ row.index + 1 }}
	</template>

	<template #cell(bookingPaymentStatus)="data">
		<span class="badge badge-info">{{ data.value }}</span>
	</template>

	<template #cell(bookingStatus)="data">
		<span class="badge badge-primary">{{ data.value }}</span>
	</template>

	<template #cell(bookingCreatedAt)="data">
		{{ moment(data.value, 'YYYY-MM-DD HH:mm:ss').format('LLLL') }}
	</template>

	<template #cell(actions)="row">
		<b-button-group v-if="row.item.bookingStatus === 'Processing'">
			<b-button size="sm" variant="outline-primary" title="View booking details" @click="viewBookingDetails(row.item)">
				<b-icon icon="eye"></b-icon>
			</b-button>
			<b-button size="sm" variant="outline-success" title="Edit booking details" @click="showEditBookingModal(row.item)">
				<b-icon icon="pencil"></b-icon>
			</b-button>
		</b-button-group>
	</template>
</b-table-lite>

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
			<li v-for="detail in modalConfig.bookingDetails.data.customer" class="list-group-item">
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
			<li v-for="detail in modalConfig.bookingDetails.data.oneWayTrip" class="list-group-item">
				<div class="row">
					<div class="col-12 col-md-6">{{ detail.label }}</div>
					<div class="col-12 col-md-6">{{ detail.value }}</div>
				</div>
			</li>
		</ul>

		<ul class="list-group list-group-flush" v-if="modalConfig.bookingDetails.data.tripType === 'round-trip'">
			<li>Return</li>
			<li v-for="detail in modalConfig.bookingDetails.data.roundTrip" class="list-group-item">
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

<!-- Edit booking -->
<b-modal
	static
	:visible="modalConfig.editBookingDetails.show"
	@close="() => { modalConfig.editBookingDetails.show = false; modalConfig.editBookingDetails.form = {} }"
	title="Edit booking details"
	no-close-on-esc
	no-close-on-backdrop
	size="md"
	body-class="p-md-4">
	<section>
		<b-form-group
			label="Customer first name"
			:state="validateInputField($v.modalConfig.editBookingDetails.form.customerFirstName)"
			:invalid-feedback="errorMessages.required">
			<b-form-input
				type="text"
				v-model="$v.modalConfig.editBookingDetails.form.customerFirstName.$model">
			</b-form-input>
		</b-form-group>

		<b-form-group
			label="Customer last name"
			:state="validateInputField($v.modalConfig.editBookingDetails.form.customerLastName)"
			:invalid-feedback="errorMessages.required">
			<b-form-input
				type="text"
				v-model="$v.modalConfig.editBookingDetails.form.customerLastName.$model">
			</b-form-input>
		</b-form-group>

		<b-form-group
			label="Customer Phone"
			:state="validateInputField($v.modalConfig.editBookingDetails.form.customerPhone)"
			:invalid-feedback="errorMessages.required">
			<b-form-input
				type="text"
				v-model="$v.modalConfig.editBookingDetails.form.customerPhone.$model">
			</b-form-input>
		</b-form-group>
	</section>

	<template #modal-footer>
		<b-button
			class="px-4 mr-2"
			variant="danger">
			Cancel booking
		</b-button>
		<b-button
			class="px-4"
			@click="editBookingDetails"
			variant="primary">
			Save
		</b-button>
	</template>
</b-modal>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script src="<?= base_url('static/js/bookings.js') ?>"></script>
<?= $this->endSection() ?>