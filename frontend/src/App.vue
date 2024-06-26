<script>
export default {
    name: "App", // Give the component a name for clarity
    created() {
        const DBOpenRequest = window.indexedDB.open("task-master")
        // Handle the database open request
        DBOpenRequest.onsuccess = (event) => {
            console.log("Database opened successfully!")
        }
        DBOpenRequest.onerror = (event) => {
            console.error("Error opening database:", event.error)
        }
    }
}
</script>
<template>
    <RouterView />
</template>
<style scoped>
:root {
    @apply font-serif;
}
</style>
