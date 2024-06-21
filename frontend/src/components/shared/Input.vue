<template>
    <input
        v-model="model"
        :placeholder="placeholder"
        :id="props.id"
        :name="props.name"
        :type="props.type"
        class="w-full h-12 border p-2 rounded text-xs" />
</template>

<script setup>
const model = defineModel()

const props = defineProps({
    id: [String, Number],
    name: [String, Number],
    type: [String, Number],
    modelValue: [String, Number],
    placeholder: [String, Number]
})
</script>

<style></style>
