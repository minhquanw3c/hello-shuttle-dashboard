<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<?php if ($userRole === 'admin'): ?>
    <div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded mb-4">
        <div class="col-12 text-right px-0 mb-3">
            <?php if (session()->get('logged_in.role') == "admin"): ?>
                <b-button variant="outline-success" @click.prevent="resetUsers('staff')" title="Reset employees" class="mr-2">
                    <b-icon icon="bootstrap-reboot"></b-icon>
                    Employee
                </b-button>
            <?php endif ?>
            <b-button
                class="btn"
                variant="outline-primary"
                @click="openModal('createUser')"
            >
                <b-icon icon="plus-circle"></b-icon>
                Employee
            </b-button>
        </div>
        <div class="col-12">
            <b-table-lite
                caption-top
                caption="Employees"
                responsive
                striped
                :fields="tableConfig.users.fields"
                :items="employeesList">
                <template #cell(index)="row">
                    {{ row.index + 1 }}
                </template>
                <template #cell(actions)="row">
                    <b-button variant="outline-primary" @click.prevent="openModal('editUser', row.item)" class="btn-sm">
                        <b-icon icon="pencil-fill"></b-icon>
                    </b-button>
                </template>
            </b-table-lite>
        </div>
    </div>
<?php endif ?>

<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
	<div class="col-12 text-right px-0 mb-3">
        <?php if (session()->get('logged_in.role') == "admin"): ?>
            <b-button variant="outline-success" @click.prevent="resetUsers('customer')" title="Reset customers" class="mr-2">
                <b-icon icon="bootstrap-reboot"></b-icon>
                Customer
            </b-button>
        <?php endif ?>
		<b-button
            class="btn"
            variant="outline-primary"
            @click.prevent="openModal('createCustomer')"
        >
			<b-icon icon="plus-circle"></b-icon>
			Customer
		</b-button>
	</div>
	<div class="col-12">
		<b-table-lite
            caption-top
            caption="Customers"
            responsive
            striped
            :fields="tableConfig.users.fields"
            :items="customersList">
			<template #cell(index)="row">
				{{ row.index + 1 }}
			</template>
			<template #cell(actions)="row">
				<b-button variant="outline-primary" @click.prevent="openModal('editCustomer', row.item)" class="btn-sm">
					<b-icon icon="pencil-fill"></b-icon>
				</b-button>
			</template>
		</b-table-lite>
	</div>
</div>

<!-- Create employee -->
<b-modal
    title="Create employee"
    no-close-on-esc
    no-close-on-backdrop
    @close="clearModalState('createUser', true)"
    :visible="showModal.createUser">
	<b-form-group
        label="Email"
        :state="validateInputField($v.modals.createUser.userEmail)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="email"
            v-model="$v.modals.createUser.userEmail.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group
        label="Password"
        :state="validateInputField($v.modals.createUser.userPassword)"
        :invalid-feedback="errorMessages.required">
        <b-input-group>
            <b-form-input
                type="text"
                readonly
                v-model="$v.modals.createUser.userPassword.$model">
            </b-form-input>
            <b-input-append>
                <b-button @click.prevent="() => { modals.createUser.userPassword = generatePassword() }">Generate</b-button>
            </b-input-append>
        </b-input-group>
	</b-form-group>

	<b-form-group
        label="First name"
        :state="validateInputField($v.modals.createUser.userFirstName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.createUser.userFirstName.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group
        label="Last name"
        :state="validateInputField($v.modals.createUser.userLastName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.createUser.userLastName.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group
        label="Phone"
        :state="validateInputField($v.modals.createUser.userPhone)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="tel"
            v-model="$v.modals.createUser.userPhone.$model">
		</b-form-input>
	</b-form-group>

	<template #modal-footer>
		<b-button
            class="px-4"
            variant="primary"
            @click.prevent="createUser"
        >
            Create
        </b-button>
	</template>
</b-modal>

