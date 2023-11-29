<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<?php if ($userRole === 'admin'): ?>
    <div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded mb-4">
        <div class="col-12 text-right px-0 mb-3">
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
                    <b-button variant="outline-primary" @click="openModal('editUser', row.item)" class="btn-sm">
                        <b-icon icon="pencil-fill"></b-icon>
                    </b-button>
                </template>
            </b-table-lite>
        </div>
    </div>
<?php endif ?>

<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
	<div class="col-12 text-right px-0 mb-3">
		<b-button class="btn" variant="outline-primary">
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
				<b-button variant="outline-primary" @click="" class="btn-sm">
					<b-icon icon="pencil-fill"></b-icon>
				</b-button>
			</template>
		</b-table-lite>
	</div>
</div>

<!-- Modals sections -->
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
                <b-button @click.prevent="generatePassword">Generate</b-button>
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
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
    <script src="<?= base_url('static/js/users.js?v=' . now()) ?>"></script>
<?= $this->endSection() ?>