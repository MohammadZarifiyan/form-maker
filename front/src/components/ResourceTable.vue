<template>
	<div class="text-center bg-white rounded-3 shadow-sm px-4 py-1">
		<div class="flex flex-row mb-2 text-end mt-3">
			<p class="text-end d-inline-block">{{ title }}</p>
			<button class="btn btn-sm btn-success me-2" v-if="creationInputs" data-bs-toggle="modal" data-bs-target="#create-modal">
				<i class="bi bi-plus-square-dotted"></i>
				<span class="me-2">مورد جدید</span>
			</button>
		</div>
		<data-table
			v-if="resource?.data?.length"
			:updatable="updateInputs?.length > 0"
			:deletable="deletable"
			:resource="resource"
			@edit-item="row => editItem(row)"
		></data-table>
		<div v-else>
			<p>چیزی برای نمایش وجود ندارد.</p>
		</div>

		<modal id="delete-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="delete-modal-title">حذف</h5>
				<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close" @click="cancelDelete()"></button>
			</div>
			<div class="modal-body">
				این عملیات قابل بازگشت نمی باشد، از انجام آن مطمئنید؟
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" @click="cancelDelete()" data-bs-dismiss="modal">انصراف</button>
				<button type="button" class="btn btn-outline-danger" @click="deleteSelectedItem()">بله</button>
			</div>
		</modal>

		<modal id="create-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="create-modal-title">مورد جدید</h5>
				<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close" @click="cancelCreate()"></button>
			</div>
			<div class="modal-body">
				<form id="creation-form"></form>
			</div>
			<div class="modal-footer flex flex-row justify-content-start">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cancelCreate()">انصراف</button>
				<button type="submit" form="creation-form" class="btn btn-outline-success" @click.prevent="submitResource()">تایید</button>
			</div>
		</modal>

		<modal id="update-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="update-modal-title">ویرایش</h5>
				<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close" @click="cancelEdit()"></button>
			</div>
			<slot name="update">
				<div class="modal-body">
					<form id="update-form"></form>
				</div>
				<div class="modal-footer flex flex-row justify-content-start">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cancelEdit()">انصراف</button>
					<button type="submit" form="update-form" class="btn btn-outline-success" @click.prevent="updateResource()">تایید</button>
				</div>
			</slot>
		</modal>
	</div>
</template>

<script>
import axios from "@/modules/axios";
import {hideModal} from "@/helpers";

export default {
	props: {
		apiPath: {
			type: String,
			required: true,
		},
		deleteApiPath: String,
		createApiPath: String,
		updateInputs: Array,
		deletable: {
			type: Boolean,
			default: true,
		},
		title: {
			type: String,
			required: true,
		},
		creationInputs: Array,
	},
	data() {
		return {
			resource: {},
			selectedItem: null,
			errors: {
				"creation-form": {},
				"update-form": {},
			},
			datasets: {
				"creation-form": {},
				"update-form": {},
			},
		};
	},
	created() {
		this.fetchData();
	},
	mounted() {
		if (this.creationInputs) {
			this.createForm("creation-form");
		}
	},
	methods: {
		editItem(row) {
			for (let input of this.updateInputs) {
				this.datasets["update-form"][input.name] = row[input.name];
			}
			
			this.selectedItem = row.id;

			this.createForm("update-form");
		},
		cancelEdit() {
			this.selectedItem = null;

			this.datasets["update-form"] = {}
		},
		cancelCreate() {
			this.datasets["creation-form"] = {}
		},
		cancelDelete() {
			this.datasets["creation-form"] = {}
		},
		async fetchData() {
			try {
				const response = await axios.get(this.apiPath);

				this.resource = await response.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async submitResource() {
			try {
				const path = this.createApiPath ?? this.apiPath;

				const response = await axios.post(path, this.datasets["creation-form"]);

				this.resource.data.push(response.data.data);

				this.errors["creation-form"] = {};

				hideModal("create-modal");

				this.cancelCreate();
			} catch (error) {
				if (error.response.status === 422) {
					this.errors["creation-form"] = error.response.data.errors;
				}
			}
		},
		async updateResource() {
			try {
				await axios.put(this.apiPath + "/" + this.selectedItem, this.datasets["update-form"]);

				this.resource.data = await this.resource.data.map(item => item.id === this.selectedItem ? this.getUpdatedData(item) : item);

				this.errors["update-form"] = {};

				hideModal("update-modal");

				this.cancelEdit();
			} catch (error) {
				if (error.response.status === 422) {
					this.errors["update-form"] = error.response.data.errors;
				}
			}
		},
		getUpdatedData(row) {
			let fresh_data = {};

			const form_dataset = this.datasets["update-form"];

			for (let input in row) {
				fresh_data[input] = input in form_dataset ? form_dataset[input] : row[input];
			}

			return fresh_data;
		},
		createFormDiv() {
			const div = document.createElement("div");

			div.classList.add("mb-2");

			return div;
		},
		createFormLabel(text) {
			const label = document.createElement("label");

			label.setAttribute("class", "text-end d-block mb-2 form-label");

			label.innerText = text;

			return label;
		},
		createFormField(element, attributes, formId = null, name = null) {
			const field = document.createElement(element);

			for (let attribute in attributes) {
				field.setAttribute(attribute, attributes[attribute]);
			}

			switch (element) {
				case "input":
					field.setAttribute("value", this.datasets[formId][name] ?? "");

					field.addEventListener("input", event => this.datasets[formId][name] = event.target.value);

					break;

				case "select":
					field.addEventListener("input", event => this.setSelectDatasetValue(formId, name, attributes, event.target.selectedOptions));

					break;
			}

			return field;
		},
		setSelectDatasetValue(formId, name, attributes, selectedOptions) {
			let values = [];

			for (let selectedOption of selectedOptions) {
				values.push(selectedOption.value);
			}

			this.datasets[formId][name] = attributes.hasOwnProperty("multiple") && attributes.multiple ? values : values[0];
		},
		createSelectOption(text, attributes) {
			const option = this.createFormField("option", attributes);

			option.innerText = text;

			return option;
		},
		createFormErrorContainer(formId, name) {
			const errorContainer = document.createElement("small");

			errorContainer.setAttribute("class", "text-danger d-block text-end mt-1");

			errorContainer.innerText = this.errors[formId] && name in this.errors[formId] ? this.errors[formId][name] : "";

			return errorContainer;
		},
		createForm(id) {
			const form = document.getElementById(id);

			form.innerText = "";

			this.creationInputs.map(input => {
				const div = this.createFormDiv();
				const label = this.createFormLabel(input.label);
				const field = this.createFormField(input.element, input.attributes, id, input.name);

				if (input.element === "select") {
					for (let option of input.options) {
						let option_tag = this.createSelectOption(option.text, option.attributes);

						field.appendChild(option_tag);
					}

					this.setSelectDatasetValue(id, input.name, input.attributes, field.selectedOptions);
				}

				const errorContainer = this.createFormErrorContainer(id, input.name);

				div.appendChild(label);
				div.appendChild(field);
				div.appendChild(errorContainer);
				form.appendChild(div);
			})
		},
		async deleteSelectedItem() {
			try {
				const path = this.deleteApiPath ?? this.apiPath;

				await axios.delete(path + "/" + this.selectedItem);

				this.resource.data = this.resource.data.filter(item => item.id !== this.selectedItem);
			} finally {
				hideModal("delete-modal");
			}
		},
	},
	watch: {
		"errors.creation-form": function () {
			this.createForm("creation-form");
		},
		"errors.update-form": function () {
			this.createForm("update-form");
		},
	},
};
</script>
