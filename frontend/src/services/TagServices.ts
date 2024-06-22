import axios from "axios"

export async function Store(record) {
    try {
        const response = await axios.post("/tags", record)
        // Handle successful response (optional)
        console.log("Data submitted successfully:", response.data)
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
        const response = await axios.get("/tags")
        return response.data
    } catch (error) {
        console.log(error)
        return {
            status: "error",
            data: { error }
        }
    }
}
