Vue.use(window.vuelidate.default);
const { required, requiredIf, minLength, email, minValue } = window.validators;

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
                users: {
                    fields: [
                        {
                            key: 'index',
                            label: '#'
                        },
                        {
                            key: 'userFirstName',
                            label: 'First name'
                        },
                        {
                            key: 'userLastName',
                            label: 'Last name'
                        },
                        {
                            key: 'userEmail',
                            label: 'Email'
                        },
                        {
                            key: 'userPhone',
                            label: 'Phone',
                            
                        },
                        {
                            key: 'userActive',
                            label: 'Active',
                            formatter: (value) => {
                                return value === "1" ? 'Yes' : 'No';
                            }
                        },
                        {
                            key: 'actions',
                            label: 'Actions'
                        }
                    ],
                },
            },
            customersList: [],
            employeesList: [],
            errorMessages: {
                required: 'This field is required',
            },
            showModal: {
                editUser: false,
            },
            modals: {
                editUser: {
                    userId: null,
                    userEmail: null,
                    userFirstName: null,
                    userLastName: null,
                    userPhone: null,
                    userActive: null,
                }
            },
        }
    },
    mounted: async function () {
        console.log('app mounted');
        this.fetchUsersList(showToast = false);
    },
    methods: {
        validateInputField: function (input) {
            const self = this;

            return input.$dirty ? !input.$invalid : null;
        },
        fetchUsersList: function (showToast = true) {
            const self = this;
            const payload = {};

            axios
                .get(baseURL + '/api/users/list', payload)
                .then(res => {
                    console.log(res);
                    self.customersList = res.data.filter(user => user.userRole === 'customer');
                    self.employeesList = res.data.filter(user => user.userRole === 'staff');

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
        editUser: function () {
            const self = this;

            self.$v.modals.editUser.$touch();
            if (self.$v.modals.editUser.$invalid) { return; }

            const modalData = { ...self.modals.editUser };

            const payload = {
                form: {
                    userId: modalData.userId,
                    userEmail: modalData.userEmail,
                    userFirstName: modalData.userFirstName,
                    userLastName: modalData.userLastName,
                    userPhone: modalData.userPhone,
                    userActive: modalData.userActive,
                }
            };

            axios
                .post(baseURL + '/api/users/edit', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearModalState(modalId = 'editUser', closeModal = true);
                    self.fetchUsersList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearModalState(modalId = 'editUser', closeModal = true);
                });
        },
    },
    computed: {

    },
    validations: {
        modals: {
            editUser: {
                userEmail: {
                    required: required
                },
                userFirstName: {
                    required: required
                },
                userLastName: {
                    required: required
                },
                userPhone: {
                    required: required
                },
                userActive: {

                },
            },
        },
    },
});