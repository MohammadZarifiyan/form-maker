<template>
	<account title="پروژه ها">
		<resource-table api-path="account/projects" title="لیست پروژه ها" :creation-inputs="creationInputs" :update-inputs="creationInputs">
			<template #head>
				<tr>
					<th>#</th>
					<th>عنوان</th>
					<th>عملیات</th>
				</tr>
			</template>
		</resource-table>
	</account>
</template>

<script>
export default {
	computed: {
		creationInputs() {
			return [
				{
					element: "input",
					attributes: {
						class: "form-control form-control-sm",
						minlength: 3,
						maxlength: 15,
						required: true,
					},
					name: "title",
					label: "عنوان",
				},
			];
		},
	},
};
</script>
