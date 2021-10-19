<template>
	<li class="nav-item">
		<router-link :to="routePath" class="nav-link" :class="{'active': isCurrentRoute(routePath)}">{{ title }}</router-link>
	</li>
</template>

<script>
export default {
	props: {
		title: {
			type: String,
			required: true,
		},
		routePath: {
			type: String,
			required: true,
		},
	},
	methods: {
		isCurrentRoute() {
			return this.routePath === this.$route.path;
		},
	},
};
</script>
