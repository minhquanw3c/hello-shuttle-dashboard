<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
	<div class="col-12 text-right px-0 mb-3">
		<b-button variant="outline-primary" @click="fetchBookingsList">
			<b-icon icon="arrow-repeat"></b-icon>
			Bookings
		</b-button>
	</div>

	<b-table-lite responsive striped :fields="tableConfig.bookings.fields" :items="bookings">
		<template #cell(index)="row">
			{{ row.index + 1 }}
		</template>

		<template #cell(bookingRefNo)="data">
			{{ data.value }}
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
			<b-button-group verticle>
				<b-button
					v-b-tooltip.hover
					variant="primary"
					title="View booking details"
					@click="viewBookingDetails(row.item)">
					<b-icon icon="eye"></b-icon>
				</b-button>

				<template v-if="row.item.bookingStatus === 'Processing'">
					<b-button
						class="mx-3"
						v-b-tooltip.hover
						variant="primary"
						title="Edit customer details"
						@click="showEditBookingModal(row.item)">
						<b-icon icon="person-lines-fill"></b-icon>
					</b-button>

					<b-button
						class="mr-3"
						v-b-tooltip.hover
						@click.prevent="cancelBooking(row.item)"
						variant="danger"
						title="Cancel this booking">
						<b-icon icon="x-circle"></b-icon>
					</b-button>

					<b-button
						class="mr-3"
						v-b-tooltip.hover
						@click.prevent="completeBooking(row.item)"
						variant="primary"
						title="Mark this booking as Done">
						<b-icon icon="check2-square"></b-icon>
					</b-button>

					<b-button
						@click.prevent="onShowSchedulingCompleteBookingModal(row.item)"
						variant="primary"
						title="Schedule booking complete date">
						<b-icon icon="clock"></b-icon>
					</b-button>
				</template>
			</b-button-group>
		</template>
	</b-table-lite>
</div>

<!-- View booking details -->
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
		<ul class="list-group">
			<li class="list-group-item list-group-item-info">Vehicle</li>
			<li class="list-group-item list-group-item-action">
				<div class="row">
					<div class="col-12 mb-3 text-center">
						<img
							:src="modalConfig.bookingDetails.data.oneWayTripVehicle && modalConfig.bookingDetails.data.oneWayTripVehicle.image"
							alt=""
							class="img-fluid"
						/>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-6">{{ modalConfig.bookingDetails.data.oneWayTripVehicle && modalConfig.bookingDetails.data.oneWayTripVehicle.label }}</div>
					<div class="col-12 col-md-6">{{ modalConfig.bookingDetails.data.oneWayTripVehicle && modalConfig.bookingDetails.data.oneWayTripVehicle.value }}</div>
				</div>
			</li>
		</ul>
	</section>

	<section class="mt-4">
		<ul class="list-group">
			<li class="list-group-item list-group-item-info">Customer</li>
			<li v-for="detail in modalConfig.bookingDetails.data.customer" class="list-group-item list-group-item-action">
				<div class="row">
					<div class="col-12 col-md-6">{{ detail.label }}</div>
					<div class="col-12 col-md-6">{{ detail.value }}</div>
				</div>
			</li>
		</ul>
	</section>

	<section class="mt-4">
		<ul class="list-group">
			<li class="list-group-item list-group-item-info">Picking-up</li>
			<li v-for="detail in modalConfig.bookingDetails.data.oneWayTrip" class="list-group-item list-group-item-action">
				<div class="row">
					<div class="col-12 col-md-6">{{ detail.label }}</div>
					<div class="col-12 col-md-6">
						<template v-if="detail.value instanceof Array">
							<p v-for="item in detail.value" class="m-0">{{ item.configName }}</p>
						</template>

						<template v-if="!(detail.value instanceof Array)">
							{{ detail.value }}
						</template>
					</div>
				</div>
			</li>
		</ul>

		<ul
			class="list-group mt-4"
			v-if="modalConfig.bookingDetails.data.tripType === 'round-trip'"
		>
			<li class="list-group-item list-group-item-info">Return</li>
			<li v-for="detail in modalConfig.bookingDetails.data.roundTrip" class="list-group-item list-group-item-action">
				<div class="row">
					<div class="col-12 col-md-6">{{ detail.label }}</div>
					<div class="col-12 col-md-6">
						<template v-if="detail.value instanceof Array">
							<p v-for="item in detail.value" class="m-0">{{ item.configName }}</p>
						</template>

						<template v-if="!(detail.value instanceof Array)">
							{{ detail.value }}
						</template>
					</div>
				</div>
			</li>
		</ul>

		<ul class="list-group mt-4">
			<li class="list-group-item list-group-item-info">Additional notes</li>
			<li v-for="note in modalConfig.bookingDetails.data.additionalNotes" class="list-group-item list-group-item-action">
				<div class="row">
					<div class="col-12 col-md-6">{{ note.label }}</div>
					<div class="col-12 col-md-6">{{ note.value }}</div>
				</div>
			</li>
		</ul>

	</section>

	<section class="mt-4">
		<div class="list-group-item list-group-item-info text-right">Total: &dollar;{{ modalConfig.bookingDetails.data.totalPrice }}</div>
	</section>
