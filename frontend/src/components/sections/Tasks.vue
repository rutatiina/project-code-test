<template>
    <div class="w-full h-20 border-b flex items-center justify-between px-4">
        <div class="flex w-full space-x-4 items-center mx-5 my-2">
            <Icons.MagnifyingGlassIcon class="w-5 h-5" />
            <!-- <input placeholder="Search for something" class="h-full bg-lime-50 h-12" @keyup="searchCard($event)"> -->
            <input
                v-model="taskFilter.search"
                @keyup="store.tasksFetch"
                class="w-full h-10 border-2 p-2 rounded text-sm"
                placeholder="Search for something" />

            <div class="relative">
                <input
                    v-model="taskFilter.start_date"
                    @change="store.tasksFetch"
                    id="stat-date"
                    name="stat-date"
                    type="date"
                    class="border-2 h-10 rounded block px-2.5 pb-2.5 pt-4 w-full text-sm appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder="" />
                <label
                    for="start-date"
                    class="absolute rounded-lg text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                    Start date
                </label>
            </div>

            <div class="relative">
                <input
                    v-model="taskFilter.end_date"
                    @change="store.tasksFetch"
                    id="stat-date"
                    name="stat-date"
                    type="date"
                    class="border-2 h-10 rounded block px-2.5 pb-2.5 pt-4 w-full text-sm appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder="" />
                <label
                    for="start-date"
                    class="absolute rounded-lg text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                    End date
                </label>
            </div>
        </div>
    </div>

    <div class="w-full h-full overflow-auto">
        <div class="relative">
            <table class="w-full text-sm text-left text-gray-900">
                <thead class="text-xs text-gray-700 uppercase bg-lime-300">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3">
                            Members
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3">
                            Name
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3">
                            Priority
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3">
                            Category
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="border-b-2"
                        v-for="(task, key) in tasks">
                        <th
                            scope="row"
                            class="px-6 py-4 whitespace-nowrap">
                            <div class="flex -space-x-4">
                                <div
                                    class="h-10 w-10 rounded-full border-2 border-slate-500 text-center inline-block align-middle"
                                    :style="`background-image: url('https://picsum.photos/200/300?random=` + member.id + `')`"
                                    v-for="member in task.members"
                                    :key="member.id"></div>
                            </div>
                        </th>
                        <td class="px-2 py-4 font-medium text-gray-900">
                            {{ task.name }}
                        </td>
                        <td class="px-6 py-4">
                            <!-- <div class="w-auto px-2 bg-rose-200 text-rose-600 py-1 rounded-full font-bold capitalize">{{ task.priority.name }}</div> -->
                            {{ task.priority.name }}
                        </td>
                        <td class="px-6 py-4">
                            <!-- <div class="w-auto px-2 bg-rose-200 text-rose-600 py-1 rounded-full font-bold capitalize">{{ task.category.name }}</div> -->
                            {{ task.category.name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2 text-slate-500 font-semibold text-[10px]">
                                <Icons.PencilSquareIcon
                                    class="h-5 stroke-2 cursor-pointer"
                                    @click="taskEdit(task)" />
                                <Icons.TrashIcon
                                    class="h-5 stroke-2 cursor-pointer"
                                    @click="taskDelete(task)" />
                                <span>{{ task.date }}</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, onMounted, provide } from "vue"
import { Switch } from "@headlessui/vue"
import { storeToRefs } from "pinia"
import * as Icons from "@heroicons/vue/24/outline"

import { useTaskManagerStore } from "@/stores/app"
import Modal from "../shared/Modal.vue"

import Button from "../shared/Button.vue"
import Input from "../shared/Input.vue"
import TextArea from "../shared/TextArea.vue"
import Fab from "../shared/Fab.vue"
import Tag from "../shared/Tag.vue"
import Card from "../shared/Card.vue"
import Calendar from "../shared/Calendar.vue"
import * as StatusServices from "../../services/StatusServices"
import * as TaskServices from "../../services/TaskServices"

const store = useTaskManagerStore()
const {
    tasks, //
    tags,
    projects,
    categories,
    priorities,
    statuses,
    users,
    user,
    taskRecord,
    taskMembers,
    apiResponse,
    taskFilter
} = storeToRefs(store)

TaskServices.Fetch().then((response) => {
    if (response.status == "success") {
        tasks.value = response.data
    }
})

//Edit task
const isOpen = ref(false)
provide("isOpenSideModal", isOpen)
function taskEdit(task) {
    taskRecord.value = task
    taskMembers.value = task.members
    isOpen.value = true
}

function attachMember() {
    taskMembers.value.push(user.value)
    taskRecord.value.member_user_ids = taskMembers.value.map((m) => m.id)
    user.value = {}
}

function removeMember(id) {
    taskMembers.value = taskMembers.value.filter((m) => m.id !== id)
    taskRecord.value.member_user_ids = taskMembers.value.map((m) => m.id)
    // delete taskMembers.value[key]
}

async function taskUpdate() {
    const response = await TaskServices.Update(taskRecord.value.id, taskRecord.value)
    apiResponse.value = response
}

async function taskDelete(task) {
    const index = tasks.value.indexOf(task)
    if (index > -1) {
        tasks.value.splice(index, 1)
        await store.tasksDelete(task)
    }
}

// onMounted(() => console.log(statusRefs.value))
</script>

<style scoped>
.task-enter-active,
.task-leave-active {
    transition: all 0.5s ease;
}
.task-enter-from,
.task-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}
</style>
