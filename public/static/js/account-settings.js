Vue.use(window.vuelidate.default);
const { required, requiredIf, minLength, email, minValue } = window.validators;

var app = new Vue({
    el: '#main-app',
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
            }
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
        }
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
                    required: required
                },
                confirmNewPassword: {
                    required: required
                }
            },
        }
    }
});