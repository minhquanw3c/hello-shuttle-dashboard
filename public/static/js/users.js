Vue.use(window.vuelidate.default);
const { required, requiredIf, minLength, email, minValue } = window.validators;

const appData = {
    createUser: {
        userEmail: null,
        userPassword: null,
        userFirstName: null,
        userLastName: null,
        userPhone: null,
    },
    editUser: {
        userId: null,
        userEmail: null,
        userFirstName: null,
        userLastName: null,
        userPhone: null,
        userActive: null,
    },
    createCustomer: {
        customerEmail: null,
        customerPassword: null,
        customerFirstName: null,
        customerLastName: null,
        customerPhone: null,
    },
    editCustomer: {
        userId: null,
        userEmail: null,
        userFirstName: null,
        userLastName: null,
        userPhone: null,
        userActive: null,
    }
};

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
                createUser: false,
                editUser: false,
                createCustomer: false,
                editCustomer: false,
            },
            modals: {
                createUser: {...appData.createUser},
                editUser: {...appData.editUser},
                createCustomer: {...appData.createCustomer},
                editCustomer: {...appData.editCustomer},
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
        openModal: function (modalId, data = null) {
            const self = this;

            self.showModal[modalId] = true;

            if (data) {
                self.modals[modalId] = { ...data };
            }
        },
        clearModalState: function (modalId, closeModal = false) {
            const self = this;

            if (closeModal) {
                self.showModal[modalId] = false;
            }

            self.$v.modals[modalId].$reset();
            self.modals[modalId] = {...appData[modalId]};
        },
        generatePassword: function () {
            const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
            const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const digitChars = '0123456789';
      
            const allChars = lowercaseChars + uppercaseChars + digitChars;
      
            let password = '';
      
            // Add at least one lowercase character
            password += lowercaseChars.charAt(Math.floor(Math.random() * lowercaseChars.length));
      
            // Add at least one uppercase character
            password += uppercaseChars.charAt(Math.floor(Math.random() * uppercaseChars.length));
      
            // Add at least one digit
            password += digitChars.charAt(Math.floor(Math.random() * digitChars.length));
      
            // Add remaining characters
            for (let i = 3; i < 12; i++) {
              password += allChars.charAt(Math.floor(Math.random() * allChars.length));
            }
      
            // Shuffle the password characters
            password = password.split('').sort(() => Math.random() - 0.5).join('');
      
            return password;
        },
        createUser: function () {
            const self = this;

            self.$v.modals.createUser.$touch();
            if (self.$v.modals.createUser.$invalid) { return; }

            const modalData = { ...self.modals.createUser };

            const payload = {
                form: {
                    userEmail: modalData.userEmail,
                    userPassword: modalData.userPassword,
                    userFirstName: modalData.userFirstName,
                    userLastName: modalData.userLastName,
                    userPhone: modalData.userPhone,
                }
            };

            axios
                .post(baseURL + '/api/users/create', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearModalState(modalId = 'createUser', closeModal = true);
                    self.fetchUsersList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearModalState(modalId = 'createUser', closeModal = true);
                });
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
        createCustomer: function () {
            const self = this;

            self.$v.modals.createCustomer.$touch();
            if (self.$v.modals.createCustomer.$invalid) { return; }

            const modalData = { ...self.modals.createCustomer };

            const payload = {
                form: {
                    customerEmail: modalData.customerEmail,
                    customerPassword: modalData.customerPassword,
                    customerFirstName: modalData.customerFirstName,
                    customerLastName: modalData.customerLastName,
                    customerPhone: modalData.customerPhone,
                }
            };

            axios
                .post(baseURL + '/api/customers/create', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearModalState(modalId = 'createCustomer', closeModal = true);
                    self.fetchUsersList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearModalState(modalId = 'createCustomer', closeModal = true);
                });
        },
        editCustomer: function () {
            const self = this;

            self.$v.modals.editCustomer.$touch();
            if (self.$v.modals.editCustomer.$invalid) { return; }

            const modalData = { ...self.modals.editCustomer };

            const payload = {
                form: {
                    customerId: modalData.userId,
                    customerEmail: modalData.userEmail,
                    customerFirstName: modalData.userFirstName,
                    customerLastName: modalData.userLastName,
                    customerPhone: modalData.userPhone,
                    customerActive: modalData.userActive,
                }
            };

            axios
                .post(baseURL + '/api/customers/edit', payload)
                .then(res => {
                    var toastType = res.status === 200 ? 'success' : 'error';
                    self.showToastNotification(toastType);
                    self.clearModalState(modalId = 'editCustomer', closeModal = true);
                    self.fetchUsersList(showToast = false);
                })
                .catch(error => {
                    console.log(error);
                    self.clearModalState(modalId = 'editCustomer', closeModal = true);
                });
        },
    },
    computed: {

    },
    validations: {
        modals: {
            createUser: {
                userEmail: {
                    required: required
                },
                userPassword: {
                    required: required,
                    minLength: minLength(10)
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
            },
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
            createCustomer: {
                customerEmail: {
                    required: required
                },
                customerPassword: {
                    required: required,
                    minLength: minLength(10)
                },
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
            editCustomer: {
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