<script setup>
import { RouterView } from "vue-router"

const DBOpenRequest = window.indexedDB.open("task-master")
// console.log(DBOpenRequest)
</script>

<template>
    <RouterView />
</template>

<style scoped>
:root {
    @apply font-serif;
}
</style>
