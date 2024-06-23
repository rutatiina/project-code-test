import axios from "axios"

export async function Store(record) {
    try {
        const response = await axios.post("/users", record)
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
        const response = await axios.get("/users")
        return response.data
    } catch (error) {
        console.log(error)
        return {
            status: "error",
            data: { error }
        }
    }
}

export async function Update(id: number, data: object) {
    try {
        const response = await axios.patch("/users/" + id, data)
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
