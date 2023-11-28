Vue.use(window.vuelidate.default);
const { required, requiredIf, minLength, email, minValue, sameAs, helpers } = window.validators;

var app = new Vue({
    el: '#main-app',
    mixins: [utilities],
    data: function () {
        return {
            forms: {
                personalInfo: {
                    firstName: null,
                    lastName: null,
                    phone: null,
                    username: null,
                },
                password: {
                    newPassword: null,
                    confirmNewPassword: null,
                }
            },
            errorMessages: {
                required: 'This field is required',
                invalid: 'This field is invalid',
            },
        }
    },
    mounted: async function () {
        const self = this;

        console.log('app mounted');
        self.getUserInfoFromSession();
    },
    methods: {
        getUserInfoFromSession: function () {
            const self = this;

            self.forms.personalInfo.firstName = userInfo.firstName;
            self.forms.personalInfo.lastName = userInfo.lastName;
            self.forms.personalInfo.phone = userInfo.phone;
            self.forms.personalInfo.username = userInfo.username;
        },
        submitChangePassword: function (showToast = true) {
            const self = this;

            self.$v.forms.password.$touch();

            let changePasswordValidity = !self.$v.forms.password.$invalid;

            if (changePasswordValidity === false) {
                return;
            }

            const payload = {
                form: {
                    userId: userInfo.userId,
                    password: self.forms.password.newPassword,
                    confirmNewPassword: self.forms.password.confirmNewPassword,
                }
            };

            axios
                .post(baseURL + '/account/settings/update', payload)
                .then(res => {
                    if (res.data.result === true) {
                        window.location.replace(baseURL + '/logout');
                        return;
                    }

                    showToast && self.showToastNotification();
                })
                .catch(error => {
                    showToast && self.showToastNotification();
                });
        },
    },
    computed: {

    },
    validations: {
        forms: {
            personalInfo: {
                firstName: {
                    required: required
                },
                lastName: {
                    required: required
                },
                phone: {},
                username: {},
            },
            password: {
                newPassword: {
                    required: required,
                    minLength: minLength(10),
                    regex: function (val) {
                        return /^[a-zA-Z0-9]+$/g.test(val);
                    },
                },
                confirmNewPassword: {
                    required: required,
                    sameAs: sameAs(function() {
                        return this.forms.password.newPassword;
                    })
                }
            },
        }
    }
});