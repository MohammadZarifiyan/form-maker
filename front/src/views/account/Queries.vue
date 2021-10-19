<template>
	<account title="کوئری ها">
		<div class="table-responsive text-center bg-white rounded-3 shadow-sm px-4">
			<div class="flex flex-row mb-2 text-end mt-3">
				<p class="text-end d-inline-block">کوئری ها</p>
				<button class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#create-modal">
					<i class="bi bi-plus-square-dotted"></i>
					<span class="me-2">مورد جدید</span>
				</button>
			</div>
			<table class="table table-striped" v-if="resource.data?.length">
				<thead class="table-head">
					<tr>
						<th>#</th>
						<th>SQL</th>
						<th>عنوان دکمه</th>
						<th>نوع نمایش</th>
						<th>عملیات</th>
					</tr>
				</thead>
				<tbody class="table-body">
					<template v-for="(query, index) in resource.data" :key="index">
						<tr class="table-row" :class="{'bg-danger bg-opacity-10': !query.status}">
							<td>{{ query.id }}</td>
							<td dir="ltr">{{ query.sql }}</td>
							<td>{{ query.button_title }}</td>
							<td>{{ getPersianType(query.type) }}</td>
							<td>
								<button class="btn btn-sm btn-outline-danger mx-1 mt-1" data-bs-toggle="modal" data-bs-target="#delete-modal" @click="selectedItem = query.id">
									<i class="bi bi-trash-fill"></i>
								</button>
								<button class="btn btn-sm btn-outline-warning mx-1 mt-1" data-bs-toggle="modal" data-bs-target="#update-modal" @click="editItem(query)">
									<i class="bi bi-pencil-fill"></i>
								</button>
							</td>
						</tr>
						<tr v-if="query.parameters.length > 0">
							<td colspan="5">
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>عنوان</th>
											<th>کلید</th>
											<th>نوع</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(parameter, index) in query.parameters" :key="index">
											<th>{{ index + 1 }}</th>
											<th>{{ parameter.title }}</th>
											<th>{{ parameter.key }}</th>
											<th>{{ parameter.type }}</th>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</template>
				</tbody>
			</table>
			<div v-else>
				<p>چیزی برای نمایش وجود ندارد.</p>
			</div>
		</div>

		<modal id="delete-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="delete-modal-title">حذف</h5>
				<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				این عملیات قابل بازگشت نمی باشد، از انجام آن مطمئنید؟
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
				<button type="button" class="btn btn-outline-danger" @click="deleteItem()">بله</button>
			</div>
		</modal>

		<modal id="create-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="create-modal-title">مورد جدید</h5>
				<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="creation-form">
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label">اتصال</label>
						<select class="form-select form-select-sm" v-model="datasets['create-form'].connection_id">
							<option v-for="(connection, index) in connections" :key="index" :value="connection.id">{{ `${connection.database} (${connection.username}@${connection.host})` }}</option>
						</select>
						<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.connection_id"></small>
					</div>
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label" for="button_title">عنوان دکمه</label>
						<input type="text" id="button_title" class="form-control form-control-sm" minlength="6" v-model="datasets['create-form'].button_title" required />
						<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.button_title"></small>
					</div>
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label" for="sql">SQL</label>
						<input type="text" id="sql" class="form-control form-control-sm" minlength="6" dir="ltr" v-model="datasets['create-form'].sql" required />
						<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.sql"></small>
					</div>
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label">نوع نتیجه</label>
						<select id="project_id" class="form-control form-control-sm" v-model="datasets['create-form'].type">
							<option value="table">جدول</option>
							<option value="chart">نمودار</option>
						</select>
						<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.type"></small>
					</div>
					<parameter
						v-for="(parameter, index) in datasets['create-form'].parameters"
						:parameter="parameter"
						:title-error="getNestedError('create-form', index, 'parameters', 'title')"
						:key-error="getNestedError('create-form', index, 'parameters', 'key')"
						:type-error="getNestedError('create-form', index, 'parameters', 'type')"
						@delete="deleteParameter('create-form', index)"
					></parameter>
					<button class="btn btn-sm btn-outline-primary w-100" @click="addParameter('create-form')">پارامتر جدید</button>
				</form>
			</div>
			<div class="modal-footer flex flex-row justify-content-start">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
				<button type="submit" form="creation-form" class="btn btn-outline-success" @click.prevent="submitResource()">تایید</button>
			</div>
		</modal>

		<modal id="update-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="update-modal-title">مورد جدید</h5>
				<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="update-form">
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label">اتصال</label>
						<select class="form-select form-select-sm" v-model="datasets['update-form'].connection_id">
							<option v-for="(connection, index) in connections" :key="index" :value="connection.id">{{ `${connection.database} (${connection.username}@${connection.host})` }}</option>
						</select>
						<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.connection_id"></small>
					</div>
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label">عنوان دکمه</label>
						<input type="text" class="form-control form-control-sm" minlength="6" v-model="datasets['update-form'].button_title" required />
						<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.button_title"></small>
					</div>
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label">SQL</label>
						<input type="text" class="form-control form-control-sm" minlength="6" dir="ltr" v-model="datasets['update-form'].sql" required />
						<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.sql"></small>
					</div>
					<div class="mb-2">
						<label class="text-end d-block mb-2 form-label">نوع نتیجه</label>
						<select id="project_id" class="form-control form-control-sm" v-model="datasets['update-form'].type">
							<option value="table">جدول</option>
							<option value="chart">نمودار</option>
						</select>
						<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.type"></small>
					</div>
					<parameter
						v-for="(parameter, index) in datasets['update-form'].parameters"
						:parameter="parameter"
						:title-error="getNestedError('update-form', index, 'parameters', 'title')"
						:key-error="getNestedError('update-form', index, 'parameters', 'key')"
						:type-error="getNestedError('update-form', index, 'parameters', 'type')"
						@delete="deleteParameter('update-form', index)"
					></parameter>
					<button class="btn btn-sm btn-outline-primary w-100" @click="addParameter('update-form')">پارامتر جدید</button>
				</form>
			</div>
			<div class="modal-footer flex flex-row justify-content-start">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click-="">انصراف</button>
				<button type="submit" form="creation-form" class="btn btn-outline-success" @click.prevent="updateItem()">تایید</button>
			</div>
		</modal>
	</account>
