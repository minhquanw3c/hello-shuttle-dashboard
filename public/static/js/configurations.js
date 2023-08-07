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
                            key: 'carStartPrice',
                            label: 'Price'
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
                    carStartPrice: modalData.carStartPrice,
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

    },
    validations: {
        modals: {
            editConfig: {
                configValue: {
                    required: required
                },
                configMaximumQuantity: {
                    required: required
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
                    required: required
                },
            },
            editCar: {
                carQuantity: {
                    required: required
                },
                carStartPrice: {
                    required: required
                },
            },
        },
    },
});