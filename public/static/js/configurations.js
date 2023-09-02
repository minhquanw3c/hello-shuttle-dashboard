Vue.use(window.vuelidate.default);
Vue.component('multiselect', window.VueMultiselect.default);
const { required, requiredIf, minLength, email, minValue, numeric } = window.validators;
// Vue.use(VueMask);

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
                configurations: {
                    fields: [
                        {
                            key: 'index',
                            label: '#'
                        },
                        {
                            key: 'configName',
                            label: 'Config name'
                        },
                        {
                            key: 'configValue',
                            label: 'Value'
                        },
                        {
                            key: 'configGroupName',
                            label: 'Group'
                        },
                        {
                            key: 'configTypeName',
                            label: 'Type'
                        },
                        {
                            key: 'configActive',
                            label: 'State',
                            formatter: (value) => {
                                return value === "1" ? 'Active' : 'Inactive';
                            }
                        },
                        {
                            key: 'actions',
                            label: 'Actions'
                        }
                    ],
                },
                cars: {
                    fields: [
                        {
                            key: 'index',
                            label: '#'
                        },
                        {
                            key: 'carName',
                            label: 'Car name'
                        },
                        {
                            key: 'carSeatsCapacity',
                            label: 'Seats'
                        },
                        {
                            key: 'carQuantity',
                            label: 'Number of cars'
                        },
                        {
                            key: 'openDoorPrice',
                            label: 'Open door'
                        },
                        {
                            key: 'carActive',
                            label: 'State',
                            formatter: (value) => {
                                return value === "1" ? 'Active' : 'Inactive';
                            }
                        },
                        {
                            key: 'actions',
                            label: 'Actions'
                        }
                    ],
                },
            },
            configList: [],
            carsList: [],
            errorMessages: {
                required: 'This field is required',
                invalidValue: 'Invalid value',
            },
            showEditConfigModal: false,
            showEditCarModal: false,
            showAddConfigModal: false,
            modals: {
                editConfig: {},
                editCar: {},
                addConfig: {
                    configName: null,
                    configValue: null,
                    configType: null,
                    configMaximumQuantity: 1,
                },
            },
            bookingOptionTypes: [
                {
                    text: 'Extras',
                    value: 'extras',
                },
                {
                    text: 'Protection',
                    value: 'protection',
                },
            ],
            adminFeeTypes: [
                {
                    text: 'Percentage',
                    value: 'percentage',
                },
                {
                    text: 'Fixed',
                    value: 'fixed',
                },
            ],
            configActiveTabIndex: 0,
            systemConfigList: [],
            extrasConfigList: [],
            protectionConfigList: [],
        }
    },
    mounted: async function () {
        console.log('app mounted');
        this.fetchConfigList();
        this.fetchCarsList();
    },
    methods: {
        validateInputField: function (input) {
            const self = this;

            return input.$dirty ? !input.$invalid : null;
        },
        fetchCarsList: function (showToast = true) {
            const self = this;
            const payload = {};

            axios
                .get(baseURL + '/api/cars/list', payload)
                .then(res => {
                    console.log(res);
                    self.carsList = res.data;

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
        fetchConfigList: function (showToast = true) {
            const self = this;
            const payload = {};

            axios
                .get(baseURL + '/api/configurations/list', payload)
                .then(res => {
                    console.log(res);
                    self.systemConfigList = res.data.filter(item => item.configGroupId === 'cfg-gr-sys');
                    self.extrasConfigList = res.data.filter(item => item.configGroupId === 'cfg-gr-opt');
                    self.protectionConfigList = res.data.filter(item => item.configGroupId === 'cfg-gr-prt');

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
        openConfigModal: function (data) {
            const self = this;

            self.showEditConfigModal = !self.showEditConfigModal;
            self.modals.editConfig = { ...data };
        },
        openCreateConfigModal: function () {
            const self = this;

            self.showAddConfigModal = !self.showAddConfigModal;
        },
        openCarModal: function (data) {
            const self = this;

            self.showEditCarModal = !self.showEditCarModal;
            self.modals.editCar = { ...data };
        },
        clearConfigModalState: function (closeModal = false) {
            const self = this;

            if (closeModal) {
                self.showEditConfigModal = false;
            }

            self.$v.modals.editConfig.$reset();
        },
        clearCarModalState: function (closeModal = false) {
            const self = this;

            if (closeModal) {
                self.showEditCarModal = false;
            }

            self.$v.modals.editCar.$reset();
        },
        clearCreateConfigModalState: function (closeModal = false) {
            const self = this;

            if (closeModal) {
                self.showAddConfigModal = false;
            }

            self.$v.modals.addConfig.$reset();
        },
        editCar: function () {
            const self = this;

            self.$v.modals.editCar.$touch();
            if (self.$v.modals.editCar.$invalid) { return; }

            const modalData = { ...self.modals.editCar };

            const payload = {
                form: {
                    carId: modalData.carId,
                    carQuantity: modalData.carQuantity,
                    carActive: modalData.carActive,
                    //---
                    openDoorPrice: modalData.openDoorPrice,
                    //---
                    firstMiles: modalData.firstMiles,
                    firstMilesPrice: modalData.firstMilesPrice,
                    firstMilesPriceActive: modalData.firstMilesPriceActive,
                    //---
                    secondMiles: modalData.secondMiles,
                    secondMilesPrice: modalData.secondMilesPrice,
                    secondMilesPriceActive: modalData.secondMilesPriceActive,
                    //---
                    thirdMiles: modalData.thirdMiles,
                    thirdMilesPrice: modalData.thirdMilesPrice,
                    thirdMilesPriceActive: modalData.thirdMilesPriceActive,
                    //---
                    adminFeeLimitMiles: modalData.adminFeeLimitMiles,
                    adminFeeType: modalData.adminFeeType,
                    adminFeePercentage: modalData.adminFeePercentage,
                    adminFeeFixedAmount: modalData.adminFeeFixedAmount,
                    adminFeeActive: modalData.adminFeeActive,
                    //---
                    extraLuggagesPrice: modalData.extraLuggagesPrice,
                }
            };

            axios
                .post(baseURL + '/api/cars/edit', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearCarModalState(true);
                    self.fetchCarsList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearCarModalState(true);
                });
        },
        editConfig: function () {
            const self = this;

            self.$v.modals.editConfig.$touch();
            if (self.$v.modals.editConfig.$invalid) { return; }

            const modalData = { ...self.modals.editConfig };

            const payload = {
                form: {
                    configId: modalData.configId,
                    value: modalData.configValue,
                    maximumQuantity: modalData.configMaximumQuantity,
                    active: modalData.configActive,
                    countable: modalData.configCountable,
                }
            };

            axios
                .post(baseURL + '/api/configurations/edit', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearConfigModalState(true);
                    self.fetchConfigList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearConfigModalState(true);
                });
        },
        createConfig: function () {
            const self = this;

            self.$v.modals.addConfig.$touch();
            if (self.$v.modals.addConfig.$invalid) { return; }

            const modalData = { ...self.modals.addConfig };

            const payload = {
                form: {
                    name: modalData.configName,
                    value: modalData.configValue,
                    maximumQuantity: modalData.configMaximumQuantity,
                    type: modalData.configType,
                }
            };

            axios
                .post(baseURL + '/api/configurations/create', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearCreateConfigModalState(true);
                    self.fetchConfigList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearCreateConfigModalState(true);
                });
        },
    },
    computed: {
        errorMessage_firstMiles: function () {
            const self = this;

            if (!(self.$v.modals.editCar.firstMiles.required === true)) {
                return self.errorMessages.required;
            }

            if (
                !(self.$v.modals.editCar.firstMiles.numeric === true)
                ||
                !(self.$v.modals.editCar.firstMiles.minValue === true)
            ) {
                return self.errorMessages.invalidValue;
            }
        },
        errorMessage_secondMiles: function () {
            const self = this;

            if (!(self.$v.modals.editCar.secondMiles.required === true)) {
                return self.errorMessages.required;
            }

            if (
                !(self.$v.modals.editCar.secondMiles.numeric === true)
                ||
                !(self.$v.modals.editCar.secondMiles.minValue === true)
            ) {
                return self.errorMessages.invalidValue;
            }

            if (!(self.$v.modals.editCar.secondMiles.mustGreaterThanFirstMiles === true)) {
                return 'Must be greater than First miles';
            }
        },
        errorMessage_thirdMiles: function () {
            const self = this;

            if (!(self.$v.modals.editCar.thirdMiles.required === true)) {
                return self.errorMessages.required;
            }

            if (
                !(self.$v.modals.editCar.thirdMiles.numeric === true)
                ||
                !(self.$v.modals.editCar.thirdMiles.minValue === true)
            ) {
                return self.errorMessages.invalidValue;
            }

            if (!(self.$v.modals.editCar.thirdMiles.mustBeGreatest === true)) {
                return 'Must be greater than First and Second miles';
            }
        },
    },
    validations: {
        modals: {
            editConfig: {
                configValue: {
                    required: required
                },
                configMaximumQuantity: {
                    requiredIf: requiredIf(function() {
                        return this.$v.modals.editConfig.configCountable.$model === '1';
                    })
                },
                configCountable: {
                    
                },
            },
            addConfig: {
                configName: {
                    required: required
                },
                configValue: {
                    required: required
                },
                configType: {
                    required: required
                },
                configMaximumQuantity: {
                    requiredIf: requiredIf(function() {
                        return this.$v.modals.addConfig.configCountable.$model === '1';
                    })
                },
                configCountable: {
                    
                },
            },
            editCar: {
                carQuantity: {
                    required: required
                },
                carActive: {},
                openDoorPrice: {
                    required: required
                },
                // First miles config
                firstMiles: {
                    required: required,
                    numeric: numeric,
                    minValue: minValue(0),
                },
                firstMilesPrice: {
                    required: required,
                },
                firstMilesPriceActive: {},
                // Second miles config
                secondMiles: {
                    required: required,
                    numeric: numeric,
                    minValue: minValue(0),
                    mustBeGreaterThanFirstMiles: function () {
                        let firstMiles = parseInt(this.$v.modals.editCar.firstMiles.$model);
                        let secondMiles = parseInt(this.$v.modals.editCar.secondMiles.$model);

                        return secondMiles > firstMiles;
                    },
                },
                secondMilesPrice: {
                    required: required,
                },
                secondMilesPriceActive: {},
                // Third miles config
                thirdMiles: {
                    required: required,
                    numeric: numeric,
                    minValue: minValue(0),
                    mustBeGreatest: function () {
                        let firstMiles = parseInt(this.$v.modals.editCar.firstMiles.$model);
                        let secondMiles = parseInt(this.$v.modals.editCar.secondMiles.$model);
                        let thirdMiles = parseInt(this.$v.modals.editCar.thirdMiles.$model);

                        return (thirdMiles > secondMiles) && (thirdMiles > firstMiles);
                    },
                },
                thirdMilesPrice: {
                    required: required,
                },
                thirdMilesPriceActive: {},
                // Admin fee
                adminFeeLimitMiles: {
                    required: required,
                },
                adminFeeActive: {},
                adminFeeType: {
                    required: required,
                },
                adminFeePercentage: {
                    requiredIf: requiredIf(function() {
                        return this.$v.modals.editCar.adminFeeType.$model === 'percentage';
                    })
                },
                adminFeeFixedAmount: {
                    requiredIf: requiredIf(function() {
                        return this.$v.modals.editCar.adminFeeType.$model === 'fixed';
                    })
                },
                extraLuggagesPrice: {
                    required: required,
                },
            },
        },
    },
});