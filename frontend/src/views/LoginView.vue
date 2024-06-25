<template>
    <section class="bg-none">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border-2 md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-100 dark:border-gray-200">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <form
                        class=""
                        action="#">
                        <div class="text-center mb-6 text-2xl font-semibold text-gray-900 uppercase text-sm">Task manager</div>

                        <div class="relative">
                            <input
                                v-model="loginRequest.email"
                                type="email"
                                name="email"
                                id="email"
                                class="bg-gray-50 border-2 border-gray-300 text-gray-900 rounded-lg block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 rounded-lg border-1 border-gray-300 appearance-none dark:border-gray-400 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label
                                for="email"
                                class="absolute rounded-lg text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                Your Email
                            </label>
                        </div>

                        <div
                            v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.email"
                            class="text-xs text-red-500 mt-2">
                            <div v-for="error in apiResponse.data.email">{{ error }}</div>
                        </div>

                        <div class="relative mt-6">
                            <input
                                v-model="loginRequest.password"
                                type="password"
                                name="password"
                                id="password"
                                class="bg-gray-50 border-2 border-gray-300 text-gray-900 rounded-lg block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 rounded-lg border-1 border-gray-300 appearance-none dark:border-gray-400 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label
                                for="password"
                                class="absolute rounded-lg text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                Your Password
                            </label>
                        </div>

                        <div
                            v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.password"
                            class="text-xs text-red-500 mt-2">
                            <div v-for="error in apiResponse.data.password">{{ error }}</div>
                        </div>

                        <button
                            @click.prevent="login"
                            type="submit"
                            class="mt-6 w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Sign in
                        </button>

                        <div
                            v-if="apiResponse.data && apiResponse.status == 'success'"
                            class="col-span-2 text-s text-green-600 py-5">
                            Login successfully
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue"
import axios from "axios"
import router from "../router"

import { storeToRefs } from "pinia"
import { useTaskManagerStore } from "@/stores/app"
let store = useTaskManagerStore()
const { apiResponse } = storeToRefs(store)

const loginRequest = ref({
    email: "admin@projectcode.ug",
    password: "P@ssw0rd"
})

async function login() {
    try {
        const response = await axios.post("/login", loginRequest.value)
        // Handle successful response (optional)
        apiResponse.value = response.data

        if (response.data.status == "success") {
            router.push("/app")
        }
    } catch (error) {
        console.log(error)
        apiResponse.value = {
            status: "error",
            data: { error }
        }
    }
}

onMounted(async () => {
    // await axios.get("../sanctum/csrf-cookie").then((response) => {
    //     // Login...
    // })
})
</script>

<style scoped></style>
