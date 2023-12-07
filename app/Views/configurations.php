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
    <div class="row">
        <div class="col-12 text-right px-0 mb-3">
            <b-button
                variant="outline-primary"
                @click.prevent="() => showCreateCarModal = true"
            >
                <b-icon icon="plus-square"></b-icon>
                Car
            </b-button>
        </div>

        <div class="col-12">
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
    </div>
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
        :invalid-feedback="baseErrorMessages.required">
        <b-form-input
            type="text"
            v-model="$v.modals.addConfig.configName.$model">
        </b-form-input>
    </b-form-group>

    <b-form-group
        :state="validateInputField($v.modals.addConfig.configValue)"
        :invalid-feedback="baseErrorMessages.required"
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
        :invalid-feedback="baseErrorMessages.required"
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
            :invalid-feedback="baseErrorMessages.required"
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
        :invalid-feedback="baseErrorMessages.required"
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
            :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
                    label="Fee type">
                    <b-form-select
                        text-field="text"
                        value-field="value"
                        v-model="$v.modals.editCar.pickUpFeeType.$model"
                        :options="pickupFeeTypes">
                    </b-form-select>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.editCar.pickUpFeeType.$model === 'percentage'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.editCar.pickUpFeePercentage)"
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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
                    :invalid-feedback="baseErrorMessages.required"
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

<!-- Create new car -->
<b-modal
    scrollable
    no-close-on-esc
    no-close-on-backdrop
    size="lg"
    title="Create new car"
    @close.prevent="onCloseCreateNewCar"
    :visible="showCreateCarModal">
    <b-form-group label="Car details">
        <div class="row">
            <div class="col-12 col-md-6">
                <b-form-group
                    :invalid-feedback="errorMessages.createCar.carName"
                    :state="validateInputField($v.modals.createCar.carName)"
                    label="Name"
                >
                    <b-form-input
                        type="text"
                        v-model="$v.modals.createCar.carName.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-6">
                <b-form-group
                    :invalid-feedback="errorMessages.createCar.carSeats"
                    :state="validateInputField($v.modals.createCar.carSeats)"
                    label="Seats"
                >
                    <b-form-input
                        type="number"
                        min="1"
                        v-model="$v.modals.createCar.carSeats.$model">
                    </b-form-input>
                </b-form-group>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.openDoorPrice)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.openDoorPrice"
                    label="Open door">
                    <b-form-input
                        type="number"
                        min="1"
                        v-model="$v.modals.createCar.priceConfig.openDoorPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-6">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.carQuantity)"
                    :invalid-feedback="errorMessages.createCar.carQuantity"
                    label="Quantity">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.createCar.carQuantity.$model">
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
                    :state="validateInputField($v.modals.createCar.priceConfig.firstMiles)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.firstMiles"
                    label="First miles">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.firstMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.firstMilesPrice)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.firstMilesPrice"
                    label="First miles price">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.firstMilesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.createCar.priceConfig.firstMilesPriceActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>

        <div class="row align-items-baseline">
            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.secondMiles)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.secondMiles"
                    label="Second miles">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.secondMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.secondMilesPrice)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.secondMilesPrice"
                    label="Second miles price">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.secondMilesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.createCar.priceConfig.secondMilesPriceActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>

        <div class="row align-items-baseline">
            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.thirdMiles)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.thirdMiles"
                    label="Third miles">
                    <b-form-input
                        min="1"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.thirdMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.thirdMilesPrice)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.thirdMilesPrice"
                    label="Third miles price">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.thirdMilesPrice.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-4">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.createCar.priceConfig.thirdMilesPriceActive.$model">
                        Active?
                    </b-form-checkbox>
                </b-form-group>
            </div>
        </div>
    </b-form-group>

    <hr/>

    <!-- Admin fee -->
    <b-form-group label="Admin fee">
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.adminFee.limitMiles)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.adminFee.limitMiles"
                    label="Limit miles">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.adminFee.limitMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.adminFee.type)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.adminFee.type"
                    label="Fee type">
                    <b-form-select
                        text-field="text"
                        value-field="value"
                        v-model="$v.modals.createCar.priceConfig.adminFee.type.$model"
                        :options="adminFeeTypes">
                    </b-form-select>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.createCar.priceConfig.adminFee.type.$model === 'percentage'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.adminFee.percentage)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.adminFee.percentage"
                    label="Percentage">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.adminFee.percentage.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.createCar.priceConfig.adminFee.type.$model === 'fixed'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.adminFee.fixedAmount)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.adminFee.fixedAmount"
                    label="Fixed amount">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.adminFee.fixedAmount.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.createCar.priceConfig.adminFee.active.$model">
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
                    :state="validateInputField($v.modals.createCar.priceConfig.pickupFee.limitMiles)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.pickupFee.limitMiles"
                    label="Limit miles">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.pickupFee.limitMiles.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.pickupFee.type)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.pickupFee.type"
                    label="Fee type">
                    <b-form-select
                        text-field="text"
                        value-field="value"
                        v-model="$v.modals.createCar.priceConfig.pickupFee.type.$model"
                        :options="pickupFeeTypes">
                    </b-form-select>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.createCar.priceConfig.pickupFee.type.$model === 'percentage'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.pickupFee.percentage)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.pickupFee.percentage"
                    label="Percentage">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.pickupFee.percentage.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div
                v-if="$v.modals.createCar.priceConfig.pickupFee.type.$model === 'fixed'"
                class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.pickupFee.fixedAmount)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.pickupFee.fixedAmount"
                    label="Fixed amount">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.pickupFee.fixedAmount.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group>
                    <b-form-checkbox
                        value="1"
                        unchecked-value="0"
                        v-model="$v.modals.createCar.priceConfig.pickupFee.active.$model">
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
                    :state="validateInputField($v.modals.createCar.priceConfig.luggage.maxCapacity)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.luggage.maxCapacity"
                    label="Maximum luggages">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.luggage.maxCapacity.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.luggage.freeQuantity)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.luggage.freeQuantity"
                    label="Free cost luggages">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.luggage.freeQuantity.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.luggage.extrasPrice)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.luggage.extrasPrice"
                    label="Price extra luggage">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.luggage.extrasPrice.$model">
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
                    :state="validateInputField($v.modals.createCar.priceConfig.passenger.maxCapacity)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.passenger.maxCapacity"
                    label="Maximum passengers">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.passenger.maxCapacity.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.passenger.freeQuantity)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.passenger.freeQuantity"
                    label="Free cost passengers">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.passenger.freeQuantity.$model">
                    </b-form-input>
                </b-form-group>
            </div>

            <div class="col-12 col-md-3">
                <b-form-group
                    :state="validateInputField($v.modals.createCar.priceConfig.passenger.extrasPrice)"
                    :invalid-feedback="errorMessages.createCar.priceConfig.passenger.extrasPrice"
                    label="Price extra passenger">
                    <b-form-input
                        min="0"
                        type="number"
                        v-model="$v.modals.createCar.priceConfig.passenger.extrasPrice.$model">
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
                    v-model="$v.modals.createCar.carActive.$model">
                    Enable this car for booking?
                </b-form-checkbox>
            </b-form-group>
        </div>
    </div>

    <template #modal-footer>
        <b-button
            class="px-4"
            variant="primary"
            @click="createCar">
            Create
        </b-button>
    </template>
</b-modal>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script src="<?= base_url('static/js/configurations.js?v=' . now()) ?>"></script>
<?= $this->endSection() ?>