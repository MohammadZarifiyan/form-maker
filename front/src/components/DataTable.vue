<template>
	<div class="table-responsive">
		<table class="table table-striped table-hover" v-if="resource?.data?.length">
			<thead class="table-head">
			<slot name="head" :data-keys="dataKeys">
				<tr class="table-row">
					<th v-for="(key, index) in dataKeys" :key="index">{{ key }}</th>
					<th v-if="updatable || deletable">عملیات</th>
				</tr>
			</slot>
			</thead>
			<tbody class="table-body">
			<slot name="body" :data="resource.data">
				<tr class="table-row" v-for="(row, rowIndex) in resource.data" :key="rowIndex">
					<td v-for="(key, keyIndex) in dataKeys" :key="keyIndex">{{ row[key] }}</td>
					<td>
						<button class="btn btn-sm btn-outline-danger mx-1 mt-1" data-bs-toggle="modal" data-bs-target="#delete-modal" v-if="deletable" @click="$parent.selectedItem = row.id">
							<i class="bi bi-trash-fill"></i>
						</button>
						<button class="btn btn-sm btn-outline-warning mx-1 mt-1" data-bs-toggle="modal" data-bs-target="#update-modal" v-if="updatable" @click="$emit('editItem', row)">
							<i class="bi bi-pencil-fill"></i>
						</button>
					</td>
				</tr>
			</slot>
			</tbody>
		</table>
	</div>
</template>

<script>
export default {
	props: ["deletable", "updatable", "resource"],
	emits: ["editItem"],
	computed: {
		dataKeys() {
			return Object.keys(this.resource.data[0]);
		},
	},
}
</script>

<style scoped>
.table {
	width: 100%;
}

.table-head,
.table-row {
	display: table;
	width: 100%;
	table-layout: fixed;
}

.table-body {
	display: block;
	overflow-y: auto;
	table-layout: fixed;
	max-height: 400px;
}
</style>