</template>

<script>
import axios from "@/modules/axios";
import {hideModal} from "@/helpers";

export default {
	data() {
		return {
			connections: [],
			resource: [],
			errors: {
				"create-form": {},
				"update-form": {},
			},
			selectedItem: null,
			datasets: {
				"create-form": {
					sql: '',
					type: "table",
					parameters: [],
					button_title: '',
					connection_id: null,
				},
				"update-form": {},
			},
		};
	},
	created() {
		this.fetchData();
		this.fetchResource();
	},
	methods: {
		deleteParameter(id, index) {
			this.datasets[id].parameters.splice(index, 1);
		},
		addParameter(id) {
			this.datasets[id].parameters.push({
				title: '',
				key: '',
				type: "integer",
			});
		},
		editItem(row) {
			this.datasets['update-form'] = {
				connection_id: row.connection.id,
				sql: row.sql,
				type: row.type,
				parameters: [...row.parameters],
				button_title: row.button_title,
			};

			this.selectedItem = row.id;
		},
		async fetchResource() {
			try {
				const response = await axios.get("account/queries");

				this.resource = response.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async fetchData() {
			try {
				const response = await axios.get("account/connections");

				this.connections = response.data.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async deleteItem() {
			try {
				await axios.delete("account/queries/" + this.selectedItem);

				this.resource.data = this.resource.data.filter(item => item.id !== this.selectedItem);

				hideModal("delete-modal");
			} catch (e) {
				console.log("We are out of service");
			}
		},
		getPersianType(type) {
			switch (type) {
				case "table":
					return "جدول";

				case "chart":
					return "نمودار";
			}
		},
		async submitResource() {
			try {
				let dataset = this.datasets["create-form"];

				const response = await axios.post("account/queries", dataset);

				this.resource.data.push(response.data.data);

				hideModal("create-modal");
			} catch (error) {
				if (error.response.status === 422) {
					this.errors["create-form"] = error.response.data.errors;
				}
			}
		},
		async updateItem() {
			try {
				let dataset = this.datasets["update-form"];

				await axios.put("account/queries/" + this.selectedItem, dataset);

				this.resource.data = this.resource.data.map(item => {
					if (item.id === this.selectedItem) {
						item.sql = dataset.sql;
						item.button_title = dataset.button_title;
						item.parameters = dataset.parameters;
						item.type = dataset.type;
						item.status = true;
					}

					return item;
				});

				hideModal("update-modal");
			} catch (error) {
				if (error.response.status === 422) {
					this.errors["update-form"] = error.response.data.errors;
				}
			}
		},
		getNestedError(id, index, name, key) {
			const form_errors = this.errors[id];

			const ch = name + '.' + index + '.' + key;

			return ch in form_errors ? form_errors[ch] : null;
		},
	},
};
</script>
