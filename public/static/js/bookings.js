Vue.use(window.vuelidate.default);
Vue.component('multiselect', window.VueMultiselect.default);
const { required, requiredIf, minLength, email, minValue } = window.validators;
// import placePredictions from "../mixins/placePrediction";

var app = new Vue({
    el: '#main-app',
    data: function () {
        return {
            options: {
                YESNO: [
                    {
                        text: 'Yes',
                        value: true,
                    },
                    {
                        text: 'No',
                        value: false,
                    },
                ],
            },
            form: {
            },
            tableConfig: {
                bookings: {
                    fields: [
                        {
                            key: 'index',
                            label: '#'
                        },
                        {
                            key: 'customerFullName',
                            label: 'Customer'
                        },
                        {
                            key: 'customerPhone',
                            label: 'Phone'
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
            errorMessages: {
                required: 'This field is required',
            },
            modalConfig: {
                bookingDetails: {
                    show: false,
                    data: {},
                },
                editBookingDetails: {
                    show: false,
                    data: {},
                    form: {},
                    cancelBookingLink: null,
                }
            },
        }
    },
    mounted: async function () {
        console.log('app mounted');
        this.fetchBookingsList(showToast = false);
    },
    methods: {
        generateCancelBookingLink: function (bookingData) {
            const self = this;
            const bookingFormHostURL = 'https://helloshuttle.com/';

            return bookingFormHostURL.concat('cancel?booking_id=', bookingData.bookingId, '&cancel_session_id=', bookingData.bookingCancelSessionId);
        },
        validateInputField: function (input) {
            const self = this;

            return input.$dirty ? !input.$invalid : null;
        },
        fetchBookingsList: function (showToast = true) {
            const self = this;
            const payload = {};

            axios
                .get(baseURL + '/api/bookings/list', payload)
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
        editBookingDetails: function () {
            const self = this;

            self.$v.modalConfig.editBookingDetails.form.$touch();
            var formValidity = !self.$v.modalConfig.editBookingDetails.form.$invalid;

            if (formValidity === false) {
                self.showToastNotification(toastType = 'error');
                return;
            }

            let payloadData = {...self.modalConfig.editBookingDetails.form};

            const payload = {
                form: {
                    customerFirstName: payloadData.customerFirstName,
                    customerLastName: payloadData.customerLastName,
                    customerPhone: payloadData.customerPhone,
                    bookingId: payloadData.bookingId,
                }
            };

            axios
                .post(baseURL + '/api/bookings/edit', payload)
                .then(res => {
                    
                    if (res.data.result) {
                        self.fetchBookingsList(showToast = false);
                        self.modalConfig.editBookingDetails.show = false;
                    }

                    var toastType = res.data.result ? 'success' : 'error';
                    self.showToastNotification(toastType, res.data.message);
                })
                .catch(error => {
                    var toastType = 'error';
                    self.showToastNotification(toastType);
                });
        },
        showEditBookingModal: function (bookingData) {
            const self = this;

            self.modalConfig.editBookingDetails.show = true;
            self.modalConfig.editBookingDetails.form = {...bookingData};
            self.modalConfig.editBookingDetails.cancelBookingLink = self.generateCancelBookingLink({...bookingData});
        },
    },
    computed: {

    },
    validations: {
        modalConfig: {
            editBookingDetails: {
                form: {
                    customerFirstName: {
                        required: required
                    },
                    customerLastName: {
                        required: required
                    },
                    customerPhone: {
                        required: required
                    },
                },
            },
        },
    },
});