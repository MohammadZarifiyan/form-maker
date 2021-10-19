<template>
	<hr>
	<div class="mb-2">
		<label class="text-end d-block mb-2 form-label">عنوان</label>
		<input type="text" class="form-control form-control-sm" minlength="2" maxlength="20" required v-model="parameter.title" />
		<small class="text-danger d-block text-end mt-1" v-html="$props['titleError']"></small>
	</div>
	<div class="mb-2">
		<label class="text-end d-block mb-2 form-label">کلید</label>
		<input type="text" class="form-control form-control-sm" dir="ltr" minlength="2" maxlength="20" required v-model="parameter.key" />
		<small class="text-danger d-block text-end mt-1" v-html="$props['keyError']"></small>
	</div>
	<div class="row flex align-items-end mb-2">
		<div class="col">
			<label class="text-end d-block mb-2 form-label">نوع</label>
			<select class="form-select form-select-sm" v-model="parameter.type">
				<option value="integer" dir="ltr">Integer</option>
				<option value="boolean" dir="ltr">Boolean</option>
				<option value="string" dir="ltr">String</option>
			</select>
			<small class="text-danger d-block text-end mt-1" v-html="$props['typeError']"></small>
		</div>
		<div class="col-3">
			<button class="btn btn-sm btn-danger mb-1" @click="$emit('delete')">
				<i class="bi bi-trash-fill"></i>
			</button>
		</div>
	</div>
</template>

<script>
export default {
	props: ["parameter", "title-error", "key-error", "type-error"],
	emits: ["delete"],
};
</script>
