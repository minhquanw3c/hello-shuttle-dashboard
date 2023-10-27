<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded">
    <div class="col-12 text-right px-0 mb-3">
        <b-button variant="outline-primary" @click="openCreateConfigModal">
            <b-icon icon="plus-square"></b-icon>
            Configuration
        </b-button>
    </div>

    <b-tabs v-model="configActiveTabIndex" card>
        <b-tab title="System">
            <b-table-lite
                primary-key="configId"
                responsive
                striped
                :fields="tableConfig.configurations.fields"
                :items="systemConfigList">
                <template #cell(index)="row">
                    {{ row.index + 1 }}
                </template>
                <template #cell(actions)="row">
                    <b-button
                        class="d-none"
                        size="sm"
                        variant="outline-primary">
                        <b-icon icon="pencil-fill"></b-icon>
                    </b-button>
                </template>
            </b-table-lite>
        </b-tab>

        <b-tab title="Extras">
            <b-table-lite
                primary-key="configId"
                responsive
                striped
                :fields="tableConfig.configurations.fields"
                :items="extrasConfigList">
                <template #cell(index)="row">
                    {{ row.index + 1 }}
                </template>
                <template #cell(actions)="row">
                    <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="openConfigModal(row.item)"
                        v-if="row.item.configEditable === '1'">
                        <b-icon icon="pencil-fill"></b-icon>
                    </b-button>
                </template>
            </b-table-lite>
        </b-tab>

        <b-tab title="Protection">
            <b-table-lite
                primary-key="configId"
                responsive
                striped
                :fields="tableConfig.configurations.fields"
                :items="protectionConfigList">
                <template #cell(index)="row">
                    {{ row.index + 1 }}
                </template>
                <template #cell(actions)="row">
                    <b-button
                        size="sm"
                        variant="outline-primary"
                        @click="openConfigModal(row.item)"
                        v-if="row.item.configEditable === '1'">
                        <b-icon icon="pencil-fill"></b-icon>
                    </b-button>
                </template>
            </b-table-lite>
        </b-tab>
    </b-tabs>
</div>

<div class="stat-box px-3 px-md-4 py-3 py-lg-4 shadow-sm rounded mt-4">
    <b-table-lite
        primary-key="carId"
        caption="Cars"
        caption-top
        responsive
        striped
        :fields="tableConfig.cars.fields"
        :items="carsList">
        <template #cell(index)="row">
            {{ row.index + 1 }}
        </template>
        <template #cell(actions)="row">
            <b-button
                size="sm"
                variant="outline-primary"
                @click="openCarModal(row.item)"
                v-if="row.item.carEditable === '1'">
                <b-icon icon="pencil-fill"></b-icon>
            </b-button>
        </template>
    </b-table-lite>
</div>

<!-- Modals sections -->

<!-- Add new Extras/Protection configurations -->
<b-modal
    title="Add new configuration"
    @close="clearCreateConfigModalState"
    :visible="showAddConfigModal">
    <b-form-group
        label="Config name"
        :state="validateInputField($v.modals.addConfig.configName)"
        :invalid-feedback="errorMessages.required">
        <b-form-input
            type="text"
            v-model="$v.modals.addConfig.configName.$model">
        </b-form-input>
    </b-form-group>

    <b-form-group
        :state="validateInputField($v.modals.addConfig.configValue)"
        :invalid-feedback="errorMessages.required"
        label="Config value">
        <b-input-group>
            <b-form-input
                type="number"
                v-model="$v.modals.addConfig.configValue.$model">
            </b-form-input>
            <b-input-group-append>
                <b-button>
                    <b-icon icon="currency-dollar"></b-icon>
                </b-button>
            </b-input-group-append>
        </b-input-group>
    </b-form-group>

    <b-form-group
        :state="validateInputField($v.modals.addConfig.configType)"
        :invalid-feedback="errorMessages.required"
        label="Config type">
        <b-form-select
            :options="bookingOptionTypes"
            v-model="$v.modals.addConfig.configType.$model">
        </b-form-select>
    </b-form-group>

    <b-form-group>
        <b-form-checkbox
            name="add-config-countable"
            value="1"
            unchecked-value="0"
            v-model="$v.modals.addConfig.configCountable.$model">
            Countable?
        </b-form-checkbox>
    </b-form-group>

    <template v-if="$v.modals.addConfig.configCountable.$model === '1'">
        <b-form-group
            :state="validateInputField($v.modals.addConfig.configMaximumQuantity)"
            :invalid-feedback="errorMessages.required"
            label="Config maximum quantity">
            <b-form-input
                type="number"
                min="1"
                v-model="$v.modals.addConfig.configMaximumQuantity.$model">
            </b-form-select>
        </b-form-group>
    </template>

    <template #modal-footer>
        <b-button
            class="px-4"
            variant="primary"
            @click="createConfig">
            Create
        </b-button>
    </template>
