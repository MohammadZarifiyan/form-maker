<template>
	<account :title="form.title ?? 'در حال بارگیری ...'">
		<div class="text-center bg-white rounded-3 shadow-sm px-4 py-2" v-for="(query, index) in form.queries" :key="index">
			<div class="flex flex-row mb-2 text-end">
				<p class="text-end d-inline-block">کوئری #<span :class="query.status ? 'text-primary' : 'text-danger'">{{ query.id }}</span></p>
				<button class="btn btn-sm btn-primary me-2" :form="`query-${query.id}-form`">{{ query.button_title }}</button>
			</div>
			<div class="px-4 py-3 bg-light rounded-3">
				<form @submit.prevent="runQuery(query)" :id="`query-${query.id}-form`">
					<div class="row">
						<div class="mb-2 col-14 col-sm-7 col-xl-2" v-for="(parameter, parameterIndex) in query.parameters" :key="parameterIndex">
							<label class="form-label d-block text-end">{{ parameter.title }}</label>
							<input
								:type="getParameterType(parameter.type)"
								:class="getLabelClasses(parameter.type)"
								:placeholder="parameter.title"
								:name="`query-${query.id}-key-${parameter.key}`"
							/>
							<small class="text-danger d-block text-end mt-2" :id="`query-${query.id}-key-${parameter.key}`"></small>
						</div>
					</div>
				</form>
				<data-table
					v-if="query.id in results && query.type === 'table'"
					:resource="results[query.id]"
				></data-table>
				<apexchart
					v-else-if="query.id in results && query.type === 'chart'"
					height="250"
					type="line"
					:options="getChartOptions(query.id)"
					:series="getChartSeries(query.id)"
				></apexchart>
			</div>
		</div>
	</account>
</template>

<script>
import axios from "@/modules/axios";

export default {
	data() {
		return {
			form: {},
			errors: {},
			results: {},
		};
	},
	created() {
		this.verifyFormId();
		this.fetchForm();
	},
	methods: {
		async verifyFormId() {
			const form_id = this.$route.params.form_id;

			const { data } = await axios("account/forms/" + form_id + "/check", {
				params: {
					autoload: false,
				},
			});

			if (!data.data.exists) {
				await this.$router.push("/account/forms");
			}
		},
		async fetchForm() {
			try {
				const { data } = await axios.get("account/forms/" + this.$route.params.form_id)

				this.form = data.data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		getParameterType(type) {
			switch (type) {
				case "integer":
					return "number";

				case "string":
					return "text";

				case "boolean":
					return "checkbox";
			}
		},
		getLabelClasses(type) {
			switch (type) {
				case "integer":
				case "string":
					return "form-control form-control-sm";

				case "boolean":
					return "form-check";
			}
		},
		collectValues(query) {
			let values = {};

			for (let parameter of query.parameters) {
				let element = document.getElementsByName(`query-${query.id}-key-${parameter.key}`);

				values[parameter.key] = element[0].type === "checkbox" ? element[0].value === "on" : element[0].value;
			}

			return values;
		},
		async runQuery(query) {
			try {
				let dataset = {
					values: this.collectValues(query),
				};

				const response = await axios.post(`account/queries/${query.id}/run`, dataset);

				this.results[query.id] = response.data;
			} catch (error) {
				if (error.response.status === 422) {
					for (let input_error in error.response.data.errors) {
						const parameter_key = input_error.split('.').slice(-1)[0];

						document.getElementById(`query-${query.id}-key-${parameter_key}`).innerText = error.response.data.errors[input_error];
					}
				}
			}
		},
		getChartOptions(id) {
			let categories = [];

			if ("data" in this.results[id]) {
				const labels = this.getResultLabels(id);

				for (let item of this.results[id].data) {
					categories.push(item[labels[0]]);
				}
			}

			return {
				xaxis: {
					categories,
				},
			};
		},
		getChartSeries(id) {
			let data = [];

			if ("data" in this.results[id]) {
				const labels = this.getResultLabels(id);

				for (let item of this.results[id].data) {
					data.push(item[labels[1]]);
				}
			}

			return [
				{
					name: "series-1",
					data,
				},
			];
		},
		getResultLabels(id) {
			return this.results[id]?.data?.length > 0 ? Object.keys(this.results[id].data[0]) : [];
		},
	},
};
</script>
