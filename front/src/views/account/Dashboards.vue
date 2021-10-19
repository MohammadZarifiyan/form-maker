<template>
	<account title="داشبورد ها">
		<div class="table-responsive text-center bg-white rounded-3 shadow-sm px-4">
			<div class="flex flex-row mb-2 text-end mt-3">
				<p class="text-end d-inline-block">داشبورد ها</p>
				<button class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#create-modal">
					<i class="bi bi-plus-square-dotted"></i>
					<span class="me-2">مورد جدید</span>
				</button>
			</div>
			<table class="table table-striped" v-if="resource.data?.length">
				<thead class="table-head">
					<tr>
						<th>#</th>
						<th>عنوان</th>
						<th>عملیات</th>
					</tr>
				</thead>
				<tbody class="table-body">
					<template v-for="(dashboard, index) in resource.data" :key="index">
						<tr class="table-row">
							<td>{{ dashboard.id }}</td>
							<td>{{ dashboard.title }}</td>
							<td>
								<button class="btn btn-sm btn-outline-danger mx-1 mt-1" data-bs-toggle="modal" data-bs-target="#delete-modal" @click="selectedItem = dashboard.id">
									<i class="bi bi-trash-fill"></i>
								</button>
								<button class="btn btn-sm btn-outline-warning mx-1 mt-1" data-bs-toggle="modal" data-bs-target="#update-modal" @click="editItem(dashboard)">
									<i class="bi bi-pencil-fill"></i>
								</button>
								<button class="btn btn-sm btn-outline-success mx-1 mt-1" @click="$router.push('/account/dashboards/' + dashboard.id)" v-if="dashboard.queries.length > 0">
									<i class="bi bi-caret-right-fill"></i>
								</button>
							</td>
						</tr>
						<tr class="table-row" v-if="dashboard.queries.length > 0">
							<td colspan="3">
								<table class="table">
									<thead class="table-head">
										<tr>
											<th>#</th>
											<th>SQL</th>
											<th>اتصال</th>
										</tr>
									</thead>
									<tbody class="table-body">
										<tr v-for="(query, index) in dashboard.queries" :key="index">
											<td>{{ query.id }}</td>
											<td dir="ltr">{{ query.sql }}</td>
											<td>{{ `${query.connection.database} (${query.connection.username}@${query.connection.host})` }}</td>
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
	</account>

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
					<label class="text-end d-block mb-2 form-label" for="title">عنوان</label>
					<input type="text" id="title" class="form-control form-control-sm" minlength="3" maxlength="10" v-model="datasets['create-form'].title" />
					<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.title"></small>
				</div>
				<div class="mb-2">
					<label class="text-end d-block mb-2 form-label">پروژه</label>
					<select id="project_id" class="form-select form-select-sm" v-model="datasets['create-form'].project_id">
						<option v-for="(project, index) in projectResource.data" :key="index" :value="project.id">{{ project.title }}</option>
					</select>
					<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.project_id"></small>
				</div>
				<div class="mb-2">
					<label class="text-end d-block mb-2 form-label">کوئری ها</label>
					<select class="form-select form-select-sm" v-model="datasets['create-form'].queries" multiple>
						<option v-for="(query, index) in queryResource.data" :key="index" :value="query.id">{{ query.sql }}</option>
					</select>
					<small class="text-danger d-block text-end mt-1" v-html="errors['create-form']?.queries"></small>
				</div>
			</form>
		</div>
		<div class="modal-footer flex flex-row justify-content-start">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
			<button type="submit" form="creation-form" class="btn btn-outline-success" @click.prevent="submitResource()">تایید</button>
		</div>
	</modal>

	<modal id="update-modal">
		<div class="modal-header">
			<h5 class="modal-title" id="update-modal-title">ویرایش</h5>
			<button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<form id="update-form">
				<div class="mb-2">
					<label class="text-end d-block mb-2 form-label">عنوان</label>
					<input type="text" class="form-control form-control-sm" minlength="3" maxlength="10" v-model="datasets['update-form'].title" required />
					<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.title"></small>
				</div>
				<div class="mb-2">
					<label class="text-end d-block mb-2 form-label">پروژه</label>
					<select class="form-select form-select-sm" v-model="datasets['update-form'].project_id">
						<option v-for="(project, index) in projectResource.data" :key="index" :value="project.id">{{ project.title }}</option>
					</select>
					<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.project_id"></small>
				</div>
				<div class="mb-2">
					<label class="text-end d-block mb-2 form-label">کوئری ها</label>
					<select class="form-select form-select-sm" v-model="datasets['update-form'].queries" multiple>
						<option v-for="(query, index) in queryResource.data" :key="index" :value="query.id">{{ query.sql }}</option>
					</select>
					<small class="text-danger d-block text-end mt-1" v-html="errors['update-form']?.queries"></small>
				</div>
			</form>
		</div>
		<div class="modal-footer flex flex-row justify-content-start">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
			<button type="submit" form="creation-form" class="btn btn-outline-success" @click.prevent="updateItem()">تایید</button>
		</div>
	</modal>
</template>

<script>
import axios from "@/modules/axios";
import { hideModal } from "@/helpers";

export default {
	data() {
		return {
			resource: [],
			projectResource: {},
			queryResource: {},
			errors: {},
			selectedItem: null,
			datasets: {
				"create-form": {
					title: '',
					project_id: null,
					queries: [],
				},
				"update-form": {},
			},
		};
	},
	created() {
		this.fetchProjects();
		this.fetchQueries();
		this.fetchResource();
	},
	methods: {
		editItem(row) {
			this.datasets['update-form'] = {
				title: row.title,
				project_id: row.project_id,
				queries: row.queries.map(query => query.id),
			};

			this.selectedItem = row.id;
		},
		async fetchResource() {
			try {
				const response = await axios.get("account/forms", {
					params: {
						autoload: true,
					},
				});

				this.resource = response.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async fetchProjects() {
			try {
				const response = await axios.get("account/projects");

				this.projectResource = response.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async fetchQueries() {
			try {
				const response = await axios.get("account/queries");

				this.queryResource = response.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async deleteItem() {
			try {
				await axios.delete("account/forms/" + this.selectedItem);

				this.resource.data = this.resource.data.filter(item => item.id !== this.selectedItem);

				hideModal("delete-modal");
			} catch (e) {
				console.log("We are out of service");
			}
		},
		async updateItem() {
			try {
				let dataset = this.datasets["update-form"];

				dataset.autoload = true;

				await axios.put("account/forms/" + this.selectedItem, dataset);

				this.resource.data = this.resource.data.map(item => {
					if (item.id === this.selectedItem) {
						item.title = dataset.title;
						item.project_id = dataset.project_id;
						item.queries = dataset.queries.map(query_id => {
							const filtered_queries = this.queryResource.data.filter(query => query.id === query_id);

							return filtered_queries[0];
						});
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
		async submitResource() {
			try {
				let dataset = this.datasets["create-form"];

				dataset.autoload = true;

				const response = await axios.post("account/forms", dataset);

				this.resource.data.push(response.data.data);

				hideModal("create-modal");
			} catch (error) {
				if (error.response.status === 422) {
					this.errors["create-form"] = error.response.data.errors;
				}
			}
		},
	},
};
</script>
