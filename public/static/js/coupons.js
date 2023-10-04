Vue.use(window.vuelidate.default);
// Vue.component('multiselect', window.VueMultiselect.default);
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
                coupons: {
                    fields: [
                        {
                            key: 'index',
                            label: '#'
                        },
                        {
                            key: 'couponCode',
                            label: 'Code'
                        },
                        {
                            key: 'couponDiscountAmount',
                            label: 'Discount amount'
                        },
                        {
                            key: 'couponIsPercentage',
                            label: 'Percentage',
                            formatter: (value) => {
                                return value === "yes" ? 'Yes' : 'No';
                            }
                        },
                        {
                            key: 'couponStartDate',
                            label: 'Start date'
                        },
                        {
                            key: 'couponEndDate',
                            label: 'End date',
                            
                        },
                        {
                            key: 'actions',
                            label: 'Actions'
                        }
                    ],
                },
            },
            couponsList: [],
            errorMessages: {
                required: 'This field is required',
            },
            showModal: {
                editCoupon: false,
                createCoupon: false,
            },
            modals: {
                editCoupon: {

                },
                createCoupon: {
                    couponCode: null,
                    couponDiscountAmount: null,
                    couponIsPercentage: 'no',
                    couponStartDate: null,
                    couponEndDate: null,
                },
            },
        }
    },
    mounted: async function () {
        console.log('app mounted');
        this.fetchCouponsList(showToast = false);
    },
    methods: {
        validateInputField: function (input) {
            const self = this;

            return input.$dirty ? !input.$invalid : null;
        },
        fetchCouponsList: function (showToast = true) {
            const self = this;
            const payload = {};

            axios
                .get(baseURL + '/api/coupons/list', payload)
                .then(res => {
                    console.log(res);
                    self.couponsList = res.data;

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
        openModal: function (modalId, data) {
            const self = this;

            self.showModal[modalId] = true;
            self.modals[modalId] = { ...data };
        },
        clearModalState: function (modalId, closeModal = false) {
            const self = this;

            if (closeModal) {
                self.showModal[modalId] = false;
            }

            self.$v.modals[modalId].$reset();
            self.modals[modalId] = {...self.modals[modalId]};
        },
        editCoupon: function () {
            const self = this;

            self.$v.modals.editCoupon.$touch();
            if (self.$v.modals.editCoupon.$invalid) { return; }

            const modalData = { ...self.modals.editCoupon };

            const payload = {
                form: {
                    couponId: modalData.couponId,
                    code: modalData.couponCode,
                    discountAmount: modalData.couponDiscountAmount,
                    isPercentage: modalData.couponIsPercentage,
                    startDate: modalData.couponStartDate,
                    endDate: modalData.couponEndDate,
                }
            };

            axios
                .post(baseURL + '/api/coupons/edit', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearModalState(modalId = 'editCoupon', closeModal = true);
                    self.fetchCouponsList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearModalState(modalId = 'editCoupon', closeModal = true);
                });
        },
        createCoupon: function () {
            const self = this;

            self.$v.modals.createCoupon.$touch();
            if (self.$v.modals.createCoupon.$invalid) { return; }

            const modalData = { ...self.modals.createCoupon };

            const payload = {
                form: {
                    code: modalData.couponCode,
                    discountAmount: modalData.couponDiscountAmount,
                    isPercentage: modalData.couponIsPercentage,
                    startDate: modalData.couponStartDate,
                    endDate: modalData.couponEndDate,
                }
            };

            axios
                .post(baseURL + '/api/coupons/create', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearModalState(modalId = 'createCoupon', closeModal = true);
                    self.fetchCouponsList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    error.status === 500 && self.showToastNotification(toastType = 'error', error.data.message);
                    self.clearModalState(modalId = 'createCoupon', closeModal = true);
                });
        },
        generateRandomString: function() {
            const length = 10; // Change the length to your desired value
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
      
            for (let i = 0; i < length; i++) {
              const randomIndex = Math.floor(Math.random() * characters.length);
              result += characters.charAt(randomIndex);
            }

            this.modals.createCoupon.couponCode = result;
        }
    },
    computed: {

    },
    validations: {
        modals: {
            editCoupon: {
                couponCode: {
                    required: required
                },
                couponDiscountAmount: {
                    required: required
                },
                couponStartDate: {
                    required: required
                },
                couponEndDate: {
                    required: required
                },
                couponIsPercentage: {
                    required: required
                }
            },
            createCoupon: {
                couponCode: {
                    required: required
                },
                couponDiscountAmount: {
                    required: required
                },
                couponStartDate: {
                    required: required
                },
                couponEndDate: {
                    required: required
                },
                couponIsPercentage: {
                    required: required
                }
            },
        },
    },
});