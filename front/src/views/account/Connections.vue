<template>
	<account title="ارتباطات">
		<resource-table api-path="account/connections" title="لیست ارتباطات" :creation-inputs="creationInputs" :update-inputs="creationInputs">
			<template #head>
				<tr>
					<th>#</th>
					<th>میزبان</th>
					<th>پورت</th>
					<th>دیتابیس</th>
					<th>نام کاربری</th>
					<th>رمز عبور</th>
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
						minlength: 7,
						maxlength: 15,
						required: true,
						dir: "ltr",
					},
					name: "host",
					label: "میزبان",
				},
				{
					element: "input",
					attributes: {
						type: "number",
						class: "form-control form-control-sm",
						min: 1,
						max: 16777215,
						required: true,
						dir: "ltr",
					},
					name: "port",
					label: "پورت",
				},
				{
					element: "input",
					attributes: {
						class: "form-control form-control-sm",
						required: true,
						dir: "ltr",
					},
					name: "database",
					label: "دیتابیس",
				},
				{
					element: "input",
					attributes: {
						class: "form-control form-control-sm",
						required: true,
						dir: "ltr",
					},
					name: "username",
					label: "نام کاربری",
				},
				{
					element: "input",
					attributes: {
						class: "form-control form-control-sm",
						type: "password",
						dir: "ltr",
					},
					name: "password",
					label: "رمز عبور",
				},
			];
		},
	},
};
</script>
