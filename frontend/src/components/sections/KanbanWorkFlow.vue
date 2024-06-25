<template>
    <div class="w-full h-20 border-b flex items-center justify-between px-4">
        <div class="flex space-x-4 items-center">
            <Icons.MagnifyingGlassIcon class="w-5 h-5" />
            <!-- <input placeholder="Search for something" class="h-full bg-lime-50 h-12" @keyup="searchCard($event)"> -->
            <Input placeholder="Search for something" />
        </div>
        <div class="flex items-center space-x-2">
            <!-- switch -->
            <div class="flex items-center space-x-2 relative text-sm">
                <Switch
                    v-model="enabled"
                    :class="enabled ? 'bg-lime-400' : 'bg-black'"
                    class="relative inline-flex h-[30px] w-[55px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
                    <span class="sr-only">Use setting</span>
                    <span
                        aria-hidden="true"
                        :class="enabled ? 'translate-x-[25px]' : 'translate-x-0'"
                        class="pointer-events-none inline-block h-[26px] w-[26px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" />
                </Switch>
                <span>Select Timeframe</span>
                <Icons.ChevronUpIcon class="w-4" />
                <!-- Calendar -->
                <Calendar v-if="enabled" />
            </div>

            <Button
                label="Share"
                icon="ShareIcon"
                color="bg-transparent"
                size="md"
                drop-down />
        </div>
    </div>

    <div class="w-full h-full flex overflow-auto">
        <div
            class="w-1/4 border-r flex-shrink-0 text-xs overflow-auto"
            v-for="status in statuses"
            ref="statusRefs"
            :key="status.id"
            @drop="onDragEnter(status)"
            @dragover.prevent
            @dragenter.prevent>
            <div class="text-center font-bold pt-4 uppercase">{{ status.name }}</div>
            <!-- <Card draggable="true" @dragstart="hide(`card-${status.name}`)" :id="`card-${status.name}`" v-for="card in cards.filter((el) => el.status === status.name)" /> -->
            <TransitionGroup
                name="task"
                tag="div">
                <div
                    class="m-2 p-5 bg-white rounded-lg h-auto border shadow"
                    draggable="true"
                    @dragstart="dragStart(task)"
                    :id="task.id"
                    v-for="(task, key) in tasks.filter((el) => el.status.slug === status.slug)"
                    :key="'task-' + task.id">
                    <div class="flex items-center justify-between">
                        <div class="flex -space-x-4">
                            <div
                                class="h-10 w-10 rounded-full border-2 border-slate-500 text-center inline-block align-middle"
                                :style="`background-image: url('https://picsum.photos/200/300?random=` + member.id + `')`"
                                v-for="member in task.members"
                                :key="member.id"></div>
                        </div>
                        <div class="w-auto px-2 bg-rose-200 text-rose-600 py-1 rounded-full font-bold capitalize">{{ task.priority.name }}</div>
                    </div>
                    <div class="font-bold text-sm my-3">{{ task.name }}</div>
                    <div class="flex items-center space-x-1 my-1 text-[10px]">
                        <div
                            v-for="tag in task.tags"
                            :class="'w-auto px-2 bg-blue-200 text-blue-600 py-0.5 rounded-full font-bold capitalize'">
                            {{ tag.name }}
                        </div>
                        <!-- <div class="w-auto px-2 bg-blue-200 text-blue-600 py-0.5 rounded-full font-bold capitalize">Prototype</div>
                    <div class="w-auto px-2 bg-green-200 text-green-600 py-0.5 rounded-full font-bold capitalize">Research</div>
                    <div class="w-auto px-2 bg-yellow-200 text-yellow-600 py-0.5 rounded-full font-bold capitalize">Testing</div> -->
                    </div>
                    <div class="flex items-center space-x-2 text-slate-500 font-semibold mt-4 text-[10px]">
                        <Icons.CalendarIcon class="h-5 stroke-2" />
                        <Icons.PencilSquareIcon
                            class="h-5 stroke-2 cursor-pointer"
                            @click="taskEdit(task)" />
                        <Icons.TrashIcon
                            class="h-5 stroke-2 cursor-pointer"
                            @click="taskDelete(task)" />
                        <span>{{ task.date }}</span>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </div>

    <Modal>
        <template v-slot:heading>Update Task</template>
        <template v-slot:form>
            <form class="grid grid-cols-2 gap-2 -space-y-0">
                <!-- Project filed -->
                <select
                    v-model="taskRecord.project_id"
                    placeholder="Select project"
                    class="col-span-2 w-full h-12 border p-2 rounded text-xs minimal"
                    :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.project_id == '' ? 'text-gray-400' : '']">
                    <option
                        value=""
                        disabled>
                        Please select Project
                    </option>
                    <option
                        v-for="project in projects"
                        :value="project.id">
                        {{ project.name }}
                    </option>
                </select>

                <!-- Project field error -->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.project_id"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.project_id">{{ error }}</div>
                </div>

                <!-- Name field -->
                <Input
                    v-model="taskRecord.name"
                    placeholder="Title"
                    class="col-span-2" />

                <!-- Name field error-->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.name"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.name">{{ error }}</div>
                </div>

                <span class="inline-block align-baseline text-gray-400 font-bold text-xs mb-0 pl-1">Start date</span>
                <span class="inline-block align-baseline text-gray-400 font-bold text-xs mb-0 pl-1">End date</span>
                <Input
                    v-model="taskRecord.start_date"
                    placeholder="Start Date"
                    class="mt-0"
                    type="date" />
                <Input
                    v-model="taskRecord.end_date"
                    placeholder="End Date"
                    type="date" />

                <!-- Name field error-->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.start_date"
                    class="col-span-1 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.start_date">{{ error }}</div>
                </div>
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.end_date"
                    class="col-span-1 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.end_date">{{ error }}</div>
                </div>

                <!-- start: category -->
                <select
                    v-model="taskRecord.category_id"
                    :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.category_id == '' ? 'text-gray-400' : '']">
                    <option
                        value=""
                        disabled>
                        Please select category
                    </option>
                    <option
                        v-for="category in categories"
                        :value="category.id">
                        {{ category.name }}
                    </option>
                </select>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.category_id"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.category_id">{{ error }}</div>
                </div>
                <!-- end: category field -->

                <!-- start: priorities -->
                <select
                    v-model="taskRecord.priority_id"
                    :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.priority_id == '' ? 'text-gray-400' : '']">
                    <option
                        value=""
                        disabled>
                        Please select priority
                    </option>
                    <option
                        v-for="priority in priorities"
                        :value="priority.id">
                        {{ priority.name }}
                    </option>
                </select>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.priority_id"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.priority_id">{{ error }}</div>
                </div>
                <!-- end: priorities field -->

                <!-- start: status -->
                <select
                    v-model="taskRecord.status_id"
                    :class="'col-span-2 w-full h-12 border p-2 rounded text-xs minimal ' + [taskRecord.status_id == '' ? 'text-gray-400' : '']">
                    <option
                        value=""
                        disabled>
                        Please select status
                    </option>
                    <option
                        v-for="status in statuses"
                        :value="status.id">
                        {{ status.name }}
                    </option>
                </select>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.status_id"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.status_id">{{ error }}</div>
                </div>
                <!-- end: status field -->

                <!-- start: taskMembers -->
                <div class="col-span-2 w-full">
                    <div class="font-bold text-sm my-2 ms-2 text-gray-500">Attached to: {{ taskMembers.length }}</div>
                    <ul class="list-decimal list-inside">
                        <li
                            class="text-sm mb-2 bg-gray-100 py-2 px-3 relative"
                            v-for="(member, key) in taskMembers"
                            :key="member.id">
                            {{ member.name }}

                            <button
                                @click.prevent="removeMember(member.id)"
                                type="button"
                                class="h-full px-1 text-sm font-bold border-s-2 border-red-500 text-red-500 absolute top-0 right-0">
                                <Icons.XMarkIcon class="h-5 stroke-2 cursor-pointer" />
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="col-span-2 w-full align-top flex">
                    <select
                        v-model="user"
                        placeholder="Select a color"
                        :class="'grow border h-full p-2 w-auto rounded text-xs border-2 border-gray-500 minimal-small ' + [Object.keys(user).length == 0 ? 'text-gray-400' : '']">
                        <option
                            :value="{}"
                            disabled>
                            Choose member to attach
                        </option>
                        <template
                            v-for="user in users"
                            :key="user.id">
                            <option
                                v-if="!taskRecord.member_user_ids.includes(user.id)"
                                :value="user">
                                {{ user.name }}
                            </option>
                        </template>
                    </select>
                    <button
                        @click.prevent="attachMember()"
                        type="button"
                        class="flex-none px-2 h-full ml-1 rounded text-sm font-bold border-2 border-gray-500"
                        value="Assign">
                        <Icons.PlusIcon class="h-5 stroke-2 cursor-pointer" />
                    </button>
                </div>

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.member_user_ids"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.member_user_ids">{{ error }}</div>
                </div>
                <!-- end: taskMembers field -->

                <!-- Description field -->
                <TextArea
                    v-model="taskRecord.description"
                    rows="5"
                    placeholder="Description"
                    class="col-span-2" />

                <!-- Description field Error -->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.description"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.description">{{ error }}</div>
                </div>

                <Button
                    @click.prevent="taskUpdate"
                    label="Edit / Update task"
                    icon="PlusIcon"
                    color="bg-lime-500 text-white col-span-2"
                    size="xl" />

                <div
                    v-if="apiResponse.data && apiResponse.status == 'error' && apiResponse.data.id"
                    class="col-span-2 text-xs text-red-500">
                    <div v-for="error in apiResponse.data.id">{{ error }}</div>
                </div>

                <!-- Task creation success message -->
                <div
                    v-if="apiResponse.data && apiResponse.status == 'success'"
                    class="col-span-2 text-s text-green-600 py-5">
                    Task updated successfully
                </div>
            </form>
        </template>
    </Modal>
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

