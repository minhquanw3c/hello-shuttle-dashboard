<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>

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
</b-table-lite>

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

		<ul class="list-group mt-4" v-if="modalConfig.bookingDetails.data.tripType === 'round-trip'">
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

<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
    <script type="text/javascript">
        const userId = "<?= $userId ?>";
    </script>
    <script src="<?= base_url('static/js/customer/bookings.js') ?>"></script>
<?= $this->endSection() ?>