</b-modal>

<!-- Edit Extras/Protection Configurations -->
<b-modal
    title="Edit configuration"
    @close="clearConfigModalState"
    :visible="showEditConfigModal">
    <b-form-group label="Config name">
        <b-form-input
            type="text"
            disabled
            v-model="modals.editConfig.configName">
        </b-form-input>
    </b-form-group>

    <b-form-group
        :state="validateInputField($v.modals.editConfig.configValue)"
        :invalid-feedback="errorMessages.required"
        label="Config value">
        <b-input-group>
            <b-form-input
                type="number"
                v-model="$v.modals.editConfig.configValue.$model">
            </b-form-input>
            <b-input-group-append>
                <b-button>
                    <b-icon icon="currency-dollar"></b-icon>
                </b-button>
            </b-input-group-append>
        </b-input-group>
    </b-form-group>

    <b-form-group>
        <b-form-checkbox
            name="edit-config-countable"
            value="1"
            unchecked-value="0"
            v-model="$v.modals.editConfig.configCountable.$model">
            Countable?
        </b-form-checkbox>
    </b-form-group>

    <template v-if="$v.modals.editConfig.configCountable.$model === '1'">
        <b-form-group
            :state="validateInputField($v.modals.editConfig.configMaximumQuantity)"
            :invalid-feedback="errorMessages.required"
            label="Config maximum quantity">
            <b-input-group>
                <b-form-input
                    type="number"
                    v-model="$v.modals.editConfig.configMaximumQuantity.$model">
                </b-form-input>
                <b-input-group-append>
                    <b-button>
                        <b-icon icon="currency-dollar"></b-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
        </b-form-group>
    </template>

    <b-form-group>
        <b-form-checkbox
            value="1"
            unchecked-value="0"
            v-model="modals.editConfig.configActive">
            Active?
        </b-form-checkbox>
    </b-form-group>

    <template #modal-footer>
        <b-button
            class="px-4"
            variant="primary"
            @click="editConfig">
            Save
        </b-button>
    </template>
</b-modal>