let store = useTaskManagerStore()
const { tasks, tags, projects, categories, priorities, statuses, users, user, taskRecord, taskMembers, apiResponse } = storeToRefs(store)

const enabled = ref(false)

const searchCard = (e) => {
    // console.log(e.target.value)
    let filteredCards = cards.value.filter((card) => card.members.includes(parseInt(e.target.value)))
    if (e.target.value) cards.value = filteredCards
    console.log(cards.value)
}

const cards = ref([
    { id: 1, status: "doing", priority: "high", date: "5th October 2022 - 8th October 2022", members: [1, 2] },
    { id: 2, status: "done", priority: "high", date: "5th October 2022 - 8th October 2022", members: [1] },
    { id: 3, status: "to-do", priority: "medium", date: "5th October 2022 - 8th October 2022", members: [1, 2, 3] },
    { id: 4, status: "doing", priority: "low", date: "5th October 2022 - 8th October 2022", members: [1, 4, 5] },
    { id: 5, status: "doing", priority: "low", date: "5th October 2022 - 8th October 2022", members: [1, 3, 4, 5] },
    { id: 6, status: "to-do", priority: "low", date: "5th October 2022 - 8th October 2022", members: [1, 3, 4, 5] },
    { id: 7, status: "to-do", priority: "low", date: "5th October 2022 - 8th October 2022", members: [1, 4, 5] },
    { id: 8, status: "doing", priority: "high", date: "5th October 2022 - 8th October 2022", members: [1, 2, 3, 4, 5] },
    { id: 9, status: "done", priority: "high", date: "5th October 2022 - 8th October 2022", members: [1, 2, 3, 4, 5] },
    { id: 10, status: "verified", priority: "medium", date: "5th October 2022 - 8th October 2022", members: [1, 2, 3, 4, 5] },
    { id: 11, status: "refined", priority: "medium", date: "5th October 2022 - 8th October 2022", members: [1, 2, 3, 4, 5] }
])

const columns = ref(["to-do", "refined", "verified", "doing", "done"])

const statusRefs = ref([])
const taskDragged = ref([])

const dragStart = (task) => {
    taskDragged.value = task
}

StatusServices.Fetch().then((response) => {
    if (response.status == "success") {
        statuses.value = response.data
    }
})

TaskServices.Fetch().then((response) => {
    if (response.status == "success") {
        tasks.value = response.data
    }
})

function onDragEnter(status) {
    TaskServices.Update(taskDragged.value.id, { status_id: status.id }).then((response) => {
        if (response.status == "success") {
            // tasks.value = response.data

            //find the task being dragged and update its status
            var foundIndex = tasks.value.findIndex((t) => t.id == taskDragged.value.id)
            tasks.value[foundIndex].status_id = status.id
            tasks.value[foundIndex].status = status
        }
    })
}

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
