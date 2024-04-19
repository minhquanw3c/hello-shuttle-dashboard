Vue.use(window.vuelidate.default);
Vue.component("multiselect", window.VueMultiselect.default);
const { required, requiredIf, minLength, email, minValue, numeric, decimal } =
	window.validators;

const baseErrorMessages = {
	required: "This field is required",
	invalidValue: "Invalid value",
};

const modalsData = {
	createCar: {
		carName: null,
		carSeats: null,
		carQuantity: null,
		carEditable: null,
		carActive: 0,
		carImage: null,
		priceConfig: {
			openDoorPrice: null,
			firstMiles: null,
			firstMilesPrice: null,
			firstMilesPriceActive: 0,
			secondMiles: null,
			secondMilesPrice: null,
			secondMilesPriceActive: 0,
			thirdMiles: null,
			thirdMilesPrice: null,
			thirdMilesPriceActive: 0,
			adminFee: {
				limitMiles: null,
				type: null,
				percentage: null,
				fixedAmount: null,
				active: 0,
			},
			pickupFee: {
				limitMiles: null,
				type: null,
				percentage: null,
				fixedAmount: null,
				active: 0,
			},
			luggage: {
				maxCapacity: null,
				freeQuantity: null,
				extrasPrice: null,
			},
			passenger: {
				maxCapacity: null,
				freeQuantity: null,
				extrasPrice: null,
			},
		},
	},
};

