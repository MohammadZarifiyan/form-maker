<template>
	<div class="container-fluid">
		<div class="row d-sm-none">
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient">
				<div class="container-fluid">
					<button class="navbar-toggler text-center px-0" style="width: 3rem;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
						<ul class="navbar-nav me-auto my-2 mb-lg-0">
							<navbar-item title="ارتباطات" route-path="/account/connections"></navbar-item>
							<navbar-item title="کوئری ها" route-path="/account/queries"></navbar-item>
							<navbar-item title="پروژه ها" route-path="/account/projects"></navbar-item>
							<navbar-item title="داشبورد ها" route-path="/account/dashboards"></navbar-item>
							<navbar-item title="فرم ها" route-path="/account/forms"></navbar-item>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="row">
			<div class="col-sm-4 col-md-3 col-lg-2 d-none d-sm-block vh-100 bg-dark bg-gradient position-sticky fixed-top">
				<nav>
					<ul class="list-unstyled px-2 py-2">
						<sidebar-item title="ارتباطات" route-path="/account/connections"></sidebar-item>
						<sidebar-item title="کوئری ها" route-path="/account/queries"></sidebar-item>
						<sidebar-item title="پروژه ها" route-path="/account/projects"></sidebar-item>
						<sidebar-item title="داشبورد ها" route-path="/account/dashboards"></sidebar-item>
						<sidebar-item title="فرم ها" route-path="/account/forms"></sidebar-item>
					</ul>
				</nav>
			</div>
			<div class="col-sm-10 col-md-11 col-lg-12">
				<div class="row mt-3 mb-4">
					<div class="col">
						<h5>{{ title }}</h5>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<slot></slot>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		title: {
			type: String,
			required: true,
		},
	},
};
</script>

<style lang="scss" scoped>
.button:hover {

}
</style>