<!-- Edit employee -->
<b-modal
    title="Edit user"
    no-close-on-esc
    no-close-on-backdrop
    @close="clearModalState('editUser', true)"
    :visible="showModal.editUser">
	<b-form-group
        label="Email"
        :state="validateInputField($v.modals.editUser.userEmail)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="email"
            readonly
            v-model="$v.modals.editUser.userEmail.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group
        label="First name"
        :state="validateInputField($v.modals.editUser.userFirstName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.editUser.userFirstName.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group
        label="Last name"
        :state="validateInputField($v.modals.editUser.userLastName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.editUser.userLastName.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group
        label="Phone"
        :state="validateInputField($v.modals.editUser.userPhone)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="tel"
            v-model="$v.modals.editUser.userPhone.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group>
        <b-form-checkbox
            value="1"
            unchecked-value="0"
            v-model="$v.modals.editUser.userActive.$model">
            Active?
        </b-form-checkbox>
    </b-form-group>

	<template #modal-footer>
		<b-button
            class="px-4"
            variant="primary"
            @click="editUser">
            Save
        </b-button>
	</template>
</b-modal>

<!-- Create customer -->
<b-modal
    title="Create customer"
    no-close-on-esc
    no-close-on-backdrop
    @close="clearModalState('createCustomer', true)"
    :visible="showModal.createCustomer">
	<b-form-group
        label="Email"
        :state="validateInputField($v.modals.createCustomer.customerEmail)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="email"
            v-model="$v.modals.createCustomer.customerEmail.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group
        label="Password"
        :state="validateInputField($v.modals.createCustomer.customerPassword)"
        :invalid-feedback="errorMessages.required">
        <b-input-group>
            <b-form-input
                type="text"
                v-model="$v.modals.createCustomer.customerPassword.$model">
            </b-form-input>
            <b-input-append>
                <b-button @click.prevent="() => { modals.createCustomer.customerPassword = generatePassword() }">Generate</b-button>
            </b-input-append>
        </b-input-group>
	</b-form-group>

	<b-form-group
        label="First name"
        :state="validateInputField($v.modals.createCustomer.customerFirstName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.createCustomer.customerFirstName.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group
        label="Last name"
        :state="validateInputField($v.modals.createCustomer.customerLastName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.createCustomer.customerLastName.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group
        label="Phone"
        :state="validateInputField($v.modals.createCustomer.customerPhone)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="tel"
            v-model="$v.modals.createCustomer.customerPhone.$model">
		</b-form-input>
	</b-form-group>

	<template #modal-footer>
		<b-button
            class="px-4"
            variant="primary"
            @click.prevent="createCustomer"
        >
            Create
        </b-button>
	</template>
</b-modal>

<!-- Edit customer -->
<b-modal
    title="Edit customer"
    no-close-on-esc
    no-close-on-backdrop
    @close="clearModalState('editCustomer', true)"
    :visible="showModal.editCustomer">
	<b-form-group
        label="Email"
        :state="validateInputField($v.modals.editCustomer.userEmail)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            readonly
            type="email"
            v-model="$v.modals.editCustomer.userEmail.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group
        label="First name"
        :state="validateInputField($v.modals.editCustomer.userFirstName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.editCustomer.userFirstName.$model">
		</b-form-input>
	</b-form-group>

	<b-form-group
        label="Last name"
        :state="validateInputField($v.modals.editCustomer.userLastName)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="text"
            v-model="$v.modals.editCustomer.userLastName.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group
        label="Phone"
        :state="validateInputField($v.modals.editCustomer.userPhone)"
        :invalid-feedback="errorMessages.required">
		<b-form-input
            type="tel"
            v-model="$v.modals.editCustomer.userPhone.$model">
		</b-form-input>
	</b-form-group>

    <b-form-group>
        <b-form-checkbox
            value="1"
            unchecked-value="0"
            v-model="$v.modals.editCustomer.userActive.$model">
            Active?
        </b-form-checkbox>
    </b-form-group>

	<template #modal-footer>
		<b-button
            class="px-4"
            variant="primary"
            @click.prevent="editCustomer"
        >
            Save
        </b-button>
	</template>
</b-modal>

<!-- Edit customer -->
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
    <script src="<?= base_url('static/js/users.js?v=' . now()) ?>"></script>
<?= $this->endSection() ?>