</b-modal>

<!-- Edit customer details -->
<b-modal
	static
	:visible="modalConfig.editBookingDetails.show"
	@close="() => { modalConfig.editBookingDetails.show = false; modalConfig.editBookingDetails.form = {} }"
	title="Edit customer details"
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
			class="px-4"
			@click="editBookingDetails"
			variant="primary">
			Save
		</b-button>
	</template>
</b-modal>

<!-- Schedule booking complete date -->
<b-modal
	static
	:visible="modalConfig.scheduleCompleteBooking.show"
	@close="onCloseSchedulingCompleteBookingModal"
	title="Schedule booking complete date"
	no-close-on-esc
	no-close-on-backdrop
	size="xl"
	body-class="p-md-4">
	<section>
		<div class="row">
			<div class="col-6">
				<b-form-group
					label="Trip type"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.tripType)"
					:invalid-feedback="errorMessages.required">
					<b-form-input
						disabled
						type="text"
						v-model="$v.modalConfig.scheduleCompleteBooking.form.tripType.$model">
					</b-form-input>
				</b-form-group>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-md-6">
				<b-form-group
					label="Pick-up date"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.pickupDate)"
					:invalid-feedback="errorMessages.required">
					<b-form-datepicker
						disabled
						v-model="$v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.pickupDate.$model">
					</b-form-datepicker>
				</b-form-group>
			</div>

			<div class="col-12 col-md-6">
				<b-form-group
					label="Pick-up time"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.pickupTime)"
					:invalid-feedback="errorMessages.required">
					<b-form-timepicker
						disabled
						v-model="$v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.pickupTime.$model">
					</b-form-timepicker>
				</b-form-group>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-md-6">
				<b-form-group
					label="Schedule date"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.scheduleCompleteDate)"
					:invalid-feedback="errorMessages.required">
					<b-form-datepicker
						:min="$v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.pickupDate.$model"
						v-model="$v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.scheduleCompleteDate.$model">
					</b-form-datepicker>
				</b-form-group>
			</div>

			<div class="col-12 col-md-6">
				<b-form-group
					label="Schedule time"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.scheduleCompleteTime)"
					:invalid-feedback="errorMessages.required">
					<b-form-timepicker
						v-model="$v.modalConfig.scheduleCompleteBooking.form.oneWayTrip.scheduleCompleteTime.$model">
					</b-form-timepicker>
				</b-form-group>
			</div>
		</div>
	</section>

	<section v-if="modalConfig.scheduleCompleteBooking.form.tripType === 'round-trip'">
		<div class="row">
			<div class="col-12 col-md-6">
				<b-form-group
					label="Pick-up date"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.roundTrip.pickupDate)"
					:invalid-feedback="errorMessages.required">
					<b-form-datepicker
						disabled
						v-model="$v.modalConfig.scheduleCompleteBooking.form.roundTrip.pickupDate.$model">
					</b-form-datepicker>
				</b-form-group>
			</div>

			<div class="col-12 col-md-6">
				<b-form-group
					label="Pick-up time"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.roundTrip.pickupTime)"
					:invalid-feedback="errorMessages.required">
					<b-form-timepicker
						disabled
						v-model="$v.modalConfig.scheduleCompleteBooking.form.roundTrip.pickupTime.$model">
					</b-form-timepicker>
				</b-form-group>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-md-6">
				<b-form-group
					label="Schedule date"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.roundTrip.scheduleCompleteDate)"
					:invalid-feedback="errorMessages.required">
					<b-form-datepicker
						:min="$v.modalConfig.scheduleCompleteBooking.form.roundTrip.pickupDate.$model"
						v-model="$v.modalConfig.scheduleCompleteBooking.form.roundTrip.scheduleCompleteDate.$model">
					</b-form-datepicker>
				</b-form-group>
			</div>

			<div class="col-12 col-md-6">
				<b-form-group
					label="Schedule time"
					:state="validateInputField($v.modalConfig.scheduleCompleteBooking.form.roundTrip.scheduleCompleteTime)"
					:invalid-feedback="errorMessages.required">
					<b-form-timepicker
						v-model="$v.modalConfig.scheduleCompleteBooking.form.roundTrip.scheduleCompleteTime.$model">
					</b-form-timepicker>
				</b-form-group>
			</div>
		</div>
	</section>

	<template #modal-footer>
		<b-button
			class="px-4"
			@click="scheduleBookingCompleteDate"
			variant="primary">
			Save
		</b-button>
	</template>
</b-modal>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script type="text/javascript">
	const bookingFormUrl = "<?= $bookingFormUrl ?>";
</script>

<script src="<?= base_url('static/js/bookings.js') ?>"></script>
<?= $this->endSection() ?>