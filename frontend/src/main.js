import { createApp } from "vue"
import { createPinia } from "pinia"
import axios from "axios"

import App from "./App.vue"
import router from "./router"

import "./assets/main.css"

//set the default base URL for axios calls
axios.defaults.baseURL = "http://task-manager.test/api" // process.env.baseURL || process.env.apiUrl || ""

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount("#app")