<!-- Cars -->
<b-modal
    scrollable
    no-close-on-esc
    no-close-on-backdrop
    size="lg"
    title="Edit car"
    @close="clearCarModalState"
    :visible="showEditCarModal">
    <div class="row mb-3">
        <div class="col-12 text-center">
            <img
                class="d-inline-block img-fluid"
                :src="baseURL + '/static/images/vehicles/' + modals.editCar.carImage"
                :alt="modals.editCar.carName"
            />
        </div>
    </div>

    <b-form-group label="Car details">
        <div class="row">
            <div class="col-12 col-md-6">
                <b-form-group label="Name">
                    <b-form-input
                        type="text"
                        disabled
                        v-model="modals.editCar.carName">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-6">
                <b-form-group label="Seats">
                    <b-form-input
                        type="number"
                        disabled
                        min="1"
                        v-model="modals.editCar.carSeatsCapacity">
                    </b-form-input>
                </b-form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.openDoorPrice)"
                    :invalid-feedback="errorMessages.required"
                    label="Open door">
                    <b-form-input
                        type="number"
                        v-model="$v.modals.editCar.openDoorPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-6">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.carQuantity)"
                    :invalid-feedback="errorMessages.required"
                    label="Quantity">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.editCar.carQuantity.$model">
                    </b-form-input>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <b-form-group label="Price formulas">
        <div class="row align-items-baseline">
            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.firstMiles)"
                    :invalid-feedback="errorMessage_firstMiles"
                    label="First miles">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.editCar.firstMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.firstMilesPrice)"
                    :invalid-feedback="errorMessages.required"
                    label="First price">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.firstMilesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.editCar.firstMilesPriceActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>

        <div class="row align-items-baseline">
            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.secondMiles)"
                    :invalid-feedback="errorMessage_secondMiles"
                    label="Second miles">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.editCar.secondMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.secondMilesPrice)"
                    :invalid-feedback="errorMessages.required"
                    label="Second price">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.secondMilesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.editCar.secondMilesPriceActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>

        <div class="row align-items-baseline">
            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.thirdMiles)"
                    :invalid-feedback="errorMessage_thirdMiles"
                    label="Third miles">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.editCar.thirdMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.thirdMilesPrice)"
                    :invalid-feedback="errorMessages.required"
                    label="Third price">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.thirdMilesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.editCar.thirdMilesPriceActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <b-form-group label="Admin fee">
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.adminFeeLimitMiles)"
                    :invalid-feedback="errorMessages.required"
                    label="Limit miles">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.adminFeeLimitMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.adminFeeType)"
                    :invalid-feedback="errorMessages.required"
                    label="Fee type">
                    <b-form-select
                        text-field="text"
                        value-field="value"
                        v-model="$v.modals.editCar.adminFeeType.$model"
                        :options="adminFeeTypes">
                    </b-form-select>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.editCar.adminFeeType.$model === 'percentage'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.adminFeePercentage)"
                    :invalid-feedback="errorMessages.required"
                    label="Percentage">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.adminFeePercentage.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.editCar.adminFeeType.$model === 'fixed'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.adminFeeFixedAmount)"
                    :invalid-feedback="errorMessages.required"
                    label="Fixed amount">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.adminFeeFixedAmount.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.editCar.adminFeeActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <b-form-group label="Pickup fee">
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.pickUpFeeLimitMiles)"
                    :invalid-feedback="errorMessages.required"
                    label="Limit miles">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.pickUpFeeLimitMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.pickUpFeeType)"
                    :invalid-feedback="errorMessages.required"
                    label="Fee type">
                    <b-form-select
                        text-field="text"
                        value-field="value"
                        v-model="$v.modals.editCar.pickUpFeeType.$model"
                        :options="pickUpFeeTypes">
                    </b-form-select>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.editCar.pickUpFeeType.$model === 'percentage'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.pickUpFeePercentage)"
                    :invalid-feedback="errorMessages.required"
                    label="Percentage">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.pickUpFeePercentage.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.editCar.pickUpFeeType.$model === 'fixed'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.pickUpFeeFixedAmount)"
                    :invalid-feedback="errorMessages.required"
                    label="Fixed amount">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.pickUpFeeFixedAmount.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.editCar.pickUpFeeActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <b-form-group label="Lugguages">
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.maxLuggages)"
                    :invalid-feedback="errorMessages.required"
                    label="Maximum luggages">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.maxLuggages.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.freeLuggagesQuantity)"
                    :invalid-feedback="errorMessages.required"
                    label="Free cost luggages">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.freeLuggagesQuantity.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.extraLuggagesPrice)"
                    :invalid-feedback="errorMessages.required"
                    label="Price extra luggage">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.extraLuggagesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <b-form-group label="Passengers">
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.maxPassengers)"
                    :invalid-feedback="errorMessages.required"
                    label="Maximum passengers">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.maxPassengers.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.freePassengersQuantity)"
                    :invalid-feedback="errorMessages.required"
                    label="Free cost passengers">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.freePassengersQuantity.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.extraPassengersPrice)"
                    :invalid-feedback="errorMessages.required"
                    label="Price extra passenger">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.editCar.extraPassengersPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <div class="row align-items-baseline">
        <div class="col-12">
            <b-form-group>
                <b-form-checkbox
                    value="1"
                    unchecked-value="0"
                    v-model="$v.modals.editCar.carActive.$model">
                    Enable this car for booking?
                </b-form-checkbox>
            </b-form-group>
        </div>
    </div>

    <template #modal-footer>
        <b-button
            class="px-4"
            variant="primary"
            @click="editCar">
            Save
        </b-button>
    </template>
</b-modal>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script src="<?= base_url('static/js/configurations.js?v=' . now()) ?>"></script>
<?= $this->endSection() ?>