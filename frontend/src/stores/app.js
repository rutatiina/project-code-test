import { ref } from "vue"
import { defineStore } from "pinia"
import * as TaskServices from "@/services/TaskServices"

export const useTaskManagerStore = defineStore("taskManager", () => {
    const tasks = ref([])
    const tags = ref([])
    const projects = ref([])
    const categories = ref([])
    const priorities = ref([])
    const statuses = ref([])
    const users = ref([])
    const members = ref([])
    const taskMembers = ref([])
    const user = ref({})
    var apiResponse = ref({})

    const taskRecord = ref({
        project_id: "",
        name: "",
        color: "bg-purple-600",
        priority_id: "",
        category_id: "",
        status_id: "",
        start_date: "",
        end_date: "",
        description: "",
        member_user_ids: []
    })

    //fetch tasks
    function tasksFetch() {
        TaskServices.Fetch().then((response) => {
            if (response.status == "success") {
                tasks.value = response.data
            }
        })
    }

    function tasksDelete(task) {
        TaskServices.Delete(task.id).then(() => {
            tasksFetch()
        })
    }

    return {
        tasks,
        tags,
        projects,
        categories,
        priorities,
        statuses,
        users,
        members,
        user,
        taskRecord,
        taskMembers,
        apiResponse,

        tasksFetch,
        tasksDelete
    }
})
