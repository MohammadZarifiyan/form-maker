<template>
	<li class="py-2 px-3 my-2" :class="{'bg-secondary bg-opacity-25 rounded-3': isCurrentRoute(routePath)}">
		<router-link :to="routePath" class="text-white text-decoration-none">{{ title }}</router-link>
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