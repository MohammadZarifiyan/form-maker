<template>
	<account :title="resource?.data?.title ?? 'در حال بارگیری ...'">
		<div class="text-center bg-white rounded-3 shadow-sm px-4 py-2" v-for="(query, index) in resource.data.queries" :key="index" v-if="resource?.data?.queries?.length > 0">
			<div class="flex flex-row mb-2 text-end">
				<p class="text-end d-inline-block">کوئری #<span :class="query.status ? 'text-primary' : 'text-danger'">{{ query.id }}</span></p>
			</div>
			<div class="px-4 py-3 bg-light rounded-3">
				<data-table
					v-if="query.id in resource.queries_result && query.type === 'table'"
					:resource="resource.queries_result[query.id]"
				></data-table>
				<apexchart
					v-else-if="query.id in resource.queries_result && query.type === 'chart'"
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
			resource: {},
		};
	},
	created() {
		this.verifyDashboardId();
		this.fetchDashboard();
	},
	methods: {
		async verifyDashboardId() {
			const dashboard_id = this.$route.params.dashboard_id;

			const { data } = await axios("account/forms/" + dashboard_id + "/check", {
				params: {
					autoload: true,
				},
			});

			if (!data.data.exists) {
				await this.$router.push("/account/dashboards");
			}
		},
		async fetchDashboard() {
			try {
				const { data } = await axios.get("account/forms/" + this.$route.params.dashboard_id)

				console.log(data)

				this.resource = data;
			} catch (e) {
				console.log("We are out of service");
			}
		},
		getChartOptions(id) {
			let categories = [];

			if ("data" in this.resource.queries_result[id]) {
				const labels = this.getResultLabels(id);

				for (let item of this.resource.queries_result[id].data) {
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

			if ("data" in this.resource.queries_result[id]) {
				const labels = this.getResultLabels(id);

				for (let item of this.resource.queries_result[id].data) {
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
			return this.resource.queries_result[id]?.data?.length > 0 ? Object.keys(this.resource.queries_result[id]?.data[0]) : [];
		},
	},
};
</script>
