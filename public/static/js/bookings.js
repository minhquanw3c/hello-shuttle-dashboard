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
                            key: 'bookingId',
                            label: 'Booking number'
                        },
                        {
                            key: 'bookingPaymentStatus',
                            label: 'Payment status'
                        },
                        {
                            key: 'bookingCreatedAt',
                            label: 'Created at'
                        },
                    ],
                },
            },
            bookings: [],
            errorMessages: {
                required: 'This field is required',
            },
        }
    },
    mounted: async function () {
        console.log('app mounted');
        this.fetchBookingsList();
    },
    methods: {
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
                    self.bookings = res.data;

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
    },
    computed: {

    },
    validations: {

    },
});