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

export async function Fetch(filter) {
    let s = new URLSearchParams(filter).toString()
    try {
        const response = await axios.get("/tasks?" + s)
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
        const response = await axios.patch("/tasks/" + id, data)
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

export async function Delete(id: number) {
    try {
        const response = await axios.delete("/tasks/" + id)
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

export async function Restore(id: number) {
    try {
        const response = await axios.patch("/tasks/" + id+'/restore', { deleted_at: null })
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