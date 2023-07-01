<?= $this->extend("templates/base") ?>

<?= $this->section("main") ?>
<b-table-lite caption="Configurations" caption-top responsive striped :fields="tableConfig.configurations.fields" :items="configList">
    <template #cell(index)="row">
        {{ row.index + 1 }}
    </template>
    <template #cell(actions)="row">
        <b-button variant="outline-primary" @click="openConfigModal(row.item)" v-if="row.item.configEditable === '1'">
            <b-icon icon="pencil-fill"></b-icon>
        </b-button>
    </template>
</b-table-lite>

<b-table-lite caption="Cars" caption-top responsive striped :fields="tableConfig.cars.fields" :items="carsList">
    <template #cell(index)="row">
        {{ row.index + 1 }}
    </template>
    <template #cell(actions)="row">
        <b-button variant="outline-primary" @click="openCarModal(row.item)" v-if="row.item.carEditable === '1'">
            <b-icon icon="pencil-fill"></b-icon>
        </b-button>
    </template>
</b-table-lite>

<!-- Modals sections -->

<!-- Configurations -->
<b-modal title="Edit configuration" @close="clearConfigModalState" :visible="showEditConfigModal">
    <b-form-group label="Config name">
        <b-form-input disabled v-model="modals.editConfig.configName">
        </b-form-input>
    </b-form-group>

    <b-form-group :state="validateInputField($v.modals.editConfig.configValue)" :invalid-feedback="errorMessages.required" label="Config value">
        <b-input-group>
            <b-form-input v-model="$v.modals.editConfig.configValue.$model">
            </b-form-input>
            <b-input-group-append>
                <b-button>
                    <b-icon icon="currency-dollar"></b-icon>
                </b-button>
            </b-input-group-append>
        </b-input-group>
    </b-form-group>

    <b-form-group>
        <b-form-checkbox value="1" unchecked-value="0" v-model="modals.editConfig.configActive">
            Active?
        </b-form-checkbox>
    </b-form-group>

    <template #modal-footer>
        <b-button class="px-4" variant="primary" @click="editConfig">Save</b-button>
    </template>
</b-modal>

<!-- Cars -->
<b-modal title="Edit configuration" @close="clearCarModalState" :visible="showEditCarModal">
    <b-form-group label="Car name">
        <b-form-input disabled v-model="modals.editCar.carName">
        </b-form-input>
    </b-form-group>

    <b-form-group label="Car seats">
        <b-form-input disabled v-model="modals.editCar.carSeatsCapacity">
        </b-form-input>
    </b-form-group>

    <b-form-group :state="validateInputField($v.modals.editCar.carStartPrice)" :invalid-feedback="errorMessages.required" label="Start price">
        <b-form-input v-model="$v.modals.editCar.carStartPrice.$model">
        </b-form-input>
    </b-form-group>

    <b-form-group :state="validateInputField($v.modals.editCar.carQuantity)" :invalid-feedback="errorMessages.required" label="Car quantity">
        <b-form-input v-model="$v.modals.editCar.carQuantity.$model">
        </b-form-input>
    </b-form-group>

    <b-form-group>
        <b-form-checkbox value="1" unchecked-value="0" v-model="modals.editCar.carActive">
            Active?
        </b-form-checkbox>
    </b-form-group>

    <template #modal-footer>
        <b-button class="px-4" variant="primary" @click="editCar">Save</b-button>
    </template>
</b-modal>
<?= $this->endSection() ?>

<?= $this->section("page-scripts") ?>
<script src="<?= base_url('static/js/configurations.js') ?>"></script>
<?= $this->endSection() ?>