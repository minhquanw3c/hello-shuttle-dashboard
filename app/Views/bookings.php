<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<b-table-lite caption="Bookings" caption-top responsive striped :fields="tableConfig.bookings.fields" :items="bookings">
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

<b-modal static :visible="modalConfig.bookingDetails.show" @close="() => { modalConfig.bookingDetails.show = false; modalConfig.bookingDetails.data = {} }" title="Booking details" hide-footer no-close-on-esc no-close-on-backdrop size="lg" body-class="p-md-4">
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
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script src="<?= base_url('static/js/bookings.js') ?>"></script>
<?= $this->endSection() ?>