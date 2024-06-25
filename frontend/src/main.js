import { createApp } from "vue"
import { createPinia } from "pinia"
import axios from "axios"

import App from "./App.vue"
import router from "./router"

import "./assets/main.css"

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount("#app")

//set the default base URL for axios calls
axios.defaults.baseURL = "http://localhost:8000/api" // "http://task-manager.test/api" // process.env.baseURL || process.env.apiUrl || ""
axios.defaults.withCredentials = true // process.env.baseURL || process.env.apiUrl || ""
axios.defaults.withXSRFToken = true
axios.get("../sanctum/csrf-cookie").then(async () => {
    //Logic to handle login
})
axios.interceptors.response.use(
    (response) => {
        return response
    },
    (error) => {
        //when csrf token is not matching
        if (error.response.status === 419) {
            router.go()
        }

        //when no user sessions of found
        if (error.response.status === 401) {
            console.log("======= route to login screen")
            router.push("/login")
        }
        return error
    }
)
