<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
    <b-form @submit.prevent="">
        <div class="row">
            <div class="col-12 col-md-6">
                <b-form-group
                    label="First name"
                >
                    <b-form-input
                        disabled
                        type="text"
                        v-model="$v.forms.personalInfo.firstName.$model"
                    >
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-6">
                <b-form-group
                    label="Last name"
                >
                    <b-form-input
                        disabled
                        type="text"
                        v-model="$v.forms.personalInfo.lastName.$model"
                    >
                    </b-form-input>
                </b-form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <b-form-group
                    label="Mobile phone"
                >
                    <b-form-input
                        disabled
                        type="tel"
                        v-model="$v.forms.personalInfo.phone.$model"
                    >
                    </b-form-input>
                </b-form-group>
            </div>
            
            <div class="col-12 col-md-6">
                <b-form-group
                    label="Username"
                >
                    <b-form-input
                        disabled
                        type="text"
                        v-model="$v.forms.personalInfo.username.$model"
                    >
                    </b-form-input>
                </b-form-group>
            </div>
        </div>
    </b-form>
</div>

<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded mt-4">
    <b-form @submit.prevent="submitChangePassword">
        <div class="row">
            <div class="col-12">
                <b-alert
                    variant="warning"
                    show
                >
                    You will be disconnected from the system after changing password.
                    Please login to the system again for the changes to take affect.
                </b-alert>
            </div>
            <div class="col-12 col-md-6">
                <b-form-group
                    label="New password"
                    :state="validateInputField($v.forms.password.newPassword)"
                    :invalid-feedback="errorMessages.required"
                >
                    <b-form-input
                        type="password"
                        v-model="$v.forms.password.newPassword.$model"
                    >
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-6">
                <b-form-group
                    label="Confirm password"
                    :state="validateInputField($v.forms.password.confirmNewPassword)"
                    :invalid-feedback="errorMessages.required"
                >
                    <b-form-input
                        type="password"
                        v-model="$v.forms.password.confirmNewPassword.$model"
                    >
                    </b-form-input>
                </b-form-group>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 text-right">
                <b-button
                    type="submit"
                    variant="outline-primary"
                >
                    Save changes
                </b-button>
            </div>
        </div>
    </b-form>
</div>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script type="text/javascript">
	const userInfo = {
        userId: "<?= session('logged_in.user_id') ?>",
        username: "<?= session('logged_in.username') ?>",
        firstName: "<?= session('logged_in.user_first_name') ?>",
        lastName: "<?= session('logged_in.user_last_name') ?>",
        phone: "<?= session('logged_in.user_phone') ?>",
    };
</script>

<script src="<?= base_url('static/js/account-settings.js?v=' . now()) ?>"></script>
<?= $this->endSection() ?>