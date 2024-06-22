import axios from "axios"

export async function Store(record) {
    try {
        const response = await axios.post("/tasks", record)
        // Handle successful response (optional)
        return response.data
    } catch (error) {
        console.log(error)
        return {
            status: "error",
            data: { error }
        }
    }
}

export async function Fetch() {
    try {
        const response = await axios.get("/tasks")
        return response.data
    } catch (error) {
        console.log(error)
        return {
            status: "error",
            data: { error }
        }
    }
}