var app = new Vue({
	el: "#main-app",
	data: function () {
		return {
			options: {
				YESNO: [
					{
						text: "Yes",
						value: true,
					},
					{
						text: "No",
						value: false,
					},
				],
			},
			form: {},
			tableConfig: {
				configurations: {
					fields: [
						{
							key: "index",
							label: "#",
						},
						{
							key: "configName",
							label: "Config name",
						},
						{
							key: "configValue",
							label: "Value",
						},
						{
							key: "configGroupName",
							label: "Group",
						},
						{
							key: "configTypeName",
							label: "Type",
						},
						{
							key: "configActive",
							label: "State",
							formatter: (value) => {
								return value === "1" ? "Active" : "Inactive";
							},
						},
						{
							key: "actions",
							label: "Actions",
						},
					],
				},
				cars: {
					fields: [
						{
							key: "index",
							label: "#",
						},
						{
							key: "carName",
							label: "Car name",
						},
						{
							key: "carSeatsCapacity",
							label: "Seats",
						},
						{
							key: "carQuantity",
							label: "Number of cars",
						},
						{
							key: "openDoorPrice",
							label: "Open door",
						},
						{
							key: "carActive",
							label: "State",
							formatter: (value) => {
								return value === "1" ? "Active" : "Inactive";
							},
						},
						{
							key: "actions",
							label: "Actions",
						},
					],
				},
			},
			configList: [],
			carsList: [],
			errorMessages: {
				createCar: {
					carName: baseErrorMessages.required,
					carSeats: `${baseErrorMessages.required}, and must be greater than 0`,
					carQuantity: `${baseErrorMessages.required}, and must be greater than 0`,
					carEditable: null,
					carActive: null,
					carImage: null,
					priceConfig: {
						openDoorPrice: `${baseErrorMessages.required}`,
						firstMiles: `${baseErrorMessages.required}`,
						firstMilesPrice: `${baseErrorMessages.required}`,
						firstMilesPriceActive: null,
						secondMiles: `${baseErrorMessages.required}, and must be greater than First miles`,
						secondMilesPrice: `${baseErrorMessages.required}`,
						secondMilesPriceActive: null,
						thirdMiles: `${baseErrorMessages.required}, and must be greater than Second miles`,
						thirdMilesPrice: `${baseErrorMessages.required}`,
						thirdMilesPriceActive: null,
						adminFee: {
							limitMiles: `${baseErrorMessages.required}`,
							type: `${baseErrorMessages.required}`,
							percentage: `${baseErrorMessages.required}`,
							fixedAmount: `${baseErrorMessages.required}`,
							active: null,
						},
						pickupFee: {
							limitMiles: `${baseErrorMessages.required}`,
							type: `${baseErrorMessages.required}`,
							percentage: `${baseErrorMessages.required}`,
							fixedAmount: `${baseErrorMessages.required}`,
							active: null,
						},
						luggage: {
							maxCapacity: `${baseErrorMessages.required}`,
							freeQuantity: `${baseErrorMessages.required}`,
							extrasPrice: `${baseErrorMessages.required}`,
						},
						passenger: {
							maxCapacity: `${baseErrorMessages.required}`,
							freeQuantity: `${baseErrorMessages.required}`,
							extrasPrice: `${baseErrorMessages.required}`,
						},
					},
				},
			},
			showEditConfigModal: false,
			showEditCarModal: false,
			showCreateCarModal: false,
			showAddConfigModal: false,
			modals: {
				editConfig: {},
				editCar: {},
				addConfig: {
					configName: null,
					configValue: null,
					configType: null,
					configMaximumQuantity: 1,
					configCountable: null,
				},
				createCar: { ...modalsData.createCar },
			},
			bookingOptionTypes: [
				{
					text: "Extras",
					value: "extras",
				},
				{
					text: "Protection",
					value: "protection",
				},
			],
			adminFeeTypes: [
				{
					text: "Percentage",
					value: "percentage",
				},
				{
					text: "Fixed",
					value: "fixed",
				},
			],
			pickupFeeTypes: [
				{
					text: "Percentage",
					value: "percentage",
				},
				{
					text: "Fixed",
					value: "fixed",
				},
			],
			configActiveTabIndex: 0,
			systemConfigList: [],
			extrasConfigList: [],
			protectionConfigList: [],
		};
	},
	mounted: async function () {
		console.log("app mounted");
		this.fetchConfigList((showToast = false));
		this.fetchCarsList((showToast = false));
	},
	methods: {
		validateInputField: function (input) {
			const self = this;

			return input.$dirty ? !input.$invalid : null;
		},
		fetchCarsList: function (showToast = true) {
			const self = this;
			const payload = {};

			axios
				.get(baseURL + "/api/cars/list", payload)
				.then((res) => {
					self.carsList = res.data;

					if (showToast) {
						var toastType =
							res.status === 200 ? "success" : "error";
						self.showToastNotification(toastType);
					}
				})
				.catch((error) => {
					console.log(error);
					if (showToast) {
						var toastType = "error";
						self.showToastNotification(toastType);
					}
				});
		},
		fetchConfigList: function (showToast = true) {
			const self = this;
			const payload = {};

			axios
				.get(baseURL + "/api/configurations/list", payload)
				.then((res) => {
					self.systemConfigList = res.data.filter(
						(item) => item.configGroupId === "cfg-gr-sys"
					);
					self.extrasConfigList = res.data.filter(
						(item) => item.configGroupId === "cfg-gr-opt"
					);
					self.protectionConfigList = res.data.filter(
						(item) => item.configGroupId === "cfg-gr-prt"
					);

					if (showToast) {
						var toastType =
							res.status === 200 ? "success" : "error";
						self.showToastNotification(toastType);
					}
				})
				.catch((error) => {
					console.log(error);
					if (showToast) {
						var toastType = "error";
						self.showToastNotification(toastType);
					}
				});
		},
		showToastNotification: function (type = "error") {
			const self = this;

			var titleType = type === "error" ? "Error" : "Success";
			var variantType = type === "error" ? "danger" : "success";
			var messageType =
				type === "error" ? "There are errors occured" : "Data is saved";

			self.$bvToast.toast(messageType, {
				title: titleType,
				autoHideDelay: 5000,
				variant: variantType,
				solid: true,
				noCloseButton: true,
			});
		},
		openConfigModal: function (data) {
			const self = this;

			self.showEditConfigModal = !self.showEditConfigModal;
			self.modals.editConfig = { ...data };
		},
		openCreateConfigModal: function () {
			const self = this;

			self.showAddConfigModal = !self.showAddConfigModal;
		},
		openCarModal: function (data) {
			const self = this;

			self.showEditCarModal = !self.showEditCarModal;
			self.modals.editCar = { ...data };
		},
		clearConfigModalState: function (closeModal = false) {
			const self = this;

			if (closeModal) {
				self.showEditConfigModal = false;
			}

			self.$v.modals.editConfig.$reset();
		},
		clearCarModalState: function (closeModal = false) {
			const self = this;

			if (closeModal) {
				self.showEditCarModal = false;
			}

			self.$v.modals.editCar.$reset();
		},
		clearCreateConfigModalState: function (closeModal = false) {
			const self = this;

			if (closeModal) {
				self.showAddConfigModal = false;
			}

			self.$v.modals.addConfig.$reset();
		},
		createCar: function () {
			const self = this;

			self.$v.modals.createCar.$touch();
			if (self.$v.modals.createCar.$invalid) {
				return;
			}

			const modalData = { ...self.modals.createCar };

			const payload = {
				form: {
					carName: modalData.carName,
					carSeats: modalData.carSeats,
					carQuantity: modalData.carQuantity,
					carActive: modalData.carActive,
					//---
					openDoorPrice: modalData.priceConfig.openDoorPrice,
					//---
					firstMiles: modalData.priceConfig.firstMiles,
					firstMilesPrice: modalData.priceConfig.firstMilesPrice,
					firstMilesPriceActive:
						modalData.priceConfig.firstMilesPriceActive,
					//---
					secondMiles: modalData.priceConfig.secondMiles,
					secondMilesPrice: modalData.priceConfig.secondMilesPrice,
					secondMilesPriceActive:
						modalData.priceConfig.secondMilesPriceActive,
					//---
					thirdMiles: modalData.priceConfig.thirdMiles,
					thirdMilesPrice: modalData.priceConfig.thirdMilesPrice,
					thirdMilesPriceActive:
						modalData.priceConfig.thirdMilesPriceActive,
					//---
					adminFeeLimitMiles:
						modalData.priceConfig.adminFee.limitMiles,
					adminFeeType: modalData.priceConfig.adminFee.type,
					adminFeePercentage:
						modalData.priceConfig.adminFee.percentage,
					adminFeeFixedAmount:
						modalData.priceConfig.adminFee.fixedAmount,
					adminFeeActive: modalData.priceConfig.adminFee.active,
					//---
					pickUpFeeLimitMiles:
						modalData.priceConfig.pickupFee.limitMiles,
					pickUpFeeType: modalData.priceConfig.pickupFee.type,
					pickUpFeePercentage:
						modalData.priceConfig.pickupFee.percentage,
					pickUpFeeFixedAmount:
						modalData.priceConfig.pickupFee.fixedAmount,
					pickUpFeeActive: modalData.priceConfig.pickupFee.active,
					//---
					maxLuggages: modalData.priceConfig.luggage.maxCapacity,
					freeLuggagesQuantity:
						modalData.priceConfig.luggage.freeQuantity,
					extraLuggagesPrice:
						modalData.priceConfig.luggage.extrasPrice,
					//---
					maxPassengers: modalData.priceConfig.passenger.maxCapacity,
					freePassengersQuantity:
						modalData.priceConfig.passenger.freeQuantity,
					extraPassengersPrice:
						modalData.priceConfig.passenger.extrasPrice,
				},
			};

			axios
				.post(baseURL + "/api/cars/create", payload)
				.then((res) => {
					var toastType = res.status === 200 ? "success" : "error";
					self.showToastNotification(toastType);
					self.onCloseCreateNewCar(true);
					self.fetchCarsList((showToast = false));
				})
				.catch((error) => {
					self.showToastNotification();
					self.onCloseCreateNewCar(true);
				});
		},
		editCar: function () {
			const self = this;

			self.$v.modals.editCar.$touch();
			if (self.$v.modals.editCar.$invalid) {
				return;
			}

			const modalData = { ...self.modals.editCar };

			const payload = {
				form: {
					carId: modalData.carId,
					carQuantity: modalData.carQuantity,
					carActive: modalData.carActive,
					//---
					openDoorPrice: modalData.openDoorPrice,
					//---
					firstMiles: modalData.firstMiles,
					firstMilesPrice: modalData.firstMilesPrice,
					firstMilesPriceActive: modalData.firstMilesPriceActive,
					//---
					secondMiles: modalData.secondMiles,
					secondMilesPrice: modalData.secondMilesPrice,
					secondMilesPriceActive: modalData.secondMilesPriceActive,
					//---
					thirdMiles: modalData.thirdMiles,
					thirdMilesPrice: modalData.thirdMilesPrice,
					thirdMilesPriceActive: modalData.thirdMilesPriceActive,
					//---
					adminFeeLimitMiles: modalData.adminFeeLimitMiles,
					adminFeeType: modalData.adminFeeType,
					adminFeePercentage: modalData.adminFeePercentage,
					adminFeeFixedAmount: modalData.adminFeeFixedAmount,
					adminFeeActive: modalData.adminFeeActive,
					//---
					pickUpFeeLimitMiles: modalData.pickUpFeeLimitMiles,
					pickUpFeeType: modalData.pickUpFeeType,
					pickUpFeePercentage: modalData.pickUpFeePercentage,
					pickUpFeeFixedAmount: modalData.pickUpFeeFixedAmount,
					pickUpFeeActive: modalData.pickUpFeeActive,
					//---
					maxLuggages: modalData.maxLuggages,
					freeLuggagesQuantity: modalData.freeLuggagesQuantity,
					extraLuggagesPrice: modalData.extraLuggagesPrice,
					//---
					maxPassengers: modalData.maxPassengers,
					freePassengersQuantity: modalData.freePassengersQuantity,
					extraPassengersPrice: modalData.extraPassengersPrice,
				},
			};

			axios
				.post(baseURL + "/api/cars/edit", payload)
				.then((res) => {
					var toastType = res.status === 200 ? "success" : "error";
					self.showToastNotification(toastType);
					self.clearCarModalState(true);
					self.fetchCarsList((showToast = false));
				})
				.catch((error) => {
					console.log(error);
					self.clearCarModalState(true);
				});
		},
		editConfig: function () {
			const self = this;

			self.$v.modals.editConfig.$touch();
			if (self.$v.modals.editConfig.$invalid) {
				return;
			}

			const modalData = { ...self.modals.editConfig };

			const payload = {
				form: {
					configId: modalData.configId,
					value: modalData.configValue,
					maximumQuantity: modalData.configMaximumQuantity,
					active: modalData.configActive,
					countable: modalData.configCountable,
				},
			};

			axios
				.post(baseURL + "/api/configurations/edit", payload)
				.then((res) => {
					var toastType = res.status === 200 ? "success" : "error";
					self.showToastNotification(toastType);
					self.clearConfigModalState(true);
					self.fetchConfigList((showToast = false));
				})
				.catch((error) => {
					console.log(error);
					self.clearConfigModalState(true);
				});
		},
		createConfig: function () {
			const self = this;

			self.$v.modals.addConfig.$touch();
			if (self.$v.modals.addConfig.$invalid) {
				return;
			}

			const modalData = { ...self.modals.addConfig };

			const payload = {
				form: {
					name: modalData.configName,
					value: modalData.configValue,
					maximumQuantity: modalData.configMaximumQuantity,
					type: modalData.configType,
				},
			};

			axios
				.post(baseURL + "/api/configurations/create", payload)
				.then((res) => {
					var toastType = res.status === 200 ? "success" : "error";
					self.showToastNotification(toastType);
					self.clearCreateConfigModalState(true);
					self.fetchConfigList((showToast = false));
				})
				.catch((error) => {
					console.log(error);
					self.clearCreateConfigModalState(true);
				});
		},
		onCloseCreateNewCar: function (clearModalState = false) {
			const self = this;

			self.showCreateCarModal = false;

			if (clearModalState) {
				self.$v.modals.createCar.$reset();
			}
		},
		resetConfigurations: function () {
			const self = this;

			const payload = {};

			axios
				.post(baseURL + "/api/configurations/reset", payload)
				.then((res) => {
					if (res.data.result) {
						self.fetchConfigList((showToast = false));
					}

					var toastType =
						res.data.result === true ? "success" : "error";
					self.showToastNotification(toastType, res.data.message);
				})
				.catch((error) => {
					var toastType = "error";
					self.showToastNotification(toastType);
				});
		},
	},
	computed: {
		errorMessage_firstMiles: function () {
			const self = this;

			if (!(self.$v.modals.editCar.firstMiles.required === true)) {
				return self.errorMessages.required;
			}

			if (
				!(self.$v.modals.editCar.firstMiles.decimal === true) ||
				!(self.$v.modals.editCar.firstMiles.minValue === true)
			) {
				return self.errorMessages.invalidValue;
			}
		},
		errorMessage_secondMiles: function () {
			const self = this;

			if (!(self.$v.modals.editCar.secondMiles.required === true)) {
				return self.errorMessages.required;
			}

			if (
				!(self.$v.modals.editCar.secondMiles.decimal === true) ||
				!(self.$v.modals.editCar.secondMiles.minValue === true)
			) {
				return self.errorMessages.invalidValue;
			}

			if (
				!(
					self.$v.modals.editCar.secondMiles
						.mustGreaterThanFirstMiles === true
				)
			) {
				return "Must be greater than First miles";
			}
		},
		errorMessage_thirdMiles: function () {
			const self = this;

			if (!(self.$v.modals.editCar.thirdMiles.required === true)) {
				return self.errorMessages.required;
			}

			if (
				!(self.$v.modals.editCar.thirdMiles.decimal === true) ||
				!(self.$v.modals.editCar.thirdMiles.minValue === true)
			) {
				return self.errorMessages.invalidValue;
			}

			if (!(self.$v.modals.editCar.thirdMiles.mustBeGreatest === true)) {
				return "Must be greater than First and Second miles";
			}
		},
	},
	validations: {
		modals: {
			editConfig: {
				configValue: {
					required: required,
				},
				configMaximumQuantity: {
					requiredIf: requiredIf(function () {
						return (
							this.$v.modals.editConfig.configCountable.$model ===
							"1"
						);
					}),
				},
				configCountable: {},
			},
			addConfig: {
				configName: {
					required: required,
				},
				configValue: {
					required: required,
				},
				configType: {
					required: required,
				},
				configMaximumQuantity: {
					requiredIf: requiredIf(function () {
						return (
							this.$v.modals.addConfig.configCountable.$model ===
							"1"
						);
					}),
				},
				configCountable: {},
			},
			editCar: {
				carQuantity: {
					required: required,
				},
				carActive: {},
				openDoorPrice: {
					required: required,
				},
				// First miles config
				firstMiles: {
					required: required,
					decimal: decimal,
					minValue: minValue(0),
				},
				firstMilesPrice: {
					required: required,
				},
				firstMilesPriceActive: {},
				// Second miles config
				secondMiles: {
					required: required,
					decimal: decimal,
					minValue: minValue(0),
					mustBeGreaterThanFirstMiles: function () {
						let firstMiles = parseFloat(
							this.$v.modals.editCar.firstMiles.$model
						);
						let secondMiles = parseFloat(
							this.$v.modals.editCar.secondMiles.$model
						);

						return secondMiles > firstMiles;
					},
				},
				secondMilesPrice: {
					required: required,
				},
				secondMilesPriceActive: {},
				// Third miles config
				thirdMiles: {
					required: required,
					decimal: decimal,
					minValue: minValue(0),
					mustBeGreatest: function () {
						let firstMiles = parseFloat(
							this.$v.modals.editCar.firstMiles.$model
						);
						let secondMiles = parseFloat(
							this.$v.modals.editCar.secondMiles.$model
						);
						let thirdMiles = parseFloat(
							this.$v.modals.editCar.thirdMiles.$model
						);

						return (
							thirdMiles > secondMiles && thirdMiles > firstMiles
						);
					},
				},
				thirdMilesPrice: {
					required: required,
				},
				thirdMilesPriceActive: {},
				// Admin fee
				adminFeeLimitMiles: {
					required: required,
				},
				adminFeeActive: {},
				adminFeeType: {
					required: required,
				},
				adminFeePercentage: {
					requiredIf: requiredIf(function () {
						return (
							this.$v.modals.editCar.adminFeeType.$model ===
							"percentage"
						);
					}),
				},
				adminFeeFixedAmount: {
					requiredIf: requiredIf(function () {
						return (
							this.$v.modals.editCar.adminFeeType.$model ===
							"fixed"
						);
					}),
				},
				// Pickup fee
				pickUpFeeLimitMiles: {
					required: required,
				},
				pickUpFeeActive: {},
				pickUpFeeType: {
					required: required,
				},
				pickUpFeePercentage: {
					requiredIf: requiredIf(function () {
						return (
							this.$v.modals.editCar.pickUpFeeType.$model ===
							"percentage"
						);
					}),
				},
				pickUpFeeFixedAmount: {
					requiredIf: requiredIf(function () {
						return (
							this.$v.modals.editCar.pickUpFeeType.$model ===
							"fixed"
						);
					}),
				},
				// Luggages
				maxLuggages: {
					required: required,
				},
				freeLuggagesQuantity: {
					required: required,
				},
				extraLuggagesPrice: {
					required: required,
				},
				// Passengers
				maxPassengers: {
					required: required,
				},
				freePassengersQuantity: {
					required: required,
				},
				extraPassengersPrice: {
					required: required,
				},
			},
			createCar: {
				carName: {
					required: required,
				},
				carSeats: {
					required: required,
					minValue: minValue(1),
				},
				carQuantity: {
					required: required,
					minValue: minValue(1),
				},
				carActive: {
					required: required,
				},
				priceConfig: {
					openDoorPrice: {
						required: required,
					},
					firstMiles: {
						required: required,
					},
					firstMilesPrice: {
						required: required,
					},
					firstMilesPriceActive: {},
					secondMiles: {
						required: required,
					},
					secondMilesPrice: {
						required: required,
					},
					secondMilesPriceActive: {},
					thirdMiles: {
						required: required,
					},
					thirdMilesPrice: {
						required: required,
					},
					thirdMilesPriceActive: {},
					adminFee: {
						limitMiles: {
							required: required,
						},
						type: {
							required: required,
						},
						percentage: {
							requiredIf: requiredIf(function () {
								return (
									this.$v.modals.createCar.priceConfig
										.adminFee.type.$model === "percentage"
								);
							}),
						},
						fixedAmount: {
							requiredIf: requiredIf(function () {
								return (
									this.$v.modals.createCar.priceConfig
										.adminFee.type.$model === "fixed"
								);
							}),
						},
						active: {},
					},
					pickupFee: {
						limitMiles: {
							required: required,
						},
						type: {
							required: required,
						},
						percentage: {
							requiredIf: requiredIf(function () {
								return (
									this.$v.modals.createCar.priceConfig
										.pickupFee.type.$model === "percentage"
								);
							}),
						},
						fixedAmount: {
							requiredIf: requiredIf(function () {
								return (
									this.$v.modals.createCar.priceConfig
										.pickupFee.type.$model === "fixed"
								);
							}),
						},
						active: {},
					},
					luggage: {
						maxCapacity: {
							required: required,
						},
						freeQuantity: {
							required: required,
						},
						extrasPrice: {
							required: required,
						},
					},
					passenger: {
						maxCapacity: {
							required: required,
						},
						freeQuantity: {
							required: required,
						},
						extrasPrice: {
							required: required,
						},
					},
				},
			},
		},
	},
});
