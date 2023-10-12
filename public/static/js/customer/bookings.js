
var app = new Vue({
    el: '#main-app',
    data: function () {
        return {
            tableConfig: {
                bookings: {
                    fields: [
                        {
                            key: 'index',
                            label: '#'
                        },
                        {
                            key: 'bookingRefNo',
                            label: 'Reference number',
                        },
                        {
                            key: 'bookingPaymentStatus',
                            label: 'Payment status',
                        },
                        {
                            key: 'bookingStatus',
                            label: 'Booking status',
                        },
                        {
                            key: 'bookingCreatedAt',
                            label: 'Created at'
                        },
                        {
                            key: 'actions',
                            label: 'Actions'
                        },
                    ],
                },
            },
            bookings: [],
            modalConfig: {
                bookingDetails: {
                    show: false,
                    data: {},
                },
            },
        }
    },
    mounted: async function () {
        console.log('app mounted');
        this.fetchBookingsList(showToast = false);
    },
    methods: {
        fetchBookingsList: function (showToast = true) {
            const self = this;

            const payload = {
                userId: userId
            };

            axios
                .post(baseURL + '/api/bookings/customer/list', payload)
                .then(res => {
                    console.log(res);
                    self.bookings = _.sortBy(res.data, booking => new Date(booking.bookingCreatedAt)).reverse();

                    if (showToast) {
                        var toastType = res.status === 200 ? 'success' : 'error';
                        self.showToastNotification(toastType);
                    }
                })
                .catch(error => {
                    console.log(error);
                    if (showToast) {
                        var toastType = 'error';
                        self.showToastNotification(toastType);
                    }
                });
        },
        showToastNotification: function (type = 'error') {
            const self = this;

            var titleType = type === 'error' ? 'Error' : 'Success';
            var variantType = type === 'error' ? 'danger' : 'success';
            var messageType = type === 'error' ? 'There are errors occured' : 'Data is saved';

            self.$bvToast.toast(
                messageType,
                {
                    title: titleType,
                    autoHideDelay: 5000,
                    variant: variantType,
                    solid: true,
                    noCloseButton: true,
                }
            );
        },
        viewBookingDetails: function (bookingData) {
            const self = this;

            const bookingDetails = JSON.parse(bookingData.bookingData);

            self.modalConfig.bookingDetails.data.tripType = bookingDetails.reservation.tripType;
            
            const customer = [
                {
                    label: 'Full name',
                    value: bookingDetails.review.customer.firstName + " " + bookingDetails.review.customer.lastName
                },
                {
                    label: 'Email',
                    value: bookingDetails.review.customer.contact.email
                },
                {
                    label: 'Phone',
                    value: bookingDetails.review.customer.contact.mobileNumber
                },
            ];

            let additionalNotes = [
                {
                    label: 'Airline',
                    value: bookingDetails.review.airline.brand ? bookingDetails.review.airline.brand.text : 'Not provided',
                },
                {
                    label: 'Flight number',
                    value: bookingDetails.review.airline.flightNumber ? bookingDetails.review.airline.flightNumber : 'Not provided',
                },
                {
                    label: 'Notes',
                    value: bookingDetails.review.additionalNotes ? bookingDetails.review.additionalNotes : 'Not provided',
                },
            ];

            let oneWayTrip = [
                {
                    label: 'Origin',
                    value: bookingDetails.reservation.oneWayTrip.origin.description,
                },
                {
                    label: 'Rest stop',
                    value: bookingDetails.reservation.oneWayTrip.restStop.description,
                },
                {
                    label: 'Destination',
                    value: bookingDetails.reservation.oneWayTrip.destination.description,
                },
                {
                    label: 'Pickup time',
                    value: moment(
                            bookingDetails.reservation.oneWayTrip.pickup.date.concat(" ", bookingDetails.reservation.oneWayTrip.pickup.time),
                            "YYYY-MM-DD hh:mm:ss"
                        ).format('LLLL'),
                },
                {
                    label: 'Extras',
                    value: bookingDetails.chooseOptions.oneWayTrip.extras,
                },
                {
                    label: 'Protection',
                    value: bookingDetails.chooseOptions.oneWayTrip.protection,
                },
                {
                    label: 'Miles',
                    value: bookingDetails.review.routes.oneWayTrip.miles,
                },
                {
                    label: 'Total',
                    value: '$' + bookingDetails.review.prices.oneWayTrip,
                },
            ];

            if (self.modalConfig.bookingDetails.data.tripType === 'round-trip') {
                let roundTrip = [
                    {
                        label: 'Origin',
                        value: bookingDetails.reservation.roundTrip.origin.description,
                    },
                    {
                        label: 'Rest stop',
                        value: bookingDetails.reservation.roundTrip.restStop.description,
                    },
                    {
                        label: 'Destination',
                        value: bookingDetails.reservation.roundTrip.destination.description,
                    },
                    {
                        label: 'Pickup time',
                        value: moment(
                                bookingDetails.reservation.roundTrip.pickup.date.concat(" ", bookingDetails.reservation.roundTrip.pickup.time),
                                "YYYY-MM-DD hh:mm:ss"
                            ).format('LLLL'),
                    },
                    {
                        label: 'Extras',
                        value: bookingDetails.chooseOptions.roundTrip.extras,
                    },
                    {
                        label: 'Protection',
                        value: bookingDetails.chooseOptions.roundTrip.protection,
                    },
                    {
                        label: 'Miles',
                        value: bookingDetails.review.routes.roundTrip.miles,
                    },
                    {
                        label: 'Total',
                        value: '$' + bookingDetails.review.prices.roundTrip,
                    },
                ];
                self.modalConfig.bookingDetails.data.roundTrip = roundTrip;
            }

            self.modalConfig.bookingDetails.data.customer = customer;
            self.modalConfig.bookingDetails.data.additionalNotes = additionalNotes;
            self.modalConfig.bookingDetails.data.oneWayTrip = oneWayTrip;
            self.modalConfig.bookingDetails.data.totalPrice = bookingDetails.review.prices.total;
            self.modalConfig.bookingDetails.show = true;
        },
        cancelBooking: function (bookingData) {
            const self = this;

            const payload = {
                booking_id: bookingData.bookingId,
                cancel_session_id: bookingData.bookingCancelSessionId,
            };

            axios
                .post(bookingFormUrl + 'api/booking/cancel', payload)
                .then(res => {
                    
                    if (res.data.result) {
                        self.fetchBookingsList(showToast = false);
                        self.modalConfig.editBookingDetails.show = false;
                    }

                    var toastType = res.data.result === true ? 'success' : 'error';
                    self.showToastNotification(toastType, res.data.message);
                })
                .catch(error => {
                    var toastType = 'error';
                    self.showToastNotification(toastType);
                });
        },
    },
    computed: {

    },
    validations: {
        
    